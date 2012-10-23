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
			//~ icons: {
				//~ primary: "ui-icon-gear"
			//~ },
		}).click(function( event ) {
			//~ alert("clicked");
			$("#task-edit-dialog").data("task",$(this)).dialog("open");
			return false;
		});
	};

	//Make all tasks draggable, etc.
	$(".tasks").each(function() {
		conditionTask($(this));
	});

	//Set the title, desc, and color for a task div.
	function populateTask(tid) {
		var div = $("#"+tid+".tasks");
		div.css('background-color', tasks[tid].color);
		div.children("#task-title").html(tasks[tid].title);
		div.children("div.task-toggle").children("#task-desc").html(tasks[tid].desc);
	};

	//Create a new Task
	$("#new-task-input").keypress(function(event){
		if(event.keyCode == 13){
			//make sure the form is not empty.
			if ($("#new-task-input").val()=="")
				return false;
			var data = $("#new-task-form").formSerialize();
			var url = $("#new-task-form").attr('action');
			$.ajax({
				type: "POST",
				url: url,
				data: data,
			}).done(function( responseText ) {
				ret = jQuery.parseJSON( responseText );
				tasks[ret.task.tid] = ret.task;
				tasks[ret.task.tid].color = default_color;
				$("div.tasks:first").before("<div id='0' class='tasks'>"+$("#hidden_task").html()+"</div>");
				$("div.tasks:first").attr('id',ret.task.tid);
				$("button.task-button:first").attr('tid',ret.task.tid);
				//$("#task-button-0").attr('id',"task-button-"+ret.task.tid);
				$("#new-task-input").val("");
				populateTask(ret.task.tid);
				conditionTask($("#"+ret.task.tid));
			});
			event.preventDefault();
		}
	});

	//Dialog for editing tasks.
	$("#task-edit-dialog").dialog({
		autoOpen: false,
		//height: 317,
		width: 371,
		buttons: {
			"Save": function() {
				var data = {
					desc: $("#task-edit-desc").html(),
					title: $("#task-edit-title").val(),
					color: $("#task-edit-color").val(),
					tid: $(this).data("task").attr('tid'),
				};
				if (!data.desc)
					data.desc = "<p><br></p>"
				$.ajax({
					type: "POST",
					url: "calendar/updateTask",
					data: data,
				}).done(function( responseText ) {
					//~ ret = jQuery.parseJSON( responseText );
					//~ console.log(ret);
					//#TODO: the events should be updated via JS to reduce server load.
					$("#calendar").fullCalendar('refetchEvents');
				});
				tasks[data.tid] = data;
				populateTask(data.tid);
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			},
		},
		open: function() {
			//make the enter button click 'save'.
			$("#task-edit-dialog").keypress(function(e) {
				if (e.keyCode == $.ui.keyCode.ENTER) {
					$(this).parent().find("button:eq(0)").trigger("click");
				}
			});
			var tid = $(this).data("task").attr('tid');
			$("#task-edit-title").val(tasks[tid].title);
			$("#task-edit-desc").html(tasks[tid].desc);
			$("#task-edit-color").miniColors('value',tasks[tid].color);
		},
		close: function() {
			
		}
	});

	$("#task-edit-desc").tinymce({
		script_url : base_url+'application/assets/js/libs/tiny_mce/tiny_mce.js',
		content_css : base_url+'application/assets/css/libs/tinymce-content.css',
		theme : "advanced",
		theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink",
		theme_advanced_statusbar_location : "",
	});

	$("#task-edit-color").miniColors({
		change: function(hex, rgb) {
			//~ console.log(hex);
		}
	});
});
