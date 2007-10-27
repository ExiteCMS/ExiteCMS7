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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_INCLUDES."theme_functions.php";

// temp storage for template variables
$variables = array();

// load the DNS functions include
include PATH_INCLUDES."dns_functions.php";

// load the locates for this module
include PATH_LOCALE.LOCALESET."members-profile.php";
include PATH_LOCALE.LOCALESET."user_fields.php";

// admin function check
if (isset($user_id)) {
	if (!isNum($user_id) || !checkrights("M") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");
	// if checked out, swap userdata
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id = '".$user_id."'");
	if (dbrows($result)) $this_userdata = dbarray($result);
	$variables['is_admin'] = true;
} else {
	$variables['is_admin'] = false;
	$this_userdata = $userdata;
}

if (isset($status) && $status == 1) {
	$variables['update_profile'] = true;
}

if (isset($_POST['update_profile'])) {
	$error = ""; $set_avatar = "";
	$variables['update_profile'] = true;

	$result = dbquery("SELECT locale_code, locale_name FROM ".$db_prefix."locale WHERE locale_id = '".$_POST['user_locale']."'");
	if ($data = dbarray($result)) {
		if ($data['locale_name'] != $settings['locale']) {
			die('setting locale cookie: '.$data['locale_code']);
			setcookie("locale", $data['locale_code'], time() + 31536000, "/", "", "0");
		}
	}
	$username = trim(eregi_replace(" +", " ", $_POST['user_name']));
	if ($username == "" || $_POST['user_email'] == "" || $_POST['user_fullname'] == "" ) {
		$error .= $locale['480']."<br>\n";
	} else {
	//	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['481']."<br>\n";
		
		if ($username != $this_userdata['user_name']) {
			$result = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_name='$username'");
			if (dbrows($result) != 0) $error = $locale['482']."<br>\n";
		}
		
		if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['user_email'])) $error .= $locale['483']."<br>\n";
		
		if ($_POST['user_email'] != $this_userdata['user_email']) {
			$result = dbquery("SELECT user_email FROM ".$db_prefix."users WHERE user_email='".$_POST['user_email']."'");
			if (dbrows($result) != 0) {
				$error = $locale['484']."<br>\n";
			} else {
				$email = $_POST['user_email'];
				$email_domain = substr(strrchr($email, "@"), 1);
				if (CMS_getmxrr($email_domain, $mxhosts)) {
					// Get the hostname the MX record points to
					$mailhost = $mxhosts[0];
				} else {
					// No MX record for this domain. Might be a hostname that accepts email
					$mailhost = $email_domain;
				}
				$mailhost_ip = gethostbyname($mailhost);
				if ($mailhost != $mailhost_ip) {
					// found the mailserver for this email address. Check if the address exists
					require_once PATH_INCLUDES.'smtp_include.php';
					$mail = new SMTP();
					if (!$mail->Connect($mailhost_ip)) {
						// mail server doesn't respond
						$error .= sprintf($locale['489'], $email_domain)."<br><br>\n";
					} else {
						if (!$mail->Hello(substr(strrchr($settings['siteemail'], "@"), 1))) {
							// mail server doesn't respond to HELLO message
							$error .= sprintf($locale['489'], $email_domain)."<br><br>\n";
						} else {
							if (!$mail->Mail('address-validation'.strrchr($settings['siteemail'], "@"))) {
								// mail server doesn't respond to MAIL FROM message
								$error .= sprintf($locale['489'], $email_domain)."<br><br>\n";
							} else {
								if (!$mail->Recipient($email)) {
									// mail server doesn't respond to RCPT TO message
									$error .= sprintf($locale['490'], $email, $mailhost)."<br><br>\n";
								} else {
									// email address is accepted
								}
							}
						}
						$mail->Quit();
					}
				} else {
					$error .= sprintf($locale['488'], $email_domain)."<br><br>\n";
				}
			}
		}
	}
	
	if ($_POST['user_newpassword'] != "") {
		if ($_POST['user_newpassword2'] != $_POST['user_newpassword']) {
			$error .= $locale['485']."<br>";
		} else {
			if ($_POST['user_hash'] == $this_userdata['user_password']) {
				if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['user_newpassword'])) {
					$error .= $locale['486']."<br>\n";
				}
			} else {			
				$error .= $locale['487']."<br>\n";
			}
		}
	}
	
	$user_fullname = $_POST['user_fullname'];
	$user_hide_email = isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
	$user_location = isset($_POST['user_location']) ? stripinput(trim($_POST['user_location'])) : "";
	if ($_POST['user_month'] != "--" && $_POST['user_day'] != "--" && $_POST['user_year'] != "----") {
		$user_birthdate = (isNum($_POST['user_year']) ? $_POST['user_year'] : "0000")
		."-".(isNum($_POST['user_month']) ? $_POST['user_month'] : "00")
		."-".(isNum($_POST['user_day']) ? $_POST['user_day'] : "00");
	} else {
		$user_birthdate = "0000-00-00";
	}
	$user_aim = isset($_POST['user_aim']) ? stripinput(trim($_POST['user_aim'])) : "";
	$user_icq = isset($_POST['user_icq']) ? stripinput(trim($_POST['user_icq'])) : "";
	$user_msn = isset($_POST['user_msn']) ? stripinput(trim($_POST['user_msn'])) : "";
	$user_yahoo = isset($_POST['user_yahoo']) ? stripinput(trim($_POST['user_yahoo'])) : "";
	$user_web = isset($_POST['user_web']) ? stripinput(trim($_POST['user_web'])) : "";
	$user_newsletters = isNum($_POST['user_newsletters']) ? $_POST['user_newsletters'] : "1";
	$user_forum_fullscreen = isNum($_POST['user_forum_fullscreen']) ? $_POST['user_forum_fullscreen'] : "0";
	$user_theme = stripinput($_POST['user_theme']);
	$user_offset = isset($_POST['user_offset']) ? $_POST['user_offset'] : "+0";
	$user_sig = isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "";
	
	if ($error == "") {
		$newavatar = isset($_FILES['user_avatar']) ? $_FILES['user_avatar'] : array('name' => "");
		if ($this_userdata['user_avatar'] == "" && !empty($newavatar['name']) && is_uploaded_file($newavatar['tmp_name'])) {
			$avatarext = strrchr($newavatar['name'],".");
			while ($avatarname = md5(time())) {
				if (!file_exists(PATH_IMAGES."avatars/".$avatarname.$avatarext)) {
					break;
				}
			};
			if (preg_match("/(\.gif|\.GIF|\.jpg|\.JPG|\.png|\.PNG)$/", $avatarext) && $newavatar['size'] <= 30720) {
				$avatarname .= $avatarext;
				$set_avatar = "user_avatar='$avatarname', ";
				move_uploaded_file($newavatar['tmp_name'], PATH_IMAGES."avatars/".$avatarname);
				chmod(PATH_IMAGES."avatars/".$avatarname,0644);
				if ($size = @getimagesize(PATH_IMAGES."avatars/".$avatarname)) {
					if ($size['0'] > 100 || $size['1'] > 100) {
						unlink(PATH_IMAGES."avatars/".$avatarname);
						$set_avatar = "";
					} elseif (!verify_image(PATH_IMAGES."avatars/".$avatarname)) {
						unlink(PATH_IMAGES."avatars/".$avatarname);
						$set_avatar = "";
					}
				} else {
					unlink(PATH_IMAGES."avatars/".$avatarname);
					$set_avatar = "";
				}
			}
		}
		
		if (isset($_POST['del_avatar'])) {
			$set_avatar = "user_avatar='', ";
			// check if it exists before removing it
			if (file_exists(PATH_IMAGES."avatars/".$this_userdata['user_avatar'])) {
				unlink(PATH_IMAGES."avatars/".$this_userdata['user_avatar']);
			}
		}
		if ($user_newpassword != "") { $newpass = " user_password=md5(md5('$user_newpassword')), "; } else { $newpass = " "; }
		$result = dbquery("UPDATE ".$db_prefix."users SET user_name='$username', user_fullname='$user_fullname', ".$newpass."user_email='".$_POST['user_email']."', user_hide_email='$user_hide_email', user_location='$user_location', user_birthdate='$user_birthdate', user_aim='$user_aim', user_icq='$user_icq', user_msn='$user_msn', user_yahoo='$user_yahoo', user_web='$user_web', user_forum_fullscreen='$user_forum_fullscreen', user_newsletters='$user_newsletters', user_theme='$user_theme', user_offset='$user_offset', ".$set_avatar."user_sig='$user_sig' WHERE user_id='".$this_userdata['user_id']."'");
		if ($user_theme != $userdata['user_theme']) redirect(FUSION_SELF."?status=1");
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$this_userdata['user_id']."'");
		if (dbrows($result) != 0) {
			$this_userdata = dbarray($result);
			// recalculate the user_md5id
			$this_userdata['user_md5id'] = md5(strtolower($this_userdata['user_name'].$this_userdata['user_password']));
			$result = dbquery("UPDATE ".$db_prefix."users SET user_md5id='".$this_userdata['user_md5id']."' WHERE user_id='".$this_userdata['user_id']."'");
			if ($variables['is_admin']) {
				redirect(ADMIN."members.php".$aidlink."&amp;update_profile=ok");
			}
		}
	} else {
		$variables['error'] = $error;
	}
}

