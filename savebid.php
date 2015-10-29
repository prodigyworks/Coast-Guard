<?php
	//Include database connection details
	require_once('system-db.php');
	
	start_db();
	
	logError("TET 1", false);
	
	$bid = $_POST['bid'];
	$memberid = getLoggedOnMemberID();
	
	$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}bid
			(
				bid, memberid
			)
			VALUES
			(
				$bid, $memberid
			)";
	$result = mysql_query($qry);
	
	if (! $result) {
		if (mysql_errno() == 1062) {
			$qry = "UPDATE {$_SESSION['DB_PREFIX']}bid SET 
					bid = $bid 
					WHERE memberid = $memberid";
			$result = mysql_query($qry);
			
			if (! $result) {
				logError($qry . " - " . mysql_error());
			}
			
		} else {
			logError($qry . " - " . mysql_error());
		}
	}
	
	mysql_query("COMMIT");
	
	array_push($json, array("bid" => $bid));
	
	echo json_encode($json); 
?>