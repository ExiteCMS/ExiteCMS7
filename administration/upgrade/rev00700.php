<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| code to make the changes to upgrade to this rev.nr.|
+----------------------------------------------------*/
// upgrade for revision
$_revision = 700;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('IN_FUSION')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
			'date' => 1184152074, 
			'description' => "Upgrade from PLi-Fusion v6.1.10 to v7.00.<br /><font color='red'>This is a major upgrade, and introduces the PLi-Fusion templated system.</font>");

// array to store the commands of this update
$commands[$_revision] = array();

// database changes
// * NO DATABASE CHANGES FOR THIS REVISION

// data changes
// * NO DATA CHANGES FOR THIS REVISION
?>