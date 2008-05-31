<?php
//----------------------------------------------------------
// ExiteCMS file : dbsetup_include.php
// Date generated  : `31/05/2008 12:03`
//----------------------------------------------------------

define('CMS_VERSION', '7.10');
define('CMS_REVISION', '1426');

if ($step == 1) {

$fail = "0";
$failed = array();

//
// Code to create table `GeoIP`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."GeoIP");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."GeoIP (
  `ip_start` varchar(15) NOT NULL default '',
  `ip_end` varchar(15) NOT NULL default '',
  `ip_start_num` int(10) unsigned NOT NULL default '0',
  `ip_end_num` int(10) unsigned NOT NULL default '0',
  `ip_code` char(2) NOT NULL default ''
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "GeoIP : ".mysql_error();
}

//
// Code to create table `GeoIP_backup`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."GeoIP_backup");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."GeoIP_backup (
  `ip_start` varchar(15) NOT NULL default '',
  `ip_end` varchar(15) NOT NULL default '',
  `ip_start_num` int(10) unsigned NOT NULL default '0',
  `ip_end_num` int(10) unsigned NOT NULL default '0',
  `ip_code` char(2) NOT NULL default '',
  `ip_name` varchar(50) NOT NULL default ''
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "GeoIP_backup : ".mysql_error();
}

//
// Code to create table `GeoIP_exceptions`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."GeoIP_exceptions");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."GeoIP_exceptions (
  `ip_number` varchar(15) NOT NULL default '',
  `ip_code` char(2) NOT NULL default '',
  `ip_name` varchar(50) NOT NULL default ''
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "GeoIP_exceptions : ".mysql_error();
}

//
// Code to create table `admin`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."admin");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."admin (
  `admin_id` smallint(5) unsigned NOT NULL auto_increment,
  `admin_rights` char(2) NOT NULL default '',
  `admin_image` varchar(50) NOT NULL default '',
  `admin_title` varchar(50) NOT NULL default '',
  `admin_link` varchar(100) NOT NULL default 'reserved',
  `admin_page` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`admin_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "admin : ".mysql_error();
}

//
// Code to create table `article_cats`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."article_cats");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."article_cats (
  `article_cat_id` smallint(5) unsigned NOT NULL auto_increment,
  `article_cat_name` varchar(100) NOT NULL default '',
  `article_cat_locale` varchar(8) NOT NULL default '',
  `article_cat_description` varchar(200) NOT NULL default '',
  `article_cat_image` varchar(100) NOT NULL default '',
  `article_cat_sorting` varchar(50) NOT NULL default 'article_subject ASC',
  `article_cat_access` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`article_cat_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "article_cats : ".mysql_error();
}

//
// Code to create table `articles`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."articles");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."articles (
  `article_id` smallint(5) unsigned NOT NULL auto_increment,
  `article_cat` smallint(5) unsigned NOT NULL default '0',
  `article_subject` varchar(200) NOT NULL default '',
  `article_snippet` text NOT NULL,
  `article_article` text NOT NULL,
  `article_breaks` char(1) NOT NULL default '',
  `article_name` mediumint(8) unsigned NOT NULL default '1',
  `article_locale` varchar(8) NOT NULL default '',
  `article_datestamp` int(10) unsigned NOT NULL default '0',
  `article_reads` mediumint(8) unsigned NOT NULL default '0',
  `article_allow_comments` tinyint(1) unsigned NOT NULL default '1',
  `article_allow_ratings` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`article_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "articles : ".mysql_error();
}

//
// Code to create table `bad_login`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."bad_login");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."bad_login (
  `login_id` smallint(5) unsigned NOT NULL auto_increment,
  `login_ip` varchar(200) NOT NULL default '',
  `login_time` varchar(200) NOT NULL default '',
  `login_user` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`login_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "bad_login : ".mysql_error();
}