if (iMEMBER) {
	if ($this_userdata['user_birthdate']!="0000-00-00") {
		$user_birthdate = explode("-", $this_userdata['user_birthdate']);
		$variables['user_day'] = number_format($user_birthdate['2']);
		$variables['user_month'] = number_format($user_birthdate['1']);
		$variables['user_year'] = $user_birthdate['0'];
	} else {
		$variables['user_day'] = 0; $variables['user_month'] = 0; $variables['user_year'] = 0;
	}
	if (!isset($this_userdata['user_fullname']) or empty($this_userdata['user_fullname'])) {
	    $this_userdata['user_fullname'] = $this_userdata['user_name'];
	}
	// generate a list of available themes
	$theme_files = makefilelist(PATH_THEMES, ".|..|.svn", true, "folders", $this_userdata['user_level'] >= 102);
	array_unshift($theme_files, "Default");
 	$variables['theme_files'] = $theme_files;
 
	// check if the user's avatar exists
	if (!file_exists(PATH_IMAGES_AV.$this_userdata['user_avatar'])) $this_userdata['user_avatar'] = "imagenotfound.jpg";
	$variables['avatar'] = array('size' => parsebytesize(30720), 'x' => 100, 'y' => 100);
	$variables['timezone'] = sprintf($locale['u023'], "GMT ".(date('O')=="+0000"?"":date('O')));
	$variables['serveroffset'] = substr(date('O'),0,1).(substr(date('O'),1)/100);
}

$variables['this_userdata'] = $this_userdata;

$variables['locales'] = array();
$result = dbquery("SELECT locale_id, locale_name FROM ".$db_prefix."locale WHERE locale_active = '1' ORDER BY locale_name");
while ($data = dbarray($result)) {
	$data['selected'] = $data['locale_name'] == $settings['locale'];
	$variables['locales'][] = $data;
}

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'edit_profile', 'template' => 'main.edit_profile.tpl', 'locale' => array(PATH_LOCALE.LOCALESET."members-profile.php", PATH_LOCALE.LOCALESET."user_fields.php"));
$template_variables['edit_profile'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>