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
include PATH_LOCALE.LOCALESET."admin/downloads.php";

// TODO - WANWIZARD - 20070718 - NEED TO MOVE THIS TO SETTINGS
define('MAX_BARS', 9);

//check if the user has a right to be here. If not, bail out
if (!checkrights("D") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// verify the parameters
if (isset($download_id) && !isNum($download_id)) fallback(FUSION_SELF.$aidlink);

// given a 'start' cat_id as parent, this function recuses through the categories, 
// to get the display sequence and nesting right
function recurse_dc($parent_id, $nestlevel) {
	global $locale, $db_prefix, $aidlink, $variables;

	// get the parent category
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id = '$parent_id'");
	// if found...
	if (dbrows($result) != 0) {
		// get the category record
		$data = dbarray($result);
		// array to store a leaf of the tree
		$leaf = array();
		$leaf['node'] = 'P';	// it's a parent node
		$leaf['nestlevel'] = $nestlevel;
		$leaf['name'] = $data['download_cat_name'];
		$leaf['id'] = $data['download_cat_id'];
		$variables['tree'][] = $leaf;
		// check if this parent had any downloads assigned to it
		$result2 = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_cat='".$data['download_cat_id']."' ORDER BY download_title");
		$rows = dbrows($result2);
		$row = 1;
		if ($rows != 0) {
			while ($data2 = dbarray($result2)) {
				// get all downloads in this category
				$leaf = array();
				$leaf['node'] = 'D';
				$leaf['first'] = $row == 1;
				$leaf['last'] = $row == $rows;
				$leaf['cat_id'] = $data['download_cat_id'];
				if (!strstr($data2['download_url'],"http://") && !strstr($data2['download_url'],"../")) {
					$leaf['url'] = BASEDIR.$data2['download_url'];
				} else {
					$leaf['url'] = $data2['download_url'];
				}
				$leaf['name'] = $data2['download_title'];
				$leaf['id'] = $data2['download_id'];
				$variables['tree'][] = $leaf;
				$row++;
			}
		} else {
			// no downloads in this category
			$leaf = array();
			$leaf['node'] = 'E';
			$leaf['name'] = $locale['505'];
			$leaf['id'] = $data['download_cat_id'];
			$variables['tree'][] = $leaf;
		}
		// check if this parent is has sub categories. if so, recurse
		$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_parent = '$parent_id' ORDER BY download_cat_id DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				recurse_dc($data['download_cat_id'], $nestlevel+1);
			}
		}	
	}
}

// initialise some variables we need later
if (!isset($download_cat_id)) $download_cat_id = "-1";
if (!isset($step)) $step = "";
$barmsg = "";
$bartitle = "";

// temp storage for template variables
$variables = array();

if(isset($_POST['save_bars'])) {
	$barcontent = $_POST['download_bar'];
	if (!is_array($barcontent)) fallback(BASEDIR."index.php");
	$bar_title = stripinput($_POST['bar_title']);
	$result = dbquery("UPDATE ".$db_prefix."download_cats SET download_cat_name = '".$bar_title."' WHERE download_cat_id = '0'");
	// reset all bar indicators before setting new ones
	$result = dbquery("UPDATE ".$db_prefix."downloads SET download_bar = '0'");
	foreach($barcontent as $key => $bar) {
		if ($bar != 0) $result = dbquery("UPDATE ".$db_prefix."downloads SET download_bar = '".$key."' WHERE download_id = '".$bar."'");
	}
	$barmsg = $locale['523'];
}

