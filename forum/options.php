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

// temp storage for template variables
$variables = array();

// load the locale for this forum module
locale_load("forum.options");

// validate parameters
if (iMEMBER) {
	if (!isset($forum_id) || !isNum($forum_id)) fallback("../index.php");
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='".$forum_id."'"));
	if (!checkgroup($data['forum_access'])) fallback("index.php");
	$forum_mods = explode(".", $data['forum_moderators']);
	if (in_array($userdata['user_id'], $forum_mods) || ($data['forum_posting'] && checkgroup($data['forum_posting']))) { define("iMOD", true); } else { define("iMOD", false); }
} else {
	define("iMOD", false);
}

if (!iMOD && !iSUPERADMIN) fallback("index.php");
if (!isset($thread_id) || !isNum($thread_id)) fallback("../index.php");
if (!isset($step) || $step == "") redirect("viewthread.php?forum_id=$forum_id&amp;thread_id=$thread_id");

// cancel thread delete, redirect back to the thread
if (isset($_POST['canceldelete'])) fallback("viewthread.php?forum_id=$forum_id&amp;thread_id=$thread_id");

// store the option parameters
$variables['step'] = $step;
$variables['forum_id'] = $forum_id;
$variables['thread_id'] = $thread_id;

// renew a thread
if ($step == "renew") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."' WHERE thread_id='$thread_id'");
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='".$forum_id."'"));
	$group_id = $data['forum_access'];
	// Get all posts in this thread
	$result = dbquery("SELECT post_id FROM ".$db_prefix."posts WHERE thread_id = '".$thread_id."'");
	$post_ids = array();
	while ($data = dbarray($result)) {
		$post_ids[] = $data['post_id'];
	}
	// reset all threads_read pointers for this thread
	$result = dbquery("UPDATE ".$db_prefix."threads_read SET thread_last_read = '0' WHERE thread_id = '".$thread_id."'");
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.options.renew', 'title' => $locale['458'], 'template' => 'forum.options.tpl', 'locale' => "forum.options");
	$template_variables['forum.options.renew'] = $variables;
}

// delete thread
if ($step == "delete") {
	$variables['delete_confirmed'] = isset($_POST['deletethread']);
	if ($variables['delete_confirmed']) {
		$tdata2 = dbarray(dbquery("SELECT thread_id,thread_lastpost,thread_lastuser FROM ".$db_prefix."threads WHERE thread_id='$thread_id'"));

		$threads_count = dbcount("(forum_id)", "threads", "forum_id='$forum_id'") - 1;
		$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE thread_id='$thread_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_id='$thread_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."threads_read WHERE thread_id='$thread_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE thread_id='$thread_id'");
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE thread_id='$thread_id'");
		if (dbrows($result) != 0) {
			while ($attach = dbarray($result)) {
				unlink(PATH_ATTACHMENTS.$attach['attach_name']);
				// HV - if a thumb exist, delete that too
				if (file_exists(PATH_ATTACHMENTS.$attach['attach_name'].".thumb")) {
					unlink(PATH_ATTACHMENTS.$attach['attach_name'].".thumb");
				}
				// HV - end of changed code
			}
		}
		$result = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE thread_id='$thread_id'");
		
		if ($threads_count > 0) {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id' AND forum_lastpost='".$tdata2['thread_lastpost']."' AND forum_lastuser='".$tdata2['thread_lastuser']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT forum_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE forum_id='$forum_id' ORDER BY post_datestamp DESC LIMIT 1");
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata2['post_datestamp']."', forum_lastuser='".$pdata2['post_author']."' WHERE forum_id='$forum_id'");
			}
		} else {
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='0', forum_lastuser='0' WHERE forum_id='$forum_id'");
		}
	}
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.options.delete', 'title' => $locale['400'], 'template' => 'forum.options.tpl', 'locale' => "forum.options");
	$template_variables['forum.options.delete'] = $variables;
}

