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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locales for this module
locale_load("admin.forums");
locale_load("admin.forum_polls");

// temp storage for template variables
$variables = array();

//check if the user has a right to be here. If not, bail out
if (!checkrights("F") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// verify the parameters
if (isset($forum_id)) {
	if (!isNum($forum_id)) fallback(FUSION_SELF);
	$variables['forum_id'] = $forum_id;
}

// initialise some variables we need later
if (!isset($action)) $action = "";
if (!isset($t)) $t = "";

// create the forum polls default global settings if not defined
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

// refresh the forum order
if ($action == "refresh") {
	$i = 1; $k = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."forums SET forum_order='$i' WHERE forum_id='".$data['forum_id']."'");
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
		while ($data2 = dbarray($result2)) {
			$result3 = dbquery("UPDATE ".$db_prefix."forums SET forum_order='$k' WHERE forum_id='".$data2['forum_id']."'");
			$k++;
		}
		$i++; $k = 1;
	}
	redirect(FUSION_SELF.$aidlink);
}

// status messages
if (isset($status)) {
	if ($status == "savece") {
		$title = $locale['400'];
		$variables['message'] = $locale['401'];
	} elseif ($status == "saveer") {
		$title = $locale['406'];
		$variables['message'] = $locale['472'];
	} elseif ($status == "savecu") {
		$title = $locale['402'];
		$variables['message'] = $locale['403'];
	} elseif ($status == "savefe") {
		$title = $locale['404'];
		$variables['message'] = $locale['405'];
	} elseif ($status == "savefu") {
		$title = $locale['406'];
		$variables['message'] = $locale['407'];
	} elseif ($status == "savefm") {
		$title = $locale['408'];
		$variables['message'] = $locale['409'];
	} elseif ($status == "delc1") {
		$title = $locale['410'];
		$variables['message'] = $locale['411'];
	} elseif ($status == "delc2") {
		$title = $locale['410'];
		$variables['message'] = $locale['412']."<br />".$locale['413'];
	} elseif ($status == "delf1") {
		$title = $locale['414'];
		$variables['message'] = $locale['415'];
	} elseif ($status == "delf2") {
		$title = $locale['414'];
		$variables['message'] = $locale['416']."<br />".$locale['417'];
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.forums.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => "admin.forums");
	$template_variables['admin.forums.status'] = $variables;
	$variables = array();
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
	unset($forum_id);
} elseif (isset($_POST['reset'])) {
	// delete the record (force to use globals again)
	$result = dbquery("DELETE FROM ".$db_prefix."forum_poll_settings WHERE forum_id = '$forum_id'");
}

