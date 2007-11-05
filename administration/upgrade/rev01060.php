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
$_revision = '1060';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(23,00,0,11,04,2007), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Fix a version issue in the modules registration.<br />Added member email address validation feature.");

// array to store the commands of this update
$commands = array();

// database changes

// update the version of the Wiki module, if installed
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##modules SET mod_version = '1.0.0' WHERE mod_title = 'Wikka Wiki' AND mod_version = '1.1.6.3'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##modules SET mod_version = '1.0.1' WHERE mod_title = 'Wikka Wiki' AND mod_version = '1.1.6.4'");

// new user field to indicate that this user is a webmaster
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##users ADD user_bad_email INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER user_email");
?>