<?php
	include("system-db.php");
	
	start_db();
	
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
		
		$line1 = rand(0, count($users) - 1);
		$line2 = rand(0, count($users) - 1);

		while ($line1 == $line2) {
			$line2 = rand(0, count($users) - 1);
		}
		
		array_push($randomusers, $users[$line1]);
		array_push($randomusers, $users[$line2]);
		
		return $randomusers;
	}
	
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
			WHERE startdate = '$startDate'
			AND enddate = '$endDate'";
	$result = mysql_query($sql);
	
	if (! $result) {
		logError($sql . " - " . mysql_error());
	}
	
	$date = $startDate;
	$sql = "INSERT INTO {$_SESSION['DB_PREFIX']}schedule
			(
				startdate, enddate
			)
			VALUES
			(
				'$startDate', '$endDate'
			)";
	$result = mysql_query($sql);
	
	if (! $result) {
		logError($sql . " - " . mysql_error());
	}
	
	$scheduleid = mysql_insert_id();
	
	while (strtotime($date) < strtotime($endDate)) {
		$sql = "SELECT A.*, B.sex, B.certified 
				FROM {$_SESSION['DB_PREFIX']}rotaitem A
				INNER JOIN {$_SESSION['DB_PREFIX']}members B
				ON B.member_id = A.userid
				WHERE A.rotaid = $rotaid 
				AND A.startdate <= '$date' 
				AND A.enddate >= '$date'";
		$result = mysql_query($sql);
		
		logError($sql, false);
		
		if ($result) {
			$users = array();
			
			while (($member = mysql_fetch_assoc($result))) {
				$line = array(
						"id"		=> $member['id'],
						"userid"	=> $member['userid'],
						"sex"		=> $member['sex'],
						"date"		=> $date,
						"certified"	=> $member['certified']
					);
					
				array_push($users, $line);
			}
			
			$chosen = getRandomSelection($users);
			
			if (count($chosen) == 2) {
				foreach ($chosen as $user) {
					$userid = $user['userid'];
					$sql = "INSERT INTO {$_SESSION['DB_PREFIX']}scheduleitem
							(
								scheduleid, startdate, enddate, userid
							)
							VALUES
							(
								$scheduleid, '$date', '$date', $userid
							)";
					$itemresult = mysql_query($sql);
					
					if (! $itemresult) {
						logError($sql . " - " . mysql_error());
					}
				}
			}
			
		} else {
			logError($sql . " - " . mysql_error());
		}
		
	 	$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	
	header("location: rotaconfirmed.php?id=" . $_GET['id']);
?>
