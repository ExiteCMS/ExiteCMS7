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

// upgrade for revision
$_revision = '909';

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(22,00,0,10,10,2007),
					'title' => "Required updates for ExiteCMS v6.2 rev.".$_revision,
					'description' => "New CMS settings structure, to create more flexibility for modules."
				);

// array to store the commands of this update
$commands = array();

// database changes

// create new CMSconfig table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##CMSconfig (
  cfg_id smallint(5) unsigned NOT NULL auto_increment,
  cfg_name varchar(25) NOT NULL default '',
  cfg_value TEXT NOT NULL,
  PRIMARY KEY  (cfg_id)
) ENGINE=MyISAM;");

// and copy the settings to the new table
$commands[] = array('type' => 'function', 'value' => "migrate_settings");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function migrate_settings() {
	global $db_prefix;

	$result = dbquery("SELECT * FROM ".$db_prefix."settings LIMIT 1");
	if ($data = dbarray($result)) {
		foreach($data as $name => $value) {
			$result = dbquery("INSERT INTO ".$db_prefix."CMSconfig (cfg_name, cfg_value) VALUES ('".$name."', '".$value."')");
		}
	}

}
?>
