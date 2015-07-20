<?php
	require_once("crud.php");
	
	class DiscountBandCrud extends Crud {
		public function postScriptEvent() {
?>
<?php
		}
	}
	
	$crud = new DiscountBandCrud();
	$crud->title = "Discount Band";
	$crud->table = "{$_SESSION['DB_PREFIX']}discountband";
	$crud->sql = "SELECT * FROM {$_SESSION['DB_PREFIX']}discountband ORDER BY name";
	
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
			),
			array(
				'name'       => 'discount',
				'datatype'   => 'decimal',
				'align'		 => 'right',
				'length' 	 => 12,
				'label' 	 => 'Discount %'
			)
		);
		
	$crud->run();
	
?>
