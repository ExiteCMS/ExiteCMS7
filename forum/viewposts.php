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

// temp storage for template variables
$variables = array();

// defines
define('ITEMS_PER_PAGE', 20);

// load the forum functions include
require_once PATH_INCLUDES."forum_functions_include.php";

// set the panel title
$title = $locale['027a'];

// check if we have a forum ID to filter on
if (!isset($forum_id) || !isNum($forum_id)) $forum_id = false;

// is a thread time limit defined for guest users?
$thread_limit = ($settings['forum_guest_limit']== 0 || iMEMBER) ? 0 : (time() - $settings['forum_guest_limit'] * 86400);

// check if we have anything to display
$result = dbquery(
	"SELECT tp.*, tf.* FROM ".$db_prefix."posts tp
    INNER JOIN ".$db_prefix."threads th USING(thread_id)
	INNER JOIN ".$db_prefix."forums tf ON tp.forum_id = tf.forum_id
	WHERE ".($thread_limit==0?"":" th.thread_lastpost > ".$thread_limit." AND ").groupaccess('forum_access').($forum_id ? " AND tp.forum_id = '$forum_id'" : "")
);

$rows = dbrows($result);
$variables['rows'] = $rows;

// make sure rowstart has a valid value
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
$variables['rowstart'] = $rowstart;

$result = dbquery(
	"SELECT tp.*, tf.* FROM ".$db_prefix."posts tp
    INNER JOIN ".$db_prefix."threads th USING(thread_id)
	INNER JOIN ".$db_prefix."forums tf ON tp.forum_id = tf.forum_id
	WHERE ".($thread_limit==0?"":" th.thread_lastpost > ".$thread_limit." AND ").groupaccess('forum_access').($forum_id ? " AND tp.forum_id = '$forum_id'" : "")."
	ORDER BY post_datestamp DESC 
	LIMIT $rowstart,".ITEMS_PER_PAGE
);

$posts = array();
while ($data = dbarray($result)) {
	$data['poll'] = fpm_panels_poll_exists($data['forum_id'], $data['thread_id']);
	$posts[] = $data;
}
$variables['posts'] = $posts;

// pagenav url
if ($forum_id) {
	$variables['pagenav_url'] = FUSION_SELF."?forum_id=".$forum_id."&amp;";
}

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'viewposts', 'title' => $title, 'template' => 'forum.viewposts.tpl', 'locale' => 'forum.main');
$template_variables['viewposts'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
