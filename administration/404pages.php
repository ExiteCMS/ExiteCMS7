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
locale_load("admin.404pages");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("NF") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// validate the locale_code variable
$lc = "";
if (isset($_POST['lc']) || isset($_GET['lc'])) {
	// get the locale code
	$lc = stripinput((isset($_POST['lc']) ? $_POST['lc'] : $_GET['lc']));
	// validate it
	$result = dbquery("SELECT c.locale_name, l.* from ".$db_prefix."locales l, ".$db_prefix."locale c WHERE c.locale_code = '".$lc."' AND c.locale_active = 1 AND l.locales_code = c.locale_code AND l.locales_name = '404page' AND l.locales_key = '404page'");
	if (dbrows($result) == 0) {
		// if not found, reset the locale_code variable
		$lc = "";
	} else {
		// load the page
		$data = dbarray($result);
	}
}
$variables['lc'] = $lc;

// any status message to display?
if (isset($status)) {
	switch ($status) {
		case "su":
			$title = $locale['400'];
			$variables['message'] = sprintf($locale['401'], $data['locale_name']);
			break;
		case "er":
			$title = $locale['400'];
			$variables['message'] = $locale['402'];
			break;
		default:
			$title = $locale['400'];
			$variables['message'] = "Error. Unknown status code passed";
			break;
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.404pages.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => "admin.404pages");
	$template_variables['admin.404pages.status'] = $variables;
	// reset the selected locale code, to return to the selection form
	$variables['lc'] = "";
}

// edit requested?
if (isset($_POST['edit'])) {
	$variables['page_content'] = phpentities(stripslashes($data['locales_value']));
	// load the TinyMCE editor code
	define('LOAD_TINYMCE', true);
}

// preview requested?
if (isset($_POST['preview'])) {
	$page_content = stripslash($_POST['page_content']);
	$variables['preview'] = $page_content;
	//$page_content = stripinput((QUOTES_GPC ? addslashes($page_content) : $page_content));
	$variables['page_content'] = phpentities($page_content);
	define('LOAD_TINYMCE', true);
}

// save requested?
if (isset($_POST['save'])) {
	$page_content = addslash($_POST['page_content']);
	if (!empty($lc)) {
		$result = dbquery("UPDATE ".$db_prefix."locales SET locales_value='$page_content' WHERE locales_name = '404page' AND locales_key='404page' AND locales_code='$lc'");
		redirect(FUSION_SELF.$aidlink."&status=su&lc=$lc");
	} else {
		redirect(FUSION_SELF.$aidlink."&status=er&lc=$lc");
	}
}

// get the defined 404 pages for the dropdown
$variables['pages'] = array();
$result = dbquery("SELECT c.locale_name, l.* from ".$db_prefix."locales l, ".$db_prefix."locale c WHERE l.locales_code = c.locale_code AND l.locales_name = '404page' AND l.locales_key = '404page' AND c.locale_active = 1 ORDER BY l.locales_code");
while ($data = dbarray($result)) {
	$data['selected'] = ($data['locales_code'] == $lc);
	$variables['pages'][] = $data;
}	

// image locations for TinyMCE
$variables['img_src'] = str_replace("../","",IMAGES);

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.404pages', 'template' => 'admin.404pages.tpl', 'locale' => "admin.404pages");
$template_variables['admin.404pages'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
