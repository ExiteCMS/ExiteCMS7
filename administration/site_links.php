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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.LOCALESET."admin/sitelinks.php";

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("SL") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid
if (isset($link_id) && !isNum($link_id)) fallback(FUSION_SELF.$aidlink);
if (!isset($link_id)) $link_id = 0;
if (!isset($action)) $action = "";

/*---------------------------------------------------+
| Local functions                                    |
+----------------------------------------------------*/

// reorder the links of a menu panel
function reordermenu($panel) {

	global $db_prefix;
	
	// renumber all menu items, to get the order back in sequence
	$result = dbquery("SELECT DISTINCT link_parent FROM ".$db_prefix."site_links WHERE panel_name = '$panel' ORDER BY link_order");
	$parents = array();
	while ($data = dbarray($result)) {
		$parents[] = $data['link_parent'];
	}
	foreach($parents as $parent) {
		$i = 1;
		$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE panel_name = '$panel' AND link_parent = '$parent' ORDER BY link_order");
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".$db_prefix."site_links SET link_order='$i' WHERE link_id='".$data['link_id']."'");
			$i++;
		}
	}
}

// remove a parent record, and all it's children, from the parents list
function removefromparents($parent) {

	global $db_prefix, $variables;

	// check if this parent has any submenu links
	$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_parent='$parent' AND link_url = '---'");
	while ($data = dbarray($result)) {
		// if so, remove it
		removefromparents($data['link_id']);
	}
	// find this parent id in the parents list, and remove it
	foreach($variables['parents'] as $index1 => $menu_parents) {
		foreach($variables['parents'][$index1]['parent_ids'] as $index2 => $parents) {
		if ($parents['link_id'] == $parent) {
				unset($variables['parents'][$index1]['parent_ids'][$index2]);
				return;
			}
		}
	}
}

// generate the menu tree array for the given menu panel
function buildmenutree($parent, $depth, $panel) {

	global $db_prefix, $links;

	// make sure the links array exists
	if (!is_array($links)) $links = array();	

	// get all menu records for this panel and this parent
	$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE panel_name = '".$panel."' AND link_parent = '$parent' ORDER BY link_order");
	// process the results
	$total = dbrows($result);
	$current = 1;
	while($data = dbarray($result)) {
		$data['menu_first'] = $current == 1 ? 1 : 0;
		$data['menu_last'] = $current == $total ? 1 : 0;
		$data['menu_depth'] = $depth;
		$data['external'] = $data['link_window'] == 1;
		$data['link_visibility_name'] = getgroupname($data['link_visibility'], '-1');
		$current++;
		$links[] = $data;
		$submenu_link = count($links);
		// if this is a potential sub menu link, recurse
		$links[$submenu_link-1]['has_submenu'] = ($data['link_url'] == '---' ? buildmenutree($data['link_id'], $depth+1, $panel) : 0);
	}
	return ($total == 0 ? 0 : 1);
}

/*---------------------------------------------------+
| Main                                               |
+----------------------------------------------------*/

// generate the list of installed menu panels
$panel_list = array();
$temp = opendir(PATH_MODULES);
while ($folder = readdir($temp)) {
	if (!in_array($folder, array(".","..")) && strstr($folder, "_panel")) {
		if (is_dir(PATH_MODULES.$folder)) 
		    if (strpos($folder, '_menu_panel') !== false) $panel_list[] = $folder;
	}
}
closedir($temp); sort($panel_list); 
$variables['panel_list'] = $panel_list;

