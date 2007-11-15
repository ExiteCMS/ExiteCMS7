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
if (!checkrights("S2") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['shortdate']."' WHERE cfg_name = 'shortdate'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['longdate']."' WHERE cfg_name = 'longdate'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['forumdate']."' WHERE cfg_name = 'forumdate'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['subheaderdate']."' WHERE cfg_name = 'subheaderdate'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['timeoffset']."' WHERE cfg_name = 'timeoffset'");
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".$db_prefix."CMSconfig");
while ($data = dbarray($result)) {
	$settings2[$data['cfg_name']] = $data['cfg_value'];
}
$variables['settings2'] = $settings2;

$variables['options'] = array();
$variables['options'][''] = $locale['455'];
$variables['options']['localedate'] = $locale['458'];
$variables['options']['localedatetime'] = $locale['459'];
$variables['options']['%m/%d/%Y'] = "%m/%d/%Y";
$variables['options']['%d/%m/%Y'] = "%d/%m/%Y";
$variables['options']['%d-%m-%Y'] = "%d-%m-%Y";
$variables['options']['%d.%m.%Y'] = "%d.%m.%Y";
$variables['options']['%m/%d/%Y %H:%M'] = "%m/%d/%Y %H:%M";
$variables['options']['%d/%m/%Y %H:%M'] = "%d/%m/%Y %H:%M";
$variables['options']['%d-%m-%Y %H:%M'] = "%d-%m-%Y %H:%M";
$variables['options']['%d.%m.%Y %H:%M'] = "%d.%m.%Y %H:%M";
$variables['options']['%m/%d/%Y %H:%M:%S'] = "%m/%d/%Y %H:%M:%S";
$variables['options']['%d/%m/%Y %H:%M:%S'] = "%d/%m/%Y %H:%M:%S";
$variables['options']['%d-%m-%Y %H:%M:%S'] = "%d-%m-%Y %H:%M:%S";
$variables['options']['%d.%m.%Y %H:%M:%S'] = "%d.%m.%Y %H:%M:%S";
$variables['options']['%B %d %Y'] = "%B %d %Y";
$variables['options']['%d. %B %Y'] = "%d. %B %Y";
$variables['options']['%d %B %Y'] = "%d %B %Y";
$variables['options']['%B %d %Y %H:%M'] = "%B %d %Y %H:%M";
$variables['options']['%d. %B %Y %H:%M'] = "%d. %B %Y %H:%M";
$variables['options']['%d %B %Y %H:%M'] = "%d %B %Y %H:%M";
$variables['options']['%B %d %Y %H:%M:%S'] = "%B %d %Y %H:%M:%S";
$variables['options']['%d. %B %Y %H:%M:%S'] = "%d. %B %Y %H:%M:%S";
$variables['options']['%d %B %Y %H:%M:%S'] = "%d %B %Y %H:%M:%S";

$variables['serverzone'] = sprintf($locale['457'], "GMT ".(date('O')=="+0000"?"":date('O')));
$variables['localtime'] = time_system2local(time());

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_time', 'template' => 'admin.settings_time.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_time'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>