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
$_revision = '1082';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(18,00,0,11,07,2007), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Added characterset, and date format information to the locales table."
				);

// array to store the commands of this update
$commands = array();

// add the new fields to the locales table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##locales ADD locale_locale VARCHAR(25) NOT NULL DEFAULT '' AFTER locale_name");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##locales ADD locale_charset VARCHAR(25) NOT NULL DEFAULT '' AFTER locale_locale");

// and pupulate them with default values
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##locales SET locale_locale = 'en_US|en_GB|english|eng', locale_charset = 'iso-8859-1'");

?>