<?php 
	require_once("system-header.php"); 
	
?>
<style>
	#unofficial_referee, .badscore {
		display:none;
	}
</style>
<div id="orderapp">
	<div class="title">Match Result Form</div>
	<form id="orderform" method="POST" action="match3.php">
		<input type="hidden" id="matchdate" name="matchdate" value="<?php echo $_POST['matchdate']; ?>" />
		<input type="hidden" id="division" name="division" value="<?php echo $_POST['division']; ?>" />
		<input type="hidden" id="leaguecup" name="leaguecup" value="<?php echo $_POST['leaguecup']; ?>" />
		<input type="hidden" id="awayteam" name="awayteam" value="<?php echo $_POST['awayteam']; ?>" />
		<input type="hidden" id="agegroupid" name="agegroupid" value="<?php echo $_POST['agegroupid']; ?>" />
		<input type="hidden" id="hometeamid_lazy" name="hometeamid_lazy" value="<?php echo $_POST['hometeamid_lazy']; ?>" />
		<input type="hidden" id="hometeamid" name="hometeamid" value="<?php echo $_POST['hometeamid']; ?>" />
		<input type="hidden" id="oppositionid_lazy" name="oppositionid_lazy" value="<?php echo $_POST['oppositionid_lazy']; ?>" />
		<input type="hidden" id="oppositionid" name="oppositionid" value="<?php echo $_POST['oppositionid']; ?>" />
		<input type="hidden" id="hometeamscore" name="hometeamscore" value="<?php echo $_POST['hometeamscore']; ?>" />
		<input type="hidden" id="awayteamscore" name="awayteamscore" value="<?php echo $_POST['awayteamscore']; ?>" />
		<input type="hidden" id="refereescore" name="refereescore" value="-1"/>
		<input type="hidden" id="refereeremarks" name="refereeremarks" value=""/>
		<input type="hidden" id="pitchsize" name="pitchsize" value="<?php if (isset($_POST['pitchsize'])) echo $_POST['pitchsize']; ?>" />
		<input type="hidden" id="complycodes" name="complycodes" value="<?php if (isset($_POST['complycodes'])) echo $_POST['complycodes']; ?>" />
		<input type="hidden" id="refappointedbyleague" name="refappointedbyleague" value="L"/>
<?php 
	$i = 0;
	
	if (isset($_POST['player'])) {
		for ($i = 0; $i < count($_POST['player']); $i++) {
?>
		<input type="hidden" name="player[]" value="<?php echo $_POST['player'][$i]; ?>"></input>
<?php 
		}
	}
