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
require_once PATH_ROOT."/includes/theme_functions.php";

// no need for a reset if you're logged-in
if (iMEMBER) fallback(BASEDIR."index.php");

// load the locale for this module
locale_load("main.lostpassword");

// get the sendmail include
require_once PATH_INCLUDES."sendmail_include.php";

// temp storage for template variables
$variables = array();

if (isset($email) && isset($account)) {
	$error = 0;
	if (FUSION_QUERY != "email=".$email."&amp;account=".$account) fallback("index.php");
	$email = stripinput(trim(eregi_replace(" +", "", $email)));
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) $error = 1;
	if (!preg_match("/^[0-9a-z]{32}$/", $account)) $error = 1;
	if ($error == 0) {

		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_password='$account' AND user_email='$email'");
		if (dbrows($result) != 0) {
			$data = dbarray($result); $new_pass = "";
			for ($i=0;$i<=7;$i++) { $new_pass .= chr(rand(97, 122)); }
			$mailbody = str_replace("[NEW_PASS]", $new_pass, $locale['411']);
			$mailbody = str_replace("[USERNAME]", $data['user_name'], $mailbody);
			$mailbody = str_replace("[SITENAME]", $settings['sitename'], $mailbody);
			$mailbody = str_replace("[SITEUSERNAME]", $settings['siteusername'], $mailbody);
			sendemail($data['user_name'],$email,$settings['siteusername'],$settings['siteemail'],$locale['409'].$settings['sitename'],$mailbody);
			$result = dbquery("UPDATE ".$db_prefix."users SET user_password=md5(md5('$new_pass')) WHERE user_id='".$data['user_id']."'");
			// define the body panel variables
			$variables['message'] = $locale['402'];
			$variables['bold'] = true;
			$template_panels[] = array('type' => 'body', 'name' => 'lostpassword', 'template' => '_message_table_panel.tpl', 'locale' => "main.lostpassword");
			$template_variables['lostpassword'] = $variables;
		} else {
			$error = 1;
		}
	}
	if ($error == 1) redirect("index.php");
} elseif (isset($_POST['send_password'])) {
	$email = stripinput(trim(eregi_replace(" +", "", $_POST['email'])));
	if (preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='$email'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$new_pass_link = $settings['siteurl']."lostpassword.php?email=".$data['user_email']."&account=".$data['user_password'];
			$mailbody = str_replace("[NEW_PASS_LINK]", $new_pass_link, $locale['410']);
			$mailbody = str_replace("[USERNAME]", $data['user_name'], $mailbody);
			$mailbody = str_replace("[SITENAME]", $settings['sitename'], $mailbody);
			$mailbody = str_replace("[SITEUSERNAME]", $settings['siteusername'], $mailbody);
			sendemail($data['user_name'],$email,$settings['siteusername'],$settings['siteemail'],$locale['409'].$settings['sitename'],$mailbody);
			// define the body panel variables
			$variables['message'] = $locale['401'];
			$variables['link'] = "index.php";
			$variables['linktext'] = $locale['403'];
			$variables['bold'] = true;
			$template_panels[] = array('type' => 'body', 'name' => 'lostpassword', 'template' => '_message_table_panel.tpl', 'locale' => "main.lostpassword");
			$template_variables['lostpassword'] = $variables;
		} else {
			// define the body panel variables
			$variables['message'] = $locale['404'];
			$variables['link'] = FUSION_SELF;
			$variables['linktext'] = $locale['406'];
			$variables['bold'] = true;
			$template_panels[] = array('type' => 'body', 'name' => 'lostpassword', 'template' => '_message_table_panel.tpl', 'locale' => "main.lostpassword");
			$template_variables['lostpassword'] = $variables;
		}
	} else {
		// define the body panel variables
		$variables['message'] = $locale['405'];
		$variables['link'] = FUSION_SELF;
		$variables['linktext'] = $locale['403'];
		$variables['bold'] = true;
		$template_panels[] = array('type' => 'body', 'name' => 'lostpassword', 'template' => '_message_table_panel.tpl', 'locale' => "main.lostpassword");
		$template_variables['lostpassword'] = $variables;
	}
} else {
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'lostpassword', 'template' => 'main.lostpassword.tpl', 'locale' => "main.lostpassword");
	$template_variables['lostpassword'] = $variables;
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
