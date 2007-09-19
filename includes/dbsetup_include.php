<?php
//----------------------------------------------------------
// PLi-Fusion file : dbsetup_include.php
// Date generated  : `17/09/2007 12:05`
//----------------------------------------------------------

define('PLI_VERSION', '7.00');
define('PLI_REVISION', '800');

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
  `ip_code` char(2) NOT NULL default '',
  `ip_name` varchar(50) NOT NULL default ''
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
  `admin_id` tinyint(2) unsigned NOT NULL auto_increment,
  `admin_rights` varchar(2) NOT NULL default '',
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
// Code to create table `adverts`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."adverts");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."adverts (
  `adverts_id` smallint(5) NOT NULL auto_increment,
  `adverts_userid` smallint(5) NOT NULL default '0',
  `adverts_contract` tinyint(1) NOT NULL default '0',
  `adverts_contract_start` int(10) unsigned NOT NULL default '0',
  `adverts_contract_end` int(10) unsigned NOT NULL default '0',
  `adverts_priority` tinyint(1) unsigned NOT NULL default '1',
  `adverts_location` tinyint(2) unsigned NOT NULL default '0',
  `adverts_url` varchar(200) NOT NULL default '',
  `adverts_shown` int(11) NOT NULL default '0',
  `adverts_clicks` int(11) NOT NULL default '0',
  `adverts_sold` int(11) NOT NULL default '0',
  `adverts_image` varchar(50) NOT NULL default '',
  `adverts_html` text NOT NULL,
  `adverts_status` enum('0','1') NOT NULL default '0',
  `adverts_expired` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`adverts_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "adverts : ".mysql_error();
}

//
// Code to create table `article_cats`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."article_cats");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."article_cats (
  `article_cat_id` smallint(5) unsigned NOT NULL auto_increment,
  `article_cat_name` varchar(100) NOT NULL default '',
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
  `article_name` smallint(5) unsigned NOT NULL default '1',
  `article_datestamp` int(10) unsigned NOT NULL default '0',
  `article_reads` smallint(5) unsigned NOT NULL default '0',
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
// Code to create table `custom_pages`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."custom_pages");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."custom_pages (
  `page_id` smallint(5) NOT NULL auto_increment,
  `page_title` varchar(200) NOT NULL default '',
  `page_seo_url` varchar(255) NOT NULL default '',
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
// Code to create table `dls_mapping`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."dls_mapping");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."dls_mapping (
  `mapping_id` smallint(5) unsigned NOT NULL auto_increment,
  `mapping_url` varchar(255) NOT NULL default '',
  `download_id` smallint(5) NOT NULL default '0',
  `ds_file_from` varchar(255) NOT NULL default '',
  `ds_file_to` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`mapping_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "dls_mapping : ".mysql_error();
}

//
// Code to create table `dls_statistics`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."dls_statistics");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."dls_statistics (
  `ds_id` mediumint(8) unsigned NOT NULL auto_increment,
  `ds_success` tinyint(1) unsigned NOT NULL default '0',
  `ds_ip` varchar(15) NOT NULL default '0.0.0.0',
  `ds_cc` char(2) NOT NULL default '',
  `ds_timestamp` int(10) unsigned NOT NULL default '0',
  `ds_url` varchar(255) NOT NULL default '',
  `ds_file` varchar(255) NOT NULL default '',
  `ds_mirror` tinyint(1) unsigned NOT NULL default '0',
  `ds_processed` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ds_id`),
  KEY `countrycode` (`ds_cc`),
  KEY `mirror` (`ds_mirror`),
  KEY `filename` (`ds_file`),
  KEY `timestamp` (`ds_timestamp`),
  KEY `processed` (`ds_processed`),
  KEY `ds_url` (`ds_url`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "dls_statistics : ".mysql_error();
}

//
// Code to create table `donations`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."donations");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."donations (
  `donate_id` smallint(5) NOT NULL auto_increment,
  `donate_name` varchar(50) NOT NULL default '',
  `donate_amount` decimal(10,2) NOT NULL default '0.00',
  `donate_currency` varchar(5) NOT NULL default '',
  `donate_country` char(2) NOT NULL default '',
  `donate_comment` varchar(200) NOT NULL default '',
  `donate_timestamp` int(10) NOT NULL default '0',
  `donate_type` tinyint(1) NOT NULL default '0',
  `donate_state` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`donate_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "donations : ".mysql_error();
}

