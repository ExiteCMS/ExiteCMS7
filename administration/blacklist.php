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
locale_load("admin.blacklist");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("B") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid
if (isset($blacklist_id) && !isNum($blacklist_id)) fallback(BASEDIR."index.php");
if (isset($user_id) && !isNum($user_id)) fallback(BASEDIR."index.php");

// make sure the step variable is initialised
if (!isset($step)) $step = "";

if (isset($status)) {
	if ($status == "del") {
		$title = $locale['400'];
		$message = $locale['401'];
	}
	if ($status == "ban") {
		$title = $locale['400'];
		$message = $locale['471'];
	}
	$variables['message'] = $message;
	$variables['bold'] = true;
	// define the admin body panel
	$template_panels[] = array('type' => 'body', 'name' => 'blacklist.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => "admin.blacklist");
	$template_variables['blacklist.status'] = $variables;
}

if ($step == "delete") {
	if (isset($blacklist_id)) {
		$result = dbquery("DELETE FROM ".$db_prefix."blacklist WHERE blacklist_id='$blacklist_id'");
	} elseif (isset($user_id)) {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_status = '0' WHERE user_id='$user_id'");
	} else {
		redirect(FUSION_SELF.$aidlink);
	}
	redirect(FUSION_SELF.$aidlink."&status=del");
}

if (isset($_POST['blacklist'])) {
	$blacklist_ip = stripinput($_POST['blacklist_ip']);
	$blacklist_email = stripinput($_POST['blacklist_email']);
	$blacklist_user = isNum($_POST['blacklist_user']) ? $_POST['blacklist_user'] : 0;
	$blacklist_timeout = isNum($_POST['blacklist_timeout']) ? $_POST['blacklist_timeout'] : 0;
	$blacklist_reason = stripinput($_POST['blacklist_reason']);
	if ($blacklist_ip || $blacklist_email) {
		if ($step == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."blacklist SET blacklist_ip='$blacklist_ip', blacklist_email='$blacklist_email', blacklist_reason='$blacklist_reason' WHERE blacklist_id='$blacklist_id'");
		} else {
				$result = dbquery("INSERT INTO ".$db_prefix."blacklist (blacklist_ip, blacklist_email, blacklist_reason) VALUES ('$blacklist_ip', '$blacklist_email', '$blacklist_reason')");
		}
	} elseif ($blacklist_user) {
		$ban_expire = $blacklist_timeout == 0 ? $blacklist_timeout : (time() + $blacklist_timeout * 86400);
		$result = dbquery("UPDATE ".$db_prefix."users SET user_status='1', user_ban_expire = '$ban_expire', user_ban_reason='".mysql_escape_string($blacklist_reason)."' WHERE user_id='$blacklist_user'");
	}
	redirect(FUSION_SELF.$aidlink."&status=ban");
}

if ($step == "edit") {
	if (isset($blacklist_id)) {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."blacklist WHERE blacklist_id='$blacklist_id'"));
		$blacklist_ip = $data['blacklist_ip'];
		$blacklist_email = $data['blacklist_email'];
		$blacklist_reason = $data['blacklist_reason'];
		$form_title = $locale['421'];
		$form_action = FUSION_SELF.$aidlink."&amp;step=edit&amp;blacklist_id=".$data['blacklist_id'];
	} elseif (isset($user_id)) {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$user_id'"));
		$blacklist_user = $data['user_id'];
		$blacklist_reason = $data['user_ban_reason'];
		$blacklist_timeout = (int) (($data['user_ban_expire'] - time()) / 86400) + 1;
		$form_title = $locale['421'];
		$form_action = FUSION_SELF.$aidlink."&amp;step=edit&amp;user_id=".$data['user_id'];
	}
} else {
	$blacklist_ip = isset($_GET['ip']) ? $_GET['ip'] : "";
	$blacklist_user = isset($_GET['user_id']) ? $_GET['user_id'] : "";
	$blacklist_email = "";
	$blacklist_reason = isset($_GET['reason']) ? (isset($locale[$_GET['reason']]) ? $locale[$_GET['reason']] : "") : "";
	$form_title = $locale['420'];
	$form_action = FUSION_SELF.$aidlink;
}

$variables['blacklist_ip'] = isset($blacklist_ip) ? $blacklist_ip : "";
$variables['blacklist_email'] = isset($blacklist_email) ? $blacklist_email : "";
$variables['blacklist_reason'] = isset($blacklist_reason) ? $blacklist_reason : "";
$variables['blacklist_user'] = isset($blacklist_user) ? $blacklist_user : "0";
$variables['blacklist_timeout'] = isset($blacklist_timeout) ? $blacklist_timeout : "";
$variables['form_title'] = $form_title;
$variables['form_action'] = $form_action;

$variables['blacklist'] = array();
// get the list of blacklisted users
$result = dbquery("SELECT user_id, user_name, user_status FROM ".$db_prefix."users WHERE user_status = '1'");
while ($data = dbarray($result)) {
	$variables['blacklist'][] = $data;
}
// get the list of blacklisted ip's and email addresses
$result = dbquery("SELECT * FROM ".$db_prefix."blacklist");
while ($data = dbarray($result)) {
	$variables['blacklist'][] = $data;
}

// get the list of active members
if (!empty($user_id)) {
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id = '$user_id'");
} else {
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_status = '0'");
}
$variables['users'] = array();
while ($data = dbarray($result)) {
	$variables['users'][] = $data;
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.blacklist', 'template' => 'admin.blacklist.tpl', 'locale' => "admin.blacklist");
$template_variables['admin.blacklist'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
