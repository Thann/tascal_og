$(document).ready( function()
{
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();

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
		//~ events: [
				//~ {
					//~ title: 'tester',
					//~ start: '2012-10-19T10:22:30Z'
				//~ },
				//~ {
					//~ title: 'All Day Event',
					//~ start: new Date(y, m, 1)
				//~ },
				//~ {
					//~ title: 'Long Event',
					//~ start: new Date(y, m, d-5),
					//~ end: new Date(y, m, d-2)
				//~ },
				//~ {
					//~ id: 999,
					//~ title: 'Repeating Event',
					//~ start: new Date(y, m, d-3, 16, 0),
					//~ allDay: false
				//~ },
				//~ {
					//~ id: 999,
					//~ title: 'Repeating Event',
					//~ start: new Date(y, m, d+4, 16, 0),
					//~ allDay: false
				//~ },
				//~ {
					//~ title: 'Meeting',
					//~ start: new Date(y, m, d, 10, 30),
					//~ allDay: false
				//~ },
				//~ {
					//~ title: 'Lunch',
					//~ start: new Date(y, m, d, 12, 0),
					//~ end: new Date(y, m, d, 14, 0),
					//~ allDay: false
				//~ },
				//~ {
					//~ title: 'Birthday Party',
					//~ start: new Date(y, m, d+1, 19, 0),
					//~ end: new Date(y, m, d+1, 22, 30),
					//~ allDay: false
				//~ },
				//~ {
					//~ title: 'Click for Google',
					//~ start: new Date(y, m, 28),
					//~ end: new Date(y, m, 29),
					//~ url: 'http://google.com/'
				//~ }
			//~ ],

		eventSources:
		[
			{
				url:'calendar/fetchCal',
				data: {
					eid: 'some number',
					long_title: 'some desctiption',
					section: 'true or false',
				},
				error: function() {
					alert('there was an error while fetching events!');
				},
			}
			//Other sources eg. GCal
		],
		eventClick: function(calEvent, jsEvent, view) {
			alert('Event: ' + calEvent.title + '\nDesc: ' + calEvent.desc + '\nSection: ' + calEvent.section);
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
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;
			copiedEventObject.desc = $(this).attr('desc');

			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$("#calendar").fullCalendar('renderEvent', copiedEventObject, true);
			
			$('#new-task').each(function() {
				//~ $(this).html($(this).text() + "1");
				//~ var eventObject = {
					//~ title: $.trim($(this).text())
				//~ };
				//~ $(this).data('eventObject', eventObject);
			});
		}, //end: 'drop'

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
