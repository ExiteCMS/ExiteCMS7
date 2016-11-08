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
if (strpos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false || !defined('INIT_CMS_OK')) die();

// update the PHP session settings with the info from the CMS configuration table
ini_set('session.name', $settings['session_name']);
ini_set('session.gc_probability', $settings['session_gc_probability']);
ini_set('session.gc_divisor', $settings['session_gc_divisor']);
ini_set('session.use_only_cookies', true);
if ($settings['session_gc_maxlifetime']) {
	ini_set('session.gc_maxlifetime', $settings['session_gc_maxlifetime']);
}

// register our custom session handler
session_set_save_handler ("_open_session", "_close_session", "_read_session", "_write_session", "_destroy_session", "_gc_session");

// make sure the session cookie times out at the same time as the session record
session_set_cookie_params($settings['session_gc_maxlifetime'], "/", "", false);

// disable the default session caching. very annoying
session_cache_limiter("none");

// when called from FancyUpload, set the session cookies from the post variable
// to stay in the same session when uploading file(s)
// (session hijacking is mitigated by the session_ua function)
if (isset($_GET['action']) && $_GET['action'] == "fancyupload" && isset($_GET[$settings['session_name']]) && !empty($_GET[$settings['session_name']])) {
	session_id($_GET[$settings['session_name']]);
}

// start the session
session_start();

// update the timestamp of the session cookie, we want expiry after the last page load, not after the session has started!
setcookie(ini_get("session.name"), session_id(), time()+$settings['session_gc_maxlifetime'], "/");

// store the last_url cookie if found
if (isset($_COOKIE['last_url']) && (isURL($_COOKIE['last_url']) || isURL($settings['siteurl'].$_COOKIE['last_url'])) && $_COOKIE['last_url'] != BASEDIR."setuser.php?login=yes") {
	$_SESSION['last_url'] = stripinput($_COOKIE['last_url']);
}

// remove any rubbish from the session record
if (isset($_SESSION['locale']) && is_array($_SESSION['locale'])) {
	unset($_SESSION['locale']);
}

// mark all variables in flash as used, so they get deleted at the end of this page request
if (isset($_SESSION['_flash'])) {
	foreach($_SESSION['_flash'] as $key => $value) {
		$_SESSION['_flash'][$key]['used'] = true;
	}
}

/*---------------------------------------------------+
| Session related global functions                   |
+---------------------------------------------------*/

// regenerates the session id.
// Call this EVERY time the users privilege level changes!
function regenerate_session() {

	// saves the old session's id
	$oldSessionID = session_id();

	// regenerates the id
	// this function will create a new session, with a new id and containing the data from
	// the old session but will not delete the old session
	session_regenerate_id();

	// because the session_regenerate_id() function does not delete the old session,
	// we have to delete it manually
	destroy_session($oldSessionID);

}

// Deletes all data related to the session
function stop_session() {

	// Regenerates the session id, makes the old session ID void
	regenerate_session();

	// unset the session variables
	session_unset();

	// and destroy the session
	session_destroy();
}

// store a variable in the flash session store
function session_set_flash($name, $var) {

	// check parameters
	if (empty($name) || empty($var)) return;

	// make sure the flash variable exists
	if (!isset($_SESSION['_flash'])) $_SESSION['_flash'] = array();

	// add the variable to the session flash store
	$_SESSION['_flash'][$name] = array('var' => $var, 'used' => false);
}

// retrieve a variable from the flash session store
function session_get_flash($name) {
	// return the flash value
	if (empty($name) || !isset($_SESSION['_flash'][$name])) {
		return false;
	} else {
		$_SESSION['_flash'][$name]['used'] = true;
		return $_SESSION['_flash'][$name]['var'];
	}
}

// clean session close, cleanup the session variable before writing it
function session_clean_close() {

	// remove any expired posts trackers
	if (isset($_SESSION['posts']) && is_array($_SESSION['posts'])) {
		foreach($_SESSION['posts'] as $key => $value) {
			if ($value < time()) unset($_SESSION['posts'][$key]);
		}
	}

	// remove any expired pm trackers
	if (isset($_SESSION['pm']) && is_array($_SESSION['pm'])) {
		foreach($_SESSION['pm'] as $key => $value) {
			if ($value < time()) unset($_SESSION['pm'][$key]);
		}
	}

	// remove all used (or marked as used) flash variables
	if (isset($_SESSION['_flash'])) {
		foreach($_SESSION['_flash'] as $key => $value) {
			if ($_SESSION['_flash'][$key]['used']) {
				unset($_SESSION['_flash'][$key]);
			}
		}
	}
	// and write the session
	session_write_close();

}

