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
if (eregi("geoip_include.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// initialize the result cache
if (!isset($_GeoIP_result)) $_GeoIP_result = "";

function GeoIP_IP2Num($ip_addr) {

	$octets = explode(".", $ip_addr);
	if (count($octets) != 4) return false;
	$ipnum = 16777216*$octets[0] + 65536*$octets[1] + 256*$octets[2] + $octets[3];
	return $ipnum;
}

function GeoIP_IP2Code($ip_addr) {
	global $db_prefix, $_GeoIP_result;
	
	// check if there is an exception defined
	$result = dbquery("SELECT * FROM ".$db_prefix."GeoIP_exceptions WHERE ip_number = '".$ip_addr."' LIMIT 1");
	if (dbrows($result) != 0) {
		$_GeoIP_result = dbarray($result);
		$_GeoIP_result['ip_addr'] = $ip_addr;
	} else {
		$ipnum = GeoIP_IP2Num($ip_addr);
		if (!$ipnum) return false;
		if (!isset($_GeoIP_result) || !is_array($_GeoIP_result) || $_GeoIP_result['ip_addr'] != $ip_addr) {
			$result = dbquery("SELECT * FROM ".$db_prefix."GeoIP WHERE '".sprintf("%u", $ipnum)."' BETWEEN ip_start_num AND ip_end_num LIMIT 1");
			if (dbrows($result) == 0) {
				unset($_GeoIP_result);
				return false;
			} else {
				$_GeoIP_result = dbarray($result);
			}
		}
		$_GeoIP_result['ip_addr'] = $ip_addr;
	}
	return $_GeoIP_result['ip_code'];
}

function GeoIP_IP2Name($ip_addr) {
	global $db_prefix, $_GeoIP_result;

	$ipnum = GeoIP_IP2Num($ip_addr);
	if (!$ipnum)
		return false;
	else {
		if (!isset($_GeoIP_result) || !is_array($_GeoIP_result) || $_GeoIP_result['ip_addr'] != $ip_addr) {
			// check if there is an exception defined
			$result = dbquery("SELECT * FROM ".$db_prefix."GeoIP_exceptions WHERE ip_number = '".$ip_addr."' LIMIT 1");
			if (dbrows($result) != 0) {
				$_GeoIP_result = dbarray($result);
			} else {
				$result = dbquery("SELECT * FROM ".$db_prefix."GeoIP WHERE '".sprintf("%u", $ipnum)."' BETWEEN ip_start_num AND ip_end_num LIMIT 1");
				if (dbrows($result) == 0) {
					unset($_GeoIP_result);
					return false;
				} else {
					$_GeoIP_result = dbarray($result);
				}
			}
		}
		$_GeoIP_result['ip_addr'] = $ip_addr;
		return $_GeoIP_result['ip_name'];
	}
}

function GeoIP_Code2Name($ip_code) {
	global $db_prefix;

	$result = dbquery("SELECT * FROM ".$db_prefix."GeoIP WHERE ip_code ='".$ip_code."' LIMIT 1");
	if (dbrows($result) == 0) {
		return "";
	}
	$data = dbarray($result);
	return $data['ip_name'];
}

function GeoIP_IP2Flag($ip_addr, $tag=true, $width=false, $height=false) {
	$geoip_flag = strtolower(GeoIP_IP2Code($ip_addr, true));
	$geoip_name = GeoIP_IP2Name($ip_addr);
	if (!is_file(PATH_IMAGES."flags/".$geoip_flag.".gif")) {
		if ($tag) {
			$geoip_flag = "<img width='".($width?$width:"16")."' height='".($height?$height:"11")."' src='".IMAGES."spacer.gif' title='' alt='' />&nbsp;";
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

function GeoIP_Code2Flag($ip_code, $tag=true, $width=false, $height=false) {
	global $db_prefix;

	$result = dbquery("SELECT * FROM ".$db_prefix."GeoIP WHERE ip_code ='".$ip_code."' LIMIT 1");
	if (dbrows($result) == 0) {
		return "<img width='".($width?$width:"16")."' height='".($height?$height:"11")."' src='".IMAGES."spacer.gif' title='' alt='' />&nbsp;";
	}
	$data = dbarray($result);
	$geoip_flag = strtolower($data['ip_code']);
	$geoip_name = $data['ip_name'];
	if (!is_file(PATH_IMAGES."flags/".$geoip_flag.".gif")) {
		if ($tag) {
			$geoip_flag = "<img width='".($width?$width:"16")."' height='".($height?$height:"11")."' src='".IMAGES."spacer.gif' title='' alt='' />&nbsp;";
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