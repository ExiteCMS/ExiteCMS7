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

// validate the parameters
if (!FUSION_QUERY || !$forum_id || !isNum($forum_id)) fallback("index.php");

// indicate whether or not the user wants to see this module without side panels
define('FULL_SCREEN', (iMEMBER && $userdata['user_forum_fullscreen']));

// temp storage for template variables
$variables = array();

// load the locales for this forum module
locale_load("forum.main");
locale_load("admin.forum_polls");

// needed for localisation functions
require_once PATH_INCLUDES."geoip_include.php";

// shared forum functions include
require_once PATH_INCLUDES."forum_functions_include.php";

// load the advertisement include module and get an ad for this forum page
if (file_exists(PATH_MODULES."advertising/get_ad.php")) {
	require_once PATH_MODULES."advertising/get_ad.php";
	$variables['ad1'] = get_ad(array(1,2,5));
	$variables['ad1'] = get_ad(array(4,5));
} else {
	$variables['ad1'] = "";
	$variables['ad2'] = "";
}

// when is a folder hot?
define('FOLDER_HOT', $settings['folderhotlevel']);

// get information about this forum
$result = dbquery(
	"SELECT f.*, f2.forum_name AS forum_cat_name, f2.forum_id as forum_cat_id
	FROM ".$db_prefix."forums f
	INNER JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$forum_id."'"
);
// bail out if the requested forum does not exist
if (!dbrows($result)) {
	fallback("index.php");
}
$data = dbarray($result);
$variables['forum'] = $data;
define('PAGETITLE', $locale['401'].": ".$data['forum_name']);

// bail out if the user doesn't have access to this forum, or requested a forum category ID
if (!checkgroup($data['forum_access']) || !$data['forum_cat']) {
	fallback("index.php");
}

// if a forum rules custompage is given, check if it exists
$variables['rulespage_defined'] = false;
if ($data['forum_rulespage']) {
	$result2 = dbquery("SELECT page_title FROM ".$db_prefix."custom_pages WHERE page_id = '".$data['forum_rulespage']."'");
	if (dbrows($result2)) {
		$variables['rulespage_defined'] = true;
		$data2 = dbarray($result2);
		$variables['forum_rulestitle'] = $data2['page_title'];
	}
}
// check if the user is allowed to post in this forum
$can_post = checkgroup($data['forum_posting']);
$variables['user_can_post'] = $can_post;

// this forums caption
$variables['forum_cat_id'] = $data['forum_cat_id'];
$variables['forum_cat_name'] = $data['forum_cat_name'];
$variables['forum_name'] = $data['forum_name'];

// if a mark-all-read, was requested, check if it's possible, then do it before continuing
if (iMEMBER && $can_post && isset($action) && $action == "markallread") {
	$result = dbquery("
		SELECT p.thread_id
			FROM ".$db_prefix."posts p
			INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id
			WHERE tr.user_id = '".$userdata['user_id']."'
				AND tr.forum_id = '".$forum_id."'
				AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
				AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
					OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))
			GROUP BY p.thread_id
		");
	// update the last_read datestamp of all threads found
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."threads_read SET thread_first_read = '0', thread_last_read = '".time()."' WHERE user_id = '".$userdata['user_id']."' AND thread_id = '".$data['thread_id']."'");
	}
}

// get the number of unread posts for this user in this forum
if (iMEMBER) {
	if ($userdata['user_posts_unread']) {
		$result = dbquery("
			SELECT count(*) as unread 
				FROM ".$db_prefix."posts p 
					INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
				WHERE tr.user_id = '".$userdata['user_id']."' 
					AND tr.forum_id = '".$forum_id."'
					AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
					AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
						OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))"
			);
	} else {
		$result = dbquery("
			SELECT count(*) as unread 
				FROM ".$db_prefix."posts p 
					INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
				WHERE tr.user_id = '".$userdata['user_id']."' 
					AND tr.forum_id = '".$forum_id."'
					AND p.post_author != '".$userdata['user_id']."'
					AND p.post_edituser != '".$userdata['user_id']."'
					AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
					AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
						OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))"
			);
	} 
	$variables['unread_posts'] = ($result ? mysql_result($result, 0) : 0);
} else {
	$variables['unread_posts'] = 0;
}

