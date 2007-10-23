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
$_revision = '995';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(14,30,0,10,23,2007), 
					'title' => "Required updates for ExiteCMS v6.2 rev.".$_revision,
					'description' => "Moved the advertisement panels from the core to an optional module.");

// array to store the commands of this update
$commands = array();

// database changes

// delete the advertising link from the main menu
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##site_links WHERE link_url = 'advertising.php'");

// delete the advertising link from the admin menu
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##admin WHERE admin_rights = 'wE'");
?>