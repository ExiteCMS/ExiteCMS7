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
class authentication {

	/*-----------------------------------------------+
	| local class variables                          |
	+-----------------------------------------------*/

	// array to store the available methods
	var $methods = array();
	
	// array to store the selected authentication methods
	var $selected = array();

	// used to store the method used to logon (needed for logout and other functions!)
	var $method_used = false;

	// array to store the authentication classes
	var $classes = array();

	// used to store the current user record
	var $userrecord = false;

	// used to store the logon status code
	var $status = false;

	// used to store the available logon templates
	var $templates = array();

	// class constructor
	function authentication() {
		global $db_prefix, $settings;
		
		// load the available methods
		if (!empty($settings['authentication_methods'])) {
			// load the methods array
			$this->methods = unserialize($settings['authentication_methods']);
			$this->selected = explode(",", $settings['authentication_selected']);
		} else {
			// create the default method for local logon
			$this->methods['local'] = array('class' => "auth_local");
			$this->selected = array("local");
		}
		
		// load and instantiate the selected authentication classes
		foreach($this->methods as $name => $method) {
			$class = PATH_INCLUDES."authentication/".$method['class'].".php";
			if (file_exists($class)) {
				@include $class;
				$this->classes[$name] =& new $method['class']();
			} else {
				// defined class removed? User needs to run the security admin module to update!
			}
		}

		// do we have a logged on user?
		if ($this->logged_on()) {
			// restore the login method
			$this->method_used = $_SESSION['login_method'];
			// we have cached credentials in the session record. Restore the logon
			$userinfo_vars = explode(".", $_SESSION['userinfo']);
			$userinfo_1 = isNum($userinfo_vars['0']) ? $userinfo_vars['0'] : "0";
			$userinfo_2 = (preg_match("/^[0-9a-z]{32}$/", $userinfo_vars['1']) ? $userinfo_vars['1'] : "");
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$userinfo_1' AND user_password='$userinfo_2'");
			unset($userinfo_vars,$userinfo_1,$userinfo_2);
			if (dbrows($result) != 0) {
				$this->userrecord = dbarray($result);
			} else {
				// something fishy with the cached credentials, do a logout
				$this->logoff();
			}
		}

	}

	/*-----------------------------------------------+
	| public class methods                           |
	+-----------------------------------------------*/

	// logon function
	function logon($params) {

		// make sure we have the parameter array
		if (!isset($params) || !is_array($params)) {
			return false;
		}

		// loop through the selected logon functions
		foreach($this->selected as $method) {
			// check if the class exists and is loaded
			if (isset($this->methods[$method]) && is_object($this->classes[$method])) {
				// call the logon function of the class
				$result = $this->classes[$method]->logon($params);
				// did the logon succeed?
				if ($result) {
					// record the logon method used
					$this->method_used = $method;
					// call the post logon function of the class
					$result = $this->classes[$method]->post_logon();
					if ($result) {
						// execute post logon checks and validations
						return $this->_post_logon();
					} else {
						// class post-logon failed, report the login as failed
						return false;
					}
				}
			} else {
				_debug($this->methods);
				_debug($this->classes);
				_debug($this->selected);				
				die("can't find the class!");
			}
		}
		// all logon methods failed
		return false;
	}

	// check if a user is logged on
	function logged_on() {
		
			return isset($_SESSION['userinfo']);
	}

	// logout function
	function logoff() {
		global $db_prefix;

		// call the logoff function of the authentication method used
		if ($this->method_used) {
			$this->classes[$this->method_used]->logoff();
		}

		// remove logon information from the session record
		unset($_SESSION['user']);
		unset($_SESSION['userinfo']);
		unset($_SESSION['login_expire']);
		unset($_SESSION['login_method']);

		// delete the user's IP from the online users table
		$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_ip='".USER_IP."'");
	}

	// get the user record from the logged in user
	function get_userinfo() {

		return $this->userrecord;
	}

	// get the list of templates
	function get_templates($type = "side") {

		// do we have them cached?
		if (!isset($this->templates[$type]) || !is_array($this->templates[$type])) {
			// define the array to store the templates for this type
			$this->templates[$type] = array();

			// loop through the selected logon functions
			foreach($this->selected as $method) {
				// check if the class exists and is loaded
				if (isset($this->methods[$method]) && is_object($this->classes[$method])) {
					// call the get_template function of the class
					$template = $this->classes[$method]->get_template($type);
					// do we already have this template
					if (!isset($this->templates[$type][$template])) {
						if (isset($_SESSION['box_login_'.$method])) {
							$state = $_SESSION['box_login_'.$method] == 0 ? 1 : 0;
						} else {
							$state = 1;
						}
						$this->templates[$type][$template] = array('method' => $method, 'state' => $state);
					}
				}
			}
		}
		// return the templates
		return $this->templates[$type];
	}

	// post logon checks
	function _post_logon() {
		global $settings;

		// get the user record of the logged-in user
		$this->userrecord = $this->classes[$this->method_used]->userrecord;

		// was there a user record?
		if (!is_array($this->userrecord)) {
			// no logged-in user info present
			return false;
		}
		
		// if the account is suspended, check for an expiry date
		if ($this->userrecord['user_status'] == 1 && $this->userrecord['user_ban_expire'] > 0 && $this->userrecord['user_ban_expire'] < time() ) {
			// if this user's email address is marked as bad, reset the countdown counter
			$this->userrecord['user_bad_email'] = $this->userrecord['user_bad_email'] == 0 ? 0 : time();
			// reset the user status and the expiry date
			$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0', user_ban_expire='0', user_bad_email = '".$this->userrecord['user_bad_email']."' WHERE user_id='".$this->userrecord['user_id']."'");
			$this->userrecord['user_status'] = 0;
		}
		if ($this->userrecord['user_status'] == 0) {
			$this->status = 4;	// logon ok
		} elseif ($this->userrecord['user_status'] == 1) {
			$this->status = 1;	// account suspended
		} elseif ($this->userrecord['user_status'] == 2) {
			$this->status = 2;	// account not activated
		} else {
			$this->status = 0;	// unknown user status?
		}

		// update session info

		// set the 'remember me' status value 
		$_SESSION['remember_me'] = isset($_POST['remember_me']) ? "yes" : "no";
		$_SESSION['userinfo'] = $this->userrecord['user_id'].".".$this->userrecord['user_password'];
		// login expiry defined?
		if ($settings['login_expire']) {
			if (isset($_POST['remember_me']) && $_POST['remember_me'] == "yes") {
				$_SESSION['login_expire'] = time() + $settings['login_extended_expire'];
			} else {
				$_SESSION['login_expire'] = time() + $settings['login_expire'];
			}
		} else {
			$_SESSION['login_expire'] = mktime(0,0,0,1,1,2038);	// do not expire
		}
		// save the method used to logon
		$_SESSION['login_method'] =  $this->method_used;

		// post logon processing was a success
		return true;
	}

	/*-----------------------------------------------+
	| private class methods                          |
	+-----------------------------------------------*/

}
?>
