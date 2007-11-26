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

// legacy locale defines
define("PATH_LOCALE", PATH_ROOT."locale/");
if (!defined('LOCALESET')) define("LOCALESET", $settings['locale']."/");

// define the website location (country) (if not defined)
if (!isset($settings['country'])) $settings['country'] = "??";

// get locales information, we need this in several places
if (dbtable_exists($db_prefix."locale")) {
	$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_name = '".$settings['locale']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$settings['locale_code'] = $data['locale_code'];
		if (isset($data['locale_charset'])) $settings['charset'] = $data['locale_charset'];
		if (isset($data['locale_locale'])) $settings['locales'] = $data['locale_locale'];
	}
}
// if we couldn't find it, use some default values
if (!isset($settings['locale_code'])) $settings['locale_code'] = "en";
if (!isset($settings['charset'])) $settings['charset'] = "iso-8859-1";
if (!isset($settings['locales'])) $settings['locales'] = "en_US|en_GB|english|eng";

// set the locale for strfime()
setlocale(LC_TIME, explode("|", $settings['locales']));

// set the locale for tinyMCE, default to 'en' if not found
if (file_exists(PATH_INCLUDES."jscripts/tiny_mce/langs".$settings['locale_code'].".js")) {
	$settings['tinyMCE_locale'] = $settings['locale_code'];
} else {
	$settings['tinyMCE_locale'] = 'en';
}

// Initialise the $locale array
$locale = array();

/*----------------------------------------------------+
| locale_functions include - general functions below  |
+----------------------------------------------------*/
function locale_load($locale_name) {

	global $settings, $locale, $db_prefix;

	// assemble the locale filename
	$locales_file = PATH_ROOT."files/locales/".$settings['locale_code'].".".$locale_name.".php";

	// check if we need to recompile from the database
	if (dbtable_exists($db_prefix."locales")) {

		// get the last update date from the locale strings table
		$result = dbquery("SELECT MAX(locales_datestamp) as last_update FROM ".$db_prefix."locales WHERE locales_code = '".$settings['locale_code']."' AND locales_name = '".$locale_name."'");

		// if found...
		if ($data = dbarray($result)) {
		
			// and there is data in the database...
			if (!is_null($data['last_update'])) {

				// if the locales cache does not exist or is out of date...
				if (!file_exists($locales_file) || filemtime($locales_file) < $data['last_update']) {
	
					// get the translator information for each of the locale found
					if (dbtable_exists($db_prefix."translators")) {
						$translators = "ExiteCMS team,";
						$result2 = dbquery("SELECT t.*, u.user_id, u.user_name FROM ".$db_prefix."translators t, ".$db_prefix."users u WHERE t.translate_locale_code = '".$settings['locale_code']."' AND t.translate_translator = u.user_id ORDER BY u.user_name");
						while ($data2 = dbarray($result2)) {
							$translators .= $data2['user_name'].",";
						}
					}
					// compile the locales cache file from the locales table
					if ($handle = @fopen($locales_file, 'w')) {

						// get the locale records for the selected locale and this locale_name
						$result2 = dbquery("SELECT * FROM ".$db_prefix."locales WHERE locales_code = '".$settings['locale_code']."' AND locales_name = '".$locale_name."' ORDER BY locales_key");
						if (dbrows($result2)) {
							fwrite($handle, "<?php"."\n");
							fwrite($handle, "// ----------------------------------------------------------"."\n");
							fwrite($handle, "// locale       : ".$settings['locale']."\n");
							fwrite($handle, "// locale name  : ".$locale_name."\n");
							fwrite($handle, "// generated on : ".date("D M j Y, G:i:s T")."\n");
							if (!empty($translators)) {
								fwrite($handle, "// translators  : ".substr($translators,0,-1)."\n");
							}
							fwrite($handle, "// ----------------------------------------------------------"."\n");
						}

						// search and replace array's
						$keysearch = array("$", '"', '\'', chr(10), chr(13));
						$keyreplace = array("\\$", '\\"', "\\'", "\\n", "\\r");
						$search = array("$", '"', chr(10), chr(13));
						$replace = array("\\$", '\\"', "\\n", "\\r");

						while ($localerec = dbarray($result2)) {
							$localerec['locales_key'] = str_replace($keysearch, $keyreplace, $localerec['locales_key']);
							// check if we're dealing with an array
							if (substr($localerec['locales_value'],0,8) == "#ARRAY#\n") {
								// generate the array definition
								fwrite($handle, "\$locale['".$localerec['locales_key']."'] = array();\n");
								// extract the array
								$localerec['locales_value'] = unserialize(substr($localerec['locales_value'],8));
								// loop through the elements
								foreach($localerec['locales_value'] as $key => $value) {
									$key = str_replace($keysearch, $keyreplace, $key);
									if (is_array($value)) {
										// multi-dimensional array
										fwrite($handle, "\$locale['".$localerec['locales_key']."']['$key'] = array();\n");
										foreach($value as $key2 => $value2) {
											$key2 = str_replace($keysearch, $keyreplace, $key2);
											$value2 = str_replace($search, $replace, $value2);
											fwrite($handle, "\$locale['".$localerec['locales_key']."']['$key']['$key2'] = \"".$value2."\"".";\n");
										}
									} else {
										// single-dimensional array
										$value = str_replace($search, $replace, $value);
										fwrite($handle, "\$locale['".$localerec['locales_key']."']['$key'] = \"".$value."\"".";\n");
									}
								}
							} else {
								$localerec['locales_value'] = str_replace($search, $replace, $localerec['locales_value']);
								fwrite($handle, "\$locale['".$localerec['locales_key']."'] = \"".$localerec['locales_value']."\"".";\n");
							}
						}
						fwrite($handle, "?>");
						fclose($handle);
					} else {
						trigger_error("ExiteCMS locales error: no write access to ".$locales_file."!", E_USER_ERROR);
					}
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

			case "forum":	// forum modules
				$locales_file = PATH_LOCALE.$settings['locale']."/forum/".$nameparts[1].".php";
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
				trigger_error("ExiteCMS locales error: unknown or invalid module type specified in ".$locale_name."!", E_USER_ERROR);
		}		
	}
	
	// if a locales file could be assembled...
	if (!empty($locales_file)) {
		if (file_exists($locales_file)) {
			// and it exists, load it
			require $locales_file;
		} else {
			// otherwise, if the locale is not English, try to load the English version
			if ($settings['locale_code'] != "en") {
				// save the current locale
				$current_locale_code = $settings['locale_code'];
				$current_locale = $settings['locale'];
				// retrieve the info for the default locale
				$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = 'en'");
				if (dbrows($result)) {
					$data = dbarray($result);
					$settings['locale_code'] = $data['locale_code'];
					$settings['locale'] = $data['locale_name'];
				} else {
					// system default language missing?
					trigger_error("ExiteCMS locales error: Can't load the system default language!", E_USER_ERROR);
				}
				// try to load the default locale instead
				locale_load($locale_name);
				// restore the original locale
				$settings['locale_code'] = $current_locale_code;
				$settings['locale'] = $current_locale;
			} else {
				trigger_error("ExiteCMS locales error: unable to locate a locale for ".$locale_name."!", E_USER_ERROR);
			}
		}
	}

}
?>