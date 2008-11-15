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
$_revision = '1190';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(18,00,0,1,7,2008), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Convert several table ID fields from 16bit to 32bit, to make the CMS more scalable\nNote: This will increase the size of the database!");

// array to store the commands of this update
$commands = array();

// update the tables
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."admin CHANGE admin_id admin_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."articles CHANGE article_name article_name MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 1");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."articles CHANGE article_reads article_reads MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forums CHANGE forum_lastuser forum_lastuser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_attachments CHANGE attach_id attach_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_attachments CHANGE thread_id thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_attachments CHANGE post_id post_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_polls CHANGE poll_id poll_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_polls CHANGE thread_id thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_polls CHANGE post_id post_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_poll_options CHANGE option_id option_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_poll_options CHANGE poll_id poll_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_poll_votes CHANGE poll_id poll_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."forum_poll_votes CHANGE user_id user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."news CHANGE news_name news_name MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 1");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm CHANGE pm_id pm_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm CHANGE pm_size pm_size MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_attachments CHANGE pmattach_id pmattach_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_attachments CHANGE pm_id pm_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_config CHANGE pmconfig_id pmconfig_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_config CHANGE user_id user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_index CHANGE pmindex_id pmindex_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_index CHANGE pm_id pm_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_index CHANGE pmindex_user_id pmindex_user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_index CHANGE pmindex_reply_id pmindex_reply_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_index CHANGE pmindex_from_id pmindex_from_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."pm_index CHANGE pmindex_to_id pmindex_to_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."posts CHANGE thread_id thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."posts CHANGE post_id post_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."posts CHANGE post_reply_id post_reply_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."posts CHANGE post_author post_author MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."posts CHANGE post_edituser post_edituser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."ratings CHANGE rating_user rating_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."threads CHANGE thread_id thread_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."threads CHANGE thread_author thread_author MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."threads CHANGE thread_views thread_views MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."threads CHANGE thread_lastuser thread_lastuser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."thread_notify CHANGE thread_id thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."thread_notify CHANGE notify_user notify_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."users CHANGE user_id user_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."users CHANGE user_posts user_posts MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ".$db_prefix."users CHANGE user_newsletters user_newsletters TINYINT(1) UNSIGNED NOT NULL DEFAULT 1");
?>
