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
	<?php foreach ($groups as $g) {
		echo "<div class='group-box'>";
			echo "<div class='group-title'>".$g->name."</div>";
			foreach ($g->members as $m) {
				echo "<div class='member-box'>";
					echo "<span class='member-title'>".$m->user->rname."</span>";
					echo "<span class='member-perms'>PERMISSIONS</span>";
				echo "</div>";
			}
		echo "</div>";
	}?>
</div>
<div id='personal-wrap'>
	<div id='personal-wrap-title'>Personal Settings</div>
	
</div>
</div> <!-- End Wrap -->
<?php echo $footer; ?>
</body>
<script type='text/javascript'>
	//Set Javascript variables
	var groups = <?php echo json_encode($groups); ?>;
</script>
</html>