//
// Code to create table `download_cats`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."download_cats");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."download_cats (
  `download_cat_id` smallint(5) unsigned NOT NULL auto_increment,
  `download_cat_name` varchar(100) NOT NULL default '',
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
  `download_version` varchar(20) NOT NULL default '',
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
  `attach_id` smallint(5) unsigned NOT NULL auto_increment,
  `thread_id` smallint(5) unsigned NOT NULL default '0',
  `post_id` smallint(5) unsigned NOT NULL default '0',
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
  `option_id` int(8) unsigned NOT NULL auto_increment,
  `poll_id` int(8) unsigned NOT NULL default '0',
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
  `poll_id` int(8) unsigned NOT NULL default '0',
  `user_id` int(8) unsigned NOT NULL default '0',
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
  `poll_id` int(8) unsigned NOT NULL auto_increment,
  `thread_id` int(8) unsigned NOT NULL default '0',
  `post_id` int(8) unsigned NOT NULL default '0',
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
  `forum_lastuser` smallint(5) unsigned NOT NULL default '0',
  `forum_rulespage` smallint(5) unsigned NOT NULL default '0',
  `forum_banners` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`forum_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "forums : ".mysql_error();
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
  `news_breaks` char(1) NOT NULL default '',
  `news_name` smallint(5) unsigned NOT NULL default '1',
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
  `panel_filename` varchar(100) NOT NULL default '',
  `panel_content` text NOT NULL,
  `panel_side` tinyint(1) unsigned NOT NULL default '1',
  `panel_order` smallint(5) unsigned NOT NULL default '0',
  `panel_type` varchar(20) NOT NULL default '',
  `panel_access` tinyint(3) unsigned NOT NULL default '0',
  `panel_display` tinyint(1) unsigned NOT NULL default '0',
  `panel_status` tinyint(1) unsigned NOT NULL default '0',
  `panel_usermod` tinyint(1) unsigned NOT NULL default '0',
  `panel_state` tinyint(1) unsigned NOT NULL default '0',
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
  `pm_id` smallint(5) unsigned NOT NULL auto_increment,
  `pm_subject` varchar(100) NOT NULL default '',
  `pm_message` text NOT NULL,
  `pm_recipients` text NOT NULL,
  `pm_smileys` tinyint(1) unsigned NOT NULL default '1',
  `pm_size` smallint(5) unsigned NOT NULL default '0',
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
  `pmattach_id` smallint(5) unsigned NOT NULL auto_increment,
  `pm_id` smallint(5) unsigned NOT NULL default '0',
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
  `pmconfig_id` smallint(5) unsigned NOT NULL auto_increment,
  `user_id` smallint(5) unsigned NOT NULL default '0',
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
  `pmindex_id` smallint(5) unsigned NOT NULL auto_increment,
  `pm_id` smallint(5) unsigned NOT NULL default '0',
  `pmindex_user_id` smallint(5) unsigned NOT NULL default '0',
  `pmindex_reply_id` smallint(5) unsigned NOT NULL default '0',
  `pmindex_from_id` smallint(5) unsigned NOT NULL default '0',
  `pmindex_from_email` varchar(100) NOT NULL default '',
  `pmindex_to_id` smallint(5) unsigned NOT NULL default '0',
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
  `thread_id` smallint(5) unsigned NOT NULL default '0',
  `post_id` smallint(5) unsigned NOT NULL auto_increment,
  `post_reply_id` smallint(5) unsigned NOT NULL default '0',
  `post_subject` varchar(100) NOT NULL default '',
  `post_message` text NOT NULL,
  `post_showsig` tinyint(1) unsigned NOT NULL default '0',
  `post_smileys` tinyint(1) unsigned NOT NULL default '1',
  `post_sticky` tinyint(1) NOT NULL default '0',
  `post_author` smallint(5) unsigned NOT NULL default '0',
  `post_datestamp` int(10) unsigned NOT NULL default '0',
  `post_ip` varchar(20) NOT NULL default '0.0.0.0',
  `post_edituser` smallint(5) unsigned NOT NULL default '0',
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
// Code to create table `posts_unread`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."posts_unread");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."posts_unread (
  `user_id` smallint(5) unsigned NOT NULL default '0',
  `forum_id` smallint(5) unsigned NOT NULL default '0',
  `thread_id` smallint(5) unsigned NOT NULL default '0',
  `post_id` smallint(5) unsigned NOT NULL default '0',
  `post_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`forum_id`,`thread_id`,`post_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "posts_unread : ".mysql_error();
}

//
// Code to create table `ratings`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."ratings");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."ratings (
  `rating_id` smallint(5) unsigned NOT NULL auto_increment,
  `rating_item_id` smallint(5) unsigned NOT NULL default '0',
  `rating_type` char(1) NOT NULL default '',
  `rating_user` smallint(5) unsigned NOT NULL default '0',
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
// Code to create table `settings`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."settings (
  `sitename` varchar(200) NOT NULL default '',
  `siteurl` varchar(200) NOT NULL default '',
  `sitebanner` varchar(200) NOT NULL default '',
  `siteemail` varchar(100) NOT NULL default '',
  `siteusername` varchar(30) NOT NULL default '',
  `siteintro` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `footer` text NOT NULL,
  `opening_page` varchar(100) NOT NULL default '',
  `news_headline` tinyint(3) unsigned NOT NULL default '0',
  `news_columns` tinyint(3) unsigned NOT NULL default '1',
  `news_items` tinyint(3) unsigned NOT NULL default '4',
  `news_latest` tinyint(1) unsigned NOT NULL default '0',
  `locale` varchar(20) NOT NULL default 'English',
  `theme` varchar(100) NOT NULL default '',
  `shortdate` varchar(50) NOT NULL default '',
  `longdate` varchar(50) NOT NULL default '',
  `forumdate` varchar(50) NOT NULL default '',
  `subheaderdate` varchar(50) NOT NULL default '',
  `timeoffset` varchar(6) NOT NULL default '0',
  `numofthreads` smallint(2) unsigned NOT NULL default '5',
  `attachments` tinyint(1) unsigned NOT NULL default '0',
  `attachmax` int(12) unsigned NOT NULL default '150000',
  `attachtypes` varchar(150) NOT NULL default '.gif,.jpg,.png,.zip,.rar,.tar',
  `thread_notify` tinyint(1) unsigned NOT NULL default '0',
  `enable_registration` tinyint(1) unsigned NOT NULL default '1',
  `email_verification` tinyint(1) unsigned NOT NULL default '1',
  `admin_activation` tinyint(1) unsigned NOT NULL default '0',
  `display_validation` tinyint(1) unsigned NOT NULL default '1',
  `validation_method` varchar(5) NOT NULL default 'image',
  `thumb_w` smallint(3) unsigned NOT NULL default '100',
  `thumb_h` smallint(3) unsigned NOT NULL default '100',
  `photo_w` smallint(4) unsigned NOT NULL default '400',
  `photo_h` smallint(4) unsigned NOT NULL default '300',
  `photo_max_w` smallint(4) unsigned NOT NULL default '1800',
  `photo_max_h` smallint(4) unsigned NOT NULL default '1600',
  `photo_max_b` int(10) unsigned NOT NULL default '150000',
  `thumb_compression` varchar(3) NOT NULL default 'gd2',
  `thumbs_per_row` smallint(2) unsigned NOT NULL default '4',
  `thumbs_per_page` smallint(2) unsigned NOT NULL default '12',
  `tinymce_enabled` tinyint(1) unsigned NOT NULL default '0',
  `smtp_host` varchar(200) NOT NULL default '',
  `smtp_username` varchar(100) NOT NULL default '',
  `smtp_password` varchar(100) NOT NULL default '',
  `bad_words_enabled` tinyint(1) unsigned NOT NULL default '0',
  `bad_words` text NOT NULL,
  `bad_word_replace` varchar(20) NOT NULL default '[censored]',
  `guestposts` tinyint(1) unsigned NOT NULL default '0',
  `numofshouts` tinyint(2) unsigned NOT NULL default '10',
  `flood_interval` tinyint(2) unsigned NOT NULL default '15',
  `counter` bigint(20) unsigned NOT NULL default '0',
  `max_users` smallint(5) unsigned NOT NULL default '0',
  `max_users_datestamp` int(10) unsigned NOT NULL default '0',
  `version` decimal(5,2) NOT NULL default '7.00',
  `revision` smallint(5) unsigned NOT NULL default '0',
  `remote_stats` tinyint(1) unsigned NOT NULL default '0',
  `maintenance` tinyint(1) unsigned NOT NULL default '0',
  `maintenance_message` text NOT NULL,
  `maintenance_color` varchar(10) NOT NULL default '#FFB900',
  `donate_forum_id` smallint(5) NOT NULL default '0',
  `forum_flags` smallint(1) NOT NULL default '0',
  `forum_max_w` smallint(4) NOT NULL default '600',
  `forum_max_h` smallint(4) NOT NULL default '600',
  `newsletter_email` varchar(100) NOT NULL default '',
  `pm_inbox` smallint(5) unsigned NOT NULL default '25',
  `pm_sentbox` smallint(5) unsigned NOT NULL default '25',
  `pm_savebox` smallint(5) unsigned NOT NULL default '25',
  `pm_send2group` smallint(5) unsigned NOT NULL default '103',
  `pm_hide_rcpts` tinyint(1) unsigned NOT NULL default '0'
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "settings : ".mysql_error();
}

//
// Code to create table `shoutbox`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."shoutbox");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."shoutbox (
  `shout_id` smallint(5) unsigned NOT NULL auto_increment,
  `shout_name` varchar(50) NOT NULL default '',
  `shout_message` varchar(200) NOT NULL default '',
  `shout_datestamp` int(10) unsigned NOT NULL default '0',
  `shout_ip` varchar(20) NOT NULL default '0.0.0.0',
  PRIMARY KEY  (`shout_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "shoutbox : ".mysql_error();
}

