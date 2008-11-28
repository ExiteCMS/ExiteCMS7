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

/*---------------------------------------------------+
| mySQL database functions
+----------------------------------------------------*/
function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	global $locale;

	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		terminate($locale['401'], mysql_errno()." : ".mysql_error());
	} elseif (!$db_select) {
		terminate($locale['402'], mysql_errno()." : ".mysql_error());
	}
}

function dbquery($query) {

	$result = @mysql_query($query);
	if (!$result) {
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
	} else {
		die("locales file $locales_file not found!");
	}
	return;
}

/*---------------------------------------------------+
| load a locale file
+----------------------------------------------------*/
function terminate($error, $tip="", $wiki=0) {
	global $locale;

	$msg = "<div style='font-family:Verdana;font-size:14px;text-align:center;font-weight:bold;'><b>".(empty($locale['403'])?"Unable to run the ExiteCMS setup":$locale['403']).":<br /><br /><font style='color:red;'>";
	$msg .= $error."</font></b><br /><br />";
	if ($wiki) {
		if (empty($locale['404'])) {
			$msg .= "Please consult our <a href='http://www.exitecms.org/modules/wiki/index.php?wakka=Setup'>Wiki</a> ";
		} else {
			$msg .= sprintf($locale['404'], "http://www.exitecms.org/modules/wiki/index.php?wakka=Setup");
		}
	}
	$msg .=" ". $tip."</div>";
	die($msg);
}

/*---------------------------------------------------+
| setup main code starts here
+----------------------------------------------------*/

// absolute path definitions
define("PATH_ROOT", dirname(__FILE__).'/');
define("PATH_ADMIN", PATH_ROOT."administration/");
define("PATH_FILES", PATH_ROOT."files/");
define("PATH_THEMES", PATH_ROOT."themes/");
define("PATH_THEME", PATH_ROOT."themes/ExiteCMS/");
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

// temp storage for template variables
$variables = array();

// used for error tracking
$error = "";

// verify the selected locale
$localeset = isset($_GET['localeset']) ? $_GET['localeset'] : "";
if (!file_exists(PATH_ADMIN."tools/language_pack_".$localeset.".php")) {
	// not found? Load the english one instead
	$localeset = "English";
	if (!file_exists(PATH_ADMIN."tools/language_pack_".$localeset.".php")) {
		// not found either? bail out!
		terminate("No suitable language pack found.", "Please consult the documentation on how to install a language pack.");
	}
}

// load the language pack file, to get some initial info about the language
require_once PATH_ADMIN."tools/language_pack_".$localeset.".php";

// load the locale for this module
$locale = array();
locale_load("main.setup");

// define some of the website settings for the template engine
$settings = array("locale" => LP_LOCALE, "theme" => "ExiteCMS");
$variables['localeset'] = LP_LANGUAGE;
$variables['charset'] = LP_CHARSET;

// parameter validation
$step = (isset($_POST['step']) ? $_POST['step'] : "0");

// check if the cache directories are writeable
if (!is_writable(PATH_FILES."cache")) {
	terminate($locale['405'], $locale['406'], true);
}
if (!is_writable(PATH_FILES."tplcache")) {
	terminate($locale['407'], $locale['406'], true);
}
if (!is_writable(PATH_FILES."locales")) {
	terminate($locale['416'], $locale['406'], true);
}

// get the FQFN of the config file
@include_once PATH_ROOT."configpath.php";
if (substr(CONFIG_PATH,0,1) == "/") {
	define('CONFIG_FILE', str_replace("//", "/", CONFIG_PATH."/config.php"));
} else {
	define('CONFIG_FILE', str_replace("//", "/", PATH_ROOT.CONFIG_PATH."/config.php"));
}

// first part in step0: check the config file
if ($step == "0") {
	// file MUST NOT exist...
	if (file_exists(CONFIG_FILE)) {
		terminate($locale['408'], $locale['409'], true);
	}
	// ... and the directory MUST be writeable!
	if(!is_writable(dirname(CONFIG_FILE))) {
		terminate($locale['415'], $locale['406'], true);
	}
}

