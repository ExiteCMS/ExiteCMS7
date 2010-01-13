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
// disable error reporting, we don't want to give anything away
error_reporting(E_USER_ERROR & ~E_DEPRECATED);

if (eregi("core_functions.php", $_SERVER['PHP_SELF'])) die();

// make sure we have enough memory to work with (independent of the php.ini setting)
ini_set('memory_limit', '32M');

// check the environment to see if we can run
if (!extension_loaded('gd') || !function_exists('gd_info')) {
	terminate('FATAL ERROR: ExiteCMS requires the graphics library GD to function, and it doesn\'t seem to be present in your PHP setup');
}
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

// prevent possible XSS attacks via $_GET (uses pre v8 xss cleaning code).
$_GET = xss_clean($_GET);

// if register_globals is turned off, extract super globals (php 4.2.0+)
// WanWizard - 20090820 - use EXTR_SKIP, we do not want to overwrite existing variables!
if (ini_get('register_globals') != 1) {
	if ((isset($_POST) == true) && (is_array($_POST) == true)) extract($_POST, EXTR_SKIP);
	if ((isset($_GET) == true) && (is_array($_GET) == true)) extract($_GET, EXTR_SKIP);
}

// store the magic quotes setting
define("QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));

// Browser window dimensions (assume 1024x768 if no cookies found)
define("BROWSER_WIDTH", isset($_COOKIE['width']) ? $_COOKIE['width'] : 1024);
define("BROWSER_HEIGHT", isset($_COOKIE['height']) ? $_COOKIE['height'] : 768);

// disable the standard PHP include path (empty's not accepted?)
ini_set('include_path', '.');

// start Output Buffering
ob_start();

// absolute path definitions
define("PATH_ROOT", realpath(dirname(__FILE__).'/../').'/');
define("PATH_ADMIN", PATH_ROOT."administration/");
define("PATH_THEMES", PATH_ROOT."themes/");
define("PATH_PHOTOS", PATH_ROOT."images/albums/");
define("PATH_IMAGES", PATH_ROOT."images/");
define("PATH_IMAGES_A", PATH_IMAGES."articles/");
define("PATH_IMAGES_ADS", PATH_IMAGES."advertising/");
define("PATH_IMAGES_AV", PATH_IMAGES."avatars/");
define("PATH_IMAGES_N", PATH_IMAGES."news/");
define("PATH_IMAGES_NC", PATH_IMAGES."news_cats/");
define("PATH_IMAGES_DC", PATH_IMAGES."download_cats/");
define("PATH_INCLUDES", PATH_ROOT."includes/");
define("PATH_GESHI", PATH_INCLUDES."geshi-1.0.8/");
define("PATH_FANCYUPLOAD", PATH_INCLUDES."fancyupload-2.0/");
define("PATH_MODULES", PATH_ROOT."modules/");
define("PATH_ATTACHMENTS", PATH_ROOT."files/attachments/");
define("PATH_PM_ATTACHMENTS", PATH_ROOT."files/pm_attachments/");
define("PATH_DOWNLOADS", PATH_ROOT."files/downloads/");

// mark that CMS Engine is properly initialized
define("INIT_CMS_OK", TRUE);

// get the full filename of the config file
@include_once PATH_ROOT."configpath.php";
if (substr(CONFIG_PATH,0,1) == "/") {
	define('CONFIG_FILE', str_replace("//", "/", CONFIG_PATH."/config.php"));
} else {
	define('CONFIG_FILE', str_replace("//", "/", PATH_ROOT.CONFIG_PATH."/config.php"));
}
if(is_file(CONFIG_FILE)) {
	@include_once CONFIG_FILE;
}

// if config.php is absent or empty, bail out with an error
if (!isset($db_name)) {
	terminate('FATAL ERROR: config file is missing. Did you run the setup?<br />Check our Wiki at http://www.exitecms.org on how to run the setup');
}

// load the database functions, and establish a database connection
require_once PATH_INCLUDES."db_functions.php";

// fetch the configuration from the database and store them in the $settings variable
$settings = array();
$result = dbquery("SELECT * FROM ".$db_prefix."configuration");
while ($data = dbarray($result)) {
	$settings[$data['cfg_name']] = $data['cfg_value'];
}

// activate PHP error reporting
if (isset($settings['debug_php_errors']) && $settings['debug_php_errors']) {
	error_reporting(E_ALL);
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
define("DOWNLOADS", BASEDIR."files/downloads/");
define("MODULES", BASEDIR."modules/");
define("INCLUDES", BASEDIR."includes/");
define("PHOTOS", IMAGES."albums/");
define("THEMES", BASEDIR."themes/");
define("GESHI", INCLUDES."geshi-1.0.8/");
define("FANCYUPLOAD", INCLUDES."fancyupload-2.0/");
define("TINY_MCE", INCLUDES."jscripts/tiny_mce/");

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

// load the user functions
require_once PATH_INCLUDES."user_functions.php";

// set the query log debugging switch, enable error reporting if needed
$_db_log = checkgroup($settings['debug_querylog'], false);

// load the locale functions
require_once PATH_INCLUDES."locale_functions.php";

// load the CMS global locale strings
locale_load("main.global");

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
	session_clean_close();

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
		if (!$url) {
			// invalid url, skip it
		} else {
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
	}
	$returner = str_replace($dec, $enc, $returner);
	return $returner;
}

// Validate numeric (positive/negative) input
function isNum($value) {
	return (preg_match("/^-?[0-9]+$/", $value));
}

// Validate decimal (positive/negative) input
function isDec($value) {
	return (preg_match("/^-?[0-9]+\.[0-9][0-9]$/", $value));
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
	// check if the file is a URL
	if (isURL($file)) {
		// check if the URL exists
		$uri = parse_url($file);
		if ($uri['host'] == $_SERVER['HTTP_HOST']) {
			// from the local server, image has been checked when uploaded!
			return true;
		}
		// checking takes to long, return true
		return true;
		$uri['port'] = isset($uri['port']) ? $uri['port'] : 80;
		// use a timeout of 1 second, slow servers will slow us down too!
		$handle = @fsockopen($uri['host'], $uri['port'], $errno, $errstr, 1);
		if (!$handle) {
			// no response on URL request, image is invalid!
			return false;
		}
		fclose($handle);
	}
	// check the image
	$er = error_reporting(0);
	// get info about the image
	$imginfo = @getimagesize($file);
	error_reporting($er);
	if ($imginfo === false) {
		$image_safe = false;
	} else {
		// get the file contents so we can check it
		$txt = file_get_contents($file);
		if ($txt === false) { $image_safe = false; }
		elseif (preg_match('#&(quot|lt|gt|nbsp);#i', $txt)) { $image_safe = false; }
		elseif (preg_match("#&\#x([0-9a-f]+);#i", $txt)) { $image_safe = false; }
		elseif ($imginfo[2] != 5 && preg_match('#&\#([0-9]+);#i', $txt)) { $image_safe = false; }       // skip for psd files
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
	if(substr($folder,-1) != "/") $folder .= "/";
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

	global $cms_authentication;

	$cms_authentication->logon(array('username' => stripinput($_SERVER['PHP_AUTH_USER']), 'password' => $_SERVER['PHP_AUTH_PW']));

	if ($cms_authentication->logged_on()) {
		$userdata = $cms_authentication->get_userinfo();
		return $userdata['user_status'];
	} else {
		return -1;	// user_status == -1: not_found
	}
}

// convert a relative URL to an absolute URL
function url_to_absolute( $baseUrl, $relativeUrl ) {
	// If relative URL has a scheme, clean path and return.
	$r = split_url( $relativeUrl );
	if ( $r === FALSE ) return FALSE;
	if ( !empty( $r['scheme'] ) ) {
		if ( !empty( $r['path'] ) && $r['path'][0] == '/' )
			$r['path'] = _url_remove_dot_segments( $r['path'] );
		return join_url( $r );
	}

	// Make sure the base URL is absolute.
	$b = split_url( $baseUrl );
	if ( $b === FALSE || empty( $b['scheme'] ) || empty( $b['host'] ) ) return FALSE;
	$r['scheme'] = $b['scheme'];

	// If relative URL has an authority, clean path and return.
	if ( isset( $r['host'] ) ) {
		if ( !empty( $r['path'] ) ) $r['path'] = _url_remove_dot_segments( $r['path'] );
		return join_url( $r );
	}

	// Copy base authority.
	$r['host'] = $b['host'];
	if ( isset( $b['port'] ) ) $r['port'] = $b['port'];
	if ( isset( $b['user'] ) ) $r['user'] = $b['user'];
	if ( isset( $b['pass'] ) ) $r['pass'] = $b['pass'];

	// If relative URL has no path, use base path
	if ( empty( $r['path'] ) ) {
		if ( !empty( $b['path'] ) ) $r['path'] = $b['path'];
		if ( !isset( $r['query'] ) && isset( $b['query'] ) ) $r['query'] = $b['query'];
		return join_url( $r );
	}

	// If relative URL path doesn't start with /, merge with base path
	if ( $r['path'][0] != '/' )	{
		$base = strrchr( $b['path'], '/');
		if ( $base === FALSE ) $base = '';
		$r['path'] = $base . '/' . $r['path'];
	}
	$r['path'] = _url_remove_dot_segments( $r['path'] );
	return join_url( $r );
}

function _url_remove_dot_segments( $path ) {
	$inSegs  = preg_split( '!/!u', $path );
	$outSegs = array( );
	foreach ( $inSegs as $seg ) {
		if ( empty( $seg ) || $seg == '.' ) continue;
		if ( $seg == '..' ) {
			array_pop( $outSegs );
		} else {
			array_push( $outSegs, $seg );
		}
	}
	$outPath = implode( '/', $outSegs );
	if ( $path[0] == '/' ) $outPath = '/' . $outPath;
	if ( $outPath != '/' &&  (strlen($path)-1) == strrpos( $path, '/' ) ) $outPath .= '/';
	return $outPath;
}

function split_url( $url, $decode=TRUE ) {
	$xunressub     = 'a-zA-Z\d\-._~\!$&\'()*+,;=';
	$xpchar        = $xunressub . ':@%';

	$xscheme       = '([a-zA-Z][a-zA-Z\d+-.]*)';

	$xuserinfo     = '((['  . $xunressub . '%]*)' .
					 '(:([' . $xunressub . ':%]*))?)';

	$xipv4         = '(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})';

	$xipv6         = '(\[([a-fA-F\d.:]+)\])';

	$xhost_name    = '([a-zA-Z\d-.%]+)';

	$xhost         = '(' . $xhost_name . '|' . $xipv4 . '|' . $xipv6 . ')';
	$xport         = '(\d*)';
	$xauthority    = '((' . $xuserinfo . '@)?' . $xhost .
					 '?(:' . $xport . ')?)';

	$xslash_seg    = '(/[' . $xpchar . ']*)';
	$xpath_authabs = '((//' . $xauthority . ')((/[' . $xpchar . ']*)*))';
	$xpath_rel     = '([' . $xpchar . ']+' . $xslash_seg . '*)';
	$xpath_abs     = '(/(' . $xpath_rel . ')?)';
	$xapath        = '(' . $xpath_authabs . '|' . $xpath_abs .
					 '|' . $xpath_rel . ')';

	$xqueryfrag    = '([' . $xpchar . '/?' . ']*)';

	$xurl          = '^(' . $xscheme . ':)?' .  $xapath . '?' .
					 '(\?' . $xqueryfrag . ')?(#' . $xqueryfrag . ')?$';


	// Split the URL into components.
	if ( !preg_match( '!' . $xurl . '!', $url, $m ) ) return FALSE;

	if ( !empty($m[2]) ) $parts['scheme']  = strtolower($m[2]);

	if ( !empty($m[7]) ) {
		if ( isset( $m[9] ) ) {
		   $parts['user'] = $m[9];
		} else {
			$parts['user'] = '';
		}
	}
	if ( !empty($m[10]) )       $parts['pass']    = $m[11];

	if ( !empty($m[13]) )       $h=$parts['host'] = $m[13];
	else if ( !empty($m[14]) )  $parts['host']    = $m[14];
	else if ( !empty($m[16]) )  $parts['host']    = $m[16];
	else if ( !empty( $m[5] ) ) $parts['host']    = '';
	if ( !empty($m[17]) )       $parts['port']    = $m[18];

	if ( !empty($m[19]) )       $parts['path']    = $m[19];
	else if ( !empty($m[21]) )  $parts['path']    = $m[21];
	else if ( !empty($m[25]) )  $parts['path']    = $m[25];

	if ( !empty($m[27]) )       $parts['query']   = $m[28];
	if ( !empty($m[29]) )       $parts['fragment']= $m[30];

	if ( !$decode )
		return $parts;
	if ( !empty($parts['user']) )
		$parts['user']     = rawurldecode( $parts['user'] );
	if ( !empty($parts['pass']) )
		$parts['pass']     = rawurldecode( $parts['pass'] );
	if ( !empty($parts['path']) )
		$parts['path']     = rawurldecode( $parts['path'] );
	if ( isset($h) )
		$parts['host']     = rawurldecode( $parts['host'] );
	if ( !empty($parts['query']) )
		$parts['query']    = rawurldecode( $parts['query'] );
	if ( !empty($parts['fragment']) )
		$parts['fragment'] = rawurldecode( $parts['fragment'] );
	return $parts;
}

function join_url( $parts, $encode=false ) {
	if ( $encode ) {
		if ( isset( $parts['user'] ) )
			$parts['user']     = rawurlencode( $parts['user'] );
		if ( isset( $parts['pass'] ) )
			$parts['pass']     = rawurlencode( $parts['pass'] );
		if ( isset( $parts['host'] ) &&
			!preg_match( '!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host'] ) )
			$parts['host']     = rawurlencode( $parts['host'] );
		if ( !empty( $parts['path'] ) )
			$parts['path']     = preg_replace( '!%2F!ui', '/',
				rawurlencode( $parts['path'] ) );
		if ( isset( $parts['query'] ) )
			$parts['query']    = rawurlencode( $parts['query'] );
		if ( isset( $parts['fragment'] ) )
			$parts['fragment'] = rawurlencode( $parts['fragment'] );
	}

	$url = '';
	if ( !empty( $parts['scheme'] ) )
		$url .= $parts['scheme'] . ':';
	if ( isset( $parts['host'] ) ) {
		$url .= '//';
		if ( isset( $parts['user'] ) ) {
			$url .= $parts['user'];
			if ( isset( $parts['pass'] ) ) $url .= ':' . $parts['pass'];
			$url .= '@';
		}
		if ( preg_match( '!^[\da-f]*:[\da-f.:]+$!ui', $parts['host'] ) )
			$url .= '[' . $parts['host'] . ']'; // IPv6
		else
			$url .= $parts['host'];             // IPv4 or name
		if ( isset( $parts['port'] ) ) $url .= ':' . $parts['port'];
		if ( !empty( $parts['path'] ) && $parts['path'][0] != '/' ) $url .= '/';
	}
	if ( !empty( $parts['path'] ) ) $url .= $parts['path'];
	if ( isset( $parts['query'] ) ) $url .= '?' . $parts['query'];
	if ( isset( $parts['fragment'] ) ) $url .= '#' . $parts['fragment'];
	return $url;
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
	die("<div style='font-family:Verdana,Sans-serif;font-size:14px;font-weight:bold;text-align:center;'>$text</div>");
}


/*---------------------------------------------------+
| XSS prevention functions, borrowed from the v8 code|
+---------------------------------------------------*/

// check variables for xss attacks, and clean them
function xss_clean($str) {

	static $never_allowed_str, $never_allowed_regex;

	// never allowed, string replacement
	if (!isset($never_allowed_str)) {
		$never_allowed_str = array(
			'document.cookie'	=> '[--XSS--]',
			'document.write'	=> '[--XSS--]',
			'.parentNode'		=> '[--XSS--]',
			'.innerHTML'		=> '[--XSS--]',
			'window.location'	=> '[--XSS--]',
			'-moz-binding'		=> '[--XSS--]',
			'<!--'				=> '&lt;!--',
			'-->'				=> '--&gt;',
			'<![CDATA['			=> '&lt;![CDATA['
		);
	}

	// never allowed, regex replacement
	if (!isset($never_allowed_regex)) {
		$never_allowed_regex = array(
			"javascript\s*:"	=> '[--XSS--]',
			"expression\s*\("	=> '[--XSS--]', // CSS and IE
			"Redirect\s+302"	=> '[--XSS--]'
		);
	}

	// is the argument passed an array?
	if (is_array($str)) {
		while (list($key) = each($str)) {
			$str[$key] = xss_clean($str[$key]);
		}
		return $str;
	}

	// Remove Invisible Characters
	$str = _remove_invisible_characters($str);

	// Protect GET variables in URLs
	$str = preg_replace('|\&([a-z\_0-9]+)\=([a-z\_0-9]+)|i', xss_hash()."\\1=\\2", $str);

	// Validate standard character entities
	// Add a semicolon if missing.  We do this to enable the conversion of entities to ASCII later.
	$str = preg_replace('#(&\#?[0-9a-z]+)[\x00-\x20]*;?#i', "\\1;", $str);

	// Validate UTF16 two byte encoding (x00). Just as above, adds a semicolon if missing.
	$str = preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$str);

	// Un-Protect GET variables in URLs
	$str = str_replace(xss_hash(), '&', $str);

	// URL Decode
	// Just in case stuff like this is submitted:
	// <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
	$str = rawurldecode($str);

	// Convert character entities to ASCII
	// This permits our tests below to work reliably.
	// We only convert entities that are within tags since
	// these are the ones that will pose security problems.
	$str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", '_convert_attribute', $str);
	$str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", '_html_entity_decode_callback', $str);

	// Remove Invisible Characters Again!
	$str = _remove_invisible_characters($str);

	// Convert all tabs to spaces
	// This prevents strings like this: ja	vascript
	// NOTE: we deal with spaces between characters later.
	// NOTE: preg_replace was found to be amazingly slow here on large blocks of data,
	//	     so we use str_replace.
	if (strpos($str, "\t") !== false) {
		$str = str_replace("\t", ' ', $str);
	}

	// Capture converted string for later comparison
	$converted_string = $str;

	// Not Allowed Under Any Conditions
	foreach ($never_allowed_str as $key => $val) {
		$str = str_replace($key, $val, $str);
	}
	foreach ($never_allowed_regex as $key => $val) {
		$str = preg_replace("#".$key."#i", $val, $str);
	}

	// Makes PHP tags safe
	// Note: XML tags are inadvertently replaced too:
	// <?xml
	// But it doesn't seem to pose a problem.
	$str = str_replace(array('<?php', '<?PHP', '<?', '?'.'>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);

	// Compact any exploded words
	// This corrects words like:  j a v a s c r i p t
	// These words are compacted back to their correct state.
	$words = array('javascript', 'expression', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
	foreach ($words as $word) {
		$temp = '';
		for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++)	{
			$temp .= substr($word, $i, 1)."\s*";
		}
		// We only want to do this when it is followed by a non-word character
		// That way valid stuff like "dealer to" does not become "dealerto"
		$str = preg_replace_callback('#('.substr($temp, 0, -3).')(\W)#is', '_compact_exploded_words', $str);
	}

	// Remove disallowed Javascript in links or img tags
	// We used to do some version comparisons and use of stripos for PHP5, but it is dog slow compared
	// to these simplified non-capturing preg_match(), especially if the pattern exists in the string
	do {
		$original = $str;
		if (preg_match("/<a/i", $str)) {
			$str = preg_replace_callback("#<a\s*([^>]*?)(>|$)#si", '_js_link_removal', $str);
		}
		if (preg_match("/<img/i", $str)) {
			$str = preg_replace_callback("#<img\s*([^>]*?)(>|$)#si", '_js_img_removal', $str);
		}
		if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str)) {
			$str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[--XSS--]', $str);
		}
	}
	while($original != $str);

	unset($original);

	// Remove JavaScript Event Handlers
	// Note: This code is a little blunt.  It removes
	// the event handler and anything up to the closing >,
	// but it's unlikely to be a problem.
	$event_handlers = array('on\w*','xmlns');
	$str = preg_replace("#<([^><]+)(".implode('|', $event_handlers).")(\s*=\s*[^><]*)([><]*)#i", "<\\1\\4", $str);

	// Sanitize naughty HTML elements
	// If a tag containing any of the words in the list
	// below is found, the tag gets converted to entities.
	// So this: <blink> Becomes: &lt;blink&gt;
	$naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
	$str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', '_sanitize_naughty_html', $str);

	// Sanitize naughty scripting elements
	// Similar to above, only instead of looking for
	// tags it looks for PHP and JavaScript commands
	// that are disallowed.  Rather than removing the
	// code, it simply converts the parenthesis to entities
	// rendering the code un-executable.
	// For example:	eval('some code') Becomes: eval&#40;'some code'&#41;
	$str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);

	// Final clean up
	// This adds a bit of extra precaution in case
	// something got through the above filters
	foreach ($never_allowed_str as $key => $val) {
		$str = str_replace($key, $val, $str);
	}
	foreach ($never_allowed_regex as $key => $val) {
		$str = preg_replace("#".$key."#i", $val, $str);
	}

	// return the result
	return $str;
}