//
// Code to create table `site_links`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."site_links");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."site_links (
  `link_id` smallint(5) unsigned NOT NULL auto_increment,
  `link_name` varchar(100) NOT NULL default '',
  `link_url` varchar(200) NOT NULL default '',
  `panel_name` varchar(200) NOT NULL default '',
  `link_visibility` tinyint(3) unsigned NOT NULL default '0',
  `link_position` tinyint(1) unsigned NOT NULL default '1',
  `link_window` tinyint(1) unsigned NOT NULL default '0',
  `link_aid` tinyint(1) unsigned NOT NULL default '0',
  `link_parent` tinyint(3) unsigned NOT NULL default '0',
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
  `thread_id` smallint(5) unsigned NOT NULL default '0',
  `notify_datestamp` int(10) unsigned NOT NULL default '0',
  `notify_user` smallint(5) unsigned NOT NULL default '0',
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
  `thread_id` smallint(5) unsigned NOT NULL auto_increment,
  `thread_subject` varchar(100) NOT NULL default '',
  `thread_author` smallint(5) unsigned NOT NULL default '0',
  `thread_views` smallint(5) unsigned NOT NULL default '0',
  `thread_lastpost` int(10) unsigned NOT NULL default '0',
  `thread_lastuser` smallint(5) unsigned NOT NULL default '0',
  `thread_sticky` tinyint(1) unsigned NOT NULL default '0',
  `thread_locked` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`thread_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "threads : ".mysql_error();
}

//
// Code to create table `user_groups`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_groups");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."user_groups (
  `group_id` tinyint(3) unsigned NOT NULL auto_increment,
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
  `user_id` smallint(5) unsigned NOT NULL auto_increment,
  `user_md5id` varchar(32) NOT NULL default '',
  `user_name` varchar(30) NOT NULL default '',
  `user_fullname` varchar(50) NOT NULL default '',
  `user_password` varchar(32) NOT NULL default '',
  `user_webmaster` tinyint(1) unsigned NOT NULL default '0',
  `user_email` varchar(100) NOT NULL default '',
  `user_hide_email` tinyint(1) unsigned NOT NULL default '1',
  `user_location` varchar(50) NOT NULL default '',
  `user_birthdate` date NOT NULL default '0000-00-00',
  `user_aim` varchar(16) NOT NULL default '',
  `user_icq` varchar(15) NOT NULL default '',
  `user_msn` varchar(100) NOT NULL default '',
  `user_yahoo` varchar(100) NOT NULL default '',
  `user_web` varchar(200) NOT NULL default '',
  `user_forum_fullscreen` tinyint(1) unsigned NOT NULL default '0',
  `user_theme` varchar(100) NOT NULL default 'Default',
  `user_offset` varchar(6) NOT NULL default '',
  `user_avatar` varchar(100) NOT NULL default '',
  `user_sig` text NOT NULL,
  `user_posts` smallint(5) unsigned NOT NULL default '0',
  `user_joined` int(10) unsigned NOT NULL default '0',
  `user_lastvisit` int(10) unsigned NOT NULL default '0',
  `user_ip` varchar(20) NOT NULL default '0.0.0.0',
  `user_rights` text NOT NULL,
  `user_groups` text NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL default '101',
  `user_status` tinyint(1) unsigned NOT NULL default '0',
  `user_ban_reason` varchar(100) NOT NULL default '',
  `user_ban_expire` int(10) unsigned NOT NULL default '0',
  `user_newsletters` smallint(5) unsigned NOT NULL default '1',
  `user_sponsor` tinyint(1) unsigned NOT NULL default '0',
  `user_cc_code` char(2) NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "users : ".mysql_error();
}

//
// Code to create table `vcode`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."vcode");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."vcode (
  `vcode_datestamp` int(10) unsigned NOT NULL default '0',
  `vcode_1` varchar(5) NOT NULL default '',
  `vcode_2` varchar(32) NOT NULL default ''
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "vcode : ".mysql_error();
}

//
// Code to create table `wiki_acls`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."wiki_acls");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."wiki_acls (
  `page_tag` varchar(75) NOT NULL default '',
  `read_acl` text NOT NULL,
  `write_acl` text NOT NULL,
  `comment_acl` text NOT NULL,
  PRIMARY KEY  (`page_tag`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "wiki_acls : ".mysql_error();
}

//
// Code to create table `wiki_comments`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."wiki_comments");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."wiki_comments (
  `id` int(10) unsigned NOT NULL auto_increment,
  `page_tag` varchar(75) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `comment` text NOT NULL,
  `user` varchar(75) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx_page_tag` (`page_tag`),
  KEY `idx_time` (`time`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "wiki_comments : ".mysql_error();
}

