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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// validate the parameters
if (!FUSION_QUERY || !$forum_id || !isNum($forum_id)) fallback("index.php");

// indicate whether or not the user wants to see this module without side panels
define('FULL_SCREEN', (iMEMBER && $userdata['user_forum_fullscreen']));

// temp storage for template variables
$variables = array();

// load the locales for this forum module
require_once PATH_LOCALE.LOCALESET."forum/main.php";
require_once PATH_LOCALE.LOCALESET."admin/forum_polls.php";

// needed for localisation functions
require_once PATH_INCLUDES."geoip_include.php";

// shared forum functions include
require_once PATH_INCLUDES."forum_functions_include.php";

// load the advertisement include module
require_once PATH_INCLUDES."advertisement.php";
$variables['advert'] = get_advert(array(1,2));

// define how many threads per page we want
define('ITEMS_PER_PAGE', 20);

// when is a folder hot?
define('FOLDER_HOT', 20);

// get information about this forum
$result = dbquery(
	"SELECT f.*, f2.forum_name AS forum_cat_name, f2.forum_id as forum_cat_id
	FROM ".$db_prefix."forums f
	LEFT JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$forum_id."'"
);
// bail out if the requested forum does not exist
if (!dbrows($result)) {
	fallback("index.php");
}
$data = dbarray($result);
$variables['forum'] = $data;

// if a forum rules custompage is given, check if it exists
$result2 = dbquery("SELECT page_title FROM ".$db_prefix."custom_pages WHERE page_id = '".$data['forum_rulespage']."'");
if (!$result2) {
	$data['rulespage_defined'] = false;
} else {
	$data['rulespage_defined'] = true;
	$data2 = dbarray($result2);
	$data['forum_rulestitle'] = $data2['page_title'];
}

// bail out if the user doesn't have access to this forum, or requested a forum category ID
if (!checkgroup($data['forum_access']) || !$data['forum_cat']) {
	fallback("index.php");
}

// check if the user is allowed to post in this forum
$can_post = checkgroup($data['forum_posting']);
$variables['user_can_post'] = $can_post;

// if a mark-all-read, was requested, check if it's possible, then do it before continuing
if (iMEMBER && $can_post && isset($action) && $action == "markallread") {
	$result = dbquery("DELETE FROM ".$db_prefix."posts_unread WHERE user_id = '".$userdata['user_id']."' AND forum_id = '".$forum_id."'");
}

// this forums caption
$variables['forum_cat_id'] = $data['forum_cat_id'];
$variables['forum_cat_name'] = $data['forum_cat_name'];
$variables['forum_name'] = $data['forum_name'];

// check for unread posts in this forum
$unread_posts = iMEMBER ? dbcount("(*)", "posts_unread", "user_id = '".$userdata['user_id']."' AND forum_id = '".$forum_id."'") : 0;
$variables['unread_posts'] = $unread_posts;

// get the number of threads in this forum
$rows = dbrows(dbquery("SELECT * FROM ".$db_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='0'"));
$variables['rows'] = $rows;

// make sure rowstart has a value
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
$variables['rowstart'] = $rowstart;

// get the threads to fill this page
$result = dbquery(
	"SELECT t.*, MAX(p.post_id) AS last_post, COUNT(p.post_id) AS thread_replies, tu1.user_name AS user_author, tu1.user_ip AS user_ip, 
			tu2.user_name AS user_lastuser, tu1.user_cc_code AS user_cc_code FROM ".$db_prefix."threads t
		LEFT JOIN ".$db_prefix."posts p USING ( thread_id )
		LEFT JOIN ".$db_prefix."users tu1 ON t.thread_author = tu1.user_id
		LEFT JOIN ".$db_prefix."users tu2 ON t.thread_lastuser = tu2.user_id
		WHERE t.forum_id = '".$forum_id."'
		GROUP BY thread_id
		ORDER BY thread_sticky DESC, thread_lastpost DESC
		LIMIT ".$rowstart.", ".ITEMS_PER_PAGE
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
	// get the unread count for this thread
	if (isset($userdata['user_id'])) {
		$data['unread_posts'] = dbcount("(*)", "posts_unread", "user_id = ".$userdata['user_id']." AND forum_id = ".$data['forum_id']." AND thread_id = ".$data['thread_id']);
		$result2 = dbquery("SELECT MIN(post_id) as post_id FROM ".$db_prefix."posts_unread WHERE user_id = ".$userdata['user_id']." AND forum_id = ".$data['forum_id']." AND thread_id = ".$data['thread_id']);
		if (dbrows($result2) != 0) {
			$data2 = dbarray($result2);
			$data['first_unread_post'] = $data2['post_id'];
		} else {
			$data['first_unread_post'] = 0;
		}
	} else {
		$data['unread_posts'] = 0;
		$data['first_unread_post'] = 0;
	}
	// number of pages of posts in this thread
	$data['thread_pages'] = ceil($data['thread_replies'] / ITEMS_PER_PAGE) + 1;
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
$template_panels[] = array('type' => 'body', 'name' => 'forum.viewforum', 'template' => 'forum.viewforum.tpl', 'locale' => array(PATH_LOCALE.LOCALESET."forum/main.php",PATH_LOCALE.LOCALESET."admin/forum_polls.php"));
$template_variables['forum.viewforum'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>