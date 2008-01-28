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

$variables['message'] = "There are no admin operations for the blogs module at the moment";
$variables['bold'] = true;
// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'blogs.message', 'title' => $locale['400'], 'template' => '_message_table_panel.tpl', 'locale' => "admin.blogs");
$template_variables['blogs.message'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>