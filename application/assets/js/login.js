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
});
