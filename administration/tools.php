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
include PATH_LOCALE.LOCALESET."admin/main.php";

// check for the proper admin access rights
if (!checkrights("T") || !defined("iAUTH") || $aid != iAUTH) fallback(ADMIN."index.php");

// temp storage for template variables
$variables = array();

// flag whether or not to show images or only links
$variables['admin_images'] = true;

// get the list of available webmaster tools
$dirlist = makefilelist(PATH_ADMIN."tools", ".|..", true);

$modules = array();
foreach($dirlist as $module) {
	// skip all non-PHP files
	if (substr($module,-4) != ".php") continue;
	// temp array to store the module info
	$temp = array('admin_link' => ADMIN."tools/".$module, 'admin_image' => ADMIN."images/tools.gif");
	// strip the extension, sanitize the name, use it as title
	$temp['admin_title'] = ucwords(str_replace("_", " ", substr($module,0,-4)));
	// store the module info for the template
	$modules[] = $temp;
}

$variables['rows'] = count($modules);
$variables['modules'] = $modules;

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'admin.tools', 'template' => 'admin.tools.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/main.php");
$template_variables['admin.tools'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>