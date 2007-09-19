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
/*---------------------------------------------------+
| mySQL database functions
+----------------------------------------------------*/
function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br />".mysql_errno()." : ".mysql_error()."</div>");
	} elseif (!$db_select) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br />".mysql_errno()." : ".mysql_error()."</div>");
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
define('IN_FUSION', true);			

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
"."$"."db_prefix="."\"".$_POST['db_prefix']."\""."
// user database settings
"."$"."user_db_host="."\"".$_POST['db_host']."\"".";
"."$"."user_db_user="."\"".$_POST['db_user']."\"".";
"."$"."user_db_pass="."\"".$_POST['db_pass']."\"".";
"."$"."user_db_name="."\"".$_POST['db_name']."\"".";
"."$"."user_db_prefix="."\"".$_POST['db_prefix']."\""."
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
			die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to run PLi-Fusion setup: A valid configuration exists.</b><br />Please consult the documentation on how to rerun the setup.</div>");
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
		if (!is_writable("config.php")) $permissions .= "config.php" . "<br />";
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
		if ($error == "") {
			$result = dbquery("INSERT INTO ".$db_prefix."settings (sitename, siteurl, sitebanner, siteemail, siteusername, siteintro, 
				description, keywords, footer, opening_page, news_headline, news_columns, news_items, locale, theme, shortdate, longdate, 
				forumdate, subheaderdate, timeoffset, numofthreads, attachments, attachmax, attachtypes, thread_notify, enable_registration, 
				email_verification, admin_activation, display_validation, validation_method, thumb_w, thumb_h, photo_w, photo_h, photo_max_w, 
				photo_max_h, photo_max_b, thumb_compression, thumbs_per_row, thumbs_per_page, tinymce_enabled, smtp_host, smtp_username, 
				smtp_password, bad_words_enabled, bad_words, bad_word_replace, guestposts, numofshouts, flood_interval, counter, max_users, 
				max_users_datestamp, version, revision, remote_stats, maintenance, maintenance_message, donate_forum_id, forum_flags, 
				newsletter_email, pm_inbox, pm_savebox, pm_sentbox) 
			VALUES ('PLi-Fusion CMS Powered Website', 'http://www.yourdomain.com/', 'images/banner.gif', 'webmaster@yourdomain.com', '$username', 
				'<center>Welcome to www.yourdomain.com</center>', '', '', '<center>Copyright &copy; 2007 PLi-Fusion</center>', 'news.php', 0, 1, 4, 
				'$localeset', 'PLiTheme', '%d/%m/%Y %H:%M', '%B %d %Y %H:%M:%S', '%d-%m-%Y %H:%M', '%B %d %Y %H:%M:%S', '0', 5, 0, 150000, 
				'.exe,.com,.bat,.js,.htm,.html,.shtml,.php,.php3', 0, 1, 1, 0, 1, 'image', 100, 100, 400, 300, 1800, 1600, 150000, 'gd2', 
				4, 12, 1, '', '', '', 0, '', '[censored]', 0, 10, 15, 0, 0, ".time().", ".PLI_VERSION.", ".PLI_REVISION.", 0, 0, '', 0, 1, 'noreply@yourdomain.com', '20', '20', '20')");

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
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S5', 'photoalbums.gif', '".$locale['491']."', 'settings_photo.php', 3)");
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S6', 'settings_misc.gif', '".$locale['492']."', 'settings_misc.php', 3)");
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S7', 'settings_pm.gif', '".$locale['493']."', 'settings_messages.php', 3)");
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S8', 'settings_lang.gif', '".$locale['459']."', 'settings_languages.php', 3)");
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SL', 'site_links.gif', '".$locale['481']."', 'site_links.php', 3)");
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('U',  'upgrade.gif', '".$locale['483']."', 'upgrade.php', 3)");
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UG', 'user_groups.gif', '".$locale['484']."', 'user_groups.php', 2)");
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UR', 'submissions.gif', '".$locale['496']."', 'redirects.php', 1)");
				$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('wE', 'adverts.gif', '".$locale['498']."', 'adverts.php', 1)");
	
				$result = dbquery("INSERT INTO ".$db_prefix."custom_pages (page_id, page_title, page_access, page_content, page_allow_comments, page_allow_ratings) VALUES (0, '404 Error Page', '', 0, '".mysql_escape_string("<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" align=\"center\"> <tbody><tr><td width=\"10\"> </td><td><div align=\"center\"><font size=\"6\"><span class=\"shoutboxname\"><br />404 - Page Not Found</span><br /></font></div><br /><br /><hr width=\"90%\" size=\"2\" /><br /><br /><div align=\"center\">".$locale['560']."<br /></div><br /><div align=\"center\">".$locale['561']."<br /></div><br /><div align=\"center\">".$locale['562']."<br /></div><br /><br /><hr width=\"90%\" size=\"2\" /><br /><br /><div align=\"center\">".$locale['563']."<br /></div><br /><div align=\"center\">".$locale['564']."</div></td><td width=\"10\"> </td></tr></tbody></table><br />")."', 0, 0)");

				$result = dbquery("SELECT admin_rights FROM ".$db_prefix."admin");
				$adminrights = "";
				while ($data = dbarray($result)) {
					$adminrights = ($adminrights == "" ? "" : ".") . $data['admin_rights'];
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
		
				$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['520']."', 'navigation_panel', '', '1', '1', 'file', '0', '0', '1')");
				$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['524']."', 'welcome_message_panel', '', '2', '1', 'file', '0', '0', '1')");
				$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['526']."', 'user_info_panel', '', '3', 1, 'file', '0', '0', '1')");

				$result = dbquery("INSERT INTO ".$db_prefix."modules (mod_title, mod_folder, mod_version) VALUES ('Main menu navigation panel', 'navigation_panel', '1.0.0')");
				$result = dbquery("INSERT INTO ".$db_prefix."modules (mod_title, mod_folder, mod_version) VALUES ('Advanced login panel', 'user_info_panel', '1.0.0')");
				$result = dbquery("INSERT INTO ".$db_prefix."modules (mod_title, mod_folder, mod_version) VALUES ('Welcome message panel', 'welcome_message_panel', '1.0.0')");

				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['500']."', 'index.php', '0', '1', '0', '1', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['501']."', 'articles.php', '0', '1', '0', '2', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['502']."', 'downloads.php', '0', '1', '0', '3', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['503']."', 'faq.php', '0', '1', '0', '4', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['504']."', 'forum/index.php', '0', '1', '0', '5', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['507']."', 'weblinks.php', '0', '1', '0', '6', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['494']."', 'news_cats.php', '0', '1', '0', '7', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['505']."', 'contact.php', '0', '1', '0', '8', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['509']."', 'search.php', '0', '1', '0', '9', 'navigation_panel')");
				$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".$locale['508']."', 'administration/index.php', '0', '1', '0', '10', 'navigation_panel')");

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