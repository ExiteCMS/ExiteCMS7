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
$_revision = '1419';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(21,00,0,5,27,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Added Forum Ranking admin module.");

// array to store the commands of this update
$commands = array();

// database changes - add the forum ranking table

$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##forum_ranking (
  rank_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  rank_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
  rank_posts_from MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
  rank_posts_to MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
  rank_title VARCHAR(50) NOT NULL ,
  rank_color VARCHAR(15) NOT NULL ,
  rank_tooltip TINYINT(1) NOT NULL DEFAULT 0,
  rank_image VARCHAR(200) NOT NULL ,
  rank_image_repeat TINYINT(3) NOT NULL DEFAULT 1,
  rank_groups VARCHAR(200) NOT NULL,
  rank_groups_and TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (rank_id)
) ENGINE = MYISAM;");

// add the new admin module "Forum Ranking"
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FR', 'ranking.gif', '238', 'ranking.php', '1')");

// and make sure the ranking image directory exists
$commands[] = array('type' => 'function', 'value' => "make_ranking_image_dir");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function make_ranking_image_dir() {

	if (!is_dir(PATH_IMAGES."ranking")) {
		mkdir(PATH_IMAGES."ranking");
	}
}

?>
