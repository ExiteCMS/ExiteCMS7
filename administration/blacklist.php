<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| Some portions copyright ? 2002 - 2006 Nick Jones   |
| http://www.php-fusion.co.uk/                       |
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.LOCALESET."admin/blacklist.php";

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("B") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid
if (isset($blacklist_id) && !isNum($blacklist_id)) fallback(BASEDIR."index.php");

// make sure the step variable is initialised
if (!isset($step)) $step = "";

if (isset($status)) {
	if ($status == "del") {
		$title = $locale['400'];
		$message = $locale['401'];
	}
	$variables['message'] = $message;
	$variables['bold'] = true;
	// define the admin body panel
	$template_panels[] = array('type' => 'body', 'name' => 'blacklist.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/blacklist.php");
	$template_variables['blacklist.status'] = $variables;
}

if ($step == "delete") {
	$result = dbquery("DELETE FROM ".$db_prefix."blacklist WHERE blacklist_id='$blacklist_id'");
	redirect(FUSION_SELF.$aidlink."&status=del");
} else {
	if (isset($_POST['blacklist_user'])) {
		$blacklist_ip = stripinput($_POST['blacklist_ip']);
		$blacklist_email = stripinput($_POST['blacklist_email']);
		$blacklist_reason = stripinput($_POST['blacklist_reason']);
		if ($blacklist_ip || $blacklist_email) {
			if ($step == "edit") {
				$result = dbquery("UPDATE ".$db_prefix."blacklist SET blacklist_ip='$blacklist_ip', blacklist_email='$blacklist_email', blacklist_reason='$blacklist_reason' WHERE blacklist_id='$blacklist_id'");
			} else {
					$result = dbquery("INSERT INTO ".$db_prefix."blacklist (blacklist_ip, blacklist_email, blacklist_reason) VALUES ('$blacklist_ip', '$blacklist_email', '$blacklist_reason')");
			}
		}
		redirect(FUSION_SELF.$aidlink);
	}
	if ($step == "edit") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."blacklist WHERE blacklist_id='$blacklist_id'"));
		$blacklist_ip = $data['blacklist_ip'];
		$blacklist_email = $data['blacklist_email'];
		$blacklist_reason = $data['blacklist_reason'];
		$form_title = $locale['421'];
		$form_action = FUSION_SELF.$aidlink."&amp;step=edit&amp;blacklist_id=".$data['blacklist_id'];
	} else {
		$blacklist_ip = isset($_GET['ip']) ? $_GET['ip'] : "";
		$blacklist_email = "";
		$blacklist_reason = isset($_GET['reason']) ? (isset($locale[$_GET['reason']]) ? $locale[$_GET['reason']] : "") : "";
		$form_title = $locale['420'];
		$form_action = FUSION_SELF.$aidlink;
	}
	$variables['blacklist_ip'] = $blacklist_ip;
	$variables['blacklist_email'] = $blacklist_email;
	$variables['blacklist_reason'] = $blacklist_reason;
	$variables['form_title'] = $form_title;
	$variables['form_action'] = $form_action;

	$variables['blacklist'] = array();
	$result = dbquery("SELECT * FROM ".$db_prefix."blacklist");
	while ($data = dbarray($result)) {
		$variables['blacklist'][] = $data;
	}
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.blacklist', 'template' => 'admin.blacklist.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/blacklist.php");
$template_variables['admin.blacklist'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>