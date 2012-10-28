$(document).ready( function()
{
	//~ var name = $( "#name" ),
	//~ email = $( "#email" ),
	//~ password = $( "#password" ),
	//~ allFields = $( [] ).add( name ).add( email ).add( password ),

	$("#login-form").submit(function() {
		var data = {
			uname: $("#login-uname").val(),
			passwd: $("#login-passwd").val(),
			remember: false
		};
		if ($("#login-remember").is(':checked'))
			data.remember = true;
		var url = $("#login-form").attr('action');
		$.ajax({
			type: "POST",
			url: url,
			data: data,
		}).done(function( responseText ) {
			ret = jQuery.parseJSON( responseText );
			console.log(ret);
			if (ret.status)
				window.location.href = ret.url;
			else 
				$("#login-results").html(ret.msg);
				$("#login-passwd").val("");
		});
		return false;
	});
	

	$("#login-button").button();

	$("#create-account-button").button({
		
	}).click(function( event ) {
		$("#create-account-dialog").dialog("open");
		return false;
	});

	$( "#create-account-dialog" ).dialog({
		autoOpen: false,
		height: 350,
		width: 350,
		buttons: {
			"Create an account": function() {
				var bValid = true;
				$("#create-uname").removeClass( "ui-state-error" );
				$("#create-email").removeClass( "ui-state-error" );
				$("#create-passwd").removeClass( "ui-state-error" );

				bValid = bValid && checkLength( $("#create-rname"), "full name", 0, 25 );
				bValid = bValid && checkLength( $("#create-uname"), "username", 3, 16 );
				bValid = bValid && checkLength( $("#create-email"), "email", 6, 80 );
				bValid = bValid && checkLength( $("#create-passwd"), "password", 5, 16 );

				bValid = bValid && checkRegexp( $("#create-uname"), /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
				// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
				bValid = bValid && checkRegexp( $("#create-email"), /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
				bValid = bValid && checkRegexp( $("#create-passwd"), /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
				
				if (bValid && $("#create-passwd").val() != $("#create-rpasswd").val()){
					$("#create-rpasswd").addClass("ui-state-error");
					$("#create-rpasswd").focus();
					$("#create-rpasswd").select();
					updateTips("Passwords dont match!");
					bValid = false;
				}

				if ( bValid ) {
					var data = {
						rname: $("#create-rname").val(),
						uname: $("#create-uname").val(),
						email: $("#create-email").val(),
						passwd: $("#create-passwd").val()
					}
					var url = $("#create-form").attr('action');
					$.ajax({
						type: "POST",
						url: url,
						data: data,
					}).done(function( responseText ) {
						ret = jQuery.parseJSON( responseText );
						console.log(ret);
						if (ret.status)
							window.location.href = ret.url;
						else 
							updateTips( ret.msg );
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
			$("#create-account-dialog").keypress(function(e) {
				if (e.keyCode == $.ui.keyCode.ENTER) {
					$(this).parent().find("button:eq(0)").trigger("click");
				}
			});
		},
		close: function() {
			$("#create-rname").val("").removeClass( "ui-state-error" );
			$("#create-uname").val("").removeClass( "ui-state-error" );
			$("#create-email").val("").removeClass( "ui-state-error" );
			$("#create-passwd").val("").removeClass( "ui-state-error" );
			$("#create-results").text("").removeClass( "ui-state-error" );
		}
	});
});
