<?php
	//Include database connection details
	require_once('system-db.php');
	
	start_db();
	
	$scheduleid = $_POST['scheduleid'];
	
	$sql = "SELECT C.member_id 
			FROM {$_SESSION['DB_PREFIX']}scheduleitem A
			INNER JOIN {$_SESSION['DB_PREFIX']}rotaitem B
			ON 1 = 1
			INNER JOIN {$_SESSION['DB_PREFIX']}members C
			ON C.member_id = B.userid
			WHERE A.id = $scheduleid
			AND B.startdate <= A.startdate
			AND B.enddate >= A.enddate
			AND B.userid != A.userid";
	$result = mysql_query($sql);	
	$users = array();
	
	
	if($result) {
		while (($member = mysql_fetch_assoc($result))) {
			array_push($users, $member['member_id']);
		}
		
	} else {
		logError($sql . " - " . mysql_error());
	}
	
	$in = ArrayToInClause($users);
	logError($sql, false);
		logError($in, false);
	
	createComboOptions("member_id", "fullname", "{$_SESSION['DB_PREFIX']}members", "WHERE member_id IN($in)", false);
?>