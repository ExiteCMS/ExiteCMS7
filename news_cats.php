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

define('NC_OVERVIEW_LIMIT', 10);

// load this module's locales
include PATH_LOCALE.LOCALESET."news_cats.php";

// make sure the cat_id is valid
if (isset($cat_id) && !isNum($cat_id)) fallback(FUSION_SELF);

// temp storage for template variables
$variables = array();
$news_cats = array();

// make the selection based on the presence of cat_id 
if (isset($cat_id)) {
	$sql = "WHERE news_cat_id='".$cat_id."'";
	$sql2 = "";
	$show_all = true;
} else {
	$sql = "ORDER BY news_cat_id";
	$sql2 = " LIMIT ".NC_OVERVIEW_LIMIT;
	$show_all = false;
	// get the number of uncategorised news items
	$count = dbcount("(news_id)", "news", "news_cat='0' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
	// only show them if there are news items available
	if ($count) {
		$data = array();
		$data['itemcount'] = $count;
		// set a flag to indicate there are more items then shown
		$data['more'] = (!isset($cat_id) && $data['itemcount'] > NC_OVERVIEW_LIMIT);
		$data['news_cat_image'] = "";
		$data['news_cat_name'] = $locale['403'];
		// get none categorised news first
		if ($data['itemcount']) {
			$result = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_cat='0' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") ORDER BY news_datestamp DESC ".$sql2);
			while ($data2 = dbarray($result2)) {
				$data['items'][] = $data2;
			}
		}
		$news_cats[] = $data;
	}
}
// get the available news_cats
$result = dbquery("SELECT * FROM ".$db_prefix."news_cats ".$sql);
while ($data = dbarray($result)) {
	// total number of items for this news_cat
	$data['itemcount'] = dbcount("(news_id)", "news", "news_cat='".$data['news_cat_id']."' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
	// set a flag to indicate there are more items then shown
	$data['more'] = (!isset($cat_id) && $data['itemcount'] > NC_OVERVIEW_LIMIT);
	// get all news items for this cat (limit if showing an overview)
	$data['items'] = array();
	if ($data['itemcount']) {
		$result2 = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_cat='".$data['news_cat_id']."' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") ORDER BY news_datestamp DESC ".$sql2);
		while ($data2 = dbarray($result2)) {
			$data['items'][] = $data2;
		}
	}
	$news_cats[] = $data;
}
$variables['news_cats'] = $news_cats;
$variables['show_all'] = $show_all;
$variables['overview_limit'] = NC_OVERVIEW_LIMIT;

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'news_cats', 'template' => 'main.news_cats.tpl', 'locale' => PATH_LOCALE.LOCALESET."news_cats.php");
$template_variables['news_cats'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>