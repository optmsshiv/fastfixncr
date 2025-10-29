<?php
// Database connection setup
\System.Management.Automation.Internal.Host.InternalHost = 'localhost';
\ = 'your_cpanel_db_user';
\ = 'your_db_password';
\ = 'your_database_name';

\ = new mysqli(\System.Management.Automation.Internal.Host.InternalHost, \, \, \);
if (\->connect_error) { die('Connection failed: ' . \->connect_error); }
?>