// display a status panel
if (isset($status)) {
	if ($status == "del") {
		$variables['message'] = $locale['401'];
	} else {
		$variables['message'] = "UNKNOWN STATUS PASSED!";
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.site_links.status', 'title' => $locale['400'], 'template' => '_message_table_panel.tpl');
	$template_variables['admin.site_links.status'] = $variables;
	$variables = array();
}

// default panel title
$title = $locale['411'];

if ($action == "refresh") {

	reordermenu($panel);
	redirect(FUSION_SELF.$aidlink);

} elseif ($action == "move") {

	// get the link order of the two records
	$data1 = dbarray(dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_id='$swap'"));
	$data2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_id='$with'"));
	if (is_array($data1) && is_array($data2)) {
		// swap the two link_orders
//		echo "UPDATE ".$db_prefix."site_links SET link_order='".$data2['link_order']."' WHERE link_id='".$data1['link_id']."'<br>";
//		die("UPDATE ".$db_prefix."site_links SET link_order='".$data1['link_order']."' WHERE link_id='".$data2['link_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order='".$data2['link_order']."' WHERE link_id='".$data1['link_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order='".$data1['link_order']."' WHERE link_id='".$data2['link_id']."'");
	}

} elseif ($action == "delete") {

	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."site_links WHERE panel_name = '$panel' AND link_id='$link_id'"));
	$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order-1 WHERE panel_name = '$panel' AND link_order>'".$data['link_order']."'");
	$result = dbquery("DELETE FROM ".$db_prefix."site_links WHERE panel_name = '$panel' AND link_id='$link_id'");
	redirect(FUSION_SELF.$aidlink."&status=del");

} else {

	if (isset($_POST['savelink'])) {
		$link_name = stripinput($_POST['link_name']);
		$link_url = stripinput($_POST['link_url']);
		$link_visibility = isNum($_POST['link_visibility']) ? $_POST['link_visibility'] : "0";
		$link_position = isset($_POST['link_position']) ? $_POST['link_position'] : "0";
		$link_window = isset($_POST['link_window']) ? $_POST['link_window'] : "0";
		$link_order = isset($_POST['link_order']) ? $_POST['link_order'] : "0";
		$link_aid = isset($_POST['link_aid']) ? $_POST['link_aid'] : "0";
		$panel_filename = isset($_POST['panel_filename']) ? $_POST['panel_filename'] : "";
		$link_parent = isset($_POST['link_parent']) ? $_POST['link_parent'] : "0";
		// if a protocol is specified in the URL, force it to open in a new window
		if (strstr($link_url, "://")) {
			$link_window = 1;
		}
		if ($action == "edit") {
			$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_id='$link_id'"));
			// if the panel name has changed, move all submenu of which this link is the parent too...
			if ($data['panel_name'] != $panel_filename) {
				// build the menu tree for this panel
				$links = array();
				buildmenutree($data['link_id'], 0, $data['panel_name']);
				foreach($links as $link) {
					$result = dbquery("UPDATE ".$db_prefix."site_links SET panel_name='$panel_filename' WHERE link_id='".$link['link_id']."'");
				}
				reordermenu($panel_filename);
			}
			// if the parent has changed, calculate a new link order
			if ($data['link_parent'] != $link_parent) {
				$link_order = dbresult(dbquery("SELECT MAX(link_order) FROM ".$db_prefix."site_links WHERE panel_name = '$panel_filename' AND link_parent='$link_parent'"),0)+1;
			} else {
				// link changed?
				if ($link_order != $data['link_order']) {
					$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order+1 WHERE panel_name = '$panel_filename' AND link_parent='$link_parent' AND link_order >= '$link_order'");
				}
			}
			// and update the link itself as well...
			$result = dbquery("UPDATE ".$db_prefix."site_links SET link_name='$link_name', link_url='$link_url', panel_name='$panel_filename', link_visibility='$link_visibility', link_position='$link_position', link_parent='$link_parent', link_window='$link_window', link_aid='$link_aid', link_order='$link_order' WHERE link_id='$link_id'");
			redirect(FUSION_SELF.$aidlink);
		} else {
			// get a linkorder if none given
			if (!$link_order) {
				$link_order = dbresult(dbquery("SELECT MAX(link_order) FROM ".$db_prefix."site_links WHERE panel_name = '$panel_filename' AND link_parent='$link_parent'"),0)+1;
			}
			$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order+1 WHERE panel_name = '$panel_filename' AND link_parent='$link_parent' AND link_order >= '$link_order'");	
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, panel_name, link_visibility, link_position, link_parent, link_window, link_aid, link_order) VALUES ('$link_name', '$link_url', '$panel_filename', '$link_visibility', '$link_position', '$link_parent', '$link_window', '$link_aid', '$link_order')");
			redirect(FUSION_SELF.$aidlink);
		}
	}

}	

