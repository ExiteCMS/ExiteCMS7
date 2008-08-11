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
if (eregi("search.downloads.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// array to store variables we want to use in the search template
$reportvars = array();

// make sure we have an action variable
if (isset($action)) {

	if ($action == "") {
		
			// no pre-processing required for this search

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

		// basis of the query for this search
		if ($boolean) {
			$sql = "SELECT td.*,tdc.*, 
					MATCH (download_title, download_description) AGAINST ('$stext' IN BOOLEAN MODE) AS score
					FROM ".$db_prefix."downloads td
					INNER JOIN ".$db_prefix."download_cats tdc ON td.download_cat=tdc.download_cat_id
					WHERE ".groupaccess('download_cat_access')." AND MATCH (download_title, download_description) AGAINST ('$stext' IN BOOLEAN MODE)";
		} else {
			$sql = "SELECT td.*,tdc.*, 1 AS score
					FROM ".$db_prefix."downloads td
					INNER JOIN ".$db_prefix."download_cats tdc ON td.download_cat=tdc.download_cat_id
					WHERE ".groupaccess('download_cat_access');
			$stext = explode(" ", $stext);
			$searchstring = "";
			foreach($stext as $sstring) {
				if (!empty($sstring)) {
					$searchstring .= ($searchstring==""?"":(" ".$qtype))." (download_title LIKE '%".trim($sstring)."%' OR download_description LIKE '%".trim($sstring)."%') ";
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
			$sql .= " AND download_datestamp >= ".(time() - $datelimit);
		}

		// add the order field
		switch ($sortby) {
			case "score":
				$sql .= " ORDER BY score ".($order?"ASC":"DESC");
				break;
			case "author":
				// not implemented for this search
				break;
			case "subject":
				$sql .= " ORDER BY download_title ".($order?"ASC":"DESC");
				break;
			case "datestamp":
				$sql .= " ORDER BY download_datestamp ".($order?"ASC":"DESC");
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
			if ($limit > 0) {
				if ($variables['rows']-$rowstart > $settings['numofthreads']) {
					$sql .= " LIMIT ".$rowstart.",".$settings['numofthreads'];
				} else {
					$sql .= " LIMIT ".$rowstart.",".($variables['rows']-$rowstart);
				}
			}
			$rptresult = dbquery($sql);

			// get the results if any
			if ($rptresult) {
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
