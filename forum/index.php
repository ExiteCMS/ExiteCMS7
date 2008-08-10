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

// indicate whether or not the user wants to see this module without side panels
define('FULL_SCREEN', (iMEMBER && $userdata['user_forum_fullscreen']));

// temp storage for template variables
$variables = array();

// load the locale for this forum module
locale_load("forum.main");

// load the advertisement include module and get an ad for this forum page
if (file_exists(PATH_MODULES."advertising/get_ad.php")) {
	// load the ad include module
	require_once PATH_MODULES."advertising/get_ad.php";
	$variables['advert'] = get_ad(array(1,2));
} else {
	$variables['advert'] = "";
}

// when is a folder hot?
define('FOLDER_HOT', 20);

define('PAGETITLE', $locale['400']);

// init some variables
$forum_list = ""; $current_cat = "";

// get the list of forums the user has access to
$result = dbquery(
	"SELECT f.*, COUNT(t.thread_id) AS thread_count, MAX(t.thread_lastpost) AS last_post, f2.forum_name AS forum_cat_name, f2.forum_id as cat_id, u.user_id, u.user_name FROM ".$db_prefix."forums f
	LEFT JOIN ".$db_prefix."threads t USING(forum_id)
	INNER JOIN ".$db_prefix."forums f2 ON f.forum_cat = f2.forum_id
	LEFT JOIN ".$db_prefix."users u ON f.forum_lastuser = u.user_id
	WHERE ".groupaccess('f.forum_access')." AND f.forum_cat != '0' GROUP BY forum_id ORDER BY f2.forum_order ASC, f.forum_order ASC"
);

// loop through the results
$variables['forums'] = array();
while ($data = dbarray($result)) {
	// check for a new category break
	if ($data['forum_cat_name'] != $current_cat) {
		$current_cat = $data['forum_cat_name'];
		$data['new_cat'] = true;
	} else {
		$data['new_cat'] = false;
	}
	// get the moderators for this forum
	$moderators = array();
	if ($data['forum_modgroup'] != '0') {
		$result2 = dbquery("SELECT group_id, group_name, group_description FROM ".$db_prefix."user_groups WHERE group_id = '".$data['forum_modgroup']."' AND group_visible & 2");
		if ($data2 = dbarray($result2)) {
			$moderators[] = array('type' => 'G', 'id' => $data2['group_id'], 'name' => $data2['group_description']);
		}
	}
	if ($data['forum_moderators']) {
		$res = "user_id='".str_replace(".", "' OR user_id='", $data['forum_moderators'])."'";
		$result2 = dbquery("SELECT user_id,user_name FROM ".$db_prefix."users WHERE (".$res.")");
		while ($data2 = dbarray($result2)) {
			$moderators[] = array('type' => 'U', 'id' => $data2['user_id'], 'name' => $data2['user_name']);
		}
	}
	$data['moderators'] = $moderators;
	
	// get the unread posts count for this forum
	if (iMEMBER) {
		if ($userdata['user_posts_unread']) {
			$result2 = dbquery("
				SELECT count(*) as unread 
					FROM ".$db_prefix."posts p 
						INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
					WHERE tr.user_id = '".$userdata['user_id']."' 
						AND tr.forum_id = '".$data['forum_id']."' 
						AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
						AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
							OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))"
				);
		} else {
			$result2 = dbquery("
				SELECT count(*) as unread 
					FROM ".$db_prefix."posts p 
						INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
					WHERE tr.user_id = '".$userdata['user_id']."' 
						AND tr.forum_id = '".$data['forum_id']."' 
						AND p.post_author != '".$userdata['user_id']."'
						AND p.post_edituser != '".$userdata['user_id']."'
						AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
						AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
							OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))"
				);
		} 
		$data['unread_posts'] = ($result2 ? mysql_result($result2, 0) : 0);
	} else {
		$data['unread_posts'] = 0;
	}
	
	// get the total post count for this forum
	$data['total_posts'] = dbcount("(post_id)", "posts", "forum_id='".$data['forum_id']."'");

	// store this record for use in the template
	$variables['forums'][] = $data;
}

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'forum.index', 'template' => 'forum.index.tpl', 'locale' => "forum.main");
$template_variables['forum.index'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
