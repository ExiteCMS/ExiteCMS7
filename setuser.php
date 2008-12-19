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

// temp storage for template variables
$variables = array();

// array to store the lines of the setuser message
$message = array();

// set the P3P header				
header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");

// make sure the error variable has a value
if (!isset($error) || !isNum($error)) $error = 0;
// set the redirect url (set in theme_cleanup), but not when in maintenance!
if (isset($_SERVER['HTTP_REFERER']) && eregi("maintenance.php", $_SERVER['HTTP_REFERER'])) {
	$variables['url'] = BASEDIR."index.php";
} elseif (isset($_SESSION['last_url']) &&  !eregi("setuser.php", $_SESSION['last_url'])) {
	$variables['url'] = $_SESSION['last_url'];
} elseif (empty($_SERVER['HTTP_REFERER'])) {
	$variables['url'] = BASEDIR."index.php";
} else {
	$variables['url'] = substr(strstr($_SERVER['HTTP_REFERER'], ":"), strlen($_SERVER['HTTP_HOST'])+3);
}

if (isset($_GET['logout']) && $_GET['logout'] == "yes") {

	// process the logout request
	$cms_authentication->logoff();

	// copy the clientside datastore to the session
	if (!empty($userdata['user_datastore']['clientside'])) {
		$_SESSION['clientside'] = $userdata['user_datastore']['clientside'];
	}

	// make sure the user info is erased from the session
	if (isset($userdata['user_name'])) {
		$message['line2'] =  "<b>".$locale['192'].$userdata['user_name']."</b>";
	}

} elseif (isset($_GET['login']) && $_GET['login'] == "yes") {

	// store any login parameters to be passed
	$params = array();
	if (!empty($_POST['user_name'])) {
		$params['username'] = stripinput($_POST['user_name']);
	}
	if (!empty($_POST['user_pass'])) {
		$params['password'] = stripinput($_POST['user_pass']);
	}
	if (!empty($_POST['user_openid_url']) && isURL($_POST['user_openid_url'])) {
		$params['openid_url'] = stripinput($_POST['user_openid_url']);
	}

	// process the logon request
	if ($cms_authentication->logon($params)) {
		// get the logon status
		$error = $cms_authentication->status;
	} else {
		$error = 3;	// // credentials not correct
	}

} elseif (isset($_GET['openid_mode'])) {

	// store any login parameters to be passed
	$params = array();

	if (!empty($_GET['openid_mode'])) {
		$params['openid_mode'] = stripinput($_GET['openid_mode']);
	}

	// process the openid logon request
	if ($cms_authentication->logon($params)) {
		$error = $cms_authentication->status;
	} else {
		$error = 3;	// credentials not correct
	}

}

// check the result of the authentication attempt, and process it
switch($error) {
	case 0:	// no errors
		// 
		$refresh = 1;
		break;
	case 1: // account is suspended
		$message['line1'] = "<b>".$locale['194']."</b>";
		$data = dbarray(dbquery("SELECT user_ban_reason, user_ban_expire FROM ".$db_prefix."users WHERE user_id='".$user_id."'"));
		if (is_array($data)) {
			if ($data['user_ban_reason'] != "") $message['line2'] = "<b>".$locale['180']." : ".$data['user_ban_reason']."</b>";
			if ($data['user_ban_expire'] > 0) $message['line4']  = "<b>".$locale['181']." ".showdate('forumdate', $data['user_ban_expire'])."</b>";
		}
		$refresh = 10;
		break;
	case 2:	// account not activated (yet)
		$message['line2'] =  "<b>".$locale['195']."</b>";
		$refresh = 10;
		break;
	case 3:	// credentials not correct
		$message['line2'] =  "<b>".$locale['196']."</b>";
		$refresh = 10;
		break;
	case 4:	// successful logon
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
	case 5:	// logon requires https
		$message['line2'] =  "<b>".$locale['https']."</b>";
		$refresh = 99999;
		break;
	case 6:	// user is banned
		$message['line2'] =  "<font style='color:red;font-weight:bold'>".($locale['banned'])."</font>";
		// get the reason for this ban
		$sub_ip1 = substr(USER_IP,0,strlen(USER_IP)-strlen(strrchr(USER_IP,".")));
		$sub_ip2 = substr($sub_ip1,0,strlen($sub_ip1)-strlen(strrchr($sub_ip1,".")));
		$result = dbquery("SELECT * FROM ".$db_prefix."blacklist WHERE blacklist_ip='".USER_IP."' OR blacklist_ip='$sub_ip1' OR blacklist_ip='$sub_ip2'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$message['line4'] =  "<b>".$locale['180'].":<br /><font style='color:red;'>".($data['blacklist_reason'])."</font></b>";
		}
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
