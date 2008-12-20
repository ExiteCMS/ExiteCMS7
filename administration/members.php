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

if (!checkrights("M") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// temp storage for template variables
$variables = array();

//load the locale for this module
locale_load("admin.members");
locale_load("main.user_fields");

// load the GeoIP include module
require_once PATH_INCLUDES."geoip_include.php";

// parameter validation
if (isset($user_id) && !isNum($user_id)) fallback("index.php");
if (!isset($step)) $step = "";
if (!isset($user_id)) $user_id= 0;
if (!isset($country) || strlen($country) != 2) $country = "";
if (!isset($sortby) || strlen($sortby) != 1) $sortby = "all";
if (!isset($order)) $order = "username";
if (!isset($field)) $field = "username";

if (isset($_POST['cancel_delete'])) fallback(FUSION_SELF.$aidlink."&order=$order&sortby=$sortby&field=$field&rowstart=$rowstart");

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
		if (dbrows($result) != 0) $error = sprintf($locale['453'],(isset($_POST['user_name']) ? $_POST['user_name'] : ""))."<br>\n";
		
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='".$_POST['email']."'");
		if (dbrows($result) != 0) $error = sprintf($locale['455'],(isset($_POST['user_email']) ? $_POST['user_email'] : ""))."<br>\n";
		
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
	$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0', user_bad_email='0' WHERE user_id='$user_id'");
	$message = $locale['431'];
	$variables['message'] = isset($message) ? $message : "";
} elseif ($step == "activate") {
	$result = dbquery("SELECT user_name,user_email FROM ".$db_prefix."users WHERE user_id='$user_id'");
	if (dbrows($result) != 0) {
		$udata = dbarray($result);
		$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0' WHERE user_id='$user_id'");
		if ($settings['email_verification'] == "1") {
			require_once PATH_INCLUDES."sendmail_include.php";
			sendemail($udata['user_name'],$udata['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['435'].$settings['sitename'],sprintf($locale['436'], $udata['user_name'], $settings['sitename'], $settings['siteusername']));
		}
		$message = $locale['434'];
	}
	$variables['message'] = isset($message) ? $message : "";
} elseif ($step == "delete") {
	if ($user_id != 1) {
		// did this user do anything on this website?
		$refs  = dbcount("(*)", "articles", "article_name='$user_id'");
		$refs += dbcount("(*)", "news", "news_name='$user_id'");
		$refs += dbcount("(*)", "comments", "comment_name='$user_id'");
		$refs += dbcount("(*)", "ratings", "rating_user='$user_id'");
		$refs += dbcount("(*)", "posts", "post_author='$user_id'");
		$refs += dbcount("(*)", "posts", "post_edituser='$user_id'");
		$refs += dbcount("(*)", "pm_index", "pmindex_user_id='$user_id'");
		$refs += dbcount("(*)", "pm_index", "pmindex_reply_id='$user_id'");
		$refs += dbcount("(*)", "pm_index", "pmindex_from_id='$user_id'");
		if ($refs) {
			// if so, mark this user as deleted, but don't delete anything
			$result = dbquery("UPDATE ".$db_prefix."users SET user_status = '3' WHERE user_id='$user_id'");
		} else {
			// it's save to delete this user
			$result = dbquery("DELETE FROM ".$db_prefix."users WHERE user_id='$user_id'");
		}
		$message = $locale['432'];
	}
	$variables['message'] = isset($message) ? $message : "";
} elseif ($step == "undelete") {
	$result = dbquery("UPDATE ".$db_prefix."users SET user_status = '0' WHERE user_id='$user_id'");
}

// get the name of the country requested
$variables['country_name'] = GeoIP_Code2Name($country);
$variables['country'] = $country;

$rows = 0;
if (iMEMBER) {
	// create the letter filter SQL clause and the selection sort SQL clause
	switch($order) {
		case "country":
			$sortfield = "user_cc_code ASC, user_level DESC, user_name ASC";
			break;
		case "email":
			$sortfield = "user_email ASC, user_level DESC";
			break;
		case "username":
		default:
			$sortfield = "user_level DESC, user_name ASC";
			break;
	}
	// create the query filter SQL clause
	$where = "";
	switch($field) {
		case "country":
			$letterfilter = "DISTINCT(UPPER(SUBSTRING(user_cc_code,1,1)))";
			break;
		case "email":
			$letterfilter = "DISTINCT(UPPER(SUBSTRING(user_email,1,1)))";
			if ($sortby != "all") {
				$where = "(user_email LIKE '".stripinput($sortby)."%' OR user_email LIKE '".strtolower(stripinput($sortby))."%')";
			}
			break;
		case "username":
		default:
			$letterfilter = "DISTINCT(UPPER(SUBSTRING(user_name,1,1)))";
			if ($sortby != "all") {
				$where = "(user_name LIKE '".stripinput($sortby)."%' OR user_name LIKE '".strtolower(stripinput($sortby))."%')";
			}
			break;
	}
	// add the country filter if requested
	$where .= $country == "" ? "" : (($where == "" ? "" : " AND ").("user_cc_code = '$country'"));


	// get the list of members
	$variables['members'] = array();
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."users".($where == ""?"":(" WHERE ".$where))." ORDER BY ".$sortfield." LIMIT ".$rowstart.", ".$settings['numofthreads']);
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
				if ($cc_flag == "" || $cc_name == "" || empty($data['user_ip']) || $data['user_ip'] == "X" || $data['user_ip'] == "0.0.0.0") {
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
	$result = dbquery("SELECT ".$letterfilter." AS letter FROM ".$db_prefix."users".($where == ""?"":(" WHERE ".$where))." ORDER BY letter");
	while ($data = dbarray($result)) {
		// get rid of unwanted characters. Need to find a beter solution for this
		$variables['search'][] = str_replace(array('&', '?'), array('',''), $data['letter']);
	}
	if (count($variables['search']) > 1 && count($variables['search'])%2) $variables['search'][] = "";
	$variables['sortby'] = $sortby;
	$variables['rows'] = dbcount("(*)", "users", $where);
	$variables['rowstart'] = $rowstart;
	$variables['items_per_page'] = $settings['numofthreads'];
	$variables['pagenav_url'] = FUSION_SELF.$aidlink."&amp;sortby=$sortby&amp;field=$field&amp;order=$order&amp;".($country==""?"":"country=$country&amp;");
}

$variables['step'] = $step;
$variables['user_id'] = $user_id;
$variables['field'] = $field;
$variables['order'] = $order;
$template_panels[] = array('type' => 'body', 'name' => 'admin.members', 'template' => 'admin.members.tpl', 'locale' => array("admin.members", "main.user_fields"));
$template_variables['admin.members'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
