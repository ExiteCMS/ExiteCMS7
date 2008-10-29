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
locale_load("admin.news-articles");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("N") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// display a status message if required
if (isset($status)) {
	if ($status == "su") {
		$title = $locale['400'];
		$message = $locale['401'];
	} elseif ($status == "sn") {
		$title = $locale['404'];
		$message = $locale['405'];
	} elseif ($status == "del") {
		$title = $locale['406'];
		$message = $locale['407'];
	} else {
		$title = $locale['400'];
		$message = "UNKNOWN STATUS CODE!";
	}
	$variables['message'] = $message;
	$variables['bold'] = true;
	// define the message body panel
	$template_panels[] = array('type' => 'body', 'title' => $title, 'name' => 'admin.frontpage.status', 'template' => '_message_table_panel.tpl');
	$template_variables['admin.frontpage.status'] = $variables;
	$variables = array();
}

// compose the query where clause based on the localisation method choosen
switch ($settings['news_localisation']) {
	case "none":
		$fwhere = "";
		$nwhere = "";
		if (!isset($news_locale)) $news_locale = $settings['locale_code'];
		break;
	case "single":
		$fwhere = "";
		$nwhere = "";
		if (!isset($news_locale)) $news_locale = $settings['locale_code'];
		break;
	case "multiple":
		if (isset($_POST['news_locale'])) $news_locale = stripinput($_POST['news_locale']);
		if (isset($news_locale)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".stripinput($news_locale)."' AND locale_active = '1' LIMIT 1");
			if (!dbrows($result)) unset($news_locale);
		}
		if (!isset($news_locale)) $news_locale = $settings['locale_code'];
		$variables['news_locale'] = $news_locale;
		$fwhere = "frontpage_locale = '".$news_locale."' ";
		$nwhere = "news_locale = '".$news_locale."' ";
		break;
}

// save the selection for the lastest news homepage panel
if (isset($_POST['save_latest'])) {

	// validate the input
	if (!is_array($_POST['headlines'])) fallback(BASEDIR."index.php");
	$headlines = $_POST['headlines'];
	if (count($headlines) != $settings['news_headline']) fallback(BASEDIR."index.php");
	
	if (!is_array($_POST['newsitems'])) fallback(BASEDIR."index.php");
	$newsitems = $_POST['newsitems'];

	// reset all headline news items before setting new ones
	$result = dbquery("DELETE FROM ".$db_prefix."news_frontpage ".($fwhere==""?"":("WHERE ".$fwhere)));

	// save the new headlines
	foreach($headlines as $key => $item) {
		if ($item != 0) $result = dbquery("INSERT INTO ".$db_prefix."news_frontpage (frontpage_locale, frontpage_headline, frontpage_order, frontpage_news_id) VALUES ('".$news_locale."', 1, '".$key."', '".$item."')");
	}
	
	// save the new latest news items
	foreach($newsitems as $key => $item) {
		if ($item != 0) $result = dbquery("INSERT INTO ".$db_prefix."news_frontpage (frontpage_locale, frontpage_headline, frontpage_order, frontpage_news_id) VALUES ('".$news_locale."', 0, '".$key."', '".$item."')");
	}
	
	// update the news_latest configuration flag
	$settings['news_latest'] = isset($_POST['news_latest']) ? "1" : "0";
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$settings['news_latest']."' WHERE cfg_name = 'news_latest'");
}

// build the list of available news cats
$newslist = array();
// add an empty first entry
$newslist[] = array('news_id' => 0, 'news_subject' => "", 'news_cat_name' => "", 'news_new_cat' => 1, 'selected' => 0);
$result = dbquery("SELECT n.news_id, n.news_subject, c.news_cat_name FROM ".$db_prefix."news n LEFT JOIN ".$db_prefix."news_cats c ON n.news_cat = c.news_cat_id ORDER BY c.news_cat_name, n.news_datestamp DESC");
$current_cat = "";
while ($data = dbarray($result)) {
	if (is_null($data['news_cat_name'])) {
		$data['news_cat_name'] = $locale['425'];
	}
	if ($data['news_cat_name'] != $current_cat) {
		$data['news_new_cat'] = 1;
		$current_cat = $data['news_cat_name'];
	} else {
		$data['news_new_cat'] = 0;
	}
	$newslist[] = $data;
}

// define the headlines array
$headlines = array();
for ($i = 1; $i <= $settings['news_headline']; $i++) {
	$result = dbquery("SELECT frontpage_news_id FROM ".$db_prefix."news_frontpage WHERE frontpage_headline=1 AND frontpage_order=".$i);
	if ($data = dbarray($result)) {
		$news_id = $data['frontpage_news_id'];
	} else {
		$news_id = 0;
	}
	$headlines[$i] = array();
	foreach($newslist as $item) {
		$item['selected'] = $item['news_id'] == $news_id ? 1 : 0;
		$headlines[$i][] = $item;
	}
}
$variables['headlines'] = $headlines;

// define the latest news items array
$newsitems = array();
for ($i = 1; $i <= $settings['news_items']; $i++) {
	$result = dbquery("SELECT frontpage_news_id FROM ".$db_prefix."news_frontpage INNER JOIN ".$db_prefix."news ON frontpage_news_id WHERE frontpage_headline=0 AND frontpage_order=".$i);
	if ($data = dbarray($result)) {
		$news_id = $data['frontpage_news_id'];
	} else {
		$news_id = 0;
	}
	$newsitems[$i] = array();
	foreach($newslist as $item) {
		$item['selected'] = $item['news_id'] == $news_id ? 1 : 0;
		$newsitems[$i][] = $item;
	}
}
$variables['newsitems'] = $newsitems;

// set the panel title
$title = $locale['540'];

// store the info to generate the panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.frontpage', 'title' => $title, 'template' => 'admin.frontpage.tpl', 'locale' => "admin.news-articles");
$template_variables['admin.frontpage'] = $variables;

require_once PATH_THEME."/theme.php";
?>
