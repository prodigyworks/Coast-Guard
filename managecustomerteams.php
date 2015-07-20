<?php
	require_once("crud.php");
	
	class AccountStatusCrud extends Crud {
		public function postScriptEvent() {
?>
<?php
		}
	}
	
	$crud = new AccountStatusCrud();
	$crud->title = "Customer Teams";
	$crud->table = "{$_SESSION['DB_PREFIX']}customerteam";
	$crud->sql = "SELECT * FROM {$_SESSION['DB_PREFIX']}customerteam ORDER BY name";
	
	$crud->columns = array(
			array(
				'name'       => 'id',
				'length' 	 => 6,
				'showInView' => false,
				'bind' 	 	 => false,
				'editable' 	 => false,
				'pk'		 => true,
				'label' 	 => 'ID'
			),
			array(
				'name'       => 'name',
				'length' 	 => 60,
				'label' 	 => 'Name'
			)
		);
		
	$crud->run();
	
?>