if (isset($_POST['save_cat'])) {
	$cat_name = stripinput($_POST['cat_name']);
	if ($action == "edit" && $t == "cat") {
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_name='$cat_name' WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=savece");
	} else {
		if ($cat_name != "") {
			$cat_order = isNum($_POST['cat_order']) ? $_POST['cat_order'] : "";
			if(!$cat_order) $cat_order=dbfunction("MAX(forum_order)", "forums", "forum_cat='0'")+1;
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='0' AND forum_order>='$cat_order'");
			$result = dbquery("INSERT INTO ".$db_prefix."forums (forum_cat, forum_name, forum_order, forum_description, forum_moderators, forum_access, forum_posting, forum_lastpost, forum_lastuser) VALUES ('0', '$cat_name', '$cat_order', '', '', '0', '0', '0', '0')");
		}
		redirect(FUSION_SELF.$aidlink."&status=savecu");
	}
} elseif (isset($_POST['save_forum'])) {
	$forum_name = stripinput($_POST['forum_name']);
	$forum_description = stripinput($_POST['forum_description']);
	$forum_cat = isNum($_POST['forum_cat']) ? $_POST['forum_cat'] : "";
	$forum_access = $_POST['forum_access'];
	$forum_posting = $_POST['forum_posting'];
	$forum_modgroup = $_POST['forum_modgroup'];
	$forum_rulespage = $_POST['forum_rulespage'];
	$forum_banners = $_POST['forum_banners'];
	$forum_attach = isset($_POST['forum_attach'])?"1":"0";
	$forum_attachtypes = stripinput($_POST['forum_attachtypes']);
	$forum_prefixes = stripinput($_POST['forum_prefixes']);
	if ($action == "edit" && $t == "forum") {
		// check if the forum access group has changed.
		$result = dbquery("SELECT forum_access FROM ".$db_prefix."forums WHERE forum_id = '".$forum_id."'");
		$data = dbarray($result);
		$group_id = $data['forum_access'];
		// if the forum access is changed, and it's not public, correct the threads_read pointers
		if ($group_id <> $forum_access && $forum_access != 0) {
			// remove users from threads_read that don't have read access to the forum anymore!
			$result = dbquery("
				SELECT DISTINCT u.user_id, u.user_level, u.user_groups
				FROM ".$db_prefix."threads_read tr
				INNER JOIN ".$db_prefix."users u ON u.user_id = tr.user_id
				WHERE tr.thread_id = '".$thread_id."'
				");
			while ($udata2 = dbarray($result)) {
				if (($forum_access > 100 and $udata2['user_level'] >= $forum_access) or preg_match("(^\.{$forum_access}|\.{$forum_access}\.|\.{$forum_access}$)", $udata2['user_groups'])) {
					// ok, user can still access this thread
				} else {
					// user doesn't have access to the new forum. Remove the thread for this user from threads_read
					$result2 = dbquery("DELETE FROM ".$db_prefix."threads_read WHERE user_id = '".$udata['user_id']."' AND thread_id = '".$thread_id."'");
				}
			}
		}
		// update the forum record
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_name='".mysql_real_escape_string($forum_name)."', forum_cat='$forum_cat', forum_description='".mysql_real_escape_string($forum_description)."', forum_access='$forum_access', forum_posting='$forum_posting', forum_modgroup='$forum_modgroup', forum_attach='$forum_attach', forum_attachtypes='$forum_attachtypes', forum_prefixes='".mysql_real_escape_string($forum_prefixes)."', forum_rulespage='$forum_rulespage', forum_banners='$forum_banners' WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=savefe");
	} else {
		if ($forum_name != "") {
			$forum_mods = "";
			$forum_order = isNum($_POST['forum_order']) ? $_POST['forum_order'] : "";
			if(!$forum_order) $forum_order=dbfunction("MAX(forum_order)", "forums", "forum_cat='$forum_cat'")+1;
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order'");
			$result = dbquery("INSERT INTO ".$db_prefix."forums (forum_cat, forum_name, forum_order, forum_description, forum_moderators, forum_access, forum_posting, forum_modgroup, forum_attach, forum_attachtypes, forum_prefixes, forum_rulespage, forum_banners, forum_lastpost, forum_lastuser) VALUES ('$forum_cat', '$forum_name', '$forum_order', '$forum_description', '$forum_mods', '$forum_access', '$forum_posting', '$forum_modgroup', '$forum_attach', '$forum_attachtypes', '".mysql_real_escape_string($forum_prefixes)."', '$forum_rulespage', '$forum_banners', '0', '0')");
			redirect(FUSION_SELF.$aidlink."&status=savefu");
		}
		redirect(FUSION_SELF.$aidlink."&status=saveer");
	}
} elseif (isset($_POST['save_forum_mods'])) {
	$forum_mods = $_POST['forum_mods'];
	$result = dbquery("UPDATE ".$db_prefix."forums SET forum_moderators='$forum_mods' WHERE forum_id='".$_POST['forum_id']."'");
	redirect(FUSION_SELF.$aidlink."&status=savefm");
} elseif ($action == "moveup") {
	if ($t == "cat") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' AND forum_order='$order'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_id='$forum_id'");
	} elseif ($t == "forum") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='$cat' AND forum_order='$order'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_id='$forum_id'");
	}
	redirect(FUSION_SELF.$aidlink);
} elseif ($action == "movedown") {
	if ($t == "cat") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' AND forum_order='$order'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_id='$forum_id'");
	} elseif ($t == "forum") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='$cat' AND forum_order='$order'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_id='$forum_id'");
	}
	redirect(FUSION_SELF.$aidlink);
} elseif ($action == "delete" && $t == "cat") {
	if (dbcount("(*)", "forums", "forum_cat='$forum_id'") == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='0' AND forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=delc1");
	} else {
		redirect(FUSION_SELF.$aidlink."&status=delc2");
	}
} elseif ($action == "delete" && $t == "forum") {
	if (dbcount("(*)", "posts", "forum_id='$forum_id'") == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='".$data['forum_cat']."' AND forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=delf1");
	} else {
		redirect(FUSION_SELF.$aidlink."&status=delf2");
	}
} else {
	if ($action == "edit") {
		if ($t == "cat") {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$cat_name = $data['forum_name'];
			$cat_title = $locale['420'];
			$cat_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data['forum_id']."&amp;t=cat";
			$cat_order = "";
			$forum_title = $locale['421'];
			$forum_action = FUSION_SELF.$aidlink;
		} elseif ($t == "forum") {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$forum_name = $data['forum_name'];
			$forum_description = $data['forum_description'];
			$forum_cat = $data['forum_cat'];
			$forum_access = $data['forum_access'];
			$forum_posting = $data['forum_posting'];
			$forum_modgroup = $data['forum_modgroup'];
			$forum_rulespage = $data['forum_rulespage'];
			$forum_banners = $data['forum_banners'];
			$forum_attach = $data['forum_attach']?true:false;
			$forum_attachtypes = $data['forum_attachtypes'];
			$forum_prefixes = $data['forum_prefixes'];
			$forum_title = $locale['422'];
			$forum_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data['forum_id']."&amp;t=forum";
			$forum_order = "";
			$cat_title = $locale['423'];
			$cat_action = FUSION_SELF.$aidlink;
			$cat_order = "";
			$cat_name = "";
		}
	} else {
		$cat_name = "";
		$cat_order = "";
		$cat_title = $locale['423'];
		$cat_action = FUSION_SELF.$aidlink;
		$forum_name = "";
		$forum_description = "";
		$forum_cat = "";
		$forum_order = "";
		$forum_access = "";
		$forum_posting = "";
		$forum_modgroup = "";
		$forum_rulespage = 0;
		$forum_banners = 1;
		$forum_attach = ($settings['attachments'] == 1);
		$forum_attachtypes = $settings['attachtypes'];
		$forum_prefixes = "";
		$forum_title = $locale['421'];
		$forum_action = FUSION_SELF.$aidlink;
	}
	$variables['show_category_panel'] = ($t != "forum");	// either blank or "cat"
	$variables['show_forum_panel'] = ($t != "cat");			// either blank or "forum"
	$variables['edit_forum_panel'] = ($action == "edit" && $t == "forum");
	$variables['action'] = $action;
	$variables['cat_title'] = $cat_title;
	$variables['cat_action'] = $cat_action;
	$variables['cat_name'] = $cat_name;
	$variables['cat_order'] = $cat_order;

	// forum panel processing
	if ($variables['show_forum_panel']) {
		// forum category list
		$variables['cat_opts'] = array();
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
		// disable the forum add/edit panel if no categories are present
		if (dbrows($result2) == 0) $variables['show_forum_panel'] = false;
		while ($data2 = dbarray($result2)) {
			$data2['selected'] = ($action == "edit" && $t == "forum" && $data2['forum_id'] == $forum_cat);
			$variables['cat_opts'][] = $data2;
		}
		// access group list
		$user_groups = getusergroups();
		$variables['access_opts'] = array();
		while(list($key, $user_group) = each($user_groups)){
			if ($user_group['0'] == 100) continue; // skip 'anonymous'
			$ug = array('id' => $user_group['0'], 'name' => $user_group['1']);
			$ug['selected'] = ($forum_access == $user_group['0']);
			$variables['access_opts'][] = $ug;
		}
		// post group list
		$variables['posting_opts'] = array();
		reset($user_groups);
		while(list($key, $user_group) = each($user_groups)){
			if ($user_group['0'] == 100) continue; // skip 'anonymous'
			$ug = array('id' => $user_group['0'], 'name' => $user_group['1']);
			$ug['selected'] = ($forum_posting == $user_group['0']);
			$variables['posting_opts'][] = $ug;
		}
		// moderator group list
		$variables['modgroup_opts'] = array();
		reset($user_groups);
		while(list($key, $user_group) = each($user_groups)){
			if (isset($ug)) unset($ug);
			if ($user_group['0'] == "0") {
				$ug = array('id' => $user_group['0'], 'name' => '&nbsp;');
			} else {
				if ($user_group['0'] < 100) {
					$ug = array('id' => $user_group['0'], 'name' => $user_group['1']);
				}
			}
			if (isset($ug) && is_array($ug)) {
				$ug['selected'] = ($forum_modgroup == $user_group['0']);
				$variables['modgroup_opts'][] = $ug;
			}
		}
		// forum rules custom pages
		$variables['rulespages'] = array();
		$variables['rulespages'][] = array('page_id' => 0, 'page_title' => $locale['474'], 'selected' => ($forum_rulespage==0));
		$result2 = dbquery("SELECT * FROM ".$db_prefix."custom_pages WHERE page_id > '0' ORDER BY page_title");
		while ($data2 = dbarray($result2)) {
			$data2['selected'] = ($forum_rulespage==$data2['page_id']);
			$variables['rulespages'][] = $data2;
		}
		$variables['forum_title'] = $forum_title;
		$variables['forum_action'] = $forum_action;
		$variables['forum_name'] = $forum_name;
		$variables['forum_description'] = $forum_description;
		$variables['forum_order'] = $forum_order;
		$variables['forum_attach'] = $forum_attach;
		$variables['forum_attachtypes'] = $forum_attachtypes;
		$variables['forum_prefixes'] = $forum_prefixes;
		$variables['forum_rulespage'] = $forum_rulespage;
		$variables['forum_banners'] = $forum_banners;

		if ($variables['edit_forum_panel']) {
			// get the lists of moderators/non-moderators
			$variables['mods1'] = array();
			$variables['mods2'] = array();
			$result = dbquery("SELECT user_id,user_name FROM ".$db_prefix."users ORDER BY user_level DESC, user_name");
			while ($data2 = dbarray($result)) {
				$user_id = $data2['user_id'];
 				if (!preg_match("(^{$user_id}$|^{$user_id}\.|\.{$user_id}\.|\.{$user_id}$)", $data['forum_moderators'])) {
					$variables['mods1'][] = $data2;
				} else {
					$variables['mods2'][] = $data2;
				}
				unset($user_id);
			}
		}
	}
	// main forum list panel
	$variables['forums'] = array();
	$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
	$variables['cat_count'] = dbrows($result);
	while ($data = dbarray($result)) {
		// get all forums in this category
		$forumlist = array();
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
		$data['forum_count'] = dbrows($result2);
		while ($data2 = dbarray($result2)) {
			$data2['forum_access_name'] = getgroupname($data2['forum_access'], '-1');
			$data2['forum_posting_name'] = getgroupname($data2['forum_posting'], '-1');
			$forumlist[] = $data2;
		}
		$data['subforums'] = $forumlist;
		$variables['forums'][] = $data;
	}
}

// get the info for the edit settings panel
if (isset($forum_id)) {

	// get the name of the forum
	$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id = '$forum_id'");
	if ($forum = dbarray($result)) {
		$variables['forum_name'] = $forum['forum_name'];
	} else {
		fallback(BASEDIR."index.php");
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
$template_panels[] = array('type' => 'body', 'name' => 'admin.forums', 'template' => 'admin.forums.tpl', 'locale' => array("admin.forums","admin.forum_polls"));
$template_variables['admin.forums'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
