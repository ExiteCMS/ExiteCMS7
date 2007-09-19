<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| Some portions copyright ? 2002 - 2006 Nick Jones   |
| http://www.php-fusion.co.uk/                       |
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.LOCALESET."admin/forum_polls.php";

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("PO") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid
$forum_id = (isset($_POST['forum_id']) && isNum($_POST['forum_id'])) ? $_POST['forum_id'] : 0;

// store the forum we're editing
$edit_forum = isset($_POST['forum_select']) ? $forum_id : "";
$variables['edit_forum'] = $edit_forum;

// init, store the default global settings
$result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id = '0'");
if (dbrows($result) == 0) {
	// create a default global settings record
	$data = array('forum_id' => 0,
				'enable_polls' => 0,
				'create_permissions' => "",
				'vote_permissions' => "",
				'guest_permissions' => 0,
				'require_approval' => 0,
				'lock_threads' => 0,
				'option_max' => 0,
				'option_show' => 0,
				'option_increment' => 0,
				'duration_min' => 0,
				'duration_max' => 0,
				'hide_poll' => 0
			);
	// and store them
	$result = dbquery("INSERT INTO ".$db_prefix."forum_poll_settings
		(forum_id, enable_polls, create_permissions, vote_permissions, guest_permissions, require_approval, lock_threads,
		option_max, option_show, option_increment, duration_min, duration_max, hide_poll) VALUES
		('0', '".$data['enable_polls']."', '".$data['create_permissions']."', '".$data['vote_permissions']."',
		'".$data['guest_permissions']."', '".$data['require_approval']."', '".$data['lock_threads']."',
		'".$data['option_max']."', '".$data['option_show']."', '".$data['option_increment']."', '".$data['duration_min']."',
		'".$data['duration_max']."', '".$data['hide_poll']."')"
	);
}

