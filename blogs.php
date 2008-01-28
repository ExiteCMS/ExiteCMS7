<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
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

// load this module's locales
locale_load("main.blogs");

// temp storage for template variables
$variables = array();

// display a status message if required
if (isset($status)) {
	if ($status == "su") {
		$title = $locale['403'];
		$message = $locale['495'];
	} elseif ($status == "sn") {
		$title = $locale['402'];
		$message = $locale['496'];
	} elseif ($status == "del") {
		$title = $locale['412'];
		$message = $locale['494'];
	} else {
		$title = $locale['400'];
		$message = "UNKNOWN STATUS CODE!";
	}
	$variables['message'] = $message;
	$variables['bold'] = true;
	// define the message body panel
	$template_panels[] = array('type' => 'body', 'title' => $title, 'name' => 'blogs.status', 'template' => '_message_table_panel.tpl');
	$template_variables['blogs.status'] = $variables;
	$variables = array();
}

// default panel title
$title = $locale['400'];

// check the rights of the current user
$variables['is_author'] = false;
$result = dbquery("SELECT group_id FROM ".$db_prefix."user_groups WHERE group_ident = 'BG01'");
if (dbrows($result)) {
	$data = dbarray($result);
	$variables['is_author'] = checkgroup($data['group_id']);
}
$variables['is_moderator'] = false;
$result = dbquery("SELECT group_id FROM ".$db_prefix."user_groups WHERE group_ident = 'BG02'");
if (dbrows($result)) {
	$data = dbarray($result);
	$variables['is_moderator'] = checkgroup($data['group_id']);
}

// save requested?
if (isset($_POST['save'])) {
	// save the blog entry
	$blog_subject = stripinput($_POST['blog_subject']);
	$blog_text = addslash($_POST['blog_text']);
	if ($settings['tinymce_enabled'] != 1) { $blog_breaks = isset($_POST['blog_breaks']) ? "y" : "n"; } else { $blog_breaks = "n"; }
	$blog_datestamp = 0;
	if ($blog_id) {
		if ($_POST['blog_date']['mday']!="--" && $_POST['blog_date']['mon']!="--" && $_POST['blog_date']['year']!="----") {
			$blog_datestamp = mktime($_POST['blog_date']['hours'],$_POST['blog_date']['minutes'],0,$_POST['blog_date']['mon'],$_POST['blog_date']['mday'],$_POST['blog_date']['year']);
		}
	}
	if ($blog_datestamp == 0) $blog_datestamp = time();
	// adjust the date according to the users timezone
	$blog_datestamp = time_local2system($blog_datestamp);
	$blog_comments = isset($_POST['blog_comments']) ? "1" : "0";
	$blog_ratings = isset($_POST['blog_ratings']) ? "1" : "0";
	if ($blog_id) {
		$result = dbquery("UPDATE ".$db_prefix."blogs SET blog_subject='$blog_subject', blog_text='$blog_text', blog_breaks='$blog_breaks', blog_comments='$blog_comments', blog_ratings='$blog_ratings', blog_editor='".$userdata['user_id']."', blog_edittime='".time()."' WHERE blog_id='$blog_id'");
		redirect(FUSION_SELF."?status=su");
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."blogs (blog_subject, blog_text, blog_breaks, blog_comments, blog_ratings, blog_author, blog_datestamp) VALUES ('$blog_subject', '$blog_text', '$blog_breaks', '$blog_comments', '$blog_ratings', '".$userdata['user_id']."', '$blog_datestamp')");
		redirect(FUSION_SELF."?status=sn");
	}
}

// check the blog_id passed
if (!isset($blog_id) || !isNum($blog_id)) $blog_id = 0;
$variables['blog_id'] = $blog_id;
		
// check the author_id passed
if (!isset($author_id) || !isNum($author_id)) $author_id = 0;
$variables['author_id'] = $author_id;
		
// check the step variable passed
if (!isset($step)) $step = "";
$variables['step'] = $step;

$show_comments = false;
$show_ratings = false;

