<?php echo doctype(); ?>
<html>
<head>
	<title><?php echo $title; ?></title>

	<?php foreach($load_css as $lib)
		echo "<link rel='stylesheet' type='text/css' href='".css_url().$lib."'/>\n\t";?>

	<?php foreach($load_js as $lib)
		echo "<script type='text/javascript' src='".js_url().$lib."'></script>\n\t";?>
	
	<link rel='shortcut icon' type='image/png' href='<?php echo img_url() . "favicon.png"; ?>' >
</head>
<body>
<?php echo $title; ?>
<div id="login-container">
	<?php echo form_open('login/validate',array('id'=>'login-form',)); //'target'=>'dummy'
	echo "<div id='input-container'>";
	echo form_input(array('id'=>'login-uname','name'=>'uname','placeholder'=>'Username','size'=>25 ,'autofocus'=>'autofocus'));
	echo form_input(array('id'=>'login-passwd','name'=>'passwd','placeholder'=>'Password','size'=>25,'type'=>'password'));
	echo "<br>";
	echo "<div id=login-results></div>";
	echo form_label("Remember me", "remember");
	echo form_checkbox(array('id'=>'login-remember','name'=>'remember','value'=>'accept'));
	echo "</div>";
	echo form_button(array('id'=>'login-button','content'=>'Log In','type'=>'submit'));
	echo form_close(); ?>
</div>
<button id='create-account-button'>Create Account</button>
<!-- The following divs will be hidden, and displayed as dialogs. -->
<div id='create-account-dialog'>
	this is madness!
</div>
</body>
</html>
