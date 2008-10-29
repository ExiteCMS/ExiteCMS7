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
locale_load("admin.activation");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkRights("UA") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");

// make sure step has a value
if (!isset($step)) $step = "";

// activate a newly registered user
if ($step == "activate") {
	// check which kind of activation is needed
	if (isset($_GET['user_code'])) {
		$usercode = stripinput($_GET['user_code']);
		$result = dbquery("SELECT * FROM ".$db_prefix."new_users WHERE user_code = '$usercode'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$data = array_merge($data, unserialize($data['user_info']));
			$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_fullname, user_password, user_email, user_hide_email, user_offset, user_posts, user_joined, user_level, user_ip, user_status) VALUES('".$data['user_name']."', '".$data['user_fullname']."', md5(md5('".$data['user_password']."')), '".$data['user_email']."', '".$data['user_hide_email']."', '".$data['user_offset']."', '0', '".time()."', '101', '".$data['user_ip']."', '0')");
			$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_code = '$usercode'");
		}
	} elseif (isset($_GET['user_id'])) {
		$user_id = stripinput($_GET['user_id']);
		if (isNum($user_id)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id = '$user_id'");
			if (dbrows($result)) {
				$result = dbquery("UPDATE ".$db_prefix."users SET user_status = '0' WHERE user_id = '$user_id'");
			}
		}
	}
}

// delete a user registeration
if ($step == "delete") {
	$usercode = stripinput($_GET['user_code']);
	$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_code = '$usercode'");
}

// get the list of new users
$variables['newusers'] = array();

// when using email verification...
if ($settings['email_verification'] == '1') {
	// get the users from the new_users table
	$result = dbquery("SELECT * FROM ".$db_prefix."new_users ORDER BY user_datestamp");
	while ($data = dbarray($result)) {
		$variables['newusers'][] = array_merge($data, unserialize($data['user_info']));
	}
} else {
	// otherwise they are in the users tabel with a user_status of 2
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_status = '2' ORDER BY user_joined");
	while ($data = dbarray($result)) {
		$variables['newusers'][] = $data;
	}
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.activation', 'template' => 'admin.activation.tpl', 'locale' => "admin.activation");
$template_variables['admin.activation'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
