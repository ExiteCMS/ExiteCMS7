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

// to execute "upgrade rev files" compatible database commands
function dbcommands($cmdarray, $db_prefix) {

	// make sure an array is passed
	if (!is_array($cmdarray)) return false;

	// process the commands
	foreach ($cmdarray as $cmd) {

		// skip empty or invalid entries
		if (!is_array($cmd) || count($cmd) == 0) continue;

		// we only support command type='db' here
		if (!isset($cmd['type']) || $cmd['type'] != "db") continue;
		
		// put the correct prefix in place and execute the command
		$result = dbquery(str_replace('##PREFIX##', $db_prefix, $cmd['value']));
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
	if (is_dir($folder)) {
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
	}
	return $res;
}

/*---------------------------------------------------+
| load a locale file
+----------------------------------------------------*/
function locale_load($locale_name) {

	global $settings, $locale, $db_prefix;

	$locales_file = PATH_ROOT."files/locales/".(defined('LP_LOCALE')?LP_LOCALE:"en").".".$locale_name.".php";
	if (file_exists($locales_file)) {
		require $locales_file;
	}
	return;
}

/*---------------------------------------------------+
| setup main code starts here
+----------------------------------------------------*/

// absolute path definitions
define("PATH_ROOT", dirname(__FILE__).'/');
define("PATH_ADMIN", PATH_ROOT."administration/");
define("PATH_THEMES", PATH_ROOT."themes/");
define("PATH_THEME", PATH_ROOT."themes/ExiteCMS/");
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
define("PATH_ATTACHMENTS", PATH_ROOT."files/attachments");

define("FUSION_SELF", isset($_SERVER['REDIRECT_URL']) && $_SERVER['REDIRECT_URL'] != "" ? basename($_SERVER['REDIRECT_URL']) : basename($_SERVER['PHP_SELF']));
define('INIT_CMS_OK', true);
define('CMS_CLI', true);
define('CMS_SETUP', true);

// error tracking
$error = "";

// verify the locale
$localeset = isset($_GET['localeset']) ? $_GET['localeset'] : "";
if (!file_exists(PATH_ADMIN."tools/language_pack_".$localeset.".php")) {
	// not found? Load the english one instead
	$localeset = "English";
	if (!file_exists(PATH_ADMIN."tools/language_pack_".$localeset.".php")) {
		// not found either? bail out!
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run the ExiteCMS setup: No suitable language pack found.</b><br />Please consult the documentation on how to install a language pack.</div>");
	}
}
// load the language pack file, to get some initial info about the language
require PATH_ADMIN."tools/language_pack_".$localeset.".php";

// define some of the website settings for the template engine
$settings = array("locale" => LP_LOCALE, "theme" => "ExiteCMS");

// temp storage for template variables
$variables = array();

$variables['localeset'] = LP_LANGUAGE;
$variables['charset'] = "iso-8859-1";

// parameter validation
$step = (isset($_GET['step']) ? $_GET['step'] : "0");
$variables['step'] = $step;

// check if the cache directories are writeable
if (!is_writable(PATH_ROOT."files/cache")) {
	die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run the ExiteCMS setup: The cache directory is not writeable.</b><br />Please consult the documentation on how to define the proper file rights.</div>");
}
if (!is_writable(PATH_ROOT."files/tplcache")) {
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
	@rename(PATH_ROOT."config.def", PATH_ROOT."config.php");
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
locale_load("main.setup");

// process the different setup steps
switch($step) {
	case "0":
		// if the config file already exists, bail out
		if (file_exists(PATH_ROOT."config.php") && filesize(PATH_ROOT."config.php")) {
			die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run the ExiteCMS setup: A valid configuration exists.</b><br />Please consult the documentation on how to rerun the setup.</div>");
		}
		// check if the config template exists
		if (!file_exists(PATH_ROOT."config.def")) {
			die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run the ExiteCMS setup: The configuration template file is missing.</b><br />Please reinstall ExiteCMS.</div>");
		}
		// create a list of available locales
		$files = makefilelist(PATH_ADMIN."tools/", ".|..", true, "files");
		$locales = array();
		foreach ($files as $file) {
			if (substr($file,0,14) == "language_pack_") $locales[] = substr($file,14,-4);
		}
		$variables['locale_files'] = $locales;
		// check if all required directories are writable
		$permissions = "";
		if (!is_writable(PATH_IMAGES)) $permissions .= PATH_IMAGES . "<br />";
		if (!is_writable(PATH_IMAGES_A)) $permissions .= PATH_IMAGES_A . "<br />";
		if (!is_writable(PATH_IMAGES_AV)) $permissions .= PATH_IMAGES_AV . "<br />";
		if (!is_writable(PATH_IMAGES_N)) $permissions .= PATH_IMAGES_N . "<br />";
		if (!is_writable(PATH_ATTACHMENTS)) $permissions .= PATH_ATTACHMENTS . "<br />";
		if (!is_writable("config.def")) {
			$permissions .= "Configuration Template" . "<br />";
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
		$password = md5(md5($password1));

		require_once PATH_INCLUDES."dbsetup_include.php";

		if ($error == "") {

			// add records to the admin table
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('A',  'articles.gif', '".$locale['462']."', 'articles.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AC', 'article_cats.gif', '".$locale['461']."', 'article_cats.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AD', 'admins.gif', '".$locale['460']."', 'administrators.php', 2)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('B',  'blacklist.gif', '".$locale['463']."', 'blacklist.php', 2)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('BG',  'blogs.gif', '".$locale['473']."', 'blogs.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('C',  '', '".$locale['464']."', 'reserved', 2)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('CP', 'c-pages.gif', '".$locale['465']."', 'custom_pages.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('D',  'dl.gif', '".$locale['468']."', 'downloads.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DB', 'db_backup.gif', '".$locale['466']."', 'db_backup.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DC', 'dl_cats.gif', '".$locale['467']."', 'download_cats.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('F',  'forums.gif', '".$locale['470']."', 'forums.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FQ', 'faq.gif', '".$locale['469']."', 'faq.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('I',  'modules.gif', '".$locale['472']."', 'modules.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IM', 'images.gif', '".$locale['471']."', 'images.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IP', '', '".$locale['473']."', 'reserved', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('M',  'members.gif', '".$locale['474']."', 'members.php', 2)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('N',  'news.gif', '".$locale['475']."', 'news.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('NC', 'news_cats.gif', '".$locale['494']."', 'news_cats.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('P',  'panels.gif', '".$locale['476']."', 'panels.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PI', 'phpinfo.gif', '".$locale['478']."', 'phpinfo.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PO', 'polls.gif', '".$locale['479']."', 'forum_polls.php', 1)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S1', 'settings.gif', '".$locale['487']."', 'settings_main.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S2', 'settings_time.gif', '".$locale['488']."', 'settings_time.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S3', 'settings_forum.gif', '".$locale['489']."', 'settings_forum.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S4', 'registration.gif', '".$locale['490']."', 'settings_registration.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S6', 'settings_misc.gif', '".$locale['492']."', 'settings_misc.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S7', 'settings_pm.gif', '".$locale['493']."', 'settings_messages.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S8', 'settings_lang.gif', '".$locale['459']."', 'settings_languages.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SL', 'site_links.gif', '".$locale['481']."', 'site_links.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('T',  'tools.gif', '".$locale['495']."', 'tools.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('U',  'upgrade.gif', '".$locale['483']."', 'upgrade.php', 3)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UG', 'user_groups.gif', '".$locale['484']."', 'user_groups.php', 2)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UR', 'submissions.gif', '".$locale['496']."', 'redirects.php', 1)");
			$result = dbcommands($commands, $db_prefix);

			// add the default 404 page to the custom pages table
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##custom_pages (page_title, page_access, page_content, page_allow_comments, page_allow_ratings) VALUES ('404 Error Page', 0, '".mysql_escape_string("<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" align=\"center\"> <tbody><tr><td width=\"10\"> </td><td><div align=\"center\"><font size=\"6\"><span class=\"shoutboxname\"><br />404 - Page Not Found</span><br /></font></div><br /><br /><hr width=\"90%\" size=\"2\" /><br /><br /><div align=\"center\">".$locale['560']."<br /></div><br /><div align=\"center\">".$locale['561']."<br /></div><br /><div align=\"center\">".$locale['562']."<br /></div><br /><br /><hr width=\"90%\" size=\"2\" /><br /><br /><div align=\"center\">".$locale['563']."<br /></div><br /><div align=\"center\">".$locale['564']."</div></td><td width=\"10\"> </td></tr></tbody></table><br />")."', 0, 0)");
			$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##custom_pages SET page_id = 0");
			$result = dbcommands($commands, $db_prefix);

			// create the admin rights field for the webmaster, based on all admin modules just inserted
			$result = dbquery("SELECT admin_rights FROM ".$db_prefix."admin");
			$adminrights = "";
			while ($data = dbarray($result)) {
				$adminrights .= ($adminrights == "" ? "" : ".") . $data['admin_rights'];
			}
					
			// add the webmaster to the users table
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##users (user_name, user_password, user_webmaster, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_forum_fullscreen, user_theme, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status) VALUES ('$username', '$password', '1', '$email', '1', '', '0000-00-00', '', '', '', '', '', '0', 'Default', '0', '', '', '0', '".time()."', '0', '0.0.0.0', '".$adminrights."', '', '103', '0')");
			$result = dbcommands($commands, $db_prefix);
	
			// add the default private messages configuration
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##pm_config (user_id, pmconfig_save_sent, pmconfig_read_notify, pmconfig_email_notify, pmconfig_auto_archive ) VALUES ('0', '0', '1', '0', '90')");
			$result = dbcommands($commands, $db_prefix);
		
			// add the default news categories 
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['540']."', 'bugs.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['541']."', 'downloads.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['542']."', 'games.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['543']."', 'graphics.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['544']."', 'hardware.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['545']."', 'journal.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['546']."', 'members.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['547']."', 'mods.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['548']."', 'movies.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['549']."', 'network.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['550']."', 'news.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['552']."', 'security.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['553']."', 'software.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['554']."', 'themes.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['555']."', 'windows.gif')");
			$result = dbcommands($commands, $db_prefix);
	
			// add the standard modules to make them pre-installed
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##modules (mod_title, mod_folder, mod_version) VALUES ('Main menu panel', 'main_menu_panel', '1.0.0')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##modules (mod_title, mod_folder, mod_version) VALUES ('Advanced login panel', 'user_info_panel', '1.0.0')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##modules (mod_title, mod_folder, mod_version) VALUES ('Welcome message panel', 'welcome_message_panel', '1.0.0')");
			$result = dbcommands($commands, $db_prefix);

			// and activate the panels of these modules
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['520']."', 'main_menu_panel', '1', '1', 'file', '0', '0', '1')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['524']."', 'welcome_message_panel', '2', '1', 'file', '0', '0', '1')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['526']."', 'user_info_panel', '4', 1, 'file', '0', '0', '1')");
			$result = dbcommands($commands, $db_prefix);

			// add the default menu links 
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['500']."', 'index.php', '0', '1', '0', '1', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['501']."', 'article_cats.php', '0', '1', '0', '2', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['502']."', 'downloads.php', '0', '1', '0', '3', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['503']."', 'faq.php', '0', '1', '0', '4', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['504']."', 'forum/index.php', '0', '1', '0', '5', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['505']."', 'news_cats.php', '0', '1', '0', '6', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['473']."', 'blogs.php', '0', '1', '0', '7', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['506']."', 'contact.php', '0', '1', '0', '8', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['507']."', 'search.php', '0', '1', '0', '9', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['508']."', 'register.php', '100', '2', '0', '10', 'main_menu_panel')");
			$result = dbcommands($commands, $db_prefix);

			// add the default forum poll settings
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##forum_poll_settings (forum_id, enable_polls, create_permissions, vote_permissions, guest_permissions, require_approval, lock_threads, option_max, option_show, option_increment, duration_min, duration_max, hide_poll) VALUES ('0', '1', 'G101', 'G101', '0', '0', '0', '10', '5', '5', '86400', '0', '1')");
			$result = dbcommands($commands, $db_prefix);

			// load the selected language pack, and populate the locale and locales tables
			define('CMS_SETUP_LOAD', true);
			require PATH_ADMIN."tools/language_pack_".$localeset.".php";

			$message = $locale['580'];
		}
		break;
}

if (isset($message)) $variables['message'] = $message;
$variables['error'] = $error;

// define the setup body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'setup', 'template' => 'main.setup.tpl', 'locale' => "main.setup");
$template_variables['setup'] = $variables;

load_templates('body', '');

// close the database connection
@mysql_close();
?>