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
locale_load("admin.reports");

// temp storage for template variables
$variables = array();

//check if the user has a right to be here. If not, bail out
if (!checkrights("R") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// check if the action variable is defined, if not, assign a default
if (!isset($action)) $action = "";

// check if the report_id variable is defined, if not, assign a default
if (!isset($report_id) || !isNum($report_id)) $report_id = 0;
$variables['report_id'] = $report_id;

// save requested?
if (isset($_POST['save'])) {
	$visibility = isset($_POST['report_visibility']) && isNum($_POST['report_visibility']) ? $_POST['report_visibility'] : 103;
	switch ($_POST['action']) {
		case "add":
			// not implemented yet
			break;
		case "edit":
			$result = dbquery("UPDATE ".$db_prefix."reports SET report_visibility='$visibility' WHERE report_id = '$report_id'");
			$action = "";
			break;
		default:
			die('invalid action passed!');
	}		
}

// add a new report definition
if ($action == "add" && !isset($variables['report'])) {
	$variables['report'] = array(
		'report_id' => 0,
		'report_mod_id' => 0,
		'report_mod_core' => 0,
		'report_name' => "",
		'report_title' => "",
		'report_version' => "",
		'report_active' => 0,
		'report_visibility' => 103
	);
}

// edit an existing report definition
if ($action == "edit" && !isset($variables['report'])) {
	$result = dbquery("SELECT r.*, m.mod_folder FROM ".$db_prefix."reports r LEFT JOIN ".$db_prefix."modules m ON r.report_mod_id = m.mod_id WHERE report_id = $report_id");
	if ($data = dbarray($result)) {
		// get the title for this report
		if ($data['report_mod_id']) {
			$data['custom'] = false;
			locale_load("modules.".$data['mod_folder']);
			$data['report_title'] = $locale[$data['report_title']];
			$locales[] = "modules.".$data['mod_folder'];
		} else {
			if ($data['report_mod_core']) {
				$data['mod_folder'] = "ExiteCMS";
				$data['custom'] = false;
			} else {
				$data['mod_folder'] = "-";
				$data['custom'] = true;
			}
			// it's a core report, get the title for the module locales
			locale_load("main.reports");
			if (isset($locale[$data['report_title']])) {
				$data['report_title'] = $locale[$data['report_title']];
			} else {
				// not found, assume it's a static title
			}
		}
		$variables['report'] = $data;
	} else {
		// return to the overview screen
		$action = "";
	}
}
//_debug($variables, true);
// add a new or edit an existing report definition
if ($action == "add" || $action == "edit") {
	// get the list of defined user groups
	$variables['usergroups'] = getusergroups(true);
}

// toggle the report status
if ($action == "setstatus") {
	// if a status is passed, validate it
	if (isset($status) && isNum($status) && $status >= 0 && $status <= 1) {
		$result = dbquery("UPDATE ".$db_prefix."reports SET report_active = '".$status."' WHERE report_id = '".$report_id."'");
	}
	// return to the overview screen
	$action = "";
}

// no action specified: show the report overview
if ($action == "") {
	// generate the report overview
	$reports = array();
	$reportindex = array();
	$result = dbquery("SELECT r.*, m.mod_folder FROM ".$db_prefix."reports r LEFT JOIN ".$db_prefix."modules m ON r.report_mod_id = m.mod_id");
	while ($data = dbarray($result)) {
		// get the title for this report
		if ($data['report_mod_id']) {
			locale_load("modules.".$data['mod_folder']);
			$data['report_title'] = $locale[$data['report_title']];
		} else {
			// make sure this field is not NULL, we need it later
			$variables['mod_folder'] = "";
			// it's a core report, get the title for the module locales
			locale_load("main.reports");
			if (isset($locale[$data['report_title']])) {
				$data['report_title'] = $locale[$data['report_title']];
			} else {
				// not found, assume it's a static title
			}
		}
		$data['groupname'] = getgroupname($data['report_visibility']);
			// store the report record
		$reports[$data['report_id']] = $data;
		$reportindex[] = $data['report_title']."_>_".$data['report_id'];
	}
	//make sure the modules are properly sorted
	sort($reportindex);
	$variables['reports'] = array();
	foreach($reportindex as $index) {
		$variables['reports'][] = $reports[substr(strstr($index,"_>_"),3)];
	}
	// reload the locale for this module
	locale_load("admin.reports");
}

// store the action variable
$variables['action'] = $action;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.reports', 'template' => 'admin.reports.tpl', 'locale' => "admin.reports");
$template_variables['admin.reports'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
