<?php
	include("system-header.php"); 
	if (isset($_FILES['refereefile']) && $_FILES['refereefile']['tmp_name'] != "") {
		if ($_FILES["refereefile"]["error"] > 0) {
			echo "Error: " . $_FILES["refereefile"]["error"] . "<br />";
			
		} else {
		  	echo "Upload: " . $_FILES["refereefile"]["name"] . "<br />";
		  	echo "Type: " . $_FILES["refereefile"]["type"] . "<br />";
		  	echo "Size: " . ($_FILES["refereefile"]["size"] / 1024) . " Kb<br />";
		  	echo "Stored in: " . $_FILES["refereefile"]["tmp_name"] . "<br>";
		}
		
		$subcat1 = "";
		$row = 1;
		
		if (($handle = fopen($_FILES['refereefile']['tmp_name'], "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		        if ($row++ == 1) {
		        	continue;
		        }
		        
		        $num = count($data);
		        $index = 0;
		        
		        $lastname = mysql_escape_string($data[$index++]);
		        $firstname = mysql_escape_string($data[$index++]);
		        $name = $firstname . " " . $lastname;
		        $address = mysql_escape_string($data[$index++]);
		        $telephone = mysql_escape_string($data[$index++]);
		        $mobile	 = mysql_escape_string($data[$index++]);
                $email = mysql_escape_string($data[$index++]);
		        		        
		        if ($data[0] != "") {
		        	echo "<div>Referee: $firstname $lastname</div>";
		        	
					$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}referee " .
							"(firstname, lastname, name, address, telephone, mobile, email) " .
							"VALUES " .
							"('$firstname', '$lastname', '$name', '$address', '$telephone', '$mobile', '$email')";
							
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
	
	if (! isset($_FILES['refereefile'])) {
?>	
		
<form class="contentform" method="post" enctype="multipart/form-data">
	<label>Upload referee CSV file </label>
	<input type="file" name="refereefile" id="refereefile" /> 
	
	<br />
	 	
	<div id="submit" class="show">
		<input type="submit" value="Upload" />
	</div>
</form>
<?php
	}
	
	include("system-footer.php"); 
?>