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
include PATH_LOCALE.LOCALESET."admin/settings.php";

// temp storage for template variables
$variables = array();

// store this modules name for the menu bar
$variables['this_module'] = FUSION_SELF;

// check for the proper admin access rights
if (!checkrights("S1") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	$siteintro = descript(stripslash($_POST['intro']));
	$sitefooter = descript(stripslash($_POST['footer']));
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['sitename'])."' WHERE cfg_name = 'sitename'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['siteurl']).(strrchr($_POST['siteurl'],"/") != "/" ? "/" : "")."' WHERE cfg_name = 'siteurl'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['siteemail'])."' WHERE cfg_name = 'siteemail'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['newsletter_email'])."' WHERE cfg_name = 'newsletter_email'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['username'])."' WHERE cfg_name = 'siteusername'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".addslashes(addslashes($siteintro))."' WHERE cfg_name = 'siteintro'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['description'])."' WHERE cfg_name = 'description'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['keywords'])."' WHERE cfg_name = 'keywords'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['opening_page'])."' WHERE cfg_name = 'opening_page'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".(isNum($_POST['news_columns']) ? $_POST['news_columns'] : "1")."' WHERE cfg_name = 'news_columns'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".(isNum($_POST['news_items']) ? $_POST['news_items'] : "4")."' WHERE cfg_name = 'news_items'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".(isNum($_POST['news_headline']) ? $_POST['news_headline'] : "0")."' WHERE cfg_name = 'news_headline'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".(isNum($_POST['download_columns']) ? $_POST['download_columns'] : "1")."' WHERE cfg_name = 'download_columns'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".stripinput($_POST['theme'])."' WHERE cfg_name = 'theme'");
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".$db_prefix."CMSconfig");
while ($data = dbarray($result)) {
	$settings2[$data['cfg_name']] = $data['cfg_value'];
}
$settings2['sitename'] = phpentities($settings2['sitename']);
$settings2['siteintro'] = phpentities(stripslashes($settings2['siteintro']));
$variables['settings2'] = $settings2;

$variables['theme_files'] = makefilelist(PATH_THEMES, ".|..|.svn", true, "folders");

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_main', 'template' => 'admin.settings_main.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/settings.php");
$template_variables['admin.settings_main'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>