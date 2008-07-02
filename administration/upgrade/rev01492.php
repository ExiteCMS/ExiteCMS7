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
$_revision = '1492';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(15,00,0,7,1,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Added Modular search core module.");

// array to store the commands of this update
$commands = array();

// add the new admin module "Searches"
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S', 'searches.gif', '260', 'searches.php', 3)");

// add the new "Searches" table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##searches (
  search_id smallint(5) unsigned NOT NULL auto_increment,
  search_mod_id smallint(5) unsigned NOT NULL default 0,
  search_name varchar(100) NOT NULL default '',
  search_title varchar(100) NOT NULL default '',
  search_version varchar(10) NOT NULL default 0,
  search_active tinyint(1) unsigned NOT NULL default 0,
  search_visibility tinyint(3) NOT NULL default 0,
  PRIMARY KEY  (search_id)
) ENGINE=MyISAM");


/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
?>
