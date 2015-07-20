<?php 
	include("system-db.php");
	
	start_db();
	
	header('Content-Type: application/json');
	
	$scheduleid = $_POST['scheduleid'];
	
	if (isUserInRole("ADMIN")) {
		$sql = "SELECT A.*, B.fullname, B.member_id
				FROM {$_SESSION['DB_PREFIX']}scheduleitem A
				INNER JOIN {$_SESSION['DB_PREFIX']}members B
				ON B.member_id = A.userid
				WHERE scheduleid = $scheduleid";
		
	} else {
		$sql = "SELECT A.*, B.fullname, B.member_id
				FROM {$_SESSION['DB_PREFIX']}scheduleitem A
				INNER JOIN {$_SESSION['DB_PREFIX']}members B
				ON B.member_id = A.userid
				WHERE scheduleid = $scheduleid
				AND B.member_id = " . getLoggedOnMemberID();
	}
	
	$result = mysql_query($sql);	

	$json = array();

	if($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$line = array(
					"id"				=> $member['id'], 
					"allDay"			=> "true",
					"className"			=> "eventcat_" . $member['member_id'],
					"start"				=> $member['startdate'],
					"end"				=> $member['enddate'],
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
