$(document).ready( function()
{
	//Takes in html and spits out plaintext.
	function stripHTML(html)
	{
		var tmp = document.createElement("DIV");
		tmp.innerHTML = html;
		return tmp.textContent||tmp.innerText;
	}

	function closeDialogs() {
		$("#event-edit-dialog").dialog("close");
		$("#task-edit-dialog").dialog("close");
	}

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
			//~ console.log(ret)
			//Update the event eid.
			if (event.eid == 0) {
				$.grep(events, function(e){
					return e.eid == 0;
				})[0].eid = ret;
			}
		});
	}

	//This is up here so it gets ran before the calendar fetches.
	function applyTaskSettings(event) {
		if (tasks[event.tid].settings & mask.showEventDesc) {
			event.taskTitle = event.title;
			event.title = stripHTML(event.desc);
			
		}
	};
	//apply to all events
	for (i in events) {
		applyTaskSettings(events[i]);
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
		//~ eventSources:
		//~ [
			//~ {
				//~ url:'calendar/fetchCal',
				//~ data: {
					//~ //eid: 'some number',
					//~ //long_title: 'some desctiption',
					//~ //section: 'true or false',
				//~ },
				//~ error: function() {
					//~ alert('there was an error while fetching events!');
				//~ },
			//~ }
			
			//Other sources eg. GCal
		//~ ],
		events: events,
		eventClick: function(calEvent, jsEvent, view) {
			if (!$("#group-"+calEvent.gid).children("div.group-box-toggle").is(":visible"))
				$("#group-"+calEvent.gid).trigger('click');
			//make the corresponding task open.
			if (!$("#"+calEvent.tid).children("div.task-toggle").is(":visible"))
				$("#"+calEvent.tid).trigger('click');
			//open dialog to view/edit event description.
			closeDialogs();
			$("#event-edit-dialog").data("eid",calEvent.eid).dialog("open");
		},

		// this function is called when something is dropped..
		drop: function(date, allDay) {
			// retrieve the dropped element's stored Event Object
			var eventObject = $(this).data('eventObject');
			//prevent dropping random things like dialog boxes.
			if (!eventObject)
				return false;

			tid = $(this).attr('id');
			eventObject.eid = 0;
			eventObject.desc = "";
			eventObject.tid = tid;
			eventObject.start = date;
			eventObject.gid = tasks[tid].gid;
			eventObject.color = tasks[tid].color;
			eventObject.title = tasks[tid].title;
			eventObject.end = new Date;
			eventObject.end.setTime(date.getTime()+1*3600000); //60*60*1000 = miliseconds in an hour.
			eventObject.allDay = allDay;

			events.push(eventObject);
			//~ console.log(events);

			// render the event on the calendar
			$("#calendar").fullCalendar( 'refetchEvents' );

			eventMod(eventObject);
		}, //end: 'drop'
		//these are set inside functions to prevent them from being run on load.
		eventDrop: function(event) {
			eventMod(event);
		},
		eventResize: function(event) {
			eventMod(event);
		},
	} );

	//Eye button to hide groups of events
	$("img.group-vis-icon").bind('click',function(){
		//alert("stub: show/hide events from theis group.");
		var gid = $(this).attr('gid');
		var ee = $.grep(events, function(e){return e.gid == gid});
		for (var i in ee) {
			if ($(this).hasClass("greyed-out"))
				ee[i].className =  null;
			else 
				ee[i].className = "hide-element";
		}
		$("#calendar").fullCalendar('refetchEvents');
		//Greyout when events are hidden.
		$(this).toggleClass("greyed-out");
		//prevent the task group from expanding.
		return false;
	});

	//Make the task groups expandable.
	$(this).find(".group-title").bind('click',function(){
		//~ console.log($(this));
		if (!$(this).next().is(":visible")){
			$("div.group-toggle").hide("fast");
			$(this).next().show("fast");
		}
	});
	//Initalize all groups closed except for the top one.
	$(".group-toggle:first").show();

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
			closeDialogs();
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
	$("#task-group-wrap").find(".new-task-input").keypress(function(event){
		if(event.keyCode == 13){
			//~ //make sure the input is not empty.
			if ($(this).val()=="")
				return false;
			task_box = $(this).parent();
			var data = {
				gid: $(this).attr('gid'),
				title: $(this).val(),
			};
			$.ajax({
				type: "POST",
				url: "calendar/addTask",
				data: data,
			}).done(function( responseText ) {
				ret = jQuery.parseJSON( responseText );
				tasks[ret.task.tid] = ret.task;
				tasks[ret.task.tid].color = default_color;
				task_box.next().before("<div id='0' class='tasks'>"+$("#hidden-task").html()+"</div>");
				task_box.next().attr('id',ret.task.tid);
				task_box.next().find(".task-button").attr('tid',ret.task.tid);
				populateTask(ret.task.tid);
				conditionTask($("#"+ret.task.tid));
			});
			$(this).val("");
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
				//#TODO:implement updating settings.
				data.settings = tasks[data.tid].settings
				if (!data.desc)
					data.desc = "<p><br></p>";
				$.ajax({
					type: "POST",
					url: "calendar/updateTask",
					data: data,
				}).done(function( responseText ) {
					//~ ret = jQuery.parseJSON( responseText );
					//~ console.log(ret);
				});
				//Update Task
				tasks[data.tid] = data;
				populateTask(data.tid);
				//Update Local JS Events
				var needsUpdate = $.grep(events, function(e){return e.tid == data.tid});
				for (var i in needsUpdate) {
					needsUpdate[i].color = data.color;
					needsUpdate[i].title = data.title;
					applyTaskSettings(needsUpdate[i]);
				}
				$("#calendar").fullCalendar('refetchEvents');
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			},
			Delete: function() {
				alert("Delete?!");
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
	$("#task-edit-dialog").parent().find("button:first").css('float','left');

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

	$("#event-edit-dialog").dialog({
		autoOpen: false,
		title: "this",
		width: 371,
		buttons: {
			"Save": function() {
				var eid = $(this).data("eid");
				var event = $.grep(events, function(e){return e.eid == eid})[0];
				var data = {
					desc: $("#event-edit-desc").html(),
					eid: eid,
				};
				event.desc = data.desc;
				$.ajax({
					type: "POST",
					url: "calendar/addEvent",
					data: data,
				}).done(function( responseText ) {
					//~ ret = jQuery.parseJSON( responseText );
					//~ console.log(ret);
				});
				applyTaskSettings(event);
				$("#calendar").fullCalendar('refetchEvents');
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			},
		},
		open: function() {
			//make the enter button click 'save'.
			$("#event-edit-dialog").keypress(function(e) {
				if (e.keyCode == $.ui.keyCode.ENTER) {
					$(this).parent().find("button:eq(0)").trigger("click");
				}
			});
			var eid = $(this).data("eid");
			var event = $.grep(events, function(e){return e.eid == eid})[0];
			$("#event-edit-dialog").dialog("option","title",tasks[event.tid].title);
			$("#event-edit-desc").html(event.desc);
		},
		close: function() {
			
		}
	});

	$("#event-edit-desc").tinymce({
		script_url : base_url+'application/assets/js/libs/tiny_mce/tiny_mce.js',
		content_css : base_url+'application/assets/css/libs/tinymce-content.css',
		theme : "advanced",
		theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink",
		theme_advanced_statusbar_location : "",
	});
});
