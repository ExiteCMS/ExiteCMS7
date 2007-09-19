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
$_revision = 800;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('IN_FUSION')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 'date' => mktime(12,0,0,9,1,2007), 'description' => "Required updates for PLi-Fusion v7.00 rev.".$_revision);

// array to store the commands of this update
$commands = array();

// database changes

// rename the main menu navigation panel
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##site_links SET panel_name = 'main_menu_panel WHERE panel_name = 'navigation_panel'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##panels SET panel_filename = 'main_menu_panel' WHERE panel_filename = 'navigation_panel'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = 'Menu System' WHERE admin_rights = 'SL'");

// add the aidlink switch to the site_links table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##site_links ADD link_aid TINYINT(1) UNSIGNED DEFAULT '0' AFTER link_window");

// add the news_latest switch to the settings table 
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD news_latest TINYINT(1) UNSIGNED DEFAULT '0' AFTER news_items");

// drop the panel_state table. Not needed anymore, replaced by cookies
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##panel_state");
?>