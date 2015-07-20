<?php
	require_once("crud.php");
	
	class RotaCrud extends Crud {
		
	}

	$crud = new RotaCrud();
	$crud->title = "Rota Items";
	$crud->table = "{$_SESSION['DB_PREFIX']}rotaitem";
	$crud->dialogwidth = 900;
	$crud->sql = 
			"SELECT A.*, B.fullname, C.description
			 FROM {$_SESSION['DB_PREFIX']}rotaitem A 
			 INNER JOIN {$_SESSION['DB_PREFIX']}members B
			 ON B.member_id = A.userid 
			 INNER JOIN {$_SESSION['DB_PREFIX']}rota C
			 ON C.id = A.rotaid
			 ORDER BY A.startdate DESC, A.userid";
	
	$crud->columns = array(
			array(
				'name'       => 'id',
				'length' 	 => 6,
				'pk'		 => true,
				'showInView' => false,
				'editable'	 => false,
				'bind' 	 	 => false,
				'label' 	 => 'ID'
			),
			array(
				'name'       => 'description',
				'length' 	 => 30,
				'editable' 	 => false,
				'bind' 		 => false,
				'label' 	 => 'Description'
			),
			array(
				'name'       => 'userid',
				'type'       => 'DATACOMBO',
				'length' 	 => 18,
				'label' 	 => 'User',
				'table'		 => 'members',
				'required'	 => true,
				'table_id'	 => 'member_id',
				'alias'		 => 'fullname',
				'table_name' => 'fullname'
			),
			array(
				'name'       => 'startdate',
				'length' 	 => 12,
				'bind'		 => false,
				'label' 	 => 'Start Date'
			),
			array(
				'name'       => 'enddate',
				'length' 	 => 12,
				'bind'		 => false,
				'label' 	 => 'End Date'
			),
			array(
				'name'       => 'notes',
				'showInView' => false,
				'type'		 => 'TEXTAREA',
				'label' 	 => 'Notes'
			)
		);
	$crud->subapplications = array(
			array(
				'title'		  => 'Items',
				'imageurl'	  => 'images/minimize.gif',
				'application' => 'managerotaitems.php'
			)
		);
		
	$crud->run();
?>