// Random Hash for protecting URLs
function xss_hash()	{

	static $xss_hash;

	if (!isset($xss_hash)) {
		if (phpversion() >= 4.2) {
			mt_srand();
		} else {
			mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);
		}
		$xss_hash = md5(time() + mt_rand(0, 1999999999));
	}

	return $xss_hash;
}

// Remove Invisible Characters
function _remove_invisible_characters($str)	{

	static $non_displayables;

	if (!isset($non_displayables)) {
		// every control character except newline (10), carriage return (13), and horizontal tab (09),
		// both as a URL encoded character (::shakes fist at IE and WebKit::), and the actual character
		$non_displayables = array(
			'/%0[0-8]/', '/[\x00-\x08]/',			// 00-08
			'/%11/', '/\x0b/', '/%12/', '/\x0c/',	// 11, 12
			'/%1[4-9]/', '/%2[0-9]/', '/%3[0-1]/',	// url encoded 14-31
			'/[\x0e-\x1f]/');						// 14-31
	}
	do {
		$cleaned = $str;
		$str = preg_replace($non_displayables, '', $str);
	}
	while ($cleaned != $str);

	return $str;
}

// Compact Exploded Words
function _compact_exploded_words($matches) {

	return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
}

// Sanitize Naughty HTML
function _sanitize_naughty_html($matches) {

	// encode opening brace
	$str = '&lt;'.$matches[1].$matches[2].$matches[3];

	// encode captured opening or closing brace to prevent recursive vectors
	$str .= str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);

	return $str;
}

// JS Link Removal
function _js_link_removal($match) {
	$attributes = _filter_attributes(str_replace(array('<', '>'), '', $match[1]));
	return str_replace($match[1], preg_replace("#href=.*?(alert\(|alert&\#40;|javascript\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
}

// JS Image Removal
function _js_img_removal($match) {
	$attributes = _filter_attributes(str_replace(array('<', '>'), '', $match[1]));
	return str_replace($match[1], preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
}

// Attribute Conversion
function _convert_attribute($match) {
	return str_replace(array('>', '<'), array('&gt;', '&lt;'), $match[0]);
}

// HTML Entity Decode Callback
function _html_entity_decode_callback($match) {

	global $settings;
	return html_entity_decode($match[0], strtoupper($settings['charset']));
}

//  Filters tag attributes for consistency and safety
function _filter_attributes($str) {

	$out = '';
	if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches)) {
		foreach ($matches[0] as $match) {
			$out .= "{$match}";
		}
	}
	return $out;
}
?>
