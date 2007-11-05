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
if (eregi("user_functions.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// need to GeoIP functions to determine the users country of origin
require_once "geoip_include.php";

// Check if users full or partial ip is blacklisted
$sub_ip1 = substr(USER_IP,0,strlen(USER_IP)-strlen(strrchr(USER_IP,".")));
$sub_ip2 = substr($sub_ip1,0,strlen($sub_ip1)-strlen(strrchr($sub_ip1,".")));
if (dbcount("(*)", "blacklist", "blacklist_ip='".USER_IP."' OR blacklist_ip='$sub_ip1' OR blacklist_ip='$sub_ip2'")) {
	header("Location: http://en.wikipedia.org/wiki/IP_blocking"); exit;
}

// Set the users site_visited cookie if this is the first visit, and update the unique visit counter
if (!isset($_COOKIE['site_visited'])) {
	$result=dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = cfg_value+1 WHERE cfg_name = 'counter'");
	setcookie("site_visited", "yes", time() + 31536000, "/", "", "0");
}

// Login code 
// TODO - WANWIZARD - 20070701 - DOESN'T BELONG HERE, NEEDS TO BE MOVED ELSEWHERE
if (isset($_POST['login'])) {
	$user_pass = md5($_POST['user_pass']);
	$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_POST['user_name']));
	// double hashed passwords as of revision 954
	if ($settings['revision'] >= 954) {
		$user_pass = md5($user_pass);
	}
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$user_name' AND user_password='".$user_pass."'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		// if the account is suspended, check for an expiry date
		if ($data['user_status'] == 1 && $data['user_ban_expire'] > 0 && $data['user_ban_expire'] < time() ) {
			// if this user's email address is marked as bad, reset the countdown counter
			$data['user_bad_email'] = $data['user_bad_email'] == 0 ? 0 : time();
			// reset the user status and the expiry date
			$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0', user_ban_expire='0', user_bad_email = '".$data['user_bad_email']."' WHERE user_id='".$data['user_id']."'");
			$data['user_status'] = 0;
		}
		if ($data['user_status'] == 0) {	
			// HV - set the 'remember me' status value into a cookie
			if (isset($_POST['remember_me'])) {
				setcookie("remember_me", "yes", time() + 31536000, "/", "", "0");
				$cookie_exp = time() + 3600*24*30;
			} else {
				setcookie("remember_me", "yes", time() - 7200, "/", "", "0");
				$cookie_exp = time() + 60*30;
			}
			// HV - end of code change
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			$cookie_value = $data['user_id'].".".$user_pass;
			setcookie("userinfo", $cookie_value, $cookie_exp, "/", "", "0");
			redirect(BASEDIR."setuser.php?user=".$data['user_name'], "script");
			exit;
		} elseif ($data['user_status'] == 1) {
			redirect(BASEDIR."setuser.php?user_id=".$data['user_id']."&error=1", "script");
			exit;
		} elseif ($data['user_status'] == 2) {
			redirect(BASEDIR."setuser.php?error=2", "script");
			exit;
		}
	} else {
		redirect(BASEDIR."setuser.php?error=3");
		exit;
	}
}

