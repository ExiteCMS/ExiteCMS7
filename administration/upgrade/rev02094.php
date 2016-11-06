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
$_revision = '2094';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(18,00,0,12,6,2008),
					'title' => "Required updates for ExiteCMS v7.2 rev.".$_revision,
					'description' => "Introduction of pluggable authentication methods.");

// array to store the commands of this update
$commands = array();

// database changes

// add the authentication methods field to the configuration
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('authentication_methods', '')");

// add the authentication settings field to the configuration
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('authentication_settings', '')");

// convert the pre-rev.2094 settings
$commands[] = array('type' => 'function', 'value' => "rev2094_auth_settings");

// delete the pre-rev.2094 settings
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name LIKE 'auth_ldap_%'");
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name LIKE 'auth_ad_%'");
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'auth_type'");

function rev2094_auth_settings() {
	global $db_prefix, $_db_link, $settings;

	// add the authentication selected field to the configuration
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES ('authentication_selected', '".$settings['auth_type']."')");

	// add the methods based on the old auth_type
	$types = explode(",", $settings['auth_type']);
	$methods = array();

	foreach($types as $type) {

		// supported logins before rev.2094
		switch ($type) {
			case "local":
				$methods[$type] = array('class' => "auth_local");
				break;
			case "openid":
				$methods[$type] = array('class' => "auth_openid");
				break;
			default:
				break;
		}
	}
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".mysqli_real_escape_string($_db_link, serialize($methods))."' WHERE cfg_name = 'authentication_methods'");
}
?>
