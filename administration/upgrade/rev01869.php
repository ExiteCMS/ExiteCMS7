<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/

// upgrade for revision
$_revision = '1869';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(11,00,0,10,19,2008), 
					'title' => "Required updates for ExiteCMS v7.2 rev.".$_revision,
					'description' => "Fixed issues when migrating from v7.1 to v7.2.");

// array to store the commands of this update
$commands = array();

// database changes

// rename settings_registration to settings_security (was set wrong in setup.php of v7.1)
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_link='settings_security.php', admin_image='security.gif' WHERE admin_rights='S4'");

?>
