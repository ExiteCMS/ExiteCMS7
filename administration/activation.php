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
locale_load("admin.activation");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkRights("UA") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");

// make sure step has a value
if (!isset($step)) $step = "";

// activate a newly registered user
if ($step == "activate") {
	$usercode = stripinput($_GET['user_code']);
	$result = dbquery("SELECT * FROM ".$db_prefix."new_users WHERE user_code = '$usercode'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$data = array_merge($data, unserialize($data['user_info']));
		$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_fullname, user_password, user_email, user_hide_email, user_offset, user_posts, user_joined, user_level, user_ip, user_status) VALUES('".$data['user_name']."', '".$data['user_fullname']."', md5(md5('".$data['user_password']."')), '".$data['user_email']."', '".$data['user_hide_email']."', '".$data['user_offset']."', '0', '".time()."', '101', '".$data['user_ip']."', '0')");
		$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_code = '$usercode'");
	}
}

// delete a user registeration
if ($step == "delete") {
	$usercode = stripinput($_GET['user_code']);
	$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_code = '$usercode'");
}

// get the list of new users
$variables['newusers'] = array();

$result = dbquery("SELECT * FROM ".$db_prefix."new_users ORDER BY user_datestamp");
while ($data = dbarray($result)) {
	$variables['newusers'][] = array_merge($data, unserialize($data['user_info']));
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.activation', 'template' => 'admin.activation.tpl', 'locale' => "admin.activation");
$template_variables['admin.activation'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>