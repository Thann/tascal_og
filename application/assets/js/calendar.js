$(document).ready( function()
{
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	function eventMod(event) {
		var data = new Object();
		data.eid = event.eid;
		data.tid = event.tid;
		data.start = event.start.toString();
		data.end = event.end.toString();
		$.ajax({
			type: 'POST',
			url: 'calendar/addEvent',
			dataType: 'json',
			data: data,
			success: function(ret) {
				console.log(ret);
			}
		});
	}

	$("#calendar").fullCalendar(
	{
		header:
		{
			left:   'prev,next today',
			center: 'title',
			right:  'month,agendaWeek,agendaDay'
		},
		editable:true,
		droppable: true,
		defaultView: 'agendaWeek',
		eventSources:
		[
			{
				url:'calendar/fetchCal',
				data: {
					//eid: 'some number',
					//long_title: 'some desctiption',
					//section: 'true or false',
				},
				error: function() {
					alert('there was an error while fetching events!');
				},
			}
			//Other sources eg. GCal
		],
		eventClick: function(calEvent, jsEvent, view) {
			alert('Event: ' + calEvent.title + '\nDesc: ' + calEvent.desc );
			//~ // change the border color just for fun
			//~ //$(this).css('border-color', 'red');
		},
		// Things we want to implement later. #BETA
		//~ disableResizing: true,
		//~ disableDragging: true,
		//~ drop: function(date) {
			//~ $.ajax({
				//~ type: "POST",
				//~ url: 'agenda_data_post',
				//~ data: 'id=' + event.id + '&start=' + event.start + '&end=' + event.end,
			//~ })
		//~ }

		// this function is called when something is dropped..
		drop: function(date, allDay) {
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');

			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);

			// assign it the date that was reported
			copiedEventObject.eid = 0;
			copiedEventObject.start = date;
			copiedEventObject.tid = $(this).attr('id');
			copiedEventObject.desc = $(this).attr('desc');
			copiedEventObject.color = $(this).attr('color');

			copiedEventObject.end = new Date;
			copiedEventObject.end.setTime(date.getTime()+3*3600000); //60*60*1000 = miliseconds in an hour.
			copiedEventObject.allDay = allDay;

			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$("#calendar").fullCalendar('renderEvent', copiedEventObject, true);

			eventMod(copiedEventObject);
		}, //end: 'drop'
		//these are set inside functions to prevent them from being run on load.
		eventDrop: function(event) {
			eventMod(event);
		},
		eventResize: function(event) {
			eventMod(event);
		},

	} );

	function makeTasksDragable() {
		$(".tasks").each(function() {
			var eventObject = {
				title: $.trim($(this).text())
			};
			$(this).data('eventObject', eventObject);
			$(this).draggable({
				zIndex: 999,
				revert: true,	  // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag

			});
		});
		$("#new-task").draggable({cancel: '#new-task'});
	};
	makeTasksDragable();

	$("#new-task-input").keypress(function(event){
		if(event.keyCode == 13){
			$("#new-task-form").ajaxSubmit({
				success: function(responseText) {
					ret = jQuery.parseJSON( responseText );
					//alert(ret);
					console.log(ret);
				}
			});
			$("#task-box div:nth-child(2)").before("<div class='tasks'>"+$("#new-task-input").val()+"</div>")	;
			$("#new-task-input").val("");
			makeTasksDragable();
			//~ return false;
			event.preventDefault();
		}
	});
});
