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
locale_load("admin.admins");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("AD") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// process the 'remove an admin' request
if (isset($remove)) {
	if (isNum($remove) && $remove != "1") {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='101', user_rights='' WHERE user_id='$remove' AND user_level>='102'");
	}
	redirect(FUSION_SELF.$aidlink);
}

// process the 'update an admin' request
if (isset($_POST['update_admin'])) {
	if (!isNum($user_id) || $user_id == "1") fallback(FUSION_SELF.$aidlink);
	// any rights assigned to this member?
	if (isset($_POST['rights'])) {
		$user_rights = "";
		for ($i = 0;$i < count($_POST['rights']);$i++) {
			$user_rights .= stripinput($_POST['rights'][$i]);
			if ($i != (count($_POST['rights'])-1)) $user_rights .= ".";
		}
		// update the user record with the new rights
		$result = dbquery("UPDATE ".$db_prefix."users SET user_rights='$user_rights' WHERE user_id='$user_id'");
	} else {
		// no rights assigned to this member, remove them from the user record
		$result = dbquery("UPDATE ".$db_prefix."users SET user_rights='' WHERE user_id='$user_id'");
		// and if the member was not a webmaster, remove the admin status also
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='101' WHERE user_id='$user_id' AND user_level='102'");
	}
	if ($user_rights != "") {
		// rights were assigned. upgrade this user to administrator
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='102' WHERE user_id='$user_id' AND user_level < '102'");
	}
	redirect(FUSION_SELF.$aidlink);
}
// process the 'edit admin rights' request
if (isset($_POST['edit_rights']) || (isset($edit))) {
	// get the userid for either the post form (edit button) or the URL (edit link)
	$user_id = (isset($_POST['user_id']) && isNum($_POST['user_id'])) ? $_POST['user_id'] : (isNum($edit) ? $edit : "0");
	// check if we want to make this member a webmaster
	$user_level = isset($_POST['make_super']) ? "103" : "102";
	// if we want to assign this member all available rights, do so
	if ($user_id && (isset($_POST['all_rights']) || isset($_POST['make_super']))) {
		$result = dbquery("SELECT admin_rights FROM ".$db_prefix."admin");
		$adminrights = "";
		while ($data = dbarray($result)) {
			$adminrights .= ($adminrights == "" ? "" : ".") . $data['admin_rights']; 
		}
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='$user_level', user_rights='".$adminrights."' WHERE user_id='$user_id' AND user_level < '$user_level'");
		redirect(FUSION_SELF.$aidlink);
	}
	// if not, load the data needed for the edit rights panel
}

// get the list of members with administrator or webmaster level
$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level>='102' AND user_status = '0' ORDER BY user_level DESC, user_name");
$variables['admins'] = array();
while ($data = dbarray($result)) {
	$data['user_rights'] = $data['user_rights'] ? str_replace(".", " ", $data['user_rights']) : "".$locale['405'];
	$data['user_level'] = getuserlevel($data['user_level']);
	if ($data['user_id'] == "1" || $data['user_id'] == $userdata['user_id']) { 
		// no editing of the webmaster or the members own rights
		$data['can_edit'] = false;
	} elseif ($data['user_level'] != "103") {
		// admins can always be edited
		$data['can_edit'] = true;
	} else { 
		// catch-all, no editing possible!
		$data['can_edit'] = false; 
	}
	$variables['admins'][] = $data;
}

// get the list of all members (remove the user himself from the list!)
$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_status = '0' ORDER BY user_name");
$variables['users'] = array();
while ($data = dbarray($result)) {
	if ($data['user_id'] != $userdata['user_id']) $variables['users'][] = $data;
}

// get information for the edit admin panel
$variables['show_edit_panel'] = isset($user_id);
if (isset($user_id)) {
	$variables['edit'] = $user_id;
	// get the user record for the admin whose's rights we're going to modify
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$user_id' ORDER BY user_id");
	if (dbrows($result)) {
		// found, define the variables for the template
		$data = dbarray($result);
		$user_rights = explode(".", $data['user_rights']);
		$variables['admin'] = $data;
		// get the available admin functions
		$admin_pages = array($locale['421'],$locale['422'],$locale['423'], $locale['427']);
		$variables['modules'] = array();
		$result = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_link !='reserved' ORDER BY admin_page ASC,admin_title");
		while ($data = dbarray($result)) {
			$data['page_name'] = $admin_pages[$data['admin_page']-1];
			$data['assigned'] = in_array($data['admin_rights'], $user_rights);
			// check if the module name is localized
			if (isNum($data['admin_title'])) {
				// get the localised name from the locales table
				$result2 = dbquery("SELECT * FROM ".$db_prefix."locales WHERE locales_code = '".$settings['locale_code']."' and locales_name = 'admin.main' and locales_key = '".$data['admin_title']."'");
				if (dbrows($result2)) {
					$data2 = dbarray($result2);
					$data['admin_title'] = $data2['locales_value'];
				}
			}
			$variables['modules'][] = $data;
		}
	} else {
		// not found, no variables available for the template
		$variables['admin'] = array();
	}
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.administrators', 'template' => 'admin.administrators.tpl', 'locale' => "admin.admins");
$template_variables['admin.administrators'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>