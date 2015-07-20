<?php include("system-header.php"); ?>
<style>
	#cup_opposition {
		display:none;
	}
	
	.score {
		text-align: center;
	}
	
	#players {
		min-height:350px;
	}
</style>
<div id="orderapp">
	<div class="title">Match Result Form</div>
	<form id="orderform" method="POST" action="match2.php">
		<input type="hidden" id="ratereferee" name="ratereferee" value="<?php if (isset($_POST['ratereferee'])) echo $_POST['ratereferee']; ?>" />
		<input type="hidden" id="rateplayers" name="rateplayers" value="<?php if (isset($_POST['rateplayers'])) echo $_POST['rateplayers']; ?>" />
		<input type="hidden" id="ratemanagement" name="ratemanagement" value="<?php if (isset($_POST['ratemanagement'])) echo $_POST['ratemanagement']; ?>" />
		<input type="hidden" id="ratespectators" name="ratespectators" value="<?php if (isset($_POST['ratespectators'])) echo $_POST['ratespectators']; ?>" />
		<input type="hidden" id="ratepitchsize" name="ratepitchsize" value="<?php if (isset($_POST['ratepitchsize'])) echo $_POST['ratepitchsize']; ?>" />
		<input type="hidden" id="ratepitchcondition" name="ratepitchcondition" value="<?php if (isset($_POST['ratepitchcondition'])) echo $_POST['ratepitchcondition']; ?>" />
		<input type="hidden" id="rategoalsize" name="rategoalsize" value="<?php if (isset($_POST['rategoalsize'])) echo $_POST['rategoalsize']; ?>" />
		<input type="hidden" id="ratechangingrooms" name="ratechangingrooms" value="<?php if (isset($_POST['ratechangingrooms'])) echo $_POST['ratechangingrooms']; ?>" />
		<input type="hidden" id="opponentids" name="opponentids" value="<?php if (isset($_POST['opponentids'])) echo $_POST['opponentids']; ?>" />
		<input type="hidden" id="requiredbarriers" name="requiredbarriers" value="<?php if (isset($_POST['requiredbarriers'])) echo $_POST['requiredbarriers']; ?>" />
		<input type="hidden" id="pitchsize" name="pitchsize" value="<?php if (isset($_POST['pitchsize'])) echo $_POST['pitchsize']; ?>" />
		<input type="hidden" id="complycodes" name="complycodes" value="<?php if (isset($_POST['complycodes'])) echo $_POST['complycodes']; ?>" />
		<input type="hidden" id="refereeremarks" name="refereeremarks" value="<?php if (isset($_POST['refereeremarks'])) echo $_POST['refereeremarks']; ?>" />
		<input type="hidden" id="remarks" name="remarks" value="<?php if (isset($_POST['remarks'])) echo $_POST['remarks']; ?>" />
		<input type="hidden" id="refereeid" name="refereeid" value="<?php if (isset($_POST['refereeid'])) echo $_POST['refereeid']; ?>" />
		<input type="hidden" id="refereeid_lazy" name="refereeid_lazy" value="<?php if (isset($_POST['refereeid_lazy'])) echo $_POST['refereeid_lazy']; ?>" />
		<input type="hidden" id="name" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" />
		<input type="hidden" id="output" name="output" value="<?php if (isset($_POST['output'])) echo $_POST['output']; ?>" />
		<input type="hidden" id="signatureid" name="signatureid" value="<?php if (isset($_POST['signatureid'])) echo $_POST['signatureid']; ?>" />
		<input type="hidden" id="refereescore" name="refereescore" value="<?php if (isset($_POST['refereescore'])) echo $_POST['refereescore']; ?>" />
		
		<table width='75%' cellspacing=5>
			<tr>
				<td>Date of Match</td>
				<td>
					<input type="text" class="datepicker" id="matchdate" name="matchdate" value="<?php if (isset($_POST['matchdate'])) echo $_POST['matchdate']; ?>" />
				</td>
				<td>Age Group</td>
				<td>
					<?php createCombo("agegroupid", "id", "name", "{$_SESSION['DB_PREFIX']}teamagegroup", "WHERE teamid = " . getLoggedOnTeamID(), true); ?>
				</td>
			</tr>
			<tr>
				<td>League / Cup</td>
				<td>
					<SELECT id="leaguecup" name="leaguecup">
						<OPTION value="L">League</OPTION>
						<OPTION value="C">Challenge Cup</OPTION>
						<OPTION value="T">Challenge Trophy</OPTION>
					</SELECT>
				</td>
				<td>Division</td>
				<td>
					<SELECT id="division" name="division">
						<OPTION value="X">N/A</OPTION>
						<OPTION value="P">Premier</OPTION>
						<OPTION value="1">Division 1</OPTION>
						<OPTION value="2">Division 2</OPTION>
						<OPTION value="3">Division 3</OPTION>
						<OPTION value="4">Division 4</OPTION>
						<OPTION value="5">Division 5</OPTION>
						<OPTION value="6">Division 6</OPTION>
					</SELECT>
				</td>
			</tr>
			<tr>
				<td>Home Team</td>
				<td>
					<?php createLazyCombo("hometeamid", "id", "name", "{$_SESSION['DB_PREFIX']}team", "", true, 40); ?>
				</td>
				<td>Home Team Score</td>
				<td>
					<input type="text" size="2" id="hometeamscore" name="hometeamscore" class="score"  value="<?php if (isset($_POST['hometeamscore'])) echo $_POST['hometeamscore']; else echo "0"; ?>" />
				</td>
			</tr>
			<tr>
				<td>Away Team</td>
				<td>
					<?php createLazyCombo("oppositionid", "id", "name", "{$_SESSION['DB_PREFIX']}team", "", true, 40); ?>
				</td>
				<td>Away Team Score</td>
				<td>
					<input type="text" size="2" id="awayteamscore" name="awayteamscore" class="score"  value="<?php if (isset($_POST['awayteamscore'])) echo $_POST['awayteamscore']; else echo "0"; ?>"/>
				</td>
			</tr>
		</table>
		<hr>
		<i>Please only include the players who played in the match</i><br><br>
		<div id="players">
		</div>
		<input class="submitbutton" type="button" onclick="processCard()" value="Next"></input>
	</form>
	<script>
		$(document).ready(
				function() {
					$("#leaguecup").change(
							function() {
								if ($(this).val() != "L") {
									$("#division").val("X");
									$("#division").attr("disabled", true);

								} else {
									$("#division").attr("disabled", false);
								}
							}
						);
					
					$("#agegroupid").change(
							function() {
								callAjax(
										"finddata.php", 
										{ 
											sql: "SELECT age FROM <?php echo $_SESSION['DB_PREFIX'];?>teamagegroup WHERE id = " + $(this).val()
										},
										function(data) {
											if (data.length == 1) {
												var agegroup = data[0].age;

												if (agegroup < 12) {
													$("#leaguecup").attr("disabled", true);
													$("#leaguecup").val("L");
													$("#division").attr("disabled", false);
													$("#orderform").attr("action", "match2u7.php");
													
												} else {
													$("#leaguecup").attr("disabled", false);
													$("#orderform").attr("action", "match2.php");
												}
											}
										},
										false
									);
								
								$.ajax({
									url: "createplayerlist.php",
									dataType: 'html',
									async: false,
									data: {
										agegroupid: $("#agegroupid").val(),
										selected: '<?php echo (isset($_POST['player']) ? json_encode($_POST['player']) : "null"); ?>'
									},
									type: "POST",
									error: function(jqXHR, textStatus, errorThrown) {
										alert(errorThrown);
									},
									success: function(data) {
										$("#players").html(data).trigger("change");
									}
								});								
							}
						);

<?php 
					if (isset($_POST['homeaway'])) echo "$('#homeaway').val('" . $_POST['homeaway' ] . "');";
					if (isset($_POST['division'])) echo "$('#division').val('" . $_POST['division' ] . "');";
					if (isset($_POST['leaguecup'])) echo "$('#leaguecup').val('" . $_POST['leaguecup' ] . "');";
					if (isset($_POST['agegroupid'])) echo "$('#agegroupid').val('" . $_POST['agegroupid' ] . "').trigger('change');";
					if (isset($_POST['oppositionid'])) echo "$('#oppositionid').val('" . $_POST['oppositionid' ] . "');";
					if (isset($_POST['oppositionid_lazy'])) echo "$('#oppositionid_lazy').val('" . $_POST['oppositionid_lazy' ] . "');";
					if (isset($_POST['hometeamid'])) echo "$('#hometeamid').val('" . $_POST['hometeamid' ] . "');";
					if (isset($_POST['hometeamid_lazy'])) echo "$('#hometeamid_lazy').val('" . $_POST['hometeamid_lazy' ] . "');";
					?>
				}
			);
		
	function processCard() {
		if (! isDate($("#matchdate").val())) {
			pwAlert("Date of Match must be a valid date");
			return false;
		}
		
		if ($("#agegroupid").val() == 0) {
			pwAlert("Team must be specified");
			return false;
		}
		
		if ($("#hometeamid_lazy").val() == "") {
			pwAlert("Home team must be specified");
			return false;
		}
		
		if ($("#leaguecup").val() == "L" && $("#division").val() == "X") {
			pwAlert("Division must be specified");
			return false;
		}
		
		if ($("#oppositionid_lazy").val() == "") {
			pwAlert("Away team must be specified");
			return false;
		}
		
		$("#orderform").submit();
	}
	</script>
</div>
<?php include("system-footer.php"); ?>

