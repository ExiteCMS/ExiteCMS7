<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| Some portions copyright ? 2002 - 2006 Nick Jones   |
| http://www.php-fusion.co.uk/                       |
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

define('AC_OVERVIEW_LIMIT', 5);

include PATH_LOCALE.LOCALESET."article_cats.php";

// make sure the cat_id is valid
if (isset($cat_id) && !isNum($cat_id)) fallback(FUSION_SELF);

// temp storage for template variables
$variables = array();
$article_cats = array();

// make the selection based on the presence of cat_id 
if (isset($cat_id)) {
	$sql = " AND article_cat_id='".$cat_id."'";
	$sql2 = "";
	$show_all = true;
} else {
	$sql = "";
	$sql2 = " LIMIT ".AC_OVERVIEW_LIMIT;
	$show_all = false;
	// get the number of uncategorised article items
	$count = dbcount("(article_id)", "articles", "article_cat='0'");
	// only show them if there are article items available
	if ($count) {
		$data = array();
		$data['itemcount'] = $count;
		// set a flag to indicate there are more items then shown
		$data['more'] = (!isset($cat_id) && $data['itemcount'] > AC_OVERVIEW_LIMIT);
		$data['article_cat_image'] = "";
		$data['article_cat_name'] = $locale['403'];
		// get none categorised article first
		if ($data['itemcount']) {
			$result = dbquery("SELECT * FROM ".$db_prefix."articles WHERE article_cat='0' ORDER BY article_datestamp DESC ".$sql2);
			while ($data2 = dbarray($result2)) {
				$data2['article_snippet'] = stripslashes($data2['article_snippet']);
				$data['items'][] = $data2;
			}
		}
		$article_cats[] = $data;
	}
}
// get the available article_cats
$result = dbquery("SELECT * FROM ".$db_prefix."article_cats WHERE ".groupaccess('article_cat_access').$sql." ORDER BY article_cat_id");
while ($data = dbarray($result)) {
	// total number of items for this news_cat
	$data['itemcount'] = dbcount("(article_id)", "articles", "article_cat='".$data['article_cat_id']."'");
	// set a flag to indicate there are more items then shown
	$data['more'] = (!isset($cat_id) && $data['itemcount'] > AC_OVERVIEW_LIMIT);
	// get all news items for this cat (limit if showing an overview)
	$data['items'] = array();
	if ($data['itemcount']) {
		$result2 = dbquery("SELECT * FROM ".$db_prefix."articles WHERE article_cat='".$data['article_cat_id']."' ORDER BY article_datestamp DESC ".$sql2);
		while ($data2 = dbarray($result2)) {
			$data2['article_snippet'] = stripslashes($data2['article_snippet']);
			$data['items'][] = $data2;
		}
	}
	$article_cats[] = $data;
}
$variables['article_cats'] = $article_cats;
$variables['show_all'] = $show_all;
$variables['overview_limit'] = AC_OVERVIEW_LIMIT;

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'article_cats', 'template' => 'main.article_cats.tpl', 'locale' => PATH_LOCALE.LOCALESET."article_cats.php");
$template_variables['article_cats'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>