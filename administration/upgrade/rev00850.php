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
$_revision = '850';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('ExiteCMS_INIT')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 'date' => mktime(22,07,0,9,28,2007), 'description' => "Required updates for ExiteCMS v7.00 rev.".$_revision."<br /><font color='red'>Added dynamic panels, and the possibility for modules to add user_groups. As of this revision, all directories that the webserver to write to have moved to the /files directory (for generic files) or /images (for images only).</font>");

// array to store the commands of this update
$commands = array();

// database changes

// rename panel_content to panel_code and add a new panel_template field
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##panels CHANGE panel_content panel_code TEXT NOT NULL");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##panels ADD panel_template TEXT NOT NULL AFTER panel_code");

// add a timestamp to the panels table, to track updates to dynamic panels, and give it a default value
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##panels ADD panel_datestamp INT(10) UNSIGNED NOT NULL");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##panels SET panel_datestamp = '".time()."'");

// add the language settings admin module to the admin table and give all webmasters access
$commands[] = array('type' => 'db', 'value' => "INSERT INTO TABLE ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S7', 'settings_lang.gif', 'Language Setiings', 'settings_language.php', '3')");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##users SET user_rights = CONCAT(user_rights, ".S7") WHERE user_level = 103");

// add module identification to user_groups, so they can be removed when uninstalling a module
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##user_groups ADD group_ident CHAR(4) NOT NULL AFTER group_id");

// check if we have a usergroup called sponsors, if so, add the group_ident. If not, add the user_group
$commands[] = array('type' => 'function', 'value' => "sponsor_group");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/

function sponsor_group() {
	global $db_prefix;

	$result = dbquery("SELECT * FROM ".$db_prefix."user_groups WHERE group_name = 'Sponsors'");
	if ($result) {
		$data = dbarray($result);
		$result = dbquery("UPDATE ".$db_prefix."user_groups SET group_ident = 'wE01' WHERE group_id = '".$data['group_id']."'");
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."user_groups (group_ident, group_name, group_description, group_visible) VALUES ('wE01', 'Sponsors', 'Website Sponsors', '0'");
	}
}
?>