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
if (!isset($order)) $order = "username";
if (!isset($field)) $field = "username";

// get the name of the country requested
$variables['country_name'] = GeoIP_Code2Name($country);
$variables['country'] = $country;

$rows = 0;
if (iMEMBER) {
	// create the letter filter SQL clause and the selection sort SQL clause
	switch($order) {
		case "country":
			$sortfield = "user_cc_code ASC, user_level DESC, user_name ASC";
			break;
		case "email":
			$sortfield = "user_email ASC, user_level DESC";
			break;
		case "lastvisit":
			$sortfield = "user_lastvisit DESC, user_name ASC";
			break;
		case "username":
		default:
			$sortfield = "user_level DESC, user_name ASC";
			break;
	}
	// create the query filter SQL clause
	$where = "";
	switch($field) {
		case "country":
			$letterfilter = "DISTINCT(UPPER(SUBSTRING(user_cc_code,1,1)))";
			break;
		case "email":
			$letterfilter = "DISTINCT(UPPER(SUBSTRING(user_email,1,1)))";
			if ($sortby != "all") {
				$where = "(user_email LIKE '".stripinput($sortby)."%' OR user_email LIKE '".strtolower(stripinput($sortby))."%')";
			}
			break;
		case "lastvisit":
			$letterfilter = "DISTINCT(UPPER(SUBSTRING(user_name,1,1)))";
			if ($sortby != "all") {
				$where = "(user_name LIKE '".stripinput($sortby)."%' OR user_name LIKE '".strtolower(stripinput($sortby))."%')";
			}
			break;
		case "username":
		default:
			$letterfilter = "DISTINCT(UPPER(SUBSTRING(user_name,1,1)))";
			if ($sortby != "all") {
				$where = "(user_name LIKE '".stripinput($sortby)."%' OR user_name LIKE '".strtolower(stripinput($sortby))."%')";
			}
			break;
	}
	// add the country filter if requested
	$where .= $country == "" ? "" : (($where == "" ? "" : " AND ").("user_cc_code = '$country'"));

	// get the list of members
	$variables['members'] = array();
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."users".($where == ""?"":(" WHERE ".$where))." ORDER BY ".$sortfield." LIMIT ".$rowstart.", ".$settings['numofthreads']);
	$rows = dbrows($result);
	if ($rows == 0 && !empty($where)) {
		// no results? Try again without a filter
		$result = dbquery("SELECT * FROM ".$db_prefix."users ORDER BY ".$sortfield." LIMIT ".$rowstart.", ".$settings['numofthreads']);
		$rows = dbrows($result);
		$sortby="all";
		$where = "";
	} 
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
	$result = dbquery("SELECT ".$letterfilter." AS letter FROM ".$db_prefix."users".($where == ""?"":(" WHERE ".$where))." ORDER BY letter");
	while ($data = dbarray($result)) {
		// get rid of unwanted characters. Need to find a beter solution for this
		$variables['search'][] = str_replace(array('&', '?'), array('',''), $data['letter']);
	}
	if (count($variables['search'])%2) $variables['search'][] = "";
	$variables['field'] = $field;
	$variables['order'] = $order;
	$variables['sortby'] = $sortby;
	$variables['rows'] = dbcount("(*)", "users", $where);
	$variables['rowstart'] = $rowstart;
	$variables['items_per_page'] = $settings['numofthreads'];
	$variables['pagenav_url'] = FUSION_SELF."?sortby=$sortby&amp;field=$field&amp;".($country==""?"":"country=$country&amp;");
}

$template_panels[] = array('type' => 'body', 'name' => 'members', 'template' => 'main.members.tpl', 'locale' => "main.members-profile");
$template_variables['members'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
