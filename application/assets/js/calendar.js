$(document).ready( function()
{
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();

	$("#calendar").fullCalendar(
	{
		//options
		//theme:true,
		header:
		{
			left:   'prev,next today',
			center: 'title',
			right:  'month,agendaWeek,agendaDay'
		},
		editable:true,
		events: [
				{
					title: 'All Day Event',
					start: new Date(y, m, 1)
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2)
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/'
				}
			],

		//~ eventSources:
		//~ [
			//~ {
				//~ url:'agenda_data',
				//~ //type: 'POST',
				//~ data: {
					//~ eid: 'some number',
					//~ long_title: 'some desctiption',
					//~ section: 'true or false',
				//~ },
				//~ error: function() {
					//~ alert('there was an error while fetching events!');
				//~ },
			//~ }
			//~ //Other sources eg. GCal
		//~ ],
		eventClick: function(calEvent, jsEvent, view) {
			alert('Event: ' + calEvent.title + '\nDesc: ' + calEvent.long_title + '\nSection: ' + calEvent.section);
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
	 } );
	 
	 //~ $("#agenda").css( "margin-top", 0 - $("#agenda").height()/2 );
});
