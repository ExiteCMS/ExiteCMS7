<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {makepagenav} function plugin
 *
 * Type:     function<br>
 * Name:     makepagenav<br>
 * Purpose:  generates html for page navigation, using a theme template
 * Input:<br>
 *         - start: first page to show
 *         - count: number of items on a page
 *         - total: total number of items
 *         - range: (optional) number of page links to show 
 *         - link: (optional) page navigation link
 * @author WanWizard <wanwizard at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_makepagenav($params, &$smarty)
{
	global $locale;

	// parameter validation and initialisation
    if (!isset($params['start'])) {
        $smarty->trigger_error("makepagenav: missing 'start' parameter");
	} else {
		if (!isNum($params['start'])) $smarty->trigger_error("makepagenav: 'start' parameter is not numeric");
		$start = $params['start'];
	}
    if (!isset($params['count'])) {
        $smarty->trigger_error("makepagenav: missing 'count' parameter");
	} else {
		if (!isNum($params['count'])) $smarty->trigger_error("makepagenav: 'count' parameter is not numeric");
		$count = $params['count'];
	}
    if (!isset($params['total'])) {
        $smarty->trigger_error("makepagenav: missing 'total' parameter");
	} else {
		if (!isNum($params['total'])) $smarty->trigger_error("makepagenav: 'total' parameter is not numeric");
		$total = $params['total'];
	}
    if (!isset($params['range'])) {
        $range = 3;
	} else {
		if (!isNum($params['range'])) $smarty->trigger_error("makepagenav: 'range' parameter is not numeric");
		$range = $params['range'];
	}
    if (!isset($params['link'])) {
		$link = FUSION_SELF."?";
	} else {
		$link = str_replace('+', '%2B', $params['link']);
	}

	// page navigation calculation

	$pg_cnt=ceil($total / $count);

	$pages = array();
	if ($pg_cnt > 1) {
		$idx_back = $start - $count;
		$idx_next = $start + $count;
		$cur_page=ceil(($start + 1) / $count);
		$idx_fst=max($cur_page - $range, 1);
		$idx_lst=min($cur_page + $range, $pg_cnt);
		if ($range==0) {
			$idx_fst = 1;
			$idx_lst=$pg_cnt;
		}
		for($i=$idx_fst;$i<=$idx_lst;$i++) {
			$offset_page = ($i - 1) * $count;
			$pages[] = array('current' => ($i==$cur_page), 'offset' => $offset_page, 'count' => $i);
		}
	}
	$smarty->assign('mpn_pages', $pages);
	$smarty->assign('mpn_link', $link);
	$smarty->assign('mpn_pg_cnt', $pg_cnt);
	$smarty->assign('mpn_cur_page', $cur_page);
	$smarty->assign('mpn_idx_back', $idx_back);
	$smarty->assign('mpn_is_back', $idx_back >= 0);
	$smarty->assign('mpn_is_farback', $cur_page > ($range + 1));
	$smarty->assign('mpn_is_fwd', $idx_next < $total);
	$smarty->assign('mpn_is_farfwd', $cur_page < ($pg_cnt - $range));
	$smarty->assign('mpn_idx_next', $idx_next);
	$smarty->assign('mpn_last_row', ($pg_cnt - 1) * $count);

	$smarty->display('_make_page_navigation.tpl');
	
	return null;
}
?>