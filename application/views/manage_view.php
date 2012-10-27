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
<div>here is where you manage stuff!</div>
<?php echo $footer; ?>
</body>
</html>
