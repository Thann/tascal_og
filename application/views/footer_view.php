<link rel="stylesheet" type="text/css" href='<?php echo css_url()."footer.css"; ?>'/>
<div class="footer">
	<span id='copyright'> &copy; 2012 Jonathan Knapp</span>
	<span id='about-link'>about</span>
	<span id='render-time'>Page rendered in <strong>{elapsed_time}</strong> seconds.</span>
</div>
<?php $this->load->view('about_view.php'); ?>
<script type='text/javascript'>
	$("#about-view").dialog({
		autoOpen: false,
		title: "About Tascal",
		width: 470,
	});
	$("#about-link").click(function() {
		$("#about-view").dialog("open")
	});
</script>
<style>
	a {outline:none;}
</style>
