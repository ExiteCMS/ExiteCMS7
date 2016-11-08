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
$_revision = '1711';

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(17,00,0,8,28,2008),
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Added an order to seaches, to determine the default search location");

// array to store the commands of this update
$commands = array();

// Add the order field to the search table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##search ADD search_order TINYINT(3) UNSIGNED NOT NULL DEFAULT 0;");
$commands[] = array('type' => 'function', 'value' => "add_search_order");

function add_search_order() {
	global $db_prefix;

	$result = dbquery("SELECT * FROM ".$db_prefix."search ORDER BY search_id");
	$i = 1;
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."search SET search_order = ".$i++." WHERE search_id = ".$data['search_id']);
	}
}
?>
