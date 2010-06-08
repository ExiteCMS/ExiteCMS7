<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2010 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id:: index.php 1935 2008-10-29 23:42:42Z WanWizard                 $|
+----------------------------------------------------------------------+
| Last modified by $Author:: WanWizard                                $|
| Revision number $Rev:: 1935                                         $|
+---------------------------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// indicate whether or not the user wants to see this module without side panels
define('FULL_SCREEN', (iMEMBER && $userdata['user_forum_fullscreen']));

// temp storage for template variables
$variables = array();

// load the locale for this forum module
locale_load("forum.tracking");

// variable initialisation
$variables['tracking'] = array();

// validate the thread_id parameter
if (!isset($thread_id) || (!isNum($thread_id) && $thread_id != 'all')) $thread_id = false;

// make sure rowstart has a value
if (!isset($rowstart) || !isNum($rowstart) || $rowstart < 0) $rowstart = 0;

if (iMEMBER) {
	// was a reset of a thread requested?
	if ($thread_id) {
		if (isNum($thread_id)) {
			$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE notify_user = ".$userdata['user_id']." AND thread_id = ".$thread_id);
		} elseif ($thread_id == 'all') {
			$result = _debug("DELETE FROM ".$db_prefix."thread_notify WHERE notify_user = ".$userdata['user_id']);
		}
	}
	// get the list of topics being tracked by the user
	$result = dbquery("SELECT f.forum_id, f.forum_name, t.thread_subject, n.*
		FROM ".$db_prefix."thread_notify n
		JOIN ".$db_prefix."threads t USING ( thread_id )
		JOIN ".$db_prefix."forums f USING ( forum_id )
		WHERE notify_user = ".$userdata['user_id']."
		ORDER BY forum_name, thread_subject");
	// get the row count of the result
	$variables['rows'] = dbrows($result);
	// make sure rowstart is within bounds
	if ($rowstart >= $variables['rows']) {
		$rowstart = $variables['rows']-1;
	}
	// redirect back to the list if we removed a thread
	if ($thread_id) {
		redirect(BASEDIR.'forum/tracking.php?rowstart='.$rowstart);
	}
	// fetch the rows for this page
	$i = 0;
	while ($data = dbarray($result)) {
		if ($i >= $rowstart && count($variables['tracking']) < $settings['numofthreads']) {
			$variables['tracking'][] = $data;
		}
		$i++;
	}
}

// store the rowstart value
$variables['rowstart'] = $rowstart;

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'forum.tracking', 'template' => 'forum.tracking.tpl', 'locale' => "forum.tracking");
$template_variables['forum.tracking'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
