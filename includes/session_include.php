<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
if (eregi("session_include.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// **TODO** need to move these to CMSconfig!
$settings['session_gc_maxlifetime'] = 60*24*30;
$settings['session_name'] = "ExiteCMSid";
$settings['session_gc_probability'] = 1;
$settings['session_gc_divisor'] = 100;

// update the PHP session settings with the info from CMSconfig
ini_set('session.name', $settings['session_name']);
ini_set('session.gc_maxlifetime', $settings['session_gc_maxlifetime']);
ini_set('session.gc_probability', $settings['session_gc_probability']);
ini_set('session.gc_divisor', $settings['session_gc_divisor']);

// register our custom session handler
session_set_save_handler ("_open_session", "_close_session", "_read_session", "_write_session", "_destroy_session", "_gc_session");

// make sure the session cookie times out at the same time as the session record
session_set_cookie_params($settings['session_gc_maxlifetime'], "/", "", false);

// start the session
session_start();

/*---------------------------------------------------+
| Session related global functions                   |
+---------------------------------------------------*/

// regenerates the session id. 
// Call this EVERY time the users priveledge level changes!
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

	// get the session 
	$result = dbquery("SELECT * FROM ".$db_prefix."sessions 
						WHERE session_id='$session_id' 
							AND session_ua='".mysql_real_escape_string(md5($_SERVER["HTTP_USER_AGENT"] .( iMEMBER ? md5($userdata['username']) : md5("SeCuRiTyCoDe"))))."'
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

	global $db_prefix;

	// only if any session data is passed
	if (!$session_data) {
		return false;
	} else {
		// define the expiration time of this session
		$session_expire = time() + $settings['session_gc_maxlifetime'];
		// insert or update the session information
		$result = dbquery("INSERT INTO ".$db_prefix."sessions (session_id, session_ua, session_started, session_expire, session_ip, session_data) 
						VALUES ('$session_id', '".mysql_real_escape_string(md5($_SERVER["HTTP_USER_AGENT"] .( iMEMBER ? md5($userdata['username']) : md5("SeCuRiTyCoDe"))))."', '".time()."', '$session_expire', '".USER_IP."', '$session_data')
						ON DUPLICATE KEY UPDATE session_data = '$session_data', session_expire = '$session_expire'"
					);
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
	$result = dbquery("DELETE FROM ".$db_prefix."sessions WHERE session_expire >= ".time());

	return true;
}
?>