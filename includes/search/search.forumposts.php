<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
if (eregi("search.forumposts.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// array to store variables we want to use in the search template
$reportvars = array();

// make sure we have an action variable
if (isset($action)) {

	if ($action == "") {

		// add the possible  search filters ($data is defined in the calling script!)
		$data['search_filters' ] = "date,users,forums";

		// get the list of all members
		if (!isset($content_filters['forums'])) {
			$content_filters['forums'] = array();
			$content_filters['forums']['title'] = $locale['030'];
			$content_filters['forums']['field'] = "contentfilter_forums";
			$content_filters['forums']['values'] = array();
			$fresult = dbquery(
				"SELECT f.forum_id, f.forum_name, f2.forum_name AS forum_cat_name
				FROM ".$db_prefix."forums f
				INNER JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
				WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
			);
			while ($fdata = dbarray($fresult)) {
				$content_filters['forums']['values'][] = array('id' => $fdata['forum_id'], 'value' => $fdata['forum_name']);
			}
		}

		// get the list of all members
		if (!isset($content_filters['users'])) {
			$content_filters['users'] = array();
			$content_filters['users']['title'] = $locale['057'];
			$content_filters['users']['field'] = "contentfilter_users";
			$content_filters['users']['values'] = array();
			$fresult = dbquery("SELECT u.user_id, u.user_name FROM ".$db_prefix."users u WHERE user_status = 0 ORDER BY user_level DESC, user_name ASC");
			while ($fdata = dbarray($fresult)) {
				$content_filters['users']['values'][] = array('id' => $fdata['user_id'], 'value' => $fdata['user_name']);
			}
		}

	} else {

		// get the required variables (could be POST or GET vars!)
		if (isset($stext)) {
			$stext = stripinput($stext);
		} else {
			$stext = isset($_POST['stext']) ? stripinput($_POST['stext']) : "";
		}
		$stext = str_replace(',', ' ', $stext);
		$variables['stext'] = $stext;
		// note: qtype not used because of a fulltext query
		if (!isset($qtype)) {
			$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : "AND";
		}
		if ($qtype != "OR" && $qtype != "AND") {
			$qtype = "AND";
		}
		if (!isset($datelimit)) {
			$datelimit = isset($_POST['datelimit']) ? $_POST['datelimit'] : 0;
		}
		if (!isNum($datelimit)) {
			$datelimit = 0;
		}
		if (!isset($sortby)) {
			$sortby = isset($_POST['sortby']) ? $_POST['sortby'] : "score";
		}
		if (!in_array($sortby, $select_filters)) {
			$sortby = $select_filters[0];
		}
		if (!isset($order)) {
			$order = isset($_POST['order']) ? $_POST['order'] : 1;
		}
		if (!isNum($order)) {
			$order = 1;
		}
		if (!isset($limit)) {
			$limit = isset($_POST['limit']) ? $_POST['limit'] : 0;
		}
		if (!isNum($limit)) {
			$limit = 0;
		}
		$boolean = isset($_POST['boolean']) ? 0 : 1;

		if (!isset($sub_search_id)) $sub_search_id = 0;

		// basis of the query for this search
		if ($boolean) {
			switch ($sub_search_id) {
				case "1":
					$sql = "SELECT tp.*, tf.*, tu.user_id,user_name,
							MATCH(tp.post_subject) AGAINST ('$stext' IN BOOLEAN MODE) AS score
							FROM ".$db_prefix."posts tp
							INNER JOIN ".$db_prefix."forums tf USING(forum_id)
							INNER JOIN ".$db_prefix."users tu ON tp.post_author=tu.user_id
							WHERE ".groupaccess('forum_access')." AND MATCH(tp.post_subject) AGAINST ('$stext' IN BOOLEAN MODE)";
					break;
				case "2":
					// fall through to default
				default:
					$sql = "SELECT tp.*, tf.*, tu.user_id,user_name,
							MATCH(tp.post_subject, tp.post_message) AGAINST ('$stext' IN BOOLEAN MODE) AS score
							FROM ".$db_prefix."posts tp
							INNER JOIN ".$db_prefix."forums tf USING(forum_id)
							INNER JOIN ".$db_prefix."users tu ON tp.post_author=tu.user_id
							WHERE ".groupaccess('forum_access')." AND MATCH(tp.post_subject, tp.post_message) AGAINST ('$stext' IN BOOLEAN MODE)";
					break;
			}
		} else {
			$sql = "SELECT tp.*, tf.*, tu.user_id,user_name, 1 AS score
					FROM ".$db_prefix."posts tp
					INNER JOIN ".$db_prefix."forums tf USING(forum_id)
					INNER JOIN ".$db_prefix."users tu ON tp.post_author=tu.user_id
					WHERE ".groupaccess('forum_access');
			$stext = explode(" ", $stext);
			$searchstring = "";
			foreach($stext as $sstring) {
				if (!empty($sstring)) {
					switch ($sub_search_id) {
						case "1":
							$searchstring .= ($searchstring==""?"":(" ".$qtype))." post_subject LIKE '%".trim($sstring)."%' ";
							break;
						case "2":
							// fall through to default
						default:
							$searchstring .= ($searchstring==""?"":(" ".$qtype))." (post_subject LIKE '%".trim($sstring)."%' OR post_message LIKE '%".trim($sstring)."%') ";
							break;
					}
				}
			}
			if (!empty($searchstring)) {
				$searchstring = " AND (".$searchstring.")";
			}
			$sql .= $searchstring;
		}

		// construct the page navigator URL to allow paging
		$variables['pagenav_url'] = FUSION_SELF."?action=search&amp;search_id=".$search_id."&amp;";
		$variables['pagenav_url'] .= "stext=".$stext."&amp;";
		$variables['pagenav_url'] .= "boolean=".$boolean."&amp;";
		$variables['pagenav_url'] .= "datelimit=".$datelimit."&amp;";
		$variables['pagenav_url'] .= "sortby=".$sortby."&amp;";
		$variables['pagenav_url'] .= "order=".$order."&amp;";
		$variables['pagenav_url'] .= "limit=".$limit."&amp;";

		// add a datelimit if requested
		if ($datelimit) {
			$sql .= " AND article_datestamp >= ".(time() - $datelimit);
		}

		// add a forum filter if requested
		if (isset($_POST['contentfilter_forums']) && isNum($_POST['contentfilter_forums']) && $_POST['contentfilter_forums'] > 0 ) {
			$sql .= " AND tp.forum_id = '".$_POST['contentfilter_forums']."'";
		}
		// add an author if requested
		if (isset($_POST['contentfilter_users']) && isNum($_POST['contentfilter_users']) && $_POST['contentfilter_users'] > 0 ) {
			$sql .= " AND (tp.post_author = '".$_POST['contentfilter_users']."'"." OR tp.post_edituser = '".$_POST['contentfilter_users']."')";
		}

		// add the order field
		switch ($sortby) {
			case "score":
				$sql .= " ORDER BY score ".($order?"ASC":"DESC");
				break;
			case "author":
				$sql .= " ORDER BY tp.post_author ".($order?"ASC":"DESC");
				break;
			case "subject":
				$sql .= " ORDER BY post_subject ".($order?"ASC":"DESC");
				break;
			case "datestamp":
				$sql .= " ORDER BY post_datestamp ".($order?"ASC":"DESC");
				break;
			case "count":
				// not implemented for this search
				break;
		}

		// check if we have a rowstart value
		if (!isset($rowstart)) $rowstart = 0;
	
		// check how many rows this would output
		$rptresult = mysql_query($sql.($limit?" LIMIT $limit":""));
		$variables['rows'] = dbrows($rptresult);
		if ($variables['rows']) {
			$variables['rowstart'] = $rowstart;
			$variables['items_per_page'] = $settings['numofthreads'];

			// now add a query limit, make sure not to overshoot the limit requested
			if ($variables['rows']-$rowstart > $settings['numofthreads']) {
				$sql .= " LIMIT ".$rowstart.",".$settings['numofthreads'];
			} else {
				$sql .= " LIMIT ".$rowstart.",".($variables['rows']-$rowstart);
			}
			$rptresult = dbquery($sql);

			// get the results if any
			if ($variables['rows']) {
				$reportvars['output'] = array();
				while ($rptdata = dbarray($rptresult)) {
					$reportvars['output'][] = $rptdata;
				}

				// get the score divider for this result set
				$divider = 0;
				foreach($reportvars['output'] as $key => $value) {
					$divider = max($divider, $value['score']);
				}

				// calculate the relevance for this result set
				foreach($reportvars['output'] as $key => $value) {
					$reportvars['output'][$key]['relevance'] = $value['score'] / $divider * 100;
				}
			}
		}
	}
}
?>
