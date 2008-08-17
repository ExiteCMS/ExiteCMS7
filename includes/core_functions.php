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
if (eregi("core_functions.php", $_SERVER['PHP_SELF'])) die();

// disable error reporting, we don't want to give anything away
error_reporting(E_USER_ERROR);

// code to calculate the page loading time, this can be used
// to show statistics in theme.php, p.e. when generating the
// code for the page footer
$_loadstats = array();
$_loadtime = explode(" ", microtime());
$_loadtime = $_loadtime[1] + $_loadtime[0];
$_loadstats['time'] = -$_loadtime;
$_loadstats['querytime'] = 0;
$_loadstats['queries'] = 0;
$_loadstats['selects'] = 0;
$_loadstats['inserts'] = 0;
$_loadstats['deletes'] = 0;
$_loadstats['updates'] = 0;
$_loadstats['others'] = 0;
$_loadstats['compression'] = (ini_get('zlib.output_compression') == "1");
unset($_loadtime);

// if register_globals is turned off, extract super globals (php 4.2.0+)
// TODO - WANWIZARD - 20070701 - NEED TO GET RID OF THIS !!!
if (ini_get('register_globals') != 1) {
	if ((isset($_POST) == true) && (is_array($_POST) == true)) extract($_POST, EXTR_OVERWRITE);
	if ((isset($_GET) == true) && (is_array($_GET) == true)) extract($_GET, EXTR_OVERWRITE);
} else {
	// if not, unset all globals created by register_globals!
	$rg = array_keys($_REQUEST);
	foreach($rg as $var) {
		if ($_REQUEST[$var] === $$var) {
//			unset($$var);
		}
	}
}

// prevent any possible XSS attacks via $_GET.
foreach ($_GET as $check_url) {
	// deal with array's in GET parameters
	if (is_array($check_url)) {
		foreach ($check_url as $url_parts) {
			if ((eregi("<[^>]*script*\"?[^>]*>", $url_parts)) || (eregi("<[^>]*object*\"?[^>]*>", $url_parts)) ||
					(eregi("<[^>]*iframe*\"?[^>]*>", $url_parts)) || (eregi("<[^>]*applet*\"?[^>]*>", $url_parts)) ||
					(eregi("<[^>]*meta*\"?[^>]*>", $url_parts)) || (eregi("<[^>]*style*\"?[^>]*>", $url_parts)) ||
					(eregi("<[^>]*form*\"?[^>]*>", $url_parts)) || (eregi("\([^>]*\"?[^)]*\)", $url_parts))) {
				die ();
			}
		}
	} else {
		if ((eregi("<[^>]*script*\"?[^>]*>", $check_url)) || (eregi("<[^>]*object*\"?[^>]*>", $check_url)) ||
				(eregi("<[^>]*iframe*\"?[^>]*>", $check_url)) || (eregi("<[^>]*applet*\"?[^>]*>", $check_url)) ||
				(eregi("<[^>]*meta*\"?[^>]*>", $check_url)) || (eregi("<[^>]*style*\"?[^>]*>", $check_url)) ||
				(eregi("<[^>]*form*\"?[^>]*>", $check_url)) || (eregi("\([^>]*\"?[^)]*\)", $check_url))) {
			die ();
		}
	}
}
unset($check_url);

// disable the standard PHP include path (empty's not accepted?)
ini_set('include_path', '.');

// start Output Buffering
ob_start();

// absolute path definitions
define("PATH_ROOT", realpath(dirname(__FILE__).'/../').'/');
define("PATH_ADMIN", PATH_ROOT."administration/");
define("PATH_THEMES", PATH_ROOT."themes/");
define("PATH_PHOTOS", PATH_ROOT."images/gallery/");
define("PATH_IMAGES", PATH_ROOT."images/");
define("PATH_IMAGES_A", PATH_IMAGES."articles/");
define("PATH_IMAGES_ADS", PATH_IMAGES."advertising/");
define("PATH_IMAGES_AV", PATH_IMAGES."avatars/");
define("PATH_IMAGES_N", PATH_IMAGES."news/");
define("PATH_IMAGES_NC", PATH_IMAGES."news_cats/");
define("PATH_IMAGES_DC", PATH_IMAGES."download_cats/");
define("PATH_INCLUDES", PATH_ROOT."includes/");
define("PATH_GESHI", PATH_INCLUDES."geshi-1.0.8");
define("PATH_MODULES", PATH_ROOT."modules/");
define("PATH_ATTACHMENTS", PATH_ROOT."files/attachments/");
define("PATH_PM_ATTACHMENTS", PATH_ROOT."files/pm_attachments/");

// mark that CMS Engine is properly initialized
define("INIT_CMS_OK", TRUE);

// load the config file
@include_once PATH_ROOT."configpath.php";
if (substr(CONFIG_PATH,0,1) == "/") {
	if(is_file(CONFIG_PATH."/config.php")) {
		@include_once CONFIG_PATH."/config.php";
	}
} else {
	if(is_file(PATH_ROOT.CONFIG_PATH."/config.php")) {
		@include_once PATH_ROOT.CONFIG_PATH."/config.php";
	}
}

// if config.php is absent or empty, bail out with an error
if (!isset($db_name)) terminate('FATAL ERROR: config file is missing. Check our Wiki at http://exitecms.exite.eu on how to run the setup');

// load the database functions, and establish a database connection
require_once PATH_INCLUDES."db_functions.php";

// fetch the configuration from the database and store them in the $settings variable
$settings = array();
$result = dbquery("SELECT * FROM ".$db_prefix."configuration");
while ($data = dbarray($result)) {
	$settings[$data['cfg_name']] = $data['cfg_value'];
}

// define the default sitebanner
$settings['sitebanner'] = "site_logo.gif";

// backward compatibility: make sure siteurl contains a relative path!
$settings['siteurl'] = strstr(str_replace("http://", "", str_replace("https://", "", $settings['siteurl'])), "/");

// define the website basedir (relative path from the root)
define ("BASEDIR", $settings['siteurl']);

