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
require_once PATH_ROOT."/includes/theme_functions.php";

// make sure the parameter passed is valid
if (isset($download_id) && !isNum($download_id)) fallback("index.php");
if (isset($cat_id) && !isNum($cat_id)) fallback("index.php");

// load this module's locales
locale_load("main.downloads");

function countdownloads($cat_id) {
	global $db_prefix;

	// the the number of downloads for this category itself
	$count = dbcount("(download_cat)", "downloads", "download_cat='".$cat_id."'");

	// check if this category has any children. If so, count them too..
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_parent='$cat_id' AND ".groupaccess('download_cat_access'));
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			// ... and since they can have childeren too, recurse...
			$count = $count + countdownloads($data['download_cat_id']);
		}
	}
	return $count;
}

// temp storage for template variables
$variables = array();

// compose the query where clause based on the localisation method choosen
switch ($settings['localisation_method']) {
	case "none":
		$where = "";
		break;
	case "single":
		$where = "";
		break;
	case "multiple":
		$where = "download_cat_locale = '".$settings['locale_code']."' ";
		break;
}

// store the number of columns in the panel
$variables['columns'] = $settings['download_columns'];

define('DOWNLOAD_LIMIT', 10);
$variables['download__limit'] = DOWNLOAD_LIMIT;

if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
$variables['rowstart'] = $rowstart;

// if a download ID is given...
if (isset($download_id)) {
	$res = 0;
	// and it exists ...
	if ($data = dbarray(dbquery("SELECT download_url,download_cat FROM ".$db_prefix."downloads WHERE download_id='$download_id'"))) {
		$cdata = dbarray(dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id='".$data['download_cat']."'"));
		// and the user has access to it...
		if (checkgroup($cdata['download_cat_access'])) {
			$res = 1;
			// update the download counter (if we're using internal statistics)
			if (!isset($settings['remote_stats']) || !$settings['remote_stats']) $result = dbquery("UPDATE ".$db_prefix."downloads SET download_count=download_count+1 WHERE download_id='$download_id'");
			// if a URL is given for the download, redirect to it, else fall back to the download category
			if ($data['download_url']) {
				redirect($data['download_url']);
				exit;
			} else {
				redirect(FUSION_SELF.isset($cat_id)?("?cat_id=".$cat_id):"");
				exit;
			}
		}
	}
	// redirect to the main download screen
	redirect("downloads.php");
	exit;
}

if (!isset($cat_id)) {
	// get all root categories
	$variables['subcats'] = false;
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_parent='0' AND ".groupaccess('download_cat_access').($where=""?"":(" AND ".$where))." ORDER BY download_datestamp DESC");
	if ($result) {
		// any downloads in the 'root' are public, and ordered by download_id DESC, by default!
		$variables['parent'] = array('download_cat_access' => 0, 'download_cat_sorting' => 'download_id DESC');	
		$cat_id = 0;
	}
} else {
	// get the selected category, and all sub-categories of the requested download category
	$variables['subcats'] = true;
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id='$cat_id'");
	if (dbrows($result) == 0) {
		redirect("downloads.php");
		exit;
	}
	$variables['parent'] = dbarray($result);
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_parent='$cat_id' AND ".groupaccess('download_cat_access')." ORDER BY ".$variables['parent']['download_cat_cat_sorting']);
}
// fill the download_cats array with the result
$variables['cats_count'] = dbrows($result);
$variables['download_cats'] = array();
while ($result && $data = dbarray($result)) {
	$data['download_count'] = countdownloads($data['download_cat_id']);
	$variables['download_cats'][] = $data;
}

// check if there are files for download in this category
if (isset($cat_id)) {
	if (checkgroup($variables['parent']['download_cat_access'])) {
		$variables['download_count'] = dbcount("(*)", "downloads", "download_cat='$cat_id'");
		$result = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_cat='$cat_id' ORDER BY ".$variables['parent']['download_cat_sorting']." LIMIT $rowstart,".DOWNLOAD_LIMIT);
		$variables['downloads'] = array();
		while ($data = dbarray($result)) {
			$data['now'] = showdate("", time());
			$data['download_description'] = nl2br(stripslashes($data['download_description']));
			$variables['downloads'][] = $data;
		}
	}
}

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'downloads', 'template' => 'main.downloads.tpl', 'locale' => "main.downloads");
$template_variables['downloads'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>