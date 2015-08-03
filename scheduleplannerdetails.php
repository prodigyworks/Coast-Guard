<?php 
	include("system-embeddedheader.php");
?>
<!--  Start of content -->
<link rel="stylesheet" href="css/fullcalendar.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/fullcalendar.print.css" type="text/css" media="all" />
<style>
	.ui-dialog {
		margin-top: 10px;
		min-height: 50px;
	}
	.fc-event-inner span {
		font-size: 10px;
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
<div id='calendar'></div>

<script>
	var scheduleid = 0;
	var year = <?php echo substr($_GET['from'], 0, 4); ?>;
	var month = <?php echo substr($_GET['from'], 5, 2); ?>;
	var date = <?php echo substr($_GET['from'], 8, 2); ?>;

	if (date == 1) {
		month--;
	}

	if (month < 0) {
		month = 11;
		year--;
	}

	$(document).ready(function() {
		$('#calendar').fullCalendar({
			editable: true,
			aspectRatio: 2.1,
			allDayDefault: false, 
			year: year,
			month: month,
			
			header: {
				left: '',
				center: 'title',
				right: ''
			},

			eventRender: function(event, element) {
			   element.attr('title', "Click to view " + event.title);
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
						"findscheduleid.php", 
						{ 
							startdate: startDate,
							enddate: endDate
						},
						function(data) {
							if (data.length > 0) {
								scheduleid = data[0].id;
							}
						},
						false
					);
		    	
			    $.ajax({
	                type: 'POST',
	                url: 'schedulerotadata.php',
	                async: false,
	                dataType:'json',
			        data: {
	                    scheduleid: scheduleid
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

                		$(".fc-event-time").each(function() {
                				if ($(this).html() == "12a") {
                					$(this).html("A");
                				}
                				
                				if ($(this).html() == "12p") {
                					$(this).html("B");
                				}
                			});

                        window.print();
			        }
			     });
		    }
		});
	});
</script>
<?php 
	include("system-embeddedfooter.php");
?>
