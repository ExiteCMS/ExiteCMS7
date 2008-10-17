<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/

// upgrade for revision
$_revision = '1858';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(23,00,0,10,17,2008), 
					'title' => "Required updates for ExiteCMS v7.2 rev.".$_revision,
					'description' => "Improved the login procedure, added support for secure logins.");

// array to store the commands of this update
$commands = array();

// database changes

// add the new config values to the configuration table
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('auth_ssl', '0')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('auth_required', '0')");
?>