// This cookie expires in 30 minutes. When it does, the user will be logged out.
if (isset($_COOKIE['userinfo'])) {
	$cookie_vars = explode(".", $_COOKIE['userinfo']);
	$cookie_1 = isNum($cookie_vars['0']) ? $cookie_vars['0'] : "0";
	$cookie_2 = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$cookie_1' AND user_password='".$cookie_2."'");
	if (dbrows($result) != 0) {
		// HV - update the userinfo cookie, so it doesn't expire while the user is busy on the site
		if (isset($_COOKIE['remember_me'])) {
			setcookie("userinfo", $_COOKIE['userinfo'], time() + 3600*24*30, "/", "", "0");
		} else {
			setcookie("userinfo", $_COOKIE['userinfo'], time() + 60*30, "/", "", "0");
		}
		// HV - end of changed code
		unset($cookie_vars,$cookie_1,$cookie_2);
		$userdata = dbarray($result);
		if ($userdata['user_status'] == 0) {
			if ($userdata['user_theme'] != "Default" && file_exists(PATH_THEMES.$userdata['user_theme']."/theme.php")) {
				define("PATH_THEME", PATH_THEMES.$userdata['user_theme']."/");
				define("THEME", THEMES.$userdata['user_theme']."/");
			} else {
				define("PATH_THEME", PATH_THEMES.$settings['theme']."/");
				define("THEME", THEMES.$settings['theme']."/");
			}
			if ($userdata['user_offset'] <> 0) {
				$settings['timeoffset'] = $settings['timeoffset'] + $userdata['user_offset'];
			}
			if (empty($_COOKIE['lastvisit'])) {
				setcookie("lastvisit", $userdata['user_lastvisit'], time(), "/", "", "0");
				$lastvisited = $userdata['user_lastvisit'];
			} else {
				$lastvisited = $_COOKIE['lastvisit'];
			}
		} else {
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			// make sure the old user cookie is erased
			setcookie("user", "", time() - 7200, "/", "", "0");
			setcookie("userinfo", "", time() - 7200, "/", "", "0");
			setcookie("lastvisit", "", time() - 7200, "/", "", "0");
			redirect(BASEDIR."index.php", "script");
			exit;
		}
	} else {
		unset($cookie_vars,$cookie_1,$cookie_2);
		header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		// make sure the old user cookie is erased
		setcookie("user", "", time() - 7200, "/", "", "0");
		setcookie("userinfo", "", time() - 7200, "/", "", "0");
		setcookie("lastvisit", "", time() - 7200, "/", "", "0");
		redirect(BASEDIR."index.php", "script");
		exit;
	}
} else {
	define("PATH_THEME", PATH_THEMES.$settings['theme']."/");
	define("THEME", THEMES.$settings['theme']."/");
	$userdata = array(); $userdata['user_level'] = 0; $userdata['user_rights'] = ""; $userdata['user_groups'] = "";
}

if (isset($userdata) && is_array($userdata)) {
	// if group memberships are defined, get the users own group memberships into an array
	if (!empty($userdata['user_groups'])) {
		$groups = explode(".", substr($userdata['user_groups'], 1));
		foreach ($groups as $group) {
			// check if this groups has subgroups. If so, add them to the array
			getsubgroups($group);
		}
		// create a new user_group field with all inherited groups, and
		// get the inherited group rights and add them to the user own rights
		$userdata['user_groups'] = "";
		foreach ($groups as $group) {
			$userdata['user_groups'] .= ".".$group;
			$result = dbarray(dbquery("SELECT group_rights FROM ".$db_prefix."user_groups WHERE group_id = '".$group."'"));
			if (isset($result['group_rights']) && $result['group_rights'] != "") {
				$userdata['user_rights'] .= ($userdata['user_rights']==""?"":".").$result['group_rights'];
			}
		}
	}
	// set the User level, Admin Rights & User Group definitions
	define("iUSER", $userdata['user_level']);
	define("iUSER_GROUPS", substr($userdata['user_groups'], 1));
	define("iUSER_RIGHTS", $userdata['user_rights']);
	define("iGUEST", $userdata['user_level'] == 0 ? 1 : 0);
	define("iMEMBER", $userdata['user_level'] >= 101 ? 1 : 0);
	define("iADMIN", iUSER_RIGHTS != "" ? 1 : 0);
	define("iSUPERADMIN", $userdata['user_level'] == 103 ? 1 : 0);
	// dynamically update the members admin status based on the users current rights
	if (iUSER == '101' && iADMIN) {
		// upgrade to administrator
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level = '102' WHERE user_id = '".$userdata['user_id']."'");
	}
	if (iUSER == '102' && !iADMIN) {
		// downgrade to ordinary member
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level = '101' WHERE user_id = '".$userdata['user_id']."'");
	}
} else {
	define("iUSER", 0);
	define("iUSER_GROUPS", "");
	define("iUSER_RIGHTS", "");
	define("iGUEST", 1);
	define("iMEMBER", 0);
	define("iADMIN", 0);
	define("iSUPERADMIN", 0);
}

// if logged in, update the users lastvisit time and country
if (iMEMBER) {
	$cc_code = $userdata['user_id'] == 1 ? $locale['country'] : GeoIP_IP2Code(USER_IP, true);
	$result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."', user_cc_code='".$cc_code."' WHERE user_id='".$userdata['user_id']."'");
}

