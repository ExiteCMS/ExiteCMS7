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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once dirname(__FILE__)."/includes/theme_functions.php";

// temp storage for template variables
$variables = array();

// load the locale for this module
locale_load("main.print");

if (!isset($item_id) || !isNum($item_id)) fallback("index.php");

if ($type == "A") {
	$result = dbquery(
		"SELECT ta.*,tac.*, tu.user_id,user_name FROM ".$db_prefix."articles ta
		INNER JOIN ".$db_prefix."article_cats tac ON ta.article_cat=tac.article_cat_id
		LEFT JOIN ".$db_prefix."users tu ON ta.article_name=tu.user_id
		WHERE article_id='$item_id'"
	);
	if (dbrows($result)) {
		$data = dbarray($result);
		$data['article'] = str_replace("<--PAGEBREAK-->", "", stripslashes($data['article_article']));
		if ($data['article_breaks'] == "y") $data['article'] = nl2br($data['article']);
	}
} elseif ($type == "B") {
	$res = dbquery(
		"SELECT b.*, u.user_name FROM ".$db_prefix."blogs b
		LEFT JOIN ".$db_prefix."users u ON b.blog_author=u.user_id
		WHERE blog_id='$item_id'"
	);
	if (dbrows($res) != 0) {
		$data = dbarray($res);
		$data['blog_text'] = stripslashes($data['blog_text']);
		if ($data['blog_breaks'] == "y") $data['blog_text'] = nl2br($data['blog_text']);
	}
} elseif ($type == "N") {
	$res = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
		LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
		WHERE news_id='$item_id'"
	);
	if (dbrows($res) != 0) {
		$data = dbarray($res);
		$data['news'] = stripslashes($data['news_news']);
		if ($data['news_breaks'] == "y") $data['news'] = nl2br($data['news']);
		if ($data['news_extended']) {
			$data['news_extended'] = stripslashes($data['news_extended']);
			if ($data['news_breaks'] == "y") $data['news_extended'] = nl2br($data['news_extended']);
		}
	}
}
if (isset($data)) $variables['data'] = $data;
$variables['type'] = $type;

// define the first body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'print', 'template' => 'main.print.tpl', 'locale' => "main.print");
$template_variables['print'] = $variables;

// load the panels
load_templates('body', '');

// and clean up
theme_cleanup();
?>