// first part in step1: validate the input, and create the config file
if ($step == "1") {
	// verify the user input: hostname
	$db_host = isset($_POST['db_host']) ? stripinput($_POST['db_host']) : "";
	$variables['db_host'] = $db_host;
	if (empty($db_host) || !preg_match("/^[-0-9\.A-Z_@]+$/i", $db_host)) {
		$error .= $locale['417']."<br /><br />\n";
	}
	// verify the user input: username
	$db_user = isset($_POST['db_user']) ? stripinput($_POST['db_user']) : "";
	$variables['db_user'] = $db_user;
	if (empty($db_user) || !preg_match("/^[-0-9A-Z_@]+$/i", $db_user)) {
		$error .= $locale['418']."<br /><br />\n";
	}
	// verify the user input: password
	$db_pass = isset($_POST['db_pass']) ? stripinput($_POST['db_pass']) : "";
	$variables['db_pass'] = $db_pass;
	if (empty($db_pass) || !preg_match("/^[-0-9A-Z_@]+$/i", $db_pass)) {
		$error .= $locale['419']."<br /><br />\n";
	}
	// verify the user input: database name
	$db_name = isset($_POST['db_name']) ? stripinput($_POST['db_name']) : "";
	$variables['db_name'] = $db_name;
	if (empty($db_name) || !preg_match("/^[-0-9A-Z_@]+$/i", $db_name)) {
		$error .= $locale['427']."<br /><br />\n";
	}
	// verify the user input: table prefix
	$db_prefix = isset($_POST['db_prefix']) ? stripinput($_POST['db_prefix']) : "";
	$variables['db_prefix'] = $db_prefix;
	if (!empty($db_prefix) && !preg_match("/^[A-Z0-9][-0-9A-Z_@]*$/i", $db_prefix)) {
		$error .= $locale['428']."<br /><br />\n";
	}
	// verify a connection to the database server can be made
	if (empty($error)) {
		$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
		if (!$db_connect) {
			$error .= $locale['401']."<br />".$locale['429']."<br /><br />\n";
		}
	}
	// verify if the database exists on the server
	if (empty($error)) {
		$db_select = @mysql_select_db($db_name);
		if (!$db_select) {
			$error .= sprintf($locale['434'],$db_name)."<br /><br />\n";
		}
	}
	// verify if the given user has create table on the database
	if (empty($error)) {
		$result = dbquery("CREATE TABLE ".$db_prefix."___test (test TINYINT(1) NOT NULL) ENGINE = MYISAM");
		if (!$result) {
			$error .= sprintf($locale['435'],$db_name)."<br /><br />\n";
		} else {
			$result = dbquery("DROP TABLE ".$db_prefix."___test");
		}
	}
	// if no errors were detected, create the config file
	if ($error == "") {
		$config = "<?php
// global database settings
"."$"."db_host="."\"".$_POST['db_host']."\"".";
"."$"."db_user="."\"".$_POST['db_user']."\"".";
"."$"."db_pass="."\"".$_POST['db_pass']."\"".";
"."$"."db_name="."\"".$_POST['db_name']."\"".";
"."$"."db_prefix="."\"".$_POST['db_prefix']."\"".";

// user database settings (may be shared with another ExiteCMS DB)
"."$"."user_db_host="."\"".$_POST['db_host']."\"".";
"."$"."user_db_user="."\"".$_POST['db_user']."\"".";
"."$"."user_db_pass="."\"".$_POST['db_pass']."\"".";
"."$"."user_db_name="."\"".$_POST['db_name']."\"".";
"."$"."user_db_prefix="."\"".$_POST['db_prefix']."\"".";
?>";
		$temp = fopen(CONFIG_FILE,"w");
		if (!fwrite($temp, $config)) {
			$error .= $locale['430']."<br /><br />";
			fclose($temp);
		} else {
			fclose($temp);
		}
	}
	if (!empty($error)) {
		$step = 0;
	}
}

require_once PATH_ROOT."includes/theme_functions.php";

