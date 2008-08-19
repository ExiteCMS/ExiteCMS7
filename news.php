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

// make sure the readmore variable is numeric. If not, reload the page
if (isset($readmore) && !isNum($readmore)) fallback(FUSION_SELF);

// compose the query where clause based on the localisation method choosen
switch ($settings['news_localisation']) {
	case "none":
		$fwhere = "";
		$nwhere = "";
		break;
	case "single":
		$fwhere = "";
		$nwhere = "";
		break;
	case "multiple":
		if (isset($_POST['news_locale'])) $news_locale = stripinput($_POST['news_locale']);
		if (isset($news_locale)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".stripinput($news_locale)."' AND locale_active = '1' LIMIT 1");
			if (!dbrows($result)) unset($news_locale);
		}
		if (!isset($news_locale)) $news_locale = $settings['locale_code'];
		$variables['news_locale'] = $news_locale;
		$fwhere = "frontpage_locale = '".$news_locale."' ";
		$nwhere = "news_locale = '".$news_locale."' ";
		break;
}

if (isset($readmore)) {
	// view a single news item
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
		LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
		WHERE ".groupaccess('news_visibility')." AND news_id='$readmore'"
	);
	if (dbrows($result) == 0) {
		redirect(FUSION_SELF);
	} else {
		$data = dbarray($result);
		// process the raw fields if needed
		$data['news_subject'] = stripslashes($data['news_subject']);
		$data['news_news'] = stripslashes($data['news_extended'] ? $data['news_extended'] : $data['news_news']);
		$data['news_breaks'] == "y" ? nl2br(stripslashes($data['news_news'])) : stripslashes($data['news_news']);
		$data['news_comments'] = dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'");
		$data['allow_comments'] = $data['news_allow_comments'];
		// if this item belongs to a category, get the id and the image
		if ($data['news_cat'] != 0) {
			$result2 = dbquery("SELECT * FROM ".$db_prefix."news_cats WHERE news_cat_id='".$data['news_cat']."'");
			if (dbrows($result2)) {
				$data2 = dbarray($result2);
				$data['news_cat_image'] = $data2['news_cat_image'];
				$data['news_cat_id'] = $data2['news_cat_id'];
			}
		}
		// and store it for use in the template
		$variables['news'][] = $data;
		// update the read counter
		$result = dbquery("UPDATE ".$db_prefix."news SET news_reads = news_reads + 1 WHERE news_id = '$readmore'");
	}
	// check if the user is allowed to edit
	$variables['allow_edit'] = checkrights("N");
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'news-readmore', 'template' => 'main.news.readmore.tpl');
	$template_variables['news-readmore'] = $variables;
	
	// check if we need to display comments
	if ($data['news_allow_comments']) {
		include PATH_INCLUDES."comments_include.php";
		showcomments("N","news","news_id",$readmore,FUSION_SELF."?readmore=$readmore");
	}
	
	// check if we need to display ratings
	if ($data['news_allow_ratings']) {
		include PATH_INCLUDES."ratings_include.php";
		showratings("N",$readmore,FUSION_SELF."?readmore=$readmore");
	}
} else {
	// show a news item overview
	// make sure rowstart is valid and initialised if needed
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	// check how many news items we have
	if ($settings['news_latest']) {
		// only the ones defined for the frontpage
		$result = dbquery("SELECT news_id FROM ".$db_prefix."news_frontpage f INNER JOIN ".$db_prefix."news ON news_id=frontpage_news_id WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")".($fwhere==""?"":(" AND ".$fwhere)));
	} else {
		// all news items
		$result = dbquery("SELECT news_id FROM ".$db_prefix."news WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")".($nwhere==""?"":(" AND ".$nwhere)));
	}
	$rows = dbrows($result);
	if ($rows != 0) {
		// news items found, fetch them, taking rowstart into account
		if ($settings['news_latest']) {
			$result = dbquery(
				"SELECT nf.*, tn.*, tc.*, user_id, user_name FROM ".$db_prefix."news_frontpage nf
				INNER JOIN ".$db_prefix."news tn ON tn.news_id=nf.frontpage_news_id
				LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
				LEFT JOIN ".$db_prefix."news_cats tc ON tn.news_cat=tc.news_cat_id
				WHERE ".groupaccess('news_visibility')." AND (news_start='0' OR news_start<=".time().") AND (news_end='0' OR news_end>=".time().")
					".($fwhere==""?"":(" AND ".$fwhere))."
				ORDER BY frontpage_headline DESC, frontpage_order, news_datestamp DESC LIMIT $rowstart,".$settings['news_items']
			);		
		} else {
			$result = dbquery(
				"SELECT nf.*, tn.*, tc.*, user_id, user_name FROM ".$db_prefix."news tn
				LEFT JOIN ".$db_prefix."news_frontpage nf ON tn.news_id=nf.frontpage_news_id
				LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
				LEFT JOIN ".$db_prefix."news_cats tc ON tn.news_cat=tc.news_cat_id
				WHERE ".groupaccess('news_visibility')." AND (news_start='0' OR news_start<=".time().") AND (news_end='0' OR news_end>=".time().")
					".($nwhere==""?"":(" AND ".$nwhere))."
				ORDER BY frontpage_headline DESC, frontpage_order, news_datestamp DESC LIMIT $rowstart,".$settings['news_items']
			);		
		}
		// array to track news_id's to prevent duplicate items (wrong definition in admin/frontpage)
		$duplicates = array();
		// retrieve all the rows and store them in the template variable, one array per column
		$variables['news'] = array();
		$variables['_maxcols'] = $settings['news_columns'];
		$i = 0;
		$h = 0;
		$mc = false;
		while ($data = dbarray($result)) {
			// check if we have displayed this news item before
			if (in_array($data['news_id'], $duplicates)) {
				// already displayed, skip it
				continue;
			}
			// new item, store it for checks later
			$duplicates[] = $data['news_id'];
			if (!isset($variables['news'][$i])) $variables['news'][$i] = array();
			// increment the news headline counter
			if (!$data['frontpage_headline'] || $h == $settings['news_headline']) $i++;
			// if the maximum number of columns reached, roll it over
			if ($i > $variables['_maxcols']) { $i = 1; $mc = true; }
			// still counting headlines?
			if ($i == 0) $h++;
			// process the raw fields if needed
			$data['news_subject'] = stripslashes($data['news_subject']);
			$data['news_news'] = $data['news_breaks'] == "y" ? nl2br(stripslashes($data['news_news'])) : stripslashes($data['news_news']);
			$data['news_comments'] = dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'");
			$data['allow_comments'] = $data['news_allow_comments'];
			// and store it for use in the template
			$variables['news'][$i][] = $data;
		}
		// if not enough news items are available to fill all columns,
		// adjust max columns 
		if (!$mc && $i < $variables['_maxcols']) $variables['_maxcols'] = $i;
		// check if the user is allowed to edit
		$variables['allow_edit'] = checkrights("N");
		// parameters needed for page navigation
		$variables['rows'] = $rows;
		$variables['items_per_page'] = $settings['news_items'];
		$variables['rowstart'] = $rowstart;
		// define the body panel variables
		$template_panels[] = array('type' => 'body', 'name' => 'news', 'template' => 'main.news.tpl');
		$template_variables['news'] = $variables;
	} else {
		// no news items found
		$template_panels[] = array('type' => 'body', 'name' => 'message_panel', 'title' => $locale['029'], 'template' => '_message_table_panel.tpl');
		$variables['message'] = $locale['047'];
		$template_variables['message_panel'] = $variables;
	}
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
