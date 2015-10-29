<?php
	include("system-db.php");
	
	start_db();
			
	/* Start of confirmation. */
	$rotaid = $_GET['id'];
	$sql = "SELECT startdate, enddate 
			FROM {$_SESSION['DB_PREFIX']}rota 
			WHERE id = $rotaid";
	$result = mysql_query($sql);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$startDate = $member['startdate'];
			$endDate = $member['enddate'];
		}
	}
	
	$sql = "DELETE FROM {$_SESSION['DB_PREFIX']}schedule
			WHERE rotaid = $rotaid";
	$result = mysql_query($sql);
	
	if (! $result) {
		logError($sql . " - " . mysql_error());
	}
	
	$sql = "DELETE FROM {$_SESSION['DB_PREFIX']}scheduleitem
			WHERE scheduleid NOT IN (SELECT id FROM {$_SESSION['DB_PREFIX']}schedule)";
	$result = mysql_query($sql);
	
	if (! $result) {
		logError($sql . " - " . mysql_error());
	}
	
	$date = $startDate;
	$sql = "INSERT INTO {$_SESSION['DB_PREFIX']}schedule
			(
				startdate, enddate, rotaid
			)
			VALUES
			(
				'$startDate', '$endDate', $rotaid
			)";
	$result = mysql_query($sql);
	
	if (! $result) {
		logError($sql . " - " . mysql_error());
	}
	
	$scheduleid = mysql_insert_id();
	$available = array();
	
	while (strtotime($date) < strtotime($endDate)) {
		array_push($available, calculate($rotaid, $date, "A"));
		array_push($available, calculate($rotaid, $date, "B"));
		
	 	$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	
	$schedule = array();
	
	foreach ($available as $entry) {
		if (count($entry) == 0) {
			continue;
		}
		
		$users = array();
		
		for ($i = 0; $i < 100; $i++) {
			/* Limit to 100 attempts. */
			$users = getRandomSelection($entry);
			$drop = false;
			
			if (count($users) < 2) {
				continue;
			}
			
			foreach ($users as $user) {
				if (userExceededBid($user, $schedule)) {
					$drop = true;
					break;
				}
				
				if (userOnCallWithin5Days($user, $schedule)) {
					$drop = true;
					break;
				}
			}
			
			if ($drop) {
				$users = array();
				continue;
			}
			
			break;
		}
		
		/* Add these users. */
		foreach ($users as $user) {
			array_push($schedule, $user);
		}
	}
	
	foreach ($schedule as $item) {
		$userid = $item['userid'];
		$date = $item['date'];
		$watch = $item['watch'];
		
		$sql = "INSERT INTO {$_SESSION['DB_PREFIX']}scheduleitem
				(
					scheduleid, startdate, enddate, userid, watch
				)
				VALUES
				(
					$scheduleid, '$date', '$date', $userid, '$watch'
				)";
		$itemresult = mysql_query($sql);
		
//		echo "<div>$sql</div>";
		
		if (! $itemresult) {
			logError($sql . " - " . mysql_error());
		}
	}
	
	mysql_query("COMMIT");
	
	header("location: scheduleplanner.php?id=$scheduleid");

	function getUserBid($memberid) {
		$bid = 5;
		$qry = "SELECT bid 
				FROM {$_SESSION['DB_PREFIX']}bid 
				WHERE memberid = $memberid";
		$result = mysql_query($qry);
		
		//Check whether the query was successful or not
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$bid = $member['bid'];
			}
			
		} else {
			logError($qry . " - " . mysql_error());
		}
		
		return $bid;
	}

	function userOnCallWithin5Days($user, $schedule) {
		$bid = getUserBid($user['userid']);
		$prev = "1972-01-01";
		
		foreach ($schedule as $item) {
			if ($user['userid'] == $item['userid']) {
				$prev = $item['date'];
			}
		}
		
		$date1 = new DateTime($user['date']);
		$date2 = new DateTime($prev);
	
		$diff = $date2->diff($date1)->format("%a");

		if ($diff < 5) {
			return true;
		}
		
		return false;
	}

	function userExceededBid($user, $schedule) {
		$bid = getUserBid($user['userid']);
		$bids = 0;
		
		foreach ($schedule as $item) {
			if ($user['userid'] == $item['userid']) {
				$bids++;
			}
		}
		
		if ($bids >= $bid) {
			return true;
		}
		
		return false;
	}
	
	function calculate($rotaid, $date, $watch) {
		$users = array();
		$sql = "SELECT A.*, B.sex, B.certified 
				FROM {$_SESSION['DB_PREFIX']}rotaitem A
				INNER JOIN {$_SESSION['DB_PREFIX']}members B
				ON B.member_id = A.userid
				WHERE A.rotaid = $rotaid 
				AND A.watch IN ('E', '$watch')
				AND A.startdate <= '$date' 
				AND A.enddate >= '$date'";
		$result = mysql_query($sql);
		
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$duplicate = false;
				$line = array(
						"id"		=> $member['id'],
						"userid"	=> $member['userid'],
						"sex"		=> $member['sex'],
						"watch"		=> $watch,
						"date"		=> $date,
						"certified"	=> $member['certified']
					);
					
				if (! $duplicate) {
					array_push($users, $line);
				}
			}
			
		} else {
			logError($sql . " - " . mysql_error());
		}
		
		return $users;
	}

	function getRandomSelection($users) {
		$womenonly = true;
		$certified = false;
		
		foreach ($users as $user) {
			if ($user['sex'] == "M") {
				$womenonly = false;
			}
			
			if ($user['certified'] == "Y") {
				$certified = true;
			}
		}
		
		if ($womenonly || ! $certified || count($users) < 2) {
			return array();
		}
		
		$randomusers = array();
		

		for (; ;) {
			$line1 = rand(0, count($users) - 1);
			$line2 = rand(0, count($users) - 1);
			
			if ($line1 == $line2) {
				continue;
			}
			
			if ($users[$line1]['sex'] == "F" && $users[$line2]['sex'] == "F") {
				continue;
			}
			
			if ($users[$line1]['certified'] == "N" && $users[$line2]['certified'] == "N") {
				continue;
			}
			
			break;
		}
		
		array_push($randomusers, $users[$line1]);
		array_push($randomusers, $users[$line2]);
		
		return $randomusers;
	}