// now make the siteurl fully qualified using the current server host info
$settings['siteurl'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on") ? "https://" : "http://").$_SERVER['HTTP_HOST'].$settings['siteurl'];

// calculate the correct forum_unread_threshold for this session
$settings['unread_threshold'] = time() - 60*60*24*$settings['unread_threshold'];

// URL path definitions relative to BASEDIR
define("ADMIN", BASEDIR."administration/");
define("IMAGES", BASEDIR."images/");
define("IMAGES_A", IMAGES."articles/");
define("IMAGES_ADS", IMAGES."advertising/");
define("IMAGES_AV", IMAGES."avatars/");
define("IMAGES_N", IMAGES."news/");
define("IMAGES_NC", IMAGES."news_cats/");
define("IMAGES_DC", IMAGES."download_cats/");
define("FORUM", BASEDIR."forum/");
define("ATTACHMENTS", BASEDIR."files/attachments/");
define("PM_ATTACHMENTS", BASEDIR."files/pm_attachments/");
define("MODULES", BASEDIR."modules/");
define("INCLUDES", BASEDIR."includes/");
define("PHOTOS", IMAGES."photoalbum/");
define("THEMES", BASEDIR."themes/");

// extract server settings information
if (isset($_SERVER['SERVER_SOFTWARE'])) {

	// Common definitions - CGI mode

	define("CMS_CLI", false);
	$_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? cleanurl($_SERVER['QUERY_STRING']) : "";
	$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? cleanurl($_SERVER['REQUEST_URI']) : "";
	$_SERVER['PHP_SELF'] = $_SERVER['PHP_SELF'];
	$PHP_SELF = cleanurl($_SERVER['PHP_SELF']);
	define("FUSION_REQUEST", isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
	define("FUSION_QUERY", isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "");
	if (basename($_SERVER['PHP_SELF']) == "404handler.php") {
		define("FUSION_SELF", basename(isset($_SERVER['REDIRECT_URL']) && $_SERVER['REDIRECT_URL'] != "" ? $_SERVER['REDIRECT_URL'] : $PHP_SELF));
		define("FUSION_URL", isset($_SERVER['REDIRECT_URL']) && $_SERVER['REDIRECT_URL'] != "" ? $_SERVER['REDIRECT_URL'] : $PHP_SELF);
	} else {
		define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
		define("FUSION_URL", $PHP_SELF);
	}
	define("USER_IP", $_SERVER['REMOTE_ADDR']);

	// start session management
	require_once PATH_INCLUDES."session_functions.php";

} else {

	// Common definitions - CLI mode

	define("CMS_CLI", true);
	define("USER_IP", '0.0.0.0');
}
define("QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));

// Browser window dimensions (assume 1024x768 if no cookies found)
define("BROWSER_WIDTH", isset($_COOKIE['width']) ? $_COOKIE['width'] : 1024);
define("BROWSER_HEIGHT", isset($_COOKIE['height']) ? $_COOKIE['height'] : 768);

// load the user functions
require_once PATH_INCLUDES."user_functions.php";

// set the query log debugging switch, enable error reporting if needed
$_db_log = checkgroup($settings['debug_querylog'], false);

// activate PHP error reporting
if (isset($settings['debug_php_errors']) && $settings['debug_php_errors']) {
	error_reporting(E_ALL);
}

// load the locale functions
require_once PATH_INCLUDES."locale_functions.php";

// load the CMS global locale strings
locale_load("main.global");

// check for upgrades in progress.
if (!eregi("upgrade.php", $_SERVER['PHP_SELF'])) {

	include PATH_ADMIN."upgrade.php";
	//  If so, force a switch to maintenance mode
	if (UPGRADES) $settings['maintenance'] = 2;

	// if not called from the maintenance mode module! (to prevent a loop, endless ;-)
	// check if we need to redirect to maintenance mode (for users) or upgrade (for webmasters)
	if ($settings['maintenance'] && !eregi("maintenance.php", $_SERVER['PHP_SELF'])) {
		if (!iSUPERADMIN) {
			// deny all non-webmasters access to the site
			redirect('maintenance.php?reason='.$settings['maintenance']);
		} else {
			// force webmasters to the upgrade module
//			redirect(ADMIN.'upgrade.php'.$aidlink);
		}
	}
}

// image types we support
$imagetypes = array(".bmp",".gif",".iff",".jpg",".jpeg",".png",".psd",".tiff",".wbmp");

// image types we can generate a thumbnail from
$thumbtypes = array(".gif",".jpg",".jpeg",".png",".bmp", ".psd");

// debug function, handy to print a standard debug text
function _debug($text, $abort=false) {

	if (is_array($text)) {
		echo "<br /><hr /><br /><pre>"; print_r($text); echo "</pre><br /><hr /><br />";
	} elseif (is_object($text)) {
		echo "<br /><hr /><br /><pre>"; print_r($text); echo "</pre><br /><hr /><br />";
	} else {
		echo "<br /><hr /><br /><pre>".$text."</pre><br /><hr /><br />";
	}
	if ($abort) die();
}

/*---------------------------------------------------+
| core_functions include - general functions below   |
+----------------------------------------------------*/

// Redirect browser using the header function
function redirect($location, $type="header") {
	global $locale;

	// get rid of &amp; in the location
	$location = str_replace("&amp;", "&", $location);

	// make sure the session is properly closed before redirecting
	session_write_close();

	if ($type == "header") {
		header("Location: ".$location);
	} else {
		echo "<script type='text/javascript'>document.location.href='".$location."'</script>\n";
		echo sprintf($locale['182'], $location);
	}
	exit;
}

// Fallback to safe area in event of unauthorised access
function fallback($location) {

	// get rid of &amp; in the location
	$location = str_replace("&amp;", "&", $location);
		
	redirect($location, "header");
	exit;
}

// Clean URL Function, prevents entities in server globals
function cleanurl($url) {
	$bad_entities = array("&", "\"", "'", '\"', "\'", "<", ">", "(", ")", "*");
	$safe_entities = array("&amp;", "", "", "", "", "", "", "", "", "");
	$url = str_replace($bad_entities, $safe_entities, $url);
	return $url;
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (QUOTES_GPC) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// stripslash function, only stripslashes if magic_quotes_gpc is on
function stripslash($text) {
	if (QUOTES_GPC) $text = stripslashes($text);
	return $text;
}

// addslash function, add correct number of slashes depending on quotes_gpc
function addslash($text) {
	if (!QUOTES_GPC) {
		$text = addslashes(addslashes($text));
	} else {
		$text = addslashes($text);
	}
	return $text;
}

// htmlentities is too agressive so we use this function
function phpentities($text) {
	$search = array("\"", "'", "\\", "<", ">");
	$replace = array("&quot;", "&#39;", "&#92;", "&lt;", "&gt;");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Trim a line of text to a preferred length
function trimlink($text, $length, $filler="...") {
	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length) $text = substr($text, 0, ($length-3)).$filler;
	$text = str_replace($dec, $enc, $text);
	return $text;
}

// Trim a URI to a preferred length by cutting out the middle (preserve the hostname if possible)
function shortenlink($text, $length, $filler="...") {

	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$returner = str_replace($enc, $dec, $text);
	if (strlen($returner) > $length) {
		$url = preg_match("=[^/]/[^/]=",$returner,$treffer,PREG_OFFSET_CAPTURE);
		$cutpos = $treffer[0][1]+2;
		$part[0] = substr($returner,0,$cutpos);
		$part[1] = substr($returner,$cutpos);
		$strlen1 = $cutpos;
		if ($strlen1 > $length) {
			$returner = substr($returner,0,$length-3).$filler;
		} else {
			$strlen2 = strlen($part[1]);
			$cutpos = $strlen2-($length-3-$strlen1);
			$returner = $part[0].$filler.substr($part[1],$cutpos);
		}
	}
	$returner = str_replace($dec, $enc, $returner);
	return $returner;
}

// Validate numeric input
function isNum($value) {
	return (preg_match("/^[0-9]+$/", $value));
}

// Validate decimal input
function isDec($value) {
	return (preg_match("/^[0-9]+\.[0-9][0-9]$/", $value));
}

// validate an IP address
function isIP($value){
    return preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $value);
}

// validate an URL
function isURL($value, $onlyhttp=false, $schemereq=false) {

	// Build the regex to check the URL
	if ($onlyhttp) {
		$scheme = "(https?)\:\/\/";												// HTTP SCHEMES supported
	} else {
		$scheme = "(https?|s?ftp|mailto|svn|cvs|callto|mms|skype)\:\/\/";		// ALL SCHEMES supported
	}
	if ($schemereq) {
		$urlregex = "^(".$scheme.")";											// scheme
	} else {
		$urlregex = "^(".$scheme.")?";											// scheme (optional)
	}
	$urlregex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";	// USERID + PASSWORD (optional)
	$urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*";							// HOSTNAME or IP
	$urlregex .= "(\:[0-9]{2,5})?";												// PORT (optional)
	$urlregex .= "(\/([a-z0-9+\$_%-~]\.?)+)*\/?";								// PATH (optional)
	$urlregex .= "(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?";						// GET querystring (optional)
	$urlregex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?\$";								// ANCHOR (optional)
	// validate the URL
	return eregi($urlregex, $value);
}

// Parse smiley bbcode into HTML images
function parsesmileys($message) {
	$smiley = array(
		"\:oops\:" => "<img src='".IMAGES."smiley/more/redface.gif' alt='smiley' />",
		"\:doubt\:" => "<img src='".IMAGES."smiley/more/doubt.gif' alt='smiley' />",
		"\:thumbleft" => "<img src='".IMAGES."smiley/more/icon_thumleft.gif' alt='smiley' />",
		"\:thumbright" => "<img src='".IMAGES."smiley/more/icon_thumright.gif' alt='smiley' />",
		"\:smt004" => "<img src='".IMAGES."smiley/more/004.gif' alt='smiley' />",
		"\:smt005" => "<img src='".IMAGES."smiley/more/005.gif' alt='smiley' />",
		"\:smt006" => "<img src='".IMAGES."smiley/more/006.gif' alt='smiley' />",
		"\:smt007" => "<img src='".IMAGES."smiley/more/007.gif' alt='smiley' />",
		"\:smt008" => "<img src='".IMAGES."smiley/more/008.gif' alt='smiley' />",
		"\:smt009" => "<img src='".IMAGES."smiley/more/009.gif' alt='smiley' />",
		"\:smt010" => "<img src='".IMAGES."smiley/more/010.gif' alt='smiley' />",
		"\:smt011" => "<img src='".IMAGES."smiley/more/011.gif' alt='smiley' />",
		"\:smt012" => "<img src='".IMAGES."smiley/more/012.gif' alt='smiley' />",
		"\:smt013" => "<img src='".IMAGES."smiley/more/013.gif' alt='smiley' />",
		"\:smt014" => "<img src='".IMAGES."smiley/more/014.gif' alt='smiley' />",
		"\:smt016" => "<img src='".IMAGES."smiley/more/016.gif' alt='smiley' />",
		"\:smt017" => "<img src='".IMAGES."smiley/more/017.gif' alt='smiley' />",
		"\:smt018" => "<img src='".IMAGES."smiley/more/018.gif' alt='smiley' />",
		"\:smt019" => "<img src='".IMAGES."smiley/more/019.gif' alt='smiley' />",
		"\:smt020" => "<img src='".IMAGES."smiley/more/020.gif' alt='smiley' />",
		"\:smt021" => "<img src='".IMAGES."smiley/more/021.gif' alt='smiley' />",
		"\:smt022" => "<img src='".IMAGES."smiley/more/022.gif' alt='smiley' />",
		"\:smt023" => "<img src='".IMAGES."smiley/more/023.gif' alt='smiley' />",
		"\:smt024" => "<img src='".IMAGES."smiley/more/024.gif' alt='smiley' />",
		"\:smt025" => "<img src='".IMAGES."smiley/more/025.gif' alt='smiley' />",
		"\:smt026" => "<img src='".IMAGES."smiley/more/026.gif' alt='smiley' />",
		"\:smt027" => "<img src='".IMAGES."smiley/more/027.gif' alt='smiley' />",
		"\:smt028" => "<img src='".IMAGES."smiley/more/028.gif' alt='smiley' />",
		"\:smt029" => "<img src='".IMAGES."smiley/more/029.gif' alt='smiley' />",
		"\:smt030" => "<img src='".IMAGES."smiley/more/030.gif' alt='smiley' />",
		"\:smt031" => "<img src='".IMAGES."smiley/more/031.gif' alt='smiley' />",
		"\:smt032" => "<img src='".IMAGES."smiley/more/032.gif' alt='smiley' />",
		"\:smt033" => "<img src='".IMAGES."smiley/more/033.gif' alt='smiley' />",
		"\:smt034" => "<img src='".IMAGES."smiley/more/034.gif' alt='smiley' />",
		"\:smt035" => "<img src='".IMAGES."smiley/more/035.gif' alt='smiley' />",
		"\:smt036" => "<img src='".IMAGES."smiley/more/036.gif' alt='smiley' />",
		"\:smt037" => "<img src='".IMAGES."smiley/more/037.gif' alt='smiley' />",
		"\:smt038" => "<img src='".IMAGES."smiley/more/038.gif' alt='smiley' />",
		"\:smt039" => "<img src='".IMAGES."smiley/more/039.gif' alt='smiley' />",
		"\:smt040" => "<img src='".IMAGES."smiley/more/040.gif' alt='smiley' />",
		"\:smt041" => "<img src='".IMAGES."smiley/more/041.gif' alt='smiley' />",
		"\:smt042" => "<img src='".IMAGES."smiley/more/042.gif' alt='smiley' />",
		"\:smt043" => "<img src='".IMAGES."smiley/more/043.gif' alt='smiley' />",
		"\:smt044" => "<img src='".IMAGES."smiley/more/044.gif' alt='smiley' />",
		"\:smt045" => "<img src='".IMAGES."smiley/more/045.gif' alt='smiley' />",
		"\:smt046" => "<img src='".IMAGES."smiley/more/046.gif' alt='smiley' />",
		"\:smt047" => "<img src='".IMAGES."smiley/more/047.gif' alt='smiley' />",
		"\:smt048" => "<img src='".IMAGES."smiley/more/048.gif' alt='smiley' />",
		"\:smt049" => "<img src='".IMAGES."smiley/more/049.gif' alt='smiley' />",
		"\:smt050" => "<img src='".IMAGES."smiley/more/050.gif' alt='smiley' />",
		"\:smt051" => "<img src='".IMAGES."smiley/more/051.gif' alt='smiley' />",
		"\:smt052" => "<img src='".IMAGES."smiley/more/052.gif' alt='smiley' />",
		"\:smt053" => "<img src='".IMAGES."smiley/more/053.gif' alt='smiley' />",
		"\:smt054" => "<img src='".IMAGES."smiley/more/054.gif' alt='smiley' />",
		"\:smt055" => "<img src='".IMAGES."smiley/more/055.gif' alt='smiley' />",
		"\:smt056" => "<img src='".IMAGES."smiley/more/056.gif' alt='smiley' />",
		"\:smt057" => "<img src='".IMAGES."smiley/more/057.gif' alt='smiley' />",
		"\:smt058" => "<img src='".IMAGES."smiley/more/058.gif' alt='smiley' />",
		"\:smt059" => "<img src='".IMAGES."smiley/more/059.gif' alt='smiley' />",
		"\:smt060" => "<img src='".IMAGES."smiley/more/060.gif' alt='smiley' />",
		"\:smt061" => "<img src='".IMAGES."smiley/more/061.gif' alt='smiley' />",
		"\:smt062" => "<img src='".IMAGES."smiley/more/062.gif' alt='smiley' />",
		"\:smt063" => "<img src='".IMAGES."smiley/more/063.gif' alt='smiley' />",
		"\:smt064" => "<img src='".IMAGES."smiley/more/064.gif' alt='smiley' />",
		"\:smt065" => "<img src='".IMAGES."smiley/more/065.gif' alt='smiley' />",
		"\:smt066" => "<img src='".IMAGES."smiley/more/066.gif' alt='smiley' />",
		"\:smt067" => "<img src='".IMAGES."smiley/more/067.gif' alt='smiley' />",
		"\:smt068" => "<img src='".IMAGES."smiley/more/068.gif' alt='smiley' />",
		"\:smt069" => "<img src='".IMAGES."smiley/more/069.gif' alt='smiley' />",
		"\:smt070" => "<img src='".IMAGES."smiley/more/070.gif' alt='smiley' />",
		"\:smt073" => "<img src='".IMAGES."smiley/more/073.gif' alt='smiley' />",
		"\:smt074" => "<img src='".IMAGES."smiley/more/074.gif' alt='smiley' />",
		"\:smt075" => "<img src='".IMAGES."smiley/more/075.gif' alt='smiley' />",
		"\:smt076" => "<img src='".IMAGES."smiley/more/076.gif' alt='smiley' />",
		"\:smt077" => "<img src='".IMAGES."smiley/more/077.gif' alt='smiley' />",
		"\:smt078" => "<img src='".IMAGES."smiley/more/078.gif' alt='smiley' />",
		"\:smt079" => "<img src='".IMAGES."smiley/more/079.gif' alt='smiley' />",
		"\:smt080" => "<img src='".IMAGES."smiley/more/080.gif' alt='smiley' />",
		"\:smt081" => "<img src='".IMAGES."smiley/more/081.gif' alt='smiley' />",
		"\:smt082" => "<img src='".IMAGES."smiley/more/082.gif' alt='smiley' />",
		"\:smt083" => "<img src='".IMAGES."smiley/more/083.gif' alt='smiley' />",
		"\:smt084" => "<img src='".IMAGES."smiley/more/084.gif' alt='smiley' />",
		"\:smt085" => "<img src='".IMAGES."smiley/more/085.gif' alt='smiley' />",
		"\:smt086" => "<img src='".IMAGES."smiley/more/086.gif' alt='smiley' />",
		"\:smt087" => "<img src='".IMAGES."smiley/more/087.gif' alt='smiley' />",
		"\:smt088" => "<img src='".IMAGES."smiley/more/088.gif' alt='smiley' />",
		"\:smt089" => "<img src='".IMAGES."smiley/more/089.gif' alt='smiley' />",
		"\:smt090" => "<img src='".IMAGES."smiley/more/090.gif' alt='smiley' />",
		"\:smt091" => "<img src='".IMAGES."smiley/more/091.gif' alt='smiley' />",
		"\:smt092" => "<img src='".IMAGES."smiley/more/092.gif' alt='smiley' />",
		"\:smt093" => "<img src='".IMAGES."smiley/more/093.gif' alt='smiley' />",
		"\:smt084" => "<img src='".IMAGES."smiley/more/094.gif' alt='smiley' />",
		"\:smt095" => "<img src='".IMAGES."smiley/more/095.gif' alt='smiley' />",
		"\:smt096" => "<img src='".IMAGES."smiley/more/096.gif' alt='smiley' />",
		"\:smt097" => "<img src='".IMAGES."smiley/more/097.gif' alt='smiley' />",
		"\:smt098" => "<img src='".IMAGES."smiley/more/098.gif' alt='smiley' />",
		"\:smt099" => "<img src='".IMAGES."smiley/more/099.gif' alt='smiley' />",
		"\:smt101" => "<img src='".IMAGES."smiley/more/101.gif' alt='smiley' />",
		"\:smt103" => "<img src='".IMAGES."smiley/more/103.gif' alt='smiley' />",
		"\:smt104" => "<img src='".IMAGES."smiley/more/104.gif' alt='smiley' />",
		"\:smt105" => "<img src='".IMAGES."smiley/more/105.gif' alt='smiley' />",
		"\:smt106" => "<img src='".IMAGES."smiley/more/106.gif' alt='smiley' />",
		"\:smt107" => "<img src='".IMAGES."smiley/more/107.gif' alt='smiley' />",
		"\:smt108" => "<img src='".IMAGES."smiley/more/108.gif' alt='smiley' />",
		"\:smt109" => "<img src='".IMAGES."smiley/more/109.gif' alt='smiley' />",
		"\:smt110" => "<img src='".IMAGES."smiley/more/110.gif' alt='smiley' />",
		"\:smt111" => "<img src='".IMAGES."smiley/more/111.gif' alt='smiley' />",
		"\:smt112" => "<img src='".IMAGES."smiley/more/112.gif' alt='smiley' />",
		"\:smt113" => "<img src='".IMAGES."smiley/more/113.gif' alt='smiley' />",
		"\:smt114" => "<img src='".IMAGES."smiley/more/114.gif' alt='smiley' />",
		"\:smt115" => "<img src='".IMAGES."smiley/more/115.gif' alt='smiley' />",
		"\:smt116" => "<img src='".IMAGES."smiley/more/116.gif' alt='smiley' />",
		"\:smt117" => "<img src='".IMAGES."smiley/more/117.gif' alt='smiley' />",
		"\:smt118" => "<img src='".IMAGES."smiley/more/118.gif' alt='smiley' />",
		"\:smt119" => "<img src='".IMAGES."smiley/more/119.gif' alt='smiley' />",
		"\:smt120" => "<img src='".IMAGES."smiley/more/120.gif' alt='smiley' />",
		"\:boring" => "<img src='".IMAGES."smiley/more/015.gif' alt='smiley' />",
		"\:smt071" => "<img src='".IMAGES."smiley/more/071.gif' alt='smiley' />",
		"\:smt102" => "<img src='".IMAGES."smiley/more/102.gif' alt='smiley' />",
		"\:smt100" => "<img src='".IMAGES."smiley/more/100.gif' alt='smiley' />",
		"\:shock\:" => "<img src='".IMAGES."smiley/more/shock.gif' alt='smiley' />",
		"\:lol\:" => "<img src='".IMAGES."smiley/more/lol.gif' alt='smiley' />",
		"\:razz\:" => "<img src='".IMAGES."smiley/more/razz.gif' alt='smiley' />",
		"\:cry\:" => "<img src='".IMAGES."smiley/more/cry.gif' alt='smiley' />",
		"\:evil\:" => "<img src='".IMAGES."smiley/more/evil.gif' alt='smiley' />",
		"\:twisted\:" => "<img src='".IMAGES."smiley/more/icon_twisted.gif' alt='smiley' />",
		"\:roll\:" => "<img src='".IMAGES."smiley/more/rolleyes.gif' alt='smiley' />",
		"\:wink\:" => "<img src='".IMAGES."smiley/more/wink.gif' alt='smiley' />",
		"\:idea\:" => "<img src='".IMAGES."smiley/more/idea.gif' alt='smiley' />",
		"\:arrow\:" => "<img src='".IMAGES."smiley/more/arrow.gif' alt='smiley' />",
		"\:mrgreen\:" => "<img src='".IMAGES."smiley/more/icon_mrgreen.gif' alt='smiley' />",
		"\:badgrin\:" => "<img src='".IMAGES."smiley/more/badgrin.gif' alt='smiley' />",
		"\;\)" => "<img src='".IMAGES."smiley/wink.gif' alt='smiley' />",
		"\:\(" => "<img src='".IMAGES."smiley/sad.gif' alt='smiley' />",
		"\:\|" => "<img src='".IMAGES."smiley/frown.gif' alt='smiley' />",
		"\:o" => "<img src='".IMAGES."smiley/shock.gif' alt='smiley' />",
		"\:p" => "<img src='".IMAGES."smiley/pfft.gif' alt='smiley' />",
		"b\)" => "<img src='".IMAGES."smiley/cool.gif' alt='smiley' />",
		"\:d" => "<img src='".IMAGES."smiley/grin.gif' alt='smiley' />",
		"\:@" => "<img src='".IMAGES."smiley/angry.gif' alt='smiley' />",
		"=D&gt;" => "<img src='".IMAGES."smiley/more/eusa_clap.gif' alt='smiley' />",
		"\\\:D/" => "<img src='".IMAGES."smiley/more/eusa_dance.gif' alt='smiley' />",
		"\:D" => "<img src='".IMAGES."smiley/more/biggrin.gif' alt='smiley' />",
		"\:\-D" => "<img src='".IMAGES."smiley/more/003.gif' alt='smiley' />",
		"\:\-\)" => "<img src='".IMAGES."smiley/more/001.gif' alt='smiley' />",
		"\:\(" => "<img src='".IMAGES."smiley/more/sad.gif' alt='smiley' />",
		"\:o" => "<img src='".IMAGES."smiley/more/surprised.gif' alt='smiley' />",
		"8\)" => "<img src='".IMAGES."smiley/more/cool.gif' alt='smiley' />",
		"\:x" => "<img src='".IMAGES."smiley/more/mad.gif' alt='smiley' />",
		"\:\-x" => "<img src='".IMAGES."smiley/more/icon_mad.gif' alt='smiley' />",
		"\:P" => "<img src='".IMAGES."smiley/more/icon_razz.gif' alt='smiley' />",
		"\;\-\)" => "<img src='".IMAGES."smiley/more/002.gif' alt='smiley' />",
		"\:\!\:" => "<img src='".IMAGES."smiley/more/exclaim.gif' alt='smiley' />",
		"\:\?\:" => "<img src='".IMAGES."smiley/more/question.gif' alt='smiley' />",
		"\:\?" => "<img src='".IMAGES."smiley/more/confused.gif' alt='smiley' />",
		"\:\|" => "<img src='".IMAGES."smiley/more/neutral.gif' alt='smiley' />",
		"\#\-o" => "<img src='".IMAGES."smiley/more/eusa_doh.gif' alt='smiley' />",
		"\=P\~" => "<img src='".IMAGES."smiley/more/eusa_drool.gif' alt='smiley' />",
		"\:\^o" => "<img src='".IMAGES."smiley/more/eusa_liar.gif' alt='smiley' />",
		"\[\-X" => "<img src='".IMAGES."smiley/more/eusa_naughty.gif' alt='smiley' />",
		"\[\-o\<\;" => "<img src='".IMAGES."smiley/more/eusa_pray.gif' alt='smiley' />",
		"8\-\[" => "<img src='".IMAGES."smiley/more/eusa_shifty.gif' alt='smiley' />",
		"\[\-\(" => "<img src='".IMAGES."smiley/more/eusa_snooty.gif' alt='smiley' />",
		"\:\-k" => "<img src='".IMAGES."smiley/more/eusa_think.gif' alt='smiley' />",
		"\]\(\*\,\)" => "<img src='".IMAGES."smiley/more/eusa_wall.gif' alt='smiley' />",
		"\:\-\"" => "<img src='".IMAGES."smiley/more/eusa_whistle.gif' alt='smiley' />",
		"O\:\)" => "<img src='".IMAGES."smiley/more/eusa_angel.gif' alt='smiley' />",
		"\=\;" => "<img src='".IMAGES."smiley/more/eusa_hand.gif' alt='smiley' />",
		"\:\-\&" => "<img src='".IMAGES."smiley/more/eusa_sick.gif' alt='smiley' />",
		"\:\-\(\{\|\=" => "<img src='".IMAGES."smiley/more/eusa_boohoo.gif' alt='smiley' />",
		"\:\-\$" => "<img src='".IMAGES."smiley/more/eusa_shhh.gif' alt='smiley' />",
		"\:\-s" => "<img src='".IMAGES."smiley/more/eusa_eh.gif' alt='smiley' />",
		"\:\-\#" => "<img src='".IMAGES."smiley/more/eusa_silenced.gif' alt='smiley' />",
		"\:\)" => "<img src='".IMAGES."smiley/smile.gif' alt='smiley' />"
	);
	foreach($smiley as $key=>$smiley_img) {
		$search = "#(^|[[:space:]])".$key."([[:space:]]|$)?#si";
		$replace = "\\1".$smiley_img."\\2";
		$message = preg_replace($search, $replace, $message);
	}
	return $message;
}

// internal function: preg_replace_callback for parseubb, to validate the URL found in [url]
function _parseubb_checkurl($matches) {

	// if it's a old-style bbcode (not [url=][/url] but [url][/url]), convert it before checking
	if (empty($matches[2])) {
		$matches[2] = $matches[3];
	}

	// validate the URL (in $matches[1])
	if (isURL($matches[2])) {
		// check if the URL is prefixed. If not, assume http://
		if (!eregi("^((https?|s?ftp|mailto|svn|cvs|callto|mms|skype)\:\/\/){1}", $matches[2])) {
			$matches[2] = "http://".$matches[2];
		}
		// return the html for the URL bbcode
		return "<a href='".$matches[2]."' alt='' target='_blank'>".$matches[3]."</a>";
	} else {
		// make the bbcode passed harmless
		return stripinput($matches[0]);
	}
}

// internal function: preg_replace_callback for parseubb, to validate the IMG found in [img]
function _parseubb_checkimg($matches) {
	global $locale;

	// validate the URL (in $matches[1]) or check if it is a local image file
	if (isURL($matches[1], true) || file_exists(PATH_ROOT.$matches[1])) {
		if (verify_image($matches[1])) {
			return "<img src=\"".$matches[1]."\" style=\"border:0px\" alt=\"\" />";
		}
	}
	// return a sanitized version of the orginal BBcode
	return stripinput($matches[0]);
}

// Parse bbcode into HTML code
function parseubb($text) {
	global $settings, $locale;
	
	// horizontal line
	$text = preg_replace('#\[hr\]#si', '<hr />', $text);

	// old style lists
	$text = preg_replace('#\[li\](.*?)\[/li\]#si', '<li style=\'margin-left:15px;\'>\1</li>', $text);
	$text = preg_replace('#\[ul\](.*?)\[/ul\]#si', '<ul style=\'margin-left:-20px;\'>\1</ul>', $text);

	// new style lists
	$text = preg_replace('#\[list=1\](.*?)\[/list\]#si', '<ol>\1</ol>', $text);
	$text = preg_replace('#\[list\](.*?)\[/list\]#si', '<ul>\1</ul>', $text);
	$text = preg_replace('#\r\n\[\*\]#si', '<li>', $text);

	//get rid of line breaks after a list item, for better formatting
	$text=str_replace("</li><br />","</li>",$text);
	$text=str_replace("</ul><br />","</ul>",$text);

	// text formatting
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $text);
	$text = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $text);
	$text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
	$text = preg_replace('#\[strike\](.*?)\[/strike\]#si', '<span style=\'text-decoration: line-through;\'>\1</span>', $text);
	$text = preg_replace('#\[sup\](.*?)\[/sup\]#si', '<sup>\1</sup>', $text);
	$text = preg_replace('#\[sub\](.*?)\[/sub\]#si', '<sub>\1</sub>', $text);
	$text = preg_replace('#\[blockquote\](.*?)\[/blockquote\]#si', '<blockquote style=\'border:1px dotted;padding:2px;\'>\1</blockquote>', $text);

	$text = preg_replace('#\[left\](.*?)\[/left\]#si', '<div align=\'left\'>\1</div>', $text);
	$text = preg_replace('#\[center\](.*?)\[/center\]#si', '<div align=\'center\'>\1</div>', $text);
	$text = preg_replace('#\[justify\](.*?)\[/justify\]#si', '<div align=\'justify\'>\1</div>', $text);
	$text = preg_replace('#\[right\](.*?)\[/right\]#si', '<div align=\'right\'>\1</div>', $text);

	$text = preg_replace('#\[font=(.*?)\](.*?)\[/font\]#si', '<span style=\'font-family:\1\'>\2</span>', $text);
	$text = preg_replace('#\[size=([0-3]?[0-9])\](.*?)\[/size\]#si', '<span style=\'font-size:\1px\'>\2</span>', $text);
	$text = preg_replace('#\[small\](.*?)\[/small\]#si', '<span class=\'small\'>\1</span>', $text);

	$text = preg_replace('#\[color=(\#[0-9a-fA-F]{6}|black|blue|brown|cyan|grey|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/color\]#si', '<span style=\'color:\1\'>\2</span>', $text);
	$text = preg_replace('#\[highlight=(\#[0-9a-fA-F]{6}|black|blue|brown|cyan|grey|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/highlight\]#si', '<span style=\'background-color:\1\'>\2</span>', $text);

	// new wiki bbcode
	if (isset($settings['wiki_forum_links']) && $settings['wiki_forum_links']) {
		// add the link to the wiki page
		$text = preg_replace('#\[wiki\](.*?)\[/wiki\]#si', '<a href="'.BASEDIR.'modules/wiki/index.php?wakka=\1" class="wiki_link" title="'.$settings['wiki_wakka_name'].'">\1</a>', $text);
	} else {
		// strip the wiki bbcode
		$text = preg_replace('#\[wiki\](.*?)\[/wiki\]#si', '\1', $text);
	}

	// correct illegal [url=] BBcode
	$text = str_replace("[url=]", "[url]", $text);

	// convert URL bbcode, strip non-valid URL's
	$text = preg_replace_callback('#\[url(=)?(.*?)\](.*?)([\r\n]*)\[/url\]#si', '_parseubb_checkurl', $text);

	// convert mail bbcode
	$text = preg_replace('#\[mail\]([\r\n]*)([^\s\'\";:\+]*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);
	$text = preg_replace('#\[mail=([\r\n]*)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\3</a>', $text);

	// youtube bbcode
	$text = preg_replace('#\[youtube\](.*?)\[/youtube\]#si', '<object type="application/x-shockwave-flash" width="425" height="350" data="http://www.youtube.com/v/\1"><param name="movie" value="http://www.youtube.com/v/\1"></param><param name="wmode" value="transparent"></param></object>', $text);

	// flash movies
	$text = preg_replace('#\[flash width=([0-9]*?) height=([0-9]*?)\]([^\s\'\";:\+]*?)(\.swf)\[/flash\]#si', '<object classid=\'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\' codebase=\'http://active.macromedia.com/flash6/cabs/swflash.cab#version=6,0,0,0\' id=\'\3\4\' width=\'\1\' height=\'\2\'><param name=movie value=\'\3\4\'><param name=\'quality\' value=\'high\'><param name=\'bgcolor\' value=\'#ffffff\'><embed src=\'\3\4\' quality=\'high\' bgcolor=\'#ffffff\' width=\'\1\' height=\'\2\' type=\'application/x-shockwave-flash\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\'></embed></object>', $text);

	// images
	if (ini_get('allow_url_fopen')) {
		$text = preg_replace_callback('#\[img\](.*?)\[/img\]#si', '_parseubb_checkimg', $text);
	} else {
		$text = preg_replace("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#sie","'<img src=\'\\1'.str_replace(array('.php','?','&','='),'','\\3').'\\4\' style=\'border:0px\' alt=\'\' />'",$text);
	}

	// quote	 & code blocks
	$text = preg_replace('#\[quote=([\r\n]*)(.*?)\]#si', '<b>\2 '.$locale['199'].':</b><br />[quote]', $text);
	$qcount = substr_count($text, "[quote]"); $ccount = substr_count($text, "[code]");
	for ($i=0;$i < $qcount;$i++) $text = preg_replace('#\[quote\](.*?)\[/quote\]#si', '<div class=\'quote\'>\1</div>', $text);
	for ($i=0;$i < $ccount;$i++) $text = preg_replace('#\[code\](.*?)\[/code\]#si', '<b>code:</b><div class=\'codeblock\'>\1</div>', $text);

	$text = descript($text,false);

	return $text;
}

// This function sanitises news & article submissions
function descript($text,$striptags=true) {
	// Convert problematic ascii characters to their true values
	$search = array("40","41","58","65","66","67","68","69","70",
		"71","72","73","74","75","76","77","78","79","80","81",
		"82","83","84","85","86","87","88","89","90","97","98",
		"99","100","101","102","103","104","105","106","107",
		"108","109","110","111","112","113","114","115","116",
		"117","118","119","120","121","122"
		);
	$replace = array("(",")",":","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z"
		);
	$entities = count($search);
	for ($i=0;$i < $entities;$i++) $text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);
	// the following is based on code from bitflux (http://blog.bitflux.ch/wiki/)
	// Kill hexadecimal characters completely
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	// remove any attribute starting with "on" or xmlns
	$text = preg_replace('#(<[^>]+[\\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onload|xmlns)[^>]*>#iU', ">", $text);
	// remove javascript: and vbscript: protocol
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if ($striptags) {
		do {
				$thistext = $text;
			$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
		} while ($thistext != $text);
	}
	return $text;
}

// Scan image files for malicious code
function verify_image($file) {
	$image_safe = true;
	if (isURL($file, false, true) || file_exists($file)) {
		$er = error_reporting(0);
		// get info about the image
		$imginfo = @getimagesize($file);
		// get the file contents
		$txt = file_get_contents($file);
		error_reporting($er);
		if ($imginfo === false) { $image_safe = false; }
		if ($txt === false) { $image_safe = false; }
		elseif (preg_match('#&(quot|lt|gt|nbsp);#i', $txt)) { $image_safe = false; }
		elseif (preg_match("#&\#x([0-9a-f]+);#i", $txt)) { $image_safe = false; }
		elseif ($imginfo[2] != 5 && preg_match('#&\#([0-9]+);#i', $txt)) { $image_safe = false; }	// skip for psd files
		elseif (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt)) { $image_safe = false; }
		elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt)) { $image_safe = false; }
		elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt)) { $image_safe = false; }
		elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt)) { $image_safe = false; }
		elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt)) { $image_safe = false; }
		elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt)) { $image_safe = false; }
	}
	return $image_safe;
}

