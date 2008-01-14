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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// temp storage for template variables
$variables = array();

//load the locale for this module
locale_load("main.members-profile");

// load the GeoIP include module
require_once PATH_INCLUDES."geoip_include.php";

// parameter validation
if (!isset($country) || strlen($country) != 2) $country = "";
if (!isset($sortby) || strlen($sortby) != 1) $sortby = "all";

// get the name of the country requested
$variables['country_name'] = GeoIP_Code2Name($country);
$variables['country'] = $country;

$rows = 0;
if (iMEMBER) {
	// create the where clause
	$filter = "user_status = 0";	// only show activated accounts
	if ($sortby == "all") {
		if ($country != "") {
			$filter .= " AND user_cc_code = '".$country."'";
		}
	} else {
		if ($country == "") {
			$filter .= " AND (user_name LIKE '".stripinput($sortby)."%' OR user_name LIKE '".strtolower(stripinput($sortby))."%')";
		} else {
			$filter .= " AND (user_cc_code = '".$country."' AND (user_name LIKE '".stripinput($sortby)."%' OR user_name LIKE '".strtolower(stripinput($sortby))."%'))";
		}
	}
	// get the list of members
	$variables['members'] = array();
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."users ".($filter==""?"":("WHERE ".$filter))." ORDER BY user_level DESC, user_name LIMIT ".$rowstart.", ".$settings['numofthreads']);
	$rows = dbrows($result);
	$variables['members'] = array();
	if ($rows != 0) {
		while ($data = dbarray($result)) {
			$cc_flag = GeoIP_Code2Flag($data['user_cc_code']);
			$cc_name = GeoIP_Code2Name($data['user_cc_code']);
			if ($settings['forum_flags'] == 0) {
				$cc_flag = "";
				if (!$cc_name) $cc_name = $locale['408'];
			} else {
				if ($cc_flag == "" || empty($data['user_ip']) || $data['user_ip'] == "X" || $data['user_ip'] == "0.0.0.0") {
					$cc_flag = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					$cc_name = $locale['408'];
				}
			}
			$data['cc_flag'] = $cc_flag;
			$data['cc_name'] = $cc_name;
			$data['user_level'] = getuserlevel($data['user_level']);
			$variables['members'][] = $data;
		}
	} else {
		$error = $locale['403'];
		if ($country == "") {
			if ($sortby != "all") $error .= $locale['471'].$sortby;
		} else {
			$error .= $locale['470'].$country_name;
			if ($sortby != "all") $error .= $locale['471'].$sortby;
		}
	}
	// starting characters to filter on. Make sure there are an even number!
	$variables['search'] = array();
	$result = dbquery("SELECT DISTINCT(UPPER(SUBSTRING(user_name,1,1))) AS letter FROM ".$db_prefix."users ORDER BY letter");
	while ($data = dbarray($result)) {
		// get rid of unwanted characters. Need to find a beter solution for this
		$variables['search'][] = str_replace(array('&', '?'), array('',''), $data['letter']);
	}
	if (count($variables['search'])%2) $variables['search'][] = "";
	$variables['sortby'] = $sortby;
	$variables['rows'] = dbcount("(*)", "users", $filter);
	$variables['rowstart'] = $rowstart;
	$variables['items_per_page'] = $settings['numofthreads'];
	$variables['pagenav_url'] = FUSION_SELF."?sortby=$sortby&amp;".($country==""?"":"country=$country&amp;");
}

$template_panels[] = array('type' => 'body', 'name' => 'members', 'template' => 'main.members.tpl', 'locale' => "main.members-profile");
$template_variables['members'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>