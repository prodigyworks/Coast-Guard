<?php 
	require_once("confirmdialog.php");
	require_once("tinymce.php");
	
	createConfirmDialog("confirmdialog", "?", "confirmSchedule");
?>
<!--  Start of content -->
<link rel="stylesheet" href="css/fullcalendar.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/fullcalendar.print.css" type="text/css" media="all" />
<style>
	.ui-dialog {
		margin-top: 10px;
		min-height: 50px;
	}
<?php 	
	$sql = "SELECT A.member_id, A.fgcolour, A.bgcolour 
			FROM {$_SESSION['DB_PREFIX']}members A 
			ORDER BY A.member_id ";
	$result = mysql_query($sql);
		
	if ($result) {
		/* Show children. */
		while (($member = mysql_fetch_assoc($result))) {
			echo ".eventcat_" . $member['member_id']. " .fc-event-inner {\n";
			echo "background-color: " . $member['bgcolour'] . "  ! important;\n";
			echo "color: " . $member['fgcolour'] . "  ! important;\n";
			echo "}\n";
		}
		
	} else {
		logError($sql . " - " . mysql_error());
	}
?>
</style>
<script type="text/javascript" src="js/gcal.js"></script>
<script type="text/javascript" src="js/fullcalendar.min.js"></script>
<script>
function createSchedule() {
	$("#confirmdialog .confirmdialogbody").html("You are about to create the schedule.<br>Are you sure ?");
	$("#confirmdialog").dialog("open");
}

function confirmSchedule() {
	$("#confirmdialog").dialog("close");
	window.location.href = "confirmrota.php?id=" + rotaid;
}
</script>
<div id="confirmbutton">
   	<div class="wrapper">
<?php 
	if (isUserInRole("ADMIN")) {
?>
   		<a class='rgap2 link1' href="javascript:createSchedule()"><em><b><img src='images/publish.png' /> Create Schedule</b></em></a>
<?php		
	} else {
		$memberid = getLoggedOnMemberID();
		$bid = 5;
		$qry = "SELECT bid 
				FROM {$_SESSION['DB_PREFIX']}bid 
				WHERE memberid = $memberid";
		$result = mysql_query($qry);
		
		//Check whether the query was successful or not
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$bid = $member['bid'];
			}
		}
?>
      	<span>BID </span>
   		<input type="text" id="bid" size=4 value="<?php echo $bid; ?>" />
   		<button id="bidbutton">Save</button>
<?php 
	}
?>
   	</div>
</div>
<hr />
<div id='calendar'></div>
<div id="detaildialog" class="modal">
	<input type="hidden" id="eventid" />
	<table cellspacing=5>
		<tr>
			<td><b>Name</b></td>
			<td>
<?php 
	if (isUserInRole("ADMIN")) {
		createUserCombo("userid"); 
		
	} else {
		createUserCombo("userid", "WHERE member_id = " . getLoggedOnMemberID()); 
	}
?>
			</td>
		</tr>
		<tr>
			<td><b>Start Date</b></td>
			<td>
				<input class="datepicker" id="startdate" name="startdate" />
			</td>
		</tr>
		<tr>
			<td><b>End Date</b></td>
			<td>
				<input class="datepicker" id="enddate" name="enddate" />
			</td>
		</tr>
		<tr>
			<td><b>Watch</b></td>
			<td>
				<SELECT id="watch" name="watch">
					<OPTION value="A">A</OPTION>
					<OPTION value="B">B</OPTION>
					<OPTION value="E">Either</OPTION>
				</SELECT>
			</td>
		</tr>
	</table>
</div>

<?php

