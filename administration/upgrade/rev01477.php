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
$_revision = '1477';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(19,00,0,6,21,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Added the country code to post records to avoid unnecessary GeoIP lookups.");

// array to store the commands of this update
$commands = array();

// add an index on the locales table to speed up some queries
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##posts ADD post_cc CHAR(2) NOT NULL AFTER post_ip");

// and make sure the ranking image directory exists
$commands[] = array('type' => 'function', 'value' => "add_cc_to_posts");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function add_cc_to_posts() {
	global $db_prefix;

	require_once PATH_INCLUDES."geoip_include.php";

	// get all posts records
	$result = dbquery("SELECT post_id, post_ip FROM ".$db_prefix."posts");
	while ($data = dbarray($result)) {
		$cc = GeoIP_IP2Code($data['post_ip']);
		if ($cc) {
			$result2 = dbquery("UPDATE ".$db_prefix."posts SET post_cc = '".$cc."' WHERE post_id = '".$data['post_id']."'");
		}
	}
}

?>
