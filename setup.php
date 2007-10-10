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

/*---------------------------------------------------+
| mySQL database functions
+----------------------------------------------------*/
function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br />".mysql_errno()." : ".mysql_error()."</div>");
	} elseif (!$db_select) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br />".mysql_errno()." : ".mysql_error()."</div>");
	}
}

function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbarray($resource) {
	$result = @mysql_fetch_assoc($resource);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

/*---------------------------------------------------+
| Strip Input Function, prevents HTML in unwanted places
+----------------------------------------------------*/
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

/*---------------------------------------------------+
| Create a list of files or folders and store them in an array
+----------------------------------------------------*/
function makefilelist($folder, $filter, $sort=true, $type="files") {
	$res = array();
	$filter = explode("|", $filter); 
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

/*---------------------------------------------------+
| setup main code starts here
+----------------------------------------------------*/

// absolute path definitions
define("PATH_ROOT", dirname(__FILE__).'/');
define("PATH_ADMIN", PATH_ROOT."administration/");
define("PATH_THEMES", PATH_ROOT."themes/");
define("PATH_THEME", PATH_ROOT."themes/PLiTheme/");
define("PATH_LOCALE", PATH_ROOT."locale/");
define("PATH_PHOTOS", PATH_ROOT."images/photoalbum/");
define("PATH_IMAGES", PATH_ROOT."images/");
define("PATH_IMAGES_A", PATH_IMAGES."articles/");
define("PATH_IMAGES_ADS", PATH_IMAGES."advertising/");
define("PATH_IMAGES_AV", PATH_IMAGES."avatars/");
define("PATH_IMAGES_N", PATH_IMAGES."news/");
define("PATH_IMAGES_NC", PATH_IMAGES."news_cats/");
define("PATH_IMAGES_DC", PATH_IMAGES."download_cats/");
define("PATH_INCLUDES", PATH_ROOT."includes/");
define("PATH_MODULES", PATH_ROOT."modules/");
define("PATH_ATTACHMENTS", PATH_ROOT."files/");

define("FUSION_SELF", isset($_SERVER['REDIRECT_URL']) && $_SERVER['REDIRECT_URL'] != "" ? basename($_SERVER['REDIRECT_URL']) : basename($_SERVER['PHP_SELF']));
define('INIT_CMS_OK', true);			

// error tracking
$error = "";

// temp storage for template variables
$variables = array();

// parameter validation
$step = (isset($_GET['step']) ? $_GET['step'] : "0");
$variables['step'] = $step;
$localeset = (isset($_GET['localeset']) ? $_GET['localeset'] : "English");
$variables['localeset'] = $localeset;
define("LOCALESET", $localeset.'/');

// check if the cache directories are writeable
if (!is_writable(PATH_ATTACHMENTS."cache")) {
	die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run the ExiteCMS setup: The cache directory is not writeable.</b><br />Please consult the documentation on how to define the proper file rights.</div>");
}
if (!is_writable(PATH_ATTACHMENTS."tplcache")) {
	die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run the ExiteCMS setup: The template cache directory is not writeable.</b><br />Please consult the documentation on how to define the proper file rights.</div>");
}

// first part in step1: create config.php. We need it later
if ($step == "1") {
	$db_host = stripinput($_POST['db_host']);
	$db_user = stripinput($_POST['db_user']);
	$db_pass = stripinput($_POST['db_pass']);
	$db_name = stripinput($_POST['db_name']);
	$db_prefix = stripinput($_POST['db_prefix']);
	$config = "<?php
// global database settings
"."$"."db_host="."\"".$_POST['db_host']."\"".";
"."$"."db_user="."\"".$_POST['db_user']."\"".";
"."$"."db_pass="."\"".$_POST['db_pass']."\"".";
"."$"."db_name="."\"".$_POST['db_name']."\"".";
"."$"."db_prefix="."\"".$_POST['db_prefix']."\"".";

// user database settings
"."$"."user_db_host="."\"".$_POST['db_host']."\"".";
"."$"."user_db_user="."\"".$_POST['db_user']."\"".";
"."$"."user_db_pass="."\"".$_POST['db_pass']."\"".";
"."$"."user_db_name="."\"".$_POST['db_name']."\"".";
"."$"."user_db_prefix="."\"".$_POST['db_prefix']."\"".";
?>";
	$temp = fopen(PATH_ROOT."config.php","w");
	if (!fwrite($temp, $config)) {
		$error .= $locale['430']."<br /><br />";
		fclose($temp);
	} else {
		fclose($temp);
	}
}

require_once PATH_ROOT."includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.$localeset."/setup.php";

// process the different setup steps
switch($step) {
	case "0":
		// if the config file already exists, bail out
		if (file_exists(PATH_ROOT."config.php") && filesize(PATH_ROOT."config.php")) {
			die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run the ExiteCMS setup: A valid configuration exists.</b><br />Please consult the documentation on how to rerun the setup.</div>");
		}
		// check if the config template exists and is writeable. If so, rename it
		if (!file_exists(PATH_ROOT."config.def")) {
			die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run the ExiteCMS setup: The configuration template file is missing.</b><br />Please reinstall ExiteCMS.</div>");
		}
		// create a list of available locales
		$locale_files = makefilelist("locale/", ".|..", true, "folders");
		$variables['locale_files'] = $locale_files;
		// check if all required directories are writable
		$permissions = "";
		if (!is_writable(PATH_IMAGES)) $permissions .= PATH_IMAGES . "<br />";
		if (!is_writable(PATH_IMAGES_A)) $permissions .= PATH_IMAGES_A . "<br />";
		if (!is_writable(PATH_IMAGES_AV)) $permissions .= PATH_IMAGES_AV . "<br />";
		if (!is_writable(PATH_IMAGES_N)) $permissions .= PATH_IMAGES_N . "<br />";
		if (!is_writable(PATH_ATTACHMENTS)) $permissions .= PATH_ATTACHMENTS . "<br />";
		if (!is_writable("config.def")) {
			$permissions .= "Configuration Template" . "<br />";
		} else {
			@rename("config.def", "config.php");
			if (!is_writable("config.php")) $permissions .= "Configuration Tile" . "<br />";
		}
		if ($permissions == "") {
			$variables['write_check'] = true; 
		} else { 
			$variables['write_check'] = false;
			$error = "<b>".$locale['412']."</b><br /><br />".$permissions."<br /><b>".$locale['413']."</b>";
		}
		break;
	case "1":
		if ($error == "") {
			require_once "config.php";
			$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
			require_once PATH_INCLUDES."dbsetup_include.php";
			if (isset($fail) && $fail == "1") {
				$variables['fail'] = true;
				$fs = "";
				foreach($failed as $ft) {
					$fs .= ($fs == "" ? "" : ", ") . "'". $ft . "'";
				}
				$error .= sprintf($locale['431'], $fs)."<br /><br />";
			} else {
				$variables['fail'] = false;
			}
		}
		break;
	case "2":
		require_once "config.php";
		$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
		$basedir = substr($_SERVER['PHP_SELF'], 0, -9);
		$username = stripinput($_POST['username']);
		$password1 = stripinput($_POST['password1']);
		$password2 = stripinput($_POST['password2']);
		$email = stripinput($_POST['email']);
		if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['450']."<br /><br />\n";
		if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
			if ($password1 != $password2) $error .= $locale['451']."<br /><br />\n";
		} else {
			$error .= $locale['452']."<br /><br />\n";
		}
	 	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
			$error .= $locale['453']."<br /><br />\n";
		}

		require_once PATH_INCLUDES."dbsetup_include.php";

		if ($error == "") {

			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (1, 'sitename', 'ExiteCMS Powered Website')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (2, 'siteurl', '/')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (3, 'siteemail', 'webmaster@yourdomain.com')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (4, 'siteusername', '$username')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (5, 'siteintro', '<center>ExiteCMS v7.0 &copy;2007 Exite BV.<br />See http://exitecms.exite.eu for more information</center>')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (6, 'description', '')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (7, 'keywords', '')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (8, 'footer', '')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (9, 'opening_page', 'news.php')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (10, 'news_headline', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (11, 'news_columns', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (12, 'news_items', '3')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (13, 'news_latest', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (14, 'locale', 'English')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (15, 'theme', 'ExiteCMS')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (16, 'shortdate', '%d/%m/%Y %H:%M')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (17, 'longdate', '%B %d %Y %H:%M:%S')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (18, 'forumdate', '%d-%m-%Y %H:%M')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (19, 'subheaderdate', '%B %d %Y %H:%M:%S')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (20, 'timeoffset', '+0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (21, 'numofthreads', '10')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (22, 'attachments', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (23, 'attachmax', '10485760')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (24, 'attachtypes', '.exe,.com,.bat,.js,.htm,.html,.shtml,.php,.php3,.esml,.psd,.mvi')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (25, 'thread_notify', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (26, 'enable_registration', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (27, 'email_verification', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (28, 'admin_activation', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (29, 'display_validation', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (30, 'validation_method', 'image')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (31, 'thumb_w', '150')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (32, 'thumb_h', '150')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (33, 'photo_w', '400')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (34, 'photo_h', '300')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (35, 'photo_max_w', '1800')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (36, 'photo_max_h', '1600')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (37, 'photo_max_b', '150000')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (38, 'thumb_compression', 'gd2')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (39, 'thumbs_per_row', '4')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (40, 'thumbs_per_page', '12')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (41, 'tinymce_enabled', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (42, 'smtp_host', '')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (43, 'smtp_username', '')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (44, 'smtp_password', '')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (45, 'bad_words_enabled', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (46, 'bad_words', '')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (47, 'bad_word_replace', '[censored]')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (48, 'guestposts', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (49, 'numofshouts', '5')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (50, 'flood_interval', '15')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (51, 'counter', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (52, 'max_users', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (53, 'max_users_datestamp', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (54, 'version', '7.00')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (55, 'revision', '909')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (56, 'remote_stats', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (57, 'maintenance', '0')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (58, 'maintenance_message', '')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (59, 'maintenance_color', 'red')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (61, 'forum_flags', '1')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (62, 'forum_max_w', '600')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (63, 'forum_max_h', '600')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (64, 'newsletter_email', 'noreply@yourdomain.com')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (65, 'pm_inbox', '100')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (66, 'pm_sentbox', '100')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (67, 'pm_savebox', '200')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (68, 'pm_send2group', '103')";
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_id, cfg_name, cfg_value) VALUES (69, 'pm_hide_rcpts', '1')";

			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('A', 'articles.gif', '".$locale['462']."', 'articles.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AC', 'article_cats.gif', '".$locale['461']."', 'article_cats.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AD', 'admins.gif', '".$locale['460']."', 'administrators.php', 2)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('B', 'blacklist.gif', '".$locale['463']."', 'blacklist.php', 2)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('C', '', '".$locale['464']."', 'reserved', 2)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('CP', 'c-pages.gif', '".$locale['465']."', 'custom_pages.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('D',  'dl.gif', '".$locale['468']."', 'downloads.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DB', 'db_backup.gif', '".$locale['466']."', 'db_backup.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DC', 'dl_cats.gif', '".$locale['467']."', 'download_cats.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('F',  'forums.gif', '".$locale['470']."', 'forums.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FQ', 'faq.gif', '".$locale['469']."', 'faq.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('I', 'modules.gif', '".$locale['472']."', 'modules.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IM', 'images.gif', '".$locale['471']."', 'images.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IP', '', '".$locale['473']."', 'reserved', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('M',  'members.gif', '".$locale['474']."', 'members.php', 2)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('N',  'news.gif', '".$locale['475']."', 'news.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('NC', 'news_cats.gif', '".$locale['494']."', 'news_cats.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('P',  'panels.gif', '".$locale['476']."', 'panels.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PI', 'phpinfo.gif', '".$locale['478']."', 'phpinfo.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PO', 'polls.gif', '".$locale['479']."', 'polls.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S1', 'settings.gif', '".$locale['487']."', 'settings_main.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S2', 'settings_time.gif', '".$locale['488']."', 'settings_time.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S3', 'settings_forum.gif', '".$locale['489']."', 'settings_forum.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S4', 'registration.gif', '".$locale['490']."', 'settings_registration.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S6', 'settings_misc.gif', '".$locale['492']."', 'settings_misc.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S7', 'settings_pm.gif', '".$locale['493']."', 'settings_messages.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S8', 'settings_lang.gif', '".$locale['459']."', 'settings_languages.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SL', 'site_links.gif', '".$locale['481']."', 'site_links.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('U',  'upgrade.gif', '".$locale['483']."', 'upgrade.php', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UG', 'user_groups.gif', '".$locale['484']."', 'user_groups.php', 2)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UR', 'submissions.gif', '".$locale['496']."', 'redirects.php', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('wE', 'adverts.gif', '".$locale['498']."', 'adverts.php', 1)");

			$result = dbquery("INSERT INTO ".$db_prefix."custom_pages (page_id, page_title, page_access, page_content, page_allow_comments, page_allow_ratings) VALUES (0, '404 Error Page', 0, '".mysql_escape_string("<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" align=\"center\"> <tbody><tr><td width=\"10\"> </td><td><div align=\"center\"><font size=\"6\"><span class=\"shoutboxname\"><br />404 - Page Not Found</span><br /></font></div><br /><br /><hr width=\"90%\" size=\"2\" /><br /><br /><div align=\"center\">".$locale['560']."<br /></div><br /><div align=\"center\">".$locale['561']."<br /></div><br /><div align=\"center\">".$locale['562']."<br /></div><br /><br /><hr width=\"90%\" size=\"2\" /><br /><br /><div align=\"center\">".$locale['563']."<br /></div><br /><div align=\"center\">".$locale['564']."</div></td><td width=\"10\"> </td></tr></tbody></table><br />")."', 0, 0)");

			$result = dbquery("SELECT admin_rights FROM ".$db_prefix."admin");
			$adminrights = "";
			while ($data = dbarray($result)) {
				$adminrights .= ($adminrights == "" ? "" : ".") . $data['admin_rights'];
			}
					
			$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_password, user_webmaster, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_forum_fullscreen, user_theme, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status) VALUES ('$username', md5('$password1'), '1', '$email', '1', '', '0000-00-00', '', '', '', '', '', '0', 'Default', '0', '', '', '0', '".time()."', '0', '0.0.0.0', '".$adminrights."', '', '103', '0')");
	
			$result = dbquery("INSERT INTO ".$db_prefix."pm_config (user_id, pmconfig_save_sent, pmconfig_read_notify, pmconfig_email_notify, pmconfig_auto_archive ) VALUES ('0', '0', '1', '0', '90')");
		
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['540']."', 'bugs.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['541']."', 'downloads.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['542']."', 'games.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['543']."', 'graphics.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['544']."', 'hardware.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['545']."', 'journal.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['546']."', 'members.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['547']."', 'mods.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['548']."', 'movies.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['549']."', 'network.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['550']."', 'news.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['551']."', 'php-fusion.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['552']."', 'security.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['553']."', 'software.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['554']."', 'themes.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['555']."', 'windows.gif')");
	
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['520']."', 'main_menu_panel', '1', '1', 'file', '0', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['524']."', 'welcome_message_panel', '2', '1', 'file', '0', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['526']."', 'user_info_panel', '4', 1, 'file', '0', '0', '1')");

			$result = dbquery("INSERT INTO ".$db_prefix."modules (mod_title, mod_folder, mod_version) VALUES ('Main menu panel', 'main_menu_panel', '1.0.0')");
			$result = dbquery("INSERT INTO ".$db_prefix."modules (mod_title, mod_folder, mod_version) VALUES ('Advanced login panel', 'user_info_panel', '1.0.0')");
			$result = dbquery("INSERT INTO ".$db_prefix."modules (mod_title, mod_folder, mod_version) VALUES ('Welcome message panel', 'welcome_message_panel', '1.0.0')");

			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['500']."', 'index.php', '0', '1', '0', '1', 'main_menu_panel')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['501']."', 'articles.php', '0', '1', '0', '2', 'main_menu_panel')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['502']."', 'downloads.php', '0', '1', '0', '3', 'main_menu_panel')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['503']."', 'faq.php', '0', '1', '0', '4', 'main_menu_panel')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['504']."', 'forum/index.php', '0', '1', '0', '5', 'main_menu_panel')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['507']."', 'weblinks.php', '0', '1', '0', '6', 'main_menu_panel')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['494']."', 'news_cats.php', '0', '1', '0', '7', 'main_menu_panel')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['505']."', 'contact.php', '0', '1', '0', '8', 'main_menu_panel')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['509']."', 'search.php', '0', '1', '0', '9', 'main_menu_panel')");

			$result = dbquery("INSERT INTO ".$db_prefix."forum_poll_settings (forum_id, enable_polls, create_permissions, vote_permissions, guest_permissions, require_approval, lock_threads, option_max, option_show, option_increment, duration_min, duration_max, hide_poll) VALUES ('0', '1', 'G101', 'G101', '0', '0', '0', '10', '5', '5', '86400', '0', '1')");

			$message = $locale['580'];
		}
		break;
}

if (isset($message)) $variables['message'] = $message;
$variables['error'] = $error;

// define the setup body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'setup', 'template' => 'main.setup.tpl', 'locale' => PATH_LOCALE.$localeset."/setup.php");
$template_variables['setup'] = $variables;

load_templates('body', '');

// close the database connection
@mysql_close();
?>