<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/

// upgrade for revision
$_revision = '1070';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(14,00,0,11,06,2007), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "New CMS locales table, with support for database driven locales with pre compilation to minimize the speed impact."
				);

// array to store the commands of this update
$commands = array();

// database changes

// create new locales table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##locales (
  locales_id int(10) UNSIGNED NOT NULL auto_increment,
  locales_locale varchar(50) NOT NULL default '',
  locales_name varchar(50) NOT NULL default '',
  locales_key varchar(25) NOT NULL default '',
  locales_value TEXT NOT NULL default '',
  locales_datestamp INT(10) UNSIGNED NOT NULL default '',
  PRIMARY KEY  (locales_id)
) ENGINE=MyISAM;");

// and make sure the locales cache directory exists
$commands[] = array('type' => 'function', 'value' => "make_locales_cache_dir");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function make_locales_cache_dir() {

	if (!is_dir(PATH_ROOT."files/locales")) {
		mkdir(PATH_ROOT."files/locales");
	}
}
?>