//
// Code to create table `blacklist`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."blacklist");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."blacklist (
  `blacklist_id` smallint(5) unsigned NOT NULL auto_increment,
  `blacklist_ip` varchar(20) NOT NULL default '',
  `blacklist_email` varchar(100) NOT NULL default '',
  `blacklist_reason` text NOT NULL,
  PRIMARY KEY  (`blacklist_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "blacklist : ".mysql_error();
}

//
// Code to create table `blogs`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."blogs");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."blogs (
  `blog_id` mediumint(8) NOT NULL auto_increment,
  `blog_subject` varchar(50) NOT NULL default '',
  `blog_text` text NOT NULL,
  `blog_reads` mediumint(8) NOT NULL default '0',
  `blog_breaks` char(1) NOT NULL default '',
  `blog_comments` tinyint(1) NOT NULL default '0',
  `blog_ratings` tinyint(1) NOT NULL default '0',
  `blog_author` mediumint(8) NOT NULL default '0',
  `blog_datestamp` int(10) NOT NULL default '0',
  `blog_editor` mediumint(8) NOT NULL default '0',
  `blog_edittime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`blog_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "blogs : ".mysql_error();
}

//
// Code to create table `comments`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."comments");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."comments (
  `comment_id` smallint(5) unsigned NOT NULL auto_increment,
  `comment_item_id` smallint(5) unsigned NOT NULL default '0',
  `comment_type` char(2) NOT NULL default '',
  `comment_name` varchar(50) NOT NULL default '',
  `comment_message` text NOT NULL,
  `comment_smileys` tinyint(1) unsigned NOT NULL default '1',
  `comment_datestamp` int(10) unsigned NOT NULL default '0',
  `comment_ip` varchar(20) NOT NULL default '0.0.0.0',
  PRIMARY KEY  (`comment_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "comments : ".mysql_error();
}

