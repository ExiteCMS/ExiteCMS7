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
$_revision = '1782';

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(18,00,0,9,19,2008),
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Database modifications for the new Photo Albums module.");

// array to store the commands of this update
$commands = array();

// add the new admin module "Photo albums"
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S5', 'photoalbums.gif', '261', 'settings_image.php', 1)");

// add the configuration items for this module
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('albums_create', '103')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('albums_moderators', '103')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('albums_anonymous', '0')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('albums_columns', '1')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('albums_per_page', '5')");
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'thumbs_per_row'");

// add the new "Albums" table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##albums (
  album_id smallint(5) unsigned NOT NULL auto_increment,
  album_title varchar(50) NOT NULL default '',
  album_description TEXT,
  album_highlight mediumint(8) unsigned NOT NULL default 0,
  album_count int(10) NOT NULL default 0,
  album_owner mediumint(8) unsigned NOT NULL default 0,
  album_read smallint(5) NOT NULL default -1,
  album_write smallint(5) NOT NULL default -1,
  album_allow_comments tinyint(1) NOT NULL default 1,
  album_allow_ratings tinyint(1) NOT NULL default 1,
  album_datestamp int(10) unsigned NOT NULL default 0,
  PRIMARY KEY (album_id)
) ENGINE=MyISAM");

// add the new "Photos" table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##photos (
  photo_id mediumint(8) unsigned NOT NULL auto_increment,
  photo_name varchar(255) NOT NULL default '',
  photo_thumb varchar(50) NOT NULL default '',
  photo_thumb_count int(10) NOT NULL default 0,
  photo_sized varchar(50) NOT NULL default '',
  photo_sized_count int(10) NOT NULL default 0,
  photo_original varchar(50) NOT NULL default '',
  photo_original_count int(10) NOT NULL default 0,
  photo_size int(10) NOT NULL default 0,
  photo_uploaded_by mediumint(8) unsigned NOT NULL default 0,
  photo_exif_info TEXT,
  photo_datestamp int(10) NOT NULL default 0,
  PRIMARY KEY (photo_id)
) ENGINE=MyISAM");

// add the new "Photos in Album" table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##album_photos (
  album_photo_id mediumint(8) unsigned NOT NULL auto_increment,
  album_id smallint(5) unsigned NOT NULL default 0,
  photo_id mediumint(8) unsigned NOT NULL default 0,
  album_photo_title varchar(50) NOT NULL default '',
  album_photo_description TEXT,
  album_photo_datestamp int(10) NOT NULL default 0,
  PRIMARY KEY (album_photo_id),
  UNIQUE (album_id, photo_id)
) ENGINE=MyISAM");

// add the new "Galleries" table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##galleries (
  gallery_id smallint(5) unsigned NOT NULL auto_increment,
  gallery_title varchar(50) NOT NULL default '',
  gallery_description TEXT,
  gallery_highlight mediumint(8) unsigned NOT NULL default 0,
  gallery_count int(10) NOT NULL default 0,
  gallery_read smallint(5) NOT NULL default -1,
  gallery_write smallint(5) NOT NULL default -1,
  gallery_allow_comments tinyint(1) NOT NULL default 1,
  gallery_allow_ratings tinyint(1) NOT NULL default 1,
  gallery_owner mediumint(8) unsigned NOT NULL default 0,
  gallery_datestamp int(10) unsigned NOT NULL default 0,
  PRIMARY KEY (gallery_id)
) ENGINE=MyISAM");

// add the new "Photos in Gallery" table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##gallery_photos (
  gallery_photo_id mediumint(8) unsigned NOT NULL auto_increment,
  gallery_id smallint(5) unsigned NOT NULL default 0,
  photo_id mediumint(8) unsigned NOT NULL default 0,
  gallery_photo_title varchar(50) NOT NULL default '',
  gallery_photo_description TEXT,
  gallery_photo_datestamp int(10) unsigned NOT NULL default 0,
  PRIMARY KEY (gallery_photo_id),
  UNIQUE (gallery_id, photo_id)
) ENGINE=MyISAM");

// add the personal message searche to the search table
$commands[] = array('type' => 'function', 'value' => "rev1782_add_to_menu");

// check if the albums directory exists, if not, create it
if (!is_dir(PATH_PHOTOS)) {
	@mkdir(PATH_PHOTOS, 0770);
	@touch(PATH_PHOTOS."index.php");
}
/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function rev1782_add_to_menu() {
	global $db_prefix, $settings;

	// determine the next menu order number
	$order = dbfunction("MAX(link_order)", "site_links") + 1;

	$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_locale, link_url, panel_name, link_visibility, link_position, link_window, link_order)
						VALUES('Photo Albums', '".$settings['locale_code']."', 'albums.php', 'main_menu_panel', 103, 1, 0, ".$order.")");
}
?>
