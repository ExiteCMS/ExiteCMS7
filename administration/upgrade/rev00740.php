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
$_revision = 740;

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(18,00,0,7,17,2007), 
					'title' => "Required updates for ExiteCMS v6.2 rev.".$_revision,
					'description' => "This revision introduces the new PM system, and drops support for the Photo Albums, Weblinks and Submissions modules."
				);

// array to store the commands of this update
$commands = array();

// changes for this release
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##polls");
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##poll_votes");
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##photos");
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##photo_albums");
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##weblinks");
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##weblink_cats");
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##submissions");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##download_cats ADD download_cat_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'download_cat_id DESC' AFTER download_cat_sorting");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##download_cats ADD download_datestamp INT(10) UNSIGNED NOT NULL DEFAULT 0");
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##pm (
  pm_id smallint(5) unsigned NOT NULL auto_increment,
  pm_subject varchar(100) NOT NULL default '',
  pm_message text NOT NULL,
  pm_recipients text NOT NULL,
  pm_smileys tinyint(1) unsigned NOT NULL default 1,
  pm_size smallint(5) unsigned NOT NULL default 0,
  pm_datestamp int(10) unsigned NOT NULL default 0,
  PRIMARY KEY  (pm_id)
) ENGINE=MyISAM;");
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##pm_index (
  pmindex_id smallint(5) unsigned NOT NULL auto_increment,
  pm_id smallint(5) unsigned NOT NULL,
  pmindex_user_id smallint(5) unsigned NOT NULL default 0,
  pmindex_reply_id smallint(5) unsigned NOT NULL default 0,
  pmindex_from_id smallint(5) unsigned NOT NULL default 0,
  pmindex_from_email varchar(100) NOT NULL default '',
  pmindex_to_id smallint(5) unsigned NOT NULL default 0,
  pmindex_to_email varchar(100) NOT NULL default '',
  pmindex_to_group tinyint(1) unsigned NOT NULL default 0,
  pmindex_read_datestamp int(10) unsigned NOT NULL default 0,
  pmindex_read_requested tinyint(1) unsigned NOT NULL default 0,
  pmindex_folder tinyint(1) unsigned NOT NULL default 0,
  pmindex_locked tinyint(1) unsigned NOT NULL default 0,
  PRIMARY KEY  (pmindex_id)
) ENGINE=MyISAM;");
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##pm_config (
  pmconfig_id smallint(5) unsigned NOT NULL auto_increment,
  user_id smallint(5) unsigned NOT NULL,
  pmconfig_save_sent tinyint(1) unsigned NOT NULL default 0,
  pmconfig_read_notify tinyint(1) unsigned NOT NULL default 0,
  pmconfig_email_notify tinyint(1) unsigned NOT NULL default 0,
  pmconfig_auto_archive smallint(5) unsigned NOT NULL default '90',
  pmconfig_view tinyint(1) unsigned NOT NULL default 0,
  PRIMARY KEY  (pmconfig_id)
) ENGINE=MyISAM;");
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##pm_attachments (
  pmattach_id smallint(5) unsigned NOT NULL auto_increment,
  pm_id smallint(5) unsigned NOT NULL,
  pmattach_name varchar(100) NOT NULL default '',
  pmattach_realname varchar(100) NOT NULL default '',
  pmattach_comment varchar(255) NOT NULL default '',
  pmattach_ext varchar(5) NOT NULL default '',
  pmattach_size int(20) unsigned NOT NULL default 0,
  PRIMARY KEY  (pmattach_id)
) ENGINE=MyISAM;");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##download_cats SET download_datestamp = '".time()."'");
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##admin WHERE admin_link = 'photoalbums.php' AND admin_page = '1'");
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##admin WHERE admin_link = 'submissions.php' AND admin_page = '2'");
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##admin WHERE admin_link = 'weblinks.php' AND admin_page = '1'");
$commands[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##admin WHERE admin_link = 'weblink_cats.php' AND admin_page = '1'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_image = 'images.gif', admin_title = 'Image Settings', admin_link = 'settings_image.php' WHERE admin_link = 'settings_photo.php' AND admin_page = '3'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_image = 'adverts.gif' WHERE admin_link = 'adverts.php' AND admin_page = '1'");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD pm_inbox SMALLINT(5) UNSIGNED NOT NULL DEFAULT 25");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD pm_sentbox SMALLINT(5) UNSIGNED NOT NULL DEFAULT 25");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD pm_savebox SMALLINT(5) UNSIGNED NOT NULL DEFAULT 25");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD pm_send2group SMALLINT(5) UNSIGNED NOT NULL DEFAULT 103");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##settings ADD pm_hide_rcpts TINYINT(1) UNSIGNED NOT NULL DEFAULT 0");

$commands[] = array('type' => 'function', 'value' => "migrate_messages");

$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##messages");
$commands[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##messages_options");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function migrate_messages() {
	global $db_prefix;

	// convert messages_options to pm_config
	$result = dbquery("SELECT * FROM ".$db_prefix."messages_options");
	while ($data = dbarray($result)) {
		$result2 = dbquery("INSERT INTO ".$db_prefix."pm_config (user_id, pmconfig_email_notify, pmconfig_save_sent) VALUES ('".$data['user_id']."', '".$data['pm_email_notify']."', '".$data['pm_save_sent']."')");
	}

	// convert messages to pm and pm_index
	$result = dbquery("SELECT * FROM ".$db_prefix."messages");
	while ($data = dbarray($result)) {

		switch ($data['message_folder']) {
			case '0': // inbox
				// add the message record
				$result2 = dbquery("INSERT INTO ".$db_prefix."pm (pm_subject, pm_message, pm_recipients, pm_smileys, pm_size, pm_datestamp)
					VALUES ('".mysql_escape_string($data['message_subject'])."', '".mysql_escape_string($data['message_message'])."', '".$data['message_to']."', '".($data['message_smileys']=='0'?'1':'0')."', '".strlen($data['message_message'])."', '".$data['message_datestamp']."')");
				// get the key of the inserted message record
				$pm_id = mysql_insert_id();
				// insert the inbox index record
				$result2 = dbquery("INSERT INTO ".$db_prefix."pm_index (pm_id, pmindex_user_id, pmindex_from_id, pmindex_to_id, pmindex_read_datestamp, pmindex_folder)
						VALUES ('".$pm_id."', '".$data['message_to']."', '".$data['message_from']."', '".$data['message_to']."', '".$data['message_datestamp']."', '".$data['message_folder']."')");
				break;
			case '1': // outbox
				// add the message record
				$result2 = dbquery("INSERT INTO ".$db_prefix."pm (pm_subject, pm_message, pm_recipients, pm_smileys, pm_size, pm_datestamp)
					VALUES ('".mysql_escape_string($data['message_subject'])."', '".mysql_escape_string($data['message_message'])."', '".$data['message_from']."', '".($data['message_smileys']=='0'?'1':'0')."', '".strlen($data['message_message'])."', '".$data['message_datestamp']."')");
				// get the key of the inserted message record
				$pm_id = mysql_insert_id();
				// add the outbox index record
				$result2 = dbquery("INSERT INTO ".$db_prefix."pm_index (pm_id, pmindex_user_id, pmindex_from_id, pmindex_to_id, pmindex_read_datestamp, pmindex_folder)
						VALUES ('".$pm_id."', '".$data['message_to']."', '".$data['message_to']."', '0', '".$data['message_datestamp']."', '".$data['message_folder']."')");
				break;
			case '2': // archive
				// add the message record
				$result2 = dbquery("INSERT INTO ".$db_prefix."pm (pm_subject, pm_message, pm_recipients, pm_smileys, pm_size, pm_datestamp)
					VALUES ('".mysql_escape_string($data['message_subject'])."', '".mysql_escape_string($data['message_message'])."', '".$data['message_to']."', '".($data['message_smileys']=='0'?'1':'0')."', '".strlen($data['message_message'])."', '".$data['message_datestamp']."')");
				// get the key of the inserted message record
				$pm_id = mysql_insert_id();
				// insert the archive index record
				$result2 = dbquery("INSERT INTO ".$db_prefix."pm_index (pm_id, pmindex_user_id, pmindex_from_id, pmindex_to_id, pmindex_read_datestamp, pmindex_folder)
						VALUES ('".$pm_id."', '".$data['message_to']."', '".$data['message_from']."', '".$data['message_to']."', '".$data['message_datestamp']."', '".$data['message_folder']."')");
				break;
		}
	} 
}
?>
