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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_INCLUDES."theme_functions.php";
require_once PATH_INCLUDES."forum_functions_include.php";

// load the locale for this module
locale_load("admin.comments");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("C") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameters are valid
if (isset($comment_id) && !isNum($comment_id)) fallback("index.php");
if (!isset($ctype) || !preg_match("/^[0-9A-Z]+$/i", $ctype)) fallback(BASEDIR."index.php");
if (!isset($cid) || !isNum($cid)) fallback("../index.php");

if (!isset($comment_id)) $comment_id = "";

if (isset($_POST['save_comment'])) {
	$comment_message = stripinput($_POST['comment_message']);
	$comment_smileys = isset($_POST['disable_smileys']) ? "0" : "1";
	$result = dbquery("UPDATE ".$db_prefix."comments SET comment_message='$comment_message', comment_smileys='$comment_smileys' WHERE comment_id='$comment_id'");
	redirect("comments.php?aid=".iAUTH."&ctype=$ctype&cid=$cid");
}
if (isset($step) && $step == "delete") {
	$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_id='$comment_id'");
	redirect("comments.php?aid=".iAUTH."&ctype=$ctype&cid=$cid");
}
if (isset($step) && $step == "edit") {
	$result = dbquery("SELECT * FROM ".$db_prefix."comments WHERE comment_id='$comment_id'");
	if (!dbrows($result)) fallback("comments.php?ctype=$ctype&cid=$cid");
	$data = dbarray($result);
	$variables['comment'] = $data;
}

$variables['comments'] = array();
$result = dbquery(
	"SELECT * FROM ".$db_prefix."comments LEFT JOIN ".$db_prefix."users
	ON ".$db_prefix."comments.comment_name=".$db_prefix."users.user_id
	WHERE comment_type='$ctype' AND comment_item_id='$cid' ORDER BY comment_datestamp ASC"
);
while ($data = dbarray($result)) {
	$data['comment_message'] = parsemessage(array(), $data['comment_message'], $data['comment_smileys'], true);
	$variables['comments'][] = $data;
}

// template variables
$variables['cid'] = $cid;
$variables['ctype'] = $ctype;
$variables['comment_id'] = $comment_id;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.comments', 'template' => 'admin.comments.tpl', 'locale' => "admin.comments");
$template_variables['admin.comments'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
