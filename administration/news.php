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

// make sure the parameter is valid
if (isset($news_id) && !isNum($news_id)) fallback(FUSION_SELF);

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
	$template_panels[] = array('type' => 'body', 'title' => $title, 'name' => 'admin.news.status', 'template' => '_message_table_panel.tpl');
	$template_variables['admin.news.status'] = $variables;
	$variables = array();
}

// compose the query where clause based on the localisation method choosen
switch ($settings['news_localisation']) {
	case "none":
		$where = "";
		$news_locale = "";
		break;
	case "single":
		$where = "";
		$news_locale = "";
		break;
	case "multiple":
		if (isset($_POST['news_locale'])) $news_locale = stripinput($_POST['news_locale']);
		if (isset($news_locale)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".stripinput($news_locale)."' AND locale_active = '1' LIMIT 1");
			if (!dbrows($result)) unset($news_locale);
		}
		if (!isset($news_locale)) $news_locale = $settings['locale_code'];
		$variables['news_locale'] = $news_locale;
		$where = "news_locale = '".$news_locale."' ";
		break;
}

// fill the newsitems array for the newsitem selection dropdown
$result = dbquery("SELECT * FROM ".$db_prefix."news ".($where==""?"":("WHERE ".$where))." ORDER BY news_datestamp DESC");
$variables['newsitems'] = array();
while ($data = dbarray($result)) {
	$data['selected'] = (isset($news_id) && $news_id == $data['news_id']);
	$variables['news'][] = $data;
}

