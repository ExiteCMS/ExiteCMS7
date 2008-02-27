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
if (!checkrights("S4") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['enable_registration']) ? $_POST['enable_registration'] : "1")."' WHERE cfg_name = 'enable_registration'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['email_verification']) ? $_POST['email_verification'] : "1")."' WHERE cfg_name = 'email_verification'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['admin_activation']) ? $_POST['admin_activation'] : "0")."' WHERE cfg_name = 'admin_activation'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['display_validation']) ? $_POST['display_validation'] : "1")."' WHERE cfg_name = 'display_validation'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".stripinput($_POST['validation_method'])."' WHERE cfg_name = 'validation_method'");
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".$db_prefix."configuration");
while ($data = dbarray($result)) {
	$settings2[$data['cfg_name']] = $data['cfg_value'];
}
$variables['settings2'] = $settings2;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_registration', 'template' => 'admin.settings_registration.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_registration'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>