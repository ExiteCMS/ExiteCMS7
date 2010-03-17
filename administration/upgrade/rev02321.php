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
| $Id:: rev01818.php 2033 2008-11-15 19:51:44Z webmaster              $|
+----------------------------------------------------------------------+
| Last modified by $Author:: webmaster                                $|
| Revision number $Rev:: 2033                                         $|
+---------------------------------------------------------------------*/

// upgrade for revision
$_revision = '2321';

if (eregi("rev".substr("00000".$_revision,-5).".php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(21,00,0,17,3,2010),
					'title' => "Required updates for ExiteCMS v7.2 rev.".$_revision,
					'description' => "Moved the release to version 7.3.");

// array to store the commands of this update
$commands = array();

// database changes

// update the version number
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##configuration SET cfg_value='7.30' WHERE cfg_name='version'");
?>
