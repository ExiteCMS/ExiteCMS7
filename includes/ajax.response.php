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
require "core_functions.php";

// make sure the page isn't cached
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");

// get the request field, sanitize it, and check it's availability
$request = isset($_GET['request']) ? strtolower(stripinput($_GET['request'])) : "";
if ($request == "") {
	echo "ERROR: Missing request name on ajax call!";
	exit;
}

// get the parameters field and sanitize it
$parms = isset($_GET['parms']) ? stripinput($_GET['parms']) : "";
$parms = explode(",", $parms);

// do we need a cleanup when we're finished?
$cleanup = false;

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
	case "saveconfig":
		// get the config json structure
		$config = isset($_POST['config']) && !empty($_POST['config']) ? stripinput($_POST['config']) : false;
		if (!$config) {
			header("HTTP/1.1 404 NOT FOUND");
			header("Status: 404 NOT FOUND");
			exit;
		}
		$cleanup = true;
		// make sure we have json_encode and json_decode available
		require_once "json_include.php";
		if (iMEMBER) {
			// use the user record datastore
			$userdata['user_datastore']['clientside'] = json_decode($config);
		} else {
			// store in the current session record
			$_SESSION['clientside'] = json_decode($config);
		}
		break;
	case "restoreconfig":
		// make sure we have json_encode and json_decode available
		require_once "json_include.php";
		header("Content-Type:application/json; charset=utf-8");
		if (iMEMBER) {
			// return the user record datastore
			if (!empty($userdata['user_datastore']['clientside'])) {
				echo json_encode($userdata['user_datastore']['clientside']);
			} else {
				echo json_encode(array());
			}
		} else {
			// return the user record datastore
			if (!empty($_SESSION['clientside'])) {
				echo json_encode($_SESSION['clientside']);
			} else {
				echo json_encode(array());
			}
		}
		break;
	case "counters":
		if (!iMEMBER) {
			$pms = $posts = 0;
			$pmtext = $posttext = "";
		} else {
			$pms = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id='".$userdata['user_id']."' AND pmindex_to_id='".$userdata['user_id']."' AND pmindex_read_datestamp = '0'");
			if ($pms == 1) {
				$pmtext = sprintf($locale['085'], $pms);
			} else {
				$pmtext = sprintf($locale['086'], $pms);
			}
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
			$rows = mysqli_fetch_array($result);
			$posts = $rows[0];
			if ($posts == 1) {
				$posttext = sprintf($locale['088'], $posts);
			} else {
				$posttext = sprintf($locale['089'], $posts);
			}
		}
		// make sure we have json_encode and json_decode available
		require_once "json_include.php";
		// send the results back
		header("Content-Type:application/json; charset=utf-8");
		echo json_encode(array('pmcount' => $pms, 'pmtext' => $pmtext, 'postcount' => $posts, 'posttext' => $posttext));
		break;
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
			$rows = mysqli_fetch_array($result);
			$msg = $rows[0];
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
		// return the HTML needed for the smiley's block
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

// cleanup needed?
if ($cleanup) {

	// update the user's datastore
	if (iMEMBER) {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_datastore = '".mysqli_real_escape_string($_db_link, serialize($userdata['user_datastore']))."' WHERE user_id = '".$userdata['user_id']."'");
	}

	// delete all used flash info from the session
	if (isset($_SESSION['_flash'])) {
		foreach($_SESSION['_flash'] as $key => $value) {
			if ($_SESSION['_flash'][$key]['used']) {
				unset($_SESSION['_flash'][$key]);
			}
		}
	}
	// flush any session info
	session_clean_close();

	// close the database connections
	isset($_db_link) and mysqli_close($_db_link);
	isset($_user_db_link) and mysqli_close($_user_db_link);
}