// get the number of threads in this forum
$rows = dbrows(dbquery("SELECT * FROM ".$db_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='0'"));
$variables['rows'] = $rows;

// make sure rowstart has a value
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
$variables['rowstart'] = $rowstart;

// is a thread time limit defined for guest users?
$thread_limit = ($settings['forum_guest_limit']== 0 || iMEMBER) ? 0 : (time() - $settings['forum_guest_limit'] * 86400);

// get the threads to fill this page
$result = dbquery(
	"SELECT t.*, MAX(p.post_id) AS last_post, COUNT(p.post_id) AS thread_replies, tu1.user_name AS user_author, tu1.user_ip AS user_ip, 
			tu2.user_name AS user_lastuser, tu1.user_cc_code AS user_cc_code FROM ".$db_prefix."threads t
		INNER JOIN ".$db_prefix."posts p ON t.thread_id = p.thread_id
		LEFT JOIN ".$db_prefix."users tu1 ON t.thread_author = tu1.user_id
		LEFT JOIN ".$db_prefix."users tu2 ON t.thread_lastuser = tu2.user_id
		WHERE t.forum_id = '".$forum_id."'".($thread_limit==0?"":" AND t.thread_lastpost > ".$thread_limit)."
		GROUP BY thread_id
		ORDER BY thread_sticky DESC, thread_lastpost DESC
		LIMIT ".$rowstart.", ".$settings['numofthreads']
);
$variables['threads'] = array();
while ($data = dbarray($result)) {
	// get the country flag of the last poster
    if ($settings['forum_flags'] == 0) {
    	$cc_flag = "";
    } else {
		$cc_flag = GeoIP_Code2Flag($data['user_cc_code']);
		if ($cc_flag == "" || empty($data['user_ip']) || $data['user_ip'] == "X" || $data['user_ip'] == "0.0.0.0") {
			$cc_flag = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	}
	$data['cc_flag'] = $cc_flag;
	// get the unread count for this thread (skip if the forum does not contain any unread posts)
	if (isset($userdata['user_id']) && $variables['unread_posts']) {
		$result2 = dbquery("
			SELECT count(*) as unread, MIN(p.post_id) as post_id
				FROM ".$db_prefix."posts p 
				INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
				WHERE tr.user_id = '".$userdata['user_id']."' 
					AND tr.thread_id = '".$data['thread_id']."'
					AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].") 
					AND (p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
				GROUP BY tr.thread_id
			", false);
		if (dbrows($result2) != 0) {
			$data2 = dbarray($result2);
			$data['unread_posts'] = $data2['unread'];
			$data['first_unread_post'] = $data2['post_id'];
		} else {
			$data['unread_posts'] = 0;
			$data['first_unread_post'] = 0;
		}
	} else {
		$data['unread_posts'] = 0;
		$data['first_unread_post'] = 0;
	}
	// correct the number of replies
	$data['thread_replies'] = max(0, $data['thread_replies'] - 1);
	// check if there is a poll attached to this thread
	$data['is_poll'] = fpm_panels_poll_exists($forum_id, $data['thread_id']);
	// store this record
	$variables['threads'][] = $data;
}

// generate a list of forums, for the forum switch dropdown
$result = dbquery(
	"SELECT f.forum_id, f.forum_name, f2.forum_name AS forum_cat_name
	FROM ".$db_prefix."forums f
	INNER JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
$variables['forums'] = array();
$current_cat = "";
while ($data2 = dbarray($result)) {
	if ($data2['forum_cat_name'] != $current_cat) {
		$data2['forum_new_cat'] = true;
		$current_cat = $data2['forum_cat_name'];
	} else {
		$data2['forum_new_cat'] = false;
	}
	if ($data2['forum_id'] == $data['forum_id']) {
		$data2['selected'] = true;
	} else {
		$data2['selected'] = false;
	}
	$variables['forums'][] = $data2;
}

// other variables needed in the template
$variables['forum_id'] = $forum_id;
$variables['pagenav_url'] = FUSION_SELF."?forum_id=".$forum_id."&amp;";

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'forum.viewforum', 'template' => 'forum.viewforum.tpl', 'locale' => array("forum.main","admin.forum_polls"));
$template_variables['forum.viewforum'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
