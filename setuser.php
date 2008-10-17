<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_INCLUDES."theme_functions.php";

/*---------------------------------------------------+
| User authentication functions                      |
+----------------------------------------------------*/

// authentication against the local user database
function auth_local($userid, $password) {
	global $db_prefix;
	
	// check and validate the given userid and pasword
	$user_pass = md5(md5($password));
	$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($userid));

	// check if we have a user record for this userid and password
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$user_name' AND user_password='".$user_pass."'");
	if (dbrows($result) == 0) {
		// not found, display an error message
		return 3;
	} else {
		// found, get the record and do some more validation
		$ret = auth_user_validate(dbarray($result));
		return $ret;
	}
}

// authentication against an LDAP server
function auth_ldap($userid, $password) {
	terminate('auth_ldap not defined yet!');
}

// authentication against an Active Directory server
function auth_ad($userid, $password) {
	terminate('auth_ad not defined yet!');
}

// authentication using an OpenID
function auth_openid($openid_url) {
	global $settings;

	// check if the URL is valid
	if (isURL($openid_url)) {
		require_once(PATH_INCLUDES."class.openid.php");
		$openid = new SimpleOpenID;
		$openid->SetIdentity($openid_url);
		$openid->SetApprovedURL($settings['siteurl']."setuser.php");
		$openid->SetTrustRoot($settings['siteurl']);
		$server_url = $openid->GetOpenIDServer();
		if ($server_url) {
			redirect($openid->GetRedirectURL() , "script");
			exit;
		}
	} else {
		// for now...
		return 0;
	}
}

// further validation on the userid found
function auth_user_validate($userrecord) {
	global $settings;

	// if the account is suspended, check for an expiry date
	if ($userrecord['user_status'] == 1 && $userrecord['user_ban_expire'] > 0 && $userrecord['user_ban_expire'] < time() ) {
		// if this user's email address is marked as bad, reset the countdown counter
		$userrecord['user_bad_email'] = $userrecord['user_bad_email'] == 0 ? 0 : time();
		// reset the user status and the expiry date
		$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0', user_ban_expire='0', user_bad_email = '".$userrecord['user_bad_email']."' WHERE user_id='".$userrecord['user_id']."'");
		$userrecord['user_status'] = 0;
	}
	if ($userrecord['user_status'] == 0) {	
		header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		// set the 'remember me' status value 
		$_SESSION['remember_me'] = isset($_POST['remember_me']) ? "yes" : "no";
		$_SESSION['userinfo'] = $userrecord['user_id'].".".$userrecord['user_password'];
		// login expiry defined?
		if ($settings['login_expire']) {
			if (isset($_POST['remember_me']) && $_POST['remember_me'] == "yes") {
				$_SESSION['login_expire'] = time() + $settings['login_extended_expire'];
			} else {
				$_SESSION['login_expire'] = time() + $settings['login_expire'];
			}
		} else {
			$_SESSION['login_expire'] = mktime(0,0,0,1,1,2038);	// do not expire
		}
		return 4;
	} elseif ($userrecord['user_status'] == 1) {
		return 1;
	} elseif ($userrecord['user_status'] == 2) {
		return 2;
	} else {
		return 0;
	}
}


/*---------------------------------------------------+
| Main code                                          |
+----------------------------------------------------*/

// temp storage for template variables
$variables = array();

// array to store the lines of the setuser message
$message = array();

// make sure the error variable has a value
if (!isset($error) || !isNum($error)) $error = 0;

// set the redirect url (set in theme_cleanup), butnot when in maintenance!
if (isset($_SERVER['HTTP_REFERER']) && eregi("maintenance.php", $_SERVER['HTTP_REFERER'])) {
	$variables['url'] = BASEDIR."index.php";
} elseif (isset($_SESSION['last_url'])) {
	$variables['url'] = $_SESSION['last_url'];
} elseif (empty($_SERVER['HTTP_REFERER'])) {
	$variables['url'] = BASEDIR."index.php";
} else {
	$variables['url'] = substr(strstr($_SERVER['HTTP_REFERER'], ":"), strlen($_SERVER['HTTP_HOST'])+3);
}

