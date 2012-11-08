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
<div id='task-group-wrap'>
<?php $js_tasks = array(); ?>
<?php foreach ($tasks as $group) {?>
<div class='group-box'>
	<div id='group-<?php echo $group['group']->gid; ?>' class='group-title'><?php echo $group['group']->title; ?>
		<span><img gid='<?php echo $group['group']->gid; ?>' class='group-vis-icon' src='<?php echo img_url()."eye2.png"; ?>'></span>
	</div>
	<div class='group-toggle' style='display:none;'>
	<div id="new-task" style='background:<?php echo $default_color ?>;'>
		<?php echo form_input(array('gid'=>$group['group']->gid,'class'=>'new-task-input','name'=>'title','placeholder'=>'New Task','size'=>17,'autocomplete'=>'off')); ?>
	</div>
	<?php foreach (array_reverse($group['tasks']) as $t){
		if (!$t->color)
			$t->color = $default_color;
		$js_tasks[$t->tid] = $t;
		echo "<div id='".$t->tid."' style='background:".$t->color.";' class='tasks' >";
			echo "<span id='task-title'>".$t->title."</span>";
			echo "<div id='task-toggle-".$t->tid."' style='display:none;' class='task-toggle';'>";
				echo "<span id='task-desc'>".$t->desc."</span>";
				echo "<button id='task-button-".$t->tid."' tid='".$t->tid."' class='task-button'>edit</button>";
			echo "</div>";
		echo "</div>";
	}?>
	<!-- The following is a template for new tasks that will be added with JS. -->
	<div id='hidden-task' class='tasks' style='display:none'>
		<span id='task-title'></span>
		<div id='task-toggle-0' style='display:none;' class='task-toggle'>
			<span id='task-desc'><p><br></p></span>
			<button id='task-button-0' class='task-button'>edit</button>
		</div>
	</div>
	</div>
</div>
<br>
<?php } //end: foreach?>
</div>
<div id='calendar'></div>
</div>
<!-- The following divs will be hidden, and displayed as dialogs. -->
<div id='task-edit-dialog' title='Edit Task' style='display:none;'>
	<?php echo "<div id='task-edit-box'>";
	echo form_input(array('id'=>'task-edit-title','name'=>'title','placeholder'=>'Title','size'=>25));
	echo "<br>";
	echo "<textarea id='task-edit-desc'></textarea>";
	echo "<div id='task-color-label'>Change color:";
	echo "<input id='task-edit-color' type='hidden' size='7' /></div>";
	echo "<br>";
	echo "<div id='settings-event-radio'>Events Display: ";
		echo "<input id='settings-event-title' type='radio' name='settings-event-radio' class='task-edit-settings-cb' /><label for='settings-event-title'>Title</label>";
		echo "<input id='settings-event-desc' type='radio' name='settings-event-radio' class='task-edit-settings-cb' /><label for='settings-event-desc'>Description</label>";
	echo "</div>";
	echo "<div id=task-edit-results></div>";
	echo "</div>"; ?>
</div>
<div id='event-edit-dialog' style='display:none;'>
	<?php echo "<div id='event-edit-box'>";
	echo "<textarea id='event-edit-desc'></textarea>";
	echo "<div id=event-edit-results></div>";
	echo "</div>"; ?>
</div>
<div id='delete-dialog' style='display:none;'>
	<div id='delete-dialog-contents'>Are you sure you want to delete this <span id='delete-dialog-type'>ERROR</span>?</div>
</div>
<?php echo $footer; ?>
</body>
<script type='text/javascript'>
	//Set Javascript variables
	var tasks = <?php echo json_encode($js_tasks); ?>;
	var events = <?php echo json_encode($events); ?>;
	var mask = <?php echo json_encode($mask); ?>;
	var base_url = "<?php echo base_url(); ?>";
	var default_color = "<?php echo $default_color; ?>";
</script>
</html>
