<?php 
	require_once("system-db.php"); 
	require_once('signature-to-image.php');
	require_once('matchcardreport.php');
	
	start_db();

	$matchdate = convertStringToDate($_POST['matchdate']); 
	$leaguecup = $_POST['leaguecup']; 
	$agegroupid = $_POST['agegroupid']; 
	$opposition = $_POST['oppositionid_lazy']; 
	$oppositionid = $_POST['oppositionid']; 
	$hometeam = $_POST['hometeamid_lazy']; 
	$hometeamid = $_POST['hometeamid']; 
	$hometeamscore = $_POST['hometeamscore']; 
	$awayteamscore = $_POST['awayteamscore']; 
	
	$ratereferee = $_POST['ratereferee']; 
	$rateplayers = $_POST['rateplayers']; 
	$ratemanagement = $_POST['ratemanagement']; 
	$ratespectators = $_POST['ratespectators']; 
	$ratepitchsize = $_POST['ratepitchsize']; 
	$ratepitchcondition = $_POST['ratepitchcondition']; 
	$rategoalsize = $_POST['rategoalsize']; 
	$ratechangingrooms = $_POST['ratechangingrooms']; 
	
	$complycodes = (isset($_POST['complycodes']) && $_POST['complycodes'] == "on") ? 1 : 0; 
	$pitchsize = (isset($_POST['pitchsize']) && $_POST['pitchsize'] == "on") ? 1 : 0; 
	$requiredbarriers = (isset($_POST['requiredbarriers']) && $_POST['requiredbarriers'] == "on") ? 1 : 0; 
	$opponentids = (isset($_POST['opponentids']) && $_POST['opponentids'] == "on") ? 1 : 0; 
	$refappointedbyleague = $_POST['refappointedbyleague']; 
	$refereeid = $_POST['refereeid']; 
	$referee = mysql_escape_string($_POST['refereeid_lazy']); 
	$division= mysql_escape_string($_POST['division']); 
	$refereescore = $_POST['refereescore']; 
	$remarks = mysql_escape_string($_POST['remarks']); 
	$refereeremarks = mysql_escape_string($_POST['refereeremarks']); 
	$teamid = getLoggedOnTeamID();
	$memberid = getLoggedOnMemberID();
	
	if ($refereeid == "") {
		$refereeid = 0;
	}
	
	if ($division == "") {
		$division = "X";
	}
	
	if ($oppositionid == "") {
		$oppositionid = 0;
	}
	
	if ($hometeamid == "") {
		$hometeamid = 0;
	}
	
	if ($leaguecup == "") {
		$leaguecup = "L";
	}
	
	try {
		$img = null;
		
		if (isset($_POST['output']) && $_POST['output'] != "") {
			$img = sigJsonToImage($_POST['output']);
		
		} else {
			// Create the image
			$img = imagecreatetruecolor(400, 30);
			
			// Create some colors
			$white = imagecolorallocate($img, 255, 255, 255);
			$grey = imagecolorallocate($img, 128, 128, 128);
			$black = imagecolorallocate($img, 0, 0, 0);
			imagefilledrectangle($img, 0, 0, 399, 29, $white);
			
			// The text to draw
			$text = $_POST['name'];
			// Replace path by your own font path
			$font = 'build/journal.ttf';
			
			// Add some shadow to the text
			imagettftext($img, 20, 0, 11, 21, $grey, $font, $text);
			
			// Add the text
			imagettftext($img, 20, 0, 10, 20, $black, $font, $text);
			
			// Using imagepng() results in clearer text compared with imagejpeg()
		}
		
		ob_start();
		imagepng($img);
		$imgstring = ob_get_contents(); 
        ob_end_clean();
		
		$escimgstring = mysql_escape_string($imgstring);
		$id = $_POST['signatureid'];
		
		$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}images " .
				"(" .
				"mimetype, name, image, createddate" .
				") " .
				"VALUES " .
				"(" .
				"'image/png', 'Match signture $id', '$escimgstring', NOW()" .
				")";
		$result = mysql_query($qry);
		$imageid = mysql_insert_id();
		
		file_put_contents("uploads/signature_" . $imageid . ".png", $imgstring);
		
		if (! $result) {
			logError($qry . " - " . mysql_error());
		}
		
		
		$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}matchdetails 
				(
					matchdate, teamid, imageid, leaguecup, agegroupid, 
					oppositionid, hometeamid, hometeam, hometeamscore, awayteamscore, complycodes, 
					pitchsize, requiredbarriers, opponentids, refereeid, 
					refappointedbyleague, opposition, referee, 
					refereescore, refereeremarks, remarks, division,
					ratereferee, rateplayers, ratemanagement, ratespectators,
					ratepitchsize, ratepitchcondition, rategoalsize, ratechangingrooms,
					metacreateduserid, metamodifieduserid, 
					metacreateddate, metamodifieddate
				)
				VALUES
				(
					'$matchdate', $teamid, $imageid, '$leaguecup', $agegroupid, 
					$oppositionid, $hometeamid, '$hometeam', $hometeamscore, $awayteamscore, $complycodes, 
					$pitchsize, $requiredbarriers, $opponentids, $refereeid, 
					'$refappointedbyleague', '$opposition', '$referee', 
					$refereescore, '$refereeremarks', '$remarks', '$division',
					'$ratereferee', '$rateplayers', '$ratemanagement', '$ratespectators',
					'$ratepitchsize', '$ratepitchcondition', '$rategoalsize', '$ratechangingrooms',
					$memberid, $memberid, NOW(), NOW()
				)";
	
		$result = mysql_query($qry);
		$matchid = mysql_insert_id();
	
		if (! $result) {
			logError($qry . " - " . mysql_error());
		}
		
		for ($i = 0; $i < count($_POST['player']); $i++) {
			$playerid = $_POST['player'][$i];
			
			$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}matchplayerdetails 
					(
						matchid, playerid, metacreateduserid, metamodifieduserid, 
						metacreateddate, metamodifieddate
					)
					VALUES
					(
						$matchid, $playerid, $memberid, $memberid, 
						NOW(), NOW()
					)";
		
			$result = mysql_query($qry);
		
			if (! $result) {
				logError($qry . " - " . mysql_error());
			}
		}
		
		$details = "Match report attached for match on " . $_POST['matchdate'];
		
		$file = "uploads/matchcard$id" . session_id() . ".pdf";
			
		$report = new MatchCardReport( 'P', 'mm', 'A4', $matchid);
		$report->Output($file, "F");	
		
    	sendTeamMessage($teamid, "Match Report Confirmed", $details, "", array($file));
    	sendRoleMessage("LEAGUE", "Match Report Confirmed", $details, "", array($file));
    	
    	if ($_POST['refereescore'] < 50 && $_POST['refereescore'] > 0) {
    		$refname = GetRefereeName($refereeid);
    		$refdetails = "Referee $refname has scored $refereescore<br><br>Report:<br>" . $_POST['refereeremarks'];
    		
    		smtpmailer(getSiteConfigData()->refereereportemail, "office@thefa.com", $_SESSION['SESS_TEAM_EMAIL'], "Referee Report", $refdetails);
    	}
    	
	} catch (Exception $e) {
		logError("Signing image: " . $e->getMessage());
	}
	
	mysql_query("COMMIT");
	
	header("location: matchconfirm.php?id=$matchid");

?>
		
