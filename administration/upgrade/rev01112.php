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
$_revision = '1112';

// make sure the required array's exist
if (!isset($revisions) || !is_array($revisions)) $revisions = array();
if (!isset($commands) || !is_array($commands)) $commands = array();

// register this revision update
$revisions[] = array('revision' => $_revision,
					'date' => mktime(18,00,0,11,11,2007),
					'title' => "Required updates for ExiteCMS v7.0 rev.".$_revision,
					'description' => "Make the administration index locale aware."
				);

// array to store the commands of this update
$commands = array();

$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '203' WHERE admin_link = 'articles.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '202' WHERE admin_link = 'article_cats.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '201' WHERE admin_link = 'administrators.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '204' WHERE admin_link = 'blacklist.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '' WHERE admin_link = 'reserved'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '206' WHERE admin_link = 'custom_pages.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '209' WHERE admin_link = 'downloads.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '207' WHERE admin_link = 'db_backup.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '208' WHERE admin_link = 'download_cats.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '211' WHERE admin_link = 'forums.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '210' WHERE admin_link = 'faq.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '213' WHERE admin_link = 'modules.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '212' WHERE admin_link = 'images.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '' WHERE admin_link = 'reserved'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '215' WHERE admin_link = 'members.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '216' WHERE admin_link = 'news.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '235' WHERE admin_link = 'news_cats.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '217' WHERE admin_link = 'panels.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '219' WHERE admin_link = 'phpinfo.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '220' WHERE admin_link = 'forum_polls.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '228' WHERE admin_link = 'settings_main.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '229' WHERE admin_link = 'settings_time.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '230' WHERE admin_link = 'settings_forum.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '231' WHERE admin_link = 'settings_registration.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '233' WHERE admin_link = 'settings_misc.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '234' WHERE admin_link = 'settings_messages.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '236' WHERE admin_link = 'settings_languages.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '222' WHERE admin_link = 'site_links.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '224' WHERE admin_link = 'upgrade.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '225' WHERE admin_link = 'user_groups.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '218' WHERE admin_link = 'redirects.php'");
$commands[] = array('type' => 'db', 'value' => "UPDATE ##PREFIX##admin SET admin_title = '223' WHERE admin_link = 'tools.php'");

?>