// create the list of available menu panels, and possible submenu records to be used as parent's
$variables['parents'] = array();
$result = dbquery("SELECT DISTINCT panel_name from ".$db_prefix."site_links");
while ($data = dbarray($result)) {
	$parents = array('panel' => $data['panel_name'], 'parent_ids' => array());
	$result2 = dbquery("SELECT link_id, link_name from ".$db_prefix."site_links WHERE panel_name = '".$data['panel_name']."' AND link_url = '---'");
	while ($data2 = dbarray($result2)) {
		$parents['parent_ids'][] = $data2;
	}
	$variables['parents'][] = $parents;
}

if ($action == "edit") {
	$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_id='$link_id'");
	$data = dbarray($result);
	$link_name = $data['link_name'];
	$link_url = $data['link_url'];
	// if this is a possible submenu, this link and all sublinks from the parents list (to avoid recursion)
	if ($link_url == "---") {
		removefromparents($data['link_id']);
	}
	$link_visibility = $data['link_visibility'];
	$link_order = $data['link_order'];
	$panel_filename = $data['panel_name'];
	$link_parent = $data['link_parent'];
	$link_aid = $data['link_aid'];
	$link_window = $data['link_window'];
	$link_position = $data['link_position'];
	$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;link_id=".$data['link_id'];
	$title = $locale['410'];
} else {
	$link_name = "";
	$link_url = "";
	$link_visibility = "";
	$link_order = "";
	$panel_filename = isset($variables['panel_list'][0]) ? $variables['panel_list'][0] : "";
	$link_parent = 0;
	$link_window = 0;
	$link_aid = 0;
	$link_position = 1;
	$formaction = FUSION_SELF.$aidlink;
}

$variables['formaction'] = $formaction;
$variables['action'] = $action;
$variables['link_name'] = $link_name;
$variables['link_url'] = $link_url;
$variables['link_order'] = $link_order;
$variables['link_position'] = $link_position;
$variables['link_parent'] = $link_parent;
$variables['link_window'] = $link_window;
$variables['link_aid'] = $link_aid;
$variables['panel_name'] = $panel_filename;

// create the list of available groups for the visibility dropdown
$user_groups = getusergroups();
$variables['user_groups'] = array();
while(list($key, $user_group) = each($user_groups)){
	$variables['user_groups'][] = array('id' => $user_group['0'], 'name' => $user_group['1'], 'selected' => (isset($link_visibility) && $link_visibility == $user_group['0']));
}


// build the menu tree for each panel, and add it to the panels array
$variables['panels'] = array();

// loop through the panels installed
foreach ($panel_list as $panel) {
	
	// skip empty panel names (happens sometimes ?!)
	if (empty($panel)) continue;

	// build the menu tree for this panel
	$links = array();
	buildmenutree(0, 0, $panel);

	// keep track of the previous pointers per parent
	$previous = array();
	// loop through the tree to add up- and down pointers
	foreach($links as $index => $link) {
		// if not the first menu item, find the previous one
		if ($link['menu_first'] == 0) {
			// check if we have a previous pointer
			if (isset($previous[$link['link_parent']])) {
				$links[$index]['up'] = $previous[$link['link_parent']];
			}
		}
		// update the previous pointer tracker
		$previous[$link['link_parent']] = $link['link_id'];
		// if not the first menu item, find the next one
		if ($link['menu_last'] == 0) {
			for ($i = 1; true; $i ++) {
				if (!isset($links[$index+$i])) break;
				if ($links[$index+$i]['link_parent'] == $link['link_parent']) {
					$links[$index]['down'] = $links[$index+$i]['link_id'];
					break;
				}
			}
		}
		// check if we need to add an aidlink to the link URL
		if ($link['link_aid'] and isset($aidlink)) {
			$links[$index]['link_url'] .= (strpos($links[$index]['link_url'], '?') ? ("&amp;aid=".iAUTH) : $aidlink);
		}
	}

	// and create an entry in the panels array for this menu panel
	$variables['panels'][] = array('panel' => $panel, 'panel_count' => count($links), 'links' => $links, 'title' => $locale['412'].": <b>".ucwords(str_replace('_', ' ', $panel))."</b>");
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.site_links', 'title' => $title, 'template' => 'admin.site_links.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/sitelinks.php");
$template_variables['admin.site_links'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>