$result = dbquery("SELECT * FROM ".$db_prefix."download_cats");
if (dbrows($result) != 0) {
	$variables['cats_found'] = true;	
	if ($step == "delete") {
		$result = dbquery("DELETE FROM ".$db_prefix."downloads WHERE download_id='$download_id'");
		if (dbcount("(*)", "downloads", "download_cat=$download_cat_id") == 0) {
			redirect(FUSION_SELF.$aidlink);
		} else {
			redirect(FUSION_SELF.$aidlink."&download_cat_id=$download_cat_id");
		}
	}
	if (isset($_POST['save_download'])) {
		$download_title = stripinput($_POST['download_title']);
		$download_description = addslash($_POST['download_description']);
		$download_url = stripinput($_POST['download_url']);
		$download_license = stripinput($_POST['download_license']);
		$download_os = stripinput($_POST['download_os']);
		$download_version = stripinput($_POST['download_version']);
		$download_filesize = stripinput($_POST['download_filesize']);
		if ($step == "edit") {
			$download_datestamp = isset($_POST['update_datestamp']) ? ", download_datestamp='".time()."'" : "";
			$result = dbquery("UPDATE ".$db_prefix."downloads SET download_title='$download_title', download_description='$download_description', download_url='$download_url', download_cat='$download_cat', download_license='$download_license', download_os='$download_os', download_version='$download_version', download_filesize='$download_filesize'".$download_datestamp." WHERE download_id='$download_id'");
			redirect(FUSION_SELF.$aidlink."&download_cat_id=$download_cat");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."downloads (download_title, download_description, download_url, download_cat, download_license, download_os, download_version, download_filesize, download_datestamp, download_count) VALUES ('$download_title', '$download_description', '$download_url', '$download_cat', '$download_license', '$download_os', '$download_version', '$download_filesize', '".time()."', '0')");
			redirect(FUSION_SELF.$aidlink."&download_cat_id=$download_cat");
		}
	}
	if ($step == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_id='$download_id'");
		$data = dbarray($result);
		$download_title = $data['download_title'];
		$download_description = stripslashes($data['download_description']);
		$download_url = $data['download_url'];
		$download_license = $data['download_license'];
		$download_os = $data['download_os'];
		$download_version = $data['download_version'];
		$download_filesize = $data['download_filesize'];
		$formaction = FUSION_SELF.$aidlink."&amp;step=edit&amp;download_cat_id=$download_cat_id&amp;download_id=$download_id";
		$title = $locale['470'];
	} else {
		$download_title = "";
		$download_description = "";
		$download_url = "";
		$download_license = "";
		$download_os = "";
		$download_version = "";
		$download_filesize = "";
		$formaction = FUSION_SELF.$aidlink;
		$title = $locale['471'];
	}
	$variables['cats'] = array();
	$variables['download_cats'] = array();
	$result2 = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id > 0 ORDER BY download_cat_id*10000+download_parent");
	while ($data2 = dbarray($result2)) {
		$variables['download_cats'][$data2['download_cat_id']] = $data2['download_cat_name'];
		$data2['selected'] = ($step == "edit" && $data['download_cat'] == $data2['download_cat_id']);
		$variables['cats'][] = $data2;
	}

	// build the download tree
	$variables['tree'] = array();
	$variables['tree'][] = array('node' => 'P', 'nestlevel' => -1, 'name' => $locale['455'], 'id' => 0);
	// Download root first
	$result = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_cat = '0' ORDER BY download_id DESC");
	if ($rows = dbrows($result)) {
		$row = 1;
		while ($data = dbarray($result)) {
			$variables['tree'][] = array('node' => 'D', 'first' => ($row == 1), 'last' => ($row == $rows), 'cat_id' => 0, 'url' => $data['download_url'], 'name' => $data['download_title'], 'id' => $data['download_id']);
		}
	} else {
		// no downloads in this category
		$variables['tree'][] = array('node' => 'E', 'name' => $locale['505'], 'id' => 0);
	}
	// Then recurse through the download categories
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id > 0 AND download_parent = 0 ORDER BY download_datestamp DESC");
	while($data = dbarray($result)) {
		recurse_dc($data['download_cat_id'], 0);
	}

	// get the download bar panel title
	$result = dbquery("SELECT download_cat_name FROM ".$db_prefix."download_cats WHERE download_cat_id='0'");
	if ($data = dbarray($result)) {
		$bar_title = $data['download_cat_name'];
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."download_cats (download_cat_name, download_cat_access) VALUES ('', '255')");
		$bar_title_id = mysql_insert_id();
		$result = dbquery("UPDATE ".$db_prefix."download_cats SET download_cat_id = '0' WHERE download_cat_id = '".$bar_title_id."'");
		$bar_title = "";
	}

	// get all downloads from the database
	$variables['barfiles'] = array();
	$result = dbquery("SELECT * FROM ".$db_prefix."downloads ORDER BY download_id DESC");
	while($data = dbarray($result)) {
		if ($data['download_cat']) {
			$data['download_cat_name'] = $variables['download_cats'][$data['download_cat']];
		} else {
			$data['download_cat_name'] = $locale['455'];
		}
		$variables['barfiles'][] = $data;
	}

	// template variables
	$variables['step'] = $step;
	$variables['formaction'] = $formaction;
	$variables['download_cat_id'] = $download_cat_id;
	$variables['download_title'] = $download_title;
	$variables['download_description'] = $download_description;
	$variables['download_url'] = $download_url;
	$variables['download_license'] = $download_license;
	$variables['download_os'] = $download_os;
	$variables['download_version'] = $download_version;
	$variables['download_filesize'] = $download_filesize;
	$variables['barmsg'] = $barmsg;
	$variables['bar_title'] = $bar_title;

} else {
	$title = $locale['500'];
	$variables['cats_found'] = false;	
}

// panel definitions
$template_panels[] = array('type' => 'body', 'name' => 'admin.downloads', 'title' => $title, 'template' => 'admin.downloads.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/downloads.php");
$template_variables['admin.downloads'] = $variables;


// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>