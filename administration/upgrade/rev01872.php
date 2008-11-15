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
$_revision = '1872';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(15,00,0,10,19,2008), 
					'title' => "Required updates for ExiteCMS v7.2 rev.".$_revision,
					'description' => "Store the setup default language in the configuration.");

// array to store the commands of this update
$commands = array();

// database changes

// add the language used in the setup to the configuration
$commands[] = array('type' => 'function', 'value' => "rev1972_setup_locale");

function rev1972_setup_locale() {
	global $db_prefix;
	
	// assume the first locale record is the one the site is installed with
	$result = dbquery("SELECT * FROM ".$db_prefix."locale ORDER BY locale_id ASC LIMIT 1");
	if (dbrows($result)) {
		$locale = dbarray($result);
	} else {
		// use English as the default
		$locale = array("locale_code" => "en");
	}
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES ('default_locale', '".$locale['locale_code']."')");
}
?>
