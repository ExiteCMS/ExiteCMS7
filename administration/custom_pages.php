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

// load the locale for this module
locale_load("admin.custom_pages");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("CP") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid
if (isset($page_id) && !isNum($page_id)) fallback("index.php");

// load the TinyMCE editor code
define('LOAD_TINYMCE', true);

if (isset($status)) {
	if ($status == "su") {
		$title = $locale['400'];
		$variables['message'] = $locale['401']."<br />".$locale['402']."<a href='".BASEDIR."viewpage.php?page_id=$pid'>viewpage.php?page_id=$pid</a>";
	} elseif ($status == "sn") {
		$title = $locale['405'];
		$variables['message'] = $locale['406']."<br />".$locale['402']."<a href='".BASEDIR."viewpage.php?page_id=$pid'>viewpage.php?page_id=$pid</a>";
	} elseif ($status == "del") {
		$title = $locale['407'];
		$variables['message'] = $locale['408'];
	} elseif ($status == "err") {
		$title = $locale['407'];
		$variables['message'] = $locale['411'];
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.custom_pages.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => "admin.custom_pages");
	$template_variables['admin.custom_pages.status'] = $variables;
	$variables = array();
}

if (isset($_POST['save'])) {
	$page_title = stripinput($_POST['page_title']);
	$page_access = isNum($_POST['page_access']) ? $_POST['page_access'] : "0";
	$page_content = addslash($_POST['page_content']);
	$comments = isset($_POST['page_comments']) ? "1" : "0";
	$ratings = isset($_POST['page_ratings']) ? "1" : "0";
	if (isset($page_id)) {
		$result = dbquery("UPDATE ".$db_prefix."custom_pages SET page_title='$page_title', page_access='$page_access', page_content='$page_content', page_allow_comments='$comments', page_allow_ratings='$ratings' WHERE page_id='$page_id'");
		redirect(FUSION_SELF.$aidlink."&status=su&pid=$page_id");
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."custom_pages (page_title, page_access, page_content, page_allow_comments, page_allow_ratings) VALUES ('$page_title', '$page_access', '$page_content', '$comments', '$ratings')");
		$page_id = mysql_insert_id();
		if (isset($_POST['add_link'])) {
			$result = dbquery("SELECT * FROM ".$db_prefix."site_links ORDER BY link_order DESC LIMIT 1");
			$data = dbarray($result);
			$link_order = $data['link_order'] + 1;
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('$page_title', 'viewpage.php?page_id=$page_id', '$page_access', '1', '0', '$link_order')");
		}
		redirect(FUSION_SELF.$aidlink."&status=sn&pid=$page_id");
	}
} else if (isset($_POST['delete'])) {
	if ($page_id) {
		$result = dbquery("DELETE FROM ".$db_prefix."custom_pages WHERE page_id='$page_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."site_links WHERE link_url='viewpage.php?page_id=$page_id'");
		redirect(FUSION_SELF.$aidlink."&status=del");
	} else {
		redirect(FUSION_SELF.$aidlink."&status=err");
	}
} else {
	if (isset($_POST['preview'])) {
		$addlink = isset($_POST['add_link']) ? " checked" : "";
		$page_title = stripinput($_POST['page_title']);
		$page_access = $_POST['page_access'];
		$page_content = $_POST['page_content'];
		$page_content = stripslash($page_content);
		$comments = isset($_POST['page_comments']) ? " checked" : "";
		$ratings = (isset($_POST['page_ratings']) && $_POST['page_ratings']) ? " checked" : "";

		$variables['message'] = $page_content;
		$template_panels[] = array('type' => 'body', 'name' => 'admin.custom_pages.preview', 'title' => $page_title, 'template' => '_message_table_panel.simple.tpl', 'locale' => "admin.custom_pages");
		$template_variables['admin.custom_pages.preview'] = $variables;
		//$page_content = stripinput((QUOTES_GPC ? addslashes($page_content) : $page_content));
		$page_content = phpentities($page_content);
	}
	$variables['pages'] = array();
	$result = dbquery("SELECT * FROM ".$db_prefix."custom_pages ORDER BY page_title");
	while ($data = dbarray($result)) {
		$data['selected'] = (isset($page_id) && $page_id == $data['page_id']);
		$variables['pages'][] = $data;
	}	
	if (isset($_POST['edit'])) {
		$result = dbquery("SELECT * FROM ".$db_prefix."custom_pages WHERE page_id='$page_id'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$page_title = $data['page_title'];
			$page_access = $data['page_access'];
			//$page_content = stripinput((QUOTES_GPC ? $data['page_content'] : stripslashes($data['page_content'])));
			$page_content = phpentities(stripslashes($data['page_content']));
			$comments = ($data['page_allow_comments'] == "1" ? " checked" : "");
			$ratings = ($data['page_allow_ratings'] == "1" ? " checked" : "");
			$addlink = "";
		}
	}
	if (isset($page_id)) {
		$action = FUSION_SELF.$aidlink."&amp;page_id=$page_id";
		$title = $locale['400'];
	} else {
		if (!isset($_POST['preview'])) {
			$page_title = "";
			$page_access = "";
			$page_content = "";
			$comments = " checked";
			$ratings = " checked";
			$addlink = "";
		}
		$action = FUSION_SELF.$aidlink;
		$title = $locale['405'];
	}
	// get the list of available user groups
	$variables['user_groups'] = array();
	$user_groups = getusergroups();
	while(list($key, $user_group) = each($user_groups)){
		$variables['user_groups'][] = array('id' => $user_group['0'], 'name' => $user_group['1'], 'selected' => $page_access == $user_group['0']);
	}
	$variables['title'] = $title;
	$variables['action'] = $action;
	$variables['page_title'] = $page_title;
	$variables['page_content'] = $page_content;
	$variables['img_src'] = str_replace("../","",IMAGES);
	$variables['new_page'] = !isset($page_id);
	$variables['comments'] = $comments;
	$variables['ratings'] = $ratings;
	$variables['addlink'] = $addlink;

	// define the admin body panel
	$template_panels[] = array('type' => 'body', 'name' => 'admin.custom_pages', 'template' => 'admin.custom_pages.tpl', 'locale' => "admin.custom_pages");
	$template_variables['admin.custom_pages'] = $variables;
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
