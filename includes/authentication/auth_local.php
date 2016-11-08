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

/*---------------------------------------------------+
| ExiteCMS authentication class                      |
+---------------------------------------------------*/
class auth_local {

	/*-----------------------------------------------+
	| local class variables                          |
	+-----------------------------------------------*/

	// variable to store the user record
	var $userrecord = false;

	// class constructor
	function auth_local() {
	}

	/*-----------------------------------------------+
	| public class methods                           |
	+-----------------------------------------------*/

	// logon function
	// returns true if succeeded, false if failed
	function logon($params) {
		global $db_prefix, $user_id;

		// we need a username and password from the parameter array
		if (!isset($params['username']) || !isset($params['password'])) {
			return false;
		}

		// check and validate the given userid and password
		$user_pass = md5(md5($params['password']));
		$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($params['username']));

		// check if we have a user record for this userid and password
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='".$user_name."' AND user_password='".$user_pass."'");
		if (dbrows($result) == 0) {
			// not found, display an error message
			return false;
		} else {
			// retrieve the record and store it for retrieval
			$this->userrecord = dbarray($result);
			return true;
		}
	}

	// perform method specific post logon actions
	function post_logon() {

		return true;
	}

	// logout function
	function logoff() {

		// reset the userrecord
		$this->userrecord = false;
	}

	// get the template for this authentication method
	function get_template($type = "side") {

		if ($type == "side") {
			return PATH_INCLUDES."authentication/templates/auth.username_password.side.tpl";
		} else {
			return PATH_INCLUDES."authentication/templates/auth.username_password.body.tpl";
		}
	}

	/*-----------------------------------------------+
	| private class methods                          |
	+-----------------------------------------------*/

}
?>
