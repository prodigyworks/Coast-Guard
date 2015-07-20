<?php
	require_once("crud.php");
	
	class PlayerCrud extends Crud {
		
		/* Post header event. */
		public function postHeaderEvent() {
			createDocumentLink();
		}
		
		public function postLoadScriptEvent() {
?>
			$("#dob").removeClass("datepicker");
<?php 
		}
		
		public function postScriptEvent() {
?>
			var agegroup = 0;
			
			$(document).ready(
					function() {
						$("#editform #dob").unbind("focus");
						
						callAjax(
							"finddata.php", 
							{ 
								sql: "SELECT age FROM <?php echo $_SESSION['DB_PREFIX'];?>teamagegroup WHERE id = <?php echo $_GET['id']; ?>"
							},
							function(data) {
								if (data.length == 1) {
									agegroup = data[0].age;
								}
							},
							false
						);
					}
				);
				
			function validatePlayerForm() {
				if (parseFloat($("#registrationnumber").val().substring(0, 2)) != agegroup) {
					pwAlert("Registration number does match the age group of under " + agegroup + "'s");
					return false;
				}
			}
			
			function editDocuments(node) {
				viewDocument(node, "addplayerdocument.php", node, "playerdocs", "playerid");
			}
<?php			
		}
	}
	
	$agegroupid = $_GET['id'];
	
	$crud = new PlayerCrud();
	$crud->dialogwidth = 450;
	$crud->title = "Players";
	$crud->allowAdd = isUserInRole("TEAM");
	$crud->allowEdit = isUserInRole("TEAM");
	$crud->allowRemove = isUserInRole("TEAM");
	$crud->validateForm = "validatePlayerForm";
	$crud->table = "{$_SESSION['DB_PREFIX']}player";
	$crud->sql = "SELECT A.*, B.name AS teamname, C.name AS clubname
				  FROM  {$_SESSION['DB_PREFIX']}player A
				  INNER JOIN {$_SESSION['DB_PREFIX']}teamagegroup B
				  ON B.id = A.agegroupid
				  INNER JOIN {$_SESSION['DB_PREFIX']}team C
				  ON C.id = B.teamid
				  WHERE A.agegroupid = $agegroupid
				  ORDER BY A.firstname, A.lastname";
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
				'name'       => 'agegroupid',
				'datatype'	 => 'integer',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'editable' 	 => false,
				'default'	 => $agegroupid,
				'label' 	 => 'Team'
			),
			array(
				'name'       => 'clubname',
				'length' 	 => 18,
				'editable'	 => false,
				'bind'		 => false,
				'label' 	 => 'Club'
			),			
			array(
				'name'       => 'teamname',
				'length' 	 => 12,
				'editable'	 => false,
				'bind'		 => false,
				'label' 	 => 'Team'
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
				'name'       => 'registrationnumber',
				'length' 	 => 30,
				'label' 	 => 'Registration Number'
			),			
			array(
				'name'       => 'dob',
				'length' 	 => 10,
				'datatype'	 => 'date',
				'suffix'	 => 'dd/mm/yyyy',
				'label' 	 => 'Date of Birth'
			)
		);

	$crud->subapplications = array(
			array(
				'title'		  => 'Documents',
				'imageurl'	  => 'images/document.gif',
				'script' 	  => 'editDocuments'
			)
		);
		
	$crud->run();
?>
