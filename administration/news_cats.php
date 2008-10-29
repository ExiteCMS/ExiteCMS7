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
locale_load("admin.news-articles");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkRights("NC") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid
if (isset($cat_id) && !isNum($cat_id)) fallback("index.php");

if (isset($action) && $action == "delete") {
	$result = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_cat='$cat_id'");
	if (dbrows($result) != 0) {
		opentable($locale['430']);
		$variables['message'] = $locale['431']."<br /><span class='small'>".$locale['432']."</span>";
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."news_cats WHERE news_cat_id='$cat_id'");
		$variables['message'] = $locale['433'];
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.news_cats.delete', 'title' => $locale['430'], 'template' => '_message_table_panel.tpl', 'locale' => "admin.news-articles");
	$template_variables['admin.news_cats.delete'] = $variables;
	$variables = array();
}

if (isset($_POST['save_cat'])) {
	$cat_name = stripinput($_POST['cat_name']);
	$cat_image = stripinput($_POST['cat_image']);
	if ($cat_name != "" && $cat_image != "") {
		if ($action == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."news_cats SET news_cat_name='$cat_name', news_cat_image='$cat_image' WHERE news_cat_id='$cat_id'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('$cat_name', '$cat_image')");
		}
	}
	redirect(FUSION_SELF.$aidlink);
}

if (isset($action) && $action == "edit") {
	$result = dbquery("SELECT * FROM ".$db_prefix."news_cats WHERE news_cat_id='$cat_id'");
	$data = dbarray($result);
	$cat_name = $data['news_cat_name'];
	$cat_image = $data['news_cat_image'];
	$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['news_cat_id'];
	$title = $locale['434'];

} else {
	$cat_name = "";
	$cat_image = "";
	$formaction = FUSION_SELF.$aidlink;
	$title = $locale['435'];
}
$variables['image_list'] = makefilelist(PATH_IMAGES_NC, ".|..|index.php", true);
$variables['formaction'] = $formaction;
$variables['cat_name'] = $cat_name;
$variables['cat_image'] = $cat_image;

$result = dbquery("SELECT * FROM ".$db_prefix."news_cats ORDER BY news_cat_name");
$variables['cats'] = array();
while ($data = dbarray($result)) {
	$data['image_exists'] = file_exists(PATH_IMAGES_NC.$data['news_cat_image']);
	$variables['cats'][] = $data;
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.news_cats', 'title' => $title, 'template' => 'admin.news_cats.tpl', 'locale' => "admin.news-articles");
$template_variables['admin.news_cats'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
