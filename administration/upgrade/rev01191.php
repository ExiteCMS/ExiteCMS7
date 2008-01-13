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
$_revision = '1191';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(18,00,0,1,8,2008), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Switched from post tracking to thread tracking for unread post status, to make the forums more performant and more scalable<br /><br /><b>Note: This can run for hours if you have millions of posts_unread records.</b>!!!");

// array to store the commands of this update
$commands = array();

// add the posts unread threshold value to the config table
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('unread_threshold', '90')");

?>