// if the user is an administrator, generate the security aidlink
if (iADMIN) {
	define("iAUTH", substr(md5($userdata['user_password']),16,32));
	$aidlink = "?aid=".iAUTH;
}

/*---------------------------------------------------+
| User related global functions                      |
+----------------------------------------------------*/

// Display the user's level
function getuserlevel($userlevel) {
	global $locale;
	if ($userlevel==100) { return $locale['usera']; }
	elseif ($userlevel==101) { return $locale['user1']; }
	elseif ($userlevel==102) { return $locale['user2']; }
	elseif ($userlevel==103) { return $locale['user3']; }
}

// Check if Administrator has correct rights assigned
function checkrights($right) {
	if (iSUPERADMIN || (iADMIN && in_array($right, explode(".", iUSER_RIGHTS)))) {
		return true;
	} else {
		return false;
	}
}

// Check if user is assigned to the specified user group
function checkgroup($group) {
	if (iSUPERADMIN && ($group != "100")) { return true; }
//	if (iSUPERADMIN && ($group == "0" || $group == "101" || $group == "102" || $group == "103")) { return true; }
	elseif (iADMIN && ($group == "0" || $group == "101" || $group == "102")) { return true; }
	elseif (iMEMBER && ($group == "0" || $group == "101")) { return true; }
	elseif (iGUEST && ($group == "0" || $group == "100")) { return true; }
	elseif (iMEMBER && in_array($group, explode(".", iUSER_GROUPS))) {
		return true;
	} else {
		return false;
	}
}

// Compile access levels & user group array
function getusergroups($membersonly=false,$namedarray=false) {
	global $locale, $db_prefix;

	$groups_array= array();
	if ($namedarray) {
		if (!$membersonly) $groups_array[] = array("id" => "0", "name" => $locale['user0']);
		$gsql = dbquery("SELECT group_id,group_name FROM ".$db_prefix."user_groups ORDER BY group_id");
		while ($gdata = dbarray($gsql)) {
			array_push($groups_array, array("id" => $gdata['group_id'], "name" => $gdata['group_name']));
		}
		if (!$membersonly) $groups_array[] = array("id" => "100", "name" => $locale['usera']);
		array_push($groups_array, array("id" => "101", "name" => $locale['user1']));
		array_push($groups_array, array("id" => "102", "name" => $locale['user2']));
		array_push($groups_array, array("id" => "103", "name" => $locale['user3']));
	} else {
		if (!$membersonly) $groups_array[] = array("0", $locale['user0']);
		$gsql = dbquery("SELECT group_id,group_name FROM ".$db_prefix."user_groups ORDER BY group_id");
		while ($gdata = dbarray($gsql)) {
			array_push($groups_array, array($gdata['group_id'], $gdata['group_name']));
		}
		if (!$membersonly) $groups_array[] = array("100", $locale['usera']);
		array_push($groups_array, array("101", $locale['user1']));
		array_push($groups_array, array("102", $locale['user2']));
		array_push($groups_array, array("103", $locale['user3']));
	}
	return $groups_array;
}

// Get the name of the access level or user group
function getgroupname($group, $visible="", $type='n') {
	global $locale, $db_prefix;

	if ($group == "0") { return $locale['user0']; }
	elseif ($group == "100") { return $locale['usera']; }
	elseif ($group == "101") { return $locale['user1']; }
	elseif ($group == "102") { return $locale['user2']; }
	elseif ($group == "103") { return $locale['user3'];
	} else {
		$gsql = dbquery("SELECT group_id,group_name, group_visible FROM ".$db_prefix."user_groups WHERE group_id='$group'");
		if (dbrows($gsql)!=0) {
			$gdata = dbarray($gsql);
			// make sure this is an array
			if (!is_array($visible)) {
				$visible = array(0 => $visible);
			}
			foreach($visible as $displaytype) {
				if ($displaytype == -1 || $gdata['group_visible'] & pow(2, $displaytype-1)) {
					if (strtolower($type)=="f" && $gdata['group_forumname'] != "") return $gdata['group_forumname'];
					if (strtolower($type)=="d" && $gdata['group_description'] != "") return $gdata['group_description'];
					return $gdata['group_name'];
				}
			}
			return "";
		} else {
			return "N/A";
		}
	}
}