// save forum settings?
if (isset($_POST['save_settings']) && !isset($_POST['reset'])) {
	// validate the input
	$enable_polls = isset($_POST['enable_polls']) && $_POST['enable_polls'] == 1 ? 1 : 0;
	$same_voters = isset($_POST['same_voters']) ? 1 : 0;
	$same_creators = isset($_POST['same_creators']) ? 1 : 0;
	$temp_array = isset($_POST['create_groups']) ? explode(",", $_POST['create_groups']) : "";
	$temp_array2 = array();
	for($i = 0; $i < count($temp_array); $i ++) {
		if (isNum($temp_array[$i])) { $temp_array2[] = "G".$temp_array[$i]; }
	}
	$temp_array = isset($_POST['create_users']) ? explode(",", $_POST['create_users']) : "";
	for($i = 0; $i < count($temp_array); $i ++) {
		if (isNum($temp_array[$i])) { $temp_array2[] = $temp_array[$i]; }
	}
	$temp_array = isset($_POST['vote_groups']) ? explode(",", $_POST['vote_groups']) : "";
	$temp_array3 = array();
	for($i = 0; $i < count($temp_array); $i ++) {
		if (isNum($temp_array[$i])) { $temp_array3[] = "G".$temp_array[$i]; }
	}
	$temp_array = isset($_POST['vote_users']) ? explode(",", $_POST['vote_users']) : "";
	for($i = 0; $i < count($temp_array); $i ++) {
		if (isNum($temp_array[$i])) { $temp_array3[] = $temp_array[$i]; }
	}
	$create_array = $same_creators == 1 ? array_merge_recursive($temp_array2, $temp_array3) : $temp_array2;
	$vote_array = $same_voters == 1 ? array_merge_recursive($temp_array2, $temp_array3) : $temp_array3;
	sort($create_array); sort($vote_array);
	$create_permissions = implode(".", $create_array);
	$vote_permissions = implode(".", $vote_array);
	$guest_permissions = isset($_POST['guest_permissions']) && isNum($_POST['guest_permissions']) && $_POST['guest_permissions'] < 3 ? $_POST['guest_permissions'] : 0;
	$require_approval = isset($_POST['require_approval']) && $_POST['require_approval'] == 1 ? 1 : 0;
	$lock_threads = isset($_POST['lock_threads']) && $_POST['lock_threads'] == 1 ? 1 : 0;
	$option_max = isset($_POST['option_max']) && isNum($_POST['option_max']) ? $_POST['option_max'] : 10;
	$option_show = isset($_POST['option_show']) && isNum($_POST['option_show']) ? $_POST['option_show'] : 5;
	$option_increment = isset($_POST['option_increment']) && isNum($_POST['option_increment']) ? $_POST['option_increment'] : 5;
	$duration_min = isset($_POST['duration_min']) && isNum($_POST['duration_min']) && $_POST['duration_min'] >= 1 ? $_POST['duration_min'] : 1;
	$duration_max = isset($_POST['duration_max']) && isNum($_POST['duration_max']) ? $_POST['duration_max'] : 0;
	$hide_poll = isset($_POST['hide_poll']) && $_POST['hide_poll'] == 1 ? 1 : 0;
	// check if this record already exists
	$result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id = '$forum_id'");
	if (dbrows($result) != 0) {
		$result = dbquery("UPDATE ".$db_prefix."forum_poll_settings SET forum_id = '$forum_id',
			enable_polls = '".$enable_polls."', create_permissions = '".$create_permissions."',
			vote_permissions = '".$vote_permissions."', guest_permissions = '".$guest_permissions."',
			require_approval = '".$require_approval."', lock_threads = '".$lock_threads."',
			option_max = '".$option_max."', option_show = '".$option_show."',
			option_increment = '".$option_increment."', duration_min = '".($duration_min * 86400)."',
			duration_max = '".($duration_max * 86400)."', hide_poll = '".$hide_poll."' WHERE forum_id='$forum_id'"
		);
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."forum_poll_settings
			(forum_id, enable_polls, create_permissions, vote_permissions, guest_permissions, require_approval, lock_threads,
			option_max, option_show, option_increment, duration_min, duration_max, hide_poll) VALUES
			('$forum_id', '".$enable_polls."', '".$create_permissions."', '".$vote_permissions."',
			'".$guest_permissions."', '".$require_approval."', '".$lock_threads."',
			'".$option_max."', '".$option_show."', '".$option_increment."', '".($duration_min*86400)."',
			'".($duration_max*86400)."', '".$hide_poll."')"
		);
	}
	$forum_id = "";
} elseif (isset($_POST['reset'])) {
	// delete the record (force to use globals again)
	$result = dbquery("DELETE FROM ".$db_prefix."forum_poll_settings WHERE forum_id = '$forum_id'");
	// and force a reload of the edit form
	$edit_forum = $forum_id;
	$variables['edit_forum'] = $edit_forum;
}

