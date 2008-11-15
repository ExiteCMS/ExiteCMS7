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
$_revision = '1738';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(15,00,0,9,9,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Added a search module for personal messages.");

// array to store the commands of this update
$commands = array();

// create fulltext index for the pm table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##pm ADD FULLTEXT (pm_subject)");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##pm ADD FULLTEXT (pm_message)");

// add the personal message searche to the search table
$commands[] = array('type' => 'function', 'value' => "rev1738_search_pm");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function rev1738_search_pm() {
	global $db_prefix;

	$order = dbfunction("MAX(search_order)", "search") + 1;
	$result = dbquery("INSERT INTO ".$db_prefix."search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility, search_order) VALUES(0, 1, 'pm', 'src518', 1, '1.0.0', 1, 0, ".$order.")");
}
?>
