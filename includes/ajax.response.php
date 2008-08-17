<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require "core_functions.php";

// get the request field, sanitize it, and check it's availability
$request = isset($_GET['request']) ? stripinput($_GET['request']) : "";
if ($request == "") {
	echo "ERROR: Missing request name on ajax call!";
	exit;
}

// get the parameters field and sanitize it
$parms = isset($_GET['parms']) ? stripinput($_GET['parms']) : "";
$parms = explode(",", $parms);

/*---------------------------------------------------+
| Local functions                                    |
+----------------------------------------------------*/
function get_smileys($folder) {

	$results = array();
	// first, check if this folder contains subfolders
	$subs = makefilelist($folder, "", true, "folders", false);
	if (count($subs)) {
		// call recursively
		foreach ($subs as $sub) {
			$subresults = get_smileys($folder.$sub."/");
			$results = array_merge($results, $subresults);
		}
	}
	// now get the files from this directory
	$files = makefilelist($folder, "", true, "files", false);
	// we only need image files
	foreach ($files as $file) {
		$results[] = str_replace("//", "/", $folder.$file);
	}
	return $results;
}

/*---------------------------------------------------+
| Main code                                          |
+----------------------------------------------------*/

// process the request
switch ($request) {
	// return the HTML needed for the smiley's block
	case "pm":
		// get the number of unread PM messages for this user
		if (!iMEMBER) {
			$msg = 0;
		} else {
			$msg = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id='".$userdata['user_id']."' AND pmindex_to_id='".$userdata['user_id']."' AND pmindex_read_datestamp = '0'");
		}
		if (empty($parms[0])) {
			// just output the number
			echo $msg;
		} else {
			// output the number as formatted text
			if ($msg == 1) {
				echo sprintf($locale['085'], $msg);
			} else {
				echo sprintf($locale['086'], $msg);
			}
		}
		break;
	case "posts":
		// get the number of unread forum posts for this user
		if (!iMEMBER) {
			$msg = 0;
		} else {
			if ($userdata['user_posts_unread']) {
				$result = dbquery("
					SELECT count(*) as unread 
						FROM ".$db_prefix."posts p 
							INNER JOIN ".$db_prefix."forums f ON p.forum_id = f.forum_id 
							INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
						WHERE ".groupaccess('f.forum_access')."
							AND tr.user_id = '".$userdata['user_id']."' 
							AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
							AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
								OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))"
					);
			} else {
				$result = dbquery("
					SELECT count(*) as unread 
						FROM ".$db_prefix."posts p 
							INNER JOIN ".$db_prefix."forums f ON p.forum_id = f.forum_id 
							INNER JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id 
						WHERE ".groupaccess('f.forum_access')."
							AND tr.user_id = '".$userdata['user_id']."' 
							AND p.post_author != '".$userdata['user_id']."'
							AND p.post_edituser != '".$userdata['user_id']."'
							AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].")
							AND ((p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)
								OR (p.post_datestamp < tr.thread_first_read OR (p.post_edittime != 0 AND p.post_edittime < tr.thread_first_read)))"
					);
			} 
			$msg = ($result ? mysql_result($result, 0) : 0);
		}
		if (empty($parms[0])) {
			// just output the number
			echo $msg;
		} else {
			// output the number as formatted text
			if ($msg == 1) {
				echo sprintf($locale['088'], $msg);
			} else {
				echo sprintf($locale['089'], $msg);
			}
		}
		break;
	case "smileys":
		if (empty($parms[0])) {
			echo "ERROR: Missing parameter on '$request' ajax call!";
		} else {
			$smiles = "";
			$smileys = get_smileys(PATH_IMAGES."smiley/");
			foreach($smileys as $key=>$smiley) {
				// make the file path relative
				$smiley = substr($smiley, strlen(PATH_IMAGES));
				$smiles .= "<img src='".IMAGES."$smiley' alt='' onclick=\"insertText('".$parms[0]."', '[img]".IMAGES.$smiley."[/img]');\" />\n";
			}
			echo $smiles;
		}
		break;	
	default:
		echo "ERROR: Unknown request type '$request' on ajax call!";
}
?>
