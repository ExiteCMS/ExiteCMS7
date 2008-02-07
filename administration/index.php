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
locale_load("admin.main");

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
$moduleindex = array();
$result = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_page='$pagenum'");
$rows = dbrows($result);
if ($rows != 0) {
	while ($data = dbarray($result)) {
		if (checkrights($data['admin_rights']) && $data['admin_link'] != "reserved") {
			// check the location and the existence of the admin image, and pass the full path to the template
			$path = explode("/", $data['admin_link']);
			if (isset($path[2]) && file_exists(PATH_MODULES.$path[2]."/images/".$data['admin_image'])) {
				$data['admin_image'] = MODULES.$path[2]."/images/".$data['admin_image'];
			} else {
				if (file_exists(PATH_ADMIN."images/".$data['admin_image'])) {
					$data['admin_image'] = ADMIN."images/".$data['admin_image'];
				} else {
					$data['admin_image'] = ADMIN."images/module_panel.gif";
				}
			}
			// check if the module name is localized
			if (isNum($data['admin_title'])) {
				// get the localised name from the locales table
				$result2 = dbquery("SELECT * FROM ".$db_prefix."locales WHERE locales_code = '".$settings['locale_code']."' and locales_name = 'admin.main' and locales_key = '".$data['admin_title']."'");
				if (dbrows($result2)) {
					$data2 = dbarray($result2);
					$data['admin_title'] = $data2['locales_value'];
				}
			}
			// store the module record and the index record
			$modules[] = $data;
			$moduleindex[] = $data['admin_title']."_>_".(count($modules)-1);
		}
	}
}
$variables['rows'] = $rows;

//make sure the modules are properly sorted
sort($moduleindex);
$variables['modules'] = array();
foreach($moduleindex as $index) {
	$variables['modules'][] = $modules[substr(strstr($index,"_>_"),3)];
}

// gather some website statistics
$variables['statistics'] = array();

$variables['statistics']['members_registered'] = dbcount("(user_id)", "users", "user_status<='1'");
$variables['statistics']['members_unactive'] = dbcount("(user_id)", "users", "user_status='2'");
$variables['statistics']['members_suspended'] = dbcount("(user_id)", "users", "user_status='1'");
$variables['statistics']['members_deleted'] = dbcount("(user_id)", "users", "user_status='3'");
$result = dbquery("SELECT count(*) as unread, sum(tr.thread_page) AS pages FROM ".$db_prefix."posts p LEFT JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id WHERE (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].") AND (p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)", false);
$variables['statistics']['messages_unread'] = ($result ? mysql_result($result, 0) : 0);
$variables['statistics']['comments'] = dbcount("(comment_id)", "comments");
$variables['statistics']['shouts'] = dbcount("(shout_id)", "shoutbox");
$variables['statistics']['posts'] = dbcount("(post_id)", "posts");

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'admin.index', 'template' => 'admin.index.tpl', 'locale' => "admin.main");
$template_variables['admin.index'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>