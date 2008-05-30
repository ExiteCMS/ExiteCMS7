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
locale_load("admin.ranking");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("FR") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameter is valid
if (isset($rank_id) && !isNum($rank_id)) fallback(FUSION_SELF.$aidlink);
if (!isset($rank_id)) $rank_id = 0;
if (!isset($action)) $action = "";

// variable initialisation
$page_reload = false;

// save requested?
if (isset($_POST['save'])) {
	// validate the input
	$variables['rank_id'] = IsNum($_POST['rank_id'])?$_POST['rank_id']:0;
	$variables['rank_order'] = IsNum($_POST['rank_order'])?$_POST['rank_order']:0;
	$variables['rank_order_old'] = IsNum($_POST['rank_order_old'])?$_POST['rank_order_old']:0;
	$variables['rank_posts_from'] = $_POST['rank_posts_from'];
	$variables['rank_posts_to'] = $_POST['rank_posts_to'];
	$variables['rank_title'] = stripinput($_POST['rank_title']);
	$variables['rank_color'] = stripinput($_POST['rank_color']);
	$variables['rank_tooltip'] = IsNum($_POST['rank_tooltip'])?$_POST['rank_tooltip']:0;
	$variables['rank_image'] = stripinput($_POST['rank_image']);
	$variables['rank_image_repeat'] = IsNum($_POST['rank_image_repeat'])?$_POST['rank_image_repeat']:0;
	$variables['rank_groups'] = $_POST['rank_groups'];
	$variables['rank_groups_and'] = IsNum($_POST['rank_groups_and'])?$_POST['rank_groups_and']:0;

	if (empty($_POST['rank_posts_from']) || empty($_POST['rank_posts_to']) || !isNum($_POST['rank_posts_from']) || !isNum($_POST['rank_posts_to'])) {
		// define the message panel variables
		$variables['bold'] = true;
		$variables['message'] = $locale['490'];
		$template_panels[] = array('type' => 'body', 'name' => 'admin.ranking.error', 'title' => $locale['400'], 'template' => '_message_table_panel.tpl', 'locale' => "admin.ranking");
		$template_variables['admin.ranking.error'] = $variables;
		$page_reload = true;
	} else {
		if ($_POST['rank_posts_from'] >= $_POST['rank_posts_to']) {
			// define the message panel variables
			$variables['bold'] = true;
			$variables['message'] = $locale['491'];
			$template_panels[] = array('type' => 'body', 'name' => 'admin.ranking.error', 'title' => $locale['400'], 'template' => '_message_table_panel.tpl', 'locale' => "admin.ranking");
			$template_variables['admin.ranking.error'] = $variables;
			$page_reload = true;
		} else {
			// get the max order
			$data = dbarray(dbquery("SELECT MAX(rank_order) as rank_order FROM ".$db_prefix."forum_ranking"));
			$reorder = false;
			if ($variables['rank_id']) {
				// if the order has been changed
				if ($variables['rank_order'] != $variables['rank_order_old']) {
					if ($variables['rank_order'] > $data['rank_order'] + 1) {
						// given order was out of range, add it to the end
						$variables['rank_order'] = $data['rank_order'] + 1;
					} else {
						// move to make room for this new record
						$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = rank_order + 1 WHERE rank_order >= '".$variables['rank_order']."'");
					}
					$reorder = true;
				}
				// update the record
				$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = '".$variables['rank_order']."', rank_posts_from = '".$variables['rank_posts_from']."', rank_posts_to = '".$variables['rank_posts_to']."', rank_title = '".$variables['rank_title']."', rank_color= '".$variables['rank_color']."', rank_tooltip = '".$variables['rank_tooltip']."', rank_image = '".$variables['rank_image']."', rank_image_repeat = '".$variables['rank_image_repeat']."', rank_groups = '".$variables['rank_groups']."', rank_groups_and = '".$variables['rank_groups_and']."' WHERE rank_id = '".$rank_id."'");
				// reorder if needed
				if ($reorder) {
					$result = dbquery("SELECT * FROM ".$db_prefix."forum_ranking ORDER BY rank_order");
					$i = 1;
					while ($data = dbarray($result)) {
						$result2 = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = '".$i++."'WHERE rank_id = '".$data['rank_id']."'");
					}
				}
				// return to the list
				$action = "";
			} else {
				// check the order
				if ($variables['rank_order']) {
					// move (if needed) to make room for this new record
					$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = rank_order + 1 WHERE rank_order >= '".$variables['rank_order']."'");
					if (dbrows($result) == 0) {
						// given order was out of range, add it to the end
						$variables['rank_order'] = $data['rank_order'] + 1;
					}
				} else {
					// not given, add the new ranking to the end
					$variables['rank_order'] = $data['rank_order'] + 1;
				}
				// insert the new record
				$result = dbquery("INSERT INTO ".$db_prefix."forum_ranking (rank_order, rank_posts_from, rank_posts_to, rank_title, rank_color, rank_tooltip, rank_image, rank_image_repeat, rank_groups, rank_groups_and) VALUES ('".$variables['rank_order']."', '".$variables['rank_posts_from']."', '".$variables['rank_posts_to']."', '".$variables['rank_title']."', '".$variables['rank_color']."', '".$variables['rank_tooltip']."', '".$variables['rank_image']."', '".$variables['rank_image_repeat']."', '".$variables['rank_groups']."', '".$variables['rank_groups_and']."')");
			}
			// and return to the ranking list panel
			$action = "";
		}
	}
}

