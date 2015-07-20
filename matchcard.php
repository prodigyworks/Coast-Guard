<?php
	require_once('matchcardreport.php');
	
	$report = new MatchCardReport( 'P', 'mm', 'A4', $_GET['id']);
	$report->Output();
?>