// Replace offensive words with the defined replacement word
function censorwords($text) {
	global $settings;
	if ($settings['bad_words_enabled'] == "1" && $settings['bad_words'] != "" ) {
		$word_list = explode("\r\n", $settings['bad_words']);
		for ($i=0;$i < count($word_list);$i++) {
			if ($word_list[$i] != "") $text = preg_replace("/".$word_list[$i]."/si", $settings['bad_word_replace'], $text);
		}
	}
	return $text;
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files", $hidden=false) {
	$res = array();
	if (is_dir($folder)) {
		$filter = explode("|", $filter); 
		$temp = opendir($folder);
		while ($file = readdir($temp)) {
			if (!$hidden && $file{0} == ".") continue;
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

// Format the date & time accordingly
function showdate($format, $val=false) {
	global $settings;

	// if no date value is passed, use now
	if (!$val) $val = time();

	// return it in the format requested
	switch($format) {
		case "shortdate":
		case "longdate":
		case "subheaderdate":
		case "forumdate":
			$format = $settings[$format];
	}
	switch($format) {
		case "localedate":
			$format = preg_replace("/[^a-zA-z%]/", " ", str_replace("%m", "%B", nl_langinfo(D_FMT)));
			break;
		case "localetime":
			$format = nl_langinfo(T_FMT);
			break;
		case "localedatetime":
			$format = preg_replace("/[^a-zA-z%]/", " ", str_replace("%m", "%B", nl_langinfo(D_FMT)))." ".nl_langinfo(T_FMT);
			break;
		default:
	}	
	return strftime($format, $val);
}

//convert system time to local time
function time_system2local($val) {
	global $settings;
	
	// calculate local time
	if (strpos($settings['timeoffset'], ":") === false) {
		$localtime = $settings['timeoffset'];
	} else {
		$offset = substr($settings['timeoffset'],strpos($settings['timeoffset'], ":") + 1) / 60;
		if ($settings['timeoffset']{0} == "-") {
			$localtime = substr($settings['timeoffset'],0,strpos($settings['timeoffset'], ":")) - $offset;
		} else {
			$localtime = substr($settings['timeoffset'],0,strpos($settings['timeoffset'], ":")) + $offset;
		}
	}
	return $val + ($localtime * 3600);
}

//convert local time to system time
function time_local2system($val) {
	global $settings;
	
	// calculate local time
	if (strpos($settings['timeoffset'], ":") === false) {
		$localtime = $settings['timeoffset'];
	} else {
		$offset = substr($settings['timeoffset'],strpos($settings['timeoffset'], ":") + 1) / 60;
		if ($settings['timeoffset']{0} == "-") {
			$localtime = substr($settings['timeoffset'],0,strpos($settings['timeoffset'], ":")) - $offset;
		} else {
			$localtime = substr($settings['timeoffset'],0,strpos($settings['timeoffset'], ":")) + $offset;
		}
	}
	return $val - ($localtime * 3600);
}


// translate a timestamp into a date relative to now
function datediff($datefrom,$dateto=-1)
{
	global $locale;
	
	// validate parameters
	if ($datefrom == 0) { return ""; }
	if ($dateto == -1) { $dateto = time(); }

	// calculate the difference in seconds betweeen the two timestamps
	$difference = abs($dateto - $datefrom);

	// determine the interface
	if ($difference < 60) {
		// if difference is less than 60 seconds, seconds is a good interval of choice
		$interval = "s";
	} elseif ($difference >= 60 && $difference<60*60) {
		// if difference is between 60 seconds and 60 minutes, minutes is a good interval
      $interval = "h";
	} elseif ($difference >= 60*60 && $difference<60*60*24) {
		// if difference is between 1 hour and 24 hours, hours is a good interval
		$interval = "h";
	} elseif ($difference >= 60*60*24 && $difference<60*60*24*7) {
		// if difference is between 1 day and 7 days, days is a good interval
		$interval = "d";
	} elseif ($difference >= 60*60*24*7 && $difference < 60*60*24*30) {
		// if difference is between 1 week and 30 days, weeks is a good interval
		$interval = "w";
	} elseif ($difference >= 60*60*24*30 && $difference < 60*60*24*365) {
		// if difference is between 30 days and 365 days, months is a good interval, again, the same thing
		// applies, if the 29th February happens to exist between your 2 dates, the function will return
		// the 'incorrect' value for a day
		$interval = "m";
	} elseif ($difference >= 60*60*24*365) {
		// if difference is greater than or equal to 365 days, return year. This will be incorrect if
		// for example, you call the function on the 28th April 2008 passing in 29th April 2007. It will
		// return 1 year ago when in actual fact (yawn!) not quite a year has gone by
		$interval = "y";
	}

	// based on the interval, determine the number of units between the two dates
	// from this point on, you would be hard pushed telling the difference between
    // this function and DateDiff. If the $datediff returned is 1, be sure to return
	// the singular of the unit, e.g. 'day' rather 'days'
	$res = "";
	switch($interval) {
		case "w":
			$datediff = floor($difference / 60 / 60 / 24 / 7);
			$res = $datediff . " " . (($datediff==1) ? $locale['072'] : $locale['073']);
			break;

		case "y":
			$datediff = floor($difference / 60 / 60 / 24 / 365);
			$res = $datediff . " " . (($datediff==1) ? $locale['078'] : $locale['079']);
			break;

		case "m":
			$months_difference = floor($difference / 60 / 60 / 24 / 29);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference++;
			}
			$datediff = $months_difference;
			// we need this in here because it is possible to have an 'm' interval and a months
			// difference of 12 because we are using 29 days in a month
			if ($datediff==12) {
				$datediff--;
			}
			$res .= $datediff . " " . (($datediff==1) ? $locale['076'] : $locale['077']);
			break;

		case "d":
			$datediff = floor($difference / 60 / 60 / 24);
			$res .= $datediff . " " . (($datediff==1) ? $locale['074'] : $locale['075']);
			break;

		case "h":
			$datediff = floor($difference / 60 / 60);
			$difference =  $difference - $datediff * 60 * 60;
			$res .= sprintf("%02d:", $datediff);

		case "n":
			$datediff = floor($difference / 60);
			$difference =  $difference - $datediff * 60;
			$res .= sprintf("%02d:", $datediff);

		case "s":
			$datediff = $difference;
			$res .= sprintf("%02d", $datediff);
			break;
	}
	return $res;
}

// translate bytes into kb, mb, gb or tb by CrappoMan
function parsebytesize($size,$digits=2,$dir=false) {
	$kb=1024; $mb=1024*$kb; $gb=1024*$mb; $tb=1024*$gb;
	if (($size==0)&&($dir)) { return "Empty"; }
	elseif ($size<$kb) { return $size." Bytes"; }
	elseif ($size<$mb) { return round($size/$kb,$digits)." Kb"; }
	elseif ($size<$gb) { return round($size/$mb,$digits)." Mb"; }
	elseif ($size<$tb) { return round($size/$gb,$digits)." Gb"; }
	else { return round($size/$tb,$digits)." Tb"; }
}

// check if the uploaded attachment file exists, if so, find a unique one
function attach_exists($file, $attachpath = PATH_ATTACHMENTS) {
	$i = 1;
	$file = str_replace(" ?&%*[]()", "_________", $file);
	$file_name = substr($file, 0, strrpos($file, "."));
	$file_ext = strrchr($file,".");
	while (file_exists($attachpath.$file)) {
		$file = $file_name."_".$i.$file_ext;
		$i++;
	}
	return $file;
}

function checkCMSversion($min=false, $max=false) {
	global $locale;

	$error	 = "";
	
	if ($min) {
		if (str_replace(".", "", $settings['version']) < str_replace(".", "", $min)) {
			$error .= sprintf($locale['mod001'], $min);
		}
	}
	if ($max) {
		if (str_replace(".", "", $settings['version']) > str_replace(".", "", $max)) {
			$error .= sprintf($locale['mod002'], $max);
		}
	}
	return $error;
}

function checkCMSrevision($min=false, $max=false) {
	global $locale;

	$error	 = "";
	
	if ($min) {
		if ($settings['revision'] < $min) {
			$error .= sprintf($locale['mod003'], $min);
		}
	}
	if ($max) {
		if ($settings['revision'] > $max) {
			$error .= sprintf($locale['mod005'], $max);
		}
	}
	return $error;
}

function auth_BasicAuthentication() {
	
	global $settings;
	
	// ask the user for authentication
	header('WWW-Authenticate: Basic realm="'.$settings['sitename'].'"');
	header('HTTP/1.0 401 Unauthorized');
	// if the user cancels, redirect to the homepage
	echo "<script type='text/javascript'>document.location.href='".BASEDIR."index.php'</script>\n";
	exit;
}

function auth_validate_BasicAuthentication() {

	global $db_prefix; 

	$user_pass = md5($_SERVER['PHP_AUTH_PW']);
	$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_SERVER['PHP_AUTH_USER']));

	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$user_name' AND user_password='$user_pass'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$cookie_value = $data['user_id'].".".$data['user_password'];
		// if the account is suspended, check for an expiry date
		if ($data['user_status'] == 1 && $data['user_ban_expire'] > 0 && $data['user_ban_expire'] < time() ) {
			// reset the user status and the expiry date
			$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0', user_ban_expire='0' WHERE user_id='".$data['user_id']."'");
			$data['user_status'] = 0;
		}
		if ($data['user_status'] == 0) {	
			$cookie_exp = time() + 60*30;
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie("userinfo", $cookie_value, $cookie_exp, "/", "", "0");
			return 0;
		} 
		return $data['user_status'];
	} else {
		return -1;	// user_status == -1: not_found
	}
}

// get the OS type
function CMS_getOS () {
	if (substr(PHP_OS, 0, 3) == 'WIN') {
		return "Windows";
	} elseif ( stristr(PHP_OS, "linux")) {
		return "Linux";
	} elseif ( stristr(PHP_OS, "SunOS")) {
		return "SunOS";
	} elseif ( stristr(PHP_OS, "Solaris")) {
		return "Solaris";
	} else {
		return "Other";
	}
}


// replacement for die()
function terminate($text) {
	die("<div style='font-family:Verdana,Sans-serif;font-size:11px;text-align:center;'>$text</div>");
}
?>
