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
| $Id:: rev02123.php 2128 2008-12-19 13:02:47Z WanWizard              $|
+----------------------------------------------------------------------+
| Last modified by $Author:: WanWizard                                $|
| Revision number $Rev:: 2128                                         $|
+---------------------------------------------------------------------*/
if (strpos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false || !defined('INIT_CMS_OK')) die();

// upgrade for revision
$_revision = '2276';

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(11,00,0,11,1,2009),
					'title' => "Required updates for ExiteCMS v7.2 rev.".$_revision,
					'description' => "Added a user profile option to allow users to automatically track threads they participate in.");

// array to store the commands of this update
$commands = array();

// database changes

// add the user_datastore field to the user table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##users ADD user_posts_track TINYINT(1) NOT NULL DEFAULT 0 AFTER user_posts_unread");
?>
