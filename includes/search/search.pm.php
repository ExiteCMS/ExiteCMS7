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
if (eregi("search.forumposts.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// make sure we have an action variable
if (isset($action)) {

	if ($action == "") {

		// add the possible  search filters ($data is defined in the calling script!)
		$data['search_filters' ] = "date";

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
			$sql = "SELECT m.*, i.*, MATCH(m.pm_subject, m.pm_message) AGAINST ('$stext' IN BOOLEAN MODE) AS score
					FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i 
					WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."'
						AND MATCH(m.pm_subject, m.pm_message) AGAINST ('$stext' IN BOOLEAN MODE)";
		} else {
			$sql = "SELECT m.*, i.*, 1 AS score
					FROM ".$db_prefix."pm m, ".$db_prefix."pm_index i
					WHERE m.pm_id = i.pm_id AND i.pmindex_user_id = '".$userdata['user_id']."'";
			$stext = explode(" ", $stext);
			$searchstring = "";
			foreach($stext as $sstring) {
				if (!empty($sstring)) {
					$searchstring .= ($searchstring==""?"":(" ".$qtype))." (m.pm_subject LIKE '%".trim($sstring)."%' OR m.pm_message LIKE '%".trim($sstring)."%') ";
				}
			}
			if (!empty($searchstring)) {
				$searchstring = " AND (".$searchstring.")";
			}
			$sql .= $searchstring;
		}

		// add a datelimit if requested
		if ($datelimit) {
			$sql .= " AND pm_datestamp >= ".(time() - $datelimit);
		}

		// add the order field
		switch ($sortby) {
			case "score":
				$sql .= " ORDER BY score ".($order?"ASC":"DESC");
				break;
			case "author":
				$sql .= " ORDER BY i.pmindex_from_id ".($order?"ASC":"DESC");
				break;
			case "subject":
				$sql .= " ORDER BY m.pm_subject ".($order?"ASC":"DESC");
				break;
			case "datestamp":
				$sql .= " ORDER BY m.pm_datestamp ".($order?"ASC":"DESC");
				break;
			case "count":
				// not implemented for this search
				break;
		}

		// check how many rows this would output
		$rptresult = mysql_query($sql.($limit?" LIMIT $limit":""));
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
					$pmfolders = array($locale['src519'], $locale['src520'], $locale['src521']);
					while ($rptdata = dbarray($rptresult)) {
						$rptdata['folder'] = $pmfolders[$rptdata['pmindex_folder']];
						// get the information for the recipient(s)
						$rptdata['recipients'] = array();
						if ($rptdata['pmindex_folder'] == 0 || ($rptdata['pmindex_folder'] == 2 && $rptdata['pmindex_user_id'] != $rptdata['pmindex_from_id'])) {
							// incomming, get the sender info
							$result2 = dbquery("SELECT 0 as is_group, user_name AS name FROM ".$db_prefix."users WHERE user_id = '".$rptdata['pmindex_from_id']."'");
							if ($data2 = dbarray($result2)) {
								$rptdata['recipients'][] = $data2;
							}
						} elseif ($rptdata['pmindex_folder'] == 1 || ($rptdata['pmindex_folder'] == 2 && $rptdata['pmindex_user_id'] == $rptdata['pmindex_from_id'])) {
							// outgoing, get the recepient info
							$recipients = explode(",", $rptdata['pm_recipients']);
							foreach ($recipients as $recipient) {
								if ($recipient < 0) {
									// recipient is a user group
									$result2 = dbquery("SELECT 1 as is_group, group_id AS id, group_name AS name, group_visible AS visible FROM ".$db_prefix."user_groups WHERE group_id = '".abs($recipient)."'");
									if ($data2 = dbarray($result2)) {
										$data2['visible'] = $data2['visible'] & pow(2, 0);
										$rptdata['recipients'][] = $data2;
									}
								} else {
									// recipient is a single member
									$result2 = dbquery("SELECT 0 as is_group, user_id AS id, user_name AS name FROM ".$db_prefix."users WHERE user_id = '".$recipient."'");
									if ($data2 = dbarray($result2)) {
										$data2['visible'] = iMEMBER;
										$rptdata['recipients'][] = $data2;
									}
								}
							}
						}
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
