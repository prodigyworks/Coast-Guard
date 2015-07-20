<?php 
	include("system-db.php");
	
	start_db();
	
	header('Content-Type: application/json');
	
	$rotaid = $_POST['rotaid'];
	
	if (isUserInRole("ADMIN")) {
		$sql = "SELECT A.*, B.fullname, B.member_id
				FROM {$_SESSION['DB_PREFIX']}rotaitem A
				INNER JOIN {$_SESSION['DB_PREFIX']}members B
				ON B.member_id = A.userid
				WHERE rotaid = $rotaid";
		
	} else {
		$sql = "SELECT A.*, B.fullname, B.member_id
				FROM {$_SESSION['DB_PREFIX']}rotaitem A
				INNER JOIN {$_SESSION['DB_PREFIX']}members B
				ON B.member_id = A.userid
				WHERE rotaid = $rotaid
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
					"rotaid"			=> $member['rotaid']
				);
				
			array_push($json, $line);
		}
		
	} else {
		logError($qry . " - " . mysql_error());
	}

	echo json_encode($json);
?>
