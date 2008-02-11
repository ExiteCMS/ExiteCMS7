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
	LEFT JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
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

// get information about the current thread
$result = dbquery(
	"SELECT * FROM ".$db_prefix."threads 
	WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'"
);
// bail out if the requested forum does not exist
if (!dbrows($result)) {
	fallback("index.php");
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
		$result = dbquery("SELECT MAX(post_datestamp) AS last_post FROM ".$db_prefix."posts WHERE post_author='".$userdata['user_id']."'");
		if (!iSUPERADMIN || dbrows($result) > 0) {
			$data = dbarray($result);
			if ((time() - $data['last_post']) < $settings['flood_interval']) {
				$flood = true;
				$result = dbquery("INSERT INTO ".$db_prefix."flood_control (flood_ip, flood_timestamp) VALUES ('".USER_IP."', '".time()."')");
				if (dbcount("(flood_ip)", "flood_control", "flood_ip='".USER_IP."'") > 4) {
					$result = dbquery("UPDATE ".$db_prefix."users SET user_status='1', user_ban_reason='".$locale['530']."' WHERE user_id='".$userdata['user_id']."'");
					redirect("post.php?action=quickreply&forum_id=$forum_id&thread_id=$thread_id&post_id=0&errorcode=2");
				} else {
					redirect("post.php?action=quickreply&forum_id=$forum_id&thread_id=$thread_id&post_id=0&errorcode=1");
				}
			}
		}
		// check if this isn't a reload, back-post, or double submit
		if (isset($_COOKIE['post_'.$random_id])) {
			redirect("post.php?action=quickreply&forum_id=$forum_id&thread_id=$thread_id&post_id=0&errorcode=3");
		} else {
			if (!$flood) {
				setcookie("post_".$random_id, "posted", time() + 60*60, "/", "", "0");
				$sig = ($userdata['user_sig'] ? '1' :'0');
				$smileys = isset($_POST['disable_smileys']) ? "0" : "1";
				$subject = "RE: ".stripinput(censorwords($tdata['thread_subject']));
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
				$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."', thread_lastuser='".$userdata['user_id']."' WHERE thread_id='$thread_id'");
				$result = dbquery("INSERT INTO ".$db_prefix."posts (forum_id, thread_id, post_subject, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('$forum_id', '$thread_id', '$subject', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".time()."', '".USER_IP."', '0', '0')");
				$newpost_id = mysql_insert_id();
				$result = dbquery("UPDATE ".$db_prefix."users SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
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
	$result = dbquery("
		SELECT count(*) as unread, tr.thread_last_read, tr.thread_page
			FROM ".$db_prefix."posts p
			LEFT JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id
			WHERE tr.user_id = '".$userdata['user_id']."' 
				AND tr.thread_id = '".$thread_id."' 
				AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].") 
				AND (p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
			GROUP BY tr.thread_id
		");
	if (dbrows($result)) {
		$data = dbarray($result);
		$variables['unread_posts'] = $data['unread'];
		$thread_last_read = $data['thread_last_read'];
		$thread_page = $data['thread_page'];
	} else {
		$variables['unread_posts'] = 0;
		$thread_last_read = time();
		$thread_page = 0;
	}
} else {
	$variables['unread_posts'] = 0;
	$thread_last_read = time();
	$thread_page = 0;
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

// last_post_datestamp, needed to update threads_read later
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
		}

		// if there's a reply id, get the user_name
		if ($data['post_reply_id'] != 0) {
			$reply_to = dbarray(dbquery("SELECT user_name FROM ".$db_prefix."users u, ".$db_prefix."posts p WHERE p.post_id = '".$data['post_reply_id']."' AND p.post_author = u.user_id"));
			if (is_array($reply_to)) 
				$data['post_reply_username'] = $reply_to['user_name'];
			else
				$data['post_reply_username'] = "";
		}
		// check if this post is read or unread
		$data['unread'] = $data['post_datestamp'] > $thread_last_read || $data['post_edittime'] > $thread_last_read;

		// update last_post_datestamp
		$last_post_datestamp = max($data['post_datestamp'], $data['post_edittime'], $last_post_datestamp);

		// check what options to show for this post
		$data['user_can_edit'] = iMEMBER && $data['post_author'] != 0 && (iMOD || iSUPERADMIN || (!$tdata['thread_locked'] && $userdata['user_id'] == $data['post_author']));
		$data['show_ip'] = (iMOD || iSUPERADMIN && ($data['post_ip'] != "0.0.0.0" && file_exists(PATH_THEME."images/ip.gif")));
	
		// country flag
		if ($settings['forum_flags']) {
			if ($settings['hide_webmaster'] && iSUPERADMIN) {
				$data['cc_flag'] = GeoIP_Code2Flag($settings['country']);
			} else {
				$data['cc_flag'] = GeoIP_IP2Flag($data['post_ip']);
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
		$data['group_names'][] = array('type' => 'U', 'level' => $data['user_level'], 'name' => getuserlevel($data['user_level']));
		if ($data['user_groups'] != "") {
			$gresult = dbquery("SELECT group_name, group_forumname, group_color FROM ".$db_prefix."user_groups WHERE group_id IN (".str_replace('.', ',', substr($data['user_groups'],1)).") AND group_visible & 2");
			$grecs = dbrows($gresult);
			while ($gdata = dbarray($gresult)) {
				$data['group_names'][] = array('type' => 'G', 'color' => $gdata['group_color'], 'name' => $gdata['group_forumname']==""?$gdata['group_name']:$gdata['group_forumname']);
			}
		}

		// check if the users avatar exists
		if (!file_exists(PATH_IMAGES."avatars/".$data['user_avatar'])) $data['user_avatar'] = "imagenotfound.jpg";
		// prepare the message text for display
		$data['post_message'] = parsemessage($data['post_message'], $data['post_smileys']);

		// prepare the users signature
		$data['user_sig'] = nl2br(parseubb(parsesmileys($data['user_sig'])));

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

// update the threads_read record for this user and thread when the last_post_datestamp is newer
if (iMEMBER && $last_post_datestamp) {
	$result = dbquery("UPDATE ".$db_prefix."threads_read SET thread_last_read = '".$last_post_datestamp."', thread_page = '".min($rowstart, $thread_page)."' WHERE user_id = '".$userdata['user_id']."' AND thread_id = '".$thread_id."' AND thread_last_read < '".$last_post_datestamp."'");
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

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'forum.viewthread', 'template' => 'forum.viewthread.tpl', 'locale' => array("forum.main","admin.forum_polls"));
$template_variables['forum.viewthread'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>