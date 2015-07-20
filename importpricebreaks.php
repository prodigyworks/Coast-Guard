<?php
	include("system-header.php"); 
	
	$substringstart = 0;
	
	function startsWith($Haystack, $Needle){
	    // Recommended version, using strpos
	    return strpos($Haystack, $Needle) === 0;
	}
	
	if (isset($_FILES['pricebreakfile']) && $_FILES['pricebreakfile']['tmp_name'] != "") {
		if ($_FILES["pricebreakfile"]["error"] > 0) {
			echo "Error: " . $_FILES["pricebreakfile"]["error"] . "<br />";
			
		} else {
		  	echo "Upload: " . $_FILES["pricebreakfile"]["name"] . "<br />";
		  	echo "Type: " . $_FILES["pricebreakfile"]["type"] . "<br />";
		  	echo "Size: " . ($_FILES["pricebreakfile"]["size"] / 1024) . " Kb<br />";
		  	echo "Stored in: " . $_FILES["pricebreakfile"]["tmp_name"] . "<br>";
		}
		
		$subcat1 = "";
		$row = 1;
		
		if (($handle = fopen($_FILES['pricebreakfile']['tmp_name'], "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		        if ($row++ == 1) {
		        	continue;
		        }
		        
		        $num = count($data);
		        $index = 0;
		        
		        $productcode = trim(mysql_escape_string($data[$index++]));
		        $priceeach = str_replace(",", "", mysql_escape_string($data[$index++]));
		        $qtyfrom = str_replace(",", "", mysql_escape_string($data[$index++]));
		        $qtyto = str_replace(",", "", mysql_escape_string($data[$index++]));
		        $productid = 0;
		        		        
		        if ($data[0] != "") {
		        	echo "<div>Product code: $productcode</div>";
	        	
					$qry = "SELECT id " .
							"FROM {$_SESSION['DB_PREFIX']}product " .
							"WHERE productcode = '$productcode'";
					$result = mysql_query($qry);
					
					//Check whether the query was successful or not
					if ($result) {
						while (($member = mysql_fetch_assoc($result))) {
							$productid = $member['id'];
						}
					}
		        
					$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}pricebreak 
							(
							productid, priceeach, qtyfrom, qtyto 
							)  
							VALUES  
							(
							$productid, $priceeach, $qtyfrom, $qtyto 
							)";
							
					$result = mysql_query($qry);
        	
					if (mysql_errno() != 1062 && mysql_errno() != 0 ) {
						logError(mysql_error() . " : " .  $qry);
					}
		        }
		    }
		    
		    fclose($handle);
			echo "<h1>" . $row . " downloaded</h1>";
		}
	}
	
	if (! isset($_FILES['pricebreakfile'])) {
?>	
		
<form class="contentform" method="post" enctype="multipart/form-data" onsubmit="return askPassword()">
	<label>Upload Price Break CSV file </label>
	<input type="file" name="pricebreakfile" id="pricebreakfile" /> 
	
	<br />
	 	
	<div id="submit" class="show">
		<input type="submit" value="Upload" />
	</div>
</form>
<?php
	}
	
	include("system-footer.php"); 
?>