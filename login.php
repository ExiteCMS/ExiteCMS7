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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_INCLUDES."theme_functions.php";

// redirect back to the homepage if already logged in
if (iMEMBER) {
	header("Location:".BASEDIR."index.php");
	exit;
}

// check if HTTPS if required, and if so, present.
if ($settings['auth_ssl'] && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")) {
	header("Location:".BASEDIR."setuser.php?error=5");
	exit;
}

// temp storage for template variables
$variables = array();

$variables['loginerror'] = isset($loginerror) ? $loginerror : "";
$variables['remember_me'] = isset($_SESSION['remember_me']) ? $_SESSION['remember_me'] : "no";
$variables['login_expiry']  = (iADMIN && isset($_SESSION['login_expire'])) ? time_system2local($_SESSION['login_expire']) : "";

// get which authentication to show
$variables['auth_methods'] = explode(",",$settings['authentication_selected']);
$variables['method_count'] = count($variables['auth_methods']);
$variables['auth_state'] = array();
foreach($variables['auth_methods'] as $key => $method) {
	if (isset($_SESSION['box_login'.$key])) {
		$variables['auth_state'][] = $_SESSION['box_login'.$key] == 0 ? 1 : 0;
	} else {
		$variables['auth_state'][] = 1;
	}
}

// check if we need to display a registration link
if ($settings['enable_registration']) {
	$variables['show_reglink'] = true;
	// get all menu items for this user
	$linkinfo = array();
	require_once PATH_INCLUDES."menu_include.php";
	menu_generate_tree("", array(1,2,3), false);
	foreach ($linkinfo as $link) {
		if ($link['link_url'] == "/register.php") {
			$variables['show_reglink'] = false;
			break;
		}
	}
} else {
	$variables['show_reglink'] = false;
}

// check if we need to display links
$variables['show_passlink'] = 1;

// define the first body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'login', 'template' => 'main.login.tpl');
$template_variables['login'] = $variables;

// make sure updates to session variables are written
session_write_close();

load_templates('body', '');

// and clean up
theme_cleanup();
?>
