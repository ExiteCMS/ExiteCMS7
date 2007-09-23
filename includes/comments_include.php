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
if (eregi("comments_include.php", $_SERVER['PHP_SELF']) || !defined('ExiteCMS_INIT')) die();

// load the locale for this include
include PATH_LOCALE.LOCALESET."comments.php";

// function to display the comments panel
function showcomments($comment_type,$cdb,$ccol,$comment_id,$clink) {

	global $db_prefix, $settings,$locale,$userdata,$aidlink,
		$template_panels, $template_variables;

	$variables = array();
	
	if ((iMEMBER || $settings['guestposts'] == "1") && isset($_POST['post_comment'])) {
		$flood = false;
		if (dbrows(dbquery("SELECT $ccol FROM ".$db_prefix."$cdb WHERE $ccol='$comment_id'"))==0) {
			fallback(BASEDIR."index.php");
		}
		if (iMEMBER) {
			$comment_name = $userdata['user_id'];
		} elseif ($settings['guestposts'] == "1") {
			$comment_name = trim(stripinput($_POST['comment_name']));
			$comment_name = preg_replace("(^[0-9]*)", "", $comment_name);
			if (isNum($comment_name)) $comment_name="";
		}
		$comment_message = trim(stripinput(censorwords($_POST['comment_message'])));
		$comment_smileys = isset($_POST['disable_smileys']) ? "0" : "1";
		if ($comment_name != "" && $comment_message != "") {
			$result = dbquery("SELECT MAX(comment_datestamp) AS last_comment FROM ".$db_prefix."comments WHERE comment_ip='".USER_IP."'");
			if (!iSUPERADMIN || dbrows($result) > 0) {
				$data = dbarray($result);
				if ((time() - $data['last_comment']) < $settings['flood_interval']) {
					$flood = true;
					$result = dbquery("INSERT INTO ".$db_prefix."flood_control (flood_ip, flood_timestamp) VALUES ('".USER_IP."', '".time()."')");
					if (dbcount("(flood_ip)", "flood_control", "flood_ip='".USER_IP."'") > 4) {
						if (iMEMBER) $result = dbquery("UPDATE ".$db_prefix."users SET user_status='1' WHERE user_id='".$userdata['user_id']."'");
					}
				}
			}
			if (!$flood) $result = dbquery("INSERT INTO ".$db_prefix."comments (comment_item_id, comment_type, comment_name, comment_message, comment_smileys, comment_datestamp, comment_ip) VALUES ('$comment_id', '$comment_type', '$comment_name', '$comment_message', '$comment_smileys', '".time()."', '".USER_IP."')");
		}
		redirect($clink);
	}

	$result = dbquery(
		"SELECT tcm.*,user_name FROM ".$db_prefix."comments tcm
		LEFT JOIN ".$db_prefix."users tcu ON tcm.comment_name=tcu.user_id
		WHERE comment_item_id='$comment_id' AND comment_type='$comment_type'
		ORDER BY comment_datestamp ASC"
	);
	if (dbrows($result) != 0) {
		$variables['allow_post'] = checkrights("C");
		$variables['comments'] = array();
		while ($data = dbarray($result)) {
			if ($data['comment_smileys'] == "1") {
				$data['comment_message']= parsesmileys($data['comment_message']);
			}
			$data['comment_message'] = nl2br(parseubb($data['comment_message']));
			$variables['comments'][] = $data;
		}
	}
	$variables['comment_type'] = $comment_type;
	$variables['comment_id'] = $comment_id;
	$variables['post_link'] = $clink;

	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'comments_include', 'template' => 'include.comments.tpl', 'locale' => PATH_LOCALE.LOCALESET."comments.php");
	$template_variables['comments_include'] = $variables;
}
?>