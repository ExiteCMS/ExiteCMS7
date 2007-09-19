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
include PATH_LOCALE.LOCALESET."admin/forums.php";

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
	$template_panels[] = array('type' => 'body', 'name' => 'admin.forums.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/forums.php");
	$template_variables['admin.forums.status'] = $variables;
	$variables = array();
}	

if (isset($_POST['save_cat'])) {
	$cat_name = stripinput($_POST['cat_name']);
	if ($action == "edit" && $t == "cat") {
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_name='$cat_name' WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=savece");
	} else {
		if ($cat_name != "") {
			$cat_order = isNum($_POST['cat_order']) ? $_POST['cat_order'] : "";
			if(!$cat_order) $cat_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$db_prefix."forums WHERE forum_cat='0'"),0)+1;
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
	if ($action == "edit" && $t == "forum") {
		// check if the forum access group has changed.
		$result = dbquery("SELECT forum_access FROM ".$db_prefix."forums WHERE forum_id = '".$forum_id."'");
		$data = dbarray($result);
		$group_id = $data['forum_access'];
		// if the forum access is changed, remove the unread posts flag from users that don't have access anymore
		if ($group_id <> $forum_access && $forum_access != 0) {
			// get all users with unread posts in this forum
			$result = dbquery("SELECT DISTINCT user_id FROM ".$db_prefix."posts_unread WHERE forum_id = '".$forum_id."'");
			// for every user, check if the user is member of the new group. If not, remove the unread markers
			while ($data = dbarray($result)) {
				// get the users group memberships
				$result2 = dbquery("SELECT user_groups FROM ".$db_prefix."users WHERE user_id = '".$data['user_id']."'");
				if ($data2 = dbarray($result2)) {
					$groups = (strpos($data2['user_groups'], ".") == 0 ? explode(".", substr($data2['user_groups'], 1)) : explode(".", $data2['user_groups']));
					foreach ($groups as $group) {
						// check if this groups has subgroups. If so, add them to the array
						getsubgroups($group);
					}
					// check if the user is member of the new group
					if (!in_array($forum_access, $groups)) 
						// if not, delete the unread markers for this user and forum
						$result2 = dbquery("DELETE FROM ".$db_prefix."posts_unread WHERE forum_id = '".$forum_id."' AND user_id = '".$data['user_id']."'");
				}
			}
		}
		// update the forum record
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_name='$forum_name', forum_cat='$forum_cat', forum_description='$forum_description', forum_access='$forum_access', forum_posting='$forum_posting', forum_modgroup='$forum_modgroup', forum_attach='$forum_attach', forum_attachtypes='$forum_attachtypes', forum_rulespage='$forum_rulespage', forum_banners='$forum_banners' WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=savefe");
	} else {
		if ($forum_name != "") {
			$forum_mods = "";
			$forum_order = isNum($_POST['forum_order']) ? $_POST['forum_order'] : "";
			if(!$forum_order) $forum_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$db_prefix."forums WHERE forum_cat='$forum_cat'"),0)+1;
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order'");	
			$result = dbquery("INSERT INTO ".$db_prefix."forums (forum_cat, forum_name, forum_order, forum_description, forum_moderators, forum_access, forum_posting, forum_modgroup, forum_attach, forum_attachtypes, forum_rulespage, forum_banners, forum_lastpost, forum_lastuser) VALUES ('$forum_cat', '$forum_name', '$forum_order', '$forum_description', '$forum_mods', '$forum_access', '$forum_posting', '$forum_modgroup', '$forum_attach', '$forum_attachtypes', '$forum_rulespage', '$forum_banners', '0', '0')");
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
				$ug = array('id' => $user_group['0'], 'name' => '');
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

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.forums', 'template' => 'admin.forums.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/forums.php");
$template_variables['admin.forums'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>