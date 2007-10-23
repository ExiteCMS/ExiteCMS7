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

if (!checkrights("M") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// temp storage for template variables
$variables = array();

//load the locale for this module
include PATH_LOCALE.LOCALESET."admin/members.php";
include PATH_LOCALE.LOCALESET."user_fields.php";

// load the GeoIP include module
require_once PATH_INCLUDES."geoip_include.php";

// parameter validation
if (isset($user_id) && !isNum($user_id)) fallback("index.php");
if (!isset($country) || strlen($country) != 2) $country = "";
if (!isset($sortby) || strlen($sortby) != 1) $sortby = "all";
if (!isset($step)) $step = "";
if (!isset($user_id)) $user_id= 0;

define('ITEMS_PER_PAGE', 20);

if (isset($_POST['cancel_delete'])) fallback(FUSION_SELF.$aidlink."&sortby=$sortby&rowstart=$rowstart");

if ($step == "add") {
	if (isset($_POST['add_user'])) {
		$error = "";
		
		$username = trim(eregi_replace(" +", " ", $_POST['username']));
		$fullname = $_POST['fullname'];
	
		if ($username == "" || $_POST['password1'] == "" || $_POST['email'] == "") $error .= $locale['451']."<br>\n";

//		if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['452']."<br>\n";

		if (preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['password1'])) {
			if ($_POST['password1'] != $_POST['password2']) $error .= $locale['456']."<br>\n";
		} else {
			$error .= $locale['457']."<br>\n";
		}
		if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['email'])) {
			$error .= $locale['454']."<br>\n";
		}	
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) $error = $locale['453']."<br>\n";
		
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='".$_POST['email']."'");
		if (dbrows($result) != 0) $error = $locale['455']."<br>\n";
		
		if ($error == "") {
			$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_md5id, user_fullname, user_password, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_theme, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status) VALUES ('$username', '".md5(strtolower($username.$password1))."', '$fullname', '".md5(md5('$password1'))."', '$email', '$hide_email', '', '0000-00-00', '', '', '', '', '', 'Default', '0', '', '', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '0')");
		}
		$variables['message'] = $error;
		$variables['is_added'] = true;
	} else {
		$variables['is_add'] = true;
	}
} elseif ($step == "ban") {
	if (isset($_POST['ban'])) {
		$user_ban_reason = stripinput(trim($_POST['user_ban_reason']));
		if ($user_ban_reason == "") 
			$message = $locale['463'];
		else {
			$user_ban_expire = stripinput(trim($_POST['user_ban_expire']));
			if ($user_ban_expire == "") $user_ban_expire = 0;
			if (!IsNum($user_ban_expire) || $user_ban_expire < 0)
				$message = $locale['464'];
			else {
				if ($user_ban_expire) $user_ban_expire = time() + 86400 * $user_ban_expire;
				if ($user_id != 1) {
					$result = dbquery("UPDATE ".$db_prefix."users SET user_status='1', user_ban_reason='$user_ban_reason', user_ban_expire='$user_ban_expire' WHERE user_id='$user_id'");
					$message = $locale['430'].($user_ban_expire?sprintf($locale['465'], showdate('forumdate', $user_ban_expire)):"");
				}
			}
		}
	} else {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$user_id'"));
		$variables['bantitle'] = $locale['417'].': <b>'.$data['user_name'].'</b>';
		$variables['user_ban_reason'] = $data['user_ban_reason'];
		$variables['user_ban_expire'] = "7";
	}
	$variables['message'] = isset($message) ? $message : "";

} elseif ($step == "unban") {
	$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0' WHERE user_id='$user_id'");
	$message = $locale['431'];
	$variables['message'] = isset($message) ? $message : "";
} elseif ($step == "activate") {
	$result = dbquery("SELECT user_name,user_email FROM ".$db_prefix."users WHERE user_id='$user_id'");
	if (dbrows($result) != 0) {
		$udata = dbarray($result);
		$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0' WHERE user_id='$user_id'");
		if ($settings['email_verification'] == "1") {
			require_once PATH_INCLUDES."sendmail_include.php";
			sendemail($udata['user_name'],$udata['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['435'].$settings['sitename'],str_replace("[USER_NAME]", $udata['user_name'], $locale['436']));
		}
		$message = $locale['434'];
	}
	$variables['message'] = isset($message) ? $message : "";
} elseif ($step == "delete") {
	if ($user_id != 1) {
		$result = dbquery("DELETE FROM ".$db_prefix."users WHERE user_id='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."articles WHERE article_name='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_name='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."messages WHERE message_to='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."messages WHERE message_from='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."news WHERE news_name='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_user='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."shoutbox WHERE shout_name='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_author='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE post_author='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."posts_unread WHERE user_id='$user_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE notify_user='$user_id'");
		$message = $locale['432'];
	}
	$variables['message'] = isset($message) ? $message : "";
}

// get the name of the country requested
$variables['country_name'] = GeoIP_Code2Name($country);
$variables['country'] = $country;

$rows = 0;
if (iMEMBER) {
	// create the where clause
	if ($sortby == "all") {
		if ($country == "") {
			$orderby = "";
		} else {
			$orderby = " user_cc_code = '".$country."'";
		}
	} else {
		if ($country == "") {
			$orderby = " user_name LIKE '".stripinput($sortby)."%' OR user_name LIKE '".strtolower(stripinput($sortby))."%'";
		} else {
			$orderby = " user_cc_code = '".$country."' AND (user_name LIKE '".stripinput($sortby)."%' OR user_name LIKE '".strtolower(stripinput($sortby))."%')";
		}
	}
	// get the list of members
	$variables['members'] = array();
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."users".($orderby==""?"":" WHERE").$orderby." ORDER BY user_level DESC, user_name LIMIT ".$rowstart.", ".ITEMS_PER_PAGE);
	$rows = dbrows($result);
	$variables['members'] = array();
	if ($rows != 0) {
		while ($data = dbarray($result)) {
			$cc_flag = GeoIP_Code2Flag($data['user_cc_code']);
			$cc_name = GeoIP_Code2Name($data['user_cc_code']);
			if ($settings['forum_flags'] == 0) {
				$cc_flag = "";
				if (!$cc_name) $cc_name = $locale['408'];
			} else {
				if ($cc_flag == "" || empty($data['user_ip']) || $data['user_ip'] == "X" || $data['user_ip'] == "0.0.0.0") {
					$cc_flag = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					$cc_name = $locale['408'];
				}
			}
			$data['cc_flag'] = $cc_flag;
			$data['cc_name'] = $cc_name;
			$data['user_level_name'] = getuserlevel($data['user_level']);
			// check what this user is allowed to do with this member record
			$data['can_edit'] = ($data['user_level'] != 103 || iSUPERADMIN);
			$data['can_delete'] = ($data['user_id'] != $userdata['user_id'] && ($data['user_level'] != 103 || dbcount("(*)", "users", "user_level = '103'") > 1));
			$data['can_ban'] = ($data['user_level'] != 103);
			$variables['members'][] = $data;
		}
	} else {
		$error = $locale['403'];
		if ($country == "") {
			if ($sortby != "all") $error .= $locale['471'].$sortby;
		} else {
			$error .= $locale['470'].$country_name;
			if ($sortby != "all") $error .= $locale['471'].$sortby;
		}
	}
	// starting characters to filter on. Make sure there are an even number!
	$variables['search'] = array();
	$result = dbquery("SELECT DISTINCT(UPPER(SUBSTRING(user_name,1,1))) AS letter FROM ".$db_prefix."users ORDER BY letter");
	while ($data = dbarray($result)) {
		// get rid of unwanted characters. Need to find a beter solution for this
		$variables['search'][] = str_replace(array('&', '?'), array('',''), $data['letter']);
	}
	if (count($variables['search'])%2) $variables['search'][] = "";
	$variables['sortby'] = $sortby;
	$variables['rows'] = dbcount("(*)", "users", $orderby);
	$variables['rowstart'] = $rowstart;
	$variables['items_per_page'] = ITEMS_PER_PAGE;
	$variables['pagenav_url'] = FUSION_SELF."?sortby=$sortby&amp;".($country==""?"":"country=$country&amp;");
}

$variables['step'] = $step;
$variables['user_id'] = $user_id;
$template_panels[] = array('type' => 'body', 'name' => 'admin.members', 'template' => 'admin.members.tpl', 'locale' => array(PATH_LOCALE.LOCALESET."admin/members.php", PATH_LOCALE.LOCALESET."user_fields.php"));
$template_variables['admin.members'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>