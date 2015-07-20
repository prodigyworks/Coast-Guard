<?php
	require_once('system-db.php');
	require_once('pdfreport.php');
	require_once("simple_html_dom.php");
	
	class RefereeReport extends PDFReport {
		function newPage() {
			$this->Image("images/logomain2.png", 165.6, 1);
			
			$size = $this->addText( 10, 13, "Referee Average Score Report", 12, 4, 'B') + 5;
			$this->SetFont('Arial','', 6);
				
			$cols = array( 
					"Referee"  => 120,
					"Games"  => 35,
					"Average Score"  => 35
				);
			
			$this->addCols($size, $cols);

			$cols = array(
					"Referee"  => "L",
					"Games"  => "R",
					"Average Score"  => "R"
				);
			$this->addLineFormat( $cols);
			$this->SetY(30);
		}
		
		function AddPage($orientation='', $size='') {
			parent::AddPage($orientation, $size);
			
			$this->newPage();
		}
		
		function __construct($orientation, $metric, $size, $startdate, $enddate, $userid) {
			$dynamicY = 0;
			
	        parent::__construct($orientation, $metric, $size);
	        
	        $this->SetAutoPageBreak(true, 30);
	        
			$this->AddPage();
			
			try {
				$and = "";
				
				if ($startdate != "") {
					$and .= " AND A.matchdate >= '$startdate'  ";
				}
				
				if ($enddate != "") {
					$and .= " AND A.matchdate <= '$enddate'  ";
				}
				
				if ($userid != "0") {
					$and .= " AND A.refereeid = $userid   ";
				}
				
				$sql = "SELECT COUNT(*) AS matches, SUM(refereescore) AS score, 
					    B.name AS refereeename
						FROM {$_SESSION['DB_PREFIX']}matchdetails A 
						LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}referee B 
						ON B.id = A.refereeid 
						WHERE refereescore >= 0 $and
						GROUP BY B.name  
						ORDER BY B.name";
				$result = mysql_query($sql);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
						$line=array(
								"Referee"  => $member['refereeename'],
								"Games"  => $member['matches'],
								"Average Score"  => $member['score'] / $member['matches']
							);
							
						$this->addLine( $this->GetY(), $line );
					}
					
				} else {
					logError($sql . " - " . mysql_error());
				}
				
			} catch (Exception $e) {
				logError($e->getMessage());
			}
		}
	}
	
	start_db();
	
	$pdf = new RefereeReport( 'P', 'mm', 'A4', convertStringToDate($_POST['datefrom']), convertStringToDate($_POST['dateto']), $_POST['refereeid']);
	$pdf->Output();
?>