/*---------------------------------------------------+
| Custom session handler functions                   |
+---------------------------------------------------*/

// custom open() function
function _open_session($save_path,$session_name) {

	return true;

}

// custom close() function
function _close_session() {

	return true;

}

// custom read() function
function _read_session($session_id) {

	global $db_prefix;

	// check for the site_visited cookie or the FUID. If not found, there can't be session data available
	if (!isset($_COOKIE['site_visited']) && !isset($_GET['FUID'])) return false;

	// get the session
	$result = dbquery("SELECT * FROM ".$db_prefix."sessions
						WHERE session_id='$session_id'
							AND session_ua='"._session_ua()."'
							AND session_expire >= ".time()
					);

	if (dbrows($result) == 1) {
		// if found, return the session data
		$sess_read = dbarray($result);
		return $sess_read['session_data'];
	} else {
		// if not found, return false
		return false;
	}
}

// custom write() function
function _write_session($session_id,$session_data) {

	global $db_prefix, $db_host, $db_user, $db_pass, $db_name, $_db_link, $settings, $userdata;

	// reconnect if needed, some php versions close the db on exit or die!
	if ( ! $_db_link)
	{
		$_db_link = dbconnect($db_host, $db_user, $db_pass, $db_name);
	}

	// only if any session data is passed
	if (!$session_data) {
		return false;
	} else {
		// check for the site_visited cookie. If not found, there can't be session data available
		if (!isset($_COOKIE['site_visited'])) return false;

		// define the expiration time of this session
		if ($settings['session_gc_maxlifetime']) {
			$session_expire = time() + $settings['session_gc_maxlifetime'];
		} else {
			// expiration is set to 1/1/2038 (equals NEVER ;-)
			$session_expire = mktime(0,0,0,1,1,2038);
		}
		// determine the userid
		if (!defined('iMEMBER') || !iMEMBER) {
			$session_user_id = 0;
		} else {
			$session_user_id = $userdata['user_id'];
		}

		// insert or update the session information
		$result = dbquery("INSERT INTO ".$db_prefix."sessions (session_id, session_ua, session_started, session_expire, session_ip, session_user_id, session_data)
						VALUES ('$session_id', '"._session_ua()."', '".time()."', '$session_expire', '".USER_IP."', '".$session_user_id."', '".mysqli_real_escape_string($_db_link, $session_data)."')
						ON DUPLICATE KEY UPDATE session_data = '".mysqli_real_escape_string($_db_link, $session_data)."', session_ua = '"._session_ua()."', session_expire = '$session_expire', session_ip = '".USER_IP."', session_user_id = '".$session_user_id."'"
					);

		mysqli_close($_db_link);

		return true;
	}
}

// custom destroy() function
function _destroy_session($session_id) {

	global $db_prefix;

	// delete the requested session record
	$result = dbquery("DELETE FROM ".$db_prefix."sessions WHERE session_id = '$session_id'");

	return true;
}

// custom garbage collection function
function _gc_session() {

	global $db_prefix;

	// delete all expired records
	$result = dbquery("DELETE FROM ".$db_prefix."sessions WHERE session_expire < ".time());

	return true;
}

// generate the session_ua. It provides sort of two-factor authentication, using
// what you have (session cookie and site_visited cookie) and what you are (IP information)
// can't include the user agent here, because FancyUpload uses a different agent string!
function _session_ua() {

	$session_ua = "";

	// when called from FancyUpload, set the session cookies from the post variable
	// to stay in the same session when uploading file(s)
	if (isset($_GET['FUID']) && !empty($_GET['FUID'])) {
		return $_GET['FUID'];
	}
	$session_ua .= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
//	$session_ua .= isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
	$session_ua .= isset($_COOKIE['site_visited']) ? $_COOKIE['site_visited'] : "";

	return md5($session_ua);
}
?>
