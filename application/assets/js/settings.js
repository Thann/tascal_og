$(document).ready( function()
{
	$("#user-edit-color").miniColors({
		change: function(hex, rgb) {
			//~ console.log(hex);
		}
	});

	//Used below to add members to groups.
	function add_member(event,object) {
		if(event.keyCode == 13){
			//make sure the input is not empty.
			if (object.val()=="")
				return false;
			member_box = object.parent();
			var data = {
				gid: object.attr('gid'),
				uname: object.val()
			};
			$.ajax({
				type: "POST",
				url: "settings/addMember",
				data: data,
			}).done(function( responseText ) {
				ret = jQuery.parseJSON( responseText );
				//~ console.log(ret);
				if (ret.status) {
					//add member to group
					$.grep(groups, function(e){return e.gid == data.gid})[0].members.push(ret.member);
					member_box.next().before("<div class='member-box'>"+$("#hidden-member").html()+"</div>");
					member_box.next().css('background-color',((ret.member.user.color)?ret.member.user.color:default_color));
					member_box.next().find(".member-title").html(ret.member.user.rname);
					member_box.next().attr('uid',ret.member.uid);
				}
				else 
					member_box.find("#error-msg").html(ret.msg);
			});
			object.val("");
			event.preventDefault();
		}
	}

	//Bind keypress event to each add-member-input box
	$("#group-wrap").find(".add-member-input").keypress(function(event){add_member(event,$(this))});

	//Create a new group
	$("#add-group-input").keypress(function(event){
		if(event.keyCode == 13){
			//make sure the input is not empty.
			if ($(this).val()=="")
				return false;
			group_box = $(this).parent();
			var data = {
				title: $(this).val(),
			};
			$.ajax({
				type: "POST",
				url: "settings/AddGroup",
				data: data,
			}).done(function( responseText ) {
				ret = jQuery.parseJSON( responseText );
				//~ console.log(ret);
				if (ret.status){
					groups.push(ret.group);
					console.log(groups);
					group_box.next().before("<div class='group-box'>"+$("#hidden-group").html()+"</div>");
					group_box.next().find(".add-member-input").keypress(function(event){add_member(event,$(this))});
					group_box.next().find(".add-member-input").attr('gid',ret.group.gid);
					group_box.next().find(".group-title").html(ret.group.title);
					conditionButton(group_box.next().find(".group-edit-button"));
				}
			});
			$(this).val("");
			event.preventDefault();
		}
	});

	function conditionButton(button){
		button.button();
		//make the button easily theme-able
		button.children(".ui-button-text").addClass("group-edit-button-text");
		button.click(function(){
			var gid = $(this).parent().find(".add-member-input").attr('gid');
			$("#group-edit-dialog").data("gid",gid).dialog("open");
		});
		
	}
	$(".group-edit-button").each(function(){conditionButton($(this));});

	$("#settings-save-button").button({
		
	}).click(function() {
		var bValid = true;
		$("#settings-uname").removeClass( "ui-state-error" );
		$("#settings-email").removeClass( "ui-state-error" );
		$("#settings-passwd").removeClass( "ui-state-error" );
		$("#settings-rpasswd").removeClass( "ui-state-error" );

		bValid = bValid && checkLength( $("#settings-rname"), "full name", 0, 25 );
		bValid = bValid && checkLength( $("#settings-uname"), "username", 3, 16 );
		bValid = bValid && checkLength( $("#settings-email"), "email", 6, 80 );

		bValid = bValid && checkRegexp( $("#settings-uname"), /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
		// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
		bValid = bValid && checkRegexp( $("#settings-email"), /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );

		if ($("#settings-passwd").val() != "") {
			bValid = bValid && checkRegexp( $("#settings-passwd"), /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
			bValid = bValid && checkLength( $("#settings-passwd"), "password", 5, 16 );
			if (bValid && $("#settings-passwd").val() != $("#settings-rpasswd").val()){
				$("#settings-rpasswd").addClass("ui-state-error");
				$("#settings-rpasswd").focus();
				$("#settings-rpasswd").select();
				updateTips("Passwords dont match!");
				bValid = false;
			}
		}

		if ( bValid ) {
			var data = {
				uid: user_id,
				rname: $("#settings-rname").val(),
				uname: $("#settings-uname").val(),
				email: $("#settings-email").val(),
				color: $("#user-edit-color").val()
			};
			if ($("#settings-passwd").val())
				data.passwd = $("#settings-passwd").val();
			$.ajax({
				type: "POST",
				url: "login/update",
				data: data,
			}).done(function( responseText ) {
				ret = jQuery.parseJSON( responseText );
				updateTips( ret.msg );
				if (ret.status)
					//Update the users info in the groups.
					$(".member-box").each(function(){
						if ($(this).attr('uid')==user_id) {
							$(this).css('background-color',data.color);
							$(this).children('.member-title').html(data.rname);
						}
					});
			});
		}
		return false;
	});

	//Dialog for editing tasks.
	$("#group-edit-dialog").dialog({
		autoOpen: false,
		width: 371,
		buttons: {
			"Save": function() {
				var data = {
					title: $("#group-edit-title").val(),
					gid: $(this).data("gid"),
				};
				//#TODO:implement updating settings.
				$.ajax({
					type: "POST",
					url: "settings/updateGroup",
					data: data,
				}).done(function( responseText ) {
					ret = jQuery.parseJSON( responseText );
					console.log(ret);
				});
				//Update Local JS Group
				$.grep(groups, function(e){return e.gid == data.gid})[0].title = data.title;;
				//Update Group-Box Title
				$("input.add-member-input").each(function(){
					if ($(this).attr('gid') == data.gid)
						$(this).parent().parent().children(".group-title").html(data.title);
				});
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			},
			Delete: function() {
				$("#delete-dialog").data("gid",$(this).data("gid")).dialog("open");
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
			var gid = $(this).data("gid");
			var group = $.grep(groups, function(e){return e.gid == gid})[0];
			$("#group-edit-title").val(group.title);
			if (group.owner != user_id)
				//Hide delete button, because only the owner can delete a group
				$(this).parent().find("button:eq(2)").css("display","none");
		},
		close: function() {
			
		}
	});

	$("#delete-dialog").dialog({
		autoOpen: false,
		title: "Permanently Delete?",
		width: 435,
		buttons: {
			Delete: function() {
				var gid = $(this).data("gid");
				$.ajax({
					type: "POST",
					url: "settings/rmGroup",
					data: {gid: gid},
				}).done(function( responseText ) {
					ret = jQuery.parseJSON( responseText );
					//~ console.log(ret);
					if (ret.status) {
						for (i in groups) {
							if (groups[i].gid == gid){
								groups.splice(i,1);
								break;
							}
						}
						$("input.add-member-input").each(function(){
							if ($(this).attr('gid') == gid) {
								$(this).parent().parent().hide("slow");
								$(this).attr('gid', "NULL");
							}
						});
					}
					else
						alert(ret.msg)
				});
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			},
		},
		open: function() {
			$("#delete-dialog-type").html("group");
		},
	});
});
