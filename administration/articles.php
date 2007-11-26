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
locale_load("admin.news-articles");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("A") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid
if (isset($article_id) && !isNum($article_id)) fallback("index.php");

if (isset($status)) {
	if ($status == "su") {
		$title = $locale['500'];
		$variables['message'] = $locale['501'];
	} elseif ($status == "sn") {
		$title = $locale['504'];
		$variables['message'] = $locale['505'];
	} elseif ($status == "del") {
		$title = $locale['506'];
		$variables['message'] = $locale['507'];
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.article.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => "admin.news-articles");
	$template_variables['admin.article.status'] = $variables;
	$variables = array();
}

// compose the query where clause based on the localisation method choosen
switch ($settings['localisation_method']) {
	case "none":
		$where = "";
		$where_cat = "";
		break;
	case "single":
		$where = "";
		$where_cat = "";
		break;
	case "multiple":
		if (isset($_POST['article_locale'])) $article_locale = stripinput($_POST['article_locale']);
		if (isset($article_locale)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".stripinput($article_locale)."' AND locale_active = '1' LIMIT 1");
			if (!dbrows($result)) unset($article_locale);
		}
		if (!isset($article_locale)) $article_locale = $settings['locale_code'];
		$variables['article_locale'] = $article_locale;
		$where = "article_locale = '".$article_locale."' ";
		$where_cat = "article_cat_locale = '".$article_locale."' ";
		break;
}

