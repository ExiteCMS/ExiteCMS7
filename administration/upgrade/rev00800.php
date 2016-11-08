<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id::                                                               $|
+----------------------------------------------------------------------+
| Last modified by $Author::                                          $|
| Revision number $Rev::                                              $|
+---------------------------------------------------------------------*/
if (strpos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false || !defined('INIT_CMS_OK')) die();

// upgrade for revision
$_revision = 800;

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(14,00,0,9,10,2007),
					'title' => "Required updates for ExiteCMS v6.2 rev.".$_revision,
					'description' => "This revision introduces sub menu's to the menu panels, with a choose of using indentation or foldable menu's."
				);

// array to store the commands of this update
$commands = array();

// database changes

// rename the main menu navigation panel
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##site_links SET panel_name = 'main_menu_panel' WHERE panel_name = 'navigation_panel'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##panels SET panel_filename = 'main_menu_panel' WHERE panel_filename = 'navigation_panel'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = 'Menu System' WHERE admin_rights = 'SL'");

// add the aidlink switch to the site_links table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##site_links ADD link_aid TINYINT(1) UNSIGNED DEFAULT 0 AFTER link_window");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##site_links ADD link_parent TINYINT(3) UNSIGNED DEFAULT 0 AFTER link_aid");

// add the news_latest switch to the settings table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD news_latest TINYINT(1) UNSIGNED DEFAULT 0 AFTER news_items");

// drop the panel_state table. Not needed anymore, replaced by cookies
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##panel_state");
?>
