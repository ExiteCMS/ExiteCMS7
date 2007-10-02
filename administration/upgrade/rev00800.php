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
$_revision = 800;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 'date' => mktime(14,43,0,9,10,2007), 'description' => "Required updates for ExiteCMS v7.00 rev.".$_revision."<br /><font color='red'>This revision introduces sub menu's to the menu panels, with a choose of using indentation or foldable menu's.</font>");

// array to store the commands of this update
$commands = array();

// database changes

// rename the main menu navigation panel
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##site_links SET panel_name = 'main_menu_panel' WHERE panel_name = 'navigation_panel'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##panels SET panel_filename = 'main_menu_panel' WHERE panel_filename = 'navigation_panel'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = 'Menu System' WHERE admin_rights = 'SL'");

// add the aidlink switch to the site_links table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##site_links ADD link_aid TINYINT(1) UNSIGNED DEFAULT '0' AFTER link_window");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##site_links ADD link_parent TINYINT(3) UNSIGNED DEFAULT '0' AFTER link_aid");

// add the news_latest switch to the settings table 
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD news_latest TINYINT(1) UNSIGNED DEFAULT '0' AFTER news_items");

// drop the panel_state table. Not needed anymore, replaced by cookies
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##panel_state");
?>