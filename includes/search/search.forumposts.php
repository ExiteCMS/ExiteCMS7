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

		// make sure the sub search ID is defined
		if (!isset($sub_search_id)) $sub_search_id = 0;

		// retrieve the search criteria
		if (isset($_SESSION['search'])) {
			// from the session store (used when paging through the results)
			$stext = $_SESSION['search']['stext'];
			$qtype = $_SESSION['search']['qtype'];
			$datelimit = $_SESSION['search']['datelimit'];
			$boolean = $_SESSION['search']['boolean'];
			$sortby = $_SESSION['search']['sortby'];
			$order = $_SESSION['search']['order'];
			$limit = $_SESSION['search']['limit'];
			$contentfilter_forums = $_SESSION['search']['contentfilter_forums'];
			$contentfilter_users = $_SESSION['search']['contentfilter_users'];
		} else {
			// from the search form
			$stext = isset($_POST['stext']) ? stripinput($_POST['stext']) : "";
			$stext = str_replace(',', ' ', $stext);
			$boolean = isset($_POST['boolean']) ? 0 : 1;
			$qtype = isset($_POST['qtype']) ? stripinput($_POST['qtype']) : "AND";
			if ($qtype != "OR" && $qtype != "AND") {
				$qtype = "AND";
			}
			$sortby = isset($_POST['sortby']) ? stripinput($_POST['sortby']) : "score";
			if (!in_array($sortby, $select_filters)) {
				$sortby = $select_filters[0];
			}
			$order = isset($_POST['order']) && isNum($_POST['order']) ? $_POST['order'] : 1;
			$limit = isset($_POST['limit']) && isNum($_POST['limit']) ? $_POST['limit'] : 0;
			$datelimit = isset($_POST['datelimit']) && isNum($_POST['datelimit']) ? $_POST['datelimit'] : 0;
			// add a forum filter if requested
			if (isset($_POST['contentfilter_forums']) && isNum($_POST['contentfilter_forums']) && $_POST['contentfilter_forums'] > 0 ) {
				$contentfilter_forums =  stripinput($_POST['contentfilter_forums']);
			}
			// add an author if requested
			if (isset($_POST['contentfilter_users']) && isNum($_POST['contentfilter_users']) && $_POST['contentfilter_users'] > 0 ) {
				$contentfilter_users = stripinput($_POST['contentfilter_users']);
			}
		}
		$variables['stext'] = $stext;

		// store the search parameters in the session record
		$_SESSION['search'] = array('stext' => $stext,
									'qtype' => $qtype,
									'datelimit' => $datelimit,
									'boolean' => $boolean,
									'sortby' => $sortby,
									'order' => $order,
									'limit' => $limit,
									'contentfilter_forums' => $contentfilter_forums,
									'contentfilter_users' => $contentfilter_users
								);

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

		// add a datelimit if requested
		if ($datelimit) {
			$sql .= " AND tp.post_datestamp >= ".(time() - $datelimit);
		}

		// add a forum filter if requested
		if (!empty($contentfilter_forums)) {
			$sql .= " AND tp.forum_id = '".$contentfilter_forums."'";
		}

		// add an author if requested
		if (!empty($contentfilter_users)) {
			$sql .= " AND (tp.post_author = '".$contentfilter_users."'"." OR tp.post_edituser = '".$contentfilter_users."')";
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
				$sql .= " ORDER BY tp.post_subject ".($order?"ASC":"DESC");
				break;
			case "datestamp":
				$sql .= " ORDER BY tp.post_datestamp ".($order?"ASC":"DESC");
				break;
			case "count":
				// not implemented for this search
				break;
		}

		// check how many rows this would output
		$rptresult = dbquery($sql.($limit?" LIMIT $limit":""));
		$rows = dbrows($rptresult);

		// are there any results?
		if ($rows) {

			// are we interested in these results?
			if ($lines < $settings['numofthreads'] && $rowstart < $variables['rows'] + $rows) {

				// add a query limit, we might not need all records
				$sql .= " LIMIT ".(max($rowstart-$variables['rows'],0)).",".min($rows,($settings['numofthreads']-$lines));

				// launch the query
				$rptresult = dbquery($sql);

				// get the results if any
				if ($rptresult) {
					while ($rptdata = dbarray($rptresult)) {
						$rptdata['_template'] = $data['template'];
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

			// add the amount of rows found to the total rows counter
			$variables['rows'] += $rows;

		}

	}
}
?>