// process the add and edit actions
if ($action == "add" || $action == "edit") {

	if (!$page_reload && $action == "edit") {
		// get the requested record and fill the form variables
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_ranking WHERE rank_id = '".$rank_id."'");
		if ($data = dbarray($result)) {
			$variables = array_merge($variables, $data);
		} else {
			// id not found, return to the list
			$action = "";
		}
	} elseif (!$page_reload && $action == "add") {
		// initialise the form variables
		$variables['rank_id'] = 0;
		$variables['rank_order'] = "";
		$variables['rank_posts_from'] = "";
		$variables['rank_posts_to'] = "";
		$variables['rank_title'] = "";
		$variables['rank_color'] = "";
		$variables['rank_tooltip'] = 0;
		$variables['rank_image'] = "";
		$variables['rank_image_repeat'] = "";
		$variables['rank_groups'] = "";
		$variables['rank_groups_and'] = 0;
	}

	// create the group selection arrays
	$fp_group_array = array(
		array(103, $locale['user3']),
		array(102, $locale['user2']),
		array(101, $locale['user1'])
	);
	$result = dbquery("SELECT * FROM ".$db_prefix."user_groups ORDER BY group_name");
	if (dbrows($result) != 0) {
		while ($fp_group_data = dbarray($result)) {
			$fp_group_array[] = array($fp_group_data['group_id'], $fp_group_data['group_name']);
		}
	}
	$variables['create_group_1'] = array(); 
	$variables['create_group_2'] = array();
	while(list($key, $fp_group) = each($fp_group_array)){
		$group_id = $fp_group['0'];
		if (!preg_match("(^{$group_id}$|^{$group_id}\,|\,{$group_id}\,|\,{$group_id}$)", $variables['rank_groups'])) {
			$variables['create_group_1'][] = array($fp_group['0'], $fp_group['1']);
		} else { $variables['create_group_2'][] = array($fp_group['0'], $fp_group['1']); }
		unset($group_id);
	}
	unset($fp_group);

	// build the list of available ranking images
	$variables['imagelist'] = makefilelist(PATH_IMAGES."ranking", "", true, 'files');
}

// delete action
if ($action == "delete") {
	$result = dbquery("DELETE FROM ".$db_prefix."forum_ranking WHERE rank_id = '".$rank_id."'");
	// return to the list
	$action = "";
	// display a message
	$variables['bold'] = true;
	$variables['message'] = $locale['492'];
	$template_panels[] = array('type' => 'body', 'name' => 'admin.ranking.message', 'title' => $locale['400'], 'template' => '_message_table_panel.tpl', 'locale' => "admin.ranking");
	$template_variables['admin.ranking.message'] = $variables;
}

// move up action
if ($action == "up") {
	if (isset($rank_order) && isNum($rank_order)) {
		$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = '0' WHERE rank_order = '".$rank_order."'");
		$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = '".$rank_order."' WHERE rank_order = '".($rank_order-1)."'");
		$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = '".($rank_order-1)."' WHERE rank_order = '0'");
		$action = "";
	}
}

// move down action
if ($action == "down") {
	if (isset($rank_order) && isNum($rank_order)) {
		$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = '0' WHERE rank_order = '".$rank_order."'");
		$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = '".$rank_order."' WHERE rank_order = '".($rank_order+1)."'");
		$result = dbquery("UPDATE ".$db_prefix."forum_ranking SET rank_order = '".($rank_order+1)."' WHERE rank_order = '0'");
		$action = "";
	}
}

if ($action == "") {

	// get the rankings
	$result = dbquery("SELECT * FROM ".$db_prefix."forum_ranking ORDER BY rank_order");
	$variables['rankings'] = array();
	while ($data = dbarray($result)) {
		// get the grouplist for this ranking
		if (strpos($data['rank_groups'], ",")) {
			$groups = explode(",", $data['rank_groups']);
		} else {
			$groups = empty($data['rank_groups']) ? array() : array($data['rank_groups']);
		}
		$data['rank_groups'] = "";
		foreach($groups as $group) {
			$data['rank_groups'] .= ($data['rank_groups'] == "" ? "" : ($data['rank_groups_and'] == 0 ? $locale['436'] : $locale['437'])) .str_replace(" ", "&nbsp;", getgroupname($group, -1));
		}
		$variables['rankings'][] = $data;
	}
}
//_debug($variables, true);

// store other variables
$variables['action'] = $action;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.ranking', 'title' => $locale['400'], 'template' => 'admin.ranking.tpl', 'locale' => "admin.ranking");
$template_variables['admin.ranking'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>