if (isset($_POST['save'])) {

	// save the news item
	$news_subject = stripinput($_POST['news_subject']);
	$news_cat = isNum($_POST['news_cat']) ? $_POST['news_cat'] : "0";
	$body = addslash($_POST['body']);
	if ($_POST['body2']) $body2 = addslash(preg_replace("(^<p>\s</p>$)", "", $_POST['body2']));
	$news_start_date = 0; $news_end_date = 0; $news_post_date = 0;
	if ($_POST['news_start']['mday']!="--" && $_POST['news_start']['mon']!="--" && $_POST['news_start']['year']!="----") {
		$news_start_date = mktime($_POST['news_start']['hours'],$_POST['news_start']['minutes'],0,$_POST['news_start']['mon'],$_POST['news_start']['mday'],$_POST['news_start']['year']);
	}
	if ($_POST['news_end']['mday']!="--" && $_POST['news_end']['mon']!="--" && $_POST['news_end']['year']!="----") {
		$news_end_date = mktime($_POST['news_end']['hours'],$_POST['news_end']['minutes'],0,$_POST['news_end']['mon'],$_POST['news_end']['mday'],$_POST['news_end']['year']);
	}
	if (isset($news_id)) {
		if ($_POST['news_date']['mday']!="--" && $_POST['news_date']['mon']!="--" && $_POST['news_date']['year']!="----") {
			$news_post_date = mktime($_POST['news_date']['hours'],$_POST['news_date']['minutes'],0,$_POST['news_date']['mon'],$_POST['news_date']['mday'],$_POST['news_date']['year']);
		}
	}
	if ($news_post_date == 0) $news_post_date = ($news_start_date != 0 ? $news_start_date : time());
	// adjust the dates according to the users timezone
	if ($news_start_date != 0) $news_start_date = time_local2system($news_start_date);
	if ($news_end_date != 0) $news_end_date = time_local2system($news_end_date);
	if ($news_post_date != 0) $news_post_date = time_local2system($news_post_date);
	$news_visibility = isNum($_POST['news_visibility']) ? $_POST['news_visibility'] : "0";
	if ($settings['tinymce_enabled'] != 1) { $news_breaks = isset($_POST['line_breaks']) ? "y" : "n"; } else { $news_breaks = "n"; }
	$news_comments = isset($_POST['news_comments']) ? "1" : "0";
	$news_ratings = isset($_POST['news_ratings']) ? "1" : "0";
	if (isset($news_id)) {
		$result = dbquery("UPDATE ".$db_prefix."news SET news_subject='$news_subject', news_cat='$news_cat', news_news='$body', news_extended='$body2', news_breaks='$news_breaks', news_datestamp='$news_post_date', news_start='$news_start_date', news_end='$news_end_date', news_visibility='$news_visibility', news_allow_comments='$news_comments', news_allow_ratings='$news_ratings' WHERE news_id='$news_id'");
		redirect(FUSION_SELF.$aidlink."&status=su");
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."news (news_subject, news_cat, news_news, news_extended, news_breaks, news_name, news_locale, news_datestamp, news_start, news_end, news_visibility, news_reads, news_allow_comments, news_allow_ratings) VALUES ('$news_subject', '$news_cat', '$body', '$body2', '$news_breaks', '".$userdata['user_id']."', '$news_locale', '$news_post_date', '$news_start_date', '$news_end_date', '$news_visibility', '0', '$news_comments', '$news_ratings')");
		redirect(FUSION_SELF.$aidlink."&status=sn");
	}
	
} else if (isset($_POST['delete'])) {

	// delete the news item	
	$result = dbquery("DELETE FROM ".$db_prefix."news WHERE news_id='$news_id'");
	$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_item_id='$news_id' and comment_type='N'");
	$result = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_item_id='$news_id' and rating_type='N'");
	redirect(FUSION_SELF.$aidlink."&status=del");

} else {

	// preview of the news item is requested
	if (isset($_POST['preview'])) {
		$news_subject = stripinput($_POST['news_subject']);
		$body = phpentities(stripslash($_POST['body']));
		$bodypreview = str_replace("src='".str_replace("../", "", IMAGES_N), "src='".IMAGES_N, stripslash($_POST['body']));
		if ($_POST['body2']) {
			$body2 = phpentities(stripslash($_POST['body2']));
			$body2preview = str_replace("src='".str_replace("../", "", IMAGES_N), "src='".IMAGES_N, stripslash($_POST['body2']));
		}
		if (isset($_POST['line_breaks'])) {
			$news_breaks = " checked";
			$bodypreview = nl2br($bodypreview);
			if ($body2) $body2preview = nl2br($body2preview);
		} else {
			$news_breaks = "";
		}
		$news_start = array(
			"mday" => isNum($_POST['news_start']['mday']) ? $_POST['news_start']['mday'] : "--",
			"mon" => isNum($_POST['news_start']['mon']) ? $_POST['news_start']['mon'] : "--",
			"year" => isNum($_POST['news_start']['year']) ? $_POST['news_start']['year'] : "----",
			"hours" => isNum($_POST['news_start']['hours']) ? $_POST['news_start']['hours'] : "0",
			"minutes" => isNum($_POST['news_start']['minutes']) ? $_POST['news_start']['minutes'] : "0",
		);
		$news_end = array(
			"mday" => isNum($_POST['news_end']['mday']) ? $_POST['news_end']['mday'] : "--",
			"mon" => isNum($_POST['news_end']['mon']) ? $_POST['news_end']['mon'] : "--",
			"year" => isNum($_POST['news_end']['year']) ? $_POST['news_end']['year'] : "----",
			"hours" => isNum($_POST['news_end']['hours']) ? $_POST['news_end']['hours'] : "0",
			"minutes" => isNum($_POST['news_end']['minutes']) ? $_POST['news_end']['minutes'] : "0",
		);
		if (isset($_POST['news_date'])) {
			$news_date = array(
				"mday" => isNum($_POST['news_date']['mday']) ? $_POST['news_date']['mday'] : "--",
				"mon" => isNum($_POST['news_date']['mon']) ? $_POST['news_date']['mon'] : "--",
				"year" => isNum($_POST['news_date']['year']) ? $_POST['news_date']['year'] : "----",
				"hours" => isNum($_POST['news_date']['hours']) ? $_POST['news_date']['hours'] : "0",
				"minutes" => isNum($_POST['news_date']['minutes']) ? $_POST['news_date']['minutes'] : "0",
			);
		}
		$news_comments = isset($_POST['news_comments']) ? 1 : 0;
		$news_ratings = isset($_POST['news_ratings']) ? 1 : 0;

		$variables['html'] = "$bodypreview\n";
		$template_panels[] = array('type' => 'body', 'title' => $news_subject, 'name' => 'admin.news.preview', 'template' => '_custom_html.tpl');
		$template_variables['admin.news.preview'] = $variables;
		$variables = array();

		if (isset($body2preview)) {
			$variables['html'] = "$body2preview\n";
			$template_panels[] = array('type' => 'body', 'title' => $news_subject, 'name' => 'admin.news.preview2', 'template' => '_custom_html.tpl');
			$template_variables['admin.news.preview2'] = $variables;
			$variables = array();
		}
	}

	// if this is an edit of an existing post, load the information
	if (isset($_POST['edit']) && isset($news_id)) {
		$result = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_id='$news_id'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$news_subject = $data['news_subject'];
			$news_cat = $data['news_cat'];
			$body = phpentities(stripslashes($data['news_news']));
			$body2 = phpentities(stripslashes($data['news_extended']));
			if ($data['news_start'] > 0) $news_start = getdate(time_system2local($data['news_start']));
			if ($data['news_end'] > 0) $news_end = getdate(time_system2local($data['news_end']));
			if ($data['news_datestamp'] > 0) $news_date = getdate(time_system2local($data['news_datestamp']));
			$news_breaks = $data['news_breaks'] == "y" ? 1 : 0;
			$news_comments = $data['news_allow_comments'] == "1" ? 1 : 0;
			$news_ratings = $data['news_allow_ratings'] == "1" ? 1 : 0;
			$news_visibility = $data['news_visibility'];
		}
		$action = FUSION_SELF.$aidlink."&amp;news_id=$news_id";
		$title = $locale['400'];
	} else {
		if (isset($_POST['preview'])) {
			if (isset($news_id) && $news_id) {
				$action = FUSION_SELF.$aidlink."&amp;news_id=$news_id";
				$title = $locale['400'];
			} else {
				$action = FUSION_SELF.$aidlink;
				$title = $locale['404'];
			}
		} else {
			$news_subject = "";
			$news_cat = 0;
			$body = "";
			$body2 = "";
			$news_breaks = 1;
			$news_comments = 1;
			$news_ratings = 1;
			$news_visibility = 0;
			$action = FUSION_SELF.$aidlink;
			$title = $locale['404'];
		}
	}
	
	// load the variables to display this news item
	$variables['action'] = $action;
	$variables['news_id'] = isset($news_id) ? $news_id : 0;
	$variables['news_subject'] = $news_subject;
	$variables['news_cat'] = $news_cat;
	$variables['body'] = $body;
	$variables['body2'] = $body2;
	$variables['news_breaks'] = $news_breaks;
	$variables['news_comments'] = $news_comments;
	$variables['news_ratings'] = $news_ratings;
	$variables['news_visibility'] = $news_visibility;
	$variables['news_start'] = isset($news_start) ? $news_start : "";
	$variables['news_end'] = isset($news_end) ? $news_end : "";
	$variables['news_date'] = isset($news_date) ? $news_date : "";

	// create a list for the news categories dropdown
	$result = dbquery("SELECT * FROM ".$db_prefix."news_cats ORDER BY news_cat_name");
	$variables['news_cats'] = array();
	while ($data = dbarray($result)) {
		$data['selected'] = (isset($news_cat) && $news_cat == $data['news_cat_id']);
		$variables['news_cats'][] = $data;
	}

	// get the list of defined user groups for the visibility dropdown
	$variables['usergroups'] = getusergroups(false, true);

	// create a list for the news images dropdown
	$variables['images'] = makefilelist(PATH_IMAGES_N, ".|..|index.php", true);

	define('LOAD_TINYMCE', true);

}

// get the installed locales
$variables['locales'] = array();
$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_active = '1'");
while ($data = dbarray($result)) {
	$variables['locales'][$data['locale_code']] = $data['locale_name'];
}


// store the info to generate the panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.news', 'title' => $title, 'template' => 'admin.news.tpl', 'locale' => "admin.news-articles");
$template_variables['admin.news'] = $variables;

require_once PATH_THEME."/theme.php";
?>
