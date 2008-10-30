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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// make sure the parameter passed is valid
if (!isset($cat_id)) $cat_id = 0;
if (isset($cat_id) && !isNum($cat_id)) fallback("index.php");

// load this module's locales
locale_load("main.faq");

// temp storage for template variables
$variables = array();

// if no cat_id given, show the FAQ categories
if (!$cat_id) {
	$title = $locale['400'];
	$result = dbquery("SELECT * FROM ".$db_prefix."faq_cats ORDER BY faq_cat_name");
	$variables['faqs'] = array();
	while($data = dbarray($result)) {
		$data['count'] = dbcount("(faq_id)", "faqs", "faq_cat_id='".$data['faq_cat_id']."'");
		$variables['faqs'][] = $data;
	}
} else {
	if (!$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."faq_cats WHERE faq_cat_id='$cat_id'"))) {
		redirect(FUSION_SELF);
	}
	$title = $locale['401'].": ".$data['faq_cat_name'];
	$rows = dbcount("(*)", "faqs", "faq_cat_id='$cat_id'");
	$variables['rows'] = $rows;
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	$variables['rowstart'] = $rowstart;
	$variables['items_per_page'] = $settings['numofthreads'];
	if ($rows != 0) {
		$variables['faqs'] = array();
		$result = dbquery("SELECT * FROM ".$db_prefix."faqs WHERE faq_cat_id='$cat_id' ORDER BY faq_id LIMIT $rowstart,".$settings['numofthreads']);
		while ($data = dbarray($result)) {
			$data['faq_answer'] = nl2br(stripslashes($data['faq_answer']));
			$variables['faqs'][] = $data;
		}
	}
}

$variables['cat_id'] = $cat_id;
// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'faq', 'title' => $title, 'template' => 'main.faq.tpl', 'locale' => "main.faq");
$template_variables['faq'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
