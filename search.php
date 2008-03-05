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

// temp storage for template variables
$variables = array();

// minimum search length
define('MIN_SEARCH_LENGTH', 3);

// define the searchable menu items
$searchable = array('news_cats.php',
		'forum/index.php',
		'article_cats.php',
		'downloads.php',
		'weblinks.php'
);
$searchtypes = array('n', 'f', 'a', 'd', 'w');

// load the locale for this module
locale_load("main.search");

// validate the possible parameters
if (isset($stype)) $stype = $stype;
if (!isset($stype)) $stype = isset($_POST['stype']) ? $_POST['stype'] : "f";
$variables['stype'] = $stype;

if (isset($stext)) $stext = stripinput($stext);
if (!isset($stext)) $stext = isset($_POST['stext']) ? stripinput($_POST['stext']) : "";
$variables['searchtext'] = str_replace(',', ' ', $stext);

if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
$variables['rowstart'] = $rowstart;

// do we have a localized menu? If so, select only the current locale
switch ($settings['sitelinks_localisation']) {
	case "none":
		$where = "";
		break;
	case "single":
		$where = "";
		break;
	case "multiple":
		if (isset($link_locale)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".stripinput($link_locale)."' AND locale_active = '1' LIMIT 1");
			if (!dbrows($result)) unset($link_locale);
		}
		if (!isset($link_locale)) $link_locale = $settings['locale_code'];
		$variables['link_locale'] = $link_locale;
		$where = "AND link_locale = '".$link_locale."' ";
		break;
}

// get the available options from the database
$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_position<='2' ".$where."ORDER BY link_order");
$variables['links'] = array();
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			foreach($searchable as $idx => $link) {
				if ($link == $data['link_url']) {
					$data['value'] = $searchtypes[$idx];
					$variables['links'][] = $data;
				}
			}
		}
	}
}

// if we've got a search request, start searching...
if ($stext != "" && strlen($stext) >= "3") {
	$stext = mysql_escape_string($stext);
	switch ($stype) {
		case "a":	// articles
			$result = dbquery(
				"SELECT ta.*,tac.*, tu.user_id,user_name, 
				MATCH(article_subject, article_snippet, article_article) AGAINST ('$stext' IN BOOLEAN MODE) AS score 
				FROM ".$db_prefix."articles ta
				INNER JOIN ".$db_prefix."article_cats tac ON ta.article_cat=tac.article_cat_id
				LEFT JOIN ".$db_prefix."users tu ON ta.article_name=tu.user_id
				WHERE ".groupaccess('article_cat_access')." AND MATCH(article_subject, article_snippet, article_article) AGAINST ('$stext' IN BOOLEAN MODE)
				ORDER BY score DESC, article_datestamp DESC"
			);
			define('RESULTS_PER_PAGE', 15);
			break;
		case "d":	// downloads
			$result = dbquery(
				"SELECT td.*,tdc.*, 
				MATCH (download_title, download_description) AGAINST ('$stext' IN BOOLEAN MODE) AS score
				FROM ".$db_prefix."downloads td
				INNER JOIN ".$db_prefix."download_cats tdc ON td.download_cat=tdc.download_cat_id
				WHERE ".groupaccess('download_cat_access')." AND MATCH (download_title, download_description) AGAINST ('$stext' IN BOOLEAN MODE)
				ORDER BY score DESC, td.download_datestamp DESC"
			);
			define('RESULTS_PER_PAGE', 10);
			break;
		case "f":	// forums
			$result = dbquery(
				"SELECT tp.*, tf.*, tu.user_id,user_name, fa.attach_id,
				MATCH(tp.post_subject, tp.post_message, fa.attach_name, fa.attach_realname, fa.attach_comment) AGAINST ('$stext' IN BOOLEAN MODE) AS score
				FROM ".$db_prefix."posts tp
				INNER JOIN ".$db_prefix."forums tf USING(forum_id)
				INNER JOIN ".$db_prefix."users tu ON tp.post_author=tu.user_id
				LEFT JOIN ".$db_prefix."forum_attachments fa ON tp.post_id=fa.post_id
				WHERE ".groupaccess('forum_access')." AND MATCH(tp.post_subject, tp.post_message, fa.attach_name, fa.attach_realname, fa.attach_comment) AGAINST ('$stext' IN BOOLEAN MODE)
				ORDER BY score DESC, post_datestamp DESC"
			);
			define('RESULTS_PER_PAGE', 15);
			break;
		case "n":	// news
			$result = dbquery(
				"SELECT tn.*, user_id, user_name,
				MATCH(news_subject, news_news, news_extended) AGAINST ('$stext' IN BOOLEAN MODE) AS score
				FROM ".$db_prefix."news tn
				LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
				WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") 
				AND MATCH(news_subject, news_news, news_extended) AGAINST ('$stext' IN BOOLEAN MODE)
				ORDER BY score DESC, news_datestamp DESC"
			);
			define('RESULTS_PER_PAGE', 15);
			break;
		case "m":	// members
			$result = dbquery(
				"SELECT *, 1 AS score
				FROM ".$db_prefix."users 
				WHERE user_name LIKE '%$stext%'
				ORDER BY score DESC, user_name ASC"
			);
			define('RESULTS_PER_PAGE', 30);
			break;
		case "w":	// weblinks
			break;
			$result = dbquery(
				"SELECT tw.*,twc.*,
				MATCH (download_title, download_description) AGAINST ('$stext' IN BOOLEAN MODE) AS score
				FROM ".$db_prefix."weblinks tw
				INNER JOIN ".$db_prefix."weblink_cats twc ON tw.weblink_cat=twc.weblink_cat_id
				WHERE ".groupaccess('weblink_cat_access')." AND MATCH (download_title, download_description) AGAINST ('$stext' IN BOOLEAN MODE)
				ORDER BY score DESC"
			);
			define('RESULTS_PER_PAGE', 25);
			break;
		default:
			break;
	}
	// retrieve the results
	$variables['result_count'] = dbrows($result);
	$variables['items_per_page'] = RESULTS_PER_PAGE;
	$variables['results'] = array();
	$divider = 0;
	$c = 0;
	while ($data = dbarray($result)) {
		if ($divider == 0) $divider = $data['score'];
		if ($c++ < $rowstart) continue;
		if ($c > ($rowstart + RESULTS_PER_PAGE)) break;
		$results = array();
		$results['relevance'] = $data['score'] / $divider * 100;
		$results['data'] = $data;
		$variables['results'][] = $results;
	}
}

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'search', 'template' => 'main.search.tpl', 'locale' => "main.search");
$template_variables['search'] = $variables;


// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>