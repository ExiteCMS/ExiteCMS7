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

// load the locale for this module
locale_load("admin.panels");

// temp storage for template variables
$variables = array();

// allow panels to move columns? (side to center and back?)
define('PANEL_SIDE_MOVE', true);

// check for the proper admin access rights
if (!checkrights("P") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameters are valid
if (isset($panel_id) && !isNum($panel_id)) fallback(ADMIN."panels.php".$aidlink);

// panel_locale
switch($settings['panels_localisation']) {
	case "multiple":
		if (!isset($panel_locale)) {
			$panel_locale = $settings['locale_code'];
		} else {
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".$panel_locale."' AND locale_active = '1' LIMIT 1");
			if (dbrows($result)==0) {
				$panel_locale = $settings['locale_code'];
			}
		}
		$where = " panel_locale = '".$panel_locale."' ";
		break;
	case "none":
	case "single":
	default:
		$panel_locale = "";
		$where = "";
		break;
}

// scan all installed modules and make a list of available panels
$panel_list = array();
$current_folder = "";
$result = dbquery("SELECT mod_folder FROM ".$db_prefix."modules ORDER BY mod_folder");
while ($data = dbarray($result)) {
	// get the list of panel php files in the module directory
	foreach(glob(PATH_MODULES.$data['mod_folder']."/*_panel.php") as $panelname) {
		if ($data['mod_folder'] != $current_folder) {
			$data['new_module'] = true;
			$current_folder = $data['mod_folder'];
		} else {
			$data['new_module'] = false;
		}
		$data['selected'] = false;
		$data['panel_name'] = basename($panelname, ".php");
		$data['panel_filename'] = $data['mod_folder']."/".$data['panel_name'].".php";
		$panel_list[] = $data;
	}
}
array_unshift($panel_list, array('mod_folder' => "", 'new_module' => true, 'panel_name' => "", 'panel_filename' => "", 'selected' => false));

if (isset($_POST['save'])) {
	$error = "";
	$panel_usermod = $_POST['panel_usermod'];
	$panel_locale = $_POST['panel_locale'];
	$panel_name = stripinput($_POST['panel_name']);
	if ($panel_name == "") $error .= $locale['470']."<br>";
	if ($_POST['panel_filename'] == "") {
		$panel_filename = "";
		$panel_code = addslash($_POST['panel_code']);
		$panel_template = addslash($_POST['panel_template']);
		$panel_type = "dynamic";
	} else {
		$panel_filename = stripinput($_POST['panel_filename']);
		$panel_type = "file";
		$panel_code = "";
		$panel_template = "";
	}
	$panel_side = isNum($_POST['panel_side']) ? $_POST['panel_side'] : "1";
	$panel_access = isNum($_POST['panel_access']) ? $_POST['panel_access'] : "0";
	$panel_state = isNum($_POST['panel_state']) ? $_POST['panel_state'] : "0";
	// display-on-all-pages only possible for body panels
	if ($panel_side != "2" && $panel_side != "3") {
		$panel_display = "0";
	} else {
		$panel_display = isset($_POST['panel_display']) ? "1" : "0";
	}

	if (isset($panel_id)) {
		if ($panel_name != "") {
			$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id='$panel_id'"));
			if (strtolower($panel_name) != strtolower($data['panel_name'])) {
				$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_name='$panel_name'".($where!=""?(" AND ".$where):""));
				if (dbrows($result) != 0) $error .= $locale['471']."<br>";
			}
		}
		if ($panel_type == "dynamic" && $panel_template == "") $error .= $locale['472']."<br>";
		if ($error == "") {
			$result = dbquery("UPDATE ".$db_prefix."panels SET panel_name='$panel_name', panel_locale='$panel_locale', panel_filename='$panel_filename', panel_code='$panel_code', panel_template='$panel_template', panel_access='$panel_access', panel_display='$panel_display', panel_side='$panel_side', panel_usermod = '$panel_usermod', panel_state = '$panel_state', panel_datestamp = '".time()."' WHERE panel_id='$panel_id'");
		}
		// define the message panel variables
		if ($error != "") {
			$variables['message'] = $locale['481']."<br /><br />".$error;
		} else {
			$variables['message'] = $locale['482'];
		}
		$variables['bold'] = true;
		$variables['link'] = "panels.php".$aidlink.($panel_locale!=""?("&panel_locale=".$panel_locale):"");
		$variables['linktext'] = $locale['486'];
		$template_panels[] = array('type' => 'body', 'name' => 'admin.panel_editor.status', 'title' => $locale['480'], 'template' => '_message_table_panel.tpl');
		$template_variables['admin.panel_editor.status'] = $variables;
		$variables = array();

	} else {

		if ($panel_name != "") {
			$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_name='$panel_name'".($where!=""?(" AND ".$where):""));
			if (dbrows($result) != 0) $error .= $locale['471']."<br>";
		}
		if ($panel_type == "dynamic" && $panel_template == "") $error .= $locale['472']."<br>";
		if ($panel_type == "file" && $panel_filename == "") $error .= $locale['473']."<br>";
		if ($error == "") {
			$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$panel_side'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order DESC LIMIT 1");
			if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_locale, panel_filename, panel_code, panel_template, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_usermod, panel_state, panel_datestamp) VALUES ('$panel_name', '$panel_locale', '$panel_filename', '$panel_code', '$panel_template', '$panel_side', '$neworder', '$panel_type', '$panel_access', '$panel_display', '0', '$panel_usermod', '$panel_state', '".time()."')");
		}
		// define the message panel variables
		if ($error != "") {
			$variables['message'] = $locale['484']."<br /><br />".$error;
		} else {
			$variables['message'] = $locale['485'];
		}
		$variables['bold'] = true;
		$variables['link'] = "panels.php".$aidlink.($panel_locale!=""?("&panel_locale=".$panel_locale):"");
		$variables['linktext'] = $locale['486'];
		$template_panels[] = array('type' => 'body', 'name' => 'admin.panel_editor.status', 'title' => $locale['483'], 'template' => '_message_table_panel.tpl');
		$template_variables['admin.panel_editor.status'] = $variables;
		$variables = array();
	}
	$title = $locale['451'];
	unset($panel_id);

} else {

	if (isset($_POST['preview'])) {
		$panel_name = stripinput($_POST['panel_name']);
		$panel_filename = stripinput($_POST['panel_filename']);
		$panel_code = isset($_POST['panel_code']) ? $_POST['panel_code'] : "";
		$panel_template = isset($_POST['panel_template']) ? $_POST['panel_template'] : "";
		$panel_access = $_POST['panel_access'];
		$panel_side = $_POST['panel_side'];
		$panelon = isset($_POST['panel_display']) ? " checked" : "";
		$panelopts = $_POST['panel_side'] == "1" || $_POST['panel_side'] == "4" ? " style='display:none'" : " style='display:block'";
		$panel_usermod = $_POST['panel_usermod'];
		$panel_state = $_POST['panel_state'];
		$panel_code = stripslash($panel_code);
		$panel_template = stripslash($panel_template);
		if ($panel_filename == "") {
			$panel_type = "dynamic";
			eval($panel_code);
			if ($panel_side == 0) {
				$template_panels[] = array('type' => 'header', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => 'string:'.$panel_template);
			} elseif ($panel_side == 1) {
				$template_panels[] = array('type' => 'left', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => 'string:'.$panel_template);
			} elseif ($panel_side == 2) {
				$template_panels[] = array('type' => 'upper', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => 'string:'.$panel_template);
			} elseif ($panel_side == 3) {
				$template_panels[] = array('type' => 'lower', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => 'string:'.$panel_template);
			} elseif ($panel_side == 4) {
				$template_panels[] = array('type' => 'right', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => 'string:'.$panel_template);
			} elseif ($panel_side == 5) {
				$template_panels[] = array('type' => 'footer', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => 'string:'.$panel_template);
			}
		} else {
			$panel_type = "file";
			@include PATH_MODULES.$panel_filename;
			if ($panel_side == 0) {
				$template_panels[] = array('type' => 'header', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => "modules.".basename($panel_filename,".php").".tpl");
			} if ($panel_side == 1) {
				$template_panels[] = array('type' => 'left', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => "modules.".basename($panel_filename,".php").".tpl");
			} elseif ($panel_side == 2) {
				$template_panels[] = array('type' => 'upper', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => "modules.".basename($panel_filename,".php").".tpl");
			} elseif ($panel_side == 3) {
				$template_panels[] = array('type' => 'lower', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => "modules.".basename($panel_filename,".php").".tpl");
			} elseif ($panel_side == 4) {
				$template_panels[] = array('type' => 'right', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => "modules.".basename($panel_filename,".php").".tpl");
			} elseif ($panel_side == 5) {
				$template_panels[] = array('type' => 'footer', 'name' => 'admin.panel_editor.preview', 'title' => $panel_name, 'state' => ($panel_state == 2 ? 0 : $panel_state), 'template' => "modules.".basename($panel_filename,".php").".tpl");
			}
		}
		if (isset($variables) && is_array($variables)) {
			$template_variables['admin.panel_editor.preview'] = $variables;
			$variables = array();
		}
	}

	if (isset($step) && $step == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id='$panel_id'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$panel_name = $data['panel_name'];
			$panel_type = $data['panel_type'];
			$panel_filename = $data['panel_filename'];
			if ($panel_type == "file" && @is_dir(PATH_MODULES.$panel_filename)) {
				$panel_filename = $panel_filename."/".$panel_filename;
			}
			$panel_code = phpentities(stripslashes($data['panel_code']));
			$panel_template = phpentities(stripslashes($data['panel_template']));
			$panel_access = $data['panel_access'];
			$panel_side = $data['panel_side'];
			$panel_usermod = $data['panel_usermod'];
			$panel_state = $data['panel_state'];
			$panelon = $data['panel_display'] == "1" ? " checked" : "";
			$panelopts = $panel_side == "1" || $panel_side == "4" ? " style='display:none'" : " style='display:block'";
		}
	}
}

