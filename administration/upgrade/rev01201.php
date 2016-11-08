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
$_revision = '1201';

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(13,00,0,1,18,2008),
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Added the new Blogs CMS core module. Made hiding the webmaster(s) login configurable");

// array to store the commands of this update
$commands = array();

// add the flag for hiding the webmaster
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('hide_webmaster', '0')");

// add the new admin module "blogs"
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('BG', 'blogs.gif', '226', 'blogs.php', '1')");

// add the new user groups for the module "blogs"
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##user_groups (group_ident, group_name, group_groups, group_rights, group_description, group_forumname, group_visible) VALUES ('BG01', 'Blog Editors', '', '', 'Blog Editors', 'Blog Editor', '1')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##user_groups (group_ident, group_name, group_groups, group_rights, group_description, group_forumname, group_visible) VALUES ('BG02', 'Blog Moderators', '', '', 'Blog Moderators', 'Blog Moderator', '1')");

// add the tables for the new module "blogs"
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##blogs (
  blog_id mediumint(8) NOT NULL AUTO_INCREMENT,
  blog_subject varchar(50) NOT NULL default '',
  blog_text text NOT NULL,
  blog_reads mediumint(8) NOT NULL default 0,
  blog_breaks char(1) NOT NULL default 'n',
  blog_comments tinyint(1) NOT NULL default 0,
  blog_ratings tinyint(1) NOT NULL default 0,
  blog_author mediumint(8) NOT NULL default 0,
  blog_datestamp int(10) NOT NULL default 0,
  blog_editor mediumint(8) NOT NULL default 0,
  blog_edittime int(10) NOT NULL default 0,
  PRIMARY KEY  (blog_id)
) ENGINE=MyISAM;");

?>
