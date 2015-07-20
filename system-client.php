<?php 
	require_once("system-embeddedheader.php"); 

	$customerid = getLoggedOnCustomerID();
?>
	<form action="system-client-exec.php" method="post" id="loginForm">
		<br>
		<div><label>Client</label></div>
		<br>
		<?php createCombo("clientid", "id", "name", "{$_SESSION['DB_PREFIX']}customerclient", "WHERE customerid = $customerid")?>
		<br>
		<br>
		<div><label>Site</label></div>

		<br>
		<SELECT id="siteid" name="siteid" style="width:200px">
			<OPTION value=""></OPTION>
		</SELECT>
		<br>
		<br>
		<input type="submit" value="Confirm"></input>
	</form>
	<script>
		$(document).ready(
				function() {
					$("#clientid").change(
							function() {
								$.ajax({
										url: "createclientsitecombo.php",
										dataType: 'html',
										async: false,
										data: {
											clientid: $("#clientid").val()
										},
										type: "POST",
										error: function(jqXHR, textStatus, errorThrown) {
											alert(errorThrown);
										},
										success: function(data) {
											$("#siteid").html(data);
										}
									});
							}
						);
				}
			);
	</script>
</div>

<?php include("system-embeddedfooter.php"); ?>					
