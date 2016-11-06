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
if (eregi("pm_functions_include.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// variable initialisation

if (!isset($action)) $action = "";
$random_id = md5(microtime());
$attachments = array();

// get the global message options
$result = dbquery("SELECT * FROM ".$db_prefix."pm_config WHERE user_id='0'");
if (dbrows($result) == 0) {
	// if they don't exist, set a default
	$result = dbquery("INSERT INTO ".$db_prefix."pm_config (user_id, pmconfig_save_sent, pmconfig_read_notify, pmconfig_email_notify, pmconfig_auto_archive, pmconfig_view ) VALUES ('0', '0', '1', '0', '90', '0')");
	$result = dbquery("SELECT * FROM ".$db_prefix."pm_config WHERE user_id='0'");
}
$global_options = dbarray($result);
$global_options['pm_inbox'] = $settings['pm_inbox'];
$global_options['pm_sentbox'] = $settings['pm_sentbox'];
$global_options['pm_savebox'] = $settings['pm_savebox'];
$global_options['pm_inbox_group'] = ($settings['pm_inbox_group'] && checkgroup($settings['pm_inbox_group']));
$global_options['pm_sentbox_group'] = ($settings['pm_sentbox_group'] && checkgroup($settings['pm_sentbox_group']));
$global_options['pm_savebox_group'] = ($settings['pm_savebox_group'] && checkgroup($settings['pm_savebox_group']));
$global_options['pm_send2group'] = $settings['pm_send2group'];
$global_options['pm_hide_rcpts'] = $settings['pm_hide_rcpts'];
$variables['global_options'] = $global_options;

// define the mailbox totals array
$totals = array('inbox' => 0, 'outbox' => 0, 'archive' => 0);

/*---------------------------------------------------+
| Delete a single message, identified by pmindex_id  |
+----------------------------------------------------*/
function deletemessage($msg_id, $user_id) {

	global $db_prefix;

	// delete the selected message
	$result = dbquery("SELECT * FROM ".$db_prefix."pm_index WHERE pmindex_id = '".$msg_id."' AND pmindex_user_id='".$user_id."'");
	if ($data = dbarray($result)) {
		$result2 = dbquery("DELETE FROM ".$db_prefix."pm_index WHERE pmindex_id='".$msg_id."' AND pmindex_user_id='".$user_id."'");
		if (dbcount("(*)", "pm_index", "pm_id = '".$data['pm_id']."'") == 0) {
			$result2 = dbquery("SELECT * FROM ".$db_prefix."pm_attachments WHERE pm_id = '".$data['pm_id']."'");
			while ($data2 = dbarray($result2)) {
				@unlink(PATH_PM_ATTACHMENTS.$data2['pmattach_name']);
				// if a thumb exists, delete that too...
				if (file_exists(PATH_PM_ATTACHMENTS.$data2['pmattach_name'].".thumb")) {
				    @unlink(PATH_PM_ATTACHMENTS.$data2['pmattach_name'].".thumb");
				}
			}
			$result2 = dbquery("DELETE FROM ".$db_prefix."pm_attachments WHERE pm_id = '".$data['pm_id']."'");
			$result2 = dbquery("DELETE FROM ".$db_prefix."pm WHERE pm_id = '".$data['pm_id']."'");
		}
	}
}

/*---------------------------------------------------+
| Save the new message, and send notifications out   |
+----------------------------------------------------*/
function storemessage($message, $old_pm_id, $from_cms = false) {

	global $db_prefix, $_db_link, $settings, $userdata, $locale, $action, $attachments, $global_options, $totals;

	// check for double posting, generate an error if it is
	$random_id = isset($_POST['random_id']) ? $_POST['random_id'] : false;
	if ($random_id === false || isset($_SESSION['pm'][$random_id])) {
		return $locale['641'];
	}

	// add this post to pm message tracker
	if (!isset($_SESSION['pm']) || !is_array($_SESSION['pm'])) $_SESSION['pm'] = array();
	$_SESSION['pm'][$random_id] = time()+60*60*12;

	// check if we need to make room in the outbox of the sender
	if (!$global_options['pm_sentbox_group']) {
		if ($totals['outbox'] >= $global_options['pm_sentbox']) {
			$limit = $totals['outbox'] - $global_options['pm_sentbox'] + 1;
			$result = dbquery(
				"SELECT * FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
				WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."' AND i.pmindex_folder = '1'
				ORDER BY m.pm_datestamp LIMIT ".$limit
				);
			while ($data = dbarray($result)) {
				deletemessage($data['pmindex_id'], $userdata['user_id']);
			}
		}
	}

	// create the recipients list for this message
	$recipients = "";
	foreach($message['recipients'] as $recipient) {
		$recipients .= ($recipients == "" ? "" : "," ) . $recipient;
	}

	// store the new message
	$result = dbquery("INSERT INTO ".$db_prefix."pm (pm_subject, pm_message, pm_recipients, pm_smileys, pm_size, pm_datestamp)
		VALUES ('".mysqli_real_escape_string($_db_link, $message['pm_subject'])."', '".mysqli_real_escape_string($_db_link, $message['pm_message'])."', '".$recipients."', '".$message['pm_smileys']."', '".$message['pm_size']."', '".$message['pm_datestamp']."')");
	$pm_id = mysqli_insert_id($_db_link);

	// process the attachments, handle deletes first
	if (isset($_POST['delattach']) && count($_POST['delattach']) != 0) {
		foreach($_POST['delattach'] as $key => $value) {
			// check to make sure it is a new upload
			if ($value[0] == "-") {
				$attach = $attachments[substr($value,1)];
				// delete the attachment
				@unlink(PATH_PM_ATTACHMENTS.$attach['attach_tmp']);
				// if a thumb exists, delete that too...
				if (file_exists(PATH_PM_ATTACHMENTS.$attach['attach_tmp'].".thumb")) {
				    @unlink(PATH_PM_ATTACHMENTS.$attach['attach_tmp'].".thumb");
				}
				// and remove the upload from the attachment array
				unset($attachments[substr($value,1)]);
			}
		}
	}
	// now save any remaining attachments uploads
	foreach($attachments as $key => $attachment) {
		$attachext = strtolower(strrchr($attachment['attach_name'],"."));
		$attachname = attach_exists(strtolower($attachment['attach_name']), PATH_PM_ATTACHMENTS);
		if (file_exists(PATH_PM_ATTACHMENTS.$attachment['attach_tmp'].".thumb")) {
			rename(PATH_PM_ATTACHMENTS.$attachment['attach_tmp'].".thumb", PATH_PM_ATTACHMENTS.$attachname.".thumb");
			chmod(PATH_PM_ATTACHMENTS.$attachname.".thumb",0664);
		}
		rename(PATH_PM_ATTACHMENTS.$attachment['attach_tmp'], PATH_PM_ATTACHMENTS.$attachname);
		chmod(PATH_PM_ATTACHMENTS.$attachname,0664);
		$result = dbquery("INSERT INTO ".$db_prefix."pm_attachments (pm_id, pmattach_name, pmattach_realname, pmattach_comment, pmattach_ext, pmattach_size) VALUES ('$pm_id', '$attachname', '".$attachment['attach_name']."', '".mysqli_real_escape_string($_db_link, $attachment['attach_comment'])."', '$attachext', '".$attachment['attach_size']."')");
	}

	// copy original (and not excluded) attachments when forwarding a message
	if ($action == "forward" && $old_pm_id) {
		$result = dbquery("SELECT * FROM ".$db_prefix."pm_attachments WHERE pm_id='$old_pm_id'");
		while ($data = dbarray($result)) {
			// check if this attachment is not excluded
			if (!in_array($data['pmattach_id'], $_POST['delattach'])) {
				// make a copy of the attachment
				$attachname = attach_exists(strtolower($data['pmattach_realname']), PATH_PM_ATTACHMENTS);
				if (file_exists(PATH_PM_ATTACHMENTS.$data['pmattach_name'].".thumb")) {
					copy(PATH_PM_ATTACHMENTS.$data['pmattach_name'].".thumb", PATH_PM_ATTACHMENTS.$attachname.".thumb");
					chmod(PATH_PM_ATTACHMENTS.$attachname.".thumb",0664);
				}
				copy(PATH_PM_ATTACHMENTS.$data['pmattach_name'], PATH_PM_ATTACHMENTS.$attachname);
				chmod(PATH_PM_ATTACHMENTS.$attachname,0664);
				// and create a new attachment record
				$result2 = dbquery("INSERT INTO ".$db_prefix."pm_attachments (pm_id, pmattach_name, pmattach_realname, pmattach_comment, pmattach_ext, pmattach_size) VALUES ('$pm_id', '$attachname', '".$data['pmattach_realname']."', '".mysqli_real_escape_string($_db_link, $data['pmattach_comment'])."', '".$data['pmattach_ext']."', '".$data['pmattach_size']."')");
			}
		}
	}

	// create an index record for the outbox of the sender
	if (!$from_cms) {
		$result = dbquery("INSERT INTO ".$db_prefix."pm_index (pm_id, pmindex_user_id, pmindex_reply_id, pmindex_from_id, pmindex_from_email, pmindex_to_id, pmindex_to_email, pmindex_to_group, pmindex_folder, pmindex_read_datestamp)
			 VALUES ('".$pm_id."', '".$userdata['user_id']."', '0', '".$userdata['user_id']."', '', '0', '', '0', '1', '".time()."')");
	}

	// load the sendmail module, we might have to send notifications
	require_once PATH_INCLUDES."sendmail_include.php";

	// loop through the users
	$error = "";
	foreach($message['user_ids'] as $user) {
		// check if this recipient has room in his inbox. If not, create it
		if (!$global_options['pm_inbox_group']) {
			$inbox_total = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id = '".$user['user_id']."' AND pmindex_folder = '0'");
			if ($inbox_total >= $global_options['pm_inbox']) {
				$limit = $inbox_total - $global_options['pm_inbox'] + 1;
				$result = dbquery(
					"SELECT * FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
					WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$user['user_id']."' AND i.pmindex_folder = '0'
					ORDER BY m.pm_datestamp LIMIT ".$limit
					);
				while ($data = dbarray($result)) {
					deletemessage($data['pmindex_id'], $user['user_id']);
				}
			}
		}
		// create an index record for the inbox of the recipient
		$result = dbquery("INSERT INTO ".$db_prefix."pm_index (pm_id, pmindex_user_id, pmindex_reply_id, pmindex_from_id, pmindex_from_email, pmindex_to_id, pmindex_to_email, pmindex_to_group, pmindex_folder, pmindex_read_requested)
			 VALUES ('".$pm_id."', '".$user['user_id']."', '0', '".($from_cms?0:$userdata['user_id'])."', '', '".$user['user_id']."', '', '0', '0', '1')");
		// user notification if needed
		if ($user['pmconfig_email_notify']) {
			// make sure we have a user locale
			if (empty($user['user_locale'])) {
				$user['user_locale'] = $settings['locale_code'];
			}
			// get the message strings for this user using the users own locale. If not found, use the current users locale strings (might be wrong!)
			$message_subject = dbarray(dbquery("SELECT locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$user['user_locale']."' AND locales_name = 'main.pm' and locales_key = '625'"));
			$message_subject = isset($message_subject['locales_value']) ? $message_subject['locales_value'] : $locale['625'];
			$message_content = dbarray(dbquery("SELECT locales_value FROM ".$db_prefix."locales WHERE locales_code = '".$user['user_locale']."' AND locales_name = 'main.pm' and locales_key = '626'"));
			$message_content = isset($message_content['locales_value']) ? $message_content['locales_value'] : $locale['626'];

			// send the notification email out
			$error = sendemail($user['user_name'], $user['user_email'], $settings['siteusername'],
						($settings['newsletter_email'] != "" ? $settings['newsletter_email'] : $settings['siteemail']),
						sprintf($message_subject,$settings['sitename']),
						$user['user_name'].sprintf($message_content, ($from_cms?$locale['sysusr']:$userdata['user_name']), $settings['sitename'], $message['pm_subject'], $settings['siteurl']));
		}
	}
	return $error == true ? "" : $error;
}
?>