//	
//	function calculate2($scheduleid, $rotaid, $date, $startDate, $endDate, $watch, $existingusers) {
//		$users = array();
//		$sql = "SELECT A.*, B.sex, B.certified 
//				FROM {$_SESSION['DB_PREFIX']}rotaitem A
//				INNER JOIN {$_SESSION['DB_PREFIX']}members B
//				ON B.member_id = A.userid
//				WHERE A.rotaid = $rotaid 
//				AND A.watch IN ('E', '$watch')
//				AND A.startdate <= '$date' 
//				AND A.enddate >= '$date'";
//		$result = mysql_query($sql);
//		
//		if ($result) {
//			while (($member = mysql_fetch_assoc($result))) {
//				$duplicate = false;
//				$line = array(
//						"id"		=> $member['id'],
//						"userid"	=> $member['userid'],
//						"sex"		=> $member['sex'],
//						"watch"		=> $watch,
//						"date"		=> $date,
//						"certified"	=> $member['certified']
//					);
//					
//				foreach ($existingusers as $existinguser) {
//					if ($existinguser['userid'] == $line['userid']) {
//						$duplicate = true;
//					}
//				}
//					
//				if (! $duplicate) {
//					array_push($users, $line);
//				}
//			}
//			
//			$chosen = getRandomSelection($users, $existingusers);
//			
//			if (count($chosen) == 2) {
//				foreach ($chosen as $user) {
//					$userid = $user['userid'];
//					$watch = $user['watch'];
//					
//					$sql = "INSERT INTO {$_SESSION['DB_PREFIX']}scheduleitem
//							(
//								scheduleid, startdate, enddate, userid, watch
//							)
//							VALUES
//							(
//								$scheduleid, '$date', '$date', $userid, '$watch'
//							)";
//					$itemresult = mysql_query($sql);
//					
//					if (! $itemresult) {
//						logError($sql . " - " . mysql_error());
//					}
//				}
//			}
//			
//		} else {
//			logError($sql . " - " . mysql_error());
//		}
//		
//		return $chosen;
//	}
?>
