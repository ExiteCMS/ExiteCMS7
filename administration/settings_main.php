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
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		sitename='".stripinput($_POST['sitename'])."',
		siteurl='".stripinput($_POST['siteurl']).(strrchr($_POST['siteurl'],"/") != "/" ? "/" : "")."',
		sitebanner='".stripinput($_POST['sitebanner'])."',
		siteemail='".stripinput($_POST['siteemail'])."',
		newsletter_email='".stripinput($_POST['newsletter_email'])."',
		siteusername='".stripinput($_POST['username'])."',
		siteintro='".addslashes(addslashes($siteintro))."',
		description='".stripinput($_POST['description'])."',
		keywords='".stripinput($_POST['keywords'])."',
		footer='".addslashes(addslashes($sitefooter))."',
		opening_page='".stripinput($_POST['opening_page'])."',
		news_columns='".(isNum($_POST['news_columns']) ? $_POST['news_columns'] : "1")."',
		news_items='".(isNum($_POST['news_items']) ? $_POST['news_items'] : "4")."',
		news_headline='".(isNum($_POST['news_headline']) ? $_POST['news_headline'] : "0")."',
		theme='".stripinput($_POST['theme'])."'
	");
	redirect(FUSION_SELF.$aidlink);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));
$settings2['sitename'] = phpentities($settings2['sitename']);
$settings2['siteintro'] = phpentities(stripslashes($settings2['siteintro']));
$settings2['footer'] = phpentities(stripslashes($settings2['footer']));
$variables['settings2'] = $settings2;

$variables['theme_files'] = makefilelist(PATH_THEMES, ".|..", true, "folders");

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_main', 'template' => 'admin.settings_main.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/settings.php");
$template_variables['admin.settings_main'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>