tascal
================
A simple task and time managment system.

Installation
----------------
Setup a webserver with PHP and copy everything into the webserver folder.

Configuration 
-----------------
1. In phpliteadmin.php, change `$password = "admin";` to a better password.
2. In application/config/config.php, change `$config['encryption_key'] = '';` to something more secure.
3. Make sure the the webserver has read and write permissions for the folder: `applicaton/db/` and `tascal.sqlite` contained therein.
