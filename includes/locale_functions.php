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

// locale defines (no longer needed once we moved to the new locale system!)

define("PATH_LOCALE", PATH_ROOT."locale/");

// locale detection - step 3 - use the website's default
if (!defined('LOCALESET')) {
	define("LOCALESET", $settings['locale']."/");
}

// Initialise the $locale array
$locale = array();

// Load the global language file
locale_load("main.global");

/*----------------------------------------------------+
| locale_functions include - general functions below  |
+----------------------------------------------------*/
function locale_load($locale_name) {

	global $settings, $locale;
	
	// assemble the locale filename
	$locales_file = PATH_ROOT."files/".$locale['locale'].".".$locale_name.".php";

	// check if we need to recompile from the database
	if (dbtable_exists($prefix."locales")) {

		// get the last update date from the locale strings table
		$result = dbquery("SELECT MAX(locales_datestamp) as last_update FROM ".$db_prefix." WHERE locales_locale = '".$settings['locale']."' AND locales_name = '".$locale_name."'");

		// if found...
		if ($data = dbarray($result)) {

			// if the locales cache does not exist or is out of date...
			if (!file_exists($locales_file) || filemtime($locales_file) < $data['last_update']) {

				// compile the locales cache file from the locales table
				if ($handle = fopen($locales_file, 'w')) {

					// get the locale records for the selected locale and this locale_name
					$result = dbquery("SELECT * FROM ".$db_prefix." WHERE locales_locale = '".$settings['locale']."' AND locales_name = '".$locale_name."' ORDER BY locales_key");
					if (dbrows($result)) {
						fwrite($handle, "<?php"."\n");
						fwrite($handle, "// ----------------------------------------------------------"."\n");
						fwrite($handle, "// locale : ".$locale['locale']."\n");
						fwrite($handle, "// name   : ".$locale_name."\n");
						fwrite($handle, "// date   : ".date("D M j Y, G:i:s T")."\n");
						fwrite($handle, "// ----------------------------------------------------------"."\n");
					}
					while ($data = dbarray($result)) {
						fwrite($handle, "\$locale['".$data['locales_key']."'] = \"".$data['locales_value']."\"".";\n");
					}
					fwrite($handle, "?>"."\n");
					fclose($handle);
				}
			}
		}
	}

	// if the locale file could not be found or generated, check if a static file exists in the 'old' location
	if (!file_exists($locales_file)) {

		// split the locale_name to determine the location of the file on disk
		// name is in the form: moduletype.modulename, p.e. main.setup, or admin.articles, or modules.some_panel
		$nameparts = explode(".", strtolower($locale_name));

		switch ($nameparts[0]) {

			case "admin":	// admin modules
				$locales_file = PATH_LOCALE.$settings['locale']."/admin/".$nameparts[1].".php";
				break;

			case "main":	// main modules
				$locales_file = PATH_LOCALE.$settings['locale']."/".$nameparts[1].".php";
				break;

			case "modules":	// installable modules & plugins
				$locales_file = PATH_MODULES.$nameparts[1]."/locale/".$settings['locale'].".php";
				break;

			case "tools":	// webmaster tools
				$locales_file = PATH_ADMIN."tools/locale/".$nameparts[1].".php";
				break;

			default:
				// unknown module type
		}		
	}
	
	// if a locales file could be assembled, and it exists, load it
	if (!empty($locales_file) && file_exists($locales_file)) {
		include $locales_file;
	}

}
?>