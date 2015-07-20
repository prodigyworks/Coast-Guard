<?php
	require_once("crud.php");
	
	class RotaCrud extends Crud {
		
	}

	$crud = new RotaCrud();
	$crud->title = "Rotas";
	$crud->table = "{$_SESSION['DB_PREFIX']}rota";
	$crud->dialogwidth = 900;
	$crud->sql = 
			"SELECT A.*
			 FROM {$_SESSION['DB_PREFIX']}rota A 
			 ORDER BY A.startdate DESC";
	
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
				'label' 	 => 'Description'
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
				'name'       => 'status',
				'length' 	 => 9,
				'label' 	 => 'Status',
				'type'       => 'COMBO',
				'options'    => array(
						array(
							'value'		=> 'P',
							'text'		=> 'Planning'
						),
						array(
							'value'		=> 'N',
							'text'		=> 'Confirmed'
						)
					)
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
