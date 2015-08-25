<?php
	//Include database connection details
	require_once('system-db.php');
	
	start_db();
	
	$eventid = $_POST['eventid'];

	if ($eventid != "") {
		$qry = "DELETE FROM {$_SESSION['DB_PREFIX']}rotaitem 
				WHERE id = $eventid";
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " - " . mysql_error());
		}
	}
	
	mysql_query("COMMIT");
?>