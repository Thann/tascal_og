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
<div id='task-box-1' class='task-box'>
	<div class='task-title'>My Tasks</div>
	<div id="new-task" >
		<?php echo form_open('calendar/addTask',array('id'=>'new-task-form'));
		echo form_hidden('desc',"");
		echo form_input(array('id'=>'new-task-input','name'=>'title','placeholder'=>'New Task','size'=>17));
		echo form_close(); ?>
	</div>
	<?php $js_tasks = array(); ?>
	<?php foreach (array_reverse($tasks) as $t){
		$js_tasks[$t->tid] = $t;
		if ($t->color)
			$bgstyle = "style='background:".$t->color.";'";
		else
			$bgstyle = "";
		echo "<div id='".$t->tid."' ".$bgstyle." class='tasks' >".$t->title;
			echo "<div id='task-toggle-".$t->tid."' style='display:none;' class='task-toggle';'>DESC=".$t->desc;
				echo "<button id='task-button-".$t->tid."' class='task-button'>edit</button>";
			echo "</div>";
		echo "</div>";
	}?>
	<div id='hidden_task' class='tasks' style='display:none'>
		<div id='task-toggle-0' style='display:none;' class='task-toggle'>DESC= 
			<button id='task-button-0' class='task-button'>edit</button>
		</div>
	</div>
	<script type='text/javascript'>
		var tasks = <?php echo json_encode($js_tasks); ?>;
	</script>
</div>
<div id='calendar'></div>
</div>
<!-- The following divs will be hidden, and displayed as dialogs. -->
<div id='task-edit-dialog'>
	this is madness!
</div>
<div class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</div>
</body>
</html>
