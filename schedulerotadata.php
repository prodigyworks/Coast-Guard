<?php 
	include("system-db.php");
	
	start_db();
	
	header('Content-Type: application/json');
	
	$scheduleid = $_POST['scheduleid'];
	
	$sql = "SELECT A.*, B.fullname, B.member_id
			FROM {$_SESSION['DB_PREFIX']}scheduleitem A
			INNER JOIN {$_SESSION['DB_PREFIX']}members B
			ON B.member_id = A.userid
			WHERE scheduleid = $scheduleid";
	$result = mysql_query($sql);	

	$json = array();

	if($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$startdate = $member['startdate'];
			$enddate = $member['enddate'];
			
			if ($member['watch'] == "A") {
				$startdate .= " 00:00";
				$enddate .= " 11:59";
				$allDay = "false";
								
			} else if ($member['watch'] == "B") {
				$startdate .= " 12:00";
				$enddate .= " 23:59";
				$allDay = "false";
				
			} else {
				$startdate .= " 00:00";
				$enddate .= " 23:59";
				$allDay = "true";
			}
			
			$line = array(
					"id"				=> $member['id'], 
					"allDay"			=> $allDay,
					"className"			=> "eventcat_" . $member['member_id'],
					"start"				=> $startdate,
					"end"				=> $enddate,
					"title"				=> $member['fullname'],
					"scheduleid"		=> $member['scheduleid']
				);
				
			array_push($json, $line);
		}
		
	} else {
		logError($qry . " - " . mysql_error());
	}

	echo json_encode($json);
?>
