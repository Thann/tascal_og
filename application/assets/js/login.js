$(document).ready( function()
{
	$("#login-form").ajaxForm({
		success: function(responseText) {
			if( responseText != "fail" )
				window.location.href = responseText;
			else{
				$("#login-results").html('Bad username or password.');
				$("#login-passwd").val("");
			}
		}
	});
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
		height: 300,
		width: 350,
		close: function() {
			//allFields.val("").removeClass("ui-state-error");
		}
	});
});
