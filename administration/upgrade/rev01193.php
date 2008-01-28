<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/

// upgrade for revision
$_revision = '1193';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(12,00,0,1,13,2008), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Switched from post tracking to thread tracking for unread post status, to make the forums more performant and more scalable<br /><br /><b>Note: This can run for hours if you have millions of posts_unread records.</b>!!!");

// array to store the commands of this update
$commands = array();

// add the user_forum_datestamp field to the users table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##users ADD user_forum_datestamp INT(10) NOT NULL DEFAULT '0' AFTER user_forum_fullscreen");

// create the threads_read table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##threads_read (
  user_id mediumint(8) NOT NULL default '0',
  forum_id smallint(5) NOT NULL default '0',
  thread_id mediumint(8) NOT NULL default '0',
  thread_page smallint(5) NOT NULL default '0',
  thread_last_read int(10) NOT NULL default '0',
  PRIMARY KEY  (user_id,forum_id,thread_id)
) ENGINE=MyISAM;");

// convert posts_unread to threads_read
$commands[] = array('type' => 'function', 'value' => "make_threads_read");

// drop the posts_unread table
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##posts_unread");

function make_threads_read() {
	global $db_prefix;

	// give this function some memory and execution time
	ini_set('memory_limit', '32M');
	ini_set('max_execution_time', '0');

	$threshold = time() - 60*60*24*90; // 90 days
	
	// set the user_forum_datestamp
	$result = dbquery("UPDATE ".$db_prefix."users SET user_forum_datestamp = '".time()."'");
	
	// get all required user info into an array
	$users = array();
	$uresult = dbquery("SELECT DISTINCT user_id, user_name, user_level, user_groups FROM ".$db_prefix."users ORDER BY user_id");
	while ($udata = dbarray($uresult)) {
		$users[] = $udata;
	}
	
	// get all required thread info into an array
	$threads = array();
	$tresult = dbquery("SELECT DISTINCT t.thread_id, t.forum_id, t.thread_subject, t.thread_lastpost, f.forum_access FROM ".$db_prefix."forums f, ".$db_prefix."threads t WHERE t.forum_id = f.forum_id ORDER BY thread_id, forum_id");
	while ($tdata = dbarray($tresult)) {
		$threads[] = $tdata;
	}

	// now process all threads...
	foreach ($threads as $tdata) {
		// only process if the last post of this thread is after the threshold
		if ($tdata['thread_lastpost'] >= $threshold) {
			// loop through the users
			foreach ($users as $udata) {
				// check if this user has access to this forum
				$access = false;
				if ($udata['user_level'] == 103 && $tdata['forum_access'] != "100") { 
					$access = true; 
				} elseif ($udata['user_level'] == 102 && ($tdata['forum_access'] == "0" || $tdata['forum_access'] == "101" || $tdata['forum_access'] == "102")) { 
					$access = true; 
				} elseif ($tdata['forum_access'] == "0" || $tdata['forum_access'] == "101") { 
					$access = true;
				} else {
					$groups = explode(".", substr($udata['user_groups'],1));
					$access = in_array($tdata['forum_access'], $groups);
				}
				if ($access == true) {
					// for every user_id / thread_id combination, check the posts_unread for the oldest unread record
					$result = mysql_query("SELECT MIN(post_time) AS post_time FROM ".$db_prefix."posts_unread WHERE forum_id = '".$tdata['forum_id']."' AND thread_id = '".$tdata['thread_id']."' AND user_id = '".$udata['user_id']."'");
					$pdata = mysql_fetch_assoc($result);
					$lastpost = 0;
					if (is_null($pdata['post_time'])) {
						// mark the thread as completely read
						$lastpost = $tdata['thread_lastpost'];
					} else {
						// an unread record found, mark the thread as read up until this post
						$lastpost = $pdata['post_time'] - 1;
					}
					if ($lastpost > $threshold) {
						$result = mysql_query("INSERT INTO ".$db_prefix."threads_read (user_id, forum_id, thread_id, thread_last_read) VALUES ('".$udata['user_id']."', '".$tdata['forum_id']."', '".$tdata['thread_id']."', '".$lastpost."')");
					}
				}
			}
		}
	}
}
?>