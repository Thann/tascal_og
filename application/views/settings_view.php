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
<?php echo $header; ?>
<div id='wrap'>
<div id='group-wrap'>
	<div id='group-wrap-title'>Group Settings</div>
	<div id="add-group">
		<?php echo form_open('settings/addGroup',array('id'=>'add-group-form'));
		echo form_input(array('class'=>'add-group-input','name'=>'title','placeholder'=>'Add Group','size'=>25));
		echo form_close(); ?>
	</div>
	<?php foreach ($groups as $g) {
		echo "<div class='group-box'>";
			echo "<div class='group-title'>".$g->title."</div>";
			echo "<div id='add-member' style='background:".$default_color.";'>";
				echo form_open('settings/addMemeber',array('id'=>'add-member-form'));
				echo form_input(array('gid'=>$g->gid,'class'=>'add-member-input','name'=>'name','placeholder'=>'Add Member','size'=>25));
				echo form_close();
			echo "</div>";
			foreach ($g->members as $m) {
				echo "<div class='member-box' style='background:".$default_color.";'>";
					echo "<span class='member-title'>".$m->user->rname."</span>";
					echo "<span class='member-perms'>PERMISSIONS</span>";
				echo "</div>";
			}
		echo "</div>";
	}?>
</div>
<div id='personal-wrap'>
	<div id='personal-wrap-title'>Personal Settings</div>
	<?php echo form_open('',array('id'=>'settings-form',));
	echo form_input(array('id'=>'settings-rname','name'=>'rname','placeholder'=>'Full Name','size'=>25),$user->rname);
	echo "<br>";
	echo form_input(array('id'=>'settings-uname','name'=>'uname','placeholder'=>'Username','size'=>25),$user->uname);
	echo "<br>";
	echo form_input(array('id'=>'settings-email','name'=>'email','placeholder'=>'Email','size'=>25),$user->email);
	echo "<br>";
	echo form_input(array('id'=>'settings-passwd','name'=>'passwd','placeholder'=>'New Password','size'=>25,'type'=>'password'));
	echo "<br>";
	echo form_input(array('id'=>'settings-rpasswd','name'=>'rpasswd','placeholder'=>'Repeat Password','size'=>25,'type'=>'password'));
	echo "<br>";
	echo "<div id=create-results></div>";
	echo form_button(array('id'=>'settings-save-button','content'=>'Save','type'=>'submit'));
	echo form_close(); ?>
</div>

</div> <!-- End Wrap -->
<?php echo $footer; ?>
</body>
<script type='text/javascript'>
	//Set Javascript variables
	var groups = <?php echo json_encode($groups); ?>;
	var user_id = <?php echo json_encode($user->uid); ?>;
</script>
</html>
