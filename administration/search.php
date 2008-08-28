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
locale_load("admin.search");

// temp storage for template variables
$variables = array();

//check if the user has a right to be here. If not, bail out
if (!checkrights("S") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// check if the action variable is defined, if not, assign a default
if (!isset($action)) $action = "";

// check if the search_id variable is defined, if not, assign a default
if (!isset($search_id) || !isNum($search_id)) $search_id = 0;
$variables['search_id'] = $search_id;

// save requested?
if (isset($_POST['save'])) {
	$visibility = isset($_POST['search_visibility']) && isNum($_POST['search_visibility']) ? $_POST['search_visibility'] : 103;
	switch ($_POST['action']) {
		case "add":
			// not implemented yet
			break;
		case "edit":
			$result = dbquery("UPDATE ".$db_prefix."search SET search_visibility='$visibility' WHERE search_id = '$search_id'");
			$action = "";
			break;
		default:
			die('invalid action passed!');
	}		
}

// add a new search definition
if ($action == "add" && !isset($variables['search'])) {
	$variables['searh'] = array(
		'search_id' => 0,
		'search_mod_id' => 0,
		'search_name' => "",
		'search_title' => "",
		'search_query' => "",
		'search_template' => "",
		'search_version' => "",
		'search_active' => 0,
		'search_visibility' => 103
	);
}

// edit an existing search definition
if ($action == "edit" && !isset($variables['search'])) {
	$result = dbquery("SELECT r.*, m.mod_folder FROM ".$db_prefix."search r LEFT JOIN ".$db_prefix."modules m ON r.search_mod_id = m.mod_id WHERE search_id = '".$search_id."'");
	if ($data = dbarray($result)) {
		// get the title for this search
		if ($data['search_mod_id']) {
			$data['custom'] = false;
			locale_load("modules.".$data['mod_folder']);
			$data['search_title'] = $locale[$data['search_title']];
			$locales[] = "modules.".$data['mod_folder'];
		} else {
			if ($data['search_mod_core']) {
				$data['mod_folder'] = "ExiteCMS";
				$data['custom'] = false;
			} else {
				$data['mod_folder'] = "-";
				$data['custom'] = true;
			}
			// it's a core search, get the title from the module locales
			locale_load("main.search");
			if (isset($locale[$data['search_title']])) {
				$data['search_title'] = $locale[$data['search_title']];
			} else {
				// not found, assume it's a static title
			}
		}
		$variables['search'] = $data;
	} else {
		// return to the overview screen
		$action = "";
	}
}

// add a new or edit an existing search definition
if ($action == "add" || $action == "edit") {
	// get the list of defined user groups
	$variables['usergroups'] = getusergroups(false);
}

// toggle the search status
if ($action == "setstatus") {
	// if a status is passed, validate it
	if (isset($status) && isNum($status) && $status >= 0 && $status <= 1) {
		$result = dbquery("UPDATE ".$db_prefix."search SET search_active = '".$status."' WHERE search_id = '".$search_id."'");
	}
	// return to the overview screen
	$action = "";
}

// swap the order of two records
if ($action == "swap") {
	// check the parameters passed
	$order1 = (isset($_GET['order1']) && isNum($_GET['order1'])) ? $_GET['order1'] : 0;
	$order2 = (isset($_GET['order2']) && isNum($_GET['order2'])) ? $_GET['order2'] : 0;
	if ($order1 != 0 && $order2 != 0 && $order1 != $order2) {
		$result1 = dbquery("SELECT search_id FROM ".$db_prefix."search WHERE search_order = '".$order1."'");
		$result2 = dbquery("SELECT search_id FROM ".$db_prefix."search WHERE search_order = '".$order2."'");
		if ($result1 && $result2) {
			// everything checks out, swap 'm!
			$data = dbarray($result1);
			$result = dbquery("UPDATE ".$db_prefix."search SET search_order = ".$order2." WHERE search_id = ".$data['search_id']);
			$data = dbarray($result2);
			$result = dbquery("UPDATE ".$db_prefix."search SET search_order = ".$order1." WHERE search_id = ".$data['search_id']);
		}
	}
	// return to the overview screen
	$action = "";
}

// no action specified: show the search overview
if ($action == "") {
	// generate the searches overview
	$variables['searches'] = array();
	$result = dbquery("SELECT s.*, m.mod_folder FROM ".$db_prefix."search s LEFT JOIN ".$db_prefix."modules m ON s.search_mod_id = m.mod_id ORDER BY search_order");
	while ($data = dbarray($result)) {
		// get the title for this search
		if ($data['search_mod_id']) {
			locale_load("modules.".$data['mod_folder']);
			$data['search_title'] = $locale[$data['search_title']];
		} else {
			// make sure this field is not NULL, we need it later
			$variables['mod_folder'] = "";
			// it's a core search, get the title for the module locales
			locale_load("main.search");
			if (isset($locale[$data['search_title']])) {
				$data['search_title'] = $locale[$data['search_title']];
			} else {
				// not found, assume it's a static title
			}
		}
		$data['groupname'] = getgroupname($data['search_visibility']);
			// store the search record
		$variables['searches'][] = $data;
	}
	// reload the locale for this module
	locale_load("admin.search");
}

// add the previous and next id's
foreach($variables['searches'] as $key => $value) {
	$variables['searches'][$key]['order_down'] = isset($variables['searches'][$key+1]) ? $variables['searches'][$key+1]['search_order'] : 0;
	$variables['searches'][$key]['order_up'] = isset($variables['searches'][$key-1]) ? $variables['searches'][$key-1]['search_order'] : 0;
}

// store the action variable
$variables['action'] = $action;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.search', 'template' => 'admin.search.tpl', 'locale' => "admin.search");
$template_variables['admin.search'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
