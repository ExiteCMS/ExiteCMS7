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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("admin.news-articles");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkRights("AC") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");

// make sure the parameter is valid
if (isset($cat_id) && !isNum($cat_id)) fallback("index.php");

if (isset($status)) {
	if ($status == "deln") {
		$title = $locale['450'];
		$variables['message'] = $locale['451']."<br />".$locale['452'];
	} elseif ($status == "dely") {
		$title = $locale['450'];
		$variables['message'] = $locale['454'];
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.article_cats.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => "admin.news-articles");
	$template_variables['admin.article_cats.status'] = $variables;
	$variables = array();
}

if (isset($action) && $action == "delete") {
	$result = dbquery("SELECT * FROM ".$db_prefix."articles WHERE article_cat='$cat_id'");
	if (dbrows($result) != 0) {
		redirect(FUSION_SELF.$aidlink."&status=deln");
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."article_cats WHERE article_cat_id='$cat_id'");
		redirect(FUSION_SELF.$aidlink."&status=dely");
	}
} else {
	if (isset($_POST['save_cat'])) {
		$cat_name = stripinput($_POST['cat_name']);
		$cat_description = stripinput($_POST['cat_description']);
		$cat_access = isNum($_POST['cat_access']) ? $_POST['cat_access'] : "0";		
		if (isNum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "1") {
			$cat_sorting = "article_id ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
		} else if (isNum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "2") {
			$cat_sorting = "article_subject ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
		} else if (isNum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "3") {
			$cat_sorting = "article_datestamp ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
		} else {
			$cat_sorting = "article_subject ASC";
		}
		if ($action == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."article_cats SET article_cat_name='$cat_name', article_cat_description='$cat_description', article_cat_sorting='$cat_sorting', article_cat_access='$cat_access' WHERE article_cat_id='$cat_id'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."article_cats (article_cat_name, article_cat_description, article_cat_sorting, article_cat_access) VALUES ('$cat_name', '$cat_description', '$cat_sorting', '$cat_access')");
		}
		redirect("article_cats.php?aid=".iAUTH);
	}
	if (isset($action) && $action == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."article_cats WHERE article_cat_id='$cat_id'");
		$data = dbarray($result);
		$cat_name = $data['article_cat_name'];
		$cat_description = $data['article_cat_description'];
		$cat_sorting = explode(" ", $data['article_cat_sorting']);
		if ($cat_sorting[0] == "article_id") { $cat_sort_by = "1"; }
		if ($cat_sorting[0] == "article_subject") { $cat_sort_by = "2"; }
		if ($cat_sorting[0] == "article_datestamp") { $cat_sort_by = "3"; }
		$cat_sort_order = $cat_sorting[1];
		$cat_access = $data['article_cat_access'];
		$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['article_cat_id'];
		$title = $locale['455'];
	} else {
		$cat_name = "";
		$cat_description = "";
		$cat_sort_by = "2";
		$cat_sort_order = "ASC";
		$cat_access = "";
		$formaction = FUSION_SELF.$aidlink;
		$title = $locale['456'];
	}
	// get the list of available user groups
	$variables['user_groups'] = array();
	$user_groups = getusergroups();
	while(list($key, $user_group) = each($user_groups)){
		$variables['user_groups'][] = array('id' => $user_group['0'], 'name' => $user_group['1'], 'selected' => $cat_access == $user_group['0']);
	}

	// get the list of defined article categories
	$variables['articles'] = array(); 
	$result = dbquery("SELECT * FROM ".$db_prefix."article_cats ORDER BY article_cat_name");
	while ($data = dbarray($result)) {
		$data['access_group'] = getgroupname($data['article_cat_access'], -1);
		$variables['articles'][] = $data;
	}

	// store the variables for use in the template
	$variables['formaction'] = $formaction;
	$variables['cat_name'] = $cat_name;
	$variables['cat_description'] = $cat_description;
	$variables['cat_sort_by'] = $cat_sort_by; 
	$variables['cat_sort_order'] = $cat_sort_order;
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.article_cats', 'title' => $title, 'template' => 'admin.article_cats.tpl', 'locale' => "admin.news-articles");
$template_variables['admin.article_cats'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>