// process the step variable
switch ($step) {
	case "add":

		// is the current user an author? if not, fallback
		if (!iMEMBER || !$variables['is_author']) {
			fallback(FUSION_SELF);
		}
		$variables['blog_id'] = 0;

		define('LOAD_TINYMCE', true);
		break;

	case "edit":

		// if a blog_id is passed, load it. If not, or not valid, fallback to the blog index page
		if ($blog_id) {
			$result = dbquery("SELECT * FROM ".$db_prefix."blogs WHERE blog_id = '$blog_id'");
			if (dbrows($result) == 0) {
				fallback(FUSION_SELF);
			}
			// populate the form variables
			$data = dbarray($result);
			$variables['blog_id'] = $data['blog_id'];
			$variables['blog_subject'] = $data['blog_subject'];
			$variables['blog_text'] = stripslashes($data['blog_text']);
			$variables['blog_datestamp'] = getdate(time_system2local($data['blog_datestamp']));
			$variables['blog_breaks'] = $data['blog_breaks'] == "y" ? 1 : 0;
			$variables['blog_comments'] = $data['blog_comments'] == "1" ? 1 : 0;
			$variables['blog_ratings'] = $data['blog_ratings'] == "1" ? 1 : 0;
			$variables['blog_author'] = $data['blog_author'];
		} else {
			fallback(FUSION_SELF);
		}

		// is the current user the author or a moderator? if not, fallback
		if (!iMEMBER || ($variables['blog_author'] != $userdata['user_id'] && !$variables['is_moderator'])) {
			fallback(FUSION_SELF);
		}

		define('LOAD_TINYMCE', true);
		break;

	case "delete":

		$result = dbquery("DELETE FROM ".$db_prefix."blogs WHERE blog_id='$blog_id'");
		redirect(FUSION_SELF."?status=del");
		break;

	default:

		// if an author_id is passed, get the blog_id of the most recent blog entry for that author
		if ($author_id != 0 && $blog_id == 0) {
			$result = dbquery("SELECT b.blog_id, u.user_name FROM ".$db_prefix."blogs b, ".$db_prefix."users u WHERE blog_author = '$author_id' AND user_id = blog_author ORDER BY blog_datestamp DESC LIMIT 1");
			if (dbrows($result) == 0) {
				fallback(FUSION_SELF);
			}
			$data = dbarray($result);
			// store the blog_id
			$blog_id = $data['blog_id'];
			$variables['blog_id'] = $blog_id;
			// update the panel title to include the author name
			$title = sprintf($locale['401'], $data['user_name']);
		}
		
		$variables['bloglist'] = array();
		// if a blog_id is passed, load it. If not valid, fallback to the blog index page
		if ($blog_id != 0) {
			$result = dbquery("SELECT b.*, u.user_name, u2.user_name AS edit_name FROM ".$db_prefix."blogs b
					LEFT JOIN ".$db_prefix."users u ON u.user_id = b.blog_author
					LEFT JOIN ".$db_prefix."users u2 ON u2.user_id = b.blog_editor
					WHERE blog_id = '$blog_id' ORDER BY blog_datestamp DESC LIMIT 1");
			if (dbrows($result) == 0) {
				fallback(FUSION_SELF);
			}
			// update the read counter for this blog post
			$result2 = dbquery("UPDATE ".$db_prefix."blogs SET blog_reads = blog_reads+1 WHERE blog_id='$blog_id'");
		} else {
			// not blog_id passed. List the most recent blog entries
			$result = dbquery("SELECT b.*, u.user_name, u2.user_name AS edit_name FROM ".$db_prefix."blogs b
					LEFT JOIN ".$db_prefix."users u ON u.user_id = b.blog_author
					LEFT JOIN ".$db_prefix."users u2 ON u2.user_id = b.blog_editor
					ORDER BY blog_datestamp DESC LIMIT ".intval($settings['numofthreads']/2));
		}
		while ($data = dbarray($result)) {
			// store the blog entry(s)
			$data['blog_text'] = stripslashes($data['blog_text']);
			$variables['bloglist'][] = $data;
		}
		// check if we need to display comments and/or ratings
		if ($blog_id != 0) {
			$show_comments = $variables['bloglist'][0]['blog_comments'];
			$show_ratings = $variables['bloglist'][0]['blog_ratings'];
			$variables['author_id'] = $variables['bloglist'][0]['blog_author'];
		}
		
		// retrieve the full author list
		$variables['list'] = array();
		$result = dbquery("SELECT DISTINCT b.blog_author, u.user_name, count(*) AS count FROM ".$db_prefix."blogs b, ".$db_prefix."users u WHERE b.blog_author = u.user_id GROUP BY blog_author ORDER BY count DESC, user_name ASC");
		while ($data = dbarray($result)) {
			$data['blogs'] = array();
			// get all blog entries of this author, newest first
			$result2 = dbquery("SELECT b.blog_id, b.blog_subject FROM ".$db_prefix."blogs b WHERE b.blog_author = '".$data['blog_author']."' ORDER BY GREATEST(blog_datestamp, blog_edittime) DESC");
			while ($data2 = dbarray($result2)) {
				$data['blogs'][] = $data2;
			}
			$variables['list'][] = $data;
		}

		break;		
}

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'blogs', 'title' => $title, 'template' => 'main.blogs.tpl', 'locale' => "main.blogs");
$template_variables['blogs'] = $variables;

// check if we need to display comments
if ($show_comments) {
	include PATH_INCLUDES."comments_include.php";
	showcomments("B","blogs","blog_id",$blog_id,FUSION_SELF."?blog_id=$blog_id");
}

	// check if we need to display ratings
if ($show_ratings) {
	include PATH_INCLUDES."ratings_include.php";
	showratings("B",$blog_id,FUSION_SELF."?blog_id=$blog_id");
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>