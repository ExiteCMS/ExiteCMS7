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
if (!FUSION_QUERY || !isset($forum_id) || !isNum($forum_id) || !isset($thread_id) || !isNum($thread_id)) fallback("index.php");

// load the locales for this forum module
locale_load("forum.main");
locale_load("admin.forum_polls");

// needed for localisation functions
require_once PATH_INCLUDES."geoip_include.php";

// shared forum functions include
require_once PATH_INCLUDES."forum_functions_include.php";

// indicate whether or not the user wants to see this module without side panels
define('FULL_SCREEN', (iMEMBER && $userdata['user_forum_fullscreen']));

// temp storage for template variables
$variables = array();

// render_post defines
define('SHOW_QUOTE_BUTTON', true);		// show the reply-using-a-quote button
define('SHOW_REPLY_BUTTON', true);		// show the reply-using-a-blank-message button
define('REPLY_AS_QUOTE', false);		// show the reply button with the quote functionality
define('DOWNLOAD_IMAGES', false);		// download images or show them in the browser

// poll initialisation code
$fpm_result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id='$forum_id'");
if (dbrows($fpm_result) != 0) {
	$fpm_settings = dbarray($fpm_result);
	$fpm_settings['forum_exists'] = 1;
} else {
	$fpm_settings = dbarray(dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id='0'"));
}
if (!defined('FPM_ACCESS')) {
	if (!iMEMBER) {
		if ($fpm_settings['guest_permissions'] != 0) {
			define("FPM_ACCESS", true);
		}
	} elseif ($fpm_settings['enable_polls'] == 1 && $fpm_settings['vote_permissions'] != "") {
		$temp_array = explode(".", $fpm_settings['vote_permissions']);
		for($i = 0; $i < count($temp_array); $i ++) {
			if (isNum($temp_array[$i])) {
				if ($userdata['user_id'] == $temp_array[$i]) { define("FPM_ACCESS", true); }
			} else {
				if (checkgroup(substr($temp_array[$i], 1))) { define("FPM_ACCESS", true); }
			}
		}
	} else {
		define("FPM_ACCESS", false);
	}
	if (!defined('FPM_ACCESS')) {
		define("FPM_ACCESS", false);
	}
}
if (FPM_ACCESS) {
	$fpm = array();
	$fpm['poll_id'] = isset($_POST['fpm']['id']) && isNum($_POST['fpm']['id']) ? $_POST['fpm']['id'] : 0;
	$fpm['vote_selection'] = isset($_POST['fpm']['option']) && isNum($_POST['fpm']['option']) ? $_POST['fpm']['option'] : 0;
	$fpm['forum_id'] = isset($_GET['forum_id']) && isNum($_GET['forum_id']) ? $_GET['forum_id'] : 0;
	$fpm['thread_id'] = isset($_GET['thread_id']) && isNum($_GET['thread_id']) ? $_GET['thread_id'] : 0;
}

// store the parameters
$variables['forum_id'] = $forum_id;
$variables['thread_id'] = $thread_id;

// get information about this forum
$result = dbquery(
	"SELECT f.*, f2.forum_name AS forum_cat_name
	FROM ".$db_prefix."forums f
	INNER JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$forum_id."'"
);
// bail out if the requested forum does not exist
if (!dbrows($result)) {
	fallback("index.php");
}

// store the forum information
$fdata = dbarray($result);
$variables['forum'] = $fdata;

// bail out if the user doesn't have access to this forum, or requested a forum category ID
if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) {
	fallback("index.php");
}

// check if the user is allowed to post in this forum
$can_post = checkgroup($fdata['forum_posting']);
$variables['user_can_post'] = $can_post;

// check if the user is a moderator of this forum
$forum_mods = explode(".", $fdata['forum_moderators']);
if (iMEMBER && (in_array($userdata['user_id'], $forum_mods) || ($fdata['forum_modgroup'] && checkgroup($fdata['forum_modgroup'])))) {
	define("iMOD", true);
} else {
	define("iMOD", false);
}

// check if this user is allowed to blacklist
$variables['user_can_blacklist'] = checkrights("B");

// check if there is a thread time limit defined for guests
$thread_limit = ($settings['forum_guest_limit']== 0 || iMEMBER) ? 0 : (time() - $settings['forum_guest_limit'] * 86400);

