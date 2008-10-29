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
if (eregi("report.usercountries.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// array to store variables we want to use in the report template
$reportvars = array();

// make sure we have an action variable
if (isset($action)) {

	// check if we have a rowstart value
	if (!isset($rowstart)) $rowstart = 0;
	
	if ($action == "") {
		
			// pre-processing

	} else {

		// create the query for the report
		$sql = "SELECT user_cc_code, count(*) as count FROM ".$db_prefix."users";

		// get the required variables (could be POST or GET vars!)
		if (!isset($order)) {
			$order = isset($_POST['sortorder']) ? $_POST['sortorder'] : 0;
		}
		if (!isset($top)) {
			$top = isset($_POST['top']) ? $_POST['top'] : 0;
		}
		
		// add the group by clause for the count()
		$sql .= " GROUP BY user_cc_code";

		// construct the page navigator to allow paging
		$variables['pagenav_url'] = FUSION_SELF."?action=report&amp;report_id=".$report_id."&amp;";
		$variables['pagenav_url'] .= "top=".$top."&amp;";
		$variables['pagenav_url'] .= "order=".$order."&amp;";

		// only continue when there was no error
		if (!isset($variables['message'])) {
			// add the order field
			if ($order == 1) {
				$sql .= " ORDER BY count ASC";
			} else {
				$sql .= " ORDER BY count DESC";
			}

			// check how many rows this would output
			$rptresult = dbquery($sql.($top?" LIMIT $top":""));
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

					// include the GeoIP functions
					require_once PATH_INCLUDES."geoip_include.php";

					// load the months locale
					locale_load("months");

					// get the results
					$reportvars['output'] = array();
					while ($rptdata = dbarray($rptresult)) {
						$rptdata['_rownr'] = ++$rowstart;
						$rptdata['country'] = GeoIP_Code2Name($rptdata['user_cc_code']);
						if (empty($rptdata['country'])) {
							$rptdata['country'] = GeoIP_Code2Name("--");
						}
						$reportvars['output'][] = $rptdata;
					}
				} else {
					$variables['message'] = $locale['rpt950']." ".mysql_error();
				}
			}
		}
	}
}
?>
