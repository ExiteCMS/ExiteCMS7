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
$_revision = 780;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(22,00,0,8,27,2007), 
					'title' => "Required updates for ExiteCMS v6.2 rev.".$_revision,
					'description' => "Dropped support for the Shoutbox (now an optional module), added forum thumbnail threshold settings."
				);

// array to store the commands of this update
$commands = array();

// database changes

// remove shoutbox as a standard admin module
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##admin WHERE admin_link = 'shoutbox.php'");

// add the maintenance body color field to the settings table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD maintenance_color CHAR(10) NOT NULL DEFAULT '#FFB900' AFTER maintenance_message");

// add seperate max dimensions for images in the forum posts
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD forum_max_w SMALLINT(4) NOT NULL DEFAULT 600 AFTER forum_flags");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD forum_max_h SMALLINT(4) NOT NULL DEFAULT 600 AFTER forum_max_w");

// new user field to indicate that this user is a webmaster
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##users ADD user_webmaster TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0 AFTER user_password");
?>