// get information about the current thread
$result = dbquery(
	"SELECT * FROM ".$db_prefix."threads
	WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'".($thread_limit==0?"":" AND thread_lastpost > ".$thread_limit)
);
// bail out if the requested thread does not exist
if (!dbrows($result)) {
	fallback("viewforum.php?forum_id=".$forum_id);
}

// store the thread information
$tdata = dbarray($result);
$variables['thread'] = $tdata;
define('PAGETITLE', $locale['402'].": ".$tdata['thread_subject']);

// update the view counter for this thread
$result = dbquery("UPDATE ".$db_prefix."threads SET thread_views=thread_views+1 WHERE thread_id='$thread_id'");

// process the users quick reply to this thread
if (iMEMBER && $can_post && isset($_POST['postquickreply'])) {
	$flood = false;
	$message = stripmessageinput(censorwords($_POST['message']));
	if ($message != "") {
		if (!iSUPERADMIN) {
			// for non-webmasters, check for post flooding
			$result = dbquery("SELECT MAX(post_datestamp) AS last_post FROM ".$db_prefix."posts WHERE post_author='".$userdata['user_id']."'");
			if (dbrows($result) > 0) {
				$data = dbarray($result);
				if ((time() - $data['last_post']) < $settings['flood_interval']) {
					$flood = true;
					$result = dbquery("INSERT INTO ".$db_prefix."flood_control (flood_ip, flood_userid, flood_timestamp) VALUES ('".USER_IP."', '".$userdata['user_id']."', '".time()."')");
					if (dbcount("(flood_ip)", "flood_control", "flood_ip='".USER_IP."' AND flood_userid='".$userdata['user_id']."'") > 4) {
						$result = dbquery("UPDATE ".$db_prefix."users SET user_status='1', user_ban_reason='".$locale['530']."' WHERE user_id='".$userdata['user_id']."'");
						redirect("post.php?action=quickreply&forum_id=$forum_id&thread_id=$thread_id&post_id=0&errorcode=2");
					} else {
						redirect("post.php?action=quickreply&forum_id=$forum_id&thread_id=$thread_id&post_id=0&errorcode=1");
					}
				}
			}
		}
		// check if this isn't a reload, back-post, or double submit
		if (isset($_SESSION['posts'][$random_id])) {
			redirect("post.php?action=quickreply&forum_id=$forum_id&thread_id=$thread_id&post_id=0&errorcode=3");
		} else {
			if (!$flood) {
				if (!isset($_SESSION['posts']) || !is_array($_SESSION['posts'])) $_SESSION['posts'] = array();
				$_SESSION['posts'][$random_id] = time()+60*60*12;
				$sig = ($userdata['user_sig'] ? '1' :'0');
				$smileys = isset($_POST['disable_smileys']) ? "0" : "1";
				$subject = "RE: ".stripinput(censorwords($tdata['thread_subject']));
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
				$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."', thread_lastuser='".$userdata['user_id']."' WHERE thread_id='$thread_id'");
				$result = dbquery("INSERT INTO ".$db_prefix."posts (forum_id, thread_id, post_subject, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_cc, post_edituser, post_edittime) VALUES ('$forum_id', '$thread_id', '$subject', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".time()."', '".USER_IP."', '".$userdata['user_cc_code']."', '0', '0')");
				$newpost_id = mysql_insert_id();
				$result = dbquery("UPDATE ".$db_prefix."users SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
				// check if we need to notify people
				if ($settings['thread_notify']) {
					$result = dbquery(
						"SELECT tn.*, tu.user_id,tu.user_name,tu.user_email,tu.user_locale FROM ".$db_prefix."thread_notify tn
						LEFT JOIN ".$db_prefix."users tu ON tn.notify_user=tu.user_id
						WHERE thread_id='$thread_id' AND notify_user!='".$userdata['user_id']."' AND notify_status='1'
					");
					if (dbrows($result)) {
						require_once PATH_INCLUDES."sendmail_include.php";
						$data2 = dbarray(dbquery("SELECT thread_subject FROM ".$db_prefix."threads WHERE thread_id='$thread_id'"));
						$link = $settings['siteurl']."forum/viewthread.php?forum_id=$forum_id&thread_id=$thread_id&pid=$post_id#post_$post_id";
						while ($data = dbarray($result)) {
							// get the message text in the users own locale
							$message_el1 = array("{USERNAME}", "{THREAD_SUBJECT}", "{THREAD_URL}", "{SITE_NAME}", "{SITE_WEBMASTER}");
							$message_el2 = array($data['user_name'], $data2['thread_subject'], $link, html_entity_decode($settings['sitename']), html_entity_decode($settings['siteusername']));
							$message_subject = dbarray(dbquery("SELECT locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$data['user_locale']."' AND locales_name = 'forum.post' and locales_key = '550'"));
							$message_subject = str_replace("{THREAD_SUBJECT}", $data2['thread_subject'], $message_subject['locales_value']);
							$message_content = dbarray(dbquery("SELECT locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$data['user_locale']."' AND locales_name = 'forum.post' and locales_key = '551'"));
							$message_content = str_replace($message_el1, $message_el2, $message_content['locales_value']);
							$err = sendemail($data['user_name'],$data['user_email'],$settings['siteusername'],($settings['newsletter_email'] != "" ? $settings['newsletter_email'] : $settings['siteemail']),$message_subject,$message_content);
						}
						$result = dbquery("UPDATE ".$db_prefix."thread_notify SET notify_status='0' WHERE thread_id='$thread_id' AND notify_user != '".$userdata['user_id']."'");
					}
					if ($userdata['user_posts_track']) {
						$result = dbquery("INSERT INTO ".$db_prefix."thread_notify (thread_id, notify_datestamp, notify_user, notify_status) VALUES('$thread_id', '".time()."', '".$userdata['user_id']."', '1')");
					}
				}
				redirect("post.php?action=quickreply&forum_id=$forum_id&thread_id=$thread_id&post_id=$newpost_id&errorcode=0");
			}
		}
	}
}

// process the poll vote (if any)
fpm_vote();

// if the user has a notify active on this thread, set the notify flag
if (iMEMBER && $settings['thread_notify'] && dbcount("(thread_id)", "thread_notify", "thread_id='$thread_id' AND notify_user='".$userdata['user_id']."'")) {
	$result = dbquery("UPDATE ".$db_prefix."thread_notify SET notify_datestamp='".time()."', notify_status='1' WHERE thread_id='$thread_id' AND notify_user='".$userdata['user_id']."'");
	$variables['has_thread_notify'] = true;
} else {
	$variables['has_thread_notify'] = false;
}

// number of posts in this thread
$rows = dbcount("(thread_id)", "posts", "thread_id='$thread_id'");
$variables['rows'] = $rows;

// number of unread posts in this thread
if (iMEMBER) {
	if ($userdata['user_posts_unread']) {
		// include the users own posts
		$result = dbquery("
			SELECT count(*) as unread, tr.thread_first_read, tr.thread_last_read
				FROM ".$db_prefix."posts p
				INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id
				WHERE tr.user_id = '".$userdata['user_id']."'
					AND tr.thread_id = '".$thread_id."'
					AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
					AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
						OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))
				GROUP BY tr.thread_id
			");
	} else {
		// filter the users own posts
		$result = dbquery("
			SELECT count(*) as unread, tr.thread_first_read, tr.thread_last_read
				FROM ".$db_prefix."posts p
				INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id
				WHERE tr.user_id = '".$userdata['user_id']."'
					AND p.post_author != '".$userdata['user_id']."'
					AND p.post_edituser != '".$userdata['user_id']."'
					AND tr.thread_id = '".$thread_id."'
					AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
					AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
						OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))
				GROUP BY tr.thread_id
			");
	}
	if (dbrows($result)) {
		$data = dbarray($result);
		$variables['unread_posts'] = $data['unread'];
		$thread_first_read = $data['thread_first_read'];
		$thread_last_read = $data['thread_last_read'];
	} else {
		$variables['unread_posts'] = 0;
		$thread_first_read = 0;
		$thread_last_read = time();
	}
} else {
	$variables['unread_posts'] = 0;
	$thread_first_read = 0;
	$thread_last_read = time();
}

//if a specific post is requested, find out on which page it is, and set rowstart accordingly
if (isset($pid) && isNum($pid)) {
	$reply_count = dbcount("(post_id)", "posts", "thread_id='".$tdata['thread_id']."' AND post_id<'".$pid."'");
	$rowstart = ($reply_count - ($reply_count % $settings['numofthreads']));
}
// initialise our rowstart pointer if still needed
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
$variables['rowstart'] = $rowstart;

// check if there's a poll attached to this thread
$variables['thread_has_poll'] = fpm_view();

// init first_post_datestamp, needed to update threads_read later
$first_post_datestamp = 4294967295;

// init last_post_datestamp, needed to update threads_read later
$last_post_datestamp = 0;

// get the posts for this page of the thread
if ($rows != 0) {
	$result = dbquery(
		"SELECT p.*, u.*, u2.user_name AS edit_name, u2.user_status AS edit_status FROM ".$db_prefix."posts p
		LEFT JOIN ".$db_prefix."users u ON p.post_author = u.user_id
		LEFT JOIN ".$db_prefix."users u2 ON p.post_edituser = u2.user_id AND post_edituser > '0'
		WHERE p.thread_id='$thread_id' ORDER BY post_sticky DESC, post_datestamp ASC LIMIT $rowstart,".$settings['numofthreads']
	);
	$variables['posts'] = array();
	while ($data = dbarray($result)) {

		// if activated, get the online status of this post author
		if ($settings['forum_user_status'] && $data['post_author'] > 0) {
			// get the online status for this user
			$data2 = dbarray(dbquery("SELECT online_lastactive FROM ".$db_prefix."online WHERE online_user = '".$data['post_author']."' LIMIT 1"));
			$data['user_online'] = empty($data2['online_lastactive']) ? 0 : 1;
		} else {
			$data['user_online'] = 0;
		}

		// default, show no ranking information
		$data['show_ranking'] = false;

		// check for a system-post (author = 0 ), use some of the webmasters details for this
		if ($data['post_author'] == 0) {
			$data['user_name'] = $locale['sysusr'];
			$data['user_posts'] = "-";
			$data2 = dbarray(dbquery("SELECT user_level, user_joined FROM ".$db_prefix."users WHERE user_id = '1'"));
			$data['user_joined'] = $data2['user_joined'];
			$data['user_level'] = 0;
			$data['user_location'] = "-";
			$data['user_sig'] = "";
			$data['user_status'] = "0";
		} else {
			// check if a ranking is defined for this poster
			$result2 = dbquery("SELECT * FROM ".$db_prefix."forum_ranking WHERE rank_posts_from <= '".$data['user_posts']."' AND rank_posts_to >= '".$data['user_posts']."' ORDER BY rank_order");
			while ($data2 = dbarray($result2)) {
				// get the grouplist for this ranking
				if (strpos($data2['rank_groups'], ",")) {
					$groups = explode(",", $data2['rank_groups']);
				} else {
					if (empty($data2['rank_groups'])) {
						$groups = "";
					} else {
						$groups = array($data2['rank_groups']);
					}
				}
				if (is_array($groups)) {
					// check for group matching as well, start with the opposite of the 'bail out' value
					$ranking_match = $data2['rank_groups_and'] ? false : true;
					foreach($groups as $group) {
						if ($data2['rank_groups_and'] == 0) {
							// all should match
							if (!checkusergroup($data['post_author'], $group)) {
								// bail out if a non-match has been found
								$ranking_match = false;
								break;
							}
						} else {
							// one match is sufficient
							if (checkusergroup($data['post_author'], $group)) {
								$ranking_match = true;
								break;
							}
						}
					}
				} else {
					// no groups to test, must be a match on posts range
					$ranking_match = true;
				}
				// if we had a match, add the ranking information to the post data
				if ($ranking_match) {
					$data['ranking'] = $data2;
					$data['show_ranking'] = true;
					break;
				}
			}
		}

		// if there's a reply id, get the user_name
		if ($data['post_reply_id'] != 0) {
			$reply_to = dbarray(dbquery("SELECT user_name FROM ".$db_prefix."users u, ".$db_prefix."posts p WHERE p.post_id = '".$data['post_reply_id']."' AND p.post_author = u.user_id"));
			if (is_array($reply_to))
				$data['post_reply_username'] = $reply_to['user_name'];
			else
				$data['post_reply_username'] = "";
		}
		// check if this post is read or unread (respect the users unread posts profile setting)
		if (iMEMBER && $userdata['user_posts_unread'] == false && ( $data['post_author'] == $userdata['user_id'] ||  $data['post_edituser'] == $userdata['user_id'])) {
			// own posts are never marked read if this is set in the user profile
			$data['unread'] = false;
		} else {
			// check if the post timestamp within the set unread threshold
			if ($data['post_datestamp'] > $settings['unread_threshold'] || $data['post_edittime'] > $settings['unread_threshold']) {
				// check if it is newer that the first and last marker in the threads_read record
				if ($data['post_datestamp'] > $thread_last_read || $data['post_edittime'] > $thread_last_read || $data['post_datestamp'] < $thread_first_read || ($data['post_edittime'] != 0 && $data['post_edittime'] < $thread_first_read))  {
					$data['unread'] = true;
				} else {
					$data['unread'] = false;
				}
			} else {
				$data['unread'] = false;
			}
		}

		// update first_post_datestamp
		if ($data['post_edittime'] == 0) {
			$first_post_datestamp = min($data['post_datestamp'], $first_post_datestamp);
		} else {
			$first_post_datestamp = min($data['post_datestamp'], $data['post_edittime'], $first_post_datestamp);
		}

		// update last_post_datestamp
		$last_post_datestamp = max($data['post_datestamp'], $data['post_edittime'], $last_post_datestamp);

		// check if the user can edit this post. Assume the user can't
		$data['user_can_edit'] = false;
		if (iMEMBER) {
			// webmasters and forum moderators may always edit
			if (iSUPERADMIN || iMOD) {
				$data['user_can_edit'] = true;
			} else {
				// check if this is not a system post
				if ($data['post_author'] != 0) {
					// check if the thread is not locked
					if (!$tdata['thread_locked']) {
						// check if this is the users own post
						if ($userdata['user_id'] == $data['post_author']) {
							// check if the edit time is not expired
							if ($settings['forum_edit_timeout'] == 0 || ($data['post_datestamp'] + $settings['forum_edit_timeout'] * 3600) > time()) {
								$data['user_can_edit'] = true;
							}
						}
					}
				}
			}
		}

		// check if we can show the poster's IP address
		$data['show_ip'] = (iMOD || iSUPERADMIN && ($data['post_ip'] != "0.0.0.0" && file_exists(PATH_THEME."images/ip.gif")));

		// country flag
		if ($settings['forum_flags']) {
			// swap flags if we're hiding the webmasters true country of origin
			if ($settings['hide_webmaster'] && $data['user_level'] == 103) {
				$data['cc_flag'] = GeoIP_Code2Flag($settings['country']);
			} else {
				$data['cc_flag'] = !empty($data['post_cc']) ? GeoIP_Code2Flag($data['post_cc']) : GeoIP_IP2Flag($data['post_ip']);
			}
		} else {
			$data['cc_flag'] = "";
		}

		// correct the users website link if needed
		if ($data['user_web'] != "" && !strstr($data['user_web'], "http://")) {
			$data['user_web'] = "http://".$data['user_web'];
		}

		// user & group memberships
		$data['group_names'] = array();
		// if a ranking is present, and a rank title is defined, display that first
		if (isset($data['ranking']) && !empty($data['ranking']['rank_title']) && !$data['ranking']['rank_tooltip']) {
			$data['group_names'][] = array('type' => 'R', 'color' => $data['ranking']['rank_color'], 'name' => $data['ranking']['rank_title']);
		}
		// display the standard user level next
		$data['group_names'][] = array('type' => 'U', 'level' => $data['user_level'], 'name' => getuserlevel($data['user_level']));

		if ($data['user_groups']) {
			$groups = (strpos($data['user_groups'], ".") == 0 ? explode(".", substr($data['user_groups'], 1)) : explode(".", $data['user_groups']));
			foreach ($groups as $group) {
				// check if this groups has subgroups. If so, add them to the array
				getsubgroups($group);
			}
			for ($i = 0;$i < count($groups);$i++) {
				$gresult = dbquery("SELECT group_name, group_forumname, group_color FROM ".$db_prefix."user_groups WHERE group_id='".$groups[$i]."' AND group_visible & 2");
				if(dbrows($gresult)) {
					$gdata = dbarray($gresult);
					$data['group_names'][] = array('type' => 'G', 'color' => $gdata['group_color'], 'name' => $gdata['group_forumname']==""?$gdata['group_name']:$gdata['group_forumname']);
				}
			}
		}

		// check if the users avatar exists
		if (!file_exists(PATH_IMAGES."avatars/".$data['user_avatar'])) $data['user_avatar'] = "imagenotfound.jpg";

		// prepare the message text for display
		$data['post_message'] = parsemessage($data, "", $data['post_smileys'], false);

		// prepare the users signature (allow smiley's, limit parsing)
		$data['user_sig'] = parsemessage(array(), $data['user_sig'], true, true);

		// check for attachments (if enabled globally and for this forum)
		$data['attachments'] = array();
		if ($settings['attachments'] && $fdata['forum_attach']) {
			// get attachment information for this post
			$aresult = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id = '".$data['post_id']."' ORDER BY attach_id");
			$attaches = array();
			while ($adata = dbarray($aresult)) {
				$attaches[] = $adata;
			}
			// pre-process any attachment found
			if (count($attaches) > 0) {
				// sort the attachments according to type, images first
				$attach = array();
				foreach ($attaches as $key => $adata) {
					if (in_array($adata['attach_ext'], $imagetypes)) {
						$attach[] = $adata;
					}
				}
				// ... then the other attachments
				foreach ($attaches as $key => $adata) {
					if (!in_array($adata['attach_ext'], $imagetypes)) {
						$attach[] = $adata;
					}
				}
				unset ($attaches);
				foreach ($attach as $key => $adata) {
					// if we don't have a realname, use the filename
					if ($adata['attach_realname'] == "") {
						$adata['attach_realname'] = $adata['attach_name'];
					}
					// store the real path and the web path
					$adata['link'] = ATTACHMENTS.$adata['attach_name'];
					$file = PATH_ATTACHMENTS.$adata['attach_name'];
					$adata['file'] = $file;
					// get the size of the attachment
					if (file_exists($file)) {
						$adata['is_found'] = true;
						$adata['size'] = parsebytesize(filesize($file),0);
						if (in_array($adata['attach_ext'], $imagetypes)) {
							// check if it really is an image\
							$imageinfo = @getimagesize($file);
							if (is_array($imageinfo)) {
								$adata['is_image'] = true;
								$adata['imagesize'] = array('x' => $imageinfo[0], 'y' => $imageinfo[1]);
								// check if this image has a thumbnail
								if (file_exists($file.".thumb")) {
									$adata['thumbnail'] = ATTACHMENTS.$adata['attach_name'].".thumb";
									$adata['has_thumbnail'] = true;
								} else {
									$adata['has_thumbnail'] = false;
								}
							} else {
								$adata['is_image'] = false;
							}
						} else {
							$adata['is_image'] = false;
						}
					} else {
						$adata['is_found'] = false;
					}
					$data['attachments'][] = $adata;
				}
			}
		}
		// store this record for use in the template
		$variables['posts'][] = $data;
	}
}

if (iMEMBER) {
	// update the threads_read record for this user and thread when the first_post_datestamp is older
	if ($first_post_datestamp) {
		$result = dbquery("UPDATE ".$db_prefix."threads_read SET thread_first_read = '".$first_post_datestamp."' WHERE user_id = '".$userdata['user_id']."' AND thread_id = '".$thread_id."' AND thread_first_read > '".$first_post_datestamp."'");
	}
	// update the threads_read record for this user and thread when the last_post_datestamp is newer
	if ($last_post_datestamp) {
		$result = dbquery("UPDATE ".$db_prefix."threads_read SET thread_last_read = '".$last_post_datestamp."' WHERE user_id = '".$userdata['user_id']."' AND thread_id = '".$thread_id."' AND thread_last_read < '".$last_post_datestamp."'");
	}
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
	if ($data2['forum_id'] == $fdata['forum_id']) {
		$data2['selected'] = true;
	} else {
		$data2['selected'] = false;
	}
	$variables['forums'][] = $data2;
}

// quick-reply random post id
if (isset($_POST['random_id'])) {
	$random_id = $_POST['random_id'];
} else {
	$random_id = md5(microtime());
}
$variables['random_id'] = $random_id;

// pagenav url
$variables['pagenav_url'] = FUSION_SELF."?forum_id=$forum_id&amp;thread_id=$thread_id&amp;";

// load the hoteditor if needed
if ($settings['hoteditor_enabled'] && (!iMEMBER || $userdata['user_hoteditor'])) {
	define('LOAD_HOTEDITOR', true);
}

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'forum.viewthread', 'template' => 'forum.viewthread.tpl', 'locale' => array("forum.main","admin.forum_polls"));
$template_variables['forum.viewthread'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
