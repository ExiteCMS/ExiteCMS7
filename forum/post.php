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

// parameter validation
if (!isset($action)) fallback("index.php");

switch ($action) {
	case "postreply":
	case "quote":
		if (!isset($reply_id) || !isNum($reply_id)) fallback("index.php");
		$post_id = 0;

	case "edit":
	case "quickreply":
		if (!isset($post_id) || !isNum($post_id)) fallback("index.php");

	case "reply":
	case "track_on":
	case "track_off":
		if (!isset($thread_id) || !isNum($thread_id)) fallback("index.php");

	case "newthread":
		if (!FUSION_QUERY || !isset($forum_id) || !isNum($forum_id)) fallback("index.php");
}

// indicate whether or not the user wants to see this module without side panels
define('FULL_SCREEN', (iMEMBER && $userdata['user_forum_fullscreen']));

// temp storage for template variables
$variables = array();

// load the locales for this forum module
locale_load("forum.main");
locale_load("forum.post");

// shared forum functions includes
require_once PATH_INCLUDES."forum_functions_include.php";
require_once PATH_INCLUDES."photo_functions_include.php";

/*---------------------------------------------------+
| local functions                                    |
+----------------------------------------------------*/

// function to validate the contents of the $_POST array
function validatepost() {
	global $locale;

	// we need both a subject and a message body
	if ($_POST['subject'] == "" || $_POST['message'] == "")
		return $locale['441'];

	// make sure we have a valid prefix
	if ($_POST['new_prefix'] == '[?]') $_POST['new_prefix'] = '';

	// post validated, add the selected prefix to the post subject
	if ( !empty($_POST['new_prefix']) )
	{
		$_POST['new_prefix'] = rtrim(ltrim(trim(stripinput($_POST['new_prefix'])),'['),']');
		$_POST['subject'] = '[' . $_POST['new_prefix'] . '] ' . $_POST['subject'];
	}

	return "";
}

// function to check and store the attachment upload in a temp location
function storeupload() {

	global $forum_id, $db_prefix, $settings, $locale, $imagetypes, $attachments;

	// process new uploads
	if (isset($_FILES['attach'])) {
		// check the error code
		switch($_FILES['attach']['error']) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				return "";
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return $locale['440b'];
			default:
				return sprintf($locale['440'], $_FILES['attach']['error'], $_FILES['attach']['name']);
		}
		// check against our max. upload filesize
		if ($_FILES['attach']['size'] > $settings['attachmax']) {
			return $locale['440b'];
		}
		// verify the uploaded file
		$attach = array(
			'attach_name' => $_FILES['attach']['name'],
			'type' => $_FILES['attach']['type'],
			'attach_size' => $_FILES['attach']['size'],
			'attach_count' => 0,
			'attach_tmp' => $_FILES['attach']['tmp_name']
			);
		if ($attach['attach_tmp'] != "" && !empty($attach['attach_tmp']) && is_uploaded_file($attach['attach_tmp'])) {
			// check for illegal file types
			$attachext = strtolower(strrchr($attach['attach_name'],"."));
			$fattachtypes = dbarray(dbquery("SELECT forum_attachtypes FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
			if (isset($fattachtypes['forum_attachtypes']))
				$attachtypes = explode(",", $fattachtypes['forum_attachtypes']);
			else
				$attachtypes = explode(",", $settings['attachtypes']);
			if (in_array($attachext, $attachtypes)) {
				@unlink($attach['attach_tmp']);
	            return $locale['440a'];
			}
		} else {
			unlink($attach['attach_tmp']);
	        return $locale['440d'];
		}
		// file is ok. Move it to a safe temp location
		$tmp_name = tempnam(PATH_ATTACHMENTS, '$$$_');
		if (!move_uploaded_file($attach['attach_tmp'], $tmp_name)) {
			return $locale['440d'];
		}
		// if it's an image, see if we need to make a thumbnail
		if (in_array($attachext, $imagetypes)) {
			if (@verify_image($tmp_name)) {
				// it's a valid image. See if we need to generate a thumbnail
				$imagefile = @getimagesize($tmp_name);
				if ($imagefile[0] > $settings['forum_max_w'] || $imagefile[1] > $settings['forum_max_h']) {
					// image is bigger than the defined maximum image size. Generate a thumb image
					createthumbnail($imagefile[2], $tmp_name, $tmp_name.".thumb", $settings['thumb_w'], $settings['thumb_h']);
				}
			} else {
				// not a valid image
				unlink($tmp_name);
	            return $locale['440c'];
			}
		}
		$attach['attach_tmp'] = basename($tmp_name);
		$attach['attach_ext'] = strtolower(strrchr($attach['attach_name'],"."));
		$attach['attach_comment'] = trim(stripinput(censorwords($_POST['attach_comment'])));;
		$_POST['attach_comment'] = "";
		// add the upload to the array of already uploaded files
		$attachments[] = $attach;
	} else {
		return "";
	}
}

/*---------------------------------------------------+
| main                                               |
+----------------------------------------------------*/

// make sure these hold a valid value. 0 here means 'undefined'
if (!isset($error)) $error = 0;
if (!isset($errorcode)) $errorcode = 0;
if (!isset($forum_id)) $forum_id = 0;
if (!isset($thread_id)) $thread_id = 0;
if (!isset($post_id)) $post_id = 0;
if (!isset($reply_id)) $reply_id = 0;

// process attachments
$attachments = array();

// get the previous uploaded attachment info from the $_POST array
if (isset($_POST['attach'])) {
	foreach($_POST['attach'] as $attachment) {
		$attachments[] = $attachment;
	}
}

// get the newly uploaded attachment
$error = storeupload();
if ($error != "") {
	$template_panels[] = array('type' => 'body', 'name' => 'message_panel', 'title' => $locale['414'], 'template' => '_message_table_panel.tpl');
	$variables['message'] = $error;
	$variables['bold'] = true;
	$template_variables['message_panel'] = $variables;
	// reset the variables array
	$variables = array();
}

// flip the sticky bit on this post
if (isset($_POST["sticky_on"])) {
	$result = dbquery("UPDATE ".$db_prefix."posts SET post_sticky='1' WHERE forum_id='$forum_id' AND thread_id='$thread_id' AND post_id='$post_id'");
	$template_panels[] = array('type' => 'body', 'name' => 'message_panel', 'title' => $locale['408'], 'template' => '_message_table_panel.tpl');
	$variables['message'] = $locale['454'];
	$variables['bold'] = true;
	$template_variables['message_panel'] = $variables;
	// reset the variables array
	$variables = array();
}
if (isset($_POST["sticky_off"])) {
	$result = dbquery("UPDATE ".$db_prefix."posts SET post_sticky='0' WHERE forum_id='$forum_id' AND thread_id='$thread_id' AND post_id='$post_id'");
	$template_panels[] = array('type' => 'body', 'name' => 'message_panel', 'title' => $locale['408'], 'template' => '_message_table_panel.tpl');
	$variables['message'] = $locale['455'];
	$variables['bold'] = true;
	$template_variables['message_panel'] = $variables;
	// reset the variables array
	$variables = array();
}

// get the forum, thread and post record
switch ($action) {
	case "quote":
	case "postreply":
	case "edit":
	case "quickreply":
		if ($errorcode == 0) {
			$result = dbquery("SELECT p.*, u.user_name FROM ".$db_prefix."posts p LEFT JOIN ".$db_prefix."users u ON p.post_author = u.user_id WHERE p.post_id='".($post_id != 0 ? $post_id : $reply_id)."' AND p.thread_id='".$thread_id."' AND p.forum_id='".$forum_id."'");
			if (dbrows($result)) { $pdata = dbarray($result); } else { fallback("index.php"); }
			if ($pdata['post_author']) {
				$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$pdata['post_author']."'");
				if (dbrows($result)) { $puser = dbarray($result); } else { fallback("index.php"); }
				$variables['orgauthor'] = $reply_id > 0 ? $puser['user_name'] : "";
			} else {
				// automatic post. "fake" a user
				$puser = array();
			}
		}

	case "reply":

	case "track_on":
	case "track_off":
		$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$forum_id."'");
		if (dbrows($result)) { $tdata = dbarray($result); } else { fallback("index.php"); }

	case "newthread":
		if (!isset($variables['orgauthor'])) $variables['orgauthor'] = "";
		$result = dbquery("SELECT f.*, f2.forum_name AS forum_cat_name FROM ".$db_prefix."forums f LEFT JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id WHERE f.forum_id='".$forum_id."'");
		if (dbrows($result)) {
			$fdata = dbarray($result);
			if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) fallback("index.php");
		} else {
			fallback("index.php");
		}
		$variables['forum'] = $fdata;
		if (!checkgroup($fdata['forum_posting'])) fallback("index.php");
		$forum_mods = explode(".", $fdata['forum_moderators']);
		if (iMEMBER && (($fdata['forum_modgroup'] && checkgroup($fdata['forum_modgroup'])) || in_array($userdata['user_id'], $forum_mods))) { define("iMOD", true); } else { define("iMOD", false); }
}

