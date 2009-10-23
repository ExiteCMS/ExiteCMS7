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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_INCLUDES."theme_functions.php";

// temp storage for template variables
$variables = array();

// load the locale for this module
locale_load("main.pm");

// include the pm functions
require_once PATH_INCLUDES."pm_functions_include.php";

// include the forum functions
require_once PATH_INCLUDES."forum_functions_include.php";

// include the image functions
require_once PATH_INCLUDES."photo_functions_include.php";

// access to members only
if (!iMEMBER) fallback(BASEDIR."index.php");

/*---------------------------------------------------+
| function to gather all information needed to       |
| render a single private message                    |
+----------------------------------------------------*/
function gathermsginfo($msgrec, $preview = false) {

	global $variables, $attachments, $imagetypes, $userdata, $settings, $locale, $db_prefix;

	// get the information of the sender
	$msgrec['sender'] = array();
	if ($msgrec['pmindex_from_id'] == 0) {
		// automatic post
		$msgrec['sender']['user_name'] = $locale['sysusr'];
		$msgrec['sender']['user_posts'] = "-";
		$data2 = dbarray(dbquery("SELECT user_level, user_joined FROM ".$db_prefix."users WHERE user_id = '1'"));
		$msgrec['sender']['user_joined'] = $data2['user_joined'];
		$msgrec['sender']['user_level'] = 0;
		$msgrec['sender']['user_location'] = "-";
		$msgrec['sender']['user_sig'] = "";
		$msgrec['sender']['user_status'] = "0";
	} else {
		$result2 = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id = '".$msgrec['pmindex_from_id']."'");
		if ($data2 = dbarray($result2)) {
			$data2['group_names'] = array();
			// user & group memberships
			$data2['group_names'][] = array('type' => 'U', 'level' => $data2['user_level'], 'name' => getuserlevel($data2['user_level']));
			if ($data2['user_groups'] != "") {
				$gresult = dbquery("SELECT group_name, group_forumname, group_color FROM ".$db_prefix."user_groups WHERE group_id IN (".str_replace('.', ',', substr($data2['user_groups'],1)).") AND group_visible & 2");
				$grecs = dbrows($gresult);
				while ($gdata = dbarray($gresult)) {
					$data2['group_names'][] = array('type' => 'G', 'color' => $gdata['group_color'], 'name' => $gdata['group_forumname']==""?$gdata['group_name']:$gdata['group_forumname']);
			}
			}
			// country flag
			if ($settings['forum_flags']) {
				// fix the webmaster to the site's country code
				if ($msgrec['pmindex_from_id'] == 1) {
					$data2['cc_flag'] = GeoIP_Code2Flag($settings['country']);
				} else {
					$data2['cc_flag'] = GeoIP_IP2Flag($data2['user_ip']);
				}
			} else {
				$data2['cc_flag'] = GeoIP_Code2Flag("");
			}
			$msgrec['sender'] = $data2;
		}
	}

	// get the information this recipient (for received messages only)
	$msgrec['recipient'] = array();
	if ($msgrec['pmindex_to_id'] != 0) {
		$result2 = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id = '".$msgrec['pmindex_to_id']."'");
		if ($data2 = dbarray($result2)) {
			$data2['group_names'] = array();
			// user & group memberships
			$data2['group_names'][] = array('type' => 'U', 'level' => $data2['user_level'], 'name' => getuserlevel($data2['user_level']));
			if ($data2['user_groups'] != "") {
				$gresult = dbquery("SELECT group_name, group_forumname, group_color FROM ".$db_prefix."user_groups WHERE group_id IN (".str_replace('.', ',', substr($data2['user_groups'],1)).") AND group_visible & 2");
				$grecs = dbrows($gresult);
				while ($gdata = dbarray($gresult)) {
					$data2['group_names'][] = array('type' => 'G', 'color' => $gdata['group_color'], 'name' => $gdata['group_forumname']==""?$gdata['group_name']:$gdata['group_forumname']);
				}
			}
			// country flag
			if ($settings['forum_flags']) {
				// fix the webmaster to the site's country code
				if ($msgrec['pmindex_to_id'] == 1) {
					$data2['cc_flag'] = GeoIP_Code2Flag($settings['country']);
				} else {
					$data2['cc_flag'] = GeoIP_IP2Flag($data2['user_ip']);
				}
			} else {
				$data2['cc_flag'] = GeoIP_Code2Flag("");
			}
			$msgrec['recipient'] = $data2;
		}
	}

	// get the information for the recipient(s)
	$recipients = explode(",", $msgrec['pm_recipients']);
	$msgrec['recipients'] = array();
	foreach ($recipients as $recipient) {
		if ($recipient < 0) {
			// recipient is a user group
			$result2 = dbquery("SELECT * FROM ".$db_prefix."user_groups WHERE group_id = '".abs($recipient)."'");
			if ($data2 = dbarray($result2)) {
				$data2['id'] = $recipient;
				$data2['cc_flag'] = GeoIP_Code2Flag("");
				$msgrec['recipients'][] = $data2;
			}
		} else {
			// recipient is a single member
			$result2 = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id = '".$recipient."'");
			if ($data2 = dbarray($result2)) {
				$data2['id'] = $recipient;
				// country flag
				if ($settings['forum_flags']) {
					// fix the webmaster to the site's country code
					if ($recipient == 1) {
						$data2['cc_flag'] = GeoIP_Code2Flag($settings['country']);
					} else {
						$data2['cc_flag'] = GeoIP_IP2Flag($data2['user_ip']);
					}
				} else {
					$data2['cc_flag'] = GeoIP_Code2Flag("");
				}
				$msgrec['recipients'][] = $data2;
			}
		}
	}
	$msgrec['recipient_count'] = count($msgrec['recipients']);
	// if only one recipient, copy it
	if ($msgrec['recipient_count'] == 1) {
		$msgrec['recipient'] = $msgrec['recipients'][0];
		// user & group memberships
		$msgrec['recipient']['group_names'][] = array('type' => 'U', 'level' => $msgrec['recipient']['user_level'], 'name' => getuserlevel($msgrec['recipient']['user_level']));
		if ($msgrec['recipient']['user_groups'] != "") {
			$gresult = dbquery("SELECT group_name, group_forumname, group_color FROM ".$db_prefix."user_groups WHERE group_id IN (".str_replace('.', ',', substr($msgrec['recipient']['user_groups'],1)).") AND group_visible & 2");
			$grecs = dbrows($gresult);
			while ($gdata = dbarray($gresult)) {
				$msgrec['recipient']['group_names'][] = array('type' => 'G', 'color' => $gdata['group_color'], 'name' => $gdata['group_forumname']==""?$gdata['group_name']:$gdata['group_forumname']);
			}
		}
	}

	// parse the messsage body
	$msgrec['pm_message'] = parsemessage(array('pm_id' => $msgrec['pmindex_id']), $msgrec['pm_message'], $msgrec['pm_smileys']==0, false);

	// check if the users avatar exists
	if (!empty($msgrec['user_avatar']) && !file_exists(PATH_IMAGES."avatars/".$msgrec['user_avatar'])) $msgrec['user_avatar'] = "imagenotfound.jpg";

	// prepare the users signature
	if (!empty($msgrec['user_sig'])) {
		$msgrec['user_sig'] = parsemessage(array(), $msgrec['user_sig'], true, true);
	}

	// check if there are attachments for this message
	$msgrec['attachments'] = array();
	// process attachments
	if ($settings['attachments']) {
		$result2 = dbquery("SELECT * FROM ".$db_prefix."pm_attachments WHERE pm_id='".$msgrec['pm_id']."' ORDER BY pmattach_id");
		$rows = dbrows($result2);
		$msgrec['attachment_count'] = $rows;
		$msgrec['attachments'] = array();
		$next = 0;
		while ($adata = dbarray($result2)) {
			$next++;
			$adata['delete_checked'] = isset($_POST['delattach'][$next]);
			$adata['new'] = false;
			$adata['index'] = $next;
			$file = PATH_PM_ATTACHMENTS.$adata['pmattach_name'];
			if (file_exists($file)) {
				$adata['is_found'] = true;
				$adata['link'] = PM_ATTACHMENTS.$adata['pmattach_name'];
				$adata['size'] = parsebytesize(filesize($file),0);
				if (in_array($adata['pmattach_ext'], $imagetypes)) {
					// check if it really is an image
					$imageinfo = @getimagesize($file);
					if (is_array($imageinfo)) {
						$adata['is_image'] = true;
						$adata['imagesize'] = array('x' => $imageinfo[0], 'y' => $imageinfo[1]);
						// check if this image has a thumbnail
						if (file_exists($file.".thumb")) {
							$adata['thumbnail'] = PM_ATTACHMENTS.$adata['pmattach_name'].".thumb";
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
			$msgrec['attachments'][] = $adata;
		}
	}
	return $msgrec;
}


/*---------------------------------------------------+
| function to check and store the attachment upload  |
| in a temp location                                 |
+----------------------------------------------------*/
function storeupload() {

	global $db_prefix, $settings, $locale, $imagetypes, $attachments;

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
				return $locale['474b'];
			default:
				return sprintf($locale['474'], $_FILES['attach']['error'], $_FILES['attach']['name']);
		}
		// check against our max. upload filesize
		if ($_FILES['attach']['size'] > $settings['attachmax']) {
			return $locale['474b'];
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
			$attachtypes = explode(",", $settings['attachtypes']);
			if (in_array($attachext, $attachtypes)) {
				@unlink($attach['attach_tmp']);
	            return $locale['474a'];
			}
		} else {
			unlink($attach['attach_tmp']);
	        return $locale['474d'];
		}
		// file is ok. Move it to a safe temp location
		$tmp_name = tempnam(PATH_PM_ATTACHMENTS, '$$$_');
		if (!move_uploaded_file($attach['attach_tmp'], $tmp_name)) {
			return $locale['474d'];
		}
		// if it's an image, see if we need to make a thumbnail
		if (in_array($attachext, $imagetypes)) {
			if (@getimagesize($tmp_name) && @verify_image($tmp_name)) {
				// it's a valid image. See if we need to generate a thumbnail
				$imagefile = @getimagesize($tmp_name);
				if ($imagefile[0] > $settings['forum_max_w'] || $imagefile[1] > $settings['forum_max_h']) {
					// image is bigger than the defined maximum image size. Generate a thumb image
					createthumbnail($imagefile[2], $tmp_name, $tmp_name.".thumb", $settings['thumb_w'], $settings['thumb_h']);
				}
			} else {
				// not a valid image
				unlink($tmp_name);
	            return $locale['474c'];
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
| Main                                               |
+----------------------------------------------------*/

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
	$template_panels[] = array('type' => 'body', 'name' => 'message_panel', 'title' => $locale['475'], 'template' => '_message_table_panel.tpl');
	$variables['message'] = $error;
	$variables['bold'] = true;
	$template_variables['message_panel'] = $variables;
	// reset the variables array
	$variables = array();
}

// if messages are checked, gather the message id's
$msg_ids = ""; $check_count = 0;
if (isset($_POST['check_mark'])) {
	if (is_array($_POST['check_mark']) && count($_POST['check_mark']) > 1) {
		foreach ($_POST['check_mark'] as $thisnum) {
			if (isNum($thisnum)) $msg_ids .= ($msg_ids ? "," : "").$thisnum;
			$check_count++;
		}
	} else {
		if (isNum($_POST['check_mark'][0])) $msg_ids = $_POST['check_mark'][0];
		$check_count = 1;
	}
}

// get the users message options
$result = dbquery("SELECT * FROM ".$db_prefix."pm_config WHERE user_id='".$userdata['user_id']."'");
if (dbrows($result)) {
	$variables['update_type'] = "update";
	$user_options = dbarray($result);
} else {
	$variables['update_type'] = "insert";
	$user_options = $global_options;
}
$variables['user_options'] = $user_options;

// define how many messages per page we want
switch ($variables['user_options']['pmconfig_view']) {
	case "0":
		define('ITEMS_PER_PAGE', $settings['numofthreads']);
		break;
	case "1":
		define('ITEMS_PER_PAGE', intval($settings['numofthreads']/2));
		break;
}

// determine the active folder
if (!isset($folder) || !preg_match("/^(".$locale['402']."|".$locale['403']."|".$locale['404']."|".$locale['425'].")$/", $folder)) $folder = $locale['402'];

// get the folder totals
$totals = array();
$totals['inbox'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$userdata['user_id']."' AND pmindex_folder = '0'");
$totals['outbox'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$userdata['user_id']."' AND pmindex_folder = '1'");
$totals['archive'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$userdata['user_id']."' AND pmindex_folder = '2'");

// make sure the action variable is defined
if (!isset($action)) $action = "";

// make sure view_id has a value
$variables['view_id'] = 0;

// reset the error message
$variables['errormessage'] = "";

// process the posted message
if (isset($_POST['send_message'])) {

	// check if the msg_id (== pmindex_id!) exists, and if so, retrieve the pm_id
	if ($msg_id) {
		$result = dbquery("SELECT pm_id FROM ".$db_prefix."pm_index WHERE pmindex_id = '$msg_id' LIMIT 1");
		if (dbrows($result)) {
			$data = dbarray($result);
			$pm_id = $data['pm_id'];
		} else {
			$msg_id = 0;
			$pm_id = 0;
		}
	} else {
		$pm_id = 0;
	}

	// create the message record
	$newmsg = array();

	// check if we have a subject and a message body
	$subject = stripinput(trim($_POST['subject']));
	$message = stripmessageinput(trim($_POST['message']));
	// if not, return to the inbox folder
	if ($subject == "" || $message == "") fallback(FUSION_SELF."?folder=".$locale[402]);

	$newmsg['pm_subject'] = $subject;
	$newmsg['pm_message'] = $message;
	$newmsg['pm_size'] = strlen($message);
	$newmsg['pm_datestamp'] = time();

	// smileys setting for this post
	$newmsg['pm_smileys'] = isset($_POST['chk_disablesmileys']) ? "1" : "0";

	// create the list of users to send this message to
	$newmsg['recipients'] = isset($_POST['recipients']) && is_array($_POST['recipients']) ? $_POST['recipients'] : array();
	$user_ids = array();
	$newmsg['user_ids'] = array();

	foreach($newmsg['recipients'] as $recipient) {

		// process individual users
		if ($recipient >= 0) {
			$result = dbquery(
				"SELECT u.user_id, u.user_name, u.user_email, u.user_locale, mo.pmconfig_email_notify, COUNT(pmindex_id) as message_count FROM ".$db_prefix."users u
				LEFT JOIN ".$db_prefix."pm_config mo USING(user_id)
				LEFT JOIN ".$db_prefix."pm_index pmi ON pmindex_to_id=u.user_id AND pmindex_folder='0'
				LEFT JOIN ".$db_prefix."pm pm ON pm.pm_id=pmi.pm_id
				WHERE u.user_id='".$recipient."' GROUP BY u.user_id"
			);
		}

		// process user groups
		if ($recipient < 0) {
			$group_id = abs($recipient);
			if ($group_id == "101" || $group_id == "102" || $group_id == "103") {
				// message to a user_level based group
				$result = dbquery(
					"SELECT u.user_id, u.user_name, u.user_email, u.user_locale, mo.pmconfig_email_notify FROM ".$db_prefix."users u
					LEFT JOIN ".$db_prefix."pm_config mo USING(user_id)
					WHERE user_status = '0' AND user_level >= '".$group_id."'"
				);
			} else {
				// message to a user_groups based group
				$groups = array();
				// gather the group and it's sub-groups into an array
				getgroupmembers($group_id);
				$sql = "SELECT u.user_id, u.user_name, u.user_email, u.user_locale, mo.pmconfig_email_notify FROM ".$db_prefix."users u
						LEFT JOIN ".$db_prefix."pm_config mo USING(user_id)
						WHERE ";
				$c = 0;
				foreach ($groups as $group) {
					$sql .= ($c++==0?"":"OR ")."user_groups REGEXP('^\\\.{$group}$|\\\.{$group}\\\.|\\\.{$group}$') ";
				}
				$result = dbquery($sql);
			}
		}

		// process the user information retrieved
		while ($data = dbarray($result)) {
			// if we don't have PM options for this user, use the global one
			if ( is_null($data['pmconfig_email_notify']) ) {
				$data['pmconfig_email_notify'] = $global_options['pmconfig_email_notify'];
			}
			// make sure we don't already have this user (due to group membership)
			if (!in_array($data['user_id'], $user_ids)) {
			// add it to the processed user_ids list
				$user_ids[] = $data['user_id'];
				$newmsg['user_ids'][] = $data;
			}
		}
	}

	// store the message
	$variables['errormessage'] = storemessage($newmsg, $pm_id);

	// return to the outbox folder
	$action = "";
	$folder = $locale['403'];

}

// process the actions
if (isset($_POST['close'])) {

	// delete any newly uploaded attachments
	foreach ($attachments as $attachment) {
		if (file_exists(PATH_PM_ATTACHMENTS.$attachment['attach_tmp'].".thumb")) {
			@unlink(PATH_PM_ATTACHMENTS.$attachment['attach_tmp'].".thumb");
		}
		@unlink(PATH_PM_ATTACHMENTS.$attachment['attach_tmp']);
	}
	$action = "";

} elseif ($action == "" && isset($_POST['multi_archive'])) {

	// move the selected messages to the arhive folder
	if ($msg_ids && $check_count > 0) {
		if ($global_options['pm_savebox'] == "0" || ($totals['archive'] + $check_count) <= $global_options['pm_savebox'] || $global_options['pm_savebox_group']) {
			$result = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_folder='2' WHERE pmindex_id IN(".$msg_ids.") AND pmindex_user_id='".$userdata['user_id']."' AND pmindex_read_datestamp != '0'");
		} else {
			$variables['errormessage'] = $locale['629'];
		}
	}

} elseif ($action == "" && isset($_POST['multi_restore'])) {

	// move the selected messages back to the message folder
	if ($msg_ids && $check_count > 0) {
		$msg_ids = explode(",", $msg_ids);
		foreach($msg_ids as $msg_id) {
			// get the message and check to with folder it has to be restored
			$result = dbquery("SELECT pmindex_user_id, pmindex_to_id FROM ".$db_prefix."pm_index WHERE pmindex_id = '".$msg_id."' AND pmindex_user_id='".$userdata['user_id']."'");
			if (dbrows($result)) {
				$data = dbarray($result);
				if ($data['pmindex_user_id'] == $data['pmindex_to_id']) {
					// restore to inbox
					if ($global_options['pm_inbox'] == "0" || $totals['inbox'] < $global_options['pm_inbox'] || $global_options['pm_inbox_group']) {
						$result = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_folder='0' WHERE pmindex_id = '".$msg_id."' AND pmindex_user_id='".$userdata['user_id']."'");
						$totals['inbox'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$userdata['user_id']."' AND pmindex_folder = '0'");
					} else {
						$variables['errormessage'] = $locale['629'];
						break;
					}
				} else {
					// restore to outbox
					if ($global_options['pm_sentbox'] == "0" || $totals['outbox'] < $global_options['pm_sentbox'] || $global_options['pm_sentbox_group']) {
						$result = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_folder='1' WHERE pmindex_id = '".$msg_id."' AND pmindex_user_id='".$userdata['user_id']."'");
						$totals['outbox'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$userdata['user_id']."' AND pmindex_folder = '1'");
					} else {
						$variables['errormessage'] = $locale['629'];
						break;
					}
				}
			}
		}
	}

} elseif ($action == "" && isset($_POST['multi_read'])) {

	// mark the selected messages as read
	if ($msg_ids && $check_count > 0) {
		$result = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_read_datestamp='".time()."' WHERE pmindex_id IN(".$msg_ids.") AND pmindex_to_id='".$userdata['user_id']."'");
	}

} elseif ($action == "" && isset($_POST['multi_unread'])) {

	// mark the selected messages as unread
	if ($msg_ids && $check_count > 0) {
		$result = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_read_datestamp='0' WHERE pmindex_id IN(".$msg_ids.") AND pmindex_to_id='".$userdata['user_id']."'");
	}

} elseif ($action == "" && isset($_POST['multi_delete'])) {

	// delete the selected messages
	if ($msg_ids && $check_count > 0) {
		$msg_ids = explode(",", $msg_ids);
		foreach($msg_ids as $msg_id) {
			deletemessage($msg_id, $userdata['user_id']);
		}
	}

} elseif ($action == "" && isset($_POST['save_options'])) {

	// save the changes to the user options
	$pmconfig_email_notify = isNum($_POST['pm_email_notify']) ? $_POST['pm_email_notify'] : "0";
	$pmconfig_read_notify = isNum($_POST['pm_read_notify']) ? $_POST['pm_read_notify'] : $global_config['pmconfig_read_notify'];
	$pmconfig_save_sent = isNum($_POST['pm_save_sent']) ? $_POST['pm_save_sent'] : $global_config['pmconfig_save_sent'];
	$pmconfig_auto_archive = isNum($_POST['pm_auto_archive']) ? $_POST['pm_auto_archive'] : $global_config['pmconfig_auto_archive'];
	$pmconfig_view = isNum($_POST['pm_view']) ? $_POST['pm_view'] : $global_config['pmconfig_view'];
	if ($_POST['update_type'] == "insert") {
		$result = dbquery("INSERT INTO ".$db_prefix."pm_config (user_id, pmconfig_email_notify, pmconfig_save_sent, pmconfig_read_notify, pmconfig_auto_archive, pmconfig_view) VALUES ('".$userdata['user_id']."', '$pmconfig_email_notify', '$pmconfig_save_sent', '$pmconfig_read_notify', '$pmconfig_auto_archive', '$pmconfig_view')");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."pm_config SET pmconfig_email_notify='$pmconfig_email_notify', pmconfig_save_sent='$pmconfig_save_sent', pmconfig_read_notify='$pmconfig_read_notify', pmconfig_auto_archive='$pmconfig_auto_archive', pmconfig_view='$pmconfig_view' WHERE user_id='".$userdata['user_id']."'");
	}
	redirect(FUSION_SELF."?folder=options");

} elseif ($action == "view") {

	$variables['view_id'] = isset($msg_id) && isNum($msg_id) ? $msg_id : 0;

} elseif ($action == "delete") {

	// delete the selected message
	deletemessage($msg_id, $userdata['user_id']);

} elseif ($action == "archive") {

	if ($global_options['pm_savebox'] == "0" || $totals['archive'] < $global_options['pm_savebox'] || $global_options['pm_savebox_group']) {
		$result = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_folder='2' WHERE pmindex_id = '".$msg_id."' AND pmindex_user_id='".$userdata['user_id']."'");
	} else {
		$variables['errormessage'] = $locale['629'];
	}

} elseif ($action == "restore") {

	// get the message and check to with folder it has to be restored
	$result = dbquery("SELECT pmindex_user_id, pmindex_to_id FROM ".$db_prefix."pm_index WHERE pmindex_id = '".$msg_id."' AND pmindex_user_id='".$userdata['user_id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		if ($data['pmindex_user_id'] == $data['pmindex_to_id']) {
			// restore to inbox
			if ($global_options['pm_inbox'] == "0" || $totals['inbox'] < $global_options['pm_inbox'] || $global_options['pm_inbox_group']) {
				$result = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_folder='0' WHERE pmindex_id = '".$msg_id."' AND pmindex_user_id='".$userdata['user_id']."'");
			} else {
				$variables['errormessage'] = $locale['629'];
			}
		} else {
			// restore to outbox
			if ($global_options['pm_sentbox'] == "0" || $totals['outbox'] < $global_options['pm_sentbox'] || $global_options['pm_sentbox_group']) {
				$result = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_folder='1' WHERE pmindex_id = '".$msg_id."' AND pmindex_user_id='".$userdata['user_id']."'");
			} else {
				$variables['errormessage'] = $locale['629'];
			}
		}
	}

}

if (isset($_POST['upload']) || isset($_POST['send_preview']) || $action == "post" || $action == "forward" || $action == "reply" || $action == "quote") {

	// make sure msg_id and user_id have a valid value
	$msg_id = (isset($msg_id) && isNum($msg_id)) ? $msg_id : 0;
	$user_id = (isset($user_id) && isNum($user_id)) ? $user_id : 0;
	$group_id = (isset($group_id) && isNum($group_id)) ? $group_id : 0;
	$variables['recipient_given'] = ($user_id || $group_id);

	// check if the msg_id (== pmindex_id!) exists, and if so, retrieve the pm_id
	if ($msg_id) {
		$result = dbquery("SELECT pm_id FROM ".$db_prefix."pm_index WHERE pmindex_id = '$msg_id' LIMIT 1");
		if (dbrows($result)) {
			$data = dbarray($result);
			$pm_id = $data['pm_id'];
		} else {
			$msg_id = 0;
			$pm_id = 0;
		}
	} else {
		$pm_id = 0;
	}

	// prepare reloading commands
	if (isset($_POST['send_preview'])) {

		// mark this as a preview
		$variables['is_preview'] = true;

		// create the preview message array
		$variables['messages'] = array();

		// get the info from the users post
		$data = array();
		$data['pm_id'] = $pm_id;
		$data['pm_subject'] = stripinput($_POST['subject']);
		$data['pm_smileys'] = isset($_POST['chk_disablesmileys']) ? "1" : "0";
		$data['pm_message'] = stripmessageinput($_POST['message']);
		$data['pm_size'] = 0;
		$data['pm_datestamp'] = 0;
		$data['pmindex_id'] = 0;
		$data['pmindex_user_id'] = $userdata['user_id'];
		$data['pmindex_reply_id'] = $msg_id;
		$data['pmindex_from_id'] = $userdata['user_id'];
		$data['pmindex_from_email'] = "";
		$to_id = $_POST['recipients'][0];
		$data['pmindex_to_group'] = ($to_id < 0) ? 1 : 0;
		$data['pmindex_to_id'] = abs($to_id);
		$data['pmindex_to_email'] = "";
		$data['pmindex_read_datestamp'] = 0;
		$data['pmindex_read_requested'] = 0;
		$data['pmindex_folder'] = 0;
		$data['pmindex_locked'] = 0;

		// create the recipients list for this message
		$data['pm_recipients'] = "";
		foreach($_POST['recipients'] as $recipient) {
			$data['pm_recipients'] .= ($data['pm_recipients'] == "" ? "" : "," ) . $recipient;
		}

		// get all other info needed to preview this message
		$data = gathermsginfo($data, true);

		// add attachment info of newly uploaded attachments
		$next = count($data['attachments']);
		foreach($attachments as $key => $attachment) {
			$next++;
			$adata = array();
			$adata['new'] = true;
			$adata['is_found'] = true;
			$adata['is_image'] = false;
			$adata['size'] = parsebytesize($attachment['attach_size'],0);
			$adata['delete_checked'] = isset($_POST['delattach'][$next]);
			$adata['key'] = $key;
			$adata['index'] = $next;
			$adata['attach_tmp'] = $attachment['attach_tmp'];
			$adata['type'] = $attachment['type'];
			$adata['pmattach_id'] = 0;
			$adata['pmattach_comment'] = $attachment['attach_comment'];
			$adata['pmattach_name'] = $attachment['attach_name'];
			$adata['pmattach_realname'] = $attachment['attach_name'];
			$adata['pmattach_ext'] = $attachment['attach_ext'];
			$data['attachments'][] = $adata;
		}
		// update the attachment count
		$data['attachment_count'] = count($data['attachments']);

		// create the message record
		$variables['messages'][] = $data;
	}

	$variables['recipients'] = array();
	// get the recipients from the posted form, or from the URL
	if (isset($_POST['recipients']) && is_array($_POST['recipients'])) {
		foreach($_POST['recipients'] as $recipient) {
			if ($recipient > 0) {
				$name = dbarray(dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id='$recipient'"));
				$variables['recipients'][] = array($recipient, stripinput($name['user_name']));
			} else {
				$name = dbarray(dbquery("SELECT group_name FROM ".$db_prefix."user_groups WHERE group_id='".substr($recipient,1)."'"));
				$variables['recipients'][] = array($recipient, stripinput($name['group_name']));
			}
		}
	} else {
		if ($user_id) {
			$name = dbarray(dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id='$user_id'"));
			$variables['recipients'][] = array($user_id, $name['user_name']);
		}
		if ($group_id) {
			$name = dbarray(dbquery("SELECT group_name FROM ".$db_prefix."user_groups WHERE group_id='$group_id'"));
			$variables['recipients'][] = array(-1 * $group_id, $name['group_name']);
		}
	}

	// if it's not a new post, retrieve message information
	if (isset($_POST['send_preview']) || isset($_POST['upload'])) {
		// reload from POST variables
		$variables['subject'] = stripinput($_POST['subject']);
		$variables['message'] = stripmessageinput($_POST['message']);
		$variables['attach_comment'] = stripinput($_POST['attach_comment']);
		$action = stripinput($_POST['action']);
		$folder = stripinput($_POST['folder']);
		$variables['org_message'] = isset($_POST['org_message']) ? $_POST['org_message'] : "";
		$variables['pmindex_from_id'] = isset($_POST['pmindex_from_id']) ? $_POST['pmindex_from_id'] : 0;
		$variables['pmindex_to_id'] = isset($_POST['pmindex_to_id']) ? $_POST['pmindex_to_id'] : 0;
		$variables['reply_message'] = parsemessage(array(), $variables['org_message'], !isset($_POST['chk_disablesmileys']), false);
	} else {
		if ($action != "post") {
			// load from the database
			if (isset($msg_id) && isNum($msg_id)) {
				$result = dbquery(
					"SELECT * FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
					WHERE m.pm_id = i.pm_id AND i.pmindex_id = '".$msg_id."' LIMIT 1"
				);
				// fallback if the record can not be found
				if (dbrows($result)==0) {
					fallback(FUSION_SELF."?folder=$folder");
				}
				// get the record
				$data = dbarray($result);
				// check if the users owns this record. if not, fall back!
				if ($data['pmindex_user_id'] != $userdata['user_id']) {
					fallback(FUSION_SELF."?folder=$folder");
				}
				$pm_id = $data['pm_id'];
				$variables['subject'] = (!strstr($data['pm_subject'], "RE: ") ? "RE: " : "").$data['pm_subject'];
				$variables['org_message'] = $data['pm_message'];
				$variables['orgauthor'] = "";
				if ($action != "post") {
					if ($data['pmindex_user_id'] == $data['pmindex_to_id'] || $data['pmindex_to_id'] == 0)
						$result2 = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '".$data['pmindex_from_id']."'");
					else
						$result2 = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '".$data['pmindex_to_id']."'");
					if ($result2) {
						$data2 = dbarray($result2);
						if ($action == "quote") {
							$variables['message'] = "[quote=".$data2['user_name']."]".$variables['org_message']."[/quote]";
						} else if ($action == "forward") {
							$variables['message'] = $variables['org_message'];
						}
						$variables['orgauthor'] = $data2['user_name'];
					} else {
						if ($action == "quote") {
							$variables['message'] = "[quote]".$variables['org_message']."[/quote]";
						} else if ($action == "forward") {
							$variables['message'] = $variables['org_message'];
						}
					}
				}
				$variables['reply_message'] = parsemessage(array(), $variables['org_message'], $data['pm_smileys'] == 0, false);
				$variables['org_message'] = $variables['org_message'];
				$variables['pmindex_from_id'] = $data['pmindex_from_id'];
				$variables['pmindex_to_id'] = $data['pmindex_to_id'];
			}
		}
	}

	// if the user is admin or superadmin, load the list of usergroups
	if (checkgroup($global_options['pm_send2group'])) {
		$variables['user_groups'] = getusergroups(true);
	}

	// if it's a post or a forward, prepare a list for the dropdown
	if ($action == "post" || $action == "forward") {
		$variables['user_list'] = array();
		$result = dbquery("SELECT u.user_id, u.user_name FROM ".$db_prefix."users u WHERE user_status = 0 ORDER BY user_level DESC, user_name ASC");
		while ($data = dbarray($result)) {
			// only other users (can't send a PM to yourself), skip the user_id passed to us (from a PM button p.e.)
			if ($data['user_id'] != $userdata['user_id'] && $data['user_id'] != $user_id) {
				$variables['user_list'][] = $data;
			}
		}
	}

	// attachment variable initialisation
	$variables['attachments'] = array();
	$variables['attachment_count'] = 0;
	$attach_next_ptr = 0;

	if ($settings['attachments'] && $action == "forward") {
		// get the attachments from the original message
		$result2 = dbquery("SELECT * FROM ".$db_prefix."pm_attachments WHERE pm_id='".$pm_id."' ORDER BY pmattach_id");
		$rows = dbrows($result2);
		$variables['attachment_count'] += $rows;
		while ($adata = dbarray($result2)) {
			$attach_next_ptr++;
			$adata['delete_checked'] = isset($_POST['delattach'][$attach_next_ptr]);
			$adata['new'] = false;
			$adata['index'] = $attach_next_ptr;
			$file = PATH_PM_ATTACHMENTS.$adata['pmattach_name'];
			if (file_exists($file)) {
				$adata['is_found'] = true;
				$adata['size'] = parsebytesize(filesize($file),0);
				if (in_array($adata['pmattach_ext'], $imagetypes)) {
					// check if it really is an image
					$imageinfo = @getimagesize($attachrealfile);
					if (is_array($imageinfo)) {
						$adata['is_image'] = true;
						$adata['imagesize'] = array('x' => $imageinfo[0], 'y' => $imageinfo[1]);
						// check if this image has a thumbnail
						if (file_exists($file.".thumb")) {
							$adata['thumbnail'] = PM_ATTACHMENTS.$adata['attach_name'].".thumb";
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
			$adata['attach_id'] = $adata['pmattach_id'];
			$adata['attach_comment'] = $adata['pmattach_comment'];
			$adata['attach_size'] = $adata['pmattach_size'];
			$adata['attach_count'] = 0;
			$adata['attach_name'] = $adata['pmattach_name'];
			$adata['attach_realname'] = $adata['pmattach_realname'];
			$adata['attach_ext'] = $adata['pmattach_ext'];
			$variables['attachments'][] = $adata;
		}
	}

	// process newly uploaded attachments
	foreach($attachments as $key => $attachment) {
		$attach_next_ptr++;
		$adata = array();
		$adata['new'] = true;
		$adata['is_found'] = true;
		$adata['is_image'] = false;
		$adata['size'] = parsebytesize($attachment['attach_size'],0);
		$adata['delete_checked'] = isset($_POST['delattach'][$key]);
		$adata['key'] = $key;
		$adata['attach_tmp'] = $attachment['attach_tmp'];
		$adata['type'] = $attachment['type'];
		$adata['attach_id'] = 0;
		$adata['attach_comment'] = $attachment['attach_comment'];
		$adata['attach_size'] = $attachment['attach_size'];
		$adata['attach_count'] = $attachment['attach_count'];
		$adata['attach_name'] = $attachment['attach_name'];
		$adata['attach_realname'] = $attachment['attach_name'];
		$adata['attach_ext'] = $attachment['attach_ext'];
		$variables['attachments'][] = $adata;
	}
	// update the attachment count
	$variables['attachment_count'] += count($attachments);
	// attachment upload information
	$variables['attachmax'] = parsebytesize($settings['attachmax']);
	$variables['attachtypes'] = str_replace(',', ' ', $settings['attachtypes']);

	// messsage post panel
	$variables['group_id'] = isset($_POST['group_id']) && isNum($_POST['group_id']) ? $_POST['group_id'] : 0;
	if ($variables['group_id'] == 0 && $group_id != 0) $variables['group_id'] = $group_id;
	$variables['allow_sendtoall'] = checkgroup($global_options['pm_send2group']);
	$variables['is_sendtoall'] = checkgroup($global_options['pm_send2group']) && (isset($_POST['chk_sendtoall']) && $variables['group_id']);
	$variables['action'] = $action;
	$variables['folder'] = $folder;
	$variables['msg_id'] = $msg_id;

	// panel title
	switch ($action) {
		case "forward":
			$title = $locale['436'];
			break;
		case "reply":
		case "quote":
			$title = $locale['434'];
			break;
		default:
			$title = $locale['420'];
	}
	// colors for the color dropdown
	$variables['fontcolors'] = array();
	$result = dbquery("SELECT locales_key, locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$settings['locale_code']."' AND locales_name = 'colors'");
	while ($data = dbarray($result)) {
		$variables['fontcolors'][] = array('color' => $data['locales_key'], 'name' => $data['locales_value']);
	}

	// message id, to prevend duplicate posts
	if (isset($_POST['random_id'])) {
		$variables['random_id'] = $_POST['random_id'];
	} else {
		$variables['random_id'] = md5(microtime());
	}

	// load the hoteditor if needed
	if ($settings['hoteditor_enabled'] && (!iMEMBER || $userdata['user_hoteditor'])) {
		define('LOAD_HOTEDITOR', true);
	}

	// define the panel and assign the template variables
	$template_panels[] = array('type' => 'body', 'name' => 'pm.post', 'title' => $title, 'template' => 'main.pm.post.tpl', 'locale' => array("main.pm"));
	$template_variables['pm.post'] = $variables;

} else {

	// update the totals, they might have been changed by an action
	$totals['inbox'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$userdata['user_id']."' AND pmindex_folder = '0'");
	$totals['outbox'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$userdata['user_id']."' AND pmindex_folder = '1'");
	$totals['archive'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$userdata['user_id']."' AND pmindex_folder = '2'");

	// process the folder selection
	if ($folder == $locale['425']) {

		// prepare to show the option screen
		$variables['folder'] = $folder;
		$variables['totals'] = $totals;
		// define the panel and assign the template variables
		$template_panels[] = array('type' => 'body', 'name' => 'pm.options', 'title' => $locale['400'].' - '.$locale['425'], 'template' => 'main.pm.options.tpl', 'locale' => "main.pm");
		$template_variables['pm.options'] = $variables;

	} else {

		// prepare to show the selected mailbox folder
		if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
		$title = $folder;
		// select the records for this folder's page
		if ($folder == $locale[402]) {
			// if msg_id has been given, but rowstart = 0, determine rowstart first
			if ($variables['view_id'] != 0 && $rowstart == 0) {
				$result = dbquery(
					"SELECT i.pmindex_id FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
					WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."' AND i.pmindex_folder = '0'
					ORDER BY m.pm_datestamp DESC"
				);
				$found = 0;
				while ($data = dbarray($result)) {
					if ($data['pmindex_id'] == $variables['view_id']) {
						break;
					}
					$found++;
				}
				$rowstart = intval($found / ITEMS_PER_PAGE) * ITEMS_PER_PAGE;
			}
			$result = dbquery(
				"SELECT * FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
				WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."' AND i.pmindex_folder = '0'
				ORDER BY m.pm_datestamp DESC LIMIT $rowstart,".ITEMS_PER_PAGE
			);
		} elseif ($folder == $locale[403]) {
			// if msg_id has been given, but rowstart = 0, determine rowstart first
			if ($variables['view_id'] != 0 && $rowstart == 0) {
				$result = dbquery(
					"SELECT i.pmindex_id FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
					WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."' AND i.pmindex_folder = '1'
					ORDER BY m.pm_datestamp DESC"
				);
				$found = 0;
				while ($data = dbarray($result)) {
					if ($data['pmindex_id'] == $variables['view_id']) {
						break;
					}
					$found++;
				}
				$rowstart = intval($found / ITEMS_PER_PAGE) * ITEMS_PER_PAGE;
			}
			$result = dbquery(
				"SELECT * FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
				WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."' AND i.pmindex_folder = '1'
				ORDER BY m.pm_datestamp DESC LIMIT $rowstart,".ITEMS_PER_PAGE
			);
		} elseif ($folder == $locale[404]) {
			// if msg_id has been given, but rowstart = 0, determine rowstart first
			if ($variables['view_id'] != 0 && $rowstart == 0) {
				$result = dbquery(
					"SELECT i.pmindex_id FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
					WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."' AND i.pmindex_folder = '2'
					ORDER BY m.pm_datestamp DESC"
				);
				$found = 0;
				while ($data = dbarray($result)) {
					if ($data['pmindex_id'] == $variables['view_id']) {
						break;
					}
					$found++;
				}
				$rowstart = intval($found / ITEMS_PER_PAGE) * ITEMS_PER_PAGE;
			}
			$result = dbquery(
				"SELECT * FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
				WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."' AND i.pmindex_folder = '2'
				ORDER BY m.pm_datestamp DESC LIMIT $rowstart,".ITEMS_PER_PAGE
			);
		}
		// get the messages
		$variables['messages'] = array();
		while ($data = dbarray($result)) {
			$data = gathermsginfo($data);
			if ($user_options['pmconfig_view'] == 1 || (isset($variables['view_id']) && $variables['view_id'] == $data['pmindex_id'])) {
				// check if read request checks are allowed. If not, disable it
				$read_requested = $user_options['pmconfig_read_notify'];
				// mark the message as read, and update the read request status
				$result2 = dbquery("UPDATE ".$db_prefix."pm_index SET pmindex_read_datestamp = '".time()."', pmindex_read_requested = '".$read_requested."' WHERE pmindex_id = '".$data['pmindex_id']."'");
			}
			// for messages that this user's send, get the read status
			if ($data['pmindex_from_id'] == $userdata['user_id']) {
				$readstatus = array();
				foreach($data['recipients'] as $recipient) {
					// skip the groups, we only need the user id's
					if (isset($recipient['user_id'])) {
						$result2 = dbquery("SELECT u.user_name, pmi.* FROM ".$db_prefix."pm_index pmi, ".$db_prefix."users u WHERE pmi.pmindex_user_id = u.user_id AND pm_id = '".$data['pm_id']."' AND pmindex_user_id = '".$recipient['user_id']."'");
						if ($data2 = dbarray($result2)) {
							$readstatus[] = array('user_id' => $data2['pmindex_read_requested'] ? $data2['pmindex_user_id'] : 0, 'user_name' => $data2['user_name'], 'read' => ($data2['pmindex_read_datestamp'] != 0), 'datestamp' => $data2['pmindex_read_datestamp']);
						} else {
							$readstatus[] = array('user_id' => 0, 'user_name' => "", 'read' => 0, 'datestamp' => 0);
						}
					}
				}
				$data['readstatus'] = $readstatus;
			}
			$variables['messages'][] = $data;
		}
		// main messsages panel
		$variables['folder'] = $folder;
		$variables['totals'] = $totals;
		switch ($folder) {
			case $locale['402']:
				$variables['rows'] = $totals['inbox'];
				break;
			case $locale['403']:
				$variables['rows'] = $totals['outbox'];
				break;
			case $locale['404']:
				$variables['rows'] = $totals['archive'];
				break;
		}
		$variables['rowstart'] = $rowstart;
		$variables['pagenav_url'] = FUSION_SELF."?folder=".$folder."&amp;";
		// define the panel and assign the template variables
		$template_panels[] = array('type' => 'body', 'name' => 'pm', 'title' => $locale['400'].' - '.$title, 'template' => 'main.pm.tpl', 'locale' => "main.pm");
		$template_variables['pm'] = $variables;
	}
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
