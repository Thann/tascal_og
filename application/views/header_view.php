<link rel="stylesheet" type="text/css" href='<?php echo css_url()."header.css"; ?>'/>
<div id='header'>
<?php echo "user: ".$user->uname ?>
<?php echo anchor('login/logout', "<img src='" . img_url() . "logout.png' alt='logout' id='header-logout-icon' />"); ?>
</div>
