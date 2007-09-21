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
if (eregi("user_info_panel.php", $_SERVER['PHP_SELF']) || !defined('ExiteCMS_INIT')) die();

// array's to store the variables for this panel
$variables = array();

if (isset($userdata['user_id'])) {
	$variables['new_pm_msg'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id='".$userdata['user_id']."' AND pmindex_to_id='".$userdata['user_id']."' AND pmindex_read_datestamp = '0'");
	$variables['new_post_msg'] = dbcount("(post_id)", "posts_unread", "user_id='".$userdata['user_id']."'");
	$variables['user_id'] = isset($userdata['user_id'])?$userdata['user_id']:0;
	$variables['user_name'] = isset($userdata['user_name'])?$userdata['user_name']:"";
	if (iADMIN) {
		$usr_rghts = " (admin_rights='".str_replace(".", "' OR admin_rights='", $userdata['user_rights'])."')";
		$variables['adminpage1'] = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='1'");
		$variables['adminpage2'] = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='2'");
		$variables['adminpage3'] = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='3'");
		$variables['adminpage4'] = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='4'");
	}
}
$variables['loginerror'] = isset($loginerror) ? $loginerror : "";
$variables['remember_me'] = isset($_COOKIE['remember_me'])?$_COOKIE['remember_me']:"no";

$template_variables['modules.user_info_panel'] = $variables;
?>
