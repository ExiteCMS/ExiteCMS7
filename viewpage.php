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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("main.custom_pages");

// temp storage for template variables
$variables = array();

// parameter validation
if (!isset($page_id) || !isNum($page_id)) fallback("index.php");

// check if the requested page exists
$result = dbquery("SELECT * FROM ".$db_prefix."custom_pages WHERE page_id='$page_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$title = $data['page_title'];
	if (checkgroup($data['page_access'])) {
		$custompage = stripslashes($data['page_content']);
		$content = true;
	} else {
		$custompage = $locale['400'];
		$content = false;
	}
} else {
	$title = $locale['401'];
	$custompage = $locale['402'];
	$content = false;
}

$variables['content'] = $content;
$variables['custompage'] = $custompage;

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'viewpage', 'title' => $title, 'template' => 'main.viewpage.tpl', 'locale' => "main.custom_pages");
$template_variables['viewpage'] = $variables;

if (dbrows($result) && checkgroup($data['page_access'])) {
	// check if we need to display comments
	if ($data['page_allow_comments']) {
		include PATH_INCLUDES."comments_include.php";
		showcomments("C","custom_pages","page_id",$page_id,FUSION_SELF."?page_id=$page_id");
	}
	
	// check if we need to display ratings
	if ($data['page_allow_ratings']) {
		include PATH_INCLUDES."ratings_include.php";
		showratings("C",$page_id,FUSION_SELF."?page_id=$page_id");	
	}
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
