<?php
/*---------------------------------------------------+
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

/*---------------------------------------------------+
| function to generate the array of site_links       |
| records, with all information to create a menu     |
| with submenu's.                                    |
|                                                    |
| required globals:                                  |
| $db_prefix - database table prefix                 |
| $linkinfo[] - array to store the site_links        |
|                                                    |
| Returned contents of the $linkinfo array:          |
| [panel_name] = name of the menu panel              |
| [menu_first] = true only if this is the first item |
| [menu_last] = true only if this is the last item   |
| [menu_depth] = depth, 0 = main menu                |
| [menu_state] = 0=open, 1=closed, -1=N/A            |
| [div_state] = 0=open, 1=closed, -1=N/A             |
| [external] = 1 if the URL is absolute, 0 = relative|
| + the entire contents of the site_links record!    |
|                                                    |
| parameters:                                        |
| $panel - name of the menu panel for which the menu |
|          is generated. default = 'main_menu_panel' |
|          leave empty to get items from all panels  |
| $position - array of possible link_position        |
|           values to include in the result.         |
|           default = (1,2) for all menu panel items |
| $parent - link_parent of the root of the menu      |
|           default = 0, which generates the         |
|           structure of a complete menu, or a       |
|           link_id of a parent_link for a submenu   |
|           if parent === false, the parent value is |
|           ignored in the menu item selection       |
| $depth - indicates the menu depth.                 |
|          default = 0, which is usually what you use|
|          recursive calls will increase the depth   |
|          to build the tree                         |
| $no_sec - indicates whether security on a link     |
|          should be checked. If true, the check is  |
|          skipped, and all entries are returned     |
| $no_loc - indicates whether locale information     |
|          shouldbe checked. If true, the check is   |
|          skipped, and all entries are returned     |
+----------------------------------------------------*/
function menu_generate_tree($panel='main_menu_panel', $position=array(1,2), $parent=0, $depth=0, $no_sec=false, $no_loc=false) {

	global $settings, $db_prefix, $linkinfo, $aidlink;

	// make sure the linkinfo array exists
	if (!is_array($linkinfo)) $linkinfo = array();

	// validate the parameters
	if ((!isNum($parent) && !is_bool($parent)) || !isNum($depth) || !is_string($panel) || !is_array($position) || !is_bool($no_sec)) return false;

	$where = "";

	// build the menu panel selection
	$where .= ($panel != "" ? ($where == "" ? "" : " AND ")."panel_name = '".$panel."' " : "");
	if (!$no_loc && $settings['sitelinks_localisation'] == "multiple") {
		$where .= ($where == "" ? "" : " AND ")."link_locale = '".$settings['locale_code']."' ";
	}

	// build the parent link selection
	$where .= ($parent !== false ? ($where == "" ? "" : " AND ")."link_parent = '".$parent."' " : "");

	// build the position selection
	$pos_where = "";
	foreach ($position as $pos) {
		// make sure the position given is numeric
		if (isNum($pos)) {
			$pos_where .= ($pos_where == "" ? "link_position IN (" : ",") . $pos;
		}
	}
	$where .= ($pos_where != "" ? ($where == "" ? "" : " AND ").$pos_where.") " : "");

	// create the WHERE clause
	$where = ($where == "" ? "" : "WHERE ".$where);

	// get all menu records for this panel and this parent
	$result = dbquery("SELECT * FROM ".$db_prefix."site_links ".$where." ORDER BY link_parent ASC, link_order ASC");
	// process the results
	$current = $start = count($linkinfo);
	while($data = dbarray($result)) {
		// only include records that the user is allowed to see (unless showall is specified)
		if ($no_sec || checkgroup($data['link_visibility'])) {
			// true if this is the first menu item
			$data['menu_first'] = $current == $start ? 1 : 0;
			// for the first menu item, check if there's a menu_state cookie stored for this menu
			$data['div_state'] = -1;
			if ($data['menu_first'] && isset($_COOKIE['box_menu'.$parent])) {
				$data['div_state'] = $_COOKIE['box_menu'.$parent];
			}
			$data['menu_state'] = -1;
			if (isset($_COOKIE['box_menu'.$data['link_id']])) {
				$data['menu_state'] = $_COOKIE['box_menu'.$data['link_id']];
			}
			// also check it for the menu entry itself
			// assume this is not last menu item
			$data['menu_last'] = 0;
			// depth of this item in the menu (0 = main menu, > 0 = submenu)
			$data['menu_depth'] = $depth;
			// true if the link points to an absolute URL
			$data['external'] = (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")  ? 1 : 0);
			// convert a relative URL to an absolute URL if needed
			if ($data['link_url'] != '---') {
				$data['link_url'] = (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")  ? $data['link_url'] : BASEDIR.$data['link_url']);
			}
			// check if we need to add an aidlink to the url
			if (iADMIN && $data['link_aid'] && isset($aidlink)) {
				$data['link_url'] .= (strpos($data['link_url'], '?') ? ("&amp;".substr($aidlink,1)) : $aidlink);
			}
			// get the name of the user group attached to this link
			$data['link_visibility_name'] = getgroupname($data['link_visibility'], '-1');
			$data['has_submenu'] = false;
			$linkinfo[$current] = $data;
			$current++;
			// if this is a potential sub menu link, recurse
			$submenu = ($data['link_name'] != '---' && $data['link_url'] == '---');
			if ($submenu) {
				$submenu_link = count($linkinfo);
				$linkinfo[$submenu_link-1]['has_submenu'] = menu_generate_tree($panel, $position, $data['link_id'], $depth+1, $no_sec);
			}
		}
	}

	// any entries found?
	if ($current != $start) {
		// set the flag of the last menu entry
		$linkinfo[$current-1]['menu_last'] = 1;
		return true;
	} else {
		return false;
	}
}
?>
