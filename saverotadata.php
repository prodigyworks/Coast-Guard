<?php
	//Include database connection details
	require_once('system-db.php');
	require_once("sqlprocesstoarray.php");
	
	start_db();
	
	$userid = $_POST['userid'];
	$rotaid = $_POST['rotaid'];
	$startdate = convertStringToDate($_POST['startdate']);
	$enddate = convertStringToDate($_POST['enddate']);
	$notes = mysql_escape_string($_POST['notes']);
	
	if ($_POST['eventid'] == "") {
		$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}rotaitem 
				(
					rotaid, userid, startdate, enddate, notes
				)
				VALUES
				(
					$rotaid, $userid, '$startdate', '$enddate', '$notes'
				)";
			$result = mysql_query($qry);
			
			if (! $result) {
				logError($qry . " - " . mysql_error());
			}
			
			$id = mysql_insert_id();
		
	} else {
		$id = $_POST['eventid'];
		$qry = "UPDATE {$_SESSION['DB_PREFIX']}rotaitem SET 
				startdate = '$startdate',
				enddate = '$enddate',
				notes = '$notes'
				WHERE id = $id";
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " - " . mysql_error());
		}
	}
	
	$sql = "SELECT A.*, B.fullname
			FROM {$_SESSION['DB_PREFIX']}rotaitem A
			INNER JOIN {$_SESSION['DB_PREFIX']}members B
			ON B.member_id = A.userid
			WHERE A.id = $id";
	$json = new SQLProcessToArray();
	
	echo json_encode($json->fetch($sql));
?>