function showCalendar($currentRota) {
?>
<script>
	var rotaid = 0;
	
	$(document).ready(function() {
		$("#detaildialog").dialog({
				modal: true,
				width: 400,
				autoOpen: false,
				title: "Details",
				buttons: {
					Ok: function() {
						callAjax(
								"saverotadata.php", 
								{ 
									rotaid: rotaid,
									eventid: $("#eventid").val(),
									userid: $("#userid").val(),
									notes: "",
									startdate: $("#startdate").val(),
									enddate: $("#enddate").val(),
									watch: $("#watch").val()
								},
								function(items) {
									$("#calendar").fullCalendar('refetchEvents');
								},
								false
							);
			
						$(this).dialog("close");
					},
					"Remove": function() {
						callAjax(
								"removerotadata.php", 
								{ 
									eventid: $("#eventid").val()
								},
								function(items) {
									$("#calendar").fullCalendar('refetchEvents');
								},
								false
							);
			
						$(this).dialog("close");
					},
					Cancel: function() {
						$(this).dialog("close");
					}
				}
			});

		$("#bidbutton").click(
				function() {
					callAjax(
							"savebid.php", 
							{ 
								bid: $("#bid").val()
							},
							function(data) {
							}
						);
				}
			);

		$('#calendar').fullCalendar({
			editable: true,
			aspectRatio: 2.1,
			allDayDefault: false, 
			header: {
				left: 'prev,next today',
				center: 'title',
				right: ''
			},

			eventRender: function(event, element) {
			   element.attr('title', "Click to view " + event.title);
			},
			
			eventClick: function(calEvent, jsEvent, view) {
				if (calEvent.id != 0) {
					callAjax(
							"finddata.php", 
							{ 
								sql: "SELECT A.id, A.userid, A.watch, " +
									 "DATE_FORMAT(A.startdate, '%d/%m/%Y') AS startdate, " +
									 "DATE_FORMAT(A.enddate, '%d/%m/%Y') AS enddate " +
									 "FROM <?php echo $_SESSION['DB_PREFIX'];?>rotaitem A " + 
									 "WHERE A.id = " + calEvent.id
							},
							function(data) {
								if (data.length > 0) {
									var node = data[0];
								
									$("#eventid").val(node.id);
									$("#userid").val(node.userid);
									$("#startdate").val(node.startdate);
									$("#enddate").val(node.enddate);
									$("#watch").val(node.watch);

									$("#detaildialog").dialog("open");
								}
							}
						);
				
				}
		    },
		    
		    dayClick: function(date, element, view) {
<?php 
?>
				$("#eventid").val("");
				$("#userid").val("<?php echo getLoggedOnMemberID(); ?>");
				$("#startdate").val(formatDate(date));
				$("#enddate").val(formatDate(date));
				$("#watch").val("B");
				
				$("#detaildialog").dialog("open");
<?php 
?>
		    },
		    
		    events: function(start, end, callback) {
		    	var startYear = start.getYear();
		    	var endYear = end.getYear();
		    	
		    	if (startYear < 2000) {
		    	    startYear += 1900;
		    	}
		    	
		    	if (endYear < 2000) {
		    	    endYear += 1900;
		    	}

		    	var startDate = startYear + "-" + padZero(start.getMonth() + 1) + "-" + padZero(start.getDate());
		    	var endDate = endYear + "-" + padZero(end.getMonth() + 1) + "-" + padZero(end.getDate());

				callAjax(
						"findrotaid.php", 
						{ 
							startdate: startDate,
							enddate: endDate
						},
						function(data) {
							if (data.length > 0) {
								rotaid = data[0].id;
							}
						},
						false
					);
		    	
			    $.ajax({
	                type: 'POST',
	                url: 'currentrotadata.php',
	                async: false,
	                dataType:'json',
			        data: {
	                    rotaid: rotaid
			        },
			        error: function(error) {
			            alert('there was an error while fetching events');
			        },
			        success: function(msg) {
						var events = [];
						 
                        for(var c = 0; c < msg.length; c++){
                        	var item = msg[c];

                            events.push({
	                                id: item.id,                                
	                                title: item.title,
	                                allDay: item.allDay == "true" ? true : false,
	                                start: item.start,
	                                end: item.end,
	                                editable: true,
	                                className: item.className
	                            });
                        }
                        
                        callback(events);
                        
                        var found = false;
                        var days = 0;

                        $(".fc-widget-content").each(function() {
                            	var dayn = $(this).find(".fc-day-number").html();

                            	if (! found && dayn == 6) {
                                	found = true;
                            	}

                            	if (found && dayn == 6 && days > 1) {
                                	found = false;
                            	}

                            	if (found) {
                                	$(this).css("background-color", "yellow");
                                	
                            	} else {
                                	$(this).css("background-color", "red");
                            	}

                            	if (found) {
                                	days++;
                            	}
	                        });
			        }
			     });
		    }
		});
		
	});
	
	
	
</script>
<?php 
}
?>
