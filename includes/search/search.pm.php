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

// array to store variables we want to use in the search template
$reportvars = array();

// make sure we have an action variable
if (isset($action)) {

	if ($action == "") {

		// add the possible  search filters ($data is defined in the calling script!)
		$data['search_filters' ] = "date";

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
		if (!isset($boolean)) {
			$boolean = isset($_POST['boolean']) ? 0 : 1;
		}

		if (!isset($sub_search_id)) $sub_search_id = 0;

		// construct the page navigator URL to allow paging
		$variables['pagenav_url'] = FUSION_SELF."?action=search&amp;search_id=".$search_id."&amp;";
		$variables['pagenav_url'] .= "stext=".urlencode($stext)."&amp;";
		$variables['pagenav_url'] .= "boolean=".$boolean."&amp;";
		$variables['pagenav_url'] .= "datelimit=".$datelimit."&amp;";
		$variables['pagenav_url'] .= "sortby=".$sortby."&amp;";
		$variables['pagenav_url'] .= "order=".$order."&amp;";
		$variables['pagenav_url'] .= "limit=".$limit."&amp;";

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
				$pmfolders = array($locale['src519'], $locale['src520'], $locale['src521']);
				$reportvars['output'] = array();
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
//			_debug($reportvars, true);
		}
	}
}
?>