if (isset($_GET['logout']) && $_GET['logout'] == "yes") {

	// process the logout request

	header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
	// make sure the user info is erased from the session
	unset($_SESSION['user']);
	unset($_SESSION['userinfo']);
	unset($_SESSION['login_expire']);
	$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_ip='".USER_IP."'");
	if (isset($userdata['user_name'])) {
		$message['line2'] =  "<b>".$locale['192'].$userdata['user_name']."</b>";
	}

} elseif (isset($_GET['login']) && $_GET['login'] == "yes") {

	// process the login request
	$auth_methods = isset($settings['auth_type']) ? explode(",",$settings['auth_type'].",") : array('local');
	foreach($auth_methods as $auth_method) {
		switch($auth_method) {
			case "local":
				// authentication against the local user database
				if (!empty($_POST['user_name']) && !empty($_POST['user_pass'])) {
					$error = auth_local($_POST['user_name'], $_POST['user_pass']);
				}
				break;
			case "ldap":
				break;
			case "ad":
				break;
			case "openid":
				// authentication against an openid provider
				if (!empty($_POST['user_openid_url'])) {
					$error = auth_openid($_POST['user_openid_url']);
				}
				break;
			case "default":
				// empty or unknown entry, ignore
				break;
		}
	}

} else {

	if (isset($_GET['openid_mode'])) {
		// handle openid login
		require_once(PATH_INCLUDES."class.openid.php");
		$openid = new SimpleOpenID;
		$openid->SetIdentity(urldecode($_GET['openid_identity']));
		if ($openid->ValidateWithServer()) {
			$openid_url = strtolower($openid->OpenID_Standarize($_GET['openid_identity']));
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_openid_url='".$openid_url."'");
			if (dbrows($result) != 0) {
				// found, get the record and do some more validation
				$error = auth_user_validate(dbarray($result));
			} else {
				$message['line2'] =  "<b>".$locale['196']."</b>";
			}
		} else {
			trigger_error($openid->GetError());
			exit;
		}
	}

}

// check the result of the authentication attempt, and process it
switch($error) {
	case 0:
		// 
		$refresh = 1;
		break;
	case 1:
		$message['line1'] = "<b>".$locale['194']."</b>";
		$data = dbarray(dbquery("SELECT user_ban_reason, user_ban_expire FROM ".$db_prefix."users WHERE user_id='$user_id'"));
		if (is_array($data)) {
			if ($data['user_ban_reason'] != "") $message['line2'] = "<b>".$locale['180']." : ".$data['user_ban_reason']."</b>";
			if ($data['user_ban_expire'] > 0) $message['line4']  = "<b>".$locale['181']." ".showdate('forumdate', $data['user_ban_expire'])."</b>";
		}
		$refresh = 10;
		break;
	case 2:
		$message['line2'] =  "<b>".$locale['195']."</b>";
		$refresh = 10;
		break;
	case 3:
		$message['line2'] =  "<b>".$locale['196']."</b>";
		$refresh = 10;
		break;
	case 4:
		if (isset($_SESSION['userinfo'])) {
			// now that we have user info, finish the login validation
			$userinfo_vars = explode(".", $_SESSION['userinfo']);
			$user_pass = (preg_match("/^[0-9a-z]{32}$/", $userinfo_vars['1']) ? $userinfo_vars['1'] : "");
			$user_id = isNum($userinfo_vars['0']) ? $userinfo_vars['0'] : "0";
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$user_id."' AND user_password='".$user_pass."'");
			if ($data = dbarray($result)) {
				if ($data['user_bad_email'] != 0) {
					$variables['url'] = BASEDIR."edit_profile.php?check=email&value=".(90 - intval((time() - $data['user_bad_email']) / 86400));
				}
				$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_user='0' AND online_ip='".USER_IP."'");
				$message['line2'] =  "<b>".$locale['193'].$data['user_name']."</b>";
				$refresh = 1;
			} else {
				$message['line2'] =  "<b>".$locale['196']."</b>";
				$refresh = 10;
			}
		} else {
			$message['line2'] =  "<b>SESSION ERROR. Please report this to the Webmaster</b>";
			$refresh = 99999;
		}
		break;
	case 5:
		$message['line2'] =  "<b>".$locale['https']."</b>";
		$refresh = 99999;
		break;
	default:
		// unknown result code
		_debug($error);
		terminate("unknown result code from an authentication module!");
		break;
}

// store the message for use in the template
$variables['message'] = $message;

// auto-redirect counter (in seconds)
$variables['error'] = $error;
$variables['refresh'] = isset($refresh) ? $refresh : 10;

// define the first body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'setuser', 'template' => 'main.setuser.tpl');
$template_variables['setuser'] = $variables;

// make sure updates to session variables are written
session_write_close();

load_templates('body', '');

// and clean up
theme_cleanup();
?>
