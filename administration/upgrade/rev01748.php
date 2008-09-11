<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/

// upgrade for revision
$_revision = '1748';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(14,30,0,9,11,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Added a webbased file explorer to remotely manage your webserver");

// array to store the commands of this update
$commands = array();

// add the new user groups for the module "eXtplorer"
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##user_groups (group_ident, group_name, group_groups, group_rights, group_description, group_forumname, group_visible) 
				VALUES ('EX01', 'File Administrators', '', '', 'File Administrators', 'File Administrator', '0')");

// add the personal message searche to the search table
$commands[] = array('type' => 'function', 'value' => "rev1748_add_to_menu");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function rev1748_add_to_menu() {
	global $db_prefix, $settings;

	// determine the next menu order number
	$order = dbfunction("MAX(link_order)", "site_links") + 1;

	// get the group ID for the file admin group
	$result = dbquery("SELECT group_id FROM ".$db_prefix."user_groups WHERE group_ident = 'EX01'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$group = $data['group_id'];
	} else {
		$group = 103;
	}

	$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_locale, link_url, panel_name, link_visibility, link_position, link_window, link_order) 
						VALUES('File Explorer', '".$settings['locale_code']."', 'includes/eXtplorer/index.php', 'main_menu_panel', ".$group.", 1, 1, ".$order.")");
}
?>
