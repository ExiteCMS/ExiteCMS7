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
$_revision = '1176';

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(14,00,0,12,18,2007),
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Modified the panel code to allow multiple panels per module.");

// array to store the commands of this update
$commands = array();

// database changes

// update the contents of the panels table
$commands[] = array('type' => 'function', 'value' => "update_panels");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function update_panels() {

	global $db_prefix;

	$result = dbquery("SELECT * FROM ".$db_prefix."panels");
	while ($data = dbarray($result)) {
		if (substr($data['panel_filename'],-4) != ".php") {
			$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_filename = '".$data['panel_filename']."/".$data['panel_filename'].".php' WHERE panel_id = '".$data['panel_id']."'");
		}
	}
}
?>
