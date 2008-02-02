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
// save the random site_visited value, we need that later in session management!
if (!isset($_COOKIE['site_visited'])) {
	$result=dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = cfg_value+1 WHERE cfg_name = 'counter'");
	$site_visited = md5(uniqid(rand(), true));
	setcookie("site_visited", $site_visited, time() + 31536000, "/", "", "0");
} else {
	// replace the pre v7.1 cookie if needed
	if ($_COOKIE['site_visited'] == "yes") {
		$site_visited = md5(uniqid(rand(), true));
		setcookie("site_visited", $site_visited, time() + 31536000, "/", "", "0");
	} else {
		$site_visited = $_COOKIE['site_visited'];
	}
}

// Login code 
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
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			// set the 'remember me' status value 
			$_SESSION['remember_me'] = isset($_POST['remember_me']) ? "1" : "0";
			$_SESSION['userinfo'] = $data['user_id'].".".$user_pass;
			$_SESSION['login_expire'] = isset($_POST['remember_me']) ? (time() + 60*60*24*30) : (time() + 60*60);
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

// login session expired?
if (!empty($_SESSION['login_expire']) && $_SESSION['login_expire'] < time()) {
	// clear the login info from the session
	unset($_SESSION['user']);
	unset($_SESSION['userinfo']);
	unset($_SESSION['login_expire']);
}

// Are we logged in?
if (isset($_SESSION['userinfo'])) {
	$userinfo_vars = explode(".", $_SESSION['userinfo']);
	$userinfo_1 = isNum($userinfo_vars['0']) ? $userinfo_vars['0'] : "0";
	$userinfo_2 = (preg_match("/^[0-9a-z]{32}$/", $userinfo_vars['1']) ? $userinfo_vars['1'] : "");
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$userinfo_1' AND user_password='$userinfo_2'");
	unset($userinfo_vars,$userinfo_1,$userinfo_2);
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		if ($userdata['user_status'] == 0) {
			if ($userdata['user_theme'] != "Default" && file_exists(PATH_THEMES.$userdata['user_theme']."/theme.php")) {
				define("PATH_THEME", PATH_THEMES.$userdata['user_theme']."/");
				define("THEME", THEMES.$userdata['user_theme']."/");
			} else {
				define("PATH_THEME", PATH_THEMES.$settings['theme']."/");
				define("THEME", THEMES.$settings['theme']."/");
				// make sure the default theme exists!
				if (!file_exists(PATH_THEMES.$settings['theme']."/theme.php")) {
					die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>FATAL ERROR: Unable to load the default theme</b></div>");
				}
			}
			if ($userdata['user_offset'] <> 0) {
				$settings['timeoffset'] = $settings['timeoffset'] + $userdata['user_offset'];
			}
			if (empty($_SESSION['lastvisit'])) {
				$_SESSION['lastvisit'] = $userdata['user_lastvisit'];
				$lastvisited = $userdata['user_lastvisit'];
			} else {
				$lastvisited = $_SESSION['lastvisit'];
			}
		} else {
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			// make sure the user info is erased from the session
			unset($_SESSION['user']);
			unset($_SESSION['userinfo']);
			unset($_SESSION['login_expire']);
			unset($_SESSION['lastvisit']);
			redirect(BASEDIR."index.php", "script");
			exit;
		}
	} else {
		header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		// make sure the user info is erased from the session
		unset($_SESSION['user']);
		unset($_SESSION['userinfo']);
		unset($_SESSION['login_expire']);
		unset($_SESSION['lastvisit']);
		redirect(BASEDIR."index.php", "script");
		exit;
	}
} else {
	define("PATH_THEME", PATH_THEMES.$settings['theme']."/");
	define("THEME", THEMES.$settings['theme']."/");
	$userdata = array(); $userdata['user_level'] = 0; $userdata['user_rights'] = ""; $userdata['user_groups'] = "";
}

// if logged in, extract info from the userdata record
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
	$cc_code = (iSUPERADMIN && $settings['hide_webmaster']) ? $settings['country'] : GeoIP_IP2Code(USER_IP, true);
	$result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."', user_cc_code='".$cc_code."' WHERE user_id='".$userdata['user_id']."'");
}

// update the threads_read table for the current user
if (iMEMBER) {
	// get all new threads for this user since we've last checked
	$result = dbquery("SELECT t.forum_id, t.thread_id FROM ".$db_prefix."threads t, ".$db_prefix."forums f WHERE f.forum_id = t.forum_id AND ".groupaccess('f.forum_access')." AND thread_lastpost > '".$userdata['user_forum_datestamp']."'");
	$result2 = dbquery("UPDATE ".$db_prefix."users SET user_forum_datestamp = '".time()."' WHERE user_id = '".$userdata['user_id']."'");
	// insert a new threads_read record for these threads, to indicate we haven't read them yet
	while ($data = dbarray($result)) {
		$result2 = dbquery("INSERT IGNORE INTO ".$db_prefix."threads_read (user_id, forum_id, thread_id, thread_last_read) VALUES ('".$userdata['user_id']."', '".$data['forum_id']."', '".$data['thread_id']."', '".$userdata['user_forum_datestamp']."')");
	}	
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
		if (!$membersonly) $groups_array[$locale['user0']] = array("id" => "0", "name" => $locale['user0']);
		$gsql = dbquery("SELECT group_id,group_name FROM ".$db_prefix."user_groups ORDER BY group_id");
		while ($gdata = dbarray($gsql)) {
			$groups_array[$gdata['group_name']] = array("id" => $gdata['group_id'], "name" => $gdata['group_name']);
		}
		if (!$membersonly) $groups_array[$locale['usera']] = array("id" => "100", "name" => $locale['usera']);
		$groups_array[$locale['user1']] = array("id" => "101", "name" => $locale['user1']);
		$groups_array[$locale['user2']] = array("id" => "102", "name" => $locale['user2']);
		$groups_array[$locale['user3']] = array("id" => "103", "name" => $locale['user3']);
	} else {
		if (!$membersonly) $groups_array[$locale['user0']] = array("0", $locale['user0']);
		$gsql = dbquery("SELECT group_id,group_name FROM ".$db_prefix."user_groups ORDER BY group_id");
		while ($gdata = dbarray($gsql)) {
			$groups_array[$gdata['group_name']] = array($gdata['group_id'], $gdata['group_name']);
		}
		if (!$membersonly) $groups_array[$locale['usera']] = array("100", $locale['usera']);
		$groups_array[$locale['user1']] = array("101", $locale['user1']);
		$groups_array[$locale['user2']] = array("102", $locale['user2']);
		$groups_array[$locale['user3']] = array("103", $locale['user3']);
	}
	// sort the array numerically
	ksort($groups_array);
	// the array returned needs a numeric index
	$groups = array();
	foreach($groups_array as $group) {
		$groups[] = $group;
	}
	return $groups;
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
			return false;
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
