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
if (eregi("locale_functions.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// locale detection - step 1 - check if there's a users preference available
if (iMEMBER) {
	// check if we (still) support this language. If so, update the locale setting
	$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".$userdata['user_locale']."' AND locale_active = '1'");
	if ($data = dbarray($result)) {
		$settings['locale'] = $data['locale_name'];
		define("LOCALESET", $settings['locale']."/");
		define("LOCALEDIR", $data['locale_direction']);
	}
}

// locale detection - step 2 - check if there's a locale cookie set
if (!defined('LOCALESET') && isset($_COOKIE['locale'])) {
	// check if we (still) support this language. If so, update the locale setting
	$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".$_COOKIE['locale']."' AND locale_active = '1'");
	if ($data = dbarray($result)) {
		$settings['locale'] = $data['locale_name'];
		define("LOCALESET", $settings['locale']."/");
		define("LOCALEDIR", $data['locale_direction']);
	}
}

// locale detection - step 3 - check the browsers accepted languages
if (!defined('LOCALESET') && isset($settings['browserlang']) && $settings['browserlang'] && isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
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
			define("LOCALEDIR", $data['locale_direction']);
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
				define("LOCALEDIR", $data['locale_direction']);
				break;
			}
		}
	}
}

// legacy locale defines (for v7.0 style locale files)
define("PATH_LOCALE", PATH_ROOT."locale/");
if (!defined('LOCALESET')) define("LOCALESET", $settings['locale']."/");
if (!defined('LOCALEDIR')) define("LOCALEDIR", "LTR");

// define the website location (country) (if not defined)
if (!isset($settings['country'])) $settings['country'] = "??";

// get locales information, we need this in several places
if ($settings['revision'] > 1070) {
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
if (!isset($settings['charset'])) $settings['charset'] = "utf-8";
if (!isset($settings['locales'])) $settings['locales'] = "en_US|en_GB|english|eng";

// set the PHP charset
ini_set('default_charset', $settings['charset']);

// set the locale for strfime()
setlocale(LC_TIME, explode("|", $settings['locales']));

// set the locale for tinyMCE, default to 'en' if not found
if (file_exists(PATH_INCLUDES."jscripts/tiny_mce/langs/".$settings['locale_code'].".js")) {
	$settings['tinyMCE_locale'] = $settings['locale_code'];
} else {
	$settings['tinyMCE_locale'] = 'en';
}

// set the locale for PHPmailer, default to 'en' if not found
if (file_exists(PATH_INCLUDES."languages/phpmailer.lang-".$settings['locale_code'].".php")) {
	$settings['PHPmailer_locale'] = $settings['locale_code'];
} else {
	$settings['PHPmailer_locale'] = 'en';
}

// Initialise the $locale array
$locale = array();

/*----------------------------------------------------+
| locale_functions include - general functions below  |
+----------------------------------------------------*/
function locale_load($locale_name, $locale_code="") {

	global $settings, $locale, $db_prefix;

	// if no locale code is specified, use the current user locale
	if (empty($locale_code)) {
		$locale_code = $settings['locale_code'];
	}

	// assemble the locale filename
	$locales_file = PATH_ROOT."files/locales/".$locale_code.".".$locale_name.".php";

	// check if we need to recompile from the database
	if ($settings['revision'] > 1070) {

		// get the last update date from the locale strings table
		$result = dbquery("SELECT MAX(locales_datestamp) as last_update FROM ".$db_prefix."locales WHERE locales_code = '".$locale_code."' AND locales_name = '".$locale_name."'");

		// if found...
		if ($data = dbarray($result)) {

			// and there is data in the database...
			if (!is_null($data['last_update'])) {

				// if the locales cache does not exist or is out of date...
				if (!file_exists($locales_file) || filemtime($locales_file) < $data['last_update']) {

					// get the translator information for each of the locale found
					if (dbtable_exists($db_prefix."translators")) {
						$translators = "ExiteCMS team,";
						$result2 = dbquery("SELECT t.*, u.user_id, u.user_name FROM ".$db_prefix."translators t, ".$db_prefix."users u WHERE t.translate_locale_code = '".$locale_code."' AND t.translate_translator = u.user_id ORDER BY u.user_name");
						while ($data2 = dbarray($result2)) {
							$translators .= $data2['user_name'].",";
						}
					}
					// compile the locales cache file from the locales table
					if ($handle = @fopen($locales_file, 'w')) {

						// get the locale records for the selected locale and this locale_name
						$result2 = dbquery("SELECT * FROM ".$db_prefix."locales WHERE locales_code = '".$locale_code."' AND locales_name = '".$locale_name."' ORDER BY locales_key");
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

						// search and replace array's for keys and values
						$keysearch = array("'");
						$keyreplace = array("\'");
						$search = array('"', '$', "\n");
						$replace = array('\"', '\$', '\n');

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
						fwrite($handle, "?".">");
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
			// otherwise, if the locale is not the default system locale, try to load the system locale version
			if ($locale_code != $settings['default_locale']) {
				// retrieve the info for the system locale
				$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".$settings['default_locale']."'");
				if (dbrows($result)) {
					$data = dbarray($result);
					// try to load the default locale instead
					locale_load($locale_name, $data['locale_code']);
				} else {
					// system default language missing?
					trigger_error("ExiteCMS locales error: Can't load the system default language!", E_USER_ERROR);
				}
			} else {
				trigger_error("ExiteCMS locales error: unable to locate a locale for ".$locale_name."!", E_USER_NOTICE);
			}
		}
	}

}

function load_localestrings($localestrings, $locales_code, $locales_name, $step="upgrade") {
	global $db_prefix;

	// remove the old locale strings for this locale name ($step to be compatible with the language pack function!)
	if ($step == "upgrade") {
		$result = dbquery("DELETE FROM ".$db_prefix."locales WHERE locales_code = '$locales_code' AND locales_name = '$locales_name'");
	}

	// determine the timestamp of this update
	$timestamp = defined('LP_DATE') ? LP_DATE : time();

	// proces the imported locale strings
	foreach ($localestrings as $key => $value) {
		if (is_array($value)) {
			$value = "#ARRAY#\n".serialize($value);
		}
		$result = dbquery("INSERT INTO ".$db_prefix."locales (locales_code, locales_name, locales_key, locales_value, locales_datestamp) VALUES ('$locales_code', '$locales_name', '".mysql_escape_string($key)."', '".mysql_escape_string($value)."', '".$timestamp."')");
	}
	return true;
}
?>