// lock thread
if ($step == "lock") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_locked='1' WHERE thread_id='$thread_id'");
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.options.lock', 'title' => $locale['410'], 'template' => 'forum.options.tpl', 'locale' => "forum.options");
	$template_variables['forum.options.lock'] = $variables;
}

// unload thread
if ($step == "unlock") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_locked='0' WHERE thread_id='$thread_id'");
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.options.unlock', 'title' => $locale['420'], 'template' => 'forum.options.tpl', 'locale' => "forum.options");
	$template_variables['forum.options.unlock'] = $variables;
}

// make this thread sticky
if ($step == "sticky") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_sticky='1' WHERE thread_id='$thread_id'");
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.options.sticky', 'title' => $locale['430'], 'template' => 'forum.options.tpl', 'locale' => "forum.options");
	$template_variables['forum.options.sticky'] = $variables;
}

// make this thread non-sticky
if ($step == "nonsticky") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_sticky='0' WHERE thread_id='$thread_id'");
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.options.sticky', 'title' => $locale['440'], 'template' => 'forum.options.tpl', 'locale' => "forum.options");
	$template_variables['forum.options.sticky'] = $variables;
}

// move thread
if ($step == "move") {
	$variables['move_confirmed'] = isset($_POST['move_thread']);
	if ($variables['move_confirmed']) {
		$new_forum_id = $_POST['new_forum_id'];
		if (!isset($new_forum_id) || !isNum($new_forum_id)) fallback("../index.php");
		if (!dbcount("(forum_id)", "forums", "forum_id='".$new_forum_id."'")) fallback("../index.php");
		$tdata2 = dbarray(dbquery("SELECT thread_id,thread_lastpost,thread_lastuser FROM ".$db_prefix."threads WHERE thread_id='$thread_id'"));

		$threads_count_old = dbcount("(forum_id)", "threads", "forum_id='$forum_id'") - 1;
		$threads_count_new = dbcount("(forum_id)", "threads", "forum_id='$new_forum_id'") + 1;
		
		$result = dbquery("UPDATE ".$db_prefix."threads SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");
		$result = dbquery("UPDATE ".$db_prefix."posts SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");
		$result = dbquery("UPDATE ".$db_prefix."threads_read SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");

		// get the access group number for the new forum
		$result = dbquery("SELECT forum_access from ".$db_prefix."forums WHERE forum_id = '".$new_forum_id."'");
		$udata = dbarray($result);
		$group_id = $udata['forum_access'];

		// we only need to do this if the new forum is not public
		if ($group_id > 0) {
			// remove users from threads_read that don't have read access to the new forum!
			$result = dbquery("
				SELECT DISTINCT u.user_id, u.user_level, u.user_groups 
				FROM ".$db_prefix."threads_read tr
				LEFT JOIN ".$db_prefix."users u ON u.user_id = tr.user_id
				WHERE tr.thread_id = '".$thread_id."'
				");
			while ($udata2 = dbarray($result)) {
				if (($group_id > 100 and $udata2['user_level'] >= $group_id) or preg_match("(^\.{$group_id}|\.{$group_id}\.|\.{$group_id}$)", $udata2['user_groups'])) {
					// ok, user can still access this thread
				} else {
					// user doesn't have access to the new forum. Remove the thread for this user from threads_read
					$result2 = dbquery("DELETE FROM ".$db_prefix."threads_read WHERE user_id = '".$udata['user_id']."' AND thread_id = '".$thread_id."'");
				}
			}
		}		

		// update the forum record
		if ($threads_count_old > 0) {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id' AND forum_lastpost='".$tdata2['thread_lastpost']."' AND forum_lastuser='".$tdata2['thread_lastuser']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT forum_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE forum_id='$forum_id' ORDER BY post_datestamp DESC LIMIT 1");
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata2['post_datestamp']."', forum_lastuser='".$pdata2['post_author']."' WHERE forum_id='$forum_id'");
			}
		} else {
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='0', forum_lastuser='0' WHERE forum_id='$forum_id'");
		}
		
		if ($threads_count_old > 1) {
			$result = dbquery("SELECT forum_lastpost FROM ".$db_prefix."forums WHERE forum_id='$new_forum_id'");
			$fdata2 = dbarray($result);
			if ($tdata2['thread_lastpost'] > $fdata2['forum_lastpost']) {
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$tdata2['thread_lastpost']."', forum_lastuser='".$tdata2['thread_lastuser']."' WHERE forum_id='$new_forum_id'");
			}
		} else {
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$tdata2['thread_lastpost']."', forum_lastuser='".$tdata2['thread_lastuser']."' WHERE forum_id='$new_forum_id'");
		}
	} else {
		// get the data for the forum dropdown
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
			if ($data2['forum_id'] == $forum_id) {
				$data2['selected'] = true;
			} else {
				$data2['selected'] = false;
			}
			$variables['forums'][] = $data2;
		}
	}

	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.options.move', 'title' => $locale['450'], 'template' => 'forum.options.tpl', 'locale' => "forum.options");
	$template_variables['forum.options.move'] = $variables;
}

