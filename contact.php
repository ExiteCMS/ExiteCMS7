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
require_once PATH_ROOT."/includes/theme_functions.php";

// include the secureimage class
require_once PATH_INCLUDES."secureimage-1.0.3/secureimage.php";

// temp storage for template variables
$variables = array();

// check if an alternative email address is given. If so, validate it.
if (isset($target) && isset($tc)) {
	if (md5("PrEf".$target."SuFf") == $tc) {
		if (strpos($target, '@') === false) $target = $target . strstr($settings['siteemail'], '@');
	} else {
		$target = $settings['siteemail'];
	}
} else {
	$target = $settings['siteemail'];
}

// load the locale for this module
locale_load("main.contact");

// captcha check
$cic = "";
$securimage = new Securimage();
if ($securimage->check($_POST['captcha_code']) == false) {
	$cic = "&cic=1";
}
$variables['cic'] = $cic;

// get variables from the post, or initialize them
if (isset($_POST['sendmessage'])) {
	$mailname = substr(stripinput(trim($_POST['mailname'])),0,50);
	$email = substr(stripinput(trim($_POST['email'])),0,100);
	$subject = substr(str_replace(array("\r","\n","@"), "", descript(stripslash(trim($_POST['subject'])))),0,50);
	$message = descript(stripslash(trim($_POST['message'])));
} else {
	$mailname = "";
	$email = "";
	$subject = "";
	$message = "";
}

// captcha check ok and message posted?
if ($cic == "" && isset($_POST['sendmessage'])) {
	$errors = array();
	if ($mailname == "") {
		$errors[] = $locale['420'];
	}
	if ($email == "" || !preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$errors[] = $locale['421'];
	}
	if ($subject == "") {
		$errors[] = $locale['422'];
	}
	if ($message == "") {
		$errors[] = $locale['423'];
	}
	$error = count($errors);
	if ($error == 0) {
		require_once PATH_INCLUDES."sendmail_include.php";
		sendemail($settings['siteusername'],$target,$mailname,$email,$subject,$message);
	}
	// define the body panel variables
	$variables['target'] = $target;
	$variables['error'] = $error;
	$variables['errors'] = $errors;
	$template_panels[] = array('type' => 'body', 'name' => 'main.contact.message', 'template' => 'main.contact.message.tpl', 'locale' => "main.contact");
	$template_variables['main.contact.message'] = $variables;
} else {
	// form variables
	$variables['mailname'] = $mailname;
	$variables['email'] = $email;
	$variables['subject'] = $subject;
	$variables['message'] = $message;
	// define the body panel variables
	$variables['target'] = $target;
	$template_panels[] = array('type' => 'body', 'name' => 'main.contact', 'template' => 'main.contact.tpl', 'locale' => "main.contact");
	$template_variables['main.contact'] = $variables;
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>