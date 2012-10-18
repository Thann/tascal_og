<?php echo doctype(); ?>
<html>
<head>
	<title><?php echo $title; ?></title>
	
	<?php echo $application_css; ?>
	<link rel="stylesheet" type="text/css" href='<?php echo css_url()."fullcalendar.css"; ?>'/>
	<script type='text/javascript' src='<?php echo js_url() . "jquery-1.8.1.min.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "jquery-ui-1.8.23.custom.min.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "fullcalendar.min.js"; ?>' ></script>
	<script type='text/javascript' src='<?php echo js_url() . "gcal.js"; ?>' ></script>
	<?php echo $application_js; ?>

	<!--<link rel='shortcut icon' type='image/png' href='<?php echo  "img_url()" . "favicon.png"; ?>' > -->
	<script type='text/javascript'>
		//~ var TEST = "woah";	
		var php_ret = <?php echo json_encode($java_vars); ?>;
	</script>
</head>
<body>
<div id='wrap'>
<div id='tasks'>
	<div id="new-task" class="tasks">
		<input id="new-task-input" type="text" name="new-task-name" size=17></input>
	</div>
	<?php foreach ($tasks as $t){
		echo "<div id='task_".$t["id"]."' class='tasks' desc='".$t["desc"]."' >".$t["name"]."</div>";
	}?>
</div>
<div id='calendar'></div>
</div>
</body>
</html>
