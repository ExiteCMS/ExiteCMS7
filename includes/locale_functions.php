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
if (eregi("locale_functions.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// locale defines (no longer needed once we moved to the new locale system

define("PATH_LOCALE", PATH_ROOT."locale/");

// locale detection - step 1 - check if there's a locale cookie set
if (isset($_COOKIE['locale'])) {
	// check if we (still) support this language. If so, update the locale setting
	$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".$_COOKIE['locale']."' AND locale_active = '1'");
	if ($data = dbarray($result)) {
		$settings['locale'] = $data['locale_name'];
		define("LOCALESET", $settings['locale']."/");
	}
}

// locale detection - step 2 - check the browsers accepted languages
if (!defined('LOCALESET') && isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	// check which languages are supported by the users browser
	$temp = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	foreach($temp as $lng) {
		$thislng = explode(";", $lng);
		// check if we support this language
		$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".$thislng[0]."' AND locale_active = '1'");
		if ($data = dbarray($result)) {
			// if so, set the locale
			$settings['locale'] = $data['locale_name'];
			define("LOCALESET", $settings['locale']."/");
			break;
		}
	}
	// if not found, loop again on languages only
	if (!defined('LOCALESET')) {
		foreach($temp as $lng) {
			$thislng = explode(";", $lng);
			$thislng = explode("-", $thislng[0]);
			// check if we support this language
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".$thislng[0]."' AND locale_active = '1'");
			if ($data = dbarray($result)) {
				// if so, set the locale
				$settings['locale'] = $data['locale_name'];
				define("LOCALESET", $settings['locale']."/");
				break;
			}
		}
	}
}

// locale detection - step 3 - use the website's default
if (!defined('LOCALESET')) {
	define("LOCALESET", $settings['locale']."/");
}

// Initialise the $locale array
$locale = array();

// Load the global language file
include PATH_LOCALE.LOCALESET."global.php";

?>