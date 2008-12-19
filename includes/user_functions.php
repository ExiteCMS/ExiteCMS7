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
if (eregi("user_functions.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// load and instantiate the authentication class
require_once "authentication/authentication.php";
$cms_authentication =& new authentication();

// need the GeoIP functions to determine the users country of origin
require_once "geoip_include.php";

// Check if users full or partial ip is blacklisted
$sub_ip1 = substr(USER_IP,0,strlen(USER_IP)-strlen(strrchr(USER_IP,".")));
$sub_ip2 = substr($sub_ip1,0,strlen($sub_ip1)-strlen(strrchr($sub_ip1,".")));
if (FUSION_SELF != "setuser.php" && dbcount("(*)", "blacklist", "blacklist_ip='".USER_IP."' OR blacklist_ip='$sub_ip1' OR blacklist_ip='$sub_ip2'")) {
	redirect(BASEDIR."setuser.php?error=6");
	exit;
}

// check for bot users
$_bot_list = array("Teoma","alexa","froogle","Gigabot","inktomi","looksmart","URL_Spider_SQL","Firefly","NationalDirectory","Ask Jeeves","TECNOSEEK",
					"InfoSeek","WebFindBot","girafabot","crawler","www.galaxy.com","Googlebot","Scooter","Slurp","msnbot","appie","FAST","WebBug",
					"Spade","ZyBorg","rabaz","Baiduspider","Feedfetcher-Google","TechnoratiSnoop","Rankivabot","Mediapartners-Google","Sogou web spider",
					"WebAlta Crawler","Twiceler","curl/"
				);
foreach($_bot_list as $bot) {
	if(strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
		define("CMS_IS_BOT", true);
		break;
	}
}
if (!defined("CMS_IS_BOT")) {
	define("CMS_IS_BOT", false);
}
unset($_bot_list);

// Set the users site_visited cookie if this is the first visit, and update the unique visit counter
// save the random site_visited value, we need that later in session management!
if (!CMS_IS_BOT) {
	if (!isset($_COOKIE['site_visited'])) {
		$result=dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = cfg_value+1 WHERE cfg_name = 'counter'");
		$site_visited = md5(uniqid(rand(), true));
		setcookie("site_visited", $site_visited, time() + 31536000, "/", "", "0");
	} else {
		// replace the pre v7.1 cookie if needed
		if ($_COOKIE['site_visited'] == "yes") {
			$site_visited = md5(uniqid(rand(), true));
		} else {
			// get the cookie value
			$site_visited = $_COOKIE['site_visited'];
		}
		// refresh the cookie
		setcookie("site_visited", $site_visited, time() + 31536000, "/", "", "0");
	}
}

// if not in the process of posting a form, did the login session expire?
if (count($_POST)==0 && !empty($_SESSION['login_expire']) && $_SESSION['login_expire'] < time()) {
	$cms_authentication->logoff();
}

// Are we logged in?
if ($cms_authentication->logged_on()) {

	$userdata = $cms_authentication->get_userinfo();
	// set the user's theme
	if (isset($_SESSION['set_theme']) && file_exists(PATH_THEMES.$_SESSION['set_theme']."/theme.php")) {
		$userdata['user_theme'] = $_SESSION['set_theme'];
		unset($_SESSION['set_theme']);
		$result2 = dbquery("UPDATE ".$db_prefix."users SET user_theme = '".$userdata['user_theme']."' WHERE user_id='$userinfo_1' AND user_password='$userinfo_2'");
		define("PATH_THEME", PATH_THEMES.$userdata['user_theme']."/");
		define("THEME", THEMES.$userdata['user_theme']."/");
	} elseif ($userdata['user_theme'] != "Default" && file_exists(PATH_THEMES.$userdata['user_theme']."/theme.php")) {
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
	// update the login expiration timestamp
	if ($settings['login_expire']) {
		if (isset($_SESSION['remember_me']) && $_SESSION['remember_me'] == "yes") {
			$_SESSION['login_expire'] = time() + $settings['login_extended_expire'];
		} else {
			$_SESSION['login_expire'] = time() + $settings['login_expire'];
		}
	} else {
		$_SESSION['login_expire'] = mktime(0,0,0,1,1,2038);	// do not expire
	}
} else {
	// is login required?
	if ($settings['auth_required'] && !in_array(FUSION_SELF, array("login.php","setuser.php","lostpassword.php", "register.php","maintenance.php"))) {
		redirect(BASEDIR."login.php", "script");
		exit;
	}
	// create a dummy userdata array
	$userdata = array(); 
	$userdata['user_level'] = 0; $userdata['user_rights'] = ""; $userdata['user_groups'] = ""; $userdata['user_datastore'] = array();
	// check for a theme selection. If present, override the default theme
	if (isset($_SESSION['set_theme']) && file_exists(PATH_THEMES.$_SESSION['set_theme']."/theme.php")) {
		define("PATH_THEME", PATH_THEMES.$_SESSION['set_theme']."/");
		define("THEME", THEMES.$_SESSION['set_theme']."/");
		$userdata['user_theme'] = $_SESSION['set_theme'];
	} else {
		define("PATH_THEME", PATH_THEMES.$settings['theme']."/");
		define("THEME", THEMES.$settings['theme']."/");
		$userdata['user_theme'] = $settings['theme'];
	}
}

// if logged in, extract info from the userdata record
if (isset($userdata) && is_array($userdata)) {
	// extract the user datastore
	if (!empty($userdata['user_datastore'])) {
		$userdata['user_datastore'] = unserialize($userdata['user_datastore']);
	} else {
		$userdata['user_datastore'] = array();
	}
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

// if the user changed the state of a panel, a cookie has been created to record the new state
// get these cookies, and store them in the users session record to be reused, and delete the cookie
if (!isset($userdata['user_datastore']['panelstates'])) {
	$userdata['user_datastore']['panelstates'] = array();
}
foreach($_COOKIE as $cookiename => $cookievalue) {
	if (substr($cookiename,0,4) == "box_" && isNum($cookievalue) && ($cookievalue == 0 || $cookievalue == 1)) {
		// store the value
		$userdata['user_datastore']['panelstates'][$cookiename] = $cookievalue;
		// and delete the cookie
		setcookie ($cookiename, "", 1);
	}
}

// get the country code for this user, override the country code for webmasters if needed
define('USER_CC', (iSUPERADMIN && $settings['hide_webmaster']) ? $settings['country'] : GeoIP_IP2Code(USER_IP, true));

// if logged in, update the users lastvisit time and country
if (iMEMBER) {
	$result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."', user_cc_code='".USER_CC."' WHERE user_id='".$userdata['user_id']."'");
}

if (!CMS_IS_BOT) {
	if (iGUEST) {
		// update the last users online information for guests
		$result = dbquery("SELECT * FROM ".$db_prefix."online WHERE online_user='0' AND online_ip='".USER_IP."'");
		if (dbrows($result) != 0) {
			$result = dbquery("UPDATE ".$db_prefix."online SET online_lastactive='".time()."' WHERE online_user='0' AND online_ip='".USER_IP."'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."online (online_user, online_ip, online_lastactive) VALUES ('0', '".USER_IP."', '".time()."')");
		}
	} else {
		// update the last users online information for members
		$result = dbquery("SELECT * FROM ".$db_prefix."online WHERE online_user='".$userdata['user_id']."'");
		if (dbrows($result) != 0) {
			$result = dbquery("UPDATE ".$db_prefix."online SET online_lastactive='".time()."' WHERE online_user='".$userdata['user_id']."' AND online_ip='".USER_IP."'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."online (online_user, online_ip, online_lastactive) VALUES ('".$userdata['user_id']."', '".USER_IP."', '".time()."')");
		}
	}
}
// users inactive for more than 5 minutes are not considered to be online
$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_lastactive<".(time()-300)."");

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

// generate the security aidlink
if (iMEMBER) {
	define("iAUTH", substr(md5($userdata['user_password']),16,32));
	$aidlink = "?aid=".iAUTH;
}

// check for upgrades in progress.
// NOTE: when in CLI mode,  skip this. Also, when a form has been posted, skip this and finish the POST!
if (!CMS_CLI && count($_POST)==0 && !eregi("upgrade.php", $_SERVER['PHP_SELF'])) {

	include PATH_ADMIN."upgrade.php";
	//  If so, force a switch to maintenance mode
	if (UPGRADES) $settings['maintenance'] = 2;

	// if not called from the maintenance mode module! (to prevent a loop, endless ;-)
	// check if we need to redirect to maintenance mode (for users) or upgrade (for webmasters)
	if (!iSUPERADMIN && $settings['maintenance'] && !eregi("maintenance.php", $_SERVER['PHP_SELF'])) {
		// deny all non-webmasters access to the site
		redirect(BASEDIR.'maintenance.php?reason='.$settings['maintenance']);
	}
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

// Check if the current user is assigned to the specified user group
function checkgroup($group, $all4superadmin = true) {
	// webmaster is member of everything exept anonymous
	if (iSUPERADMIN && $all4superadmin && ($group != "100")) { return true; }
	// check the webmasters try memberships if all4superadmin = false!
	elseif (iSUPERADMIN && ($group == "0" || $group == "101" || $group == "102" || $group == "103")) { return true; }
	elseif (iADMIN && ($group == "0" || $group == "101" || $group == "102")) { return true; }
	elseif (iMEMBER && ($group == "0" || $group == "101")) { return true; }
	elseif (iGUEST && ($group == "0" || $group == "100")) { return true; }
	elseif (iMEMBER && !empty($group) && in_array($group, explode(".", iUSER_GROUPS))) {
		return true;
	} else {
		return false;
	}
}

// Check if a user is assigned to the specified user group
function checkusergroup($user_id, $group_id) {
	global $groups, $db_prefix;

	// result cache
	static $resultcache;
	if (isset($resultcache[$user_id][$group_id])) {
		return $resultcache[$user_id][$group_id];
	}

	$check = false;
	if ($group_id == "101") { 
		// every user is a member
		$check = true;
	} else {
		// get the group rights from the user record
		$result = dbquery("SELECT user_groups, user_level FROM ".$db_prefix."users WHERE user_id = '".$user_id."'");
		if ($data = dbarray($result)) {
			// check if the requested group matches a user level
			if ($group_id == $data['user_level']) { 
				$check = true;
			} else {
				// if group memberships are defined, get the users own group memberships into an array
				if (!empty($data['user_groups'])) {
					$groups = explode(".", substr($data['user_groups'], 1));
					foreach ($groups as $group) {
						// check if this groups has subgroups. If so, add them to the array
						getsubgroups($group);
					}
					// now that we have all groups, check for a match
					foreach ($groups as $group) {
						if ($group == $group_id) { 
							$check = true;
							break;
						}
					}
				}
			}
		}
	}

	if (!isset($resultcache[$user_id])) $resultcache[$user_id] = array();
	$resultcache[$user_id][$group_id] = $check;

	return $check;
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
		if (!$membersonly) $groups_array[0] = array("0", $locale['user0']);
		$gsql = dbquery("SELECT group_id,group_name FROM ".$db_prefix."user_groups ORDER BY group_id");
		while ($gdata = dbarray($gsql)) {
			$groups_array[$gdata['group_id']] = array($gdata['group_id'], $gdata['group_name']);
		}
		if (!$membersonly) $groups_array[100] = array("100", $locale['usera']);
		$groups_array[101] = array("101", $locale['user1']);
		$groups_array[102] = array("102", $locale['user2']);
		$groups_array[103] = array("103", $locale['user3']);
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
		$res .= ($res != ""?" AND ":"")."$field != '100'";
		return $res;
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

	// result cache
	static $resultcache;
	if (isset($resultcache[$group_id])) {
		return $resultcache[$group_id];
	}
	
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

	if (!isset($resultcache)) $resultcache = array();
	$resultcache[$group_id] = $members;

	return $members;
}
?>
