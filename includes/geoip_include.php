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
if (eregi("geoip_include.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// initialize the result cache
if (!isset($_GeoIP_result)) $_GeoIP_result = array();

function GeoIP_IP2Num($ip_addr) {

	if (isIP($ip_addr)) {
		$octets = explode(".", $ip_addr);
		$ipnum = 16777216*$octets[0] + 65536*$octets[1] + 256*$octets[2] + $octets[3];
		return $ipnum;
	} else {
		return false;
	}
}

function GeoIP_IP2Code($ip_addr) {
	global $db_prefix, $_GeoIP_result;

	// not cached?
	if (!isset($_GeoIP_result[$ip_addr])) {

		// check if there is an exception defined
		$result = dbquery("SELECT * FROM ".$db_prefix."GeoIP_exceptions WHERE ip_number = '".$ip_addr."' LIMIT 1");
		if ($data = dbarray($result)) {
			// add it to the cache
			$_GeoIP_result[$ip_addr] = $data['ip_code'];
		} else {
			// convert the IP address to a number
			$ipnum = GeoIP_IP2Num($ip_addr);
			if (!$ipnum) return false;			
			// look this IP address up
			$result = dbquery("SELECT * FROM ".$db_prefix."GeoIP WHERE '".sprintf("%u", $ipnum)."' BETWEEN ip_start_num AND ip_end_num LIMIT 1");
			if ($data = dbarray($result)) {
				// add it to the cache
				$_GeoIP_result[$ip_addr] = $data['ip_code'];
			} else {
				$_GeoIP_result[$ip_addr] = false;
			}
		}

	}

	return $_GeoIP_result[$ip_addr];
}

function GeoIP_IP2Name($ip_addr) {

	$ip_code = GeoIP_IP2Code($ip_addr);

	if (!$ip_code)
		return false;
	else {
		return GeoIP_Code2Name($ip_code);
	}
}

function GeoIP_Code2Name($ip_code) {
	global $db_prefix, $settings;

	// not cached?
	if (!isset($_GeoIP_result[$ip_code])) {

		$result = dbquery("SELECT locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$settings['locale_code']."' AND locales_name = 'countrycode' AND locales_key = '".$ip_code."' LIMIT 1");
		if (!dbrows($result)) {
			// no translated country names found, load the english set instead
			$result = dbquery("SELECT locales_value FROM ".$db_prefix."locales WHERE locales_code = 'en' AND locales_name = 'countrycode' AND locales_key = '".$ip_code."' LIMIT 1");
		}
		if (dbrows($result) == 0) {
			$_GeoIP_result[$ip_code] = "";
		} else {
			$data = dbarray($result);
			$_GeoIP_result[$ip_code] = $data['locales_value'];
		}
	}

	return $_GeoIP_result[$ip_code];
}

function GeoIP_IP2Flag($ip_addr, $tag=true, $width=false, $height=false) {

	return GeoIP_flag(strtolower(GeoIP_IP2Code($ip_addr, true)), GeoIP_IP2Name($ip_addr), $tag, $width, $height);
}

function GeoIP_Code2Flag($ip_code, $tag=true, $width=false, $height=false) {
	global $db_prefix;

	return GeoIP_flag(strtolower($ip_code), GeoIP_Code2Name($ip_code), $tag, $width, $height);
}

function GeoIP_flag($geoip_flag="", $geoip_name="", $tag=true, $width=false, $height=false) {

	if (!is_file(PATH_IMAGES."flags/".$geoip_flag.".gif")) {
		if ($tag) {
			$geoip_flag = "<img width='".($width?$width:"14")."' height='".($height?$height:"10")."' src='".IMAGES."flags/unknown.gif' title='' alt='' />&nbsp;";
		} else {
			$geoip_flag = "";
		}
	} else {
		$geoip_flag = IMAGES."flags/".$geoip_flag.".gif";
		if ($tag) {
			$geoip_flag = "<img ".($width?("width='".$width."' "):"").($height?("height='".$height."' "):"")."src='".$geoip_flag."' title='".($geoip_name?$geoip_name:"")."' alt='".($geoip_name?$geoip_name:"")."' />&nbsp;";
		}
	}
	return $geoip_flag;
}
?>
