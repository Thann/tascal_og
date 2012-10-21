<?php echo doctype(); ?>
<html>
<head>
	<title><?php echo $title; ?></title>
	
	<?php echo $application_css; ?>
	<link rel="stylesheet" type="text/css" href='<?php echo css_url()."fullcalendar.css"; ?>'/>
	<script type='text/javascript' src='<?php echo js_url() . "jquery-1.8.1.min.js"; ?>' ></script>
	<!--<script type='text/javascript' src='<?php echo js_url() . "jquery-ui-1.8.23.custom.min.js"; ?>' ></script>-->
	<script type='text/javascript' src='<?php echo js_url() . "jquery-ui-1.9.0.custom.min.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "jquery.form.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "fullcalendar.min.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "gcal.js"; ?>' ></script>
	<?php echo $application_js; ?>

	<link rel='shortcut icon' type='image/png' href='<?php echo img_url() . "favicon.png"; ?>' >
	<script type='text/javascript'>
		var php_ret = <?php echo json_encode($js_vars); ?>;
	</script>
</head>
<body>
<?php echo $header; ?>

<div id='wrap'>
<div id='task-box-1' class='task-box'>
	<div id="new-task" class="tasks" >
		<?php echo form_open('calendar/addTask',array('id'=>'new-task-form'));
		//echo form_hidden('uid',$user->uid);
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
		<div id='task-toggle-4' style='display:none;' class='task-toggle'>DESC= 
			<button id='task-button-4' class='task-button'>edit</button>
		</div>
	</div>
	<script type='text/javascript'>
		var tasks = <?php echo json_encode($js_tasks); ?>;
	</script>
</div>
<div id='calendar'></div>
</div>
<div class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</div>
</body>
</html>
