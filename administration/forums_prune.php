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
if (eregi("forums_prune.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// temp storage for template variables
$variables = array();

//check if the user has a right to be here. If not, bail out
if (!checkrights("S3") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");

$expired = time()-(86400 * $_POST['prune_days']);
// Check number of posts & threads older than expired date and delete them
$result = dbquery("SELECT post_id,post_datestamp FROM ".$db_prefix."posts WHERE post_datestamp < $expired");
$delposts = dbrows($result);
if ($delposts != 0) {
	$delattach = 0;
	while ($data = dbarray($result)) {
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='".$data['post_id']."'");
		if (dbrows($result2) != 0) {
			$delattach++;
			$attach = dbarray($result2);
			@unlink(PATH_ATTACHMENTS.$attach['attach_name']);
			$result3 = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE post_id='".$data['post_id']."'");
		}
	}
}
$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE post_datestamp < $expired");
$result = dbquery("SELECT thread_id,thread_lastpost FROM ".$db_prefix."threads WHERE thread_lastpost < $expired");
$delthreads = dbrows($result);
if ($delthreads != 0) {
	while ($data = dbarray($result)) {
		$result2 = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE thread_id='".$data['thread_id']."'");
	}
}
$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_lastpost < $expired");

// define the message panel variables
$variables['bold'] = true;
$variables['message'] =$locale['801'].$delposts."<br />".$locale['802'].$delthreads."<br />".$locale['803'].$delattach."<br />";
$template_panels[] = array('type' => 'body', 'name' => 'admin.forums_prune', 'title' => $locale['800'], 'template' => '_message_table_panel.tpl', 'locale' => "admin.settings");
$template_variables['admin.forums_prune'] = $variables;
?>
