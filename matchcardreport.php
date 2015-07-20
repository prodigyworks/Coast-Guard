<?php
	require_once('system-db.php');
	require_once('pdfreport.php');
	require_once("simple_html_dom.php");

	class MatchCardReport extends PDFReport {
		private $headermember = null;
        private $margin = 5;
			
		
		function newPage() {
            $this->AddPage();;
			$this->addText( 73, 7, "Match Result Form", 16, 4, 'B') + 5;
			
			if ($this->headermember['age'] > 11) {
				$this->Image("images/logo_over11.png", 180, 1);
				
			} else {
				$this->Image("images/logo_under12.png", 175, 1);
			}
			
	        $this->Line( 15, 19 + $this->margin, 195, 19 + $this->margin);
	        $this->Line( 15, 38.5 + $this->margin, 195, 38.5 + $this->margin);
            $this->Line( 15, 50 + $this->margin, 195, 50 + $this->margin);
            $this->Line( 15, 150 + $this->margin, 195, 150 + $this->margin);
			
            $this->addText( 15, 19, "Confirmation No", 10, 4, 'B');
			$this->addText( 45, 19, $this->headermember['id'], 10, 4, '');
			$this->addText( 85, 19, "Club", 10, 4, 'B');
			$this->addText( 110, 19, $this->headermember['submittedteamname'], 10, 4, '');
			
			if ($this->headermember['leaguecup'] == "L") {
				$leaguecup = "League";
				
			} else if ($this->headermember['leaguecup'] == "C") {
				$leaguecup = "Challenge Cup";
				
			} else if ($this->headermember['leaguecup'] == "X") {
				$leaguecup = "Cup";
				
			} else if ($this->headermember['leaguecup'] == "T") {
				$leaguecup = "Challenge Trophy";
			}
			
			$this->addText( 15, 20 + $this->margin, "Date of Match", 10, 4, 'B');
			$this->addText( 45, 20 + $this->margin, $this->headermember['matchdate'], 10, 4, '');
			$this->addText( 85, 20 + $this->margin, "Age Group", 10, 4, 'B');
			$this->addText( 110, 20 + $this->margin, $this->headermember['agegroupname'], 10, 4, '');
			$this->addText( 145, 20 + $this->margin, $leaguecup, 10, 4, 'B');
			
			$hometeam = $this->headermember['hometeam'];
			$awayteam = $this->headermember['opposition'];

			$this->addText( 15, 27 + $this->margin, "Home Team", 10, 4, 'B');
			$this->addText( 45, 27 + $this->margin, $hometeam, 10, 4, '');
			$this->addText( 85, 27 + $this->margin, "Goals", 10, 4, 'B');
			$this->addText( 110, 27 + $this->margin, $this->headermember['hometeamscore'], 10, 4, '');

			$this->addText( 15, 34 + $this->margin, "Away Team", 10, 4, 'B');
			$this->addText( 45, 34 + $this->margin, $awayteam, 10, 4, '');
			$this->addText( 85, 34 + $this->margin, "Goals", 10, 4, 'B');
			$this->addText( 110, 34 + $this->margin, $this->headermember['awayteamscore'], 10, 4, '');
			
			$this->addText( 15, 40 + $this->margin, "The following players represented", 10, 4, '');
			$this->addText( 15, 45 + $this->margin, $this->headermember['submittedteamname'], 10, 4, 'B');
			
			$this->addText( 25, 53 + $this->margin, "PLAYERS FULL FIRST AND SURNAME", 10, 4, 'B');
			$this->addText( 110, 53 + $this->margin, "REGISTRATION NUMBER", 10, 4, 'B');
			
			return $this->GetY();
		}
		
		function getRateDescription($rating) {
		    if ($rating == "E") return "Excellent";
		    if ($rating == "G") return "Good";
		    if ($rating == "F") return "Fair";
		    if ($rating == "P") return "Poor";
		}		
		function __construct($orientation, $metric, $size, $id) {
			$dynamicY = 0;

			start_db();
				
	        parent::__construct($orientation, $metric, $size);
			
			try {
				$sql =  "SELECT 
						 A.*, 
						 DATE_FORMAT(A.matchdate, '%d/%m/%Y') AS matchdate,
						 B.name AS refereename, C.name AS submittedteamname, D.name AS oppositionname, 
						 E.name AS agegroupname, E.age
						 FROM  {$_SESSION['DB_PREFIX']}matchdetails A
						 LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}referee B
						 ON B.id = A.refereeid
						 LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}team C
						 ON C.id = A.teamid
						 LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}team D
						 ON D.id = A.oppositionid
						 LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}teamagegroup E
						 ON E.id = A.agegroupid
						 WHERE A.id = $id";
				$result = mysql_query($sql);
				
				if ($result) {
					while (($this->headermember = mysql_fetch_assoc($result))) {
						$dynamicY = $this->newPage() + 2;
						
						$sql = "SELECT A.*, B.firstname, B.lastname, B.registrationnumber
								FROM {$_SESSION['DB_PREFIX']}matchplayerdetails A 
								INNER JOIN {$_SESSION['DB_PREFIX']}player B 
								ON B.id = A.playerid
								WHERE A.matchid = $id 
								ORDER BY B.firstname, B.lastname";
						$itemresult = mysql_query($sql);
						$index = 1;
						
						if ($itemresult) {
							while (($itemmember = mysql_fetch_assoc($itemresult))) {
								$this->addText( 15, $dynamicY, $index++, 10, 4, '');
								$this->addText( 25, $dynamicY, $itemmember['firstname'] . " " . $itemmember['lastname'], 10, 4, '');
								$dynamicY = $this->addText( 110, $dynamicY, $itemmember['registrationnumber'], 10, 4, '');
							}
							
						} else {
							logError($qry . " - " . mysql_error());
						}

						$dynamicY = 153 + $this->margin;
						$dynamicY = $this->addText( 15, $dynamicY, "CLUB / MANAGERS REPORT (Including the FA Respect Codes of Conduct)", 10, 4, 'B') + 2;
						
						if ($this->headermember['age'] < 12) {
    						$this->addText( 15, $dynamicY, "Referee", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, $this->getRateDescription($this->headermember['ratereferee']), 10, 4, '') + 1;
    						
    						$this->addText( 15, $dynamicY, "Opponent players", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, $this->getRateDescription($this->headermember['rateplayers']), 10, 4, '') + 1;
    						
    						$this->addText( 15, $dynamicY, "Opponent Management", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, $this->getRateDescription($this->headermember['ratemanagement']), 10, 4, '') + 1;
    											
    						$this->addText( 15, $dynamicY, "Opponent Spectators", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, $this->getRateDescription($this->headermember['ratespectators']), 10, 4, '') + 1;
    						
    						$this->addText( 15, $dynamicY, "Pitch Size", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, $this->getRateDescription($this->headermember['ratepitchsize']), 10, 4, '') + 1;
    						
    						$this->addText( 15, $dynamicY, "Pitch Condition", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, $this->getRateDescription($this->headermember['ratepitchcondition']), 10, 4, '') + 1;
    						
    						$this->addText( 15, $dynamicY, "Goal Size", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, $this->getRateDescription($this->headermember['rategoalsize']), 10, 4, '') + 1;
    											
    						$this->addText( 15, $dynamicY, "Changing Rooms", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, $this->getRateDescription($this->headermember['ratechangingrooms']), 10, 4, '') + 1;

						} else {
							$this->addText( 15, $dynamicY, "Did the pitch have the required barriers, cones and markings", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, ($this->headermember['requiredbarriers']) == 1 ? "Yes" : "No", 10, 4, '') + 1;
    					
							$this->addText( 15, $dynamicY, "Was the pitch size/condition, goals and changing rooms adequate", 10, 4, '');
    						$dynamicY = $this->addText( 150, $dynamicY, ($this->headermember['pitchsize'] == 1) ? "Yes" : "No", 10, 4, '') + 1;
						}
						
    					$this->addText( 15, $dynamicY, "Did your opponent players, management and spectators comply with the Codes", 10, 4, '');
    					$dynamicY = $this->addText( 150, $dynamicY, ($this->headermember['complycodes'] == 1) ? "Yes" : "No", 10, 4, '') + 1;
											
    					$this->addText( 15, $dynamicY, "Did you check your opponent players ID cards", 10, 4, '');
    					$dynamicY = $this->addText( 150, $dynamicY, ($this->headermember['opponentids'] == 1) ? "Yes" : "No", 10, 4, '') + 1;						
												
			            $this->Line( 15, $dynamicY, 195, $dynamicY);

			            $dynamicY = $this->addText( 15, $dynamicY + 2, "REFEREE SECTION", 10, 4, 'B') + 3;
						
						$this->addText( 15, $dynamicY, "Referee", 10, 4, 'B');
						$this->addText( 45, $dynamicY, $this->headermember['referee'], 10, 4, '');
							
						if ($this->headermember['refereescore'] > 0) {
							$this->addText( 105, $dynamicY, "Appointed by League", 10, 4, 'B');
							$dynamicY = $this->addText( 145, $dynamicY, ($this->headermember['refappointedbyleague'] == "Y") ? "Yes" : "No", 10, 4, '') + 3;
						
							$this->addText( 15, $dynamicY, "Marks out of 100", 10, 4, 'B');
							$dynamicY = $this->addText( 45, $dynamicY, $this->headermember['refereescore'], 10, 4, '') + 3;
							
							$this->addText( 15, $dynamicY, "Remarks", 10, 4, 'B');
							$dynamicY = $this->addText( 45, $dynamicY , $this->headermember['refereeremarks'], 10, 4, '', 150) + 2;

						} else {
							$dynamicY = $this->addText( 145, $dynamicY, " ", 10, 4, '') ;
						}
						
					    $this->Line( 15, $dynamicY, 195, $dynamicY);
					    
						$dynamicY = $this->addText( 15, $dynamicY + 3, "SIGNED", 10, 4, 'B') + 2;
						$this->DynamicImage($this->headermember['imageid'], 15, $dynamicY);
					}
					
					
				} else {
					logError($sql . " - " . mysql_error());
				}
				
			} catch (Exception $e) {
				logError($e->getMessage());
			}
			
			$this->AliasNbPages();
		}
	}
?>