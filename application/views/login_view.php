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
<div id='bgimage'></div>
<?php echo $title; ?>
<div id='content'>
<div id="login-container">
	<?php echo form_open('login/validate',array('id'=>'login-form',));
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
</div>
<!-- The following divs will be hidden, and displayed as dialogs. -->
<div id='create-account-dialog' title="Create new user">
	<?php echo form_open('login/create',array('id'=>'create-form',));
	echo "<div id='create-container'>";
	echo form_input(array('id'=>'create-rname','name'=>'rname','placeholder'=>'Full Name','size'=>25));
	echo form_input(array('id'=>'create-uname','name'=>'uname','placeholder'=>'Username','size'=>25));
	echo form_input(array('id'=>'create-email','name'=>'email','placeholder'=>'Email','size'=>25));
	echo form_input(array('id'=>'create-passwd','name'=>'passwd','placeholder'=>'Password','size'=>25,'type'=>'password'));
	echo form_input(array('id'=>'create-rpasswd','name'=>'rpasswd','placeholder'=>'Repeat Password','size'=>25,'type'=>'password'));
	echo "<br>";
	echo "<div id=create-results></div>";
	echo "</div>";
	echo form_close(); ?>
</div>
</body>
</html>
