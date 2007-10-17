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

// only members have access to this module
if (!iMEMBER) fallback(BASEDIR."index.php");

// load the GeoIP module
require_once PATH_INCLUDES."geoip_include.php";

// load the locales for this module
include PATH_LOCALE.LOCALESET."members-profile.php";
include PATH_LOCALE.LOCALESET."user_fields.php";

// temp storage for template variables
$variables = array();

// list the profile of this member
if (isset($name) && !empty($name)) {
	// find a member by this name in the database
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$name' LIMIT 1");
	if (dbrows($result)) { 
		$data = dbarray($result); 
	}
	$lookup = $data['user_id'];
}
if (isset($lookup)) {
	// make sure we're only displaying one type of profile
	unset($group_id);
	if (isNum($lookup)) {
		// find a member by the ID in the database
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$lookup' LIMIT 1");
	} else {
		// find a member by username in the database
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='".stripinput($lookup)."' LIMIT 1");
	}
	if (dbrows($result)) { 
		$data = dbarray($result);
		$lookup = $data['user_id'];
	} else {
		// if not found, return to the homepage
		fallback("index.php");
	}
	// get country name and flag
	$cc_name = GeoIP_Code2Name($data['user_cc_code']);
	$cc_flag = GeoIP_Code2Flag($data['user_cc_code']);
	if ($cc_flag == "" || empty($data['user_ip']) || $data['user_ip'] == "X" || $data['user_ip'] == "0.0.0.0") {
		$cc_flag = "";
		$cc_name = $locale['408'];
	}
	$data['cc_name'] = $cc_name;
	$data['cc_flag'] = $cc_flag;
	// check the avatar
	if ($data['user_avatar'] != "" && !file_exists(PATH_IMAGES_AV.$data['user_avatar'])) {
		$data['user_avatar'] = "imagenotfound.jpg";
	}
	// user level
	$data['user_level'] = getuserlevel($data['user_level']);
	if ($data['user_birthdate'] != "0000-00-00") {
	    $months = explode("|", $locale['months']);
	    $user_birthdate = explode("-", $data['user_birthdate']);
	    $data['user_birthdate'] = $months[number_format($user_birthdate['1'])]." ".number_format($user_birthdate['2'])." ".$user_birthdate['0'];
	} else {
	    $data['user_birthdate'] = $locale['u048'];
	}
	if ($data['user_web']) {
		$urlprefix = !strstr($data['user_web'], "http://") ? "http://" : "";
		$data['user_web'] = $urlprefix.$data['user_web'];
	}
	$data['show_pm_button'] = (!isset($userdata['user_id']) || $data['user_id'] != $userdata['user_id']);
	if ($userdata['user_level'] >= 102) {
		$ip = (empty($data['user_ip']) || $data['user_ip'] == "X" || $data['user_ip'] == "0.0.0.0") ? false : $data['user_ip'];
		if ($ip) {
			$host = gethostbyaddr($ip);
			if ($ip != $host) $data['user_ip'] .= " (".$host.")";
		} else {
			unset($data['user_ip']);
		}
	}
	$data['shout_count'] = number_format(dbcount("(shout_id)", "shoutbox", "shout_name='".$data['user_id']."'"));
	$data['comment_count'] = number_format(dbcount("(comment_id)", "comments", "comment_name='".$data['user_id']."'"));
	$data['user_posts'] = number_format($data['user_posts']);
	$data['show_viewposts_button'] = ($data['user_posts'] > 0 and file_exists(PATH_MODULES."forum_threads_list_panel/my_posts.php"));
	$data['unread_count'] = number_format(dbcount("(*)", "posts_unread", "user_id='".$data['user_id']."'"));
	$data['user_email'] = str_replace("@","&#64;",$data['user_email']);

	if ($data['user_groups']) {
		$groups = (strpos($data['user_groups'], ".") == 0 ? explode(".", substr($data['user_groups'], 1)) : explode(".", $data['user_groups']));
		foreach ($groups as $group) {
			// check if this groups has subgroups. If so, add them to the array
			getsubgroups($group);
		}
		$c = 0;
		$data['user_groups'] = array();
		for ($i = 0;$i < count($groups);$i++) {
			$groupname = getgroupname($groups[$i], array(1));
			if (!empty($groupname)) {
				$data['user_groups'][] = array('group' => $groups[$i], 'name' => $groupname);
			}
		}
	}

	// define the body panel variables
	$variables['data'] = $data;
	$template_panels[] = array('type' => 'body', 'name' => 'profile', 'template' => 'main.profile.members.tpl', 'locale' => array(PATH_LOCALE.LOCALESET."members-profile.php", PATH_LOCALE.LOCALESET."user_fields.php"));
	$template_variables['profile'] = $variables;
}

// list all the members of this group
if (isset($group_id)) {
	// check if the group requested is a visible group
	$result = dbquery("SELECT * FROM ".$db_prefix."user_groups WHERE group_id='$group_id' AND (group_visible & 1)");
	if (dbrows($result) == 0) {
		fallback(BASEDIR."index.php");
	}
	$data = dbarray($result);
	// get the list of users that are a member of this group
	$userlist = allusersingroup($group_id);
	// store the amount of members retrieved
	$data['member_count'] = sprintf((count($userlist)==1?$locale['411']:$locale['412']), count($userlist));
	// save the group information
	$variables['data'] = $data;
	// gather member information 
	$members = array();
	foreach($userlist as $data) {
		if ($settings['forum_flags'] == 0 || empty($data['user_ip']) || $data['user_ip'] == "X" || $data['user_ip'] == "0.0.0.0") {
			$cc_flag = "";
		} else {
			$cc_flag = GeoIP_Code2Flag($data['user_cc_code']);
			if ($cc_flag == "") $cc_flag = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$data['cc_flag'] = $cc_flag;
		$members[] = $data;
	}
	// define the body panel variables
	$variables['members'] = $members;
	$template_panels[] = array('type' => 'body', 'name' => 'profile', 'template' => 'main.profile.groups.tpl', 'locale' => array(PATH_LOCALE.LOCALESET."members-profile.php", PATH_LOCALE.LOCALESET."user_fields.php"));
	$template_variables['profile'] = $variables;
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>