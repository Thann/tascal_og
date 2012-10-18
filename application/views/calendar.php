<?php echo doctype(); ?>
<html>
<head>
	<title><?php echo $title; ?></title>
	
	<?php echo $application_css; ?>
	<link rel="stylesheet" type="text/css" href='<?php echo css_url()."fullcalendar.css"; ?>'/>
	<script type='text/javascript' src='<?php echo js_url() . "jquery-1.8.1.min.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "jquery-ui-1.8.23.custom.min.js"; ?>' ></script>
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
<div id='wrap'>
<div id='task-box'>
	<div id="new-task" class="tasks">
		<?php echo form_open('calendar/addTask',array('id'=>'new-task-form'));
		echo form_hidden('uid',$user->uid);
		echo form_hidden('desc',"");
		echo form_input(array('id'=>'new-task-input','name'=>'title','placeholder'=>'New Task','size'=>17));
		echo form_close(); ?>
	</div>
	<?php foreach (array_reverse($tasks) as $t){
		echo "<div id='task_".$t->tid."' class='tasks' desc='".$t->desc."' >".$t->title."</div>";
	}?>
	<div id='hidden_task' class='tasks' style='display:none'></div>
</div>
<div id='calendar'></div>
</div>
<div class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</div>
</body>
</html>
