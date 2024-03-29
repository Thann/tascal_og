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
<?php echo $header; ?>
<div id='wrap'>
<div id='group-wrap'>
	<div id='group-wrap-title'>Group Settings</div>
	<div id="add-group">
		<?php echo form_input(array('id'=>'add-group-input','name'=>'title','placeholder'=>'Create New Group','size'=>25,'autocomplete'=>'off')); ?>
	</div>
	<?php foreach (array_reverse($groups) as $g) {
		echo "<div class='group-box'>";
			echo "<div class='group-title'>".$g->title."</div>";
				echo "<span class='group-edit-button'>edit</span>";
			echo "<div id='add-member' style='background:".$default_color.";'>";
				echo form_input(array('gid'=>$g->gid,'class'=>'add-member-input','name'=>'name','placeholder'=>'Add Member','size'=>25));
				echo "<span id='error-msg'></span>";
			echo "</div>";
			foreach (array_reverse($g->members) as $m) {
				echo "<div class='member-box' uid='".$m->user->uid."' style='background:".(($m->user->color)?$m->user->color:$default_color).";'>";
					echo "<span class='member-title'>".$m->user->rname."</span>";
					echo "<span style='float:right;'>";
						echo "<span class='member-perms'>[PERMISSIONS]</span>";
						echo "<img src='".img_url()."leave-group.png' alt='Leave Group' class='member-quit-icon' />";
					echo "</span>";
				echo "</div>";
			}
		echo "</div>";
	}?>
	<!-- The following is a template for new groups and members that will be added with JS. -->
	<div id='hidden-group' class='group-box' style='display:none'>
		<div class='group-title'>THIS SHOULD BE HIDDEN</div>
			<span class='group-edit-button'>edit</span>
		<?php echo "<div id='add-member' style='background:".$default_color.";'>";
			echo form_input(array('gid'=>0,'class'=>'add-member-input','name'=>'name','placeholder'=>'Add Member','size'=>25));
			echo "<span id='error-msg'></span>";
		echo "</div>";
		echo "<div id='hidden-member' class='member-box' uid='".$user->uid."' style='background:".(($user->color)?$user->color:$default_color).";'>"; 
			echo "<span class='member-title'>".$user->rname."</span>";
			echo "<span style='float:right;'>";
				echo "<span class='member-perms'>[PERMISSIONS]</span>";
				echo "<img src='".img_url()."leave-group.png' alt='Leave Group' class='member-quit-icon' />";
			echo "</span>";
		echo "</div>"; ?>
	</div>
</div>
<div id='personal-wrap'>
	<div id='personal-wrap-title'>Personal Settings</div>
	<?php echo form_open('',array('id'=>'settings-form',));
	echo form_input(array('id'=>'settings-rname','name'=>'rname','placeholder'=>'Full Name','size'=>25),$user->rname)."<br>";
	echo form_input(array('id'=>'settings-uname','name'=>'uname','placeholder'=>'Username','size'=>25),$user->uname)."<br>";
	echo form_input(array('id'=>'settings-email','name'=>'email','placeholder'=>'Email','size'=>25),$user->email)."<br>";
	echo form_input(array('id'=>'settings-passwd','name'=>'passwd','placeholder'=>'New Password','size'=>25,'type'=>'password'))."<br>";
	echo form_input(array('id'=>'settings-rpasswd','name'=>'rpasswd','placeholder'=>'Repeat Password','size'=>25,'type'=>'password'))."<br>";
	echo form_input(array('id'=>'user-edit-color','name'=>'color','placeholder'=>'Color','size'=>20),($user->color)?$user->color:$default_color)."<br>";
	echo "<div id=create-results></div>";
	echo form_button(array('id'=>'settings-save-button','content'=>'Save','type'=>'submit'));
	echo form_close(); ?>
</div>
</div> <!-- End Wrap -->

<!-- The following divs will be hidden, and displayed as dialogs. -->
<div id='group-edit-dialog' title='Edit Task Group' style='display:none;'>
	<?php echo "<div id='group-edit-box'>";
	echo form_input(array('id'=>'group-edit-title','name'=>'title','placeholder'=>'Title','size'=>25));
	echo "<br>";
	echo "<div id='group-ical-url'></div>";
	echo "<br>";
	//#TODO: add group settings.
	echo "[SETTINGS]";
	echo "<div id=group-edit-results></div>";
	echo "</div>"; ?>
</div>
<div id='delete-dialog' style='display:none;'>
	<div id='delete-dialog-contents'>Are you sure you want to delete this <span id='delete-dialog-type'>ERROR</span>?</div>
</div>
<div id='confirm-dialog' style='display:none;'>
	<div id='confirm-dialog-msg'></div>
</div>

<?php echo $footer; ?>
</body>
<script type='text/javascript'>
	//Set Javascript variables
	var groups = <?php echo json_encode($groups); ?>;
	var user_id = <?php echo json_encode($user->uid); ?>;
	var default_color = "<?php echo $default_color ?>";
	var base_ical_url = "<?php echo base_url()."index.php/xapi/ical/group/"; ?>";
</script>
</html>
