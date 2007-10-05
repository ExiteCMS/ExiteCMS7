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
$_revision = '877';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 'date' => mktime(21,30,0,5,10,2007), 'description' => "Required updates for ExiteCMS v7.00 rev.".$_revision."<br /><font color='red'>Added download category sorting</font>");

// array to store the commands of this update
$commands = array();

// database changes

// missing update of the download_cats table (missed in a previous rev update?)
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##download_cats ADD download_cat_cat_sorting VARCHAR(50) NOT NULL DEFAULT '' AFTER download_cat_sorting");
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##download_cats ADD download_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0'");

// and add default content
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##download_cats SET download_cat_cat_sorting = 'download_cat_id DESC', download_datestamp = '".time()."'");
?>