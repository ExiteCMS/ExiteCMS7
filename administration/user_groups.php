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

// temp storage for template variables
$variables = array();

//load the locale for this module
locale_load("admin.user_groups");

//check if the user has a right to be here. If not, bail out
if (!checkrights("UG") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// parameter validation
if (isset($group_id) && !isNum($group_id)) fallback(FUSION_SELF.$aidlink);

// binary display type array
$displaytypes = array(
	array ('bit' => 1, 'type' => $locale['451']),
	array ('bit' => 2, 'type' => $locale['452'])
);
	
if (isset($status)) {
	if ($status == "su") {
		$title = $locale['400'];
		$variables['message'] = $locale['401'];
	} elseif ($status == "sn") {
		$title = $locale['402'];
		$variables['message'] = $locale['403'];
	} elseif ($status == "sr") {
		$title = $locale['414'];
		$variables['message'] = $locale['415'];
	} elseif ($status == "addallu") {
		$title = $locale['404'];
		$variables['message'] = $locale['405'];
	} elseif ($status == "remallu") {
		$title = $locale['404'];
		$variables['message'] = $locale['406'];
	} elseif ($status == "addallg") {
		$title = $locale['416'];
		$variables['message'] = $locale['428'];
	} elseif ($status == "remallg") {
		$title = $locale['416'];
		$variables['message'] = $locale['429'];
	} elseif ($status == "selu") {
		$title = $locale['404'];
		$variables['message'] = $locale['407'];
	} elseif ($status == "selg") {
		$title = $locale['416'];
		$variables['message'] = $locale['424'];
	} elseif ($status == "deln") {
		$title = $locale['408'];
		$variables['message'] = $locale['409']."<br />".$locale['410'];
	} elseif ($status == "dely") {
		$title = $locale['408'];
		$variables['message'] = $locale['411'];
	} elseif ($status == "dels") {
		$title = $locale['408'];
		$variables['message'] = $locale['419'];
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.user_groups.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => "admin.user_groups");
	$template_variables['admin.user_groups.status'] = $variables;
	$variables = array();
}

if (isset($_POST['update_admin'])) {

	if (isset($_POST['rights'])) {
		$group_rights = "";
		for ($i = 0;$i < count($_POST['rights']);$i++) {
			$group_rights .= stripinput($_POST['rights'][$i]);
			if ($i != (count($_POST['rights'])-1)) $group_rights .= ".";
		}
		$result = dbquery("UPDATE ".$db_prefix."user_groups SET group_rights='$group_rights' WHERE group_id='$group_id'");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."user_groups SET group_rights='' WHERE group_id='$group_id'");
	}
	redirect(FUSION_SELF.$aidlink."&status=sr");

} elseif (isset($_POST['save_group'])) {

	$group_name = stripinput($_POST['group_name']);
	$group_description = stripinput($_POST['group_description']);
	$group_forumname = stripinput($_POST['group_forumname']);
	$group_color = $_POST['group_color'];
	$group_displaytype = $_POST['displaytype'];
	$group_visible = 0;
	foreach ($group_displaytype as $idx => $displaytype) {
		$group_visible += $displaytype == 1 ? pow(2, $displaytypes[$idx]['bit']-1) : 0;
	}
	if (isset($group_id)) {
		$result = dbquery("UPDATE ".$db_prefix."user_groups SET group_name='$group_name', group_description='$group_description', group_forumname='$group_forumname', group_color='$group_color', group_visible='$group_visible' WHERE group_id='$group_id'");
		redirect(FUSION_SELF.$aidlink."&status=su");
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."user_groups (group_name, group_description, group_forumname, group_color, group_visible) VALUES ('$group_name', '$group_description', '$group_forumname', '$group_color', '$group_visible')");
		redirect(FUSION_SELF.$aidlink."&status=sn");
	}

} elseif (isset($_POST['add_all_users'])) {

	$group_id = $_POST['group_id'];
	$result = dbquery("SELECT user_id,user_name,user_groups FROM ".$db_prefix."users WHERE user_status = '0'");
	while ($data = dbarray($result)) {
  		if (!preg_match("(^\.{$group_id}|\.{$group_id}\.|\.{$group_id}$)", $data['user_groups'])) {
			$user_groups = $data['user_groups'].".".$group_id;
			$result2 = dbquery("UPDATE ".$db_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
		}
	}
	redirect(FUSION_SELF.$aidlink."&status=addallu");

} elseif (isset($_POST['remove_all_users'])) {

	$group_id = $_POST['group_id'];
	$result = dbquery("SELECT user_id,user_name,user_groups FROM ".$db_prefix."users WHERE user_groups REGEXP('^\\\.{$group_id}$|\\\.{$group_id}\\\.|\\\.{$group_id}$')");
	while ($data = dbarray($result)) {
		$user_groups = $data['user_groups'];
		$user_groups = preg_replace(array("(^\.{$group_id}$)","(\.{$group_id}\.)","(\.{$group_id}$)"), array("",".",""), $user_groups);
		$result2 = dbquery("UPDATE ".$db_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
	}
	redirect(FUSION_SELF.$aidlink."&status=remallu");

} elseif (isset($_POST['add_all_groups'])) {

	$group_id = $_POST['group_id'];
	$result = dbquery("SELECT group_id,group_name,group_groups FROM ".$db_prefix."user_groups WHERE group_id != '$group_id'");
	while ($data = dbarray($result)) {
  		if (!preg_match("(^\.{$group_id}|\.{$group_id}\.|\.{$group_id}$)", $data['group_groups'])) {
			$group_groups = $data['group_groups'].".".$group_id;
			$result2 = dbquery("UPDATE ".$db_prefix."user_groups SET group_groups='$group_groups' WHERE group_id='".$data['group_id']."'");
		}
	}
	redirect(FUSION_SELF.$aidlink."&status=addallg");

} elseif (isset($_POST['remove_all_groups'])) {

	$group_id = $_POST['group_id'];
	$result = dbquery("SELECT group_id,group_name,group_groups FROM ".$db_prefix."user_groups WHERE group_groups REGEXP('^\\\.{$group_id}$|\\\.{$group_id}\\\.|\\\.{$group_id}$')");
	while ($data = dbarray($result)) {
		$group_groups = $data['group_groups'];
		$group_groups = preg_replace(array("(^\.{$group_id}$)","(\.{$group_id}\.)","(\.{$group_id}$)"), array("",".",""), $group_groups);
		$result2 = dbquery("UPDATE ".$db_prefix."user_groups SET group_groups='$group_groups' WHERE group_id='".$data['group_id']."'");
	}
	redirect(FUSION_SELF.$aidlink."&status=remallu");

} elseif (isset($_POST['save_selected_users'])) {

	$group_id = $_POST['group_id'];	$group_users = $_POST['group_users'];
	$result = dbquery("SELECT user_id,user_name,user_groups FROM ".$db_prefix."users");
	while ($data = dbarray($result)) {
		$user_id = $data['user_id'];
 		if (preg_match("(^{$user_id}$|^{$user_id}\.|\.{$user_id}\.|\.{$user_id}$)", $group_users)) {
			if (!preg_match("(^\.{$group_id}$|\.{$group_id}\.|\.{$group_id}$)", $data['user_groups'])) {
				$user_groups = $data['user_groups'].".".$group_id;
				$result2 = dbquery("UPDATE ".$db_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
			}
		} else {
		    if (preg_match("(^\.$group_id$|\.$group_id\.|\.$group_id$)", $data['user_groups'])) {
			$user_groups = $data['user_groups'];
			$user_groups = preg_replace(array("(^{$group_id}\.)","(\.{$group_id}\.)","(\.{$group_id}$)"), array("",".",""), $user_groups);
			$result2 = dbquery("UPDATE ".$db_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
		    }
		}
		unset($user_id);
	}
	redirect(FUSION_SELF.$aidlink."&status=selu");

} elseif (isset($_POST['save_selected_groups'])) {

	$group_id = $_POST['group_id'];	$group_groups = $_POST['group_groups'];
	$result = dbquery("SELECT group_id,group_name,group_groups FROM ".$db_prefix."user_groups WHERE group_id != '$group_id'");
	while ($data = dbarray($result)) {
		$this_group = $data['group_id'];
 		if (preg_match("(^{$this_group}$|^{$this_group}\.|\.{$this_group}\.|\.{$this_group}$)", $group_groups)) {
			if (!preg_match("(^\.{$group_id}$|\.{$group_id}\.|\.{$group_id}$)", $data['group_groups'])) {
				$these_groups = $data['group_groups'].".".$group_id;
				$result2 = dbquery("UPDATE ".$db_prefix."user_groups SET group_groups='$these_groups' WHERE group_id='".$data['group_id']."'");
			}
		} else {
		    if (preg_match("(^\.$group_id$|\.$group_id\.|\.$group_id$)", $data['group_groups'])) {
			$these_groups = $data['group_groups'];
			$these_groups = preg_replace(array("(^{$group_id}\.)","(\.{$group_id}\.)","(\.{$group_id}$)"), array("",".",""), $these_groups);
			$result2 = dbquery("UPDATE ".$db_prefix."user_groups SET group_groups='$these_groups' WHERE group_id='".$data['group_id']."'");
		    }
		}
	}
	redirect(FUSION_SELF.$aidlink."&status=selg");

} elseif (isset($_POST['delete'])) {

	$result = dbquery("SELECT group_ident FROM ".$db_prefix."user_groups WHERE group_id = '$group_id'");
	if ($result) {
		$data = dbarray($result);
		if ($data['group_ident'] != "") {
			redirect(FUSION_SELF.$aidlink."&status=dels");
		}
	}
	if (dbcount("(*)", "users", "user_groups REGEXP('^\\\.{$group_id}$|\\\.{$group_id}\\\.|\\\.{$group_id}$')") != 0) {
		redirect(FUSION_SELF.$aidlink."&status=deln");
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."user_groups WHERE group_id='$group_id'");
		redirect(FUSION_SELF.$aidlink."&status=dely");
	}

} else {

	$result = dbquery("SELECT * FROM ".$db_prefix."user_groups ORDER BY group_name");
	$variables['groups'] = array();
	while ($data = dbarray($result)) {
		$data['selected'] = (isset($group_id) && $group_id == $data['group_id']);
		$variables['groups'][] = $data;
	}

	if (isset($_POST['edit'])) {
		$action = "";
		$result = dbquery("SELECT * FROM ".$db_prefix."user_groups WHERE group_id='$group_id'");
		if (dbrows($result) == 0) fallback(FUSION_SELF.$aidlink);
		$data = dbarray($result);
		$group_name = $data['group_name'];
		$group_description = $data['group_description'];
		$group_forumname = $data['group_forumname'];
		$group_color = $data['group_color'];
		$group_visible = $data['group_visible'];
		$form_action = FUSION_SELF.$aidlink."&amp;group_id=$group_id";
		$title = $locale['430'];
	} else {
		$action = "";
		$group_id = 0;
		$group_name = "";
		$group_description = "";
		$group_forumname = "";
		$group_color = "";
		$group_visible = 0;
		$form_action = FUSION_SELF.$aidlink;
		$title = $locale['431'];
	}
	
	$variables['action'] = $action;
	$variables['form_action'] = $form_action;
	$variables['group_id'] = $group_id;
	$variables['group_name'] = $group_name;
	$variables['group_visible'] = $group_visible;
	$variables['group_description'] = $group_description;
	$variables['group_forumname'] = $group_forumname;
	$variables['group_color'] = $group_color;

	foreach ($displaytypes as $idx => $displaytype) {
		$displaytypes[$idx]['visible'] = (($group_visible & $displaytype['bit'])  != 0);
	}
	$variables['displaytypes'] = $displaytypes;
	
	if (isset($group_id)) {
		$variables['group1_users'] = array();
		$variables['group2_users'] = array();
		$result = dbquery("SELECT user_id,user_name,user_groups FROM ".$db_prefix."users WHERE user_status = '0' ORDER BY LOWER(user_name)");
		while ($data = dbarray($result)) {
  			if (!preg_match("(^\.{$group_id}$|\.{$group_id}\.|\.{$group_id}$)", $data['user_groups'])) {
				$variables['group1_users'][] = array('id' => $data['user_id'], 'name' => $data['user_name']);
			} else {
				$variables['group2_users'][] = array('id' => $data['user_id'], 'name' => $data['user_name']);
			}
		}
		$variables['group1_groups'] = array();
		$variables['group2_groups'] = array();
		$groups = getusergroups();
		foreach($groups as $group) {
			// skip the selected group
			if ($group[0] == $group_id) continue;
			// get the group groupmemberships
			$result = dbquery("SELECT group_groups FROM ".$db_prefix."user_groups WHERE group_id = '".$group[0]."'");
			if (dbrows($result)) {
				$data = dbarray($result);
	  			if (!preg_match("(^\.{$group_id}$|\.{$group_id}\.|\.{$group_id}$)", $data['group_groups'])) {
					$variables['group1_groups'][] = array('id' => $group[0], 'name' => $group[1]);
				} else {
					$variables['group2_groups'][] = array('id' => $group[0], 'name' => $group[1]);
				}
			} else {
				$variables['group1_groups'][] = array('id' => $group[0], 'name' => $group[1]);
			}
		}
	}

	// get information for the edit admin panel
	$variables['show_edit_panel'] = isset($edit);

	if (isset($edit)) {
		$variables['edit'] = $edit;
		// get the user record for the admin whose's rights we're going to modify
		$result = dbquery("SELECT * FROM ".$db_prefix."user_groups WHERE group_id='$group_id'");
		if (dbrows($result)) {
			// found, define the variables for the template
			$data = dbarray($result);
			$group_rights = explode(".", $data['group_rights']);
			$variables['admin'] = $data;
			// get the available admin functions
			$admin_pages = array($locale['441'],$locale['442'],$locale['443'], $locale['444']);
			$variables['modules'] = array();
			$result = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_link !='reserved' ORDER BY admin_page ASC,admin_title");
			while ($data = dbarray($result)) {
				$data['page_name'] = $admin_pages[$data['admin_page']-1];
				$data['assigned'] = in_array($data['admin_rights'], $group_rights);
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
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.user_groups', 'title' => $title, 'template' => 'admin.user_groups.tpl', 'locale' => "admin.user_groups");
$template_variables['admin.user_groups'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
