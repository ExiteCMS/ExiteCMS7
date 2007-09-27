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
	if ((eregi("<[^>]*script*\"?[^>]*>", $check_url)) || (eregi("<[^>]*object*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*iframe*\"?[^>]*>", $check_url)) || (eregi("<[^>]*applet*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*meta*\"?[^>]*>", $check_url)) || (eregi("<[^>]*style*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*form*\"?[^>]*>", $check_url)) || (eregi("\([^>]*\"?[^)]*\)", $check_url))) {
	die ();
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
define("PATH_ATTACHMENTS", PATH_ROOT."files/attachments/");
define("PATH_PM_ATTACHMENTS", PATH_ROOT."files/pm_attachments/");

// mark that ExiteCMS is properly initialized
define("ExiteCMS_INIT", TRUE);

// load the config file
if (file_exists(PATH_ROOT."config.php")) {
	@include_once PATH_ROOT."config.php";
}
// if config.php is absent or empty, bail out with an error
if (!isset($db_name)) die('FATAL ERROR: config file is missing. Check the documentation on how to perform the setup');

// load the database functions, and establish a database connection
require_once PATH_INCLUDES."db_functions.php";

// fetch the Site Settings from the database and store them in the $settings variable
$settings = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));

// add static information to the settings array (NEED TO FIND A BETTER SOLUTION FOR THIS!)
$settings['timezones'] = array("-12","-11","-10","-9:30","-9","-8","-7","-6","-5","-4","-3:30","-3","-2:30","-2","-1","+0",
	"+0:30","+1","+2","+3","+3:30","+4","+4:30","+5","+5:30","+6","+6:30","+7","+7:30","+8","+8:30","+8:45","+9","+9:30",
	"+10","+10:30","+11","+11:30","+12","+12:45","+13","+14");

// get the relative path from the SiteURL 
// (basedir might be in a sub directory of the document root!)
define ("BASEDIR", strstr(substr(strstr($settings['siteurl'], '://'),3), '/'));

