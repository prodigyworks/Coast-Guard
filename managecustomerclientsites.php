<?php
	require_once("crud.php");
	
	class CustomerCrud extends Crud {
		
		/* Post header event. */
		public function postHeaderEvent() {
			createDocumentLink();
		}
		
		public function postScriptEvent() {
?>
			function editDocuments(node) {
				viewDocument(node, "addcustomerdocument.php", node, "customerdocs", "customerid");
			}
	
			/* Derived delivery address callback. */
			function fullDeliveryAddress(node) {
				var address = "";
				
				if ((node.deliveryaddress1) != "") {
					address = address + node.deliveryaddress1;
				} 
				
				if ((node.deliveryaddress2) != "") {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.deliveryaddress2;
				} 
				
				if ((node.deliveryaddress3) != "") {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.deliveryaddress3;
				} 
				
				if ((node.deliverycity) != "") {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.deliverycity;
				} 
				
				if ((node.deliverypostcode) != "") {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.deliverypostcode;
				} 
				
				if ((node.deliverycountry) != "") {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.deliverycountry;
				} 
				
				return address;
			}
<?php			
		}
	}
	
	$clientid = $_GET['id'];
	
	$crud = new CustomerCrud();
	$crud->dialogwidth = 650;
	$crud->title = "Sites";
	$crud->table = "{$_SESSION['DB_PREFIX']}customerclientsite";
	$crud->sql = "SELECT A.*, B.name AS clientname
				  FROM  {$_SESSION['DB_PREFIX']}customerclientsite A
				  INNER JOIN {$_SESSION['DB_PREFIX']}customerclient B
				  ON B.id = A.clientid
				  INNER JOIN {$_SESSION['DB_PREFIX']}customer C
				  ON C.id = B.customerid
				  WHERE A.clientid = $clientid
				  ORDER BY A.name";
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
				'name'       => 'clientid',
				'datatype'	 => 'integer',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'editable' 	 => false,
				'default'	 => $clientid,
				'label' 	 => 'Customer'
			),
			array(
				'name'       => 'clientname',
				'length' 	 => 27,
				'bind' 	 	 => false,
				'filter'	 => false,
				'editable' 	 => false,
				'label' 	 => 'Client'
			),			
			array(
				'name'       => 'name',
				'length' 	 => 27,
				'label' 	 => 'Name'
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
				'name'       => 'deliveryaddress1',
				'length' 	 => 60,
				'showInView' => false,
				'label' 	 => 'Delivery Address 1'
			),
			array(
				'name'       => 'deliveryaddress2',
				'length' 	 => 60,
				'required'	 => false,
				'showInView' => false,
				'label' 	 => 'Delivery Address 2'
			),
			array(
				'name'       => 'deliveryaddress3',
				'length' 	 => 60,
				'showInView' => false,
				'required'	 => false,
				'label' 	 => 'Delivery Address 3'
			),
			array(
				'name'       => 'deliverycity',
				'length' 	 => 30,
				'showInView' => false,
				'label' 	 => 'Delivery City'
			),
			array(
				'name'       => 'deliverypostcode',
				'length' 	 => 10,
				'showInView' => false,
				'label' 	 => 'Delivery Post Code'
			),
			array(
				'name'       => 'deliverycountry',
				'length' 	 => 30,
				'showInView' => false,
				'label' 	 => 'Delivery Country'
			),
			array(
				'name'       => 'deliveryaddress',
				'length' 	 => 90,
				'editable'   => false,
				'bind'		 => false,
				'type'		 => 'DERIVED',
				'function'	 => 'fullDeliveryAddress',
				'label' 	 => 'Delivery Address'
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
			)
		);
		
	$crud->run();
?>
