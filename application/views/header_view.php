<link rel="stylesheet" type="text/css" href='<?php echo css_url()."header.css"; ?>'/>
<div id='header'>
<?php echo "<span id='header-uname'>user: ".$user->uname."</span>" ?>
<?php if ($location != 'calendar')
		echo anchor('calendar', "Calendar", array('class'=>'header-link')); 
	else
		echo "<span class='header-link'>[<u>Calendar</u>]</span>"?>
<?php if ($location != 'manage')
		echo anchor('manage', "Manage", array('class'=>'header-link')); 
	else
		echo "<span class='header-link'>[<u>Manage</u>]</span>"?>
<?php echo anchor('login/logout', "<img src='".img_url()."logout.png' alt='logout' id='header-logout-icon' />"); ?>
</div>
