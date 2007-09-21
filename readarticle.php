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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// temp storage for template variables
$variables = array();

if (!isset($article_id) || !isNum($article_id)) fallback("index.php");
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;

$result = dbquery(
	"SELECT ta.*,tac.*, tu.user_id,user_name FROM ".$db_prefix."articles ta
	INNER JOIN ".$db_prefix."article_cats tac ON ta.article_cat=tac.article_cat_id
	LEFT JOIN ".$db_prefix."users tu ON ta.article_name=tu.user_id
	WHERE article_id='$article_id'"
);

if (dbrows($result) == 0) {
	redirect("articles.php");
} else {
	$variables['article'] = array();
	$data = dbarray($result);
	if (checkgroup($data['article_cat_access'])) {
		// update the read counter
		if ($rowstart == 0) $result = dbquery("UPDATE ".$db_prefix."articles SET article_reads=article_reads+1 WHERE article_id='$article_id'");
		// process the raw fields if needed
		$data['article_article'] = stripslashes($data['article_article']);
		$data['article_article'] = explode("<--PAGEBREAK-->", $data['article_article']);
		$data['article_article'] = $data['article_article'][$rowstart];
		$data['pagecount'] = count($data['article_article']);
		$data['article_subject'] = stripslashes($data['article_subject']);
		$data['article_comments'] = dbcount("(comment_id)", "comments", "comment_type='A' AND comment_item_id='".$data['article_id']."'");
	}
	// and store it for use in the template
	$variables['article'][] = $data;
}

// other variables needed in the template
$variables['rowstart'] = $rowstart;
$variables['allow_edit'] = checkrights("A");

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'readarticle', 'template' => 'main.readarticle.tpl');
$template_variables['readarticle'] = $variables;

// check if we need to display comments
if ($data['article_allow_comments']) {
	include PATH_INCLUDES."comments_include.php";
	showcomments("A","articles","article_id",$article_id,FUSION_SELF."?article_id=$article_id");
}

// check if we need to display ratings
if ($data['article_allow_ratings']) {
	include PATH_INCLUDES."ratings_include.php";
	showratings("A",$article_id,FUSION_SELF."?article_id=$article_id");
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>