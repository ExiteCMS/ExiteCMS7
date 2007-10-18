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
$_revision = 760;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(12,00,0,7,28,2007), 
					'title' => "Required updates for ExiteCMS v6.2 rev.".$_revision,
					'description' => "Now banners can be activated on a per-forum basis, and support for 'broken' timezones (like +10.5) has been added. GeoIP information can now be added manually (to correct mistakes, or add non-public IP's)"
				);

// array to store the commands of this update
$commands = array();

// database changes

// update the title of the forum polls admin module
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = 'Forum Polls' WHERE admin_rights = 'PO' LIMIT 1");

// check if a forum_poll_setting default exist. If so, reset it, else create one
$result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id = '0'");
if (dbrows($result)) {
	$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##forum_poll_settings SET forum_id = 0, enable_polls = 1, create_permissions = 'G103.G5', vote_permissions = 'G101', guest_permissions = 2, require_approval = 0, lock_threads = 0, option_max = 10, option_show = 5, option_increment = 2, duration_min = 86400, duration_max = 2678400, hide_poll = 1 WHERE  forum_id = 0");
} else {
	$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##forum_poll_settings (forum_id, enable_polls, create_permissions, vote_permissions, guest_permissions, require_approval, lock_threads, option_max, option_show, option_increment, duration_min, duration_max, hide_poll) VALUES (0, 1, 'G103.G5', 'G101', 2, 0, 0, 10, 5, 2, 86400, 2678400, 1)");
}

// added option to disable the display of banners on a per-forum basis
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forums ADD forum_banners TINYINT(1) UNSIGNED NOT NULL DEFAULT '1'");

// support new homepage news assignments
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news DROP news_latest_item");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news CHANGE news_sticky news_headline TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news ADD news_latest_news TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' AFTER news_headline");

// make more room to support timezones at half-hours or quaters
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings CHANGE timeoffset timeoffset VARCHAR(6) NOT NULL DEFAULT '0'");

// rename infusions to modules
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_link = 'modules.php' WHERE admin_link = 'infusions.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_image = 'modules.gif' WHERE admin_image = 'infusions.gif'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_image = 'modules_panel.gif' WHERE admin_image = 'infusion_panel.gif'");
$commands[] = array('type' => 'db', 'value' => "RENAME TABLE ##PREFIX##infusions TO ##PREFIX##modules");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##modules CHANGE inf_id mod_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE inf_title mod_title VARCHAR(100) NOT NULL, CHANGE inf_folder mod_folder VARCHAR(100) NOT NULL, CHANGE inf_version mod_version VARCHAR(10) NOT NULL DEFAULT '0'");

// add the GeoIP exceptions feature
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##GeoIP_exceptions (ip_number CHAR(15) NOT NULL,ip_code VARCHAR(2) NOT NULL,ip_name VARCHAR(50) NOT NULL) ENGINE = MYISAM");

?>