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
$_revision = '1638';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(15,00,0,8,15,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Migration of frontpage news items to the frontpage table to enable frontpage localisation");

// array to store the commands of this update
$commands = array();

// Create the new news_frontpage table
$commands[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##news_frontpage (
  frontpage_id smallint(5) unsigned NOT NULL auto_increment,
  frontpage_locale varchar(8) NOT NULL default '',
  frontpage_headline tinyint(1) unsigned NOT NULL default '0',
  frontpage_order smallint(5) unsigned NOT NULL default '0',
  frontpage_news_id smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY (frontpage_id)
) ENGINE=MyISAM");

// migrate the frontpage date from the news table to the frontpage table
$commands[] = array('type' => 'function', 'value' => "frontpage_news");

// remove the headline and latest news fields from the news table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##news DROP news_headline, DROP news_latest_news");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function frontpage_news() {
	global $db_prefix, $settings;

	$locale = $settings['news_localisation'] == "none" ? "" : $settings['locale_code'];

	// get all defined headlines from the news table
	$result = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_headline > 0 ORDER BY news_headline ASC");
	if (dbrows($result)) {
		// add them to the frontpage table
		while ($data = dbarray($result)) {
			$result2 = dbquery("INSERT INTO ".$db_prefix."news_frontpage (frontpage_locale, frontpage_headline, frontpage_order, frontpage_news_id) VALUES ('".$locale."', 1, '".$data['news_headline']."', '".$data['news_id']."')");
		}
	}

	// get other frontpage news from the news table
	$result = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_latest_news > 0 ORDER BY news_latest_news ASC");
	if (dbrows($result)) {
		// add them to the frontpage table
		while ($data = dbarray($result)) {
			$result2 = dbquery("INSERT INTO ".$db_prefix."news_frontpage (frontpage_locale, frontpage_headline, frontpage_order, frontpage_news_id) VALUES ('".$locale."', 0, '".$data['news_latest_news']."', '".$data['news_id']."')");
		}
	}
}

?>
