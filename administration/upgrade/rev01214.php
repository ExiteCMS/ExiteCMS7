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
$_revision = '1214';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(13,00,0,2,01,2008), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Added session management");

// array to store the commands of this update
$commands = array();

// add the config variables for session management
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('session_gc_maxlifetime', '2592000')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('session_name', 'ExiteCMSid')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('session_gc_probability', '1')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('session_gc_divisor', '100')");

// add the table for session management
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##sessions (
  session_id CHAR(50) NOT NULL default '',
  session_ua CHAR(32) NOT NULL default '',
  session_started int(10) NOT NULL default '0',
  session_expire int(10) NOT NULL default '0',
  session_user_id mediumint(8) NOT NULL default '0',
  session_ip CHAR(15) NOT NULL default '',
  session_data TEXT NOT NULL default '',
  PRIMARY KEY  (session_id)
) ENGINE=MyISAM;");
?>