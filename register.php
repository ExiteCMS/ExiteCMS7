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
require_once PATH_INCLUDES."dns_functions.php";

// do we want extensive email checks?
define('CHECK_EMAIL', false);

// temp storage for template variables
$variables = array();

// no point registering when you're already a member
if (iMEMBER) fallback(BASEDIR."index.php");

// load the locales for this module
locale_load("main.register");
locale_load("main.user_fields");

// check whether we allow registrations
if ($settings['enable_registration'] == 1) {

	// new user activation
	if (isset($activate)) {
		if (!preg_match("/^[0-9a-z]{32}$/", $activate)) fallback("index.php");
		$result = dbquery("SELECT * FROM ".$db_prefix."new_users WHERE user_code='$activate'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$user_info = unserialize($data['user_info']);
			$activation = $settings['admin_activation'] == "1" ? "2" : "0";
			$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_fullname, user_password, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_locale, user_theme, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status, user_newsletters) VALUES('".$user_info['user_name']."', '".$user_info['user_fullname']."', '".md5(md5($user_info['user_password']))."', '".$user_info['user_email']."', '".$user_info['user_hide_email']."', '', '0000-00-00', '', '', '', '', '', '".$user_info['user_locale']."', 'Default', '".$user_info['user_offset']."', '', '', '0', '".time()."', '".time()."', '".USER_IP."', '', '', '101', '$activation', '1')");
			$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_code='$activate'");
			if ($settings['admin_activation'] == "1") {
				$variables['message'] = $locale['453'];
				// send notifications out if need be
				if ($settings['notify_on_activation']) {
					// get the list of all administrators with user activation access
					$admins = array();
					$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_rights LIKE '%UA%'");
					while ($data = dbarray($result)) {
						$admin_rights = explode(".", $data['user_rights']);
						if (in_array('UA', $admin_rights)) {
							$admins[] = $data;
						}
					}
					// notify these administrators
					if ($settings['notify_on_activation'] == "1") {
						// via PM
						foreach ($admins as $admin) {
							$result = dbquery("INSERT INTO ".$db_prefix."pm (pm_subject, pm_message, pm_recipients, pm_size, pm_datestamp) VALUES ('".$locale['509']."', '".mysqli_real_escape_string($_db_link, sprintf($locale['510'], $user_info['user_name']))."', '1', '100', '".time()."')");
							if ($result) {
								$pm_id = mysqli_insert_id($db_link);
								$result = dbquery("INSERT INTO ".$db_prefix."pm_index (pm_id, pmindex_user_id, pmindex_from_id, pmindex_to_id, pmindex_folder) VALUES ('".$pm_id."', '".$admin['user_id']."', '0', '".$admin['user_id']."', '0')");
							}
						}
					} elseif ($settings['notify_on_activation'] == "2") {
						// via Email
						require_once PATH_INCLUDES."sendmail_include.php";
						foreach ($admins as $admin) {
							sendemail($admin['user_name'], $admin['user_email'], $settings['siteusername'],
								($settings['newsletter_email'] != "" ? $settings['newsletter_email'] : $settings['siteemail']),
								$locale['509'],
								sprintf($locale['510'], $user_info['user_name'])
							);
						}
					}
				}
			} else {
				$variables['message'] = $locale['452'];
			}
			// define the body panel variables
			$template_panels[] = array('type' => 'body', 'name' => 'register.activate', 'template' => 'main.register.activate.tpl', 'locale' => array("main.register", "main.user_fields"));
			$template_variables['register.activate'] = $variables;
		} else {
			fallback(BASEDIR."index.php");
		}

	// process the new registration
	} else if (isset($_POST['register'])) {
		$error = "";
		$username = stripinput($_POST['username']);
		$fullname = eregi_replace("\"|'", "", $_POST['fullname']);
		$email = stripinput(trim(eregi_replace(" +", "", $_POST['email'])));
		$password1 = stripinput(trim(eregi_replace(" +", "", $_POST['password1'])));

		if ($username == "" || $password1 == "" || $email == "" || $fullname == "") $error .= $locale['402']."<br /><br />\n";

	//	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['403']."<br /><br />\n";
		if (strpos($username, " ") !== FALSE) $error .= $locale['403']."<br /><br />\n";

		if (preg_match("/^[0-9A-Z_@!\.\?]{6,20}$/i", $password1)) {
			if ($password1 != $_POST['password2']) $error .= $locale['404']."<br /><br />\n";
		} else {
			$error .= $locale['405']."<br /><br />\n";
		}

		if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
			$error .= $locale['406']."<br /><br />\n";
		}

		$email_domain = substr(strrchr($email, "@"), 1);
		if (CHECK_EMAIL) {
			if (CMS_getmxrr($email_domain, $mxhosts)) {
				// Get the hostnamxe the MX record points to
				$mailhost = $mxhosts[0];
			} else {
				// No MX record for this domain. Might be a hostname that accepts email
				$mailhost = $email_domain;
			}
			$mailhost_ip = gethostbyname($mailhost);
			if ($mailhost != $mailhost_ip) {
				// found the mailserver for this email address. Check if the address exists
				require_once PATH_INCLUDES.'class.smtp.php';
				$mail = new SMTP();
				if (!$mail->Connect($mailhost_ip, 0, 60)) {		// default SMTP port, 60sec timeout
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
							if (!$mail->CheckRecipient($email)) {
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
		}

		$result = dbquery("SELECT * FROM ".$db_prefix."blacklist WHERE blacklist_email='".$email."' OR blacklist_email='$email_domain'");
		if (dbrows($result) != 0) $error .= $locale['411']."<br /><br />\n";

		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) $error .= sprintf($locale['407'],(isset($_POST['username']) ? $_POST['username'] : ""))."<br /><br />\n";

		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='".$email."'");
		if (dbrows($result) != 0) $error .= sprintf($locale['408'],(isset($_POST['email']) ? $_POST['email'] : ""))."<br /><br />\n";

		if ($settings['email_verification'] == "1") {
			$result = dbquery("SELECT * FROM ".$db_prefix."new_users");
			while ($new_users = dbarray($result)) {
				$user_info = unserialize($new_users['user_info']);
				if ($new_users['user_email'] == $email) { $error .= $locale['409']."<br /><br />\n"; }
				if ($user_info['user_name'] == $username) { $error .= $locale['407']."<br /><br />\n"; break; }
			}
		}

		if ($settings['display_validation'] == "1") {
			// include the secureimage class
			require_once PATH_INCLUDES."secureimage-1.0.3/securimage.php";
			$securimage = new Securimage();
			if (!isset($_POST['captcha_code']) || $securimage->check($_POST['captcha_code']) == false) {
				// the code was incorrect
				$error .= $locale['410']."<br />\n";
			}
		}

		$user_hide_email = isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
		$user_offset = isset($_POST['user_offset']) && is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0";
		$user_locale = stripinput($_POST['user_locale']);

		if ($settings['email_verification'] == "0") {
			$user_location = isset($_POST['user_location']) ? stripinput(trim($_POST['user_location'])) : "";
			if ($_POST['user_Month'] != 0 && $_POST['user_Day'] != 0 && $_POST['user_Year'] != 0) {
				$user_birthdate = (isNum($_POST['user_Year']) ? $_POST['user_Year'] : "0000")
				."-".(isNum($_POST['user_Month']) ? $_POST['user_Month'] : "00")
				."-".(isNum($_POST['user_Day']) ? $_POST['user_Day'] : "00");
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
				$msg_search = array("[USERNAME]", "[SITENAME]", "[FULLNAME]", "[PASSWORD]");
				$msg_replace = array((isset($_POST['username']) ? $_POST['username'] : ""), $settings['sitename'], (isset($_POST['fullname']) ? $_POST['fullname'] : ""), (isset($_POST['password1']) ? $_POST['password1'] : ""));
				$msg = str_replace($msg_search, $msg_replace, $locale['450']);
				$msg .= $settings['siteurl']."register.php?activate=".$user_code;
				if (sendemail($username,$email,$settings['siteusername'],$settings['siteemail'],sprintf($locale['449'],$settings['sitename']), $msg)) {
					$user_info = serialize(array(
						"user_name" => $username,
						"user_fullname" => $fullname,
						"user_password" => $password1,
						"user_email" => $email,
						"user_offset" => $user_offset,
						"user_locale" => $user_locale,
						"user_ip" => USER_IP,
						"user_hide_email" => isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1"
					));
					$result = dbquery("INSERT INTO ".$db_prefix."new_users (user_code, user_email, user_datestamp, user_info) VALUES('$user_code', '".$email."', '".time()."', '".mysqli_real_escape_string($_db_link, $user_info)."')");
					$variables['message'] = $locale['454'];
					$title = $locale['400'];
				} else {
					$variables['message'] = sprintf($locale['457'],$settings['siteemail']);
					$title = $locale['456'];
				}
				// define the body panel variables
				$template_panels[] = array('type' => 'body', 'title' => $title, 'name' => 'register.verify', 'template' => '_message_table_panel.tpl');
				$template_variables['register.verify'] = $variables;
			} else {
				$activation = $settings['admin_activation'] == "1" ? "2" : "0";
				$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_password, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_theme, user_locale, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status) VALUES('$username', md5(md5('".$password1."')), '".$email."', '$user_hide_email', '$user_location', '$user_birthdate', '$user_aim', '$user_icq', '$user_msn', '$user_yahoo', '$user_web', '$user_theme', '$user_locale', '$user_offset', '', '$user_sig', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '$activation')");
				if ($settings['admin_activation'] == "1") {
					$variables['message'] = $locale['453'];
					// send notifications out if need be
					if ($settings['notify_on_activation']) {
						// get the list of all administrators with user activation access
						$admins = array();
						$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_rights LIKE '%UA%'");
						while ($data = dbarray($result)) {
							$admin_rights = explode(".", $data['user_rights']);
							if (in_array('UA', $admin_rights)) {
								$admins[] = $data;
							}
						}
						// notify these administrators
						if ($settings['notify_on_activation'] == "1") {
							// via PM
							foreach ($admins as $admin) {
								$result = dbquery("INSERT INTO ".$db_prefix."pm (pm_subject, pm_message, pm_recipients, pm_size, pm_datestamp) VALUES ('".$locale['509']."', '".mysqli_real_escape_string($_db_link, sprintf($locale['510'], $username))."', '1', '100', '".time()."')");
								if ($result) {
									$pm_id = mysqli_insert_id($_db_link);
									$result = dbquery("INSERT INTO ".$db_prefix."pm_index (pm_id, pmindex_user_id, pmindex_from_id, pmindex_to_id, pmindex_folder) VALUES ('".$pm_id."', '".$admin['user_id']."', '0', '".$admin['user_id']."', '0')");
								}
							}
						} elseif ($settings['notify_on_activation'] == "2") {
							require_once PATH_INCLUDES."sendmail_include.php";
							// via Email
							foreach ($admins as $admin) {
								sendemail($admin['user_name'], $admin['user_email'], $settings['siteusername'],
									($settings['newsletter_email'] != "" ? $settings['newsletter_email'] : $settings['siteemail']),
									$locale['509'],
									sprintf($locale['510'], $username)
								);
							}
						}
					}
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
		if ($settings['display_validation'] == "1" && $settings['validation_method'] == "text") {
			require_once PATH_INCLUDES."secureimage-1.0.3/securimage.php";
			$securimage = new Securimage();
			$securimage->createCode();
			$variables['validation_code'] = $_SESSION['securimage_code_value'];
		}
		$variables['locales'] = array();
		$result = dbquery("SELECT locale_code, locale_name FROM ".$db_prefix."locale WHERE locale_active = '1' ORDER BY locale_name");
		while ($data = dbarray($result)) {
			$variables['locales'][$data['locale_code']] = $data['locale_name'];
		}
		$variables['theme_files'] = $theme_files;
		// define the body panel variables
		$template_panels[] = array('type' => 'body', 'name' => 'register', 'template' => 'main.register.tpl', 'locale' => array("main.register", "main.user_fields"));
		$template_variables['register'] = $variables;
	}
} else {
	// define the body panel variables
	$variables['message'] = $locale['507'];
	$template_panels[] = array('type' => 'body', 'title' => $locale['400'], 'name' => 'register.disabled', 'template' => '_message_table_panel.tpl');
	$template_variables['register.disabled'] = $variables;
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
