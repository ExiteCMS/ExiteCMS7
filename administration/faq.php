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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.LOCALESET."admin/faq.php";

// temp storage for template variables
$variables = array();

//check if the user has a right to be here. If not, bail out
if (!checkrights("FQ") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// verify the parameters
if (isset($faq_cat_id) && !isNum($faq_cat_id)) fallback(FUSION_SELF);
if (isset($faq_id) && !isNum($faq_id)) fallback(FUSION_SELF);

// initialise some variables we need later
if (!isset($faq_cat_id)) $faq_cat_id = "";
if (!isset($action)) $action = "";
if (!isset($t)) $t = "";

if (isset($status)) {
	if ($status == "delcn") {
		$title = $locale['400'];
		$variables['message'] = $locale['404']."<br />".$locale['405'];
	} elseif ($status == "delcy") {
		$title = $locale['400'];
		$variables['message'] = $locale['401'];
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.faq.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/faq.php");
	$template_variables['admin.faq.status'] = $variables;
	$variables = array();
}

if ($action == "delete" && $t == "cat") {
	$result = dbquery("SELECT * FROM ".$db_prefix."faqs WHERE faq_cat_id='$faq_cat_id'");
	if (dbrows($result) != 0) {
		redirect(FUSION_SELF.$aidlink."&status=delcn");
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."faq_cats WHERE faq_cat_id='$faq_cat_id'");
		redirect(FUSION_SELF.$aidlink."&status=delcy");
	}
} else {
	if ($action == "delete" && $t == "faq") {
		$faq_count = dbcount("(faq_id)", "faqs", "faq_id='$faq_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."faqs WHERE faq_id='$faq_id'");
		if ($faq_count != 0) {
			redirect(FUSION_SELF.$aidlink."&faq_cat_id=$faq_cat_id");
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
	if (isset($_POST['save_cat'])) {
		$faq_cat_name = stripinput($_POST['faq_cat_name']);
		$faq_cat_description = stripinput($_POST['faq_cat_description']);
		if ($action == "edit" && $t == "cat") {
			$result = dbquery("UPDATE ".$db_prefix."faq_cats SET faq_cat_name='$faq_cat_name', faq_cat_description='$faq_cat_description' WHERE faq_cat_id='$faq_cat_id'");
		} else {
			if ($faq_cat_name != "") {
				$result = dbquery("INSERT INTO ".$db_prefix."faq_cats (faq_cat_name, faq_cat_description) VALUES('$faq_cat_name', '$faq_cat_description')");
			}
		}
		redirect(FUSION_SELF.$aidlink);
	}
	if (isset($_POST['save_faq'])) {
		$faq_question = stripinput($_POST['faq_question']);
		$faq_answer = addslash($_POST['faq_answer']);
		if ($action == "edit" && $t == "faq") {
			$result = dbquery("UPDATE ".$db_prefix."faqs SET faq_cat_id='$faq_cat', faq_question='$faq_question', faq_answer='$faq_answer' WHERE faq_id='$faq_id'");
		} else {
			if ($faq_question != "" && $faq_answer != "") {
				$result = dbquery("INSERT INTO ".$db_prefix."faqs (faq_cat_id, faq_question, faq_answer) VALUES ('$faq_cat', '$faq_question', '$faq_answer')");
			}
		}
		redirect(FUSION_SELF.$aidlink."&faq_cat_id=$faq_cat");
	}
	if ($action == "edit") {
		if ($t == "cat") {
			$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."faq_cats WHERE faq_cat_id='$faq_cat_id'"));
			$faq_cat_id = $data['faq_cat_id'];
			$faq_cat_name = $data['faq_cat_name'];
			$faq_cat_description = $data['faq_cat_description'];
			$faq_cat_title = $locale['421'];
			$faq_cat_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;faq_cat_id=$faq_cat_id&amp;t=cat";
			// --------------------- //
			$faq_question = "";
			$faq_answer = "";
			$faq_title = $locale['422'];
			$faq_action = FUSION_SELF.$aidlink;
		} else if ($t == "faq") {
			$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."faqs WHERE faq_id='$faq_id'"));
			$faq_cat_name = "";
			$faq_cat_description = "";
			$faq_cat_title = $locale['420'];
			$faq_cat_action = FUSION_SELF.$aidlink;
			// --------------------- //
			$faq_id = $data['faq_id'];
			$faq_question = $data['faq_question'];
			$faq_answer = stripslashes($data['faq_answer']);
			$faq_title = $locale['423'];
			$faq_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;faq_id=$faq_id&amp;t=faq";
		}
	} else {
		$faq_cat_name = "";
		$faq_cat_description = "";
		$faq_cat_title = $locale['420'];
		$faq_cat_action = FUSION_SELF.$aidlink;
		$faq_question = "";
		$faq_answer = "";
		$faq_title = $locale['422'];
		$faq_action = FUSION_SELF.$aidlink;
	}
	if (!isset($t) || $t != "faq") {
	}
	if (!isset($t) || $t != "cat") {
		$variables['cats'] = array();
		$result2 = dbquery("SELECT * FROM ".$db_prefix."faq_cats ORDER BY faq_cat_name");
		if (dbrows($result2) != 0) {
//			if (!$t) tablebreak();
			while ($data2 = dbarray($result2)) {
				$data2['selected'] = ($action == "edit" && $t == "faq" && $data2['faq_cat_id'] == $faq_cat_id);
				$variables['cats'][] = $data2;
			}
		}
	}
	// assign work variables to the template
	$variables['faq_cat_id'] = $faq_cat_id;
	$variables['faq_cat_name'] = $faq_cat_name;
	$variables['faq_cat_description'] = $faq_cat_description;
	$variables['faq_cat_title'] = $faq_cat_title;
	$variables['faq_cat_action'] = $faq_cat_action;
//	$variables['faq_id'] = $faq_id;
	$variables['faq_action'] = $faq_action;
	$variables['faq_title'] = $faq_title;
	$variables['faq_question'] = $faq_question;
	$variables['faq_answer'] = phpentities(stripslashes($faq_answer));
	$variables['action'] = $action;
	$variables['actiontype'] = $t;

}

$result = dbquery("SELECT * FROM ".$db_prefix."faq_cats ORDER BY faq_cat_name");
$variables['tree'] = array();
while ($data = dbarray($result)) {
	$data['node'] = 'C';
	$variables['tree'][] = $data;
	$result2 = dbquery("SELECT * FROM ".$db_prefix."faqs WHERE faq_cat_id='".$data['faq_cat_id']."' ORDER BY faq_id");
	$rows = dbrows($result2);
	$row = 1;
	while ($data2 = dbarray($result2)) {
		$data2['node'] = 'A';
		$data2['first'] = $row == 1;
		$data2['last'] = $row == $rows;
		$data2['faq_answer'] = stripinput($data2['faq_answer']);
		$variables['tree'][] = $data2;
		$row++;
	}
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.faq', 'template' => 'admin.faq.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/faq.php");
$template_variables['admin.faq'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>