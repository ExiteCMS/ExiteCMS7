<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/

// upgrade for revision
$_revision = 700;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('ExiteCMS_INIT')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
			'date' => 1184152074, 
			'description' => "Upgrade from ExiteCMS v6.1.10 to v7.00.<br /><font color='red'>This is a major upgrade, and introduces the ExiteCMS templated system.</font>");

// array to store the commands of this update
$commands[$_revision] = array();

// database changes
// * NO DATABASE CHANGES FOR THIS REVISION

// data changes
// * NO DATA CHANGES FOR THIS REVISION
?>