// Get the access level of a user group
function getaccesslevel($group) {
	global $locale, $db_prefix;

	if ($group == $locale['user0']) { return "0"; }
	elseif ($group == $locale['usera']) { return "100"; }
	elseif ($group == $locale['user1']) { return "101"; }
	elseif ($group == $locale['user2']) { return "102"; }
	elseif ($group == $locale['user3']) { return "103";
	} else {
		$gsql = dbquery("SELECT group_id,group_name FROM ".$db_prefix."user_groups WHERE group_name='$group'");
		if (dbrows($gsql)!=0) {
		$gdata = dbarray($gsql);
		return $gdata['group_id'];
		} else {
		return -1;  // do not use False here. This evaluates to 0, which means "GUEST" access!
		}
	}
}

function groupaccess($field) {
	global $userdata;
	
	// for now, this is fixed (could be used as a parameter to reveal '255' access records
	$hidden = false;

	// access value 255 means nobody has access to it. Used to hide things from view ;-)
	$res = ($hidden == false?"$field !='255'":"");

	if (iSUPERADMIN) { 
		$res .= ($hidden == false?" AND ":"")."($field != '100'";
	} elseif ($userdata['user_level'] >= 102) { 
		$res .= ($hidden == false?" AND ":"")."($field='0' OR $field='101' OR $field='102'";
	} elseif (iMEMBER) { 
		$res .= ($hidden == false?" AND ":"")."($field='0' OR $field='101'";
	} elseif (iGUEST) { 
		$res .= ($hidden == false?" AND ":"")."($field='0' OR $field='100'"; }
	if (iUSER_GROUPS != "") 
		$res .= " OR $field='".str_replace(".", "' OR $field='", iUSER_GROUPS)."'";
	$res .= ")";
	return $res;
}

// get all groups $group_id is a member of, our user is also a member of those groups
// due to inheritance. This is a recusive function, so indefinate nesting is possible
function getsubgroups($group_id) {
	global $groups, $db_prefix;

	$result = dbquery("SELECT group_groups FROM ".$db_prefix."user_groups WHERE group_id = '$group_id'");
	while ($data = dbarray($result)) {
		if (!empty($data['group_groups'])) {
			$newgroups = explode(".", substr($data['group_groups'], 1));
			foreach ($newgroups as $newgroup) {
				if (!in_array($newgroup, $groups)) {
					$groups[] = $newgroup;
					getsubgroups($newgroup);
				}
			}
		}
	}
	return;
}

// get all groups members of the group
// due to inheritance. This is a recusive function, so indefinate nesting is possible
function getgroupmembers($group_id) {
	global $groups, $db_prefix;

	// store this group_id
	if (!in_array($group_id, $groups)) $groups[] = $group_id;

	// check for subgroups of this group
	$result = dbquery("SELECT group_id, group_groups FROM ".$db_prefix."user_groups WHERE group_groups REGEXP('^\\\.{$group_id}$|\\\.{$group_id}\\\.|\\\.{$group_id}$')");
	while ($data = dbarray($result)) {
		// recurse
		getgroupmembers($data['group_id']);
	}
	return;
}

// returns an array of all member id's that are a member of the group requested
function allusersingroup($group_id) {

	global $groups, $settings, $db_prefix;
	
	// gather the group and it's sub-groups into an array
	$groups = array();
	getgroupmembers($group_id);

	// get all users from those groups
	$sql = "SELECT * FROM ".$db_prefix."users WHERE ";
	$c = 0;
	foreach ($groups as $group) {
		$sql .= ($c++==0?"":"OR ")."user_groups REGEXP('^\\\.{$group}$|\\\.{$group}\\\.|\\\.{$group}$') ";
	}
	if ($c == 0) $sql .= "0 ";
	$sql .= "ORDER BY user_level DESC, user_name";
	$result = dbquery($sql);

	// gather member information 
	$members = array();
	while ($data = dbarray($result)) {
		if ($settings['forum_flags'] == 0 || empty($data['user_ip']) || $data['user_ip'] == "X" || $data['user_ip'] == "0.0.0.0") {
			$cc_flag = "";
		} else {
			$cc_flag = GeoIP_Code2Flag($data['user_cc_code']);
			if ($cc_flag == "") $cc_flag = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$data['cc_flag'] = $cc_flag;
		$data['user_level'] = getuserlevel($data['user_level']);
		$members[] = $data;
	}
	return $members;
}
?>
