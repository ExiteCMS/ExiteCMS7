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
locale_load("admin.main");

// check for the proper admin access rights
if (!checkrights("T") || !defined("iAUTH") || $aid != iAUTH) fallback(ADMIN."index.php");

// temp storage for template variables
$variables = array();

// flag whether or not to show images or only links
$variables['admin_images'] = true;

// get the list of available webmaster tools (dot-files only for webmasters!)
$dirlist = makefilelist(PATH_ADMIN."tools", ".|..", true, "files", iSUPERADMIN);

// make sure nothing is executed when loading the language packs found
define('LP_SKIP_MAIN', true);

$modules = array();
foreach($dirlist as $module) {
	// skip all non-PHP files
	if (substr($module,-4) != ".php") continue;
	// temp array to store the module info
	if (substr($module,0,14) == "language_pack_") {
		// language packs
		@include PATH_ADMIN."tools/".$module;
		$temp = array('admin_link' => ADMIN."tools/".$module, 
					'admin_image' => ADMIN."images/settings_lang.gif",
					'admin_link' => ADMIN."tools/".$module, 'admin_image' => ADMIN."images/settings_lang.gif",
					'admin_title' => ucwords(str_replace("_", " ", substr($module,0,-4)))." (".date("Ymd-B", time_system2local($lp_date)).")"
				);
	} else {
		// other toolbox modules
		$temp = array('admin_link' => ADMIN."tools/".$module, 
					'admin_image' => ADMIN."images/tools.gif",
					'admin_link' => ADMIN."tools/".$module, 'admin_image' => ADMIN."images/tools.gif",
					'admin_title' => ucwords(str_replace("_", " ", substr($module,0,-4)))
				);
	}
	// store the module info for the template
	$modules[] = $temp;
}

$variables['rows'] = count($modules);
$variables['modules'] = $modules;

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'admin.tools', 'template' => 'admin.tools.tpl', 'locale' => "admin.main");
$template_variables['admin.tools'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
