<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/

// upgrade for revision
$_revision = 700;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 'date' => mktime(18,10,0,6,14,2007), 'description' => "Upgrade from PLi-Fusion v6.1.10 to ExiteCMS v7.00<br /><font color='red'><b>This is a major upgrade!</b><br />It replaces the old PHP-Fusion code with the new ExiteCMS templated CMS engine, and converts the PHP-Fusion database to the ExiteCMS format.<br /><br /><b>Make sure you have a backup, there is no way back from this upgrade!!!</b></font>");

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