<?php
	include("system-db.php");
	
	start_db();
	
	$_SESSION['SESS_CLIENT_ID'] = $_POST['clientid'];
	$_SESSION['SESS_CLIENT_SITE_ID'] = $_POST['siteid'];
	
	header("location: index.php");
?>