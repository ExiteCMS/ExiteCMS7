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
$_revision = '1426';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(12,00,0,5,31,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Database adjustments to make it compatible with MySQL v5.x STRICT MODE");

// array to store the commands of this update
$commands = array();

// database changes
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##admin CHANGE admin_rights admin_rights CHAR(2) NOT NULL DEFAULT '', 
	CHANGE admin_image admin_image VARCHAR(50) NOT NULL DEFAULT '', 
	CHANGE admin_title admin_title VARCHAR(50) NOT NULL DEFAULT ''");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##articles CHANGE article_subject article_subject VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE article_breaks article_breaks CHAR(1) NOT NULL DEFAULT '',
	CHANGE article_locale article_locale VARCHAR(8) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##article_cats CHANGE article_cat_name article_cat_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE article_cat_locale article_cat_locale VARCHAR(8) NOT NULL DEFAULT '',
	CHANGE article_cat_description article_cat_description VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE article_cat_image article_cat_image VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##bad_login CHANGE login_ip login_ip VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE login_time login_time VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE login_user login_user VARCHAR(200) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##blacklist CHANGE blacklist_ip blacklist_ip VARCHAR(20) NOT NULL DEFAULT '',
	CHANGE blacklist_email blacklist_email VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##blogs CHANGE blog_subject blog_subject VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE blog_breaks blog_breaks CHAR(1) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##comments CHANGE comment_type comment_type CHAR(2) NOT NULL DEFAULT '',
	CHANGE comment_name comment_name VARCHAR(50) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##configuration CHANGE cfg_name cfg_name VARCHAR(50) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##custom_pages CHANGE page_title page_title VARCHAR(200) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##downloads CHANGE download_title download_title VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE download_url download_url VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE download_license download_license VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE download_os download_os VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE download_version download_version VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE download_filesize download_filesize VARCHAR(20) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##download_cats CHANGE download_cat_name download_cat_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE download_cat_locale download_cat_locale VARCHAR(8) NOT NULL DEFAULT '',
	CHANGE download_cat_image download_cat_image VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##faqs CHANGE faq_question faq_question VARCHAR(200) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##faq_cats CHANGE faq_cat_name faq_cat_name VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE faq_cat_description faq_cat_description VARCHAR(250) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forums CHANGE forum_name forum_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE forum_attachtypes forum_attachtypes VARCHAR(150) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forum_attachments CHANGE attach_name attach_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE attach_realname attach_realname VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE attach_comment attach_comment VARCHAR(255) NOT NULL DEFAULT '',
	CHANGE attach_ext attach_ext VARCHAR(5) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forum_polls CHANGE poll_question poll_question VARCHAR(200) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forum_poll_options CHANGE option_text option_text VARCHAR(200) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forum_poll_votes CHANGE vote_selection vote_selection VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##forum_ranking CHANGE rank_title rank_title VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE rank_color rank_color VARCHAR(15) NOT NULL DEFAULT '',
	CHANGE rank_image rank_image VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE rank_groups rank_groups VARCHAR(200) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##GeoIP CHANGE ip_start ip_start VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	CHANGE ip_end ip_end VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	CHANGE ip_code ip_code CHAR(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##GeoIP_exceptions CHANGE ip_number ip_number VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	CHANGE ip_code ip_code CHAR(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	CHANGE ip_name ip_name VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##locale CHANGE locale_code locale_code VARCHAR(8) NOT NULL DEFAULT '',
	CHANGE locale_name locale_name VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE locale_locale locale_locale VARCHAR(25) NOT NULL DEFAULT '',
	CHANGE locale_charset locale_charset VARCHAR(25) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##locales CHANGE locales_code locales_code VARCHAR(8) NOT NULL DEFAULT '',
	CHANGE locales_name locales_name VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE locales_key locales_key VARCHAR(25) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##modules CHANGE mod_title mod_title VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE mod_folder mod_folder VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news CHANGE news_subject news_subject VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE news_breaks news_breaks CHAR(1) NOT NULL DEFAULT 'n';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news_cats CHANGE news_cat_name news_cat_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE news_cat_image news_cat_image VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##new_users CHANGE user_code user_code VARCHAR(32) NOT NULL DEFAULT '',
	CHANGE user_email user_email VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##online CHANGE online_user online_user VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE online_ip online_ip VARCHAR(20) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##panels CHANGE panel_name panel_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE panel_locale panel_locale VARCHAR(8) NOT NULL DEFAULT '',
	CHANGE panel_filename panel_filename VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE panel_type panel_type VARCHAR(20) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##pm CHANGE pm_subject pm_subject VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##pm_attachments CHANGE pmattach_name pmattach_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE pmattach_realname pmattach_realname VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE pmattach_comment pmattach_comment VARCHAR(255) NOT NULL DEFAULT '',
	CHANGE pmattach_ext pmattach_ext VARCHAR(5) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##pm_index CHANGE pmindex_from_email pmindex_from_email VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE pmindex_to_email pmindex_to_email VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##posts CHANGE post_subject post_subject VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##ratings CHANGE rating_type rating_type CHAR(1) NOT NULL DEFAULT '?';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##redirects CHANGE url_from url_from VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE url_to url_to VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##sessions CHANGE session_id session_id VARCHAR(50) NOT NULL DEFAULT '',
	CHANGE session_ua session_ua VARCHAR(32) NOT NULL DEFAULT '',
	CHANGE session_ip session_ip VARCHAR(15) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##site_links CHANGE link_name link_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE link_locale link_locale VARCHAR(8) NOT NULL DEFAULT '',
	CHANGE link_url link_url VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE panel_name panel_name VARCHAR(200) NOT NULL DEFAULT '';"); 
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##threads CHANGE thread_subject thread_subject VARCHAR(100) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##users CHANGE user_md5id user_md5id VARCHAR(32) NOT NULL DEFAULT '',
	CHANGE user_name user_name VARCHAR(30) NOT NULL DEFAULT '', 
	CHANGE user_fullname user_fullname VARCHAR(50) NOT NULL DEFAULT '', 
	CHANGE user_password user_password VARCHAR(32) NOT NULL DEFAULT '', 
	CHANGE user_email user_email VARCHAR(100) NOT NULL DEFAULT '', 
	CHANGE user_location user_location VARCHAR(50) NOT NULL DEFAULT '', 
	CHANGE user_aim user_aim VARCHAR(16) NOT NULL DEFAULT '', 
	CHANGE user_icq user_icq VARCHAR(15) NOT NULL DEFAULT '', 
	CHANGE user_msn user_msn VARCHAR(100) NOT NULL DEFAULT '', 
	CHANGE user_yahoo user_yahoo VARCHAR(100) NOT NULL DEFAULT '', 
	CHANGE user_web user_web VARCHAR(200) NOT NULL DEFAULT '', 
	CHANGE user_offset user_offset VARCHAR(6) NOT NULL DEFAULT '0', 
	CHANGE user_avatar user_avatar VARCHAR(100) NOT NULL DEFAULT '', 
	CHANGE user_ban_reason user_ban_reason VARCHAR(100) NOT NULL DEFAULT '', 
	CHANGE user_cc_code user_cc_code CHAR(2) NOT NULL DEFAULT '';");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##user_groups CHANGE group_ident group_ident VARCHAR(4) NOT NULL DEFAULT '',
	CHANGE group_name group_name VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE group_description group_description VARCHAR(200) NOT NULL DEFAULT '',
	CHANGE group_forumname group_forumname VARCHAR(100) NOT NULL DEFAULT '',
	CHANGE group_color group_color VARCHAR(25) NOT NULL DEFAULT '';");
?>
