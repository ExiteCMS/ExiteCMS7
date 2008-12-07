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
locale_load("admin.settings");

// temp storage for template variables
$variables = array();

// store this modules name for the menu bar
$variables['this_module'] = FUSION_SELF;

if (!isset($action)) $action = "";

// check for the proper admin access rights
if (!checkrights("S4") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	// validate the input
	$variables['errormessage'] = "";
	$session_timeout = is_numeric($_POST['session_timeout']) ? ($_POST['session_timeout'] * 86400) : 86400;
	$login_expire = isNum($_POST['login_expire']) ? ($_POST['login_expire'] * 60) : 3600;
	$login_extended_expire = isNum($_POST['login_extended_expire']) ? ($_POST['login_extended_expire'] * 86400) : 43200;
	if ($login_extended_expire > $session_timeout) {
		$variables['errormessage'] = $locale['532'];
	} elseif ($login_expire > $login_extended_expire) {
		$variables['errormessage'] = $locale['533'];
	}
	if ($variables['errormessage'] == "") {
		if ($variables['errormessage'] == "") {
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['enable_registration']) ? $_POST['enable_registration'] : "1")."' WHERE cfg_name = 'enable_registration'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['email_verification']) ? $_POST['email_verification'] : "1")."' WHERE cfg_name = 'email_verification'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['admin_activation']) ? $_POST['admin_activation'] : "0")."' WHERE cfg_name = 'admin_activation'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['display_validation']) ? $_POST['display_validation'] : "1")."' WHERE cfg_name = 'display_validation'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".stripinput($_POST['validation_method'])."' WHERE cfg_name = 'validation_method'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '$session_timeout' WHERE cfg_name = 'session_gc_maxlifetime'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '$login_expire' WHERE cfg_name = 'login_expire'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '$login_extended_expire' WHERE cfg_name = 'login_extended_expire'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['auth_ssl']) ? $_POST['auth_ssl'] : "0")."' WHERE cfg_name = 'auth_ssl'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['auth_required']) ? $_POST['auth_required'] : "0")."' WHERE cfg_name = 'auth_required'");
		}
	}
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".$db_prefix."configuration");
while ($data = dbarray($result)) {
	$settings2[$data['cfg_name']] = $data['cfg_value'];
}
$variables['settings2'] = $settings2;

// convert these values to what's used on the panel
if (isset($session_timeout)) {
	$variables['session_timeout'] = $session_timeout / 86400;
} else {
	$variables['session_timeout'] = $settings2['session_gc_maxlifetime'] / 86400;	// in days
}
if (isset($login_expire)) {
	$variables['login_expire'] = $login_expire / 60;
} else {
	$variables['login_expire'] = $settings2['login_expire'] / 60;	// in minutes
}
if (isset($login_extended_expire)) {
	$variables['login_extended_expire'] = $login_extended_expire / 86400;
} else {
	$variables['login_extended_expire'] = $settings2['login_extended_expire'] / 86400;	// in days
}

// load the defined authentication methods
$methods = unserialize($settings2['authentication_methods']);
$selected = explode(",", $settings2['authentication_selected']);
foreach ($methods as $name => $method) {
	$methods[$name]['status'] = 'old';
}

// status update request?
if ($action == "setstatus") {
	if ($status == 1 && array_search($authmethod,$selected) == false) {
		// add the method to the selected array and update the configuration
		$selected[] = $authmethod;
	} elseif ($status == 0 && array_search($authmethod,$selected) !== false) {
		// remove the method from the selected array and update the configuration
		unset($selected[array_search($authmethod,$selected)]);
	}
	// write the update back
	$settings2['authentication_selected'] = "";
	foreach($selected as $sel) {
		$settings2['authentication_selected'] .= ($settings2['authentication_selected'] == "" ? "" : ",").$sel;
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$settings2['authentication_selected']."' WHERE cfg_name = 'authentication_selected'");
}

// move up requested?
if ($action == "up") {
	// swap the selected method with the previous in the list
	$sel = $selected[$method_id-1];
	$selected[$method_id-1] = $selected[$method_id];
	$selected[$method_id] = $sel;
	// write the update back
	$settings2['authentication_selected'] = "";
	foreach($selected as $sel) {
		$settings2['authentication_selected'] .= ($settings2['authentication_selected'] == "" ? "" : ",").$sel;
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$settings2['authentication_selected']."' WHERE cfg_name = 'authentication_selected'");
}

// move down requested?
if ($action == "down") {
	// swap the selected method with the previous in the list
	$sel = $selected[$method_id+1];
	$selected[$method_id+1] = $selected[$method_id];
	$selected[$method_id] = $sel;
	// write the update back
	$settings2['authentication_selected'] = "";
	foreach($selected as $sel) {
		$settings2['authentication_selected'] .= ($settings2['authentication_selected'] == "" ? "" : ",").$sel;
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$settings2['authentication_selected']."' WHERE cfg_name = 'authentication_selected'");
}

// check if new authentication methods have been installed
$files = makefilelist(PATH_INCLUDES."authentication", ".|..", $sort=true, $type="files", $hidden=false);
foreach($files as $file) {
	if (substr($file,0,5) == "auth_" && strrchr($file,".") == ".php") {
		$class = substr($file, 0, strrpos($file, "."));
		$method = substr($class, strrpos($file, "_")+1);
		if (!isset($methods[$method])) {
			$methods[$method] = array('class' => $class, 'status' => "new");
		} else {
			$methods[$method]['status'] = "found";
		}
	}
}

// delete old ones, add the others to the sortlist, and update the config
$sortlist = array();
foreach ($methods as $name => $method) {
	if ($method['status'] == "old") {
		unset($methods[$name]);
	} else {
		// add to the sortlist
		if (in_array($name, $selected)) {
			$sortlist[] = substr('000'.array_search($name, $selected),-3).".".$name;
		} else {
			$sortlist[] = "zzz.".$name;
		}
	}
}
$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".mysql_real_escape_string(serialize($methods))."' WHERE cfg_name = 'authentication_methods'");

// create the list of available methods, in the correct order
sort($sortlist);
$variables['methods'] = array();
$c = count($selected);$i=1;
foreach($sortlist as $entry) {
	$listentry = explode(".", $entry);
	$variables['methods'][] = array('name' => $listentry[1], 'class' => $methods[$listentry[1]]['class'], 'status' => $listentry[0] == 'zzz' ? 0 :1, 'last' => ($i++ == $c ? 1 : 0));
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_security', 'template' => 'admin.settings_security.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_security'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