// generate the list of available forums
$result = dbquery(
	"SELECT f.forum_id, f.forum_name, f2.forum_name AS forum_cat_name
	FROM ".$db_prefix."forums f
	INNER JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
$variables['forums'] = array();
$current_cat = "";
while ($data2 = dbarray($result)) {
	if ($data2['forum_cat_name'] != $current_cat) {
		$data2['forum_new_cat'] = true;
		$current_cat = $data2['forum_cat_name'];
	} else {
		$data2['forum_new_cat'] = false;
	}
	if ($data2['forum_id'] == $forum_id) {
		$data2['selected'] = true;
	} else {
		$data2['selected'] = false;
	}
	$result2 = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id = '".$data2['forum_id']."' LIMIT 1");
	$data2['defined'] = dbrows($result2);
	$variables['forums'][] = $data2;
}

// get the info for the edit settings panel
if ($edit_forum != "") {

	// get the name of the forum
	if ($edit_forum == "0") {
		$variables['forum_name'] = $locale['FPM_022'];
	} else {
		$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id = '$forum_id'");
		if ($forum = dbarray($result)) {
			$variables['forum_name'] = $forum['forum_name'];
		} else {
			fallback(BASEDIR."index.php");
		}
	}

	// get the poll settings for this forum. If they don't exist, get the defaults. If the don't exist, make some up!
	$result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id = '$forum_id'");
	if ($data = dbarray($result)) {
		// found
		$data['duration_min'] = floor($data['duration_min']/86400);
		$data['duration_max'] = floor($data['duration_max']/86400);
	} else {
		// not found, get the globals
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id = '0'");
		if ($data = dbarray($result)) {
			// found
			$data['duration_min'] = floor($data['duration_min']/86400);
			$data['duration_max'] = floor($data['duration_max']/86400);
		} else {
			// not found either. something's wrong...
			fallback(BASEDIR."index.php");
		}
	}
	// make sure the record has the correct forum_id
	$data['forum_id'] = $forum_id;
	// store the record for use on the template
	$variables['settings'] = $data;

	// create the group and user arrays
	$fp_group_array = array(
		array(103, $locale['user3']),
		array(102, $locale['user2']),
		array(101, $locale['user1'])
	);
	$result = dbquery("SELECT * FROM ".$db_prefix."user_groups ORDER BY group_name");
	if (dbrows($result) != 0) {
		while ($fp_group_data = dbarray($result)) {
			$fp_group_array[] = array($fp_group_data['group_id'], $fp_group_data['group_name']);
		}
	}
	$variables['create_group_1'] = array(); 
	$variables['create_group_2'] = array();
	while(list($key, $fp_group) = each($fp_group_array)){
		$group_id = $fp_group['0'];
		if (!preg_match("(^G{$group_id}$|^G{$group_id}\.|\.G{$group_id}\.|\.G{$group_id}$)", $data['create_permissions'])) {
			$variables['create_group_1'][] = array($fp_group['0'], $fp_group['1']);
		} else { $variables['create_group_2'][] = array($fp_group['0'], $fp_group['1']); }
		unset($group_id);
	}
	unset($fp_group);

	reset($fp_group_array);
	$variables['vote_group_1'] = array();
	$variables['vote_group_2'] = array();
	while(list($key, $fp_group) = each($fp_group_array)){
		$group_id = $fp_group['0'];
		if (!preg_match("(^G{$group_id}$|^G{$group_id}\.|\.G{$group_id}\.|\.G{$group_id}$)", $data['vote_permissions'])) {
			$variables['vote_group_1'][] = array($fp_group['0'], $fp_group['1']);
		} else { $variables['vote_group_2'][] = array($fp_group['0'], $fp_group['1']); }
		unset($group_id);
	}

	$variables['create_user_1'] = array();
	$variables['create_user_2'] = array();
	$result = dbquery("SELECT user_id,user_name FROM ".$db_prefix."users ORDER BY user_level DESC, user_name");
	while ($fp_user_data = dbarray($result)) {
		$fp_user_array[] = $fp_user_data;
		$user_id = $fp_user_data['user_id'];
		if (!preg_match("(^{$user_id}$|^{$user_id}\.|\.{$user_id}\.|\.{$user_id}$)", $data['create_permissions'])) {
			$variables['create_user_1'][] = array($fp_user_data['user_id'], $fp_user_data['user_name']);
		} else { $variables['create_user_2'][] = array($fp_user_data['user_id'], $fp_user_data['user_name']); }
		unset($user_id);
	}
	unset($fp_user_data);

	$variables['vote_user_1'] = array();
	$variables['vote_user_2'] = array();
	while(list($key, $fp_user_data) = each($fp_user_array)){
		$user_id = $fp_user_data['user_id'];
		if (!preg_match("(^{$user_id}$|^{$user_id}\.|\.{$user_id}\.|\.{$user_id}$)", $data['vote_permissions'])) {
			$variables['vote_user_1'][] = array($fp_user_data['user_id'], $fp_user_data['user_name']);
		} else { $variables['vote_user_2'][] = array($fp_user_data['user_id'], $fp_user_data['user_name']); }
		unset($user_id);
	}
	if ($forum_id != 0 && count($variables['create_group_2']) == 0) {
		$variables['create_group_2'][] = array($forum['forum_posting'], getgroupname($forum['forum_posting']));
	}
	if ($forum_id != 0 && count($variables['vote_group_2']) == 0) {
		$variables['vote_group_2'][] = array($forum['forum_posting'], getgroupname($forum['forum_posting']));
	}
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.forum_polls', 'template' => 'admin.forum_polls.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/forum_polls.php");
$template_variables['admin.forum_polls'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>