//
// Code to create table `configuration`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."configuration");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."configuration (
  `cfg_id` smallint(5) unsigned NOT NULL auto_increment,
  `cfg_name` varchar(50) NOT NULL default '',
  `cfg_value` text NOT NULL,
  `cfg_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`cfg_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "configuration : ".mysql_error();
} else {
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('admin_activation', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('article_localisation', 'multiple')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('attachmax', '10485760')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('attachments', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('attachtypes', '.exe,.com,.bat,.js,.htm,.html,.shtml,.php,.php3,.esml,.psd,.mvi')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('bad_words', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('bad_words_enabled', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('bad_word_replace', '[censored]')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('blogs_indexage', '90')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('blogs_indexsize', '5')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('counter', '533')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('country', 'nl')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('debug_querylog', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('description', 'ExiteCMS, a light-weight CMS system, wriiten in PHP, and using MySQL and the Smarty template engine')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('display_validation', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('download_columns', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('download_localisation', 'multiple')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('email_verification', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('enable_registration', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('flood_interval', '15')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('folderhotlevel', '20')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('footer', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('forumdate', '%d/%m/%Y %H:%M')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('forum_edit_timeout', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('forum_flags', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('forum_max_h', '600')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('forum_max_w', '600')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('guestposts', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('hide_webmaster', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('keywords', 'ExiteCMS, CMS, Smarty, MySQL, PHP, Open-Source')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('locale', 'English')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('login_expire', '7200')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('login_extended_expire', '604800')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('longdate', '%m/%d/%Y %H:%M:%S')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('maintenance', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('maintenance_color', '#A19BCF')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('maintenance_message', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('max_users', '5')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('max_users_datestamp', '1193029685')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('navbar_range', '5')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('newsletter_email', 'noreply@exite.eu')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('news_columns', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('news_headline', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('news_items', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('news_latest', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('news_localisation', 'none')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('numofshouts', '5')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('numofthreads', '20')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('opening_page', 'news.php')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('panels_localisation', 'multiple')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('photo_h', '300')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('photo_max_b', '150000')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('photo_max_h', '1600')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('photo_max_w', '1800')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('photo_w', '400')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('pm_hide_rcpts', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('pm_inbox', '25')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('pm_inbox_group', '103')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('pm_savebox', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('pm_savebox_group', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('pm_send2group', '103')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('pm_sentbox', '25')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('pm_sentbox_group', '103')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('remote_stats', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('revision', '1426')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('session_gc_divisor', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('session_gc_maxlifetime', '604800')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('session_gc_probability', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('session_name', 'ExiteCMSid')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('shortdate', '%m/%d/%Y')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('siteemail', 'exitecms@exite.eu')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('siteintro', '<center>\r\n<b>This website is under construction at the moment!</b><br /><br />We are currently preparing the release of version 7.0. The release date has been set for October 27th, providing no last-minute showstoppers pop up.<br /><br />\r\nExiteCMS v7.0 �2007 Exite BV.</center>')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('sitelinks_localisation', 'multiple')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('sitename', 'ExiteCMS Support Site')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('siteurl', '/')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('siteusername', 'webmaster')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('smtp_host', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('smtp_password', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('smtp_username', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('subheaderdate', '%x %X')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('theme', 'ExiteCMS')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('thread_notify', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('thumbs_per_page', '12')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('thumbs_per_row', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('thumb_compression', 'gd2')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('thumb_h', '150')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('thumb_w', '150')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('timeoffset', '+0')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('tinymce_enabled', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('unread_threshold', '90')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('validation_method', 'image')");
	$result = dbquery("INSERT INTO ".$db_prefix."configuration (cfg_name, cfg_value) VALUES('version', '7.10')");
}

//
// Code to create table `custom_pages`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."custom_pages");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."custom_pages (
  `page_id` smallint(5) NOT NULL auto_increment,
  `page_title` varchar(200) NOT NULL default '',
  `page_access` tinyint(3) unsigned NOT NULL default '0',
  `page_content` text NOT NULL,
  `page_allow_comments` tinyint(1) unsigned NOT NULL default '0',
  `page_allow_ratings` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`page_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "custom_pages : ".mysql_error();
}

//
// Code to create table `download_cats`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."download_cats");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."download_cats (
  `download_cat_id` smallint(5) unsigned NOT NULL auto_increment,
  `download_cat_name` varchar(100) NOT NULL default '',
  `download_cat_locale` varchar(8) NOT NULL default '',
  `download_cat_description` text NOT NULL,
  `download_cat_sorting` varchar(50) NOT NULL default 'download_title ASC',
  `download_cat_cat_sorting` varchar(50) NOT NULL default 'download_cat_id DESC',
  `download_cat_access` tinyint(3) unsigned NOT NULL default '0',
  `download_cat_image` varchar(100) NOT NULL default '',
  `download_parent` tinyint(3) NOT NULL default '0',
  `download_datestamp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`download_cat_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "download_cats : ".mysql_error();
}

//
// Code to create table `downloads`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."downloads");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."downloads (
  `download_id` smallint(5) unsigned NOT NULL auto_increment,
  `download_title` varchar(100) NOT NULL default '',
  `download_description` text NOT NULL,
  `download_url` varchar(200) NOT NULL default '',
  `download_cat` smallint(5) unsigned NOT NULL default '0',
  `download_license` varchar(50) NOT NULL default '',
  `download_os` varchar(50) NOT NULL default '',
  `download_version` varchar(50) NOT NULL default '',
  `download_filesize` varchar(20) NOT NULL default '',
  `download_bar` tinyint(1) unsigned NOT NULL default '0',
  `download_datestamp` int(10) unsigned NOT NULL default '0',
  `download_count` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`download_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "downloads : ".mysql_error();
}

//
// Code to create table `faq_cats`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."faq_cats");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."faq_cats (
  `faq_cat_id` smallint(5) unsigned NOT NULL auto_increment,
  `faq_cat_name` varchar(200) NOT NULL default '',
  `faq_cat_description` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`faq_cat_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "faq_cats : ".mysql_error();
}

//
// Code to create table `faqs`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."faqs");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."faqs (
  `faq_id` smallint(5) unsigned NOT NULL auto_increment,
  `faq_cat_id` smallint(5) unsigned NOT NULL default '0',
  `faq_question` varchar(200) NOT NULL default '',
  `faq_answer` text NOT NULL,
  PRIMARY KEY  (`faq_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "faqs : ".mysql_error();
}

//
// Code to create table `flood_control`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."flood_control");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."flood_control (
  `flood_ip` varchar(20) NOT NULL default '0.0.0.0',
  `flood_timestamp` int(5) unsigned NOT NULL default '0'
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "flood_control : ".mysql_error();
}

//
// Code to create table `forum_attachments`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_attachments");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."forum_attachments (
  `attach_id` mediumint(8) unsigned NOT NULL auto_increment,
  `thread_id` mediumint(8) unsigned NOT NULL default '0',
  `post_id` mediumint(8) unsigned NOT NULL default '0',
  `attach_name` varchar(100) NOT NULL default '',
  `attach_realname` varchar(100) NOT NULL default '',
  `attach_comment` varchar(255) NOT NULL default '',
  `attach_ext` varchar(5) NOT NULL default '',
  `attach_size` int(20) unsigned NOT NULL default '0',
  `attach_count` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`attach_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "forum_attachments : ".mysql_error();
}

//
// Code to create table `forum_poll_options`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_options");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."forum_poll_options (
  `option_id` mediumint(8) unsigned NOT NULL auto_increment,
  `poll_id` mediumint(8) unsigned NOT NULL default '0',
  `option_order` tinyint(2) unsigned NOT NULL default '0',
  `option_text` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`option_id`),
  KEY `Polls` (`poll_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "forum_poll_options : ".mysql_error();
}

//
// Code to create table `forum_poll_settings`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_settings");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."forum_poll_settings (
  `forum_id` smallint(5) unsigned NOT NULL default '0',
  `enable_polls` tinyint(1) unsigned NOT NULL default '0',
  `create_permissions` text NOT NULL,
  `vote_permissions` text NOT NULL,
  `guest_permissions` tinyint(1) unsigned NOT NULL default '0',
  `require_approval` tinyint(1) unsigned NOT NULL default '0',
  `lock_threads` tinyint(1) unsigned NOT NULL default '0',
  `option_max` tinyint(2) unsigned NOT NULL default '0',
  `option_show` tinyint(2) unsigned NOT NULL default '0',
  `option_increment` tinyint(2) unsigned NOT NULL default '0',
  `duration_min` int(10) unsigned NOT NULL default '0',
  `duration_max` int(10) unsigned NOT NULL default '0',
  `hide_poll` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`forum_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "forum_poll_settings : ".mysql_error();
}

//
// Code to create table `forum_poll_votes`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_votes");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."forum_poll_votes (
  `poll_id` mediumint(8) unsigned NOT NULL default '0',
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  `vote_selection` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`poll_id`,`user_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "forum_poll_votes : ".mysql_error();
}

//
// Code to create table `forum_polls`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_polls");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."forum_polls (
  `poll_id` mediumint(8) unsigned NOT NULL auto_increment,
  `thread_id` mediumint(8) unsigned NOT NULL default '0',
  `post_id` mediumint(8) unsigned NOT NULL default '0',
  `poll_question` varchar(200) NOT NULL default '',
  `poll_start` int(10) unsigned NOT NULL default '0',
  `poll_end` int(10) unsigned NOT NULL default '0',
  `poll_type` tinyint(1) unsigned NOT NULL default '0',
  `poll_status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`poll_id`),
  UNIQUE KEY `Threads` (`thread_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "forum_polls : ".mysql_error();
}

//
// Code to create table `forum_ranking`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_ranking");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."forum_ranking (
  `rank_id` smallint(5) unsigned NOT NULL auto_increment,
  `rank_order` smallint(5) unsigned NOT NULL default '0',
  `rank_posts_from` mediumint(8) unsigned NOT NULL default '0',
  `rank_posts_to` mediumint(8) unsigned NOT NULL default '0',
  `rank_title` varchar(50) NOT NULL default '',
  `rank_color` varchar(15) NOT NULL default '',
  `rank_tooltip` tinyint(1) unsigned NOT NULL default '0',
  `rank_image` varchar(200) NOT NULL default '',
  `rank_image_repeat` tinyint(3) NOT NULL default '1',
  `rank_groups` varchar(200) NOT NULL default '',
  `rank_groups_and` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rank_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "forum_ranking : ".mysql_error();
}

//
// Code to create table `forums`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forums");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."forums (
  `forum_id` smallint(5) unsigned NOT NULL auto_increment,
  `forum_cat` smallint(5) unsigned NOT NULL default '0',
  `forum_name` varchar(100) NOT NULL default '',
  `forum_order` smallint(5) unsigned NOT NULL default '0',
  `forum_description` text NOT NULL,
  `forum_moderators` text NOT NULL,
  `forum_access` tinyint(3) unsigned NOT NULL default '0',
  `forum_posting` tinyint(3) unsigned NOT NULL default '0',
  `forum_modgroup` tinyint(3) unsigned NOT NULL default '0',
  `forum_attach` tinyint(1) unsigned NOT NULL default '0',
  `forum_attachtypes` varchar(150) NOT NULL default '',
  `forum_lastpost` int(10) unsigned NOT NULL default '0',
  `forum_lastuser` mediumint(8) unsigned NOT NULL default '0',
  `forum_rulespage` smallint(5) unsigned NOT NULL default '0',
  `forum_banners` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`forum_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "forums : ".mysql_error();
}

//
// Code to create table `locale`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."locale");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."locale (
  `locale_id` smallint(5) unsigned NOT NULL auto_increment,
  `locale_code` varchar(8) NOT NULL default '',
  `locale_name` varchar(50) NOT NULL default '',
  `locale_locale` varchar(25) NOT NULL default '',
  `locale_charset` varchar(25) NOT NULL default '',
  `locale_active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`locale_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "locale : ".mysql_error();
}

//
// Code to create table `locales`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."locales");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."locales (
  `locales_id` int(10) unsigned NOT NULL auto_increment,
  `locales_code` varchar(8) NOT NULL default '',
  `locales_name` varchar(50) NOT NULL default '',
  `locales_key` varchar(25) NOT NULL default '',
  `locales_value` text NOT NULL,
  `locales_translator` smallint(5) unsigned NOT NULL default '0',
  `locales_datestamp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`locales_id`),
  KEY `localenamekey` (`locales_code`,`locales_name`,`locales_key`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "locales : ".mysql_error();
}

//
// Code to create table `modules`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."modules");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."modules (
  `mod_id` smallint(5) unsigned NOT NULL auto_increment,
  `mod_title` varchar(100) NOT NULL default '',
  `mod_folder` varchar(100) NOT NULL default '',
  `mod_version` varchar(10) NOT NULL default '0',
  PRIMARY KEY  (`mod_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "modules : ".mysql_error();
}

//
// Code to create table `new_users`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."new_users");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."new_users (
  `user_code` varchar(32) NOT NULL default '',
  `user_email` varchar(100) NOT NULL default '',
  `user_datestamp` int(10) unsigned NOT NULL default '0',
  `user_info` text NOT NULL
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "new_users : ".mysql_error();
}

//
// Code to create table `news`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."news (
  `news_id` smallint(5) unsigned NOT NULL auto_increment,
  `news_subject` varchar(200) NOT NULL default '',
  `news_cat` smallint(5) unsigned NOT NULL default '0',
  `news_news` text NOT NULL,
  `news_extended` text NOT NULL,
  `news_breaks` char(1) NOT NULL default 'n',
  `news_name` mediumint(8) unsigned NOT NULL default '1',
  `news_datestamp` int(10) unsigned NOT NULL default '0',
  `news_start` int(10) unsigned NOT NULL default '0',
  `news_end` int(10) unsigned NOT NULL default '0',
  `news_visibility` tinyint(3) unsigned NOT NULL default '0',
  `news_reads` smallint(5) unsigned NOT NULL default '0',
  `news_headline` tinyint(1) unsigned NOT NULL default '0',
  `news_latest_news` tinyint(3) unsigned NOT NULL default '0',
  `news_allow_comments` tinyint(1) unsigned NOT NULL default '1',
  `news_allow_ratings` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`news_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "news : ".mysql_error();
}

//
// Code to create table `news_cats`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news_cats");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."news_cats (
  `news_cat_id` smallint(5) unsigned NOT NULL auto_increment,
  `news_cat_name` varchar(100) NOT NULL default '',
  `news_cat_image` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`news_cat_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "news_cats : ".mysql_error();
}

//
// Code to create table `online`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."online");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."online (
  `online_user` varchar(50) NOT NULL default '',
  `online_ip` varchar(20) NOT NULL default '',
  `online_lastactive` int(10) unsigned NOT NULL default '0'
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "online : ".mysql_error();
}

//
// Code to create table `panels`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."panels");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."panels (
  `panel_id` smallint(5) unsigned NOT NULL auto_increment,
  `panel_name` varchar(100) NOT NULL default '',
  `panel_locale` varchar(8) NOT NULL default '',
  `panel_filename` varchar(100) NOT NULL default '',
  `panel_code` text NOT NULL,
  `panel_template` text NOT NULL,
  `panel_side` tinyint(1) unsigned NOT NULL default '1',
  `panel_order` smallint(5) unsigned NOT NULL default '0',
  `panel_type` varchar(20) NOT NULL default '',
  `panel_access` tinyint(3) unsigned NOT NULL default '0',
  `panel_display` tinyint(1) unsigned NOT NULL default '0',
  `panel_status` tinyint(1) unsigned NOT NULL default '0',
  `panel_usermod` tinyint(1) unsigned NOT NULL default '0',
  `panel_state` tinyint(1) unsigned NOT NULL default '0',
  `panel_datestamp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`panel_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "panels : ".mysql_error();
}

//
// Code to create table `pm`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."pm");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."pm (
  `pm_id` mediumint(8) unsigned NOT NULL auto_increment,
  `pm_subject` varchar(100) NOT NULL default '',
  `pm_message` text NOT NULL,
  `pm_recipients` text NOT NULL,
  `pm_smileys` tinyint(1) unsigned NOT NULL default '1',
  `pm_size` mediumint(8) unsigned NOT NULL default '0',
  `pm_datestamp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pm_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "pm : ".mysql_error();
}

//
// Code to create table `pm_attachments`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."pm_attachments");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."pm_attachments (
  `pmattach_id` mediumint(8) unsigned NOT NULL auto_increment,
  `pm_id` mediumint(8) unsigned NOT NULL default '0',
  `pmattach_name` varchar(100) NOT NULL default '',
  `pmattach_realname` varchar(100) NOT NULL default '',
  `pmattach_comment` varchar(255) NOT NULL default '',
  `pmattach_ext` varchar(5) NOT NULL default '',
  `pmattach_size` int(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pmattach_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "pm_attachments : ".mysql_error();
}

//
// Code to create table `pm_config`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."pm_config");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."pm_config (
  `pmconfig_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  `pmconfig_save_sent` tinyint(1) unsigned NOT NULL default '1',
  `pmconfig_read_notify` tinyint(1) unsigned NOT NULL default '1',
  `pmconfig_email_notify` tinyint(1) unsigned NOT NULL default '0',
  `pmconfig_auto_archive` smallint(5) unsigned NOT NULL default '90',
  `pmconfig_view` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pmconfig_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "pm_config : ".mysql_error();
}

//
// Code to create table `pm_index`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."pm_index");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."pm_index (
  `pmindex_id` mediumint(8) unsigned NOT NULL auto_increment,
  `pm_id` mediumint(8) unsigned NOT NULL default '0',
  `pmindex_user_id` mediumint(8) unsigned NOT NULL default '0',
  `pmindex_reply_id` mediumint(8) unsigned NOT NULL default '0',
  `pmindex_from_id` mediumint(8) unsigned NOT NULL default '0',
  `pmindex_from_email` varchar(100) NOT NULL default '',
  `pmindex_to_id` mediumint(8) unsigned NOT NULL default '0',
  `pmindex_to_email` varchar(100) NOT NULL default '',
  `pmindex_to_group` tinyint(1) unsigned NOT NULL default '0',
  `pmindex_read_datestamp` int(10) unsigned NOT NULL default '0',
  `pmindex_read_requested` tinyint(1) unsigned NOT NULL default '0',
  `pmindex_folder` tinyint(1) unsigned NOT NULL default '0',
  `pmindex_locked` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pmindex_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "pm_index : ".mysql_error();
}

//
// Code to create table `posts`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."posts");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."posts (
  `forum_id` smallint(5) unsigned NOT NULL default '0',
  `thread_id` mediumint(8) unsigned NOT NULL default '0',
  `post_id` mediumint(8) unsigned NOT NULL auto_increment,
  `post_reply_id` mediumint(8) unsigned NOT NULL default '0',
  `post_subject` varchar(100) NOT NULL default '',
  `post_message` text NOT NULL,
  `post_showsig` tinyint(1) unsigned NOT NULL default '0',
  `post_smileys` tinyint(1) unsigned NOT NULL default '1',
  `post_sticky` tinyint(1) NOT NULL default '0',
  `post_author` mediumint(8) unsigned NOT NULL default '0',
  `post_datestamp` int(10) unsigned NOT NULL default '0',
  `post_ip` varchar(20) NOT NULL default '0.0.0.0',
  `post_edituser` mediumint(8) unsigned NOT NULL default '0',
  `post_edittime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `thread_id` (`thread_id`),
  KEY `post_datestamp` (`post_datestamp`),
  KEY `post_edittime` (`post_edittime`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "posts : ".mysql_error();
}

//
// Code to create table `ratings`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."ratings");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."ratings (
  `rating_id` smallint(5) unsigned NOT NULL auto_increment,
  `rating_item_id` smallint(5) unsigned NOT NULL default '0',
  `rating_type` char(1) NOT NULL default '?',
  `rating_user` mediumint(8) unsigned NOT NULL default '0',
  `rating_vote` tinyint(1) unsigned NOT NULL default '0',
  `rating_datestamp` int(10) unsigned NOT NULL default '0',
  `rating_ip` varchar(20) NOT NULL default '0.0.0.0',
  PRIMARY KEY  (`rating_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "ratings : ".mysql_error();
}

//
// Code to create table `redirects`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."redirects");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."redirects (
  `url_id` smallint(5) unsigned NOT NULL auto_increment,
  `url_from` varchar(100) NOT NULL default '',
  `url_to` varchar(100) NOT NULL default '',
  `url_redirect` tinyint(1) unsigned NOT NULL default '0',
  `url_parms` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`url_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "redirects : ".mysql_error();
}

//
// Code to create table `sessions`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."sessions");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."sessions (
  `session_id` varchar(50) NOT NULL default '',
  `session_ua` varchar(32) NOT NULL default '',
  `session_started` int(10) NOT NULL default '0',
  `session_expire` int(10) NOT NULL default '0',
  `session_user_id` mediumint(8) NOT NULL default '0',
  `session_ip` varchar(15) NOT NULL default '',
  `session_data` text NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "sessions : ".mysql_error();
}

//
// Code to create table `site_links`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."site_links");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."site_links (
  `link_id` smallint(5) unsigned NOT NULL auto_increment,
  `link_name` varchar(100) NOT NULL default '',
  `link_locale` varchar(8) NOT NULL default '',
  `link_url` varchar(200) NOT NULL default '',
  `panel_name` varchar(200) NOT NULL default '',
  `link_visibility` tinyint(3) unsigned NOT NULL default '0',
  `link_position` tinyint(1) unsigned NOT NULL default '1',
  `link_window` tinyint(1) unsigned NOT NULL default '0',
  `link_aid` tinyint(1) unsigned default '0',
  `link_parent` tinyint(3) unsigned default '0',
  `link_order` smallint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`link_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "site_links : ".mysql_error();
}

//
// Code to create table `thread_notify`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."thread_notify");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."thread_notify (
  `thread_id` mediumint(8) unsigned NOT NULL default '0',
  `notify_datestamp` int(10) unsigned NOT NULL default '0',
  `notify_user` mediumint(8) unsigned NOT NULL default '0',
  `notify_status` tinyint(1) unsigned NOT NULL default '1'
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "thread_notify : ".mysql_error();
}

//
// Code to create table `threads`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."threads");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."threads (
  `forum_id` smallint(5) unsigned NOT NULL default '0',
  `thread_id` mediumint(8) unsigned NOT NULL auto_increment,
  `thread_subject` varchar(100) NOT NULL default '',
  `thread_author` mediumint(8) unsigned NOT NULL default '0',
  `thread_views` mediumint(8) unsigned NOT NULL default '0',
  `thread_lastpost` int(10) unsigned NOT NULL default '0',
  `thread_lastuser` mediumint(8) unsigned NOT NULL default '0',
  `thread_sticky` tinyint(1) unsigned NOT NULL default '0',
  `thread_locked` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`thread_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "threads : ".mysql_error();
}

//
// Code to create table `threads_read`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."threads_read");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."threads_read (
  `user_id` mediumint(8) NOT NULL default '0',
  `forum_id` smallint(5) NOT NULL default '0',
  `thread_id` mediumint(8) NOT NULL default '0',
  `thread_first_read` int(10) unsigned NOT NULL default '4294967295',
  `thread_last_read` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`forum_id`,`thread_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "threads_read : ".mysql_error();
}

//
// Code to create table `user_groups`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_groups");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."user_groups (
  `group_id` tinyint(3) unsigned NOT NULL auto_increment,
  `group_ident` varchar(4) NOT NULL default '',
  `group_name` varchar(100) NOT NULL default '',
  `group_description` varchar(200) NOT NULL default '',
  `group_forumname` varchar(100) NOT NULL default '',
  `group_color` varchar(25) NOT NULL default '',
  `group_rights` text NOT NULL,
  `group_groups` text NOT NULL,
  `group_visible` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "user_groups : ".mysql_error();
}

//
// Code to create table `users`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."users");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."users (
  `user_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_md5id` varchar(32) NOT NULL default '',
  `user_name` varchar(30) NOT NULL default '',
  `user_fullname` varchar(50) NOT NULL default '',
  `user_password` varchar(32) NOT NULL default '',
  `user_webmaster` tinyint(1) unsigned NOT NULL default '0',
  `user_email` varchar(100) NOT NULL default '',
  `user_bad_email` int(10) unsigned NOT NULL default '0',
  `user_hide_email` tinyint(1) unsigned NOT NULL default '1',
  `user_location` varchar(50) NOT NULL default '',
  `user_birthdate` date NOT NULL default '0000-00-00',
  `user_aim` varchar(16) NOT NULL default '',
  `user_icq` varchar(15) NOT NULL default '',
  `user_msn` varchar(100) NOT NULL default '',
  `user_yahoo` varchar(100) NOT NULL default '',
  `user_web` varchar(200) NOT NULL default '',
  `user_forum_fullscreen` tinyint(1) unsigned NOT NULL default '0',
  `user_forum_datestamp` int(10) NOT NULL default '0',
  `user_theme` varchar(100) NOT NULL default 'Default',
  `user_locale` varchar(8) NOT NULL default 'en',
  `user_offset` varchar(6) NOT NULL default '0',
  `user_avatar` varchar(100) NOT NULL default '',
  `user_sig` text NOT NULL,
  `user_posts` mediumint(8) unsigned NOT NULL default '0',
  `user_joined` int(10) unsigned NOT NULL default '0',
  `user_lastvisit` int(10) unsigned NOT NULL default '0',
  `user_ip` varchar(20) NOT NULL default '0.0.0.0',
  `user_rights` text NOT NULL,
  `user_groups` text NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL default '101',
  `user_status` tinyint(1) unsigned NOT NULL default '0',
  `user_ban_reason` varchar(100) NOT NULL default '',
  `user_ban_expire` int(10) unsigned NOT NULL default '0',
  `user_newsletters` tinyint(1) unsigned NOT NULL default '1',
  `user_sponsor` tinyint(1) unsigned NOT NULL default '0',
  `user_posts_unread` tinyint(1) NOT NULL default '1',
  `user_cc_code` char(2) NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "users : ".mysql_error();
}

}
//----------------------------------------------------------
?>