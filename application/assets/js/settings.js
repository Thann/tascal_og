$(document).ready( function()
{
	$("#user-edit-color").miniColors({
		change: function(hex, rgb) {
			//~ console.log(hex);
		}
	});

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
});