?>
		<table>
			<tr>
				<td width='352px'>
					<label>Referee</label>
				</td>
				<td>
					&nbsp;Excellent&nbsp;
				</td>
				<td>
					<input type="radio" name="ratereferee" id="ratereferee_E" value="E" checked></input>
				</td>
				<td>
					&nbsp;Good&nbsp;
				</td>
				<td>
					<input type="radio" name="ratereferee" id="ratereferee_G" value="G"></input>
				</td>
				<td>
					&nbsp;Fair&nbsp;
				</td>
				<td>
					<input type="radio" name="ratereferee" id="ratereferee_F" value="F"></input>
				</td>
				<td>
					&nbsp;Poor&nbsp;
				</td>
				<td>
					<input type="radio" name="ratereferee" id="ratereferee_P" value="P"></input>
				</td>
			</tr>
			<tr>
				<td>
					<label>Opponent players</label>
				</td>
				<td>
					&nbsp;Excellent&nbsp;
				</td>
				<td>
					<input type="radio" name="rateplayers" id="rateplayers_E" value="E" checked></input>
				</td>
				<td>
					&nbsp;Good&nbsp;
				</td>
				<td>
					<input type="radio" name="rateplayers" id="rateplayers_G" value="G"></input>
				</td>
				<td>
					&nbsp;Fair&nbsp;
				</td>
				<td>
					<input type="radio" name="rateplayers" id="rateplayers_F" value="F"></input>
				</td>
				<td>
					&nbsp;Poor&nbsp;
				</td>
				<td>
					<input type="radio" name="rateplayers" id="rateplayers_P" value="P"></input>
				</td>
			</tr>
			<tr>
				<td>
					<label>Opponent Management</label>
				</td>
				<td>
					&nbsp;Excellent&nbsp;
				</td>
				<td>
					<input type="radio" name="ratemanagement" id="ratemanagement_E" value="E" checked></input>
				</td>
				<td>
					&nbsp;Good&nbsp;
				</td>
				<td>
					<input type="radio" name="ratemanagement" id="ratemanagement_G" value="G"></input>
				</td>
				<td>
					&nbsp;Fair&nbsp;
				</td>
				<td>
					<input type="radio" name="ratemanagement" id="ratemanagement_F" value="F"></input>
				</td>
				<td>
					&nbsp;Poor&nbsp;
				</td>
				<td>
					<input type="radio" name="ratemanagement" id="ratemanagement_P" value="P"></input>
				</td>
			</tr>
			</tr>
			<tr>
				<td>
					<label>Opponent Spectators</label>
				</td>
				<td>
					&nbsp;Excellent&nbsp;
				</td>
				<td>
					<input type="radio" name="ratespectators" id="ratespectators_E" value="E" checked></input>
				</td>
				<td>
					&nbsp;Good&nbsp;
				</td>
				<td>
					<input type="radio" name="ratespectators" id="ratespectators_G" value="G"></input>
				</td>
				<td>
					&nbsp;Fair&nbsp;
				</td>
				<td>
					<input type="radio" name="ratespectators" id="ratespectators_F" value="F"></input>
				</td>
				<td>
					&nbsp;Poor&nbsp;
				</td>
				<td>
					<input type="radio" name="ratespectators" id="ratespectators_P" value="P"></input>
				</td>
			</tr>
			<tr>
				<td>
					<label>Pitch Size</label>
				</td>
				<td>
					&nbsp;Excellent&nbsp;
				</td>
				<td>
					<input type="radio" name="ratepitchsize" id="ratepitchsize_E" value="E" checked></input>
				</td>
				<td>
					&nbsp;Good&nbsp;
				</td>
				<td>
					<input type="radio" name="ratepitchsize" id="ratepitchsize_G" value="G"></input>
				</td>
				<td>
					&nbsp;Fair&nbsp;
				</td>
				<td>
					<input type="radio" name="ratepitchsize" id="ratepitchsize_F" value="F"></input>
				</td>
				<td>
					&nbsp;Poor&nbsp;
				</td>
				<td>
					<input type="radio" name="ratepitchsize" id="ratepitchsize_P" value="P"></input>
				</td>
			</tr>
			<tr>
				<td>
					<label>Pitch Condition</label>
				</td>
				<td>
					&nbsp;Excellent&nbsp;
				</td>
				<td>
					<input type="radio" name="ratepitchcondition" id="ratepitchcondition_E" value="E" checked></input>
				</td>
				<td>
					&nbsp;Good&nbsp;
				</td>
				<td>
					<input type="radio" name="ratepitchcondition" id="ratepitchcondition_G" value="G"></input>
				</td>
				<td>
					&nbsp;Fair&nbsp;
				</td>
				<td>
					<input type="radio" name="ratepitchcondition" id="ratepitchcondition_F" value="F"></input>
				</td>
				<td>
					&nbsp;Poor&nbsp;
				</td>
				<td>
					<input type="radio" name="ratepitchcondition" id="ratepitchcondition_P" value="P"></input>
				</td>
			</tr>
			<tr>
				<td>
					<label>Goal Size</label>
				</td>
				<td>
					&nbsp;Excellent&nbsp;
				</td>
				<td>
					<input type="radio" name="rategoalsize" id="rategoalsize_E" value="E" checked></input>
				</td>
				<td>
					&nbsp;Good&nbsp;
				</td>
				<td>
					<input type="radio" name="rategoalsize" id="rategoalsize_G" value="G"></input>
				</td>
				<td>
					&nbsp;Fair&nbsp;
				</td>
				<td>
					<input type="radio" name="rategoalsize" id="rategoalsize_F" value="F"></input>
				</td>
				<td>
					&nbsp;Poor&nbsp;
				</td>
				<td>
					<input type="radio" name="rategoalsize" id="rategoalsize_P" value="P"></input>
				</td>
			</tr>
			<tr>
				<td>
					<label>Changing Rooms</label>
				</td>
				<td>
					&nbsp;Excellent&nbsp;
				</td>
				<td>
					<input type="radio" name="ratechangingrooms" id="ratechangingrooms_E" value="E" checked></input>
				</td>
				<td>
					&nbsp;Good&nbsp;
				</td>
				<td>
					<input type="radio" name="ratechangingrooms" id="ratechangingrooms_G" value="G"></input>
				</td>
				<td>
					&nbsp;Fair&nbsp;
				</td>
				<td>
					<input type="radio" name="ratechangingrooms" id="ratechangingrooms_F" value="F"></input>
				</td>
				<td>
					&nbsp;Poor&nbsp;
				</td>
				<td>
					<input type="radio" name="ratechangingrooms" id="ratechangingrooms_P" value="P"></input>
				</td>
			</tr>
		</table>
		
		<br>
		
		
		<table>
			<tr>
				<td>
					<label>Did you check your opponent players ID cards?</label>
				</td>
				<td>
					&nbsp;Yes&nbsp;
				</td>
				<td>
					<input type="radio" name="opponentids" id="opponentids_on" value="on" checked></input>
				</td>
				<td>
					&nbsp;No&nbsp;
				</td>
				<td>
					<input type="radio" name="opponentids" id="opponentids_off" value="off"></input>
				</td>
			</tr>
			<tr>
				<td>
					<label>Did the pitch have the required barriers, cones and markings?</label>
				</td>
				<td>
					&nbsp;Yes&nbsp;
				</td>
				<td>
					<input type="radio" name="requiredbarriers" id="requiredbarriers_on" value="on" checked></input>
				</td>
				<td>
					&nbsp;No&nbsp;
				</td>
				<td>
					<input type="radio" name="requiredbarriers" id="requiredbarriers_off" value="off"></input>
				</td>
			</tr>
		</table>
		
		<br>
		<label><i>If NO to any of the above (or any other observations) please provide comments</i></label>
		<textarea id="remarks" name="remarks" rows="5" cols="70"></textarea>

		<hr />

		<table width='75%' cellspacing=5>
			<tr>
				<td>Name of Referee</td>
				<td colspan=3>
					<?php createLazyCombo("refereeid", "id", "name", "{$_SESSION['DB_PREFIX']}referee", "", true, 40); ?>
				</td>
			</tr>
		</table>
		<hr />