//
// Code to create table `wiki_links`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."wiki_links");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."wiki_links (
  `from_tag` varchar(75) NOT NULL default '',
  `to_tag` varchar(75) NOT NULL default '',
  UNIQUE KEY `from_tag` (`from_tag`,`to_tag`),
  KEY `idx_from` (`from_tag`),
  KEY `idx_to` (`to_tag`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "wiki_links : ".mysql_error();
}

//
// Code to create table `wiki_pages`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."wiki_pages");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."wiki_pages (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` varchar(75) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `body` mediumtext NOT NULL,
  `owner` varchar(75) NOT NULL default '',
  `user` varchar(75) NOT NULL default '',
  `latest` enum('Y','N') NOT NULL default 'N',
  `note` varchar(100) NOT NULL default '',
  `hits` int(11) NOT NULL default '0',
  `handler` varchar(30) NOT NULL default 'page',
  PRIMARY KEY  (`id`),
  KEY `idx_tag` (`tag`),
  KEY `idx_time` (`time`),
  KEY `idx_latest` (`latest`),
  FULLTEXT KEY `body` (`body`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "wiki_pages : ".mysql_error();
}

//
// Code to create table `wiki_referrer_blacklist`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."wiki_referrer_blacklist");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."wiki_referrer_blacklist (
  `spammer` varchar(150) NOT NULL default '',
  KEY `idx_spammer` (`spammer`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "wiki_referrer_blacklist : ".mysql_error();
}

//
// Code to create table `wiki_referrers`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."wiki_referrers");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."wiki_referrers (
  `page_tag` varchar(75) NOT NULL default '',
  `referrer` varchar(150) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  KEY `idx_page_tag` (`page_tag`),
  KEY `idx_time` (`time`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "wiki_referrers : ".mysql_error();
}

//
// Code to create table `wiki_users`
//
$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."wiki_users");
$result = dbquery("CREATE TABLE IF NOT EXISTS ".$db_prefix."wiki_users (
  `name` varchar(75) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `revisioncount` int(10) unsigned NOT NULL default '20',
  `changescount` int(10) unsigned NOT NULL default '50',
  `doubleclickedit` enum('Y','N') NOT NULL default 'Y',
  `signuptime` datetime NOT NULL default '0000-00-00 00:00:00',
  `show_comments` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`name`),
  KEY `idx_signuptime` (`signuptime`)
) ENGINE=MYISAM;");
if (!$result) {
	$fail = "1";
	$failed[] = "wiki_users : ".mysql_error();
}
//----------------------------------------------------------
?>