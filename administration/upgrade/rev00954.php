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

// upgrade for revision
$_revision = '954';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(10,00,0,10,18,2007), 
					'title' => "Required updates for ExiteCMS v6.2 rev.".$_revision,
					'description' => "Added a new and more secure Captcha system.");

// array to store the commands of this update
$commands = array();

// database changes

// create new captcha table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##captcha (
  captcha_datestamp INT(10) UNSIGNED NOT NULL default 0,
  captcha_ip VARCHAR(20) NOT NULL,
  captcha_encode VARCHAR(32) NOT NULL default '',
  captcha_string VARCHAR(15) NOT NULL default ''
) ENGINE=MyISAM;");

// delete the old vcode table
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##vcode");

// update the users password with a double md5()
$commands[] = array('type' => 'function', 'value' => "update_password");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function update_password() {
	global $db_prefix;

	$result = dbquery("SELECT user_id, user_password FROM ".$db_prefix."users");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."users SET user_password='".md5($data['user_password'])."' WHERE user_id='".$data['user_id']."'");
	}
}
?>