// check if the user can edit this post.
if ($action == "edit") {
	// Assume the user can't
	$user_can_edit = false;
	if (iMEMBER) {
		// webmasters and forum moderators may always edit
		if (iSUPERADMIN || iMOD) {
			$user_can_edit = true;
		} elseif ($action == "newthread") {
			$user_can_edit = true;
		} else {
			// check if this is not a system post
			if ($pdata['post_author'] != 0) {
				// check if the thread is not locked
				if (!$tdata['thread_locked']) {
					// check if this is the users own post
					if ($userdata['user_id'] == $pdata['post_author']) {
						if ($pdata['post_sticky'] == 1) {
							// no edit timeout for sticky posts
							$user_can_edit = true;
						} elseif ($settings['forum_edit_timeout'] == 0) {
							// no edit timeout specified? User can edit the post
							$user_can_edit = true;
						} elseif ($settings['forum_edit_timeout_on_post'] == 0 && (max($pdata['post_datestamp'], $pdata['post_edittime']) + $settings['forum_edit_timeout'] * 60) > time()) {
							// timeout is within the last edit date (or post date)
							$user_can_edit = true;
						} elseif ($settings['forum_edit_timeout_on_post'] == 1 && ($pdata['post_datestamp'] + $settings['forum_edit_timeout'] * 60) > time()) {
							// timeout is within the post date
							$user_can_edit = true;
						}
					}
				}
			}
		}
	}
}

// forum poll initialisation
$fpm = array();
$fpm_data = array();