// merge two threads
if ($step == "merge") {
	$variables['merge_confirmed'] = isset($_POST['merge_threads']);
	if ($variables['merge_confirmed']) {
		$new_thread_id = $_POST['new_thread_id'];
		if (!isset($new_thread_id) || !isNum($new_thread_id)) fallback("../index.php");
		$tdata1 = dbarray(dbquery("SELECT thread_id,thread_lastpost,thread_lastuser FROM ".$db_prefix."threads WHERE thread_id='$thread_id'"));
		$tdata2 = dbarray(dbquery("SELECT thread_id,thread_lastpost,thread_lastuser FROM ".$db_prefix."threads WHERE thread_id='$new_thread_id'"));
		if ($tdata1['thread_lastpost'] > $tdata2['thread_lastpost'])
			$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".$tdata1['thread_lastpost']."', thread_lastuser='".$tdata1['thread_lastuser']."' WHERE thread_id='$new_thread_id'");
		$result = dbquery("UPDATE ".$db_prefix."posts SET thread_id='$new_thread_id' WHERE thread_id='$thread_id'");
		$result = dbquery("UPDATE ".$db_prefix."thread_notify SET thread_id='$new_thread_id' WHERE thread_id='$thread_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_id='$thread_id'");
		// merge the last_read pointers
		$result = dbquery("SELECT user_id, thread_last_read FROM ".$db_prefix."threads_read WHERE thread_id = '".$thread_id."'");
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".$db_prefix."threads_read SET thread_first_read = '".$data['thread_first_read']."' WHERE user_id = '".$data['user_id']."' AND thread_id = '".$new_thread_id."' AND thread_first_read > '".$data['thread_first_read']."'");
			$result2 = dbquery("UPDATE ".$db_prefix."threads_read SET thread_last_read = '".$data['thread_last_read']."' WHERE user_id = '".$data['user_id']."' AND thread_id = '".$new_thread_id."' AND thread_last_read < '".$data['thread_last_read']."'");
		}
		$result = dbquery("DELETE FROM ".$db_prefix."threads_read WHERE thread_id='$thread_id'");
	} else {
		// get the data for the threads dropdown
		$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE forum_id='$forum_id' ORDER BY thread_lastpost DESC");
		$variables['threads'] = array();
		while ($data = dbarray($result)) {
			$data['thread_ident'] = substr('     '.$data['thread_id'], -5).' » '.$data['thread_subject'];
			$variables['threads'][] = $data;
		}
	}
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.options.merge', 'title' => $locale['455'], 'template' => 'forum.options.tpl', 'locale' => "forum.options");
	$template_variables['forum.options.merge'] = $variables;
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
