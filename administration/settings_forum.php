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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("admin.settings");

// temp storage for template variables
$variables = array();

// store this modules name for the menu bar
$variables['this_module'] = FUSION_SELF;

// check for the proper admin access rights
if (!checkrights("S3") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['prune'])) require_once PATH_ADMIN."forums_prune.php";

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['attachments']) ? $_POST['attachments'] : "0")."' WHERE cfg_name = 'attachments'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['attachmax']) ? $_POST['attachmax'] : "150000")."' WHERE cfg_name = 'attachmax'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['forum_max_w']) ? $_POST['forum_max_w'] : "400")."' WHERE cfg_name = 'forum_max_w'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['forum_max_h']) ? $_POST['forum_max_h'] : "200")."' WHERE cfg_name = 'forum_max_h'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$_POST['attachtypes']."' WHERE cfg_name = 'attachtypes'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['thread_notify']) ? $_POST['thread_notify'] : "0")."' WHERE cfg_name = 'thread_notify'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['unread_threshold']) ? $_POST['unread_threshold'] : "0")."' WHERE cfg_name = 'unread_threshold'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['forum_edit_timeout']) ? $_POST['forum_edit_timeout'] : "0")."' WHERE cfg_name = 'forum_edit_timeout'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['forum_edit_timeout_on_post']) ? $_POST['forum_edit_timeout_on_post'] : "1")."' WHERE cfg_name = 'forum_edit_timeout_on_post'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['forum_guest_limit']) ? $_POST['forum_guest_limit'] : "0")."' WHERE cfg_name = 'forum_guest_limit'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['forum_user_status']) ? $_POST['forum_user_status'] : "0")."' WHERE cfg_name = 'forum_user_status'");
}


$settings2 = array();
$result = dbquery("SELECT * FROM ".$db_prefix."configuration");
while ($data = dbarray($result)) {
	$settings2[$data['cfg_name']] = $data['cfg_value'];
}
$variables['settings2'] = $settings2;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_forum', 'template' => 'admin.settings_forum.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_forum'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
