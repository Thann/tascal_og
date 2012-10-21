<?php echo doctype(); ?>
<html>
<head>
	<title><?php echo $title; ?></title>

	<?php echo $application_css; ?>
	<script type='text/javascript' src='<?php echo js_url() . "jquery-1.8.1.min.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "jquery-ui-1.8.23.custom.min.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "jquery.form.js"; ?>' ></script>
	<?php echo $application_js; ?>
	
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
	<?php //if (1) {echo "blah!"}; ?>
</div>

<div id='laame'></div>
<iframe id='dummy' style='display:none;'/>

</body>
</html>
