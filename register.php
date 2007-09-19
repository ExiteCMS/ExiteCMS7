<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| Some portions copyright ? 2002 - 2006 Nick Jones   |
| http://www.php-fusion.co.uk/                       |
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// temp storage for template variables
$variables = array();

// no point registering when you're already a member
if (iMEMBER) fallback(BASEDIR."index.php");

// load the locales for this module
include PATH_LOCALE.LOCALESET."register.php";
include PATH_LOCALE.LOCALESET."user_fields.php";

// check whether we allow registrations
if ($settings['enable_registration'] == 0) {

	// new user activation
	if (isset($activate)) {
		if (!preg_match("/^[0-9a-z]{32}$/", $activate)) fallback("index.php");
		$result = dbquery("SELECT * FROM ".$db_prefix."new_users WHERE user_code='$activate'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$user_info = unserialize($data['user_info']);
			$activation = $settings['admin_activation'] == "1" ? "2" : "0";
			$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_fullname, user_password, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_theme, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status, user_newsletters) VALUES('".$user_info['user_name']."', '".$user_info['user_fullname']."', '".md5($user_info['user_password'])."', '".$user_info['user_email']."', '".$user_info['user_hide_email']."', '', '0000-00-00', '', '', '', '', '', 'Default', '".$user_info['user_offset']."', '', '', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '$activation', '1')");
			$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_code='$activate'");	
			if ($settings['admin_activation'] == "1") {
				$variables['message'] = $locale['453'];
			} else {
				$variables['message'] = $locale['452'];
			}
			// define the body panel variables
			$template_panels[] = array('type' => 'body', 'name' => 'register.activate', 'template' => 'main.register.activate.tpl', 'locale' => array(PATH_LOCALE.LOCALESET."register.php", PATH_LOCALE.LOCALESET."user_fields.php"));
			$template_variables['register.activate'] = $variables;
		} else {
			fallback(BASEDIR."index.php");
		}

	// process the new registration
	} else if (isset($_POST['register'])) {
		$error = "";
		$username = stripinput(trim(eregi_replace(" +", " ", $_POST['username'])));
		$fullname = $_POST['fullname'];
		$email = stripinput(trim(eregi_replace(" +", "", $_POST['email'])));
		$password1 = stripinput(trim(eregi_replace(" +", "", $_POST['password1'])));
		
		if ($username == "" || $password1 == "" || $email == "" || $fullname == "") $error .= $locale['402']."<br /><br />\n";
		
	//	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['403']."<br /><br />\n";
		
		if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
			if ($password1 != $_POST['password2']) $error .= $locale['404']."<br /><br />\n";
		} else {
			$error .= $locale['405']."<br /><br />\n";
		}
	 
		if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
			$error .= $locale['406']."<br /><br />\n";
		}
		
		$email_domain = substr(strrchr($email, "@"), 1);
		if (getmxrr($email_domain, $mxhosts)) {
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
				$error .= sprintf($locale['413'], $email_domain)."<br /><br />\n";
			} else {
				if (!$mail->Hello(substr(strrchr($settings['siteemail'], "@"), 1))) {
					// mail server doesn't respond to HELLO message
					$error .= sprintf($locale['413'], $email_domain)."<br /><br />\n";
				} else {
					if (!$mail->Mail('address-validation'.strrchr($settings['siteemail'], "@"))) {
						// mail server doesn't respond to MAIL FROM message
						$error .= sprintf($locale['413'], $email_domain)."<br /><br />\n";
					} else {
						if (!$mail->Recipient($email)) {
							// mail server doesn't respond to RCPT TO message
							$error .= sprintf($locale['414'], $email, $mailhost)."<br /><br />\n";
						} else {
							// email address is accepted
						}
					}
				}
				$mail->Quit();
			}
		} else {
			$error .= sprintf($locale['412'], $email_domain)."<br /><br />\n";
		}
	
		$result = dbquery("SELECT * FROM ".$db_prefix."blacklist WHERE blacklist_email='".$email."' OR blacklist_email='$email_domain'");
		if (dbrows($result) != 0) $error .= $locale['411']."<br /><br />\n";
		
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) $error .= $locale['407']."<br /><br />\n";
		
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='".$email."'");
		if (dbrows($result) != 0) $error .= $locale['408']."<br /><br />\n";
		
		if ($settings['email_verification'] == "1") {
			$result = dbquery("SELECT * FROM ".$db_prefix."new_users");
			while ($new_users = dbarray($result)) {
				$user_info = unserialize($new_users['user_info']); 
				if ($new_users['user_email'] == $email) { $error .= $locale['409']."<br /><br />\n"; }
				if ($user_info['user_name'] == $username) { $error .= $locale['407']."<br /><br />\n"; break; }
			}
		}

		if ($settings['display_validation'] == "1") {
			if (isset($_POST['user_code'])) {
				$user_code = stripinput($_POST['user_code']);
				$result = dbquery("SELECT * FROM ".$db_prefix."vcode WHERE vcode_1='$user_code'");
				if (dbrows($result) == 0) {
					$error .= $locale['410']."<br /><br />\n";
				} else {
					$result = dbquery("DELETE FROM ".$db_prefix."vcode WHERE vcode_1='$user_code'");
				}
			} else {
				$error .= $locale['410']."<br /><br />\n";
			}
		}
		
		$user_hide_email = isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
		$user_offset = is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0";
		
		if ($settings['email_verification'] == "0") {
			$user_location = isset($_POST['user_location']) ? stripinput(trim($_POST['user_location'])) : "";
			if ($_POST['user_month'] != 0 && $_POST['user_day'] != 0 && $_POST['user_year'] != 0) {
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
			$user_theme = stripinput($_POST['user_theme']);
			$user_sig = isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "";
		}
		if ($error == "") {
			if ($settings['email_verification'] == "1") {
				require_once PATH_INCLUDES."sendmail_include.php";
				mt_srand((double)microtime()*1000000); $salt = "";
				for ($i=0;$i<=7;$i++) { $salt .= chr(rand(97, 122)); }
				$user_code = md5($email.$salt);
				$activation_url = $settings['siteurl']."register.php?activate=".$user_code;
				if (sendemail($username,$email,$settings['siteusername'],$settings['siteemail'],$locale['449'], $locale['450'].$activation_url)) {
					$user_info = serialize(array(
						"user_name" => $username,
						"user_fullname" => $fullname,
						"user_password" => $password1,
						"user_email" => $email,
						"user_offset" => $user_offset,
						"user_hide_email" => isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1"
					));
					$result = dbquery("INSERT INTO ".$db_prefix."new_users (user_code, user_email, user_datestamp, user_info) VALUES('$user_code', '".$email."', '".time()."', '$user_info')");
					$variables['message'] = $locale['454'];
					$title = $locale['400'];
				} else {
					$variables['message'] = $locale['457'];
					$title = $locale['456'];
				}
				// define the body panel variables
				$template_panels[] = array('type' => 'body', 'title' => $title, 'name' => 'register.verify', 'template' => '_message_table_panel.tpl');
				$template_variables['register.verify'] = $variables;
			} else {
				$activation = $settings['admin_activation'] == "1" ? "2" : "0";
				$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_password, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_theme, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status) VALUES('$username', md5('".$password1."'), '".$email."', '$user_hide_email', '$user_location', '$user_birthdate', '$user_aim', '$user_icq', '$user_msn', '$user_yahoo', '$user_web', '$user_theme', '$user_offset', '', '$user_sig', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '$activation')");
				if ($settings['admin_activation'] == "1") {
					$variables['message'] = $locale['453'];
				} else {
					$variables['message'] = $locale['452'];
				}
				// define the body panel variables
				$template_panels[] = array('type' => 'body', 'title' => $locale['451'], 'name' => 'register.verify', 'template' => '_message_table_panel.tpl');
				$template_variables['register.verify'] = $variables;
			}
		} else {
			$variables['message'] = $error;
			$variables['link'] = FUSION_SELF;
			$variables['linktext'] = $locale['459'];
			// define the body panel variables
			$template_panels[] = array('type' => 'body', 'title' => $locale['456'], 'name' => 'register.verify', 'template' => '_message_table_panel.tpl');
			$template_variables['register.verify'] = $variables;
		}
	} else {
		$variables['timezone'] = sprintf($locale['u023'], "GMT ".(date('O')=="+0000"?"":date('O')));
		$variables['serveroffset'] = substr(date('O'),0,1).(substr(date('O'),1)/100);
		if ($settings['email_verification'] == "0") {
			$theme_files = makefilelist(PATH_THEMES, ".|..", true, "folders");
			array_unshift($theme_files, "Default");
		} else {
			$theme_files = array();
		}
		if ($settings['display_validation'] == "1") {
			srand((double)microtime()*1000000); 
			$temp_num = md5(rand(0,9999)); 
			$vcode_1 = substr($temp_num, 17, 5); 
			$vcode_2 = md5($vcode_1);
			unset($temp_num);
			$result = dbquery("INSERT INTO ".$db_prefix."vcode VALUES('".time()."', '$vcode_1', '$vcode_2')");
		}
		$variables['theme_files'] = $theme_files;
		$variables['vcode_1'] = $vcode_1;
		$variables['vcode_2'] = $vcode_2;
		// define the body panel variables
		$template_panels[] = array('type' => 'body', 'name' => 'register', 'template' => 'main.register.tpl', 'locale' => array(PATH_LOCALE.LOCALESET."register.php", PATH_LOCALE.LOCALESET."user_fields.php"));
		$template_variables['register'] = $variables;
	}
} else {
	// define the body panel variables
	$variables['message'] = $locale['507'];
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'title' => $locale['400'], 'name' => 'register.disabled', 'template' => '_message_table_panel.tpl');
	$template_variables['register.disabled'] = $variables;
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>