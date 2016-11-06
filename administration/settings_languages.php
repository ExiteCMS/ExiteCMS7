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
if (!checkrights("S8") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

/*---------------------------------------------------+
| Local functions                                    |
+----------------------------------------------------*/
function migrate($tablename, $fieldname, $from_setting, $to_setting) {
	global $db_prefix, $_db_link, $settings;

	if ($from_setting == "none" && $to_setting == "single") {
		// not implemented yet
	} elseif ($from_setting == "none" && $to_setting == "multiple") {
		// set everything to the current locale
		$result = dbquery("UPDATE ".$db_prefix.$tablename. " SET ".$fieldname." = '".$settings['locale_code']."'");
		// and copy it to all other active locales
		$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code <> '".$settings['locale_code']."' AND locale_active = 1");
		while ($data = dbarray($result)) {
			$result2 = dbquery("SELECT * FROM ".$db_prefix.$tablename);
			while ($data2 = dbarray($result2)) {
				$key = 0;
				$fields = "";
				$values = "";
				foreach($data2 as $name => $value) {
					// skip the primary key
					if ($key++ == 0) continue;
					$fields .= ($fields == "" ? "" : ", ") . $name;
					if ($name == $fieldname) {
						$values .= ($values == "" ? "" : ", ") . "'".$data['locale_code']."'";
					} else {
						$values .= ($values == "" ? "" : ", ") . "'".mysqli_real_escape_string($_db_link, $value)."'";
					}
				}
				// insert the duplicated record with the new locale code
				$result3 = dbquery("INSERT INTO ".$db_prefix.$tablename." (".$fields.") VALUES (".$values.")");
			}
		}
	} elseif ($from_setting == "single" && $to_setting == "none") {
		// not implemented yet
	} elseif ($from_setting == "single" && $to_setting == "multiple") {
		// not implemented yet
	} elseif ($from_setting == "multiple" && $to_setting == "none") {
		$result = dbquery("UPDATE ".$db_prefix.$tablename. " SET ".$fieldname." = ''");
	} elseif ($from_setting == "multiple" && $to_setting == "single") {
		// not implemented yet
	} else {
		terminate("invalid migration strategy detected when migrating ".$tablename."!");
	}

}

/*---------------------------------------------------+
| Main code                                          |
+----------------------------------------------------*/

if (isset($_POST['savesettings'])) {
	// use browser language
	$browserlang = (isset($_POST['browserlang']) && IsNum($_POST['browserlang'])) ? $_POST['browserlang'] : 1;
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$browserlang."' WHERE cfg_name = 'browserlang'");

	// selected default locale
	$settings['locale'] = stripinput($_POST['localeset']);
	$old_localeset = stripinput($_POST['old_localeset']);
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$settings['locale']."' WHERE cfg_name = 'locale'");
	if (empty($_POST['old_country'])) {
		$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES ('country', '".$_POST['country']."')");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$_POST['country']."' WHERE cfg_name = 'country'");
	}

	// panel localisation
	$panels_localisation = stripinput($_POST['panels_localisation']);
	if ($panels_localisation != $settings['panels_localisation']) {
		migrate('panels', 'panel_locale', $settings['panels_localisation'], $panels_localisation);
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$panels_localisation."' WHERE cfg_name = 'panels_localisation'");

	// sitelinks localisation
	$sitelinks_localisation = stripinput($_POST['sitelinks_localisation']);
	if ($sitelinks_localisation != $settings['sitelinks_localisation']) {
		migrate('site_links', 'link_locale', $settings['sitelinks_localisation'], $sitelinks_localisation);
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$sitelinks_localisation."' WHERE cfg_name = 'sitelinks_localisation'");

	// news_localisation
	$news_localisation = stripinput($_POST['news_localisation']);
	if ($news_localisation != $settings['news_localisation']) {
		migrate('news', 'news_locale', $settings['news_localisation'], $news_localisation);
		migrate('news_frontpage', 'frontpage_locale', $settings['news_localisation'], $news_localisation);
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$news_localisation."' WHERE cfg_name = 'news_localisation'");

	// download_localisation
	$download_localisation = stripinput($_POST['download_localisation']);
	if ($download_localisation != $settings['download_localisation']) {
		migrate('download_cats', 'download_cat_locale', $settings['download_localisation'], $download_localisation);
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$download_localisation."' WHERE cfg_name = 'download_localisation'");

	// article localisation
	$article_localisation = stripinput($_POST['article_localisation']);
	if ($article_localisation != $settings['article_localisation']) {
		migrate('article', 'article_locale', $settings['article_localisation'], $download_localisation);
	}
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
