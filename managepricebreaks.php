<?php
	require_once("crud.php");
	
	class PriceBreakCrud extends Crud {
		
	}
	
	$productid = $_GET['id'];
	
	$crud = new PriceBreakCrud();
	$crud->dialogwidth = 340;
	$crud->title = "Price Breaks";
	$crud->table = "{$_SESSION['DB_PREFIX']}pricebreak";
	$crud->sql = "SELECT A.*, B.description
				  FROM  {$_SESSION['DB_PREFIX']}pricebreak A
				  INNER JOIN {$_SESSION['DB_PREFIX']}product B
				  ON B.id = A.productid
				  WHERE A.productid = $productid
				  ORDER BY A.qtyfrom, A.qtyto";
	
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
				'name'       => 'productid',
				'type'       => 'LAZYDATACOMBO',
				'length' 	 => 60,
				'label' 	 => 'Product',
				'table'		 => 'product',
				'table_id'	 => 'id',
				'editable'   => false,
				'default'	 => $_GET['id'],
				'alias'		 => 'description',
				'table_name' => 'name'
			),
			array(
				'name'       => 'priceeach',
				'length' 	 => 12,
				'datatype'   => 'double',
				'align'		 => 'right',
				'label' 	 => 'Discount Price'
			),			
			array(
				'name'       => 'qtyfrom',
				'length' 	 => 12,
				'datatype'   => 'double',
				'align'		 => 'right',
				'label' 	 => 'Quantity From'
			),
			array(
				'name'       => 'qtyto',
				'length' 	 => 12,
				'datatype'   => 'double',
				'align'		 => 'right',
				'label' 	 => 'Quantity To'
			)
		);

	$crud->run();
?>
