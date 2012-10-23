$(document).ready( function()
{
	//~ var name = $( "#name" ),
	//~ email = $( "#email" ),
	//~ password = $( "#password" ),
	//~ allFields = $( [] ).add( name ).add( email ).add( password ),
		
	function updateTips( t ) {
		$("#create-results")
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			$("#create-results").removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checkLength( o, n, min, max ) {
		if ( o.val().length > max || o.val().length < min ) {
			o.focus();
			o.select();
			o.addClass( "ui-state-error" );
			updateTips( "Length of " + n + " must be between " +
				min + " and " + max + "." );
			return false;
		} else {
			return true;
		}
	}

	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			o.focus();
			o.select();
			o.addClass( "ui-state-error" );
			updateTips( n );
			return false;
		} else {
			return true;
		}
	}

	$("#login-form").submit(function() {
		var data = $("#login-form").formSerialize(); 
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
	

	//~ $("#login-form").ajaxForm({
		//~ success: function(responseText) {
			//~ if( responseText != "fail" )
				//~ window.location.href = responseText;
			//~ else{
				//~ $("#login-results").html('Bad username or password.');
				//~ $("#login-passwd").val("");
			//~ }
		//~ }
	//~ });
	$("#login-button").button();

	$("#create-account-button").button({
		//~ icons: {
			//~ primary: "ui-icon-gear"
		//~ },
	}).click(function( event ) {
		//~ alert("clicked");
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

				if ( bValid ) {
					//alert("valid!");
					var data = $("#create-form").formSerialize(); 
					$.ajax({
						type: "POST",
						url: "login/create",
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
