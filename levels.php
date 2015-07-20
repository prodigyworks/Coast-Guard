<?php
	require_once("system-db.php");
	require_once("crud.php");
	
	start_db();
	
	class TeamCrud extends Crud {
		
		/* Post header event. */
		public function postHeaderEvent() {
			createDocumentLink();
		}
		
		public function postScriptEvent() {
?>
			function age_onchange() {
				$("#editform #name").val("Under " + $(this).val());
			}
			
			function editDocuments(node) {
				viewDocument(node, "addleveldocument.php", node, "teamagegroupdocs", "agegroupid");
			}
	
<?php			
		}
	}
	
	if (isset($_GET['id'])) {
		$teamid = $_GET['id'];
		
	} else {
		$teamid = getLoggedOnTeamID();
	}
	
	$crud = new TeamCrud();
	$crud->dialogwidth = 650;
	$crud->title = "Teams";
	$crud->table = "{$_SESSION['DB_PREFIX']}teamagegroup";
	$crud->sql = "SELECT A.*, C.name AS clubname
				  FROM  {$_SESSION['DB_PREFIX']}teamagegroup A
				  INNER JOIN {$_SESSION['DB_PREFIX']}team C
				  ON C.id = A.teamid
				  WHERE A.teamid = $teamid
				  ORDER BY A.age";
	$crud->columns = array(
			array(
				'name'       => 'id',
				'viewname'   => 'uniqueid',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'bind' 	 	 => false,
				'editable' 	 => false,
				'pk'		 => true,
				'label' 	 => 'ID'
			),
			array(
				'name'       => 'clubname',
				'length' 	 => 18,
				'editable'	 => false,
				'bind'		 => false,
				'label' 	 => 'Club'
			),			
			array(
				'name'       => 'name',
				'length' 	 => 18,
				'hidden'	 => true,
				'label' 	 => 'Team'
			),			
			array(
				'name'       => 'age',
				'type'		 => 'COMBO',
				'onchange'	 => 'age_onchange',
				'showInView' => false,
				'options'    => array(
						array(
							'value'		=> '7',
							'text'		=> 'Under 7'
						),
						array(
							'value'		=> '8',
							'text'		=> 'Under 8'
						),
						array(
							'value'		=> '9',
							'text'		=> 'Under 9'
						),
						array(
							'value'		=> '10',
							'text'		=> 'Under 10'
						),
						array(
							'value'		=> '11',
							'text'		=> 'Under 11'
						),
						array(
							'value'		=> '12',
							'text'		=> 'Under 12'
						),
						array(
							'value'		=> '13',
							'text'		=> 'Under 13'
						),
						array(
							'value'		=> '14',
							'text'		=> 'Under 14'
						),
						array(
							'value'		=> '15',
							'text'		=> 'Under 15'
						),
						array(
							'value'		=> '16',
							'text'		=> 'Under 16'
						),
						array(
							'value'		=> '17',
							'text'		=> 'Under 17'
						),
						array(
							'value'		=> '18',
							'text'		=> 'Under 18'
						)
					),
				'length' 	 => 15,
				'label' 	 => 'Team'
			),
			array(
				'name'       => 'teamid',
				'datatype'	 => 'integer',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'editable' 	 => false,
				'default'	 => $teamid,
				'label' 	 => 'Team'
			),
			array(
				'name'       => 'imageid',
				'type'		 => 'IMAGE',
				'required'   => false,
				'length' 	 => 35,
				'showInView' => false,
				'label' 	 => 'Logo'
			),			
			array(
				'name'       => 'firstname',
				'length' 	 => 15,
				'label' 	 => 'First Name'
			),			
			array(
				'name'       => 'lastname',
				'length' 	 => 15,
				'label' 	 => 'Last Name'
			),			
			array(
				'name'       => 'email',
				'length' 	 => 40,
				'label' 	 => 'Email'
			),
			array(
				'name'       => 'telephone',
				'length' 	 => 12,
				'label' 	 => 'Telephone'
			),
			array(
				'name'       => 'fax',
				'length' 	 => 12,
				'required' 	 => false,
				'label' 	 => 'Fax'
			)
		);

	$crud->subapplications = array(
			array(
				'title'		  => 'Documents',
				'imageurl'	  => 'images/document.gif',
				'script' 	  => 'editDocuments'
			),
			array(
				'title'		  => 'Players',
				'imageurl'	  => 'images/team.png',
				'application' => 'players.php'
			)
		);
		
	$crud->run();
?>
