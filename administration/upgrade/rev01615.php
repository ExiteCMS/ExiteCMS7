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
$_revision = '1615';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(15,00,0,8,10,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Updated the modular search core module.");

// array to store the commands of this update
$commands = array();

// added an internal report indicator to the reports table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##reports ADD report_mod_core tinyint(1) UNSIGNED NOT NULL default '0' AFTER report_mod_id");

// delete the old "Searches" table
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##searches");

// add the new "Search" table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##search (
  search_id smallint(5) unsigned NOT NULL auto_increment,
  search_mod_id smallint(5) unsigned NOT NULL default 0,
  search_mod_core tinyint(1) unsigned NOT NULL default 0,
  search_name varchar(100) NOT NULL default '',
  search_title varchar(100) NOT NULL default '',
  search_version varchar(10) NOT NULL default 0,
  search_active tinyint(1) unsigned NOT NULL default 0,
  search_visibility tinyint(3) NOT NULL default 0,
  search_fulltext tinyint(1) unsigned NOT NULL default 0,
  PRIMARY KEY  (search_id)
) ENGINE=MyISAM");

// add the ExiteCMS core searches to the search table
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'articles', 'src410', 1, '1.0.0', 1, 0)");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'news', 'src411', 1, '1.0.0', 1, 0)");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'forumposts', 'src412', 1, '1.0.0', 1, 0)");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'forumattachments', 'src413', 1, '1.0.0', 1, 0)");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'downloads', 'src414', 1, '1.0.0', 1, 0)");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##search (search_mod_id, search_mod_core, search_name, search_title, search_fulltext, search_version, search_active, search_visibility) VALUES(0, 1, 'members', 'src415', 0, '1.0.0', 1, 0)");

// update the admin link to search
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_link = 'search.php' WHERE admin_link = 'searches.php'");

// create fulltext index for the news table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news ADD FULLTEXT (news_subject)");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news ADD FULLTEXT (news_extended)");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news ADD FULLTEXT (news_news)");

// create fulltext index for the articles table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##articles ADD FULLTEXT (article_subject)");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##articles ADD FULLTEXT (article_snippet)");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##articles ADD FULLTEXT (article_article)");

// create fulltext index for the downloads table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##downloads ADD FULLTEXT (download_title)");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##downloads ADD FULLTEXT (download_description)");

// create fulltext index for the forum_attachments table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forum_attachments ADD FULLTEXT (attach_realname)");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forum_attachments ADD FULLTEXT (attach_comment)");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
?>
