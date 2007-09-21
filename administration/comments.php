<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.LOCALESET."admin/comments.php";

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("C") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid (and nobody is trying to edit the webmaster himself)
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
	$data['comment_message'] = nl2br(parseubb($data['comment_message']));
	if ($data['comment_smileys'] == "1") $data['comment_message'] = parsesmileys($data['comment_message']);
	$variables['comments'][] = $data;
}

// template variables
$variables['cid'] = $cid;
$variables['ctype'] = $ctype;
$variables['comment_id'] = $comment_id;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.comments', 'template' => 'admin.comments.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/comments.php");
$template_variables['admin.comments'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>