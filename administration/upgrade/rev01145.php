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
$_revision = '1145';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(16,00,0,12,2,2007), 
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Added localisation dropdowns for several CMS functions in the language settings admin panel.");

// array to store the commands of this update
$commands = array();

// database changes

// change the length of the config key
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##CMSconfig CHANGE cfg_name cfg_name VARCHAR(50) NOT NULL");

// add the 'localisation_method' fields to the CMSconfig table
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('download_localisation', 'none')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('article_localisation', 'none')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('news_localisation', 'none')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('panels_localisation', 'none')");
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##CMSconfig (cfg_name, cfg_value) VALUES ('sitelinks_localisation', 'none')");

// add the panel_locale field to the panels table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##panels ADD panel_locale VARCHAR(8) NOT NULL AFTER panel_name");
// and initialise the field with the system locale
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##panels SET panel_locale = '".$settings['locale_code']."'");

// add the link_locale field to the site_links table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##site_links ADD link_locale VARCHAR(8) NOT NULL AFTER link_name");
// and initialise the field with the system locale
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##site_links SET link_locale = '".$settings['locale_code']."'");

// add the article_locale field to the articles table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##articles ADD article_locale VARCHAR(8) NOT NULL AFTER article_name");
// and initialise the field with the system locale
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##articles SET article_locale = '".$settings['locale_code']."'");

// get the 404 error custom page, move it to the locales table
$commands[] = array('type' => 'function', 'value' => "move_404_page");
// and drop the page_seo_url field
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##custom_pages DROP page_seo_url");

// add the download_cat_locale field to the download_cats table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##download_cats ADD download_cat_locale VARCHAR(8) NOT NULL AFTER download_cat_name");
// and initialise the field with the system locale
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##download_cats SET download_cat_locale = '".$settings['locale_code']."'");

/*---------------------------------------------------+
| functions required for part of the upgrade process |
+----------------------------------------------------*/
function move_404_page() {
	
	global $db_prefix;
	
	$result = dbquery("SELECT * FROM ".$db_prefix."custom_pages WHERE page_id = '0'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$result = dbquery("INSERT INTO ".$db_prefix."locales (locales_code, locales_name, locales_key, locales_value, locales_datestamp) VALUES ('en', '404page', '404page', '".mysql_escape_string($data['page_content'])."', '".time()."')");
		$result = dbquery("DELETE FROM ".$db_prefix."custom_pages WHERE page_id = '0'");
	}
}
?>