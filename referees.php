<?php
	require_once("crud.php");
	
	class RefereeCrud extends Crud {
		
		public function postUpdateEvent($id) {
			$fullname = $_POST['firstname'] . " " . $_POST['lastname'];
			
			$qry = "UPDATE {$_SESSION['DB_PREFIX']}referee SET
					name = '$fullname'
					WHERE id = $id";
			
			$result = mysql_query($qry);
			
			if (! $result) {
				logError($qry . " - " . mysql_error());
			}
		}
		
		public function postInsertEvent() {
			$id = mysql_insert_id();
			$fullname = $_POST['firstname'] . " " . $_POST['lastname'];
			
			$qry = "UPDATE {$_SESSION['DB_PREFIX']}referee SET
					name = '$fullname'
					WHERE id = $id";
								
			$result = mysql_query($qry);
			
			if (! $result) {
				logError($qry . " - " . mysql_error());
			}
		}
		
		/* Post script event. */
		public function postScriptEvent() {
?>
<?php
		}
	}
	
	$crud = new RefereeCrud();
	$crud->dialogwidth = 750;
	$crud->title = "Referees";
	$crud->table = "{$_SESSION['DB_PREFIX']}referee";
	$crud->sql = "SELECT A.* 
				  FROM  {$_SESSION['DB_PREFIX']}referee A
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
				'name'       => 'firstname',
				'length' 	 => 30,
				'label' 	 => 'First Name'
			),
			array(
				'name'       => 'lastname',
				'length' 	 => 30,
				'label' 	 => 'Last Name'
			),
			array(
				'name'       => 'address',
				'type' 	 	 => 'BASICTEXTAREA',
				'showInView' => false,
				'label' 	 => 'Address 1'
			),
			array(
				'name'       => 'email',
				'length' 	 => 40,
				'label' 	 => 'Email'
			),
			array(
				'name'       => 'telephone',
				'length' 	 => 16,
				'label' 	 => 'Home Telephone'
			),
			array(
				'name'       => 'mobile',
				'length' 	 => 12,
				'label' 	 => 'Mobile'
			)
		);

	$crud->run();
?>