// check if there are any article categories defined
$result = dbquery("SELECT * FROM ".$db_prefix."article_cats");
if (dbrows($result) == 0) {
	// if not, ask the user to define those first
	$variables['message'] = $locale['518']."<br />".$locale['519']."<br /><a href='article_cats.php".$aidlink."'>".$locale['520']."</a>".$locale['521'];
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.article.no_cats', 'title' => $locale['517'], 'template' => '_message_table_panel.tpl', 'locale' => "admin.news-articles");
	$template_variables['admin.article.no_cats'] = $variables;
	$variables = array();

} else {

	if (isset($_POST['save'])) {
		$subject = stripinput($_POST['subject']);
		$body = addslash($_POST['body']);
		$body2 = addslash($_POST['body2']);
		if ($settings['tinymce_enabled'] != 1) { 
			$breaks = isset($_POST['line_breaks']);
		} else { 
			$breaks = false; 
		}
		$comments = isset($_POST['article_comments']) ? "1" : "0";
		$ratings = isset($_POST['article_ratings']) ? "1" : "0";
		if (isset($article_id)) {
			$result = dbquery("UPDATE ".$db_prefix."articles SET article_cat='".$_POST['article_cat']."', article_subject='$subject', article_snippet='$body', article_article='$body2', article_breaks='".($breaks?"y":"n")."', article_allow_comments='$comments', article_allow_ratings='$ratings' WHERE article_id='$article_id'");
			redirect(FUSION_SELF.$aidlink."&status=su");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."articles (article_cat, article_subject, article_snippet, article_article, article_breaks, article_name, article_locale, article_datestamp, article_reads, article_allow_comments, article_allow_ratings) VALUES ('".$_POST['article_cat']."', '$subject', '$body', '$body2', '".($breaks?"y":"n")."', '".$userdata['user_id']."', '$article_locale', '".time()."', '0', '$comments', '$ratings')");
			redirect(FUSION_SELF.$aidlink."&status=sn");
		}

	} else if (isset($_POST['delete'])) {

		$result = dbquery("DELETE FROM ".$db_prefix."articles WHERE article_id='$article_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_item_id='$article_id' and comment_type='A'");
		$result = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_item_id='$article_id' and rating_type='A'");
		redirect(FUSION_SELF.$aidlink."&status=del");

	} else {

		if (isset($_POST['preview'])) {
			$article_cat = $_POST['article_cat'];
			$subject = stripinput($_POST['subject']);
			$body = phpentities(stripslash($_POST['body']));
			$body2 = phpentities(stripslash($_POST['body2']));
			$bodypreview = str_replace("src='".str_replace("../", "", IMAGES_A), "src='".IMAGES_A, stripslash($_POST['body']));
			$body2preview = str_replace("src='".str_replace("../", "", IMAGES_A), "src='".IMAGES_A, stripslash($_POST['body2']));
			if (isset($_POST['line_breaks'])) {
				$breaks = true;
				$bodypreview = nl2br($bodypreview);
				$body2preview = nl2br($body2preview);
			}
			$comments = isset($_POST['article_comments']);
			$ratings = isset($_POST['article_ratings']);
			$variables['message'] = $bodypreview;
			$template_panels[] = array('type' => 'body', 'name' => 'admin.article.preview1', 'title' => $subject, 'template' => '_message_table_panel.simple.tpl', 'locale' => "admin.news-articles");
			$template_variables['admin.article.preview1'] = $variables;
			$variables['message'] = $body2preview;
			$template_panels[] = array('type' => 'body', 'name' => 'admin.article.preview2', 'title' => $subject, 'template' => '_message_table_panel.simple.tpl', 'locale' => "admin.news-articles");
			$template_variables['admin.article.preview2'] = $variables;
			$variables = array();
			// we've wiped this out, but we'll need it later!
			if (isset($article_locale)) $variables['article_locale'] = $article_locale;
		}

		$variables['articles'] = array();
		$result = dbquery("SELECT * FROM ".$db_prefix."articles ".($where==""?"":("WHERE ".$where))." ORDER BY article_datestamp DESC");
		while ($data = dbarray($result)) {
			$data['selected'] = (isset($article_id) && $article_id == $data['article_id']);
			$variables['articles'][] = $data;
		}

		// get the installed locales
		$variables['locales'] = array();
		$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_active = '1'");
		while ($data = dbarray($result)) {
			$variables['locales'][$data['locale_code']] = $data['locale_name'];
		}

		if (isset($_POST['edit'])) {
			$result = dbquery("SELECT * FROM ".$db_prefix."articles WHERE article_id='$article_id'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$article_cat = $data['article_cat'];
				$subject = $data['article_subject'];
				$body = phpentities(stripslashes($data['article_snippet']));
				$body2 = phpentities(stripslashes($data['article_article']));
				$breaks = ($data['article_breaks'] == "y");
				$comments = ($data['article_allow_comments'] == "1");
				$ratings = ($data['article_allow_ratings'] == "1");
			}
		}
		if (isset($article_id)) {
			$action = FUSION_SELF.$aidlink."&amp;article_id=$article_id";
			$title = $locale['500'];
		} else {
			if (!isset($_POST['preview'])) {
				$subject = "";
				$body = "";
				$body2 = "";
				$breaks = true;
				$comments = true;
				$ratings = true;
			}
			$action = FUSION_SELF.$aidlink;
			$title = $locale['504'];
		}
		$variables['catlist'] = array();
		$result = dbquery("SELECT * FROM ".$db_prefix."article_cats ".($where_cat==""?"":("WHERE ".$where_cat))." ORDER BY article_cat_name DESC");
		while ($data = dbarray($result)) {
			$data['selected'] = (isset($article_cat) && $article_cat == $data['article_cat_id']);
			$variables['catlist'][] = $data;
		}
		$variables['image_files'] = makefilelist(PATH_IMAGES_A, ".|..|index.php", true);
		// assign work variables to the template
		$variables['title'] = $title;
		$variables['action'] = $action;
		$variables['subject'] = $subject;
		$variables['body'] = $body;
		$variables['body2'] = $body2;
		$variables['breaks'] = isset($breaks) ? $breaks : false;
		$variables['comments'] = $comments;
		$variables['ratings'] = $ratings;
		$variables['img_src'] = str_replace("../","",IMAGES_A);
		define('LOAD_TINYMCE', true);

		// define the admin body panel
		$template_panels[] = array('type' => 'body', 'name' => 'admin.articles', 'template' => 'admin.articles.tpl', 'locale' => "admin.news-articles");
		$template_variables['admin.articles'] = $variables;
	}
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>