$fpm_result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id='$forum_id'");
if (dbrows($fpm_result) != 0) {
	$fpm_settings = dbarray($fpm_result); $fpm_settings['forum_exists'] = true;
} else {
	$fpm_settings = dbarray(dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id='0'"));
}
if ($action == "edit") {
	$fpm_result = dbquery("SELECT * FROM ".$db_prefix."forum_polls WHERE thread_id='$thread_id'");
	if (dbrows($fpm_result) != 0) {
		$fpm_data = dbarray($fpm_result);
		$fpm['type'] = $fpm_data['poll_type'];
		$fpm['poll_id'] = $fpm_data['poll_id'];
		$total_votes = dbcount("(poll_id)", "forum_poll_votes", "poll_id='".$fpm_data['poll_id']."'");
	} else {
		$total_votes = 0;
	}
	$fpm['reset_start'] = isset($_POST['fpm']['reset_start']) ? " checked" : "";
	$fpm['reset_votes'] = isset($_POST['fpm']['reset_votes']) ? " checked" : "";
	if (!defined('FPM_ACCESS') && (iMOD || iSUPERADMIN)) {
		define("FPM_ACCESS", true);
	} elseif (!defined('FPM_ACCESS') && $total_votes != 0) {
		define("FPM_ACCESS", false);
	}
}
if (!defined('FPM_ACCESS') && $fpm_settings['enable_polls'] == 1 && $fpm_settings['create_permissions'] != "") {
	$temp_array = explode(".", $fpm_settings['create_permissions']);
	for($i = 0; $i < count($temp_array); $i ++) {
		if (isNum($temp_array[$i])) {
			if ($userdata['user_id'] == $temp_array[$i]) {
				define("FPM_ACCESS", true);
				break;
			}
		} else {
			if (checkgroup(substr($temp_array[$i], 1))) {
				define("FPM_ACCESS", true);
				break;
			}
		}
	}
}

// make sure this is set
if (!defined('FPM_ACCESS')) {
	define("FPM_ACCESS", false);
}

if (FPM_ACCESS) {
	if ($action == "reply") {
		$fpm['exists'] = dbcount("(poll_id)", "forum_polls", "thread_id='$thread_id'");
	} else {
		$fpm['exists'] = 0;
	}
	$fpm['type'] = isset($_POST['fpm']['type']) ? $_POST['fpm']['type'] : (isset($fpm['type']) ? $fpm['type'] : 0);
	$fpm['user_name'] = isset($fpm_data['user_name']) ? $fpm_data['user_name'] : $userdata['user_name'];
	$fpm['votes'] = isset($fpm_data['poll_id']) ? dbcount("(poll_id)", "forum_poll_votes", "poll_id='".$fpm_data['poll_id']."'") : 0;
	$fpm['use_subject'] = isset($_POST['fpm']['use_subject']) ? 1 : 0;
	if ($fpm['use_subject'] == 1 && $subject != $locale['420']) {
		if ($subject == "" && $action == "reply") {
			$fpm_result = dbarray(dbquery("SELECT thread_subject FROM ".$db_prefix."threads WHERE thread_id='$thread_id'"));
			$fpm['question'] = $fpm_result['thread_subject'];
		} else { $fpm['question'] = trim(stripinput(censorwords($subject))); }
	} elseif (isset($_POST['fpm']['question'])) {
		$fpm['question'] = trim(stripinput(censorwords($_POST['fpm']['question'])));
	} else {
		$fpm['question'] = isset($fpm_data['poll_question']) ? $fpm_data['poll_question'] : "";
	}
	$fpm['blank_options'] = 0;
	$fpm['add_options'] = isset($_POST['fpm']['add_options']) ? 1 : 0;
	if (isset($_POST['fpm']['option_show']) && isNum($_POST['fpm']['option_show'])) {
		$fpm['option_show'] = $_POST['fpm']['option_show'];
	} else {
		$fpm['option_show'] = $fpm_settings['option_show'];
	}
	if (isset($_POST['fpm']['add_options'])) {
		if (($fpm['option_show'] + $fpm_settings['option_increment']) <= $fpm_settings['option_max']) {
			$fpm['option_show'] = $fpm['option_show'] + $fpm_settings['option_increment'];
		} else {
			$fpm['option_show'] = $fpm_settings['option_max'];
		}
		$variables['is_sticky'] = isset($_POST['sticky']);
		$variables['is_sig_shown'] = isset($_POST['show_sig']);
		$variables['del_check'] = isset($_POST['delete']);
		$variables['del_attach_check'] = isset($_POST['delete_attach']);
		$variables['is_smiley_disabled'] = isset($_POST['disable_smileys']);
		$variables['subject'] = trim(stripinput(censorwords($_POST['subject'])));;
		$variables['message'] = trim(stripmessageinput(censorwords($_POST['message'])));
	}
	if (isset($_POST['fpm'])) {
		for($i = 1; $i <= $fpm['option_show']; $i ++) {
			$fpm['option'][$i] = isset($_POST['fpm']['option'][$i]) ? trim(stripinput(censorwords($_POST['fpm']['option'][$i]))) : "";
			if ($fpm['option'][$i] == "") {
				$fpm['blank_options'] ++;
			}
		}
	} elseif (isset($fpm_data['poll_id'])) {
		$fpm_result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_options WHERE poll_id='".$fpm_data['poll_id']."' ORDER BY option_order");
		$i = 1;
		while($fpm_options = dbarray($fpm_result)) {
			$fpm['option'][$i] = $fpm_options['option_text']; $i ++;
		}
		if ($i <= $fpm['option_show']) {
			while ($i <= $fpm['option_show']) { $fpm['option'][$i] = ""; $i ++; }
		} else {
			$fpm['option_show'] = $i - 1;
		}
	} else {
		for($i = 1; $i <= $fpm['option_show']; $i ++) {
			$fpm['option'][$i] = ""; $fpm['blank_options'] ++;
		}
	}
	if (isset($_POST['fpm']['duration']) && isNum($_POST['fpm']['duration'])) {
		if ($_POST['fpm']['duration'] == 0 && $fpm_settings['duration_max'] == 0) {
			$fpm['duration'] = 0;
		} elseif (($_POST['fpm']['duration'] * 86400) > $fpm_settings['duration_max'] && $fpm_settings['duration_max'] != 0) {
			$fpm['duration'] = floor($fpm_settings['duration_max'] / 86400);
		} elseif (($_POST['fpm']['duration'] * 86400) > $fpm_settings['duration_max'] && $fpm_settings['duration_max'] == 0) {
			$fpm['duration'] = $_POST['fpm']['duration'];
		} elseif (($_POST['fpm']['duration'] * 86400) < $fpm_settings['duration_min']) {
			$fpm['duration'] = floor($fpm_settings['duration_min'] / 86400);
		} else {
			$fpm['duration'] = 0;
		}
	} elseif (isset($fpm_data['poll_end'])) {
		$fpm['duration'] = floor(($fpm_data['poll_end'] - $fpm_data['poll_start']) / 86400);
	} else {
		$fpm['duration'] = 0;
	}
	if ($fpm['duration'] < 0) {
		$fpm['duration'] = 0;
	}
	if (isset($fpm_data['poll_start']) && $fpm['reset_start'] == "") {
		$fpm['start'] = $fpm_data['poll_start'];
	}
	else {
		$fpm['start'] = time();
	}
	$fpm['end'] = $fpm['duration'] != 0 ? ($fpm['start'] + ($fpm['duration'] * 86400)) : 0;
	$fpm_data = array();
}

// preview the new post
if (isset($_POST['preview'])) {
	// process the action parameter
	switch ($action) {
		case "newthread":
			if (!isset($title)) $title = $locale['400'];

		case "postreply":
			if (!isset($title)) $title = $locale['402'];

		case "reply":
			if (!isset($title)) $title = $locale['402'];

		case "edit":
			if (!isset($title)) $title = $locale['405'];

		case "quote":
			if (!isset($title)) $title = $locale['402'];

			$variables['preview_title'] = $title;
			// create a posts record to show the preview
			$variables['posts'] = array();
			$preview = array();
			$preview['post_datestamp'] = time();
			$preview['post_id'] = $post_id;
			$preview['thread_id'] = $thread_id;
			$preview['forum_id'] = $forum_id;
			$preview['post_sticky'] = isset($_POST['sticky']) ? "1" : "0";
			$preview['post_smileys'] = isset($_POST['disable_smileys']) ? false : true;
			$preview['post_showsig'] = isset($_POST['show_sig']) ? "1" : "0";
			$preview['post_subject'] = trim(stripinput(censorwords($_POST['subject'])));;
			if ($preview['post_subject'] == "" && isset($tdata) && is_array($tdata)) {
				$preview['post_subject'] = "Re: ".$tdata['thread_subject'];
			}
			$preview['post_message'] = trim(stripmessageinput(censorwords($_POST['message'])));
			if ($preview['post_message'] == "") {
				$preview['post_message'] = $locale['421'];
			}
			$preview['post_message'] = parsemessage($preview);
			$preview['post_reply_id'] = $reply_id;
			if ($reply_id != 0) {
				$preview['post_reply_username'] = $variables['orgauthor'];
			}
			if ($settings['forum_flags']) {
				if ($userdata['user_id'] == 1) {
					$preview['cc_flag'] = GeoIP_Code2Flag($settings['country']);
				} else {
					$preview['cc_flag'] = !empty($userdata['user_cc_code']) ? GeoIP_Code2Flag($userdata['user_cc_code']) : GeoIP_IP2Flag($userdata['user_ip']);
				}
			} else {
				$preview['cc_flag'] = "";
			}
			$preview['post_author'] = $userdata['user_id'];
			$preview['unread'] = true;
			$preview['post_edittime'] = 0;
			$preview['post_edituser'] = 0;
			$preview['post_ip'] = $userdata['user_ip'];
			$preview['post_cc'] = $userdata['user_cc_code'];
			$preview['group_names'] = array();
			$preview['group_names'][] = array('type' => 'U', 'level' => $userdata['user_level'], 'name' => getuserlevel($userdata['user_level']));
			if (!empty($userdata['user_groups'])) {
				$gresult = dbquery("SELECT group_name, group_forumname, group_color FROM ".$db_prefix."user_groups WHERE group_id IN (".str_replace('.', ',', substr($userdata['user_groups'],1)).") AND group_visible & 2");
				$grecs = dbrows($gresult);
				while ($gdata = dbarray($gresult)) {
					$preview['group_names'][] = array('type' => 'G', 'color' => $gdata['group_color'], 'name' => $gdata['group_forumname']==""?$gdata['group_name']:$gdata['group_forumname']);
				}
			}
			$preview['attachments'] = array();
			// add information about the current user
			$preview = array_merge($preview, $userdata);
			// save the parsed message
			$_x = $preview['post_message'];
			// store the signature in the message to be parsed
			$preview['post_message'] = $preview['user_sig'];
			$preview['user_sig'] = parsemessage($preview);
			// restore the user message
			$preview['post_message'] = $_x;
			// process attachments
			if ($settings['attachments'] == "1" && $fdata['forum_attach'] == "1") {
				$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id' ORDER BY attach_id");
				$rows = dbrows($result);
				$preview['attachment_count'] = $rows + count($attachments);
				$preview['attachments'] = array();
				$next = 0;
				while ($adata = dbarray($result)) {
					$next++;
					$adata['delete_checked'] = isset($_POST['delattach'][$next]);
					$adata['new'] = false;
					$adata['index'] = $next;
					$file = PATH_ATTACHMENTS.$adata['attach_name'];
					if (file_exists($file)) {
						$adata['is_found'] = true;
						$adata['size'] = parsebytesize(filesize($file),0);
						if (in_array($adata['attach_ext'], $imagetypes)) {
							// check if it really is an image
							$imageinfo = @getimagesize($attachrealfile);
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
					$preview['attachments'][] = $adata;
				}
				foreach($attachments as $key => $attachment) {
					$next++;
					$adata = array();
					$adata['new'] = true;
					$adata['is_found'] = true;
					$adata['is_image'] = false;
					$adata['size'] = parsebytesize($attachment['attach_size'],0);
					$adata['delete_checked'] = isset($_POST['delattach'][$next]);
					$adata['key'] = $key;
					$adata['attach_tmp'] = $attachment['attach_tmp'];
					$adata['type'] = $attachment['type'];
					$adata['attach_id'] = 0;
					$adata['attach_size'] = $attachment['attach_size'];
					$adata['attach_count'] = $attachment['attach_count'];
					$adata['attach_comment'] = $attachment['attach_comment'];
					$adata['attach_name'] = $attachment['attach_name'];
					$adata['attach_realname'] = $attachment['attach_name'];
					$adata['attach_ext'] = $attachment['attach_ext'];
					$preview['attachments'][] = $adata;
				}
			}
			// and store it for use in the template
			$variables['posts'][] = $preview;
			// check if there's a poll defined
			$variables['poll_preview'] = fpm_preview();
//			 ($fpm['question'] != "" && $fpm['blank_options'] < ($fpm['option_show'] - 1));
//			$variables['poll'] = $fpm;
			// enable the preview section
			$variables['show_preview'] = true;
			$variables['user_can_post'] = false;
			break;
	}
	if (isset($title)) unset($title);
}

// bail out if an edit is requested, but no edit rights have been detected
if ($action == "edit" && !$user_can_edit) {
	resultdialog($locale['408'], $locale['502']);
} elseif (isset($_POST["cancel"])) {
	// post cancelled?
	resultdialog($locale['418'], $locale['439']);
} elseif (isset($_POST['save'])) {
	// save the changes
	// process the action parameter
	switch ($action) {
		case "newthread":
			if (!isset($title)) $title = $locale['401'];
			if (!isset($msg)) $msg = $locale['442'];

		case "postreply":
			if (!isset($title)) $title = $locale['404'];
			if (!isset($msg)) $msg = $locale['443'];

		case "reply":
			if (!isset($title)) $title = $locale['404'];
			if (!isset($msg)) $msg = $locale['443'];

		case "edit":
			if (!isset($title)) $title = $locale['409'];
			if (!isset($msg)) $msg = $locale['446'];

		case "quote":
			if (!isset($title)) $title = $locale['404'];
			if (!isset($msg)) $msg = $locale['443'];
			$error = validatepost();
			if ($error == "") {
				if (iMEMBER) {
					$sticky = isset($_POST['sticky']) ? "1" : "0";
					$sig = isset($_POST['show_sig']) ? "1" : "0";
					$update_notify = isset($update_notify) ? $update_notify : " ";
					$smileys = isset($_POST['disable_smileys']) ? "0" : "1";
					$subject = trim(stripinput(censorwords($_POST['subject'])));
					if ($subject == "") {
						$subject = "Re: ".$tdata['thread_subject'];
					}
					$message = trim(stripmessageinput(censorwords($_POST['message'])));
					if ($action == 'edit') {
						// update the post record
						if ($_POST['message'] == $_POST['org_message'])
							$result = dbquery("UPDATE ".$db_prefix."posts SET post_subject='$subject', post_smileys='$smileys' WHERE post_id='$post_id'");
						else {
							$result = dbquery("UPDATE ".$db_prefix."posts SET post_subject='$subject', post_message='$message', post_smileys='$smileys', post_edituser='".$userdata['user_id']."', post_edittime='".time()."' WHERE post_id='$post_id'");
						}
						$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_datestamp ASC LIMIT 1"));
						if ($data['post_id'] == $post_id) {
							$result = dbquery("UPDATE ".$db_prefix."threads SET thread_subject='$subject' WHERE thread_id='$thread_id'");
						}
						// flag the forum and the thread as updated
						$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
						$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."', thread_lastuser='".$userdata['user_id']."'".$update_notify." WHERE thread_id='$thread_id'");
						if ($settings['thread_notify'] && isset($_POST['notify_me'])) {
							$result = dbquery("INSERT INTO ".$db_prefix."thread_notify (thread_id, notify_datestamp, notify_user, notify_status) VALUES('$thread_id', '".time()."', '".$userdata['user_id']."', '1')");
						}
						fpm_save($post_id);
					} else {
						// insert a new post record
						$flood = false;
						if (!iSUPERADMIN) {
							// for non-webmasters, check for post flooding
							$result = dbquery("SELECT MAX(post_datestamp) AS last_post FROM ".$db_prefix."posts WHERE post_author='".$userdata['user_id']."'");
							if (dbrows($result) > 0) {
								$data = dbarray($result);
								if ((time() - $data['last_post']) < $settings['flood_interval']) {
									$flood = true;
									$error = $locale['431'];
									$result = dbquery("INSERT INTO ".$db_prefix."flood_control (flood_ip, flood_userid, flood_timestamp) VALUES ('".USER_IP."', '".$userdata['user_id']."', '".time()."')");
									if (dbcount("(flood_ip)", "flood_control", "flood_ip='".USER_IP."' AND flood_userid='".$userdata['user_id']."'") > 4) {
										$result = dbquery("UPDATE ".$db_prefix."users SET user_status='1', user_ban_reason='".$locale['434']."' WHERE user_id='".$userdata['user_id']."'");
										$error .= "<br />".$locale['432'];
									} else {
										$error .= "<br />".sprintf($locale['433'], $settings['flood_interval']);
									}
								}
							}
						}
						// check if this isn't a reload, back-post, or double submit
						if (isset($_SESSION['posts'][$random_id])) {
							$error = $locale['458'];
						} else {
							if (!$flood) {
								if (!isset($_SESSION['posts']) || !is_array($_SESSION['posts'])) $_SESSION['posts'] = array();
								$_SESSION['posts'][$random_id] = time()+60*60*12;
								$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
								switch ($action) {
									case 'reply':
									case 'postreply':
									case 'quote':
										$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."', thread_lastuser='".$userdata['user_id']."'".$update_notify." WHERE thread_id='$thread_id'");
										break;
									case 'newthread':
										$result = dbquery("INSERT INTO ".$db_prefix."threads (forum_id, thread_subject, thread_author, thread_views, thread_lastpost, thread_lastuser, thread_sticky, thread_locked) VALUES('$forum_id', '$subject', '".$userdata['user_id']."', '0', '".time()."', '".$userdata['user_id']."', '$sticky', '0')");
										$thread_id = mysql_insert_id();
										break;
								}
								$result = dbquery("INSERT INTO ".$db_prefix."posts (forum_id, thread_id, post_reply_id, post_subject, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_cc, post_edituser, post_edittime) VALUES ('$forum_id', '$thread_id', '$reply_id', '".mysql_real_escape_string($subject)."', '".mysql_real_escape_string($message)."', '$sig', '$smileys', '".$userdata['user_id']."', '".time()."', '".USER_IP."', '".$userdata['user_cc_code']."', '0', '0')");
								$post_id = mysql_insert_id();
								$result = dbquery("UPDATE ".$db_prefix."users SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
								if ($settings['thread_notify'] && isset($_POST['notify_me'])) {
									$result = dbquery("INSERT INTO ".$db_prefix."thread_notify (thread_id, notify_datestamp, notify_user, notify_status) VALUES('$thread_id', '".time()."', '".$userdata['user_id']."', '1')");
								}
								fpm_save($post_id);
							}
						}
					}
					// check if we need to notify people
					if ($settings['thread_notify']) {
						$result = dbquery(
							"SELECT tn.*, tr.thread_subject, tu.user_id,tu.user_name,tu.user_email,tu.user_locale FROM ".$db_prefix."thread_notify tn
							LEFT JOIN ".$db_prefix."users tu ON tn.notify_user=tu.user_id
							LEFT JOIN ".$db_prefix."threads tr ON tn.thread_id=tr.thread_id
							WHERE tn.thread_id='$thread_id'
						");
						$notify_self = false;
						if (dbrows($result)) {
							require_once PATH_INCLUDES."sendmail_include.php";
							$link = $settings['siteurl']."forum/viewthread.php?forum_id=$forum_id&thread_id=$thread_id&pid=$post_id#post_$post_id";
							while ($data = dbarray($result)) {
								if ($data['notify_user'] == $userdata['user_id']) {
									// mark that we're already tracking this thread, and don't send a message to ourselfs...
									$notify_self = true;
								}
								if ($data['notify_status']) {
									// do not send an email to the poster
									if ($data['notify_user'] != $userdata['user_id']) {
										// get the message text in the users own locale
										$message_el1 = array("{USERNAME}", "{THREAD_SUBJECT}", "{THREAD_URL}", "{SITE_NAME}", "{SITE_WEBMASTER}");
										$message_el2 = array($data['user_name'], $data['thread_subject'], $link, html_entity_decode($settings['sitename']), html_entity_decode($settings['siteusername']));
										$message_subject = dbarray(dbquery("SELECT locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$data['user_locale']."' AND locales_name = 'forum.post' and locales_key = '550'"));
										$message_subject = str_replace("{THREAD_SUBJECT}", $data['thread_subject'], $message_subject['locales_value']);
										$message_content = dbarray(dbquery("SELECT locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$data['user_locale']."' AND locales_name = 'forum.post' and locales_key = '551'"));
										$message_content = str_replace($message_el1, $message_el2, $message_content['locales_value']);
										$err = sendemail($data['user_name'],$data['user_email'],$settings['siteusername'],($settings['newsletter_email'] != "" ? $settings['newsletter_email'] : $settings['siteemail']),$message_subject,$message_content);
									}
								}
							}
							$result = dbquery("UPDATE ".$db_prefix."thread_notify SET notify_status='0' WHERE thread_id='$thread_id' AND notify_user != '".$userdata['user_id']."'");
						}
						// if we're not tracking, but we want to, insert a notify record
						if (!$notify_self && $userdata['user_posts_track']) {
							$result = dbquery("INSERT INTO ".$db_prefix."thread_notify (thread_id, notify_datestamp, notify_user, notify_status) VALUES('$thread_id', '".time()."', '".$userdata['user_id']."', '1')");
						}
					}
				} else {
					$error = $locale['450'];
				}
			}
			// if no error occured, process any attachments deleted
			if ($error == "") {
				if (isset($_POST['delattach']) && count($_POST['delattach']) != 0) {
					foreach($_POST['delattach'] as $key => $value) {
						// check if it is a new upload
						if ($value[0] == "-") {
							$attachments[substr($value,1)]['attach_size'] = 0;
						} else {
							// delete the attachment
							$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE attach_id = '$value'");
							if (dbrows($result) != 0) {
								$attach = dbarray($result);
								@unlink(PATH_ATTACHMENTS.$attach['attach_name']);
								// if a thumb exists, delete that too...
								if (file_exists(PATH_ATTACHMENTS.$attach['attach_name'].".thumb")) {
								    @unlink(PATH_ATTACHMENTS.$attach['attach_name'].".thumb");
								}
								$result2 = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE attach_id='$value'");
							}
						}
					}
				}
			}
			// if no error occured, process any attachments uploaded
			if ($error == "") {
				foreach($attachments as $key => $attachment) {
					// remove attachments with a size 0, save all others
					if ($attachment['attach_size'] == 0) {
						@unlink(PATH_ATTACHMENTS.$attachment['attach_tmp']);
						// if a thumb exists, delete that too...
						if (file_exists(PATH_ATTACHMENTS.$attachment['attach_tmp'].".thumb")) {
						    @unlink(PATH_ATTACHMENTS.$attachment['attach_tmp'].".thumb");
						}
					} else {
						$attachname = substr($attachment['attach_name'], 0, strrpos($attachment['attach_name'], "."));
						$attachext = strtolower(strrchr($attachment['attach_name'],"."));
						$attachtypes = explode(",", $settings['attachtypes']);
						$attachname = attach_exists(strtolower($attachment['attach_name']));
						if (file_exists(PATH_ATTACHMENTS.$attachment['attach_tmp'].".thumb")) {
							rename(PATH_ATTACHMENTS.$attachment['attach_tmp'].".thumb", PATH_ATTACHMENTS.$attachname.".thumb");
							chmod(PATH_ATTACHMENTS.$attachname.".thumb",0664);
						}
						rename(PATH_ATTACHMENTS.$attachment['attach_tmp'], PATH_ATTACHMENTS.$attachname);
						chmod(PATH_ATTACHMENTS.$attachname,0664);
						$result = dbquery("INSERT INTO ".$db_prefix."forum_attachments (thread_id, post_id, attach_name, attach_realname, attach_comment, attach_ext, attach_size) VALUES ('$thread_id', '$post_id', '$attachname', '".$attachment['attach_name']."', '".$attachment['attach_comment']."', '$attachext', '".$attachment['attach_size']."')");
					}
				}
			}
			// final result dialog
			if ($error == "") {
				resultdialog($title, $msg, true);
			} else {
				resultdialog($title, $error, false, true);
			}
			break;
	}

} elseif (isset($_POST['delete_post'])) {
		fpm_delete();
		$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE post_id='$post_id' AND thread_id='$thread_id'");
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
		if (dbrows($result) != 0) {
			while ($attach = dbarray($result)) {
				unlink(PATH_ATTACHMENTS.$attach['attach_name']);
				if (file_exists(PATH_ATTACHMENTS.$attach['attach_name'].".thumb")) {
				    unlink(PATH_ATTACHMENTS.$attach['attach_name'].".thumb");
				}
			}
			$result2 = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
		}
		$post_id = 0;
		$posts = dbcount("(post_id)", "posts", "thread_id='$thread_id'");
		if ($posts == 0) {
			$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE thread_id='$thread_id'");
			$thread_id = 0;
		}
		// update forum_lastpost and forum_lastuser if post_datestamp matches
		$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id' AND forum_lastuser='".$pdata['post_author']."' AND forum_lastpost='".$pdata['post_datestamp']."'");
		if (dbrows($result)) {
			$result = dbquery("SELECT forum_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE forum_id='$forum_id' ORDER BY post_datestamp DESC LIMIT 1");
			if (dbrows($result)) {
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata2['post_datestamp']."', forum_lastuser='".$pdata2['post_author']."' WHERE forum_id='$forum_id'");
			} else {
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='0', forum_lastuser='0' WHERE forum_id='$forum_id'");
			}
		}
		// update thread_lastpost and thread_lastuser if thread post > 0 and post_datestamp matches
		if ($posts > 0) {
			$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE thread_id='$thread_id' AND thread_lastpost='".$pdata['post_datestamp']."' AND thread_lastuser='".$pdata['post_author']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT thread_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_datestamp DESC LIMIT 1");
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".$pdata2['post_datestamp']."', thread_lastuser='".$pdata2['post_author']."' WHERE thread_id='$thread_id'");
			}
		}
		resultdialog($locale['407'], $locale['445'], $redirect=true);
} elseif (isset($_POST["move_post"])) {
	if (isset($_POST['new_forum_id']) && isset($_POST['new_thread_id'])) {
		// is the old post linked to a poll?
		$is_poll = dbcount("(*)", "forum_polls", "post_id='$post_id'");
		if ($is_poll) {
			resultdialog($locale['412'], $locale['501'], $redirect=true);
		} else {
			// move the post to the new thread
			$result = dbquery("UPDATE ".$db_prefix."posts SET thread_id='".$_POST['new_thread_id']."', forum_id='".$_POST['new_forum_id']."' WHERE post_id='$post_id'");
			// move attachments as well
			$result = dbquery("UPDATE ".$db_prefix."forum_attachments SET thread_id='".$_POST['new_thread_id']."' WHERE post_id='$post_id'");
			// update the forum record of the new thread
			$result = dbquery("SELECT MAX(forum_lastpost) as lastpost FROM ".$db_prefix."forums WHERE forum_id='".$_POST['new_forum_id']."'");
			if (dbrows($result) == 0) fallback("index.php");
			$data = dbarray($result);
			if ($data['lastpost'] < $pdata['post_datestamp'])
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata['post_datestamp']."', forum_lastuser='".$pdata['post_author']."' WHERE forum_id='".$_POST['new_forum_id']."'");
			// update the thread record of the new thread
			$result = dbquery("SELECT MAX(thread_lastpost) as lastpost FROM ".$db_prefix."threads WHERE thread_id='".$_POST['new_thread_id']."'");
			if (dbrows($result) == 0) fallback("index.php");
			$data = dbarray($result);
			if ($data['lastpost'] < $pdata['post_datestamp'])
				$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".$pdata['post_datestamp']."', thread_lastuser='".$pdata['post_author']."' WHERE thread_id='".$_POST['new_thread_id']."'");
			// update the forum record of the old thread
			$result = dbquery("SELECT MAX(forum_lastpost) as lastpost FROM ".$db_prefix."forums WHERE forum_id='".$forum_id."'");
			if (dbrows($result) == 0) fallback("index.php");
			$data = dbarray($result);
			if ($data['lastpost'] < $pdata['post_datestamp'])
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata['post_datestamp']."', forum_lastuser='".$pdata['post_author']."' WHERE forum_id='".$forum_id."'");
			// check if there are posts left in the old thread
			$posts = dbcount("(post_id)", "posts", "thread_id='$thread_id'");
			if ($posts == 0) {
				// delete the old thread
				$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
				$result = dbquery("DELETE FROM ".$db_prefix."threads_read WHERE thread_id='$thread_id'");
				$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE thread_id='$thread_id'");
			} else {
				// update the old thread
				$result = dbquery("SELECT MAX(thread_lastpost) as lastpost FROM ".$db_prefix."threads WHERE thread_id='".$thread_id."'");
				if (dbrows($result) == 0) fallback("index.php");
				$data = dbarray($result);
				if ($data['lastpost'] < $pdata['post_datestamp'])
					$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".$pdata['post_datestamp']."', thread_lastuser='".$pdata['post_author']."' WHERE thread_id='".$thread_id."'");
			}
			$forum_id = $_POST['new_forum_id'];
			$thread_id = $_POST['new_thread_id'];
			// redirect to the new post
			resultdialog($locale['412'], $locale['456'], $redirect=true);
		}
	} elseif (isset($_POST['new_forum_id'])) {
		$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='".$_POST['new_forum_id']."'");
		if (dbrows($result) == 0) fallback("index.php");
		$data = dbarray($result);
		$variables['forum_name'] = $data['forum_name'];
		$variables['new_forum_id'] = $_POST['new_forum_id'];
		$variables['forum_id'] = $forum_id;
		$variables['thread_id'] = $thread_id;
		$variables['post_id'] = $post_id;

		// get the data for the threads dropdown
		$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE forum_id='".$_POST['new_forum_id']."' AND thread_id != '$thread_id' ORDER BY thread_lastpost DESC");
		$variables['threads'] = array();
		while ($data = dbarray($result)) {
			$data['thread_ident'] = substr('     '.$data['thread_id'], -5).' » '.$data['thread_subject'];
			$variables['threads'][] = $data;
		}

		// second stage of the move
		$variables['stage'] = 2;

		// define the panel
		$template_panels[] = array('type' => 'body', 'name' => 'forum.movepost.2', 'template' => 'forum.post.move.tpl', 'locale' => array("forum.main", "forum.post"));
		$template_variables['forum.movepost.2'] = $variables;

	} else {

		$variables['forum_id'] = $forum_id;
		$variables['thread_id'] = $thread_id;
		$variables['post_id'] = $post_id;

		// first stage of the move
		$variables['stage'] = 1;

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
		// define the panel
		$template_panels[] = array('type' => 'body', 'name' => 'forum.movepost.1', 'template' => 'forum.post.move.tpl', 'locale' => array("forum.main", "forum.post"));
		$template_variables['forum.movepost.1'] = $variables;
	}
} elseif (isset($_POST["renew_post"])) {
	$result = dbquery("UPDATE ".$db_prefix."posts SET post_datestamp='".time()."' WHERE forum_id='$forum_id' AND thread_id='$thread_id' AND post_id='$post_id'");
	resultdialog($locale['416'], $locale['459'], $redirect=true);
} else {
	// process the action parameter: set the available options
	$variables['opt_sticky'] = false;
	$variables['opt_notify'] = false;
	$variables['opt_showsig'] = false;
	$variables['opt_smileys'] = true;
	switch ($action) {
		case "newthread":
			$variables['opt_sticky'] = true;
			$variables['opt_notify'] = true;
		case "postreply":
		case "reply":
		case "quote":
			$variables['opt_showsig'] = true;
		case "edit":
	}
	// process the action parameter: prepare the edit form
	switch ($action) {
		case "newthread":
			if (!isset($title)) $title = $locale['401'];
			if (!isset($variables['button_save'])) $variables['button_preview'] = $locale['400'];
			if (!isset($variables['button_save'])) $variables['button_save'] = $locale['401'];

		case "postreply":
			if (!isset($title)) {
				$title = $locale['415']." #".$reply_id;
				if (isset($pdata['user_name']) && $pdata['user_name']) $title .= " [Re: ".$pdata['user_name']."]";
			}
			if (!isset($variables['button_save'])) $variables['button_preview'] = $locale['402'];
			if (!isset($variables['button_save'])) $variables['button_save'] = $locale['404'];

		case "reply":
			if (!isset($title)) $title = $locale['403'];
			if (!isset($variables['button_save'])) $variables['button_preview'] = $locale['402'];
			if (!isset($variables['button_save'])) $variables['button_save'] = $locale['404'];

		case "edit":
			if (!isset($title)) $title = $locale['408'];
			if (!isset($variables['button_save'])) $variables['button_preview'] = $locale['405'];
			if (!isset($variables['button_save'])) $variables['button_save'] = $locale['409'];

		case "quote":
			$prefixes = explode(',', trim($fdata['forum_prefixes']));
			$variables['prefixes'] = array();
			foreach ($prefixes as $prefix) {
				$variables['prefixes'][] = trim($prefix);
			}
			if (!isset($title)) $title = $locale['415']." #".$reply_id;
			if (!isset($variables['button_save'])) $variables['button_preview'] = $locale['402'];
			if (!isset($variables['button_save'])) $variables['button_save'] = $locale['404'];
			if (isset($_POST['random_id'])) {
				$variables['random_id'] = $_POST['random_id'];
			} else {
				$variables['random_id'] = md5(microtime());
			}
			if (isset($_POST['message'])) {
				$variables['subject'] = trim(stripinput(censorwords($_POST['subject'])));;
				$variables['message'] = trim(stripmessageinput(censorwords($_POST['message'])));
				$variables['post_author'] = $_POST['post_author'];
				$variables['is_sticky'] = isset($_POST['sticky']);
				$variables['is_smiley_disabled'] = isset($_POST['disable_smileys']);
				$variables['is_sig_shown'] = isset($_POST['show_sig']);
				$variables['is_notified'] = isset($_POST['notify_me']);
				$variables['comments'] = isset($_POST['attach_comment']) ? $_POST['attach_comment']:"";
				$variables['org_message'] = isset($_POST['org_message']) ? $_POST['org_message'] : "";
			} elseif ($post_id > 0 || $reply_id > 0) {
				if (strtolower(substr($pdata['post_subject'],0,3)) == "re:") {
					$variables['subject'] = $pdata['post_subject'];
				} else {
					if ($action != "edit")
						$variables['subject'] = 'Re: '.$pdata['post_subject'];
					else
						$variables['subject'] = $pdata['post_subject'];
				}
				switch ($action) {
					case "edit":
						$variables['message'] = $pdata['post_message'];
						break;
					case "quote":
						$variables['message'] = $variables['orgauthor'] == "" ? "[quote]" : "[quote=".$variables['orgauthor']."]";
						$variables['message'] .= $pdata['post_message']."[/quote]";
						break;
					default:
						$variables['message'] = "";
				}
				$variables['org_message'] = $pdata['post_message'];
				$variables['post_author'] = $pdata['post_author'];
				$variables['is_smiley_disabled'] = ($pdata['post_smileys'] == "0");
				$variables['is_sticky'] = ($pdata['post_sticky'] == "1");
				$variables['is_sig_shown'] = ($pdata['post_showsig'] == "1");
				$variables['comments'] = "";
				$variables['del_check'] = false;
				$variables['del_attach_check'] = false;
			} else {
				$variables['subject'] = $thread_id>0?('Re: '.$tdata['thread_subject']):"";
				$variables['message'] = "";
				$variables['org_message'] = "";
				$variables['comments'] = "";
				$variables['post_author'] = $userdata['user_id'];
				$variables['is_smiley_disabled'] = false;
				$variables['is_sticky'] = false;
				$variables['is_sig_shown'] = true;
				$variables['is_notified'] = false;
				$bbcolor = "";
			}
			// process attachments
			if ($settings['attachments'] == "1" && $fdata['forum_attach'] == "1") {
				$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id' ORDER BY attach_id");
				$rows = dbrows($result);
				$variables['attachment_count'] = $rows + count($attachments);
				$variables['attachments'] = array();
				$next = 0;
				while ($adata = dbarray($result)) {
					$next++;
					$adata['delete_checked'] = isset($_POST['delattach'][$next]);
					$adata['new'] = false;
					$adata['index'] = $next;
					$variables['attachments'][] = $adata;
				}
				foreach($attachments as $key => $attachment) {
					$next++;
					$adata = array();
					$adata['new'] = true;
					$adata['delete_checked'] = isset($_POST['delattach'][$next]);
					$adata['key'] = $key;
					$adata['index'] = $next;
					$adata['attach_tmp'] = $attachment['attach_tmp'];
					$adata['type'] = $attachment['type'];
					$adata['attach_size'] = $attachment['attach_size'];
					$adata['attach_count'] = $attachment['attach_count'];
					$adata['attach_comment'] = $attachment['attach_comment'];
					$adata['attach_name'] = $attachment['attach_name'];
					$adata['attach_ext'] = $attachment['attach_ext'];
					$variables['attachments'][] = $adata;
				}
			}

			// colors for the color dropdown
			$variables['fontcolors'] = array();
			$result = dbquery("SELECT locales_key, locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$settings['locale_code']."' AND locales_name = 'colors'");
			while ($data = dbarray($result)) {
				$variables['fontcolors'][] = array('color' => $data['locales_key'], 'name' => $data['locales_value']);
			}

			// store the variables needed by this panel
			$variables['action'] = $action;
			$variables['forum_id'] = $forum_id;
			$variables['thread_id'] = $thread_id;
			$variables['post_id'] = $post_id;
			$variables['reply_id'] = $reply_id;
			$variables['fpm'] = $fpm;
			$variables['fpm_settings'] = $fpm_settings;
			$variables['attachmax'] = parsebytesize($settings['attachmax']);
			$variables['attachtypes'] = str_replace(',', ' ', $settings['attachtypes']);
			// load the hoteditor if needed
			if ($settings['hoteditor_enabled'] && (!iMEMBER || $userdata['user_hoteditor'])) {
				define('LOAD_HOTEDITOR', true);
			}
			// define the panel
			$template_panels[] = array('type' => 'body', 'name' => 'forum.post', 'title' => $title, 'template' => 'forum.post.tpl', 'locale' => array("forum.main", "forum.post", "admin.forum_polls"));
			$template_variables['forum.post'] = $variables;
			break;

		case "track_on":
			$result	= dbquery("INSERT INTO ".$db_prefix."thread_notify (thread_id, notify_datestamp, notify_user, notify_status) VALUES('$thread_id', '".time()."', '".$userdata['user_id']."', '1')");
			resultdialog($locale['451'], $locale['452'], true);
			break;

		case "track_off":
			$result	= dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE thread_id='$thread_id'	AND notify_user='".$userdata['user_id']."'");
			resultdialog($locale['451'], $locale['453'], true);
			break;

		case "quickreply":
			switch ($errorcode) {
				case "1":
					resultdialog($locale['403'], $locale['431']."<br />".sprintf($locale['433'], $settings['flood_interval']), false);
					break;
				case "2":
					resultdialog($locale['403'], $locale['431']."<br />".$locale['432'], false);
					redirect(BASEDIR.'index.php', 'script');
					break;
				case "3":
					resultdialog($locale['403'], $locale['458'], false);
					break;
				default:
					resultdialog($locale['403'], $locale['443'], true);
			}
			break;
	}
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
