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
if (eregi("user_info_panel.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// array's to store the variables for this panel
$variables = array();

if (iMEMBER) {
	// store the user's ID and name
	$variables['user_id'] = isset($userdata['user_id'])?$userdata['user_id']:0;
	$variables['user_name'] = isset($userdata['user_name'])?$userdata['user_name']:"";
	// if an administrator, check to which pages this admin has access to
	if (iADMIN) {
		$usr_rghts = " (admin_rights='".str_replace(".", "' OR admin_rights='", $userdata['user_rights'])."')";
		$variables['adminpage1'] = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='1'");
		$variables['adminpage2'] = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='2'");
		$variables['adminpage3'] = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='3'");
		$variables['adminpage4'] = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='4'");
		$variables['adminpage5'] = checkrights("T");
	}
	// new PM messages
	$variables['new_pm_msg'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id ='".$userdata['user_id']."' AND pmindex_to_id='".$userdata['user_id']."' AND pmindex_read_datestamp = '0'");
	// new forum messages
	if ($userdata['user_posts_unread']) {
		$result = dbquery("
			SELECT count(*) as unread 
				FROM ".$db_prefix."posts p 
					LEFT JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
				WHERE tr.user_id = '".$userdata['user_id']."' 
					AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
					AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
						OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))"
			);
	} else {
		$result = dbquery("
			SELECT count(*) as unread 
				FROM ".$db_prefix."posts p 
					LEFT JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
				WHERE tr.user_id = '".$userdata['user_id']."' 
					AND p.post_author != '".$userdata['user_id']."'
					AND p.post_edituser != '".$userdata['user_id']."'
					AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
					AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
						OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))"
			);
	} 
	$variables['new_post_msg'] = ($result ? mysql_result($result, 0) : 0);
	// check if the forum_threads_list_panel module is installed
	$result = dbquery("SELECT * FROM ".$db_prefix."modules WHERE mod_folder = 'forum_threads_list_panel'");
	$variables['new_posts_panel'] = dbrows($result);
}
$variables['loginerror'] = isset($loginerror) ? $loginerror : "";
$variables['remember_me'] = isset($_SESSION['remember_me']) ? $_SESSION['remember_me'] : "no";
$variables['login_expiry']  = (iADMIN && isset($_SESSION['login_expire'])) ? $_SESSION['login_expire'] : "";

$template_variables['modules.user_info_panel'] = $variables;
?>