// process the different setup steps
switch($step) {
	case "0":
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
		if ($permissions == "") {
			$variables['write_check'] = true; 
		} else { 
			$variables['write_check'] = false;
			$error = "<b>".$locale['412']."</b><br /><br /><span style='text-align:left'>".$permissions."</span><br /><b>".$locale['413']."</b>";
		}
		break;
	case "1":
		if ($error == "") {
			require_once CONFIG_FILE;
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
		require_once CONFIG_FILE;
		$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
		$basedir = substr($_SERVER['PHP_SELF'], 0, -9);
		$username = isset($_POST['username']) ? stripinput($_POST['username']) : "";
		$variables['username'] = $username;
		$password1 = isset($_POST['password1']) ? stripinput($_POST['password1']) : "";
		$variables['password1'] = $password1;
		$password2 = isset($_POST['password2']) ? stripinput($_POST['password2']) : "";
		$variables['password2'] = $password2;
		$email = isset($_POST['email']) ? stripinput($_POST['email']) : "";
		$variables['email'] = $email;
		if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['450']."<br /><br />\n";
		if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
			if ($password1 != $password2) $error .= $locale['451']."<br /><br />\n";
		} else {
			$error .= $locale['452']."<br /><br />\n";
		}
	 	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
			$error .= $locale['453']."<br /><br />\n";
		}
		// double-hash the password to prevent hash table lookups
		$password = md5(md5($password1));

		require_once PATH_INCLUDES."dbsetup_include.php";

		if ($error != "") {

			$step = 1;

		} else {

			// update installation specific configuration items
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$basedir."' WHERE cfg_name = 'siteurl'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$username."' WHERE cfg_name = ''siteusername");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$localeset."' WHERE cfg_name = 'locale'");
			$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".LP_LOCALE."' WHERE cfg_name = 'default_locale'");
 
			// create the admin rights field for the webmaster, based on all admin modules available
			$result = dbquery("SELECT admin_rights FROM ".$db_prefix."admin");
			$adminrights = "";
			while ($data = dbarray($result)) {
				$adminrights .= ($adminrights == "" ? "" : ".") . $data['admin_rights'];
			}
					
			// add the webmaster to the users table
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##users (user_name, user_password, user_webmaster, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_forum_fullscreen, user_theme, user_locale, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status) VALUES ('$username', '$password', '1', '$email', '1', '', '0000-00-00', '', '', '', '', '', '0', 'Default', '".LP_LOCALE."', '0', '', '', '0', '".time()."', '0', '0.0.0.0', '".$adminrights."', '', '103', '0')");
			$result = dbcommands($commands, $db_prefix);
	
			// add the default private messages configuration
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##pm_config (user_id, pmconfig_save_sent, pmconfig_read_notify, pmconfig_email_notify, pmconfig_auto_archive ) VALUES ('0', '0', '1', '0', '90')");
			$result = dbcommands($commands, $db_prefix);
		
			// add the default news categories 
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['540'])."', 'bugs.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['541'])."', 'downloads.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['542'])."', 'games.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['543'])."', 'graphics.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['544'])."', 'hardware.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['545'])."', 'journal.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['546'])."', 'members.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['547'])."', 'mods.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['548'])."', 'movies.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['549'])."', 'network.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['550'])."', 'news.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['552'])."', 'security.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['553'])."', 'software.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['554'])."', 'themes.gif')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##news_cats (news_cat_name, news_cat_image) VALUES ('".mysql_escape_string($locale['555'])."', 'windows.gif')");
			$result = dbcommands($commands, $db_prefix);
	
			// activate the panels of core modules
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".mysql_escape_string($locale['520'])."', 'main_menu_panel', '1', '1', 'file', '0', '0', '1')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".mysql_escape_string($locale['524'])."', 'welcome_message_panel', '2', '1', 'file', '0', '0', '1')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##panels (panel_name, panel_filename, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".mysql_escape_string($locale['526'])."', 'user_info_panel', '4', 1, 'file', '0', '0', '1')");
			$result = dbcommands($commands, $db_prefix);

			// add the default menu links 
			$commands = array();
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['500'])."', 'index.php', '0', '1', '0', '1', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['501'])."', 'article_cats.php', '0', '1', '0', '2', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['502'])."', 'downloads.php', '0', '1', '0', '3', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['503'])."', 'faq.php', '0', '1', '0', '4', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['504'])."', 'forum/index.php', '0', '1', '0', '5', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['505'])."', 'news_cats.php', '0', '1', '0', '6', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['473'])."', 'blogs.php', '0', '1', '0', '7', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['482'])."', 'albums.php', '0', '1', '0', '8', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['506'])."', 'contact.php', '0', '1', '0', '9', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['507'])."', 'search.php', '0', '1', '0', '10', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['509'])."', 'reports.php', '0', '1', '0', '11', 'main_menu_panel')");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) VALUES ('".mysql_escape_string($locale['508'])."', 'register.php', '100', '2', '0', '12', 'main_menu_panel')");
			$result = dbcommands($commands, $db_prefix);

			// add the ExiteCMS core search options
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'articles', 'src510', 1, '1.0.0', 1, 0)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'news', 'src511', 1, '1.0.0', 1, 0)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'forumposts', 'src512', 1, '1.0.0', 1, 0)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'forumattachments', 'src513', 1, '1.0.0', 1, 0)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'downloads', 'src514', 1, '1.0.0', 1, 0)");
			$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'members', 'src515', 0, '1.0.0', 1, 0)");

			// add the ExiteCMS core report options
			
			/* NOT IMPLEMENTED YET */

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

$variables['step'] = $step;

// define the setup body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'setup', 'template' => 'main.setup.tpl', 'locale' => "main.setup");
$template_variables['setup'] = $variables;

load_templates('body', '');

// close the database connection
@mysql_close();
?>
