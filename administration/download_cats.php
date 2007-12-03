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
locale_load("admin.downloads");

//check if the user has a right to be here. If not, bail out
if (!checkrights("DC") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// verify the parameters
if (isset($cat_id) && !isNum($cat_id)) fallback(FUSION_SELF.$aidlink);

// function to check if sub-categories are not assigned recusivly
function cat_not_recursive($this_id, $new_id) {
	global $db_prefix;
	
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id = '".$new_id."'");
	if (!$result) return true;
	$data = dbarray($result);
	if ($data['download_parent'] == 0) return true;
	if ($data['download_parent'] == $this_id) return false;
	return cat_not_recursive($this_id, $data['download_parent']);
}

// temp storage for template variables
$variables = array();

// status messsage before or after delete
if (isset($status)) {
	if ($status == "deln") {
		$variables['errormessage'] = $locale['401']."<br />".$locale['402'];
	} elseif ($status == "dely") {
		$variables['errormessage'] = $locale['405'];
	}
	$variables['bold'] = true;
}

if (isset($step) && $step == "delete") {
	$result = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_cat='$cat_id'");
	if (dbrows($result) != 0) {
		redirect(FUSION_SELF.$aidlink."&status=deln&cat_locale".$cat_locale);
		exit;
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."download_cats WHERE download_cat_id='$cat_id'");
		redirect(FUSION_SELF.$aidlink."&status=dely&cat_locale=".$cat_locale);
		exit;
	}
}
if (isset($_POST['save_cat'])) {
	$cat_id = isset($cat_id) ? $cat_id : "0";
	$cat_name = stripinput($_POST['cat_name']);
	$cat_locale = isset($_POST['cat_locale']) ? $_POST['cat_locale'] : "";
	$cat_description = stripinput($_POST['cat_description']);
	$cat_access = isNum($_POST['cat_access']) ? $_POST['cat_access'] : "0";
	$cat_image = stripinput($_POST['cat_image']);
	$cat_sub = isNum($_POST['cat_sub']) ? $_POST['cat_sub'] : "0";
	if (isNum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "1") {
		$cat_sorting = "download_id ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
	} else if (isNum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "2") {
		$cat_sorting = "download_title ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
	} else if (isNum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "3") {
		$cat_sorting = "download_datestamp ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
	} else {
		$cat_sorting = "download_title ASC";
	}
	if (isNum($_POST['cat_cat_sort_by']) && $_POST['cat_cat_sort_by'] == "1") {
		$cat_cat_sorting = "download_cat_id ".($_POST['cat_cat_sort_order'] == "ASC" ? "ASC" : "DESC");
	} else if (isNum($_POST['cat_cat_sort_by']) && $_POST['cat_cat_sort_by'] == "2") {
		$cat_cat_sorting = "download_cat_name ".($_POST['cat_cat_sort_order'] == "ASC" ? "ASC" : "DESC");
	} else if (isNum($_POST['cat_cat_sort_by']) && $_POST['cat_cat_sort_by'] == "3") {
		$cat_cat_sorting = "download_cat_description ".($_POST['cat_cat_sort_order'] == "ASC" ? "ASC" : "DESC");
	} else if (isNum($_POST['cat_cat_sort_by']) && $_POST['cat_cat_sort_by'] == "4") {
		$cat_cat_sorting = "download_datestamp ".($_POST['cat_cat_sort_order'] == "ASC" ? "ASC" : "DESC");
	} else {
		$cat_cat_sorting = "download_cat_id DESC";
	}
	$formaction = FUSION_SELF.$aidlink."&amp;";
	if (isset($cat_locale) && !empty($cat_locale)) $formaction .= "cat_locale=".$cat_locale;
	if (cat_not_recursive($cat_id, $cat_sub)) {
		$update_datestamp = isset($_POST['update_datestamp']) && isNum($_POST['update_datestamp']) ? true : false;
		if (isset($step) && $step == "edit") {
			// update
			$result = dbquery("UPDATE ".$db_prefix."download_cats SET download_cat_name='$cat_name', download_cat_locale='$cat_locale', download_cat_description='$cat_description', download_cat_sorting='$cat_sorting', download_cat_cat_sorting='$cat_cat_sorting', download_cat_access='$cat_access', download_cat_image='$cat_image', download_parent='$cat_sub' ".($update_datestamp ? (", download_datestamp='".time()."'") : "")." WHERE download_cat_id='$cat_id'");
		} else {
			// insert
			$result = dbquery("INSERT INTO ".$db_prefix."download_cats (download_cat_name, download_cat_locale, download_cat_description, download_cat_sorting, download_cat_cat_sorting, download_cat_access, download_cat_image, download_parent, download_datestamp) VALUES('$cat_name', '$cat_locale', '$cat_description', '$cat_sorting', '$cat_cat_sorting', '$cat_access', '$cat_image', '$cat_sub', '".time()."')");
		}
		$step = "add";
	} else {
		$formaction .= "&amp;step=edit";
		$title = $locale['420'];
		$variables['bold'] = true;
		$variables['errormessage'] = $locale['446'];
		$step = "edit_err";
	}
}

