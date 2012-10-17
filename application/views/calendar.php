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
</head>
<body>
woot
<div id='calendar'></div>
</body>
</html>
