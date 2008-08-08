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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("main.reports");

// temp storage for template variables
$variables = array();

//check if this is an administrator request
$admin_req = checkrights("R") && defined("iAUTH") && $aid != iAUTH;

// check if the action variable is defined, if not, assign a default
if (!isset($action)) $action = "";

// check if the report_id variable is defined, if not, assign a default
if (!isset($report_id) || !isNum($report_id)) $report_id = 0;

// array to store the required locales
$locales = array();

if ($action == "report") {
	// get the report info
	$result = dbquery("SELECT r.*, m.mod_folder FROM ".$db_prefix."reports r LEFT JOIN ".$db_prefix."modules m ON r.report_mod_id = m.mod_id WHERE r.report_id = '".$report_id."' AND r.report_active = 1");
	if ($variables = dbarray($result)) {
		if (checkgroup($variables['report_visibility'])) {
			// get the title for this report
			if ($variables['report_mod_id']) {
				locale_load("modules.".$variables['mod_folder']);
				$variables['report_title'] = $locale[$variables['report_title']];
				$locales[] = "modules.".$variables['mod_folder'];
			} else {
				// make sure this field is not NULL, we need it later
				$variables['mod_folder'] = "";
				// it's a core report, get the title for the module locales
				locale_load("main.reports");
				if (isset($locale[$variables['report_title']])) {
					$variables['report_title'] = $locale[$variables['report_title']];
				} else {
					// not found, assume it's a static title
				}
			}
			// get the access group name
			$variables['groupname'] = getgroupname($variables['report_visibility']);
			// name of the report table
			$variables['template'] = PATH_MODULES.$variables['mod_folder']."/templates/modules.".$variables['mod_folder'].".report.".$variables['report_name'].".tpl";
			// do any postprocessing
			@include PATH_MODULES.$variables['mod_folder']."/report.".$variables['report_name'].".php";
			// store the report result
			$variables['reportvars'] = isset($reportvars) ? $reportvars : "";
		} else {
			// no access to the report requested, return to the list
			$action = "";
		}
	} else {
		// report not found, return to the list
		$action = "";
	}
}

if ($action == "") {
	// generate the report overview
	$reports = array();
	$reportindex = array();
	$result = dbquery("SELECT r.*, m.mod_folder FROM ".$db_prefix."reports r LEFT JOIN ".$db_prefix."modules m ON r.report_mod_id = m.mod_id WHERE r.report_active = 1");
	while ($data = dbarray($result)) {
		if (checkgroup($data['report_visibility'])) {
			// get the title for this report
			if ($data['report_mod_id']) {
				locale_load("modules.".$data['mod_folder']);
				$data['report_title'] = $locale[$data['report_title']];
				$locales[] = "modules.".$data['mod_folder'];
			} else {
				// make sure this field is not NULL
				$data['mod_folder'] = "";
			}
			// get the access group name
			$data['groupname'] = getgroupname($data['report_visibility']);
			// name of the report table
			$data['template'] = PATH_MODULES.$data['mod_folder']."/templates/modules.".$data['mod_folder'].".report.".$data['report_name'].".tpl";
			// do any preprocessing
			@include PATH_MODULES.$data['mod_folder']."/report.".$data['report_name'].".php";
			$data['reportvars'] = isset($reportvars) ? $reportvars : "";
			// store the report record
			$reports[$data['report_id']] = $data;
			$reportindex[] = $data['report_title']."_>_".$data['report_id'];
		}
	}
	//make sure the modules are properly sorted
	sort($reportindex);
	$variables['reports'] = array();
	foreach($reportindex as $index) {
		$variables['reports'][] = $reports[substr(strstr($index,"_>_"),3)];
	}
}
$locales[] = "main.reports";

$variables['action'] = $action;
$variables['report_id'] = $report_id;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'main.reports', 'template' => 'main.reports.tpl', 'locale' => $locales);
$template_variables['main.reports'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
