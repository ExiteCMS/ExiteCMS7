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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once dirname(__FILE__)."/includes/theme_functions.php";

// temp storage for template variables
$variables = array();

// set the redirect url (set in theme_cleanup)
if (isset($_COOKIE['last_url'])) {
	$variables['url'] = $_COOKIE['last_url'];
} elseif (empty($_SERVER['HTTP_REFERER'])) {
	$variables['url'] = BASEDIR."index.php";
} else {
	$variables['url'] = substr(strstr($_SERVER['HTTP_REFERER'], ":"), strlen($_SERVER['HTTP_HOST'])+3);
}

// array to store the lines of the setuser message
$message = array();

// make sure the error parameter has a value
if (!isset($error) || !isNum($error)) $error = 0;

if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == "yes") {
	header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
	setcookie("user", "", time() - 7200, "/", "", "0");
	setcookie("userinfo", "", time() - 7200, "/", "", "0");
	setcookie("lastvisit", "", time() - 7200, "/", "", "0");
	$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_ip='".USER_IP."'");
	if (isset($userdata['user_name'])) {
		$message['line2'] =  "<b>".$locale['192'].$userdata['user_name']."</b>";
	}
} else {
	if ($error == 1) {
		$message['line1'] = "<b>".$locale['194']."</b>";
		$data = dbarray(dbquery("SELECT user_ban_reason, user_ban_expire FROM ".$db_prefix."users WHERE user_id='$user_id'"));
		if (is_array($data)) {
			if ($data['user_ban_reason'] != "") $message['line2'] = "<b>".$locale['180']." : ".$data['user_ban_reason']."</b>";
			if ($data['user_ban_expire'] > 0) $message['line4']  = "<b>".$locale['181']." ".showdate('forumdate', $data['user_ban_expire'])."</b>";
		}
	} elseif ($error == 2) {
		$message['line2'] =  "<b>".$locale['195']."</b>";
	} elseif ($error == 3) {
		$message['line2'] =  "<b>".$locale['196']."</b>";
	} else {
		if (isset($_COOKIE['userinfo'])) {
			$cookie_vars = explode(".", $_COOKIE['userinfo']);
			$user_pass = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
			$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($user));
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='".$user_name."' AND user_password='".$user_pass."'");
			if ($data = dbarray($result)) {
				if ($data['user_bad_email'] != 0) {
					$variables['url'] = BASEDIR."edit_profile.php?check=email&value=".(90 - intval((time() - $data['user_bad_email']) / 86400));
				}
				$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_user='0' AND online_ip='".USER_IP."'");
				$message['line2'] =  "<b>".$locale['193'].$user."</b>";
			} else {
				$message['line2'] =  "<b>".$locale['196']."</b>";
			}
		}
	}
}

// store the message for use in the template
$variables['message'] = $message;

// auto-redirect counter (in seconds)
$variables['error'] = $error;
$variables['refresh'] = $error==0 ? 1 : 10;

// define the first body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'setuser', 'template' => 'main.setuser.tpl');
$template_variables['setuser'] = $variables;

load_templates('body', '');

// close the database connection
mysql_close();

// and flush any output remaining
ob_end_flush();
?>