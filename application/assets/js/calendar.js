$(document).ready( function()
{
	function eventMod(event) {
		var data = new Object();
		data.eid = event.eid;
		data.tid = event.tid;
		data.allDay = event.allDay;
		data.end = event.end.toString();
		data.start = event.start.toString();
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

	//Make a task object draggable, expandable, etc.
	function conditionTask(task) {
		var eventObject = {
			title: 'ERROR'
		};
		task.data("eventObject", eventObject);
		task.draggable({
			zIndex: 999,
			revert: true,
			revertDuration: 0
		});
		//activate expanding functionality
		task.bind('click',function(){
			if (!task.children("div.task-toggle").is(":visible")){
				//only have one expanded at a time.
				$("div.task-toggle").hide("fast");
				task.children("div.task-toggle").show("fast");
			}
			else
				task.children("div.task-toggle").hide("fast");
		});
		//activate 'edit' button.
		task.children(".task-toggle").children(".task-button").button({
			icons: {
				primary: "ui-icon-gear"
			},
		}).click(function( event ) {
			//~ alert("clicked");
			$("#task-edit-dialog").dialog("open");
			return false;
		});
	}

	//Make all tasks draggable, etc.
	$(".tasks").each(function() {
		conditionTask($(this));
	});

	//Create a new Task
	$("#new-task-input").keypress(function(event){
		if(event.keyCode == 13){
			//make sure the form is not empty.
			if ($("#new-task-input").val()=="")
				return false;
			var data = $("#new-task-form").formSerialize(); 
			$.ajax({
				type: "POST",
				url: "calendar/addTask",
				data: data,
			}).done(function( responseText ) {
				ret = jQuery.parseJSON( responseText );
				tasks[ret.task.tid] = ret.task;
				$("div.tasks:first").before("<div class='tasks'>"+$("#new-task-input").val()+$("#hidden_task").html()+"</div>");
				$("div.tasks:first").attr('id',ret.task.tid);
				$("#new-task-input").val("");
				conditionTask($("#"+ret.task.tid));
			});
			event.preventDefault();
		}
	});

	//Dialog for editing tasks.
	$( "#task-edit-dialog" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		close: function() {
			//allFields.val("").removeClass("ui-state-error");
		}
	});
});