if (isset($panel_id)) {
	$action = FUSION_SELF.$aidlink."&amp;panel_id=$panel_id";
	$title = $locale['450'];
//	$panelon = "";
//	$panelopts = "";
} else {
	$action = FUSION_SELF.$aidlink;
	$title = $locale['451'];
	if (!isset($_POST['preview'])) {
		$panel_name = "";
		$panel_filename = "";
		$panel_code = "// define the variables for the panel\n\$variables = array();\n\n\$variables['text'] = 'Hello World!';\n";
		$panel_template = "{* Smarty template *}\n\n{include file=\"_opentable.tpl\" name=\$_name title=\$_title state=\$_state style=\$_style}\n{\$text}\n{include file=\"_closetable.tpl\"}";
		$panel_type = "";
		$panel_access = "";
		$panel_side = "";
		$panel_usermod = 0;
		$panel_state = 0;
		$panelon = "";
		$panelopts = " style='display:none'";
	}
}

//
foreach ($panel_list as $key => $value) {
	if ($value['panel_filename'] == $panel_filename) {
		$panel_list[$key]['selected'] = true;
		break;
	}
} 
$variables['action'] = $action;
$variables['panel_id'] = isset($panel_id) ? $panel_id : 0;
$variables['panel_name'] = $panel_name;
$variables['panel_locale'] = $panel_locale;
$variables['panel_type'] = $panel_type;
$variables['panel_list'] = $panel_list;
$variables['panel_state'] = $panel_state;
$variables['panel_filename'] = $panel_filename;
$variables['panel_code'] = $panel_code;
$variables['panel_template'] = $panel_template;
$variables['panel_side'] = $panel_side;
$variables['panel_usermod'] = $panel_usermod;
$variables['panelopts'] = $panelopts;
$variables['panelon'] = $panelon;
// 
$user_groups = getusergroups();
$variables['user_groups'] = array();
while(list($key, $user_group) = each($user_groups)){
	$variables['user_groups'][] = array('id' => $user_group['0'], 'name' => $user_group['1'], 'selected' => ($panel_access == $user_group['0']));
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.panel_editor', 'title' => $title, 'template' => 'admin.panel_editor.tpl', 'locale' => "admin.panels");
$template_variables['admin.panel_editor'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
