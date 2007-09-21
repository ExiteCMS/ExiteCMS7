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

if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == "yes") {
	header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
	setcookie("user", "", time() - 7200, "/", "", "0");
	setcookie("userinfo", "", time() - 7200, "/", "", "0");
	setcookie("lastvisit", "", time() - 7200, "/", "", "0");
	$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_ip='".USER_IP."'");
	if (isset($userdata['user_name'])) $message = "<b>".$locale['192'].$userdata['user_name']."</b><br /><br />\n";
	$error = "";
} else {
	if (!isset($error)) $error = "";
	if ($error == 1) {
		$error = "<b>".$locale['194']."</b><br /><br />\n";
		$data = dbarray(dbquery("SELECT user_ban_reason, user_ban_expire FROM ".$db_prefix."users WHERE user_id='$user_id'"));
		if (is_array($data)) {
			if ($data['user_ban_reason'] != "") $error .= "<b>".$locale['180']." : ".$data['user_ban_reason']."</b><br />\n";
			if ($data['user_ban_expire'] > 0) $error .= "<b>".$locale['181']." ".showdate('forumdate', $data['user_ban_expire'])."</b><br />\n";
			$error .= "<br / >";
		}
	} elseif ($error == 2) {
		$error = "<b>".$locale['195']."</b><br /><br />\n";
	} elseif ($error == 3) {
		$error = "<b>".$locale['196']."</b><br /><br />\n";
	} else {
		if (isset($_COOKIE['userinfo'])) {
			$cookie_vars = explode(".", $_COOKIE['userinfo']);
			$user_pass = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
			$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($user));
			if (!dbcount("(user_id)", "users", "user_name='$user_name' AND user_password='$user_pass'")) {
				$message = "<b>".$locale['196']."</b><br /><br />\n";
			} else {
				$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_user='0' AND online_ip='".USER_IP."'");
				$message = "<b>".$locale['193'].$user."</b><br /><br />\n";
			}
		}
	}
}

$variables['message'] = isset($message)?$message:"";
$variables['error'] = $error;
$variables['refresh'] = $error==""?1:10;

// define the first body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'setuser', 'template' => 'main.setuser.tpl');
$template_variables['setuser'] = $variables;

load_templates('body', '');

// close the database connection
mysql_close();

// and flush any output remaining
ob_end_flush();
?>