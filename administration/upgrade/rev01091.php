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
$_revision = '1091';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(01,00,0,11,08,2007), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Added characterset, and date format information to the locales table."
				);

// array to store the commands of this update
$commands = array();

// add the translator user_id to the locales table to track who changed what (we have the datestamp for the when)
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##locales ADD locales_translator SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' AFTER locales_value");

// add a locales key index to the locales table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##locales ADD INDEX localenamekey (locales_locale, locales_name, locales_key)");

// update the locale in the CMSconfig table from name to code
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##CMSconfig SET cfg_value = 'en' WHERE cfg_name = 'locale' and cfg_value = 'English'");

// change the locale name field to code in the locales table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##locales CHANGE locales_locale locales_code VARCHAR(8) NOT NULL DEFAULT ''");

// add a locale field to the users table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##users ADD user_locale VARCHAR(8) NOT NULL DEFAULT 'en' AFTER user_theme");

?>