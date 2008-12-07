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
if (eregi("authentication.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

/*---------------------------------------------------+
| ExiteCMS authentication class                      |
+---------------------------------------------------*/
class auth_openid {

	/*-----------------------------------------------+
	| local class variables                          |
	+-----------------------------------------------*/

	// variable to store the user record
	var $userrecord = false;
	
	// class constructor
	function auth_openid() {
	}

	/*-----------------------------------------------+
	| public class methods                           |
	+-----------------------------------------------*/

	// logon function
	// returns true if succeeded, false if failed
	function logon($params) {
		global $db_prefix, $settings;

		// step 1: redirect to the openid provider of choice

		// do we have a (valid) openid URL?
		if (isset($params['openid_url']) && isURL($params['openid_url'])) {
			// load the openid class, and redirect to the openid provider
			require_once(PATH_INCLUDES."authentication/class.openid.php");
			$openid = new SimpleOpenID;
			$openid->SetIdentity($params['openid_url']);
			$openid->SetApprovedURL($settings['siteurl']."setuser.php");
			$openid->SetTrustRoot($settings['siteurl']);
			$server_url = $openid->GetOpenIDServer();
			if ($server_url) {
				redirect($openid->GetRedirectURL() , "script");
				exit;
			}
		}

		// step 2: validate the openid provider return information

		if (isset($params['openid_mode'])) {
			// handle openid login
			require_once(PATH_INCLUDES."authentication/class.openid.php");
			$openid = new SimpleOpenID;
			$openid->SetIdentity(urldecode($_GET['openid_identity']));
			if ($openid->ValidateWithServer()) {
				$openid_url = strtolower($openid->OpenID_Standarize($_GET['openid_identity']));
				$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_openid_url='".$openid_url."'");
				if (dbrows($result) == 0) {
					// not found, display an error message
					return false;
				} else {
					// retrieve the record and store it for retrieval
					$this->userrecord = dbarray($result);
					return true;
				}
			} else {
				trigger_error($openid->GetError());
				exit;
			}
		}
		return false;		
	}

	// logout function
	function logoff() {

		// reset the userrecord
		$this->userrecord = false;

	}

	/*-----------------------------------------------+
	| private class methods                          |
	+-----------------------------------------------*/

}
?>
