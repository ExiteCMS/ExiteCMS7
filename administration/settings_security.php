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
		// authentication method check
		$auth_method = $_POST['auth_method']{0};
		$auth_local = (isset($_POST['auth_method']{1}) && $_POST['auth_method']{1} == "+") ? "1" : "0";
		switch ($auth_method) {
			case "0": 	// Local only
				$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = 'local' WHERE cfg_name = 'auth_type'");
				break;
			case "1": 	// LDAP
				if ($auth_local) {
					$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = 'ldap,local' WHERE cfg_name = 'auth_type'");
				} else {
					$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = 'ldap' WHERE cfg_name = 'auth_type'");
				}
				break;
			case "2":	// AD
				if ($auth_local) {
					$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = 'ad,local' WHERE cfg_name = 'auth_type'");
				} else {
					$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = 'ad' WHERE cfg_name = 'auth_type'");
				}
				break;
			case "3":	// OpenID
				if ($auth_local) {
					$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = 'local,openid' WHERE cfg_name = 'auth_type'");
				} else {
					$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = 'openid' WHERE cfg_name = 'auth_type'");
				}
				break;
			default:
				$variables['errormessage'] = "Invalid authentication method. This may never happen!";
		}
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

// check if the PHP installation supports the OpenID class
$variables['has_curl'] = function_exists('curl_exec');

// determine the auth_method defined
$auth_methods = explode(",",$settings2['auth_type']);
$auth_method = 0;
$auth_local = false;
foreach($auth_methods as $this_method) {
	switch($this_method) {
		case "ldap":
			$auth_method = 1;
			break;
		case "ad":
			$auth_method = 2;
			break;
		case "openid":
			// OpenID requires CURL to be installed
			if ($variables['has_curl']) {
				$auth_method = 3;
			}
		case "local":
			$auth_local = true;
			break;
		default:
			$auth_method = 0;
	}
}

// check if a local fallback is defined
$variables['auth_method'] = $auth_method . ($auth_local ? "+" : " ");

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_security', 'template' => 'admin.settings_security.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_security'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
