<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| Some portions copyright ? 2002 - 2006 Nick Jones   |
| http://www.php-fusion.co.uk/                       |
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
if (!checkrights("I") || !defined("iAUTH") || $aid != iAUTH || !defined('ExiteCMS_INIT')) fallback(BASEDIR."index.php");

/*---------------------------------------------------+
| Locale definition for this installation module     |
+----------------------------------------------------*/

// no locales for this plugin

/*---------------------------------------------------+
| Module identification                              |
+----------------------------------------------------*/
$mod_title = "Advanced login panel";
$mod_description = "Side panel with member login, and a member menu";
$mod_version = "1.0.0";
$mod_developer = "WanWizard";
$mod_email = "wanwizard@gmail.com";
$mod_weburl = "http://exitecms.exite.eu/";
$mod_type = "P";

/*---------------------------------------------------+
| Module administration panel installation details   |
+----------------------------------------------------*/

$mod_folder = "user_info_panel";
// no administration module for this plugin

/*---------------------------------------------------+
| Version and revision control                       |
+----------------------------------------------------*/

// check for a minumum version of the ExiteCMS engine
if (str_replace(".", "", $settings['version']) < 700) {
	$mod_errors .= sprintf($locale['mod001'], '7.00');
}
// check for a maximum version of the ExiteCMS engine
if (str_replace(".", "", $settings['version']) > 700) {
	$mod_errors .= sprintf($locale['mod002'], '7.00');
}
// check for a specific revision number range that is supported
if ($settings['revision'] < 0 || $settings['revision'] > 999999) {
	$mod_errors .= sprintf($locale['mod003'], 0, 999999);
}

/*---------------------------------------------------+
| Menu entries for this module                       |
+----------------------------------------------------*/

$mod_site_links = array();

/*---------------------------------------------------+
| commands to execute when installing this module    |
+----------------------------------------------------*/

$mod_install_cmds = array();

/*---------------------------------------------------+
| commands to execute when uninstalling this module  |
+----------------------------------------------------*/

$mod_uninstall_cmds = array();

/*---------------------------------------------------+
| function to upgrade from a previous revision       |
+----------------------------------------------------*/
if (!function_exists('module_upgrade')) {
	function module_upgrade($current_version) {
	}
}
?>