<?php 
	require_once('signature.php');

	addSignatureForm();
?>
		
		<input class="submitbutton" type="button" onclick="prevPage()" value="Prev"></input>&nbsp;
		<input class="submitbutton" type="button" onclick="processorder()" value="Next"></input>
	</form>
	<script>
		$(document).ready(
				function() {
<?php 
					if (isset($_POST['ratereferee'])) echo "$('#ratereferee_" . $_POST['ratereferee'] . "').attr('checked', true);\n";
					if (isset($_POST['rateplayers'])) echo "$('#rateplayers_" . $_POST['rateplayers'] . "').attr('checked', true);\n";
					if (isset($_POST['ratechangingrooms'])) echo "$('#ratechangingrooms_" . $_POST['ratechangingrooms'] . "').attr('checked', true);\n";
					if (isset($_POST['rategoalsize'])) echo "$('#rategoalsize_" . $_POST['rategoalsize'] . "').attr('checked', true);\n";
					if (isset($_POST['ratepitchcondition'])) echo "$('#ratepitchcondition_" . $_POST['ratepitchcondition'] . "').attr('checked', true);\n";
					if (isset($_POST['ratepitchsize'])) echo "$('#ratepitchsize_" . $_POST['ratepitchsize'] . "').attr('checked', true);\n";
					if (isset($_POST['ratespectators'])) echo "$('#ratespectators_" . $_POST['ratespectators'] . "').attr('checked', true);\n";
					if (isset($_POST['ratemanagement'])) echo "$('#ratemanagement_" . $_POST['ratemanagement'] . "').attr('checked', true);\n";
					
					if (isset($_POST['opponentids'])) echo "$('#opponentids_" . $_POST['opponentids'] . "').attr('checked', true);\n";
					if (isset($_POST['requiredbarriers'])) echo "$('#requiredbarriers_" . $_POST['requiredbarriers'] . "').attr('checked', true);\n";
					
					if (isset($_POST['remarks'])) echo "$('#remarks').val('" . mysql_escape_string($_POST['remarks']) . "');\n";
					if (isset($_POST['refereeid'])) echo "$('#refereeid').val('" . mysql_escape_string($_POST['refereeid']) . "');\n";
					if (isset($_POST['refereeid_lazy'])) echo "$('#refereeid_lazy').val('" . mysql_escape_string($_POST['refereeid_lazy']) . "');\n";
					
					if (isset($_POST['signatureid'])) echo "$('#signatureid').val('" . mysql_escape_string($_POST['signatureid']) . "');\n";
					if (isset($_POST['name'])) echo "$('#name').val('" . mysql_escape_string($_POST['name']) . "');\n";
					if (isset($_POST['output'])) echo "$('#output').val('" . mysql_escape_string($_POST['output']) . "');\n";
?>
					
			      	$('.sigPad').signaturePad(
			      			{
			      				validateFields: false
			      			}
						);

			      	$("#refereescore").change(
					      	function() {
						      	if ($(this).val() < 50) {
							      	$(".badscore").show();
							      	
						      	} else {
							      	$(".badscore").hide();
						      	}
					      	}
						);

					$("#name").trigger("blur");
				}
			);
		
		function processorder() {
			if ($("#refereeid_lazy").val() == "") {
				pwAlert("Referee must be specified");
				return false;
			}
			
			if ($("#name").val() == "") {
				pwAlert("Signature required");
				return false;
			}
			
			$("#orderform").submit();
		}
		
		function prevPage() {
			$("#orderform").attr("action", "match.php");
			$("#orderform").submit();
		}
	</script>
</div>
<?php include("system-footer.php"); ?>

