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
if (!checkrights("S8") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	$settings['locale'] = stripinput($_POST['localeset']);
	$old_localeset = stripinput($_POST['old_localeset']);
	$panels_localisation = stripinput($_POST['panels_localisation']);
	$sitelinks_localisation = stripinput($_POST['sitelinks_localisation']);
	$article_localisation = stripinput($_POST['article_localisation']);
	$download_localisation = stripinput($_POST['download_localisation']);
	$news_localisation = stripinput($_POST['news_localisation']);
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$settings['locale']."' WHERE cfg_name = 'locale'");
	if (empty($_POST['old_country'])) {
		$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES ('country', '".$_POST['country']."')");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$_POST['country']."' WHERE cfg_name = 'country'");
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$panels_localisation."' WHERE cfg_name = 'panels_localisation'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$sitelinks_localisation."' WHERE cfg_name = 'sitelinks_localisation'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$news_localisation."' WHERE cfg_name = 'news_localisation'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$download_localisation."' WHERE cfg_name = 'download_localisation'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$article_localisation."' WHERE cfg_name = 'article_localisation'");
	redirect(FUSION_SELF.$aidlink);
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".$db_prefix."configuration");
while ($data = dbarray($result)) {
	$settings2[$data['cfg_name']] = $data['cfg_value'];
}
$variables['settings2'] = $settings2;

$variables['locales'] = array();
$result = dbquery("SELECT locale_name FROM ".$db_prefix."locale WHERE locale_active = '1' ORDER BY locale_name");
while ($data = dbarray($result)) {
	$variables['locales'][] = $data['locale_name'];
}

$variables['countries'] = array();
$result = dbquery("SELECT locales_key, locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$settings['locale_code']."' AND locales_name = 'countrycode' ORDER BY locales_value");
if (!dbrows($result)) {
	// no translated country names found, load the english set instead
	$result = dbquery("SELECT locales_key, locales_value FROM ".$db_prefix."locales WHERE locales_code = 'en' AND locales_name = 'countrycode' ORDER BY locales_value");
}
while ($data = dbarray($result)) {
	$variables['countries'][$data['locales_key']] = $data['locales_value'];
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_languages', 'template' => 'admin.settings_languages.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_languages'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>