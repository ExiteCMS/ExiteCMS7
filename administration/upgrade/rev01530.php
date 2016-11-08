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
$_revision = '1530';

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(15,30,0,7,8,2008),
					'title' => "Required updates for ExiteCMS v7.1 rev.".$_revision,
					'description' => "Added the possibility to have an external process count downloads.");

// array to store the commands of this update
$commands = array();

// delete the downloads_bar field, it is no longer used (functionality now in the download statistics module)
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##downloads DROP download_bar");

// add a 'external download indicator' field to the downloads table
$commands[] = array('type' => 'db', 'value' => "ALTER TABLE ##PREFIX##downloads ADD download_external TINYINT(1) NOT NULL DEFAULT 0 AFTER download_count");

?>
