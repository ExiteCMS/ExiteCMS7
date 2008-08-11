<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// temp storage for template variables
$variables = array();

// minimum search length
define('MIN_SEARCH_LENGTH', 3);

// load the locale for this module
locale_load("main.search");
$locales = array("main.search");

// default the possible filters
$select_filters = array("score", "author", "subject", "datestamp", "count");

// storage for additional filter values
$content_filters = array();

if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
$variables['rowstart'] = $rowstart;

if (!isset($action)) $action = "";
$variables['action'] = $action;

if (!isset($search_id)) {
	if (isset($_POST['search_id']) && isNum($_POST['search_id'])) {
		$search_id = $_POST['search_id'];
	} else {
		$search_id = 0;
	}
}

// get the list of available searches
$searches = array();
$searchindex = array();
$result = dbquery("SELECT s.*, m.mod_folder FROM ".$db_prefix."search s LEFT JOIN ".$db_prefix."modules m ON s.search_mod_id = m.mod_id WHERE s.search_active = 1".($search_id?" AND s.search_id = '$search_id'":""));
while ($data = dbarray($result)) {
	if (checkgroup($data['search_visibility'])) {
		// do we have a search location? if not, make forumposts default
//		if (!$search_id && $data['search_name'] == "forumposts") {
//			$search_id = $data['search_id'];
//		}
		// get the title for this search
		if ($data['search_mod_id']) {
			$data['custom'] = false;
			locale_load("modules.".$data['mod_folder']);
			$data['search_title'] = $locale[$data['search_title']];
			$locales[] = "modules.".$data['mod_folder'];
			// name of the search template
			$data['template'] = PATH_MODULES.$data['mod_folder']."/templates/modules.".$data['mod_folder'].".search.".$data['search_name'].".tpl";
			// do any preprocessing
			@include PATH_MODULES.$data['mod_folder']."/search.".$data['search_name'].".php";
		} else {
			if ($data['search_mod_core']) {
				$data['mod_folder'] = "ExiteCMS";
				$data['custom'] = false;
				// name of the search template
				$data['template'] = PATH_INCLUDES."search/templates/search.".strtolower($data['search_name']).".tpl";
				// do any preprocessing
				@include PATH_INCLUDES."search/search.".strtolower($data['search_name']).".php";
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
		// get the access group name
		$data['groupname'] = getgroupname($data['search_visibility']);
		// store any reporting variables
		$variables['reportvars'] = isset($reportvars) ? $reportvars : array();
		// no results?
		if (count($variables['reportvars']) == 0) {
			$variables['message'] = $locale['src432'];
		}
		// store the search record
		$searches[$data['search_id']] = $data;
		$searchindex[] = $data['search_title']."_>_".$data['search_id'];
	}
}
//make sure the searches are properly sorted
sort($searchindex);
$variables['searches'] = array();
foreach($searchindex as $index) {
	$variables['searches'][] = $searches[substr(strstr($index,"_>_"),3)];
}

// do we have a search location? if not, get the first search item
if (!$search_id && count($variables['searches'])) {
//	$search_id = $variables['searches'][0]['search_id'];
}
// store the selected search location
$variables['search_id'] = $search_id;

// store the extra filters defined
$variables['content_filters'] = $content_filters;

// make sure the panel has a title
if (!isset($title)) {
	$title = $locale['src406'];
}

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'search', 'title' => $title, 'template' => 'main.search.tpl', 'locale' => $locales);
$template_variables['search'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
