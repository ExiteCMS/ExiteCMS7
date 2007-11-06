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
locale_load("admin.redirects");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("UR") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameters are valid
if (!isset($action)) $action = "add";

// delete requested
if ($action == "delete") {
	$result = dbquery("SELECT * FROM ".$db_prefix."redirects WHERE url_id = '$url_id'");
	if (dbrows($result) == 0) {
		$error = 7;
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."redirects WHERE url_id = '$url_id'");
		$error = 6;
	}
	$action = "add";
}

// get any input values. If not present, provide a default
$url_from = isset($_POST['url_from']) ? stripinput($_POST['url_from']) : "";
$url_to = isset($_POST['url_to'])? stripinput($_POST['url_to']) : "";
$url_redirect = isset($_POST['url_redirect']) ? "1" : "0";

// save requested?
if (isset($_POST['save'])) {
	switch ($action) {
		case "add":
		case "edit":
			// check if we have a value for both fields
			if ($url_from == "" || $url_to == "")
				$error = 3;
			else {
				// make sure the url_from starts with a /
				if ($url_from{0} != "/") $url_from = "/" . $url_from;
				// check if url_to is a full URL. If so, force redirect to true
				$base_url = strpos($url_to, "?") === false ? $url_to : substr($url_to, 0, strpos($url_to, "?"));
				if (preg_match("/(http:\/\/|https:\/\/)(.*)/i", $base_url)) {
					$url_redirect = "1";
				} else {
					if (!file_exists(PATH_ROOT.substr($base_url, 1))) {
						$error = 5;
						break;
					}
					if ($url_to{0} != "/") $url_to = "/" . $url_to;
				}
				switch ($action) {
					case "add":
						$result = dbquery("SELECT * FROM ".$db_prefix."redirects WHERE url_from = '$url_from'");
						if (dbrows($result) != 0) {
							$error = 4;
						} else {
							$result = dbquery("INSERT INTO ".$db_prefix."redirects (url_from, url_to, url_redirect) VALUES ('$url_from', '$url_to', '$url_redirect')");
							$error = 1;
							$url_from = "";
							$url_to = "";
							$url_redirect = "0";
						}
						break;
					case "edit":
						$result = dbquery("UPDATE ".$db_prefix."redirects SET url_from = '$url_from', url_to = '$url_to', url_redirect = '$url_redirect' WHERE url_id = '$url_id'");
						$error = 2;
						$url_from = "";
						$url_to = "";
						$url_redirect = "0";
						$action = "add";
						break;
				}
			}
			break;
		default:
			fallback(BASEDIR."index.php");
	}
}

// set the title and prepare the action
switch ($action) {
	case "add":
		$title = $locale['400'];
		break;
	case "edit":
		$title = $locale['401'];
		$result = dbquery("SELECT * FROM ".$db_prefix."redirects WHERE url_id = '$url_id'");
		if (dbrows($result) == 0) {
			$error = 7;
		} else {
			$data = dbarray($result);
			$url_id = $data['url_id'];
			$url_from = $data['url_from'];
			$url_to = $data['url_to'];
			$url_redirect = $data['url_redirect'];
		}
		break;
	case "delete":
		if (!isset($url_id) && !isNum($url_id)) fallback(BASEDIR."index.php");
		break;
	default:
		fallback(BASEDIR."index.php");
}

// need to display an error message?
if (isset($error) && isNum($error)) {
	switch ($error) {
		case 1:
			$variables['message'] = $locale['482'];
			break;
		case 2:
			$variables['message'] = $locale['483'];
			break;
		case 3:
			$variables['message'] = $locale['484'];
			break;
		case 4:
			$variables['message'] = $locale['485'];
			break;
		case 5:
			$variables['message'] = $locale['486'];
			break;
		case 6:
			$variables['message'] = $locale['487'];
			$action = "add";
			break;
		case 7:
			$variables['message'] = $locale['489'];
			break;
		default:
			$variables['message'] = "Unknown error code!";
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.redirects.status', 'template' => '_message_table_panel.tpl');
	$template_variables['admin.redirects.status'] = $variables;
	$variables = array();
}

// process the action 
$variables['title'] = $title;
$variables['action'] = $action;
$variables['url_id'] = isset($url_id) ? $url_id : 0;
$variables['url_from'] = $url_from;
$variables['url_to'] = $url_to;
$variables['url_redirect'] = $url_redirect;

$result = dbquery("SELECT * FROM ".$db_prefix."redirects ORDER BY url_from");
$variables['redirects'] = array();
while ($data = dbarray($result)) {
	$variables['redirects'][] = $data;
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.redirects', 'title' => $title, 'template' => 'admin.redirects.tpl', 'locale' => "admin.redirects");
$template_variables['admin.redirects'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>