// URL path definitions relative to BASEDIR
define("LOCALESET", $settings['locale']."/");
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
	$_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? cleanurl($_SERVER['QUERY_STRING']) : "";
	$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? cleanurl($_SERVER['REQUEST_URI']) : "";
	$_SERVER['PHP_SELF'] = $_SERVER['PHP_SELF'];
	$PHP_SELF = cleanurl($_SERVER['PHP_SELF']);
	define("FUSION_REQUEST", isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
	define("FUSION_QUERY", isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "");
	define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
	define("USER_IP", $_SERVER['REMOTE_ADDR']);
	define("FUSION_URL", $PHP_SELF);
} else {
	// Common definitions - CLI mode
	define("USER_IP", '0.0.0.0');
}
define("QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));

// Browser window dimensions (assume 1024x768 if no cookies found)
define("BROWSER_WIDTH", isset($_COOKIE['width'])?$_COOKIE['width']:1024);
define("BROWSER_HEIGHT", isset($_COOKIE['height'])?$_COOKIE['height']:768);

// Initialise the $locale array
$locale = array();

// Load the global language file
include PATH_LOCALE.LOCALESET."global.php";

// load the user functions
require_once PATH_INCLUDES."user_functions.php";

// check for upgrades in progress.
if (!eregi("upgrade.php", $_SERVER['PHP_SELF'])) {
	include PATH_ADMIN."upgrade.php";
	//  If so, force a switch to maintenance mode
	if (UPGRADES) $settings['maintenance'] = 2;
}

// check if we need to redirect to maintenance mode
if (!iADMIN && $settings['maintenance']) {
	// only if not called from the maintenance mode module! (to prevent a loop, endless ;-)
	if (!eregi("maintenance.php", $_SERVER['PHP_SELF'])) {
		redirect('maintenance.php?reason='.$settings['maintenance']);
	}
}

// image types we support
$imagetypes = array(
	".bmp",
	".gif",
	".iff",
	".jpg",
	".jpeg",
	".png",
	".psd",
	".tiff",
	".wbmp"
);

// image types we can generate a thumbnail from
$thumbtypes = array(
	".gif",
	".jpg",
	".jpeg",
	".png",
);

// debug function, handy to print a standard debug text
function _debug($text, $abort=false) {

	if (is_array($text)) {
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
	
	if ($type == "header") {
		header("Location: ".$location);
		die();
	} else {
		echo "<script type='text/javascript'>document.location.href='".$location."'</script>\n";
		echo sprintf($locale['182'], $location);
	}
}

// Fallback to safe area in event of unauthorised access
function fallback($location) {
	redirect($location, "header");
	exit;
}

// Clean URL Function, prevents entities in server globals
function cleanurl($url) {
	$bad_entities = array("&", "\"", "'", '\"', "\'", "<", ">", "(", ")");
	$safe_entities = array("&", "", "", "", "", "", "", "", "");
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

// stripslash function, add correct number of slashes depending on quotes_gpc
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
function trimlink($text, $length) {
	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length) $text = substr($text, 0, ($length-3))."...";
	$text = str_replace($dec, $enc, $text);
	return $text;
}

// Validate numeric input
function isNum($value) {
	return (preg_match("/^[0-9]+$/", $value));
}

// Validate decimal input
function isDec($value) {
	return (preg_match("/^[0-9]+\.[0-9][0-9]$/", $value));
}

// Parse smiley bbcode into HTML images
function parsesmileys($message) {
	$smiley = array(
		"#\;\)#si" => "<img src='".IMAGES."smiley/wink.gif' alt='smiley' />",
		"#\:\(#si" => "<img src='".IMAGES."smiley/sad.gif' alt='smiley' />",
		"#\:\|#si" => "<img src='".IMAGES."smiley/frown.gif' alt='smiley' />",
		"#\:o#si" => "<img src='".IMAGES."smiley/shock.gif' alt='smiley' />",
		"#\:p#si" => "<img src='".IMAGES."smiley/pfft.gif' alt='smiley' />",
		"#b\)#si" => "<img src='".IMAGES."smiley/cool.gif' alt='smiley' />",
		"#\:d#si" => "<img src='".IMAGES."smiley/grin.gif' alt='smiley' />",
		"#\:@#si" => "<img src='".IMAGES."smiley/angry.gif' alt='smiley' />",
		"#\:thumbleft#si" => "<img src='".IMAGES."smiley/more/icon_thumleft.gif' alt='smiley' />",
		"#\:thumbright#si" => "<img src='".IMAGES."smiley/more/icon_thumright.gif' alt='smiley' />",
		"#=D&gt;#si" => "<img src='".IMAGES."smiley/more/eusa_clap.gif' alt='smiley' />",
		"#\\\:D/#si" => "<img src='".IMAGES."smiley/more/eusa_dance.gif' alt='smiley' />",
		"#\:D#si" => "<img src='".IMAGES."smiley/more/biggrin.gif' alt='smiley' />",
		"#\:smt014#si" => "<img src='".IMAGES."smiley/more/014.gif' alt='smiley' />",
		"#\:boring#si" => "<img src='".IMAGES."smiley/more/015.gif' alt='smiley' />",
		"#\:smt018#si" => "<img src='".IMAGES."smiley/more/018.gif' alt='smiley' />",
		"#\:smt022#si" => "<img src='".IMAGES."smiley/more/022.gif' alt='smiley' />",
		"#\:smt071#si" => "<img src='".IMAGES."smiley/more/071.gif' alt='smiley' />",
		"#\:smt102#si" => "<img src='".IMAGES."smiley/more/102.gif' alt='smiley' />",
		"#\:smt100#si" => "<img src='".IMAGES."smiley/more/100.gif' alt='smiley' />",
		"#\:\-D#si" => "<img src='".IMAGES."smiley/more/003.gif' alt='smiley' />",
		"#\:\-\)#si" => "<img src='".IMAGES."smiley/more/001.gif' alt='smiley' />",
		"#\:\(#si" => "<img src='".IMAGES."smiley/more/sad.gif' alt='smiley' />",
		"#\:o#si" => "<img src='".IMAGES."smiley/more/surprised.gif' alt='smiley' />",
		"#\:shock\:#si" => "<img src='".IMAGES."smiley/more/shock.gif' alt='smiley' />",
		"#8\)#si" => "<img src='".IMAGES."smiley/more/cool.gif' alt='smiley' />",
		"#\:lol\:#si" => "<img src='".IMAGES."smiley/more/lol.gif' alt='smiley' />",
		"#\:x#si" => "<img src='".IMAGES."smiley/more/mad.gif' alt='smiley' />",
		"#\:\-x#si" => "<img src='".IMAGES."smiley/more/icon_mad.gif' alt='smiley' />",
		"#\:P#si" => "<img src='".IMAGES."smiley/more/icon_razz.gif' alt='smiley' />",
		"#\:razz\:#si" => "<img src='".IMAGES."smiley/more/razz.gif' alt='smiley' />",
		"#\:oops\:#si" => "<img src='".IMAGES."smiley/more/redface.gif' alt='smiley' />",
		"#\:cry\:#si" => "<img src='".IMAGES."smiley/more/cry.gif' alt='smiley' />",
		"#\:evil\:#si" => "<img src='".IMAGES."smiley/more/evil.gif' alt='smiley' />",
		"#\:twisted\:#si" => "<img src='".IMAGES."smiley/more/icon_twisted.gif' alt='smiley' />",
		"#\:roll\:#si" => "<img src='".IMAGES."smiley/more/rolleyes.gif' alt='smiley' />",
		"#\:wink\:#si" => "<img src='".IMAGES."smiley/more/wink.gif' alt='smiley' />",
		"#\;\-\)#si" => "<img src='".IMAGES."smiley/more/002.gif' alt='smiley' />",
		"#\:\!\:#si" => "<img src='".IMAGES."smiley/more/exclaim.gif' alt='smiley' />",
		"#\:\?\:#si" => "<img src='".IMAGES."smiley/more/question.gif' alt='smiley' />",
		"#\:\?#si" => "<img src='".IMAGES."smiley/more/confused.gif' alt='smiley' />",
		"#\:idea\:#si" => "<img src='".IMAGES."smiley/more/idea.gif' alt='smiley' />",
		"#\:arrow\:#si" => "<img src='".IMAGES."smiley/more/arrow.gif' alt='smiley' />",
		"#\:\|#si" => "<img src='".IMAGES."smiley/more/neutral.gif' alt='smiley' />",
		"#\:mrgreen\:#si" => "<img src='".IMAGES."smiley/more/icon_mrgreen.gif' alt='smiley' />",
		"#\:badgrin\:#si" => "<img src='".IMAGES."smiley/more/badgrin.gif' alt='smiley' />",
		"#\:doubt\:#si" => "<img src='".IMAGES."smiley/more/doubt.gif' alt='smiley' />",
		"#\#\-o#si" => "<img src='".IMAGES."smiley/more/eusa_doh.gif' alt='smiley' />",
		"#\=P\~#si" => "<img src='".IMAGES."smiley/more/eusa_drool.gif' alt='smiley' />",
		"#\:\^o#si" => "<img src='".IMAGES."smiley/more/eusa_liar.gif' alt='smiley' />",
		"#\[\-X#si" => "<img src='".IMAGES."smiley/more/eusa_naughty.gif' alt='smiley' />",
		"#\[\-o\<\;#si" => "<img src='".IMAGES."smiley/more/eusa_pray.gif' alt='smiley' />",
		"#8\-\[#si" => "<img src='".IMAGES."smiley/more/eusa_shifty.gif' alt='smiley' />",
		"#\[\-\(#si" => "<img src='".IMAGES."smiley/more/eusa_snooty.gif' alt='smiley' />",
		"#\:\-k#si" => "<img src='".IMAGES."smiley/more/eusa_think.gif' alt='smiley' />",
		"#\]\(\*\,\)#si" => "<img src='".IMAGES."smiley/more/eusa_wall.gif' alt='smiley' />",
//		"#\:\-\"#si" => "<img src='".IMAGES."smiley/more/eusa_whistle.gif' alt='smiley' />",
		"#O\:\)#si" => "<img src='".IMAGES."smiley/more/eusa_angel.gif' alt='smiley' />",
		"#\=\;#si" => "<img src='".IMAGES."smiley/more/eusa_hand.gif' alt='smiley' />",
		"#\:\-\&#si" => "<img src='".IMAGES."smiley/more/eusa_sick.gif' alt='smiley' />",
		"#\:\-\(\{\|\=#si" => "<img src='".IMAGES."smiley/more/eusa_boohoo.gif' alt='smiley' />",
		"#\:\-\$#si" => "<img src='".IMAGES."smiley/more/eusa_shhh.gif' alt='smiley' />",
		"#\:\-s#si" => "<img src='".IMAGES."smiley/more/eusa_eh.gif' alt='smiley' />",
		"#\:\-\##si" => "<img src='".IMAGES."smiley/more/eusa_silenced.gif' alt='smiley' />",
		"#\:smt004#si" => "<img src='".IMAGES."smiley/more/004.gif' alt='smiley' />",
		"#\:smt005#si" => "<img src='".IMAGES."smiley/more/005.gif' alt='smiley' />",
		"#\:smt006#si" => "<img src='".IMAGES."smiley/more/006.gif' alt='smiley' />",
		"#\:smt007#si" => "<img src='".IMAGES."smiley/more/007.gif' alt='smiley' />",
		"#\:smt008#si" => "<img src='".IMAGES."smiley/more/008.gif' alt='smiley' />",
		"#\:smt009#si" => "<img src='".IMAGES."smiley/more/009.gif' alt='smiley' />",
		"#\:smt010#si" => "<img src='".IMAGES."smiley/more/010.gif' alt='smiley' />",
		"#\:smt011#si" => "<img src='".IMAGES."smiley/more/011.gif' alt='smiley' />",
		"#\:smt012#si" => "<img src='".IMAGES."smiley/more/012.gif' alt='smiley' />",
		"#\:smt013#si" => "<img src='".IMAGES."smiley/more/013.gif' alt='smiley' />",
		"#\:smt016#si" => "<img src='".IMAGES."smiley/more/016.gif' alt='smiley' />",
		"#\:smt017#si" => "<img src='".IMAGES."smiley/more/017.gif' alt='smiley' />",
		"#\:smt019#si" => "<img src='".IMAGES."smiley/more/019.gif' alt='smiley' />",
		"#\:smt020#si" => "<img src='".IMAGES."smiley/more/020.gif' alt='smiley' />",
		"#\:smt021#si" => "<img src='".IMAGES."smiley/more/021.gif' alt='smiley' />",
		"#\:smt023#si" => "<img src='".IMAGES."smiley/more/023.gif' alt='smiley' />",
		"#\:smt024#si" => "<img src='".IMAGES."smiley/more/024.gif' alt='smiley' />",
		"#\:smt025#si" => "<img src='".IMAGES."smiley/more/025.gif' alt='smiley' />",
		"#\:smt026#si" => "<img src='".IMAGES."smiley/more/026.gif' alt='smiley' />",
		"#\:smt027#si" => "<img src='".IMAGES."smiley/more/027.gif' alt='smiley' />",
		"#\:smt028#si" => "<img src='".IMAGES."smiley/more/028.gif' alt='smiley' />",
		"#\:smt029#si" => "<img src='".IMAGES."smiley/more/029.gif' alt='smiley' />",
		"#\:smt030#si" => "<img src='".IMAGES."smiley/more/030.gif' alt='smiley' />",
		"#\:smt031#si" => "<img src='".IMAGES."smiley/more/031.gif' alt='smiley' />",
		"#\:smt032#si" => "<img src='".IMAGES."smiley/more/032.gif' alt='smiley' />",
		"#\:smt033#si" => "<img src='".IMAGES."smiley/more/033.gif' alt='smiley' />",
		"#\:smt034#si" => "<img src='".IMAGES."smiley/more/034.gif' alt='smiley' />",
		"#\:smt035#si" => "<img src='".IMAGES."smiley/more/035.gif' alt='smiley' />",
		"#\:smt036#si" => "<img src='".IMAGES."smiley/more/036.gif' alt='smiley' />",
		"#\:smt037#si" => "<img src='".IMAGES."smiley/more/037.gif' alt='smiley' />",
		"#\:smt038#si" => "<img src='".IMAGES."smiley/more/038.gif' alt='smiley' />",
		"#\:smt039#si" => "<img src='".IMAGES."smiley/more/039.gif' alt='smiley' />",
		"#\:smt040#si" => "<img src='".IMAGES."smiley/more/040.gif' alt='smiley' />",
		"#\:smt041#si" => "<img src='".IMAGES."smiley/more/041.gif' alt='smiley' />",
		"#\:smt042#si" => "<img src='".IMAGES."smiley/more/042.gif' alt='smiley' />",
		"#\:smt043#si" => "<img src='".IMAGES."smiley/more/043.gif' alt='smiley' />",
		"#\:smt044#si" => "<img src='".IMAGES."smiley/more/044.gif' alt='smiley' />",
		"#\:smt045#si" => "<img src='".IMAGES."smiley/more/045.gif' alt='smiley' />",
		"#\:smt046#si" => "<img src='".IMAGES."smiley/more/046.gif' alt='smiley' />",
		"#\:smt047#si" => "<img src='".IMAGES."smiley/more/047.gif' alt='smiley' />",
		"#\:smt048#si" => "<img src='".IMAGES."smiley/more/048.gif' alt='smiley' />",
		"#\:smt049#si" => "<img src='".IMAGES."smiley/more/049.gif' alt='smiley' />",
		"#\:smt050#si" => "<img src='".IMAGES."smiley/more/050.gif' alt='smiley' />",
		"#\:smt051#si" => "<img src='".IMAGES."smiley/more/051.gif' alt='smiley' />",
		"#\:smt052#si" => "<img src='".IMAGES."smiley/more/052.gif' alt='smiley' />",
		"#\:smt053#si" => "<img src='".IMAGES."smiley/more/053.gif' alt='smiley' />",
		"#\:smt054#si" => "<img src='".IMAGES."smiley/more/054.gif' alt='smiley' />",
		"#\:smt055#si" => "<img src='".IMAGES."smiley/more/055.gif' alt='smiley' />",
		"#\:smt056#si" => "<img src='".IMAGES."smiley/more/056.gif' alt='smiley' />",
		"#\:smt057#si" => "<img src='".IMAGES."smiley/more/057.gif' alt='smiley' />",
		"#\:smt058#si" => "<img src='".IMAGES."smiley/more/058.gif' alt='smiley' />",
		"#\:smt059#si" => "<img src='".IMAGES."smiley/more/059.gif' alt='smiley' />",
		"#\:smt060#si" => "<img src='".IMAGES."smiley/more/060.gif' alt='smiley' />",
		"#\:smt061#si" => "<img src='".IMAGES."smiley/more/061.gif' alt='smiley' />",
		"#\:smt062#si" => "<img src='".IMAGES."smiley/more/062.gif' alt='smiley' />",
		"#\:smt063#si" => "<img src='".IMAGES."smiley/more/063.gif' alt='smiley' />",
		"#\:smt064#si" => "<img src='".IMAGES."smiley/more/064.gif' alt='smiley' />",
		"#\:smt065#si" => "<img src='".IMAGES."smiley/more/065.gif' alt='smiley' />",
		"#\:smt066#si" => "<img src='".IMAGES."smiley/more/066.gif' alt='smiley' />",
		"#\:smt067#si" => "<img src='".IMAGES."smiley/more/067.gif' alt='smiley' />",
		"#\:smt068#si" => "<img src='".IMAGES."smiley/more/068.gif' alt='smiley' />",
		"#\:smt069#si" => "<img src='".IMAGES."smiley/more/069.gif' alt='smiley' />",
		"#\:smt070#si" => "<img src='".IMAGES."smiley/more/070.gif' alt='smiley' />",
		"#\:smt073#si" => "<img src='".IMAGES."smiley/more/073.gif' alt='smiley' />",
		"#\:smt074#si" => "<img src='".IMAGES."smiley/more/074.gif' alt='smiley' />",
		"#\:smt075#si" => "<img src='".IMAGES."smiley/more/075.gif' alt='smiley' />",
		"#\:smt076#si" => "<img src='".IMAGES."smiley/more/076.gif' alt='smiley' />",
		"#\:smt077#si" => "<img src='".IMAGES."smiley/more/077.gif' alt='smiley' />",
		"#\:smt078#si" => "<img src='".IMAGES."smiley/more/078.gif' alt='smiley' />",
		"#\:smt079#si" => "<img src='".IMAGES."smiley/more/079.gif' alt='smiley' />",
		"#\:smt080#si" => "<img src='".IMAGES."smiley/more/080.gif' alt='smiley' />",
		"#\:smt081#si" => "<img src='".IMAGES."smiley/more/081.gif' alt='smiley' />",
		"#\:smt082#si" => "<img src='".IMAGES."smiley/more/082.gif' alt='smiley' />",
		"#\:smt083#si" => "<img src='".IMAGES."smiley/more/083.gif' alt='smiley' />",
		"#\:smt084#si" => "<img src='".IMAGES."smiley/more/084.gif' alt='smiley' />",
		"#\:smt085#si" => "<img src='".IMAGES."smiley/more/085.gif' alt='smiley' />",
		"#\:smt086#si" => "<img src='".IMAGES."smiley/more/086.gif' alt='smiley' />",
		"#\:smt087#si" => "<img src='".IMAGES."smiley/more/087.gif' alt='smiley' />",
		"#\:smt088#si" => "<img src='".IMAGES."smiley/more/088.gif' alt='smiley' />",
		"#\:smt089#si" => "<img src='".IMAGES."smiley/more/089.gif' alt='smiley' />",
		"#\:smt090#si" => "<img src='".IMAGES."smiley/more/090.gif' alt='smiley' />",
		"#\:smt091#si" => "<img src='".IMAGES."smiley/more/091.gif' alt='smiley' />",
		"#\:smt092#si" => "<img src='".IMAGES."smiley/more/092.gif' alt='smiley' />",
		"#\:smt093#si" => "<img src='".IMAGES."smiley/more/093.gif' alt='smiley' />",
		"#\:smt084#si" => "<img src='".IMAGES."smiley/more/094.gif' alt='smiley' />",
		"#\:smt095#si" => "<img src='".IMAGES."smiley/more/095.gif' alt='smiley' />",
		"#\:smt096#si" => "<img src='".IMAGES."smiley/more/096.gif' alt='smiley' />",
		"#\:smt097#si" => "<img src='".IMAGES."smiley/more/097.gif' alt='smiley' />",
		"#\:smt098#si" => "<img src='".IMAGES."smiley/more/098.gif' alt='smiley' />",
		"#\:smt099#si" => "<img src='".IMAGES."smiley/more/099.gif' alt='smiley' />",
		"#\:smt101#si" => "<img src='".IMAGES."smiley/more/101.gif' alt='smiley' />",
		"#\:smt103#si" => "<img src='".IMAGES."smiley/more/103.gif' alt='smiley' />",
		"#\:smt104#si" => "<img src='".IMAGES."smiley/more/104.gif' alt='smiley' />",
		"#\:smt105#si" => "<img src='".IMAGES."smiley/more/105.gif' alt='smiley' />",
		"#\:smt106#si" => "<img src='".IMAGES."smiley/more/106.gif' alt='smiley' />",
		"#\:smt107#si" => "<img src='".IMAGES."smiley/more/107.gif' alt='smiley' />",
		"#\:smt108#si" => "<img src='".IMAGES."smiley/more/108.gif' alt='smiley' />",
		"#\:smt109#si" => "<img src='".IMAGES."smiley/more/109.gif' alt='smiley' />",
		"#\:smt110#si" => "<img src='".IMAGES."smiley/more/110.gif' alt='smiley' />",
		"#\:smt111#si" => "<img src='".IMAGES."smiley/more/111.gif' alt='smiley' />",
		"#\:smt112#si" => "<img src='".IMAGES."smiley/more/112.gif' alt='smiley' />",
		"#\:smt113#si" => "<img src='".IMAGES."smiley/more/113.gif' alt='smiley' />",
		"#\:smt114#si" => "<img src='".IMAGES."smiley/more/114.gif' alt='smiley' />",
		"#\:smt115#si" => "<img src='".IMAGES."smiley/more/115.gif' alt='smiley' />",
		"#\:smt116#si" => "<img src='".IMAGES."smiley/more/116.gif' alt='smiley' />",
		"#\:smt117#si" => "<img src='".IMAGES."smiley/more/117.gif' alt='smiley' />",
		"#\:smt118#si" => "<img src='".IMAGES."smiley/more/118.gif' alt='smiley' />",
		"#\:smt119#si" => "<img src='".IMAGES."smiley/more/119.gif' alt='smiley' />",
		"#\:smt120#si" => "<img src='".IMAGES."smiley/more/120.gif' alt='smiley' />",
		"#\:\)#si" => "<img src='".IMAGES."smiley/smile.gif' alt='smiley' />"
	);
	foreach($smiley as $key=>$smiley_img) {
		$message = preg_replace($key, $smiley_img, $message);
	}
	return $message;
}

// Parse bbcode into HTML code
function parseubb($text) {
	global $locale;
	
	$text = preg_replace('#\[li\](.*?)\[/li\]#si', '<li>\1</li>', $text);
	$text = preg_replace('#\[ul\](.*?)\[/ul\]#si', '<ul>\1</ul>', $text);

	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $text);
	$text = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $text);
	$text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
	$text = preg_replace('#\[center\](.*?)\[/center\]#si', '<center>\1</center>', $text);

	// correct illegal [url=] BBcode
	$text = str_replace("[url=]", "[url]", $text);
		
	$text = preg_replace('#\[url\]([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\";\+]*?)([\r\n]*)\[/url\]#si', '<a href=\'\2\3\' target=\'_blank\'>\2\3</a>', $text);
	$text = preg_replace('#\[url\]([\r\n]*)([^\s\'\";\+]*?)([\r\n]*)\[/url\]#si', '<a href=\'http://\2\' target=\'_blank\'>\2</a>', $text);
	$text = preg_replace('#\[url=([\r\n]*)(http://|ftp://|https://|ftps://)([^\'\";]*?)\](.*?)([\r\n]*)\[/url\]#si', '<a href=\'\2\3\' target=\'_blank\'>\4</a>', $text);
	$text = preg_replace('#\[url=([\r\n]*)([^\s\'\";\+]*?)\](.*?)([\r\n]*)\[/url\]#si', '<a href=\'http://\2\' target=\'_blank\'>\3</a>', $text);
	
	$text = preg_replace('#\[mail\]([\r\n]*)([^\s\'\";:\+]*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);
	$text = preg_replace('#\[mail=([\r\n]*)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);
	
	$text = preg_replace('#\[small\](.*?)\[/small\]#si', '<span class=\'small\'>\1</span>', $text);
	$text = preg_replace('#\[color=(\#[0-9a-fA-F]{6}|black|blue|brown|cyan|gray|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/color\]#si', '<span style=\'color:\1\'>\2</span>', $text);
	
	$text = preg_replace('#\[flash width=([0-9]*?) height=([0-9]*?)\]([^\s\'\";:\+]*?)(\.swf)\[/flash\]#si', '<object classid=\'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\' codebase=\'http://active.macromedia.com/flash6/cabs/swflash.cab#version=6,0,0,0\' id=\'\3\4\' width=\'\1\' height=\'\2\'><param name=movie value=\'\3\4\'><param name=\'quality\' value=\'high\'><param name=\'bgcolor\' value=\'#ffffff\'><embed src=\'\3\4\' quality=\'high\' bgcolor=\'#ffffff\' width=\'\1\' height=\'\2\' type=\'application/x-shockwave-flash\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\'></embed></object>', $text);
	$text = preg_replace("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#sie","'<img src=\'\\1'.str_replace(array('.php','?','&','='),'','\\3').'\\4\' style=\'border:0px\' alt=\'\' />'",$text);

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
		//<span style="width: expression(alert('Ping!'));"></span> (only affects ie...)
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
	$txt = file_get_contents($file);
	$image_safe = true;
	if (preg_match('#&(quot|lt|gt|nbsp);#i', $txt)) { $image_safe = false; }
	elseif (preg_match("#&\#x([0-9a-f]+);#i", $txt)) { $image_safe = false; }
	elseif (preg_match('#&\#([0-9]+);#i', $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt)) { $image_safe = false; }
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
	return $res;
}

// Format the date & time accordingly
function showdate($format, $val=false) {
	global $settings;

	// if no date value is passed, use now
	if (!$val) $val = time();
	
	// return it in the format requested
	if ($format == "shortdate" || $format == "longdate" || $format == "forumdate" || $format == "subheaderdate") {
		return strftime($settings[$format], $val);
	} else {
		return strftime($format, $val);
	}
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

// Translate bytes into kb, mb, gb or tb by CrappoMan
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
			if (isset($_POST['remember_me'])) {
				setcookie("remember_me", "yes", time() + 31536000, "/", "", "0");
				$cookie_exp = time() + 3600*24*30;
			} else {
				setcookie("remember_me", "yes", time() - 7200, "/", "", "0");
				$cookie_exp = time() + 60*30;
			}
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie("userinfo", $cookie_value, $cookie_exp, "/", "", "0");
			return 0;
		} 
		return $data['user_status'];
	} else {
		return 3;	// user_status == 3: not_found
	}
}
?>