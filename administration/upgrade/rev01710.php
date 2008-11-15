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
$_revision = '1710';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision, 
					'date' => mktime(16,00,0,8,28,2008), 
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "New reporting options for several core modules");

// array to store the commands of this update
$commands = array();

// Add the 'users per country' core report
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##reports (report_mod_id, report_mod_core, report_name, report_title, report_version, report_active, report_visibility) VALUES ('0', '1', 'usercountries', 'rpt500', '1.0.0', '0', '102')");

// Add the 'users joined per month' core report
$commands[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##reports (report_mod_id, report_mod_core, report_name, report_title, report_version, report_active, report_visibility) VALUES ('0', '1', 'usersjoined', 'rpt509', '1.0.0', '0', '102')");
?>
