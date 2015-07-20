<?php
	require_once("system-db.php");
	
	start_db();
	
	if (isUserInRole("ADMIN")) {
		header("location: matchdetails.php");
		
	} else {
		header("location: match.php");
	}
?>