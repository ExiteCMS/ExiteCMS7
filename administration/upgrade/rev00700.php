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
$_revision = 700;

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(18,00,0,6,14,2007),
					'title' => "Migration from PLi-Fusion v6.1.10 to ExiteCMS v6.2",
					'class' => 'rev_major',
					'description' => "<u>This is a major operation!</u><br />It replaces the old PHP-Fusion code with the new ExiteCMS templated CMS engine, and converts the PHP-Fusion database to the ExiteCMS format.",
					'footer' => "Make sure you have a backup, there is no way back from this upgrade!!!"
				);

// array to store the commands of this update
$commands[$_revision] = array();

// database changes
// * NO DATABASE CHANGES FOR THIS REVISION

// upgrade functions
$commands[] = array('type' => 'function', 'value' => "migration");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function migration() {
	global $db_prefix;

	// disable php code panels:
  	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_status = '0' WHERE panel_type = 'php'");

	// Reset module information
	$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_url LIKE 'infusions/%'");
	while ($data = dbarray($result)) {
	  	$result2 = dbquery("UPDATE ".$db_prefix."site_links SET link_url = '".str_replace('infusions/', 'modules/', $data['link_url'])."' WHERE link_id = '".$data['link_id']."'");
	}
	$result = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_link LIKE '%/infusions/%'");
	while ($data = dbarray($result)) {
	  	$result2 = dbquery("UPDATE ".$db_prefix."admin SET admin_link = '".str_replace('/infusions/', '/modules/', str_replace('..', '', $data['admin_link']))."' WHERE admin_id = '".$data['admin_id']."'");
	}
}
?>
