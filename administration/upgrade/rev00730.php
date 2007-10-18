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
$_revision = 730;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(16,00,0,6,29,2007), 
					'title' => "Required updates for ExiteCMS v6.2 rev.".$_revision,
					'description' => "This revision adds more granular homepage new selections, and better support for timezones."
				);

// array to store the commands of this update
$commands = array();

// database changes
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD news_columns TINYINT UNSIGNED NOT NULL DEFAULT '1' AFTER news_style");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD news_items TINYINT UNSIGNED NOT NULL DEFAULT '4' AFTER news_columns");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##redirects ADD url_parms TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings CHANGE version version DECIMAL(5,2) NOT NULL DEFAULT '7.00'");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings CHANGE subversion revision SMALLINT UNSIGNED NOT NULL DEFAULT '0'");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings CHANGE news_style news_headline TINYINT UNSIGNED NOT NULL DEFAULT '0'");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##users CHANGE user_offset user_offset CHAR(6) NOT NULL DEFAULT ''");

// data changes
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##settings SET news_headline = 0");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = 'Modules & Plugins' WHERE admin_link = 'infusions.php' AND admin_page = '3'");

// update the ExiteCMS version number
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##settings SET version = '7.00'");
?>