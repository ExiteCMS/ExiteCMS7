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
$_revision = 780;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('IN_FUSION')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 'date' => mktime(12,0,0,08,30,2007), 'description' => "Required updates for PLi-Fusion v7.00 rev.".$_revision);

// array to store the commands of this update
$commands = array();

// database changes

// remove shoutbox as a standard admin module
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##admin WHERE admin_link = 'shoutbox.php'");

// add the maintenance body color field to the settings table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD maintenance_color CHAR(10) NOT NULL DEFAULT '#FFB900' AFTER maintenance_message");

// add seperate max dimensions for images in the forum posts
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD forum_max_w SMALLINT(4) NOT NULL DEFAULT '600' AFTER forum_flags");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD forum_max_h SMALLINT(4) NOT NULL DEFAULT '600' AFTER forum_max_w");

// new user field to indicate that this user is a webmaster
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##users ADD user_webmaster TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER user_password");
?>