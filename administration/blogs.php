<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("admin.blogs");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("BG") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['saveoptions'])) {
	$variables['blogs_indexsize'] = stripinput($_POST['blogs_indexsize']);
	if (!isNum($variables['blogs_indexsize'])) $variables['blogs_indexsize'] = 5;
	$variables['blogs_indexage'] = stripinput($_POST['blogs_indexage']);
	if (!isNum($variables['blogs_indexage'])) $variables['blogs_indexage'] = 90;
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$variables['blogs_indexsize']."' WHERE cfg_name = 'blogs_indexsize'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$variables['blogs_indexage']."' WHERE cfg_name = 'blogs_indexage'");
} else {
	$variables['blogs_indexsize'] = $settings['blogs_indexsize'];
	$variables['blogs_indexage'] = $settings['blogs_indexage'];
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.blogs', 'template' => 'admin.blogs.tpl', 'locale' => "admin.blogs");
$template_variables['admin.blogs'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>