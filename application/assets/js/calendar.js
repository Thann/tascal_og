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
			type: "POST",
			url: "calendar/addEvent",
			data: data,
		}).done(function( ret ) {
			//console.log(ret)
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
			alert('Event: ' + calEvent.title + '\nDesc: ' + calEvent.desc + '\nEid: '+ calEvent.eid);
			
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
			tid = $(this).attr('id');
			copiedEventObject.eid = 0;
			copiedEventObject.desc = "";
			copiedEventObject.tid = tid;
			copiedEventObject.start = date;
			copiedEventObject.color = tasks[tid].color;
			copiedEventObject.title = tasks[tid].title;
			copiedEventObject.end = new Date;
			copiedEventObject.end.setTime(date.getTime()+1*3600000); //60*60*1000 = miliseconds in an hour.
			copiedEventObject.allDay = allDay;

			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$("#calendar").fullCalendar('renderEvent', copiedEventObject, true);

//console.log(copiedEventObject);
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
				title: 'ERROR'
			};
			$(this).data('eventObject', eventObject);
			$(this).draggable({
				zIndex: 999,
				revert: true,	  // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});
			$(this).click(function(){
				if (!$(this).children("div.task-toggle").is(":visible")){
					//only have one expanded at a time.
					$("div.task-toggle").hide("fast");
					$(this).children("div.task-toggle").show("fast");
				}
				else
					$(this).children("div.task-toggle").hide("fast");
			});
		});
		$("#new-task").draggable({cancel: '#new-task'});
	};
	makeTasksDragable();

	//Create a new Task
	$("#new-task-input").keypress(function(event){
		if(event.keyCode == 13){
			var data = $("#new-task-form").formSerialize(); 
			$.ajax({
				type: "POST",
				url: "calendar/addTask",
				data: data,
			}).done(function( responseText ) {
				ret = jQuery.parseJSON( responseText );
				console.log(ret);
				tasks[ret.task.tid] = ret.task;
				$("div.tasks:first").next().before("<div class='tasks'>"+$("#new-task-input").val()+$("#hidden_task").html()+"</div>");
				$("div.tasks:first").next().attr('id',ret.task.tid);
				$("#new-task-input").val("");
				makeTasksDragable();
			});
			//$("#task-box div:nth-child(2)").before("<div class='tasks'>"+$("#new-task-input").val()+"<button id='task-button-0 class='task-button'>edit</button></div>")	;
			//~ return false;
			event.preventDefault();
		}
	});
	//~ $( "button" ).button();
	$("button").button({
		icon:'ui-icon-gear',
	}).click(function( event ) {
			alert("clicked");
			return false;
	});
	//~ $("#16").each(function() {
		//~ $(this).click(function() {
			//~ $("#task-desc-16").removeClass("open-task").show("fast");
			//alert("woah");
		//~ });
	//~ });
});