if (isset($step) && $step == "edit") {
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id='$cat_id'");
	$data = dbarray($result);
	$cat_name = $data['download_cat_name'];
	$cat_locale = $data['download_cat_locale'];
	$cat_description = $data['download_cat_description'];
	$cat_sorting = explode(" ", $data['download_cat_sorting']);
	if ($cat_sorting[0] == "download_id") { $cat_sort_by = "1"; }
	if ($cat_sorting[0] == "download_title") { $cat_sort_by = "2"; }
	if ($cat_sorting[0] == "download_datestamp") { $cat_sort_by = "3"; }
	$cat_sort_order = $cat_sorting[1];
	if (empty($data['download_cat_cat_sorting'])) {
		$cat_cat_sort_by = "download_cat_id";
		$cat_cat_sort_order = "DESC";
	} else {
		$cat_cat_sorting = explode(" ", $data['download_cat_cat_sorting']);
		if ($cat_cat_sorting[0] == "download_cat_id") { $cat_cat_sort_by = "1"; }
		elseif ($cat_cat_sorting[0] == "download_cat_name") { $cat_cat_sort_by = "2"; }
		elseif ($cat_cat_sorting[0] == "download_cat_description") { $cat_cat_sort_by = "3"; }
		elseif ($cat_cat_sorting[0] == "download_datestamp") { $cat_cat_sort_by = "4"; }
		else { $cat_cat_sort_by = "1"; }
		$cat_cat_sort_order = $cat_cat_sorting[1];
	}
	$cat_access = $data['download_cat_access'];
	$cat_image = $data['download_cat_image'];
	$cat_sub = $data['download_parent'];
	$formaction = FUSION_SELF.$aidlink."&amp;step=edit&amp;cat_id=".$data['download_cat_id'];
	if (isset($cat_locale) && !empty($cat_locale)) $formaction .= "&amp;cat_locale=".$cat_locale;
	$title = $locale['420'];
} else { 
	$cat_id = 0;
	$cat_name = "";
	if (!isset($cat_locale)) $cat_locale = $settings['locale_code'];
	$cat_description = "";
	$cat_sort_by = "download_title";
	$cat_sort_order = "ASC";
	$cat_sub = 0;
	$cat_cat_sort_by = "download_cat_id";
	$cat_cat_sort_order = "DESC";
	$cat_access = 101;
	$cat_image = "";
	$formaction = FUSION_SELF.$aidlink;
	if (isset($cat_locale) && !empty($cat_locale)) $formaction .= "&amp;cat_locale=".$cat_locale;
	$title = $locale['421'];
}

$variables['groups'] = getusergroups(false, true);
$variables['images'] = makefilelist(PATH_IMAGES_DC, ".|..|index.php", true);
$variables['editlist'] = array();
$result_sub = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id > 0 ORDER BY download_cat_name");	
while ($data2 = dbarray($result_sub)) {
	$variables['editlist'][] = $data2;
}

// compose the query where clause based on the localisation method choosen
switch ($settings['download_localisation']) {
	case "none":
		$where = "";
		$cat_locale = "";
		break;
	case "single":
		$where = "";
		$cat_locale = "";
		break;
	case "multiple":
		if (isset($cat_locale)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".stripinput($cat_locale)."' AND locale_active = '1' LIMIT 1");
			if (!dbrows($result)) unset($cat_locale);
		}
		if (!isset($cat_locale)) $cat_locale = $settings['locale_code'];
		$where = "download_cat_locale = '".$cat_locale."' ";
		break;
}

$variables['cats'] = array();
$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id > 0 ".($where=""?"":("AND ".$where))." ORDER BY download_parent*100000-download_cat_id");
while ($data = dbarray($result)) {
	if ($data['download_parent']) {
		$result_sub = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id = '" .$data['download_parent']. "' LIMIT 1");
		while ($data_sub = dbarray($result_sub)) {
			$data['parent_cat_name'] = $data_sub['download_cat_name']; 
		}
	}
	$data['group_name'] = getgroupname($data['download_cat_access'], -1);
	$variables['cats'][] = $data;
}

// template variables
$variables['cat_id'] = $cat_id;
$variables['cat_name'] = $cat_name;
$variables['cat_locale'] = $cat_locale;
$variables['cat_description'] = $cat_description;
$variables['cat_sort_by'] = $cat_sort_by;
$variables['cat_sort_order'] = $cat_sort_order;
$variables['cat_sub'] = $cat_sub;
$variables['cat_cat_sort_by'] = $cat_cat_sort_by;
$variables['cat_cat_sort_order'] = $cat_cat_sort_order;
$variables['cat_access'] = $cat_access;
$variables['cat_image'] = $cat_image;
$variables['formaction'] = $formaction;
$variables['is_edit'] = isset($step);

// get the installed locales
$variables['locales'] = array();
$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_active = '1'");
while ($data = dbarray($result)) {
	$variables['locales'][$data['locale_code']] = $data['locale_name'];
}

// panel definitions
$template_panels[] = array('type' => 'body', 'name' => 'admin.download_cats', 'title' => $title, 'template' => 'admin.download_cats.tpl', 'locale' => "admin.downloads");
$template_variables['admin.download_cats'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>