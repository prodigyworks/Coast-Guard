<?php
	require_once("system-db.php");
	
	start_db();
	
	header('Content-Type: application/json');
	
	$json = array();
	$id = null;
	$status = "N";
	$enddate = $_POST['enddate'];
	$startdate = $_POST['startdate'];
	$startFound = false;
	$date = $startdate;
	
	while (strtotime($date) < strtotime($enddate)) {
		if (! $startFound) {
			if (date("d", strtotime($date)) == 6) {
				$startFound = true;
				$startdate = $date;
			}
		
		} else {
			if (date("d", strtotime($date)) == 5) {
				$enddate = $date;
				break;
			}
		}
		
	 	$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	
	$sql = "SELECT A.id, A.status
			FROM {$_SESSION['DB_PREFIX']}rota A
			WHERE A.startdate = '$startdate' 
			AND A.enddate = '$enddate'";
	logError($sql, false);
	$result = mysql_query($sql);	
	
	if($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$id = $member['id'];
			$status = $member['status'];
		}
		
	} else {
		logError($sql . " - " . mysql_error());
	}
	
	if ($id == null) {
		$description = "Test";
		$sql = "INSERT INTO {$_SESSION['DB_PREFIX']}rota
				(
					startdate, enddate, description, status
				)
				VALUES
				(
					'$startdate', '$enddate', '$description', 'N'
				)";
		$result = mysql_query($sql);	
		
		if (! $result) {
			logError($sql . " - " . mysql_error());
		}
		
		$id = mysql_insert_id();
	}
	
	$line = array(
			"id"				=> $id, 
			"start"				=> $startdate,
			"end"				=> $enddate,
			"description"		=> $description,
			"status"			=> $status,
			"rotaid"			=> $member['rotaid']
		);
		
	array_push($json, $line);
	
	echo json_encode($json);
?>