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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.LOCALESET."admin/main.php";

// temp storage for template variables
$variables = array();

// flag whether or not to show images or only links
$variables['admin_images'] = true;

// make sure only admins with sufficient rights have access to this module
if (!iADMIN || $userdata['user_rights'] == "" || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// if no admin section is requested, go to content management
if (!isset($pagenum) || !isNum($pagenum)) $pagenum = 1;
$variables['pagenum'] = $pagenum;

// Find out which panels and pages the admin can access
$usr_rghts = " (admin_rights='".str_replace(".", "' OR admin_rights='", $userdata['user_rights'])."')";
$page1 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='1'");
$variables['adminpage1'] = $page1;
$page2 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='2'");
$variables['adminpage2'] = $page2;
$page3 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='3'");
$variables['adminpage3'] = $page3;
$page4 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='4'");
$variables['adminpage4'] = $page4;

// Work out which tab is the active default
if ($page1) { $default = 1; }
elseif ($page2) { $default = 2; }
elseif ($page3) { $default = 3; }
elseif ($page4) { $default = 4; }
else { fallback(BASEDIR."index.php"); }

// Ensure the admin is allowed to access the selected page
$pageon = true;
if ($pagenum == 1 && !$page1) $pageon = false;
if ($pagenum == 2 && !$page2) $pageon = false;
if ($pagenum == 3 && !$page3) $pageon = false;
if ($pagenum == 4 && !$page4) $pageon = false;
if ($pageon == false) redirect("index.php".$aidlink."&pagenum=$default");

// get the available admin modules for this page
$modules = array();
$result = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_page='$pagenum' ORDER BY admin_title");
$rows = dbrows($result);
if ($rows != 0) {
	while ($data = dbarray($result)) {
		if (checkrights($data['admin_rights']) && $data['admin_link'] != "reserved") {
			$modules[] = $data;
		}
	}
}
$variables['rows'] = $rows;
$variables['modules'] = $modules;

// gather some website statistics
$variables['statistics'] = array();

$variables['statistics']['members_registered'] = dbcount("(user_id)", "users", "user_status<='1'");
$variables['statistics']['members_unactive'] = dbcount("(user_id)", "users", "user_status='2'");
$variables['statistics']['members_banned'] = dbcount("(user_id)", "users", "user_status='1'");
$variables['statistics']['messages_unread'] = dbcount("(post_id)", "posts_unread");
$variables['statistics']['comments'] = dbcount("(comment_id)", "comments");
$variables['statistics']['shouts'] = dbcount("(shout_id)", "shoutbox");
$variables['statistics']['posts'] = dbcount("(post_id)", "posts");

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'admin.index', 'template' => 'admin.index.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/main.php");
$template_variables['admin.index'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>