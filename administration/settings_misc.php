<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("admin.settings");

// temp storage for template variables
$variables = array();

// store this modules name for the menu bar
$variables['this_module'] = FUSION_SELF;

// check for the proper admin access rights
if (!checkrights("S6") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['tinymce_enabled']) ? $_POST['tinymce_enabled'] : "0")."' WHERE cfg_name = 'tinymce_enabled'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['hoteditor_enabled']) ? $_POST['hoteditor_enabled'] : "0")."' WHERE cfg_name = 'hoteditor_enabled'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".stripinput($_POST['smtp_host'])."' WHERE cfg_name = 'smtp_host'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".stripinput($_POST['smtp_username'])."' WHERE cfg_name = 'smtp_username'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".stripinput($_POST['smtp_password'])."' WHERE cfg_name = 'smtp_password'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['bad_words_enabled']) ? $_POST['bad_words_enabled'] : "0")."' WHERE cfg_name = 'bad_words_enabled'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".addslash($_POST['bad_words'])."' WHERE cfg_name = 'bad_words'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".stripinput($_POST['bad_word_replace'])."' WHERE cfg_name = 'bad_words_replace'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['guestposts']) ? $_POST['guestposts'] : "0")."' WHERE cfg_name = 'guestposts'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['numofshouts']) ? $_POST['numofshouts'] : "10")."' WHERE cfg_name = 'numofshouts'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['flood_interval']) ? $_POST['flood_interval'] : "15")."' WHERE cfg_name = 'flood_interval'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['forum_flags']) ? $_POST['forum_flags'] : "0")."' WHERE cfg_name = 'forum_flags'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['maintenance']) ? $_POST['maintenance'] : "0")."' WHERE cfg_name = 'maintenance'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".addslash(descript($_POST['maintenance_message']))."' WHERE cfg_name = 'maintenance_message'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".stripinput($_POST['maintenance_color'])."' WHERE cfg_name = 'maintenance_color'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".stripinput($_POST['debug_querylog'])."' WHERE cfg_name = 'debug_querylog'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['debug_sql_explain']) ? $_POST['debug_sql_explain'] : "0")."' WHERE cfg_name = 'debug_sql_explain'");
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".$db_prefix."configuration");
while ($data = dbarray($result)) {
	$settings2[$data['cfg_name']] = $data['cfg_value'];
}
$settings2['maintenance_message'] = stripslashes($settings2['maintenance_message']);
$variables['settings2'] = $settings2;

$variables['usergroups'] = getusergroups(true);

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_misc', 'template' => 'admin.settings_misc.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_misc'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
