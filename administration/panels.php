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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("admin.panels");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("P") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameters are valid
if (isset($panel_id) && !isNum($panel_id)) fallback(FUSION_SELF.$aidlink);
if (isset($panel_side) && !isNum($panel_side)) fallback(FUSION_SELF.$aidlink);
if (!isset($step)) $step = "";

// compose the query where clause based on the localisation method choosen
switch ($settings['panels_localisation']) {
	case "none":
		$where = "";
		break;
	case "single":
		$where = "";
		break;
	case "multiple":
		if (isset($panel_locale)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_code = '".stripinput($panel_locale)."' AND locale_active = '1' LIMIT 1");
			if (!dbrows($result)) unset($panel_locale);
		}
		if (!isset($panel_locale)) $panel_locale = $settings['locale_code'];
		$variables['panel_locale'] = $panel_locale;
		$where = "panel_locale = '".$panel_locale."' ";
		break;
}

if ($step == "refresh") {
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='0'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='4'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='5'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='6'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
}

// delete a panel
if ($step == "delete") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id='$panel_id'"));
	$result = dbquery("DELETE FROM ".$db_prefix."panels WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order>='".$data['panel_order']."'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

// flip the panel status
if ($step == "setstatus") {
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_status='$status' WHERE panel_id='$panel_id'");
}

// move up or down in the order
if ($step == "mup") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='$panel_id'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

if ($step == "mdown") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='$panel_id'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

// move location
if ($step == "mfooter") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='6'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='6', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

if ($step == "mleft") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='1', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

if ($step == "mupper") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='2', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

if ($step == "mlower") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='3', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 ".($where!=""?(" AND ".$where):"")."WHERE panel_side='2' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

if ($step == "mright") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='4'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='4', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

if ($step == "mtop") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='5'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='5', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

if ($step == "mheader") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='0'".($where!=""?(" AND ".$where):"")." ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='0', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' ".($where!=""?(" AND ".$where):"")."AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink.(isset($panel_locale)?("&panel_locale=".$panel_locale):""));
}

$state = array($locale['463'], $locale['464'], $locale['465']);
$ps = -1; $i = 0;

// NOTE: CASE construct to move Top panels (panel_side = 5) before the header panels (panel_side = 0)
$result = dbquery("SELECT *, CASE WHEN panel_side = 5 THEN 0 ELSE panel_side + 1 END AS sort FROM ".$db_prefix."panels ".($where!=""?("WHERE ".$where):"")."ORDER BY sort, panel_order");
$variables['panels'] = array();
while ($data = dbarray($result)) {
	$numrows = dbcount("(panel_id)", "panels", "panel_side='".$data['panel_side']."'".($where!=""?("AND ".$where):""));
	$data['panel_side_count'] = $numrows;
	if ($ps != $data['panel_side']) { 
		$ps = $data['panel_side']; 
		$i = 1; 
		$data['new_panel_side'] = true;
		$data['panel_side_count'] = $numrows;
	} else {
		$data['new_panel_side'] = false;
	}
	if ($numrows != 1) {
		if ($i == 1) {
			$data['order_up'] = 0;
			$data['order_down'] = $data['panel_order'] + 1;
		} else if ($i < $numrows) {
			$data['order_up'] = $data['panel_order'] - 1;
			$data['order_down'] = $data['panel_order'] + 1;
		} else {
			$data['order_up'] = $data['panel_order'] - 1;
			$data['order_down'] = 0;
		}
	} else {
		$data['order_up'] = 0;
		$data['order_down'] = 0;
	}
	if ($data['panel_side'] == 0) { $data['panel_side_name'] = $locale['490'];
	} elseif ($data['panel_side'] == 1) { $data['panel_side_name'] = $locale['420'];
	} elseif ($data['panel_side'] == 2) { $data['panel_side_name'] = $locale['421'];
	} elseif ($data['panel_side'] == 3) { $data['panel_side_name'] = $locale['425'];
	} elseif ($data['panel_side'] == 4) { $data['panel_side_name'] = $locale['422']; 
	} elseif ($data['panel_side'] == 5) { $data['panel_side_name'] = $locale['496']; 
	} elseif ($data['panel_side'] == 6) { $data['panel_side_name'] = $locale['491']; 
	}
	$data['panel_state_name'] = $state[$data['panel_state']];
	$data['panel_type_name'] = ($data['panel_type'] == "file" ? $locale['423'] : $locale['424']);
	$data['panel_access_name'] = getgroupname($data['panel_access'], -1);
	$variables['panels'][] = $data;
	$i++;
}

// get the installed locales
$variables['locales'] = array();
$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_active = '1'");
while ($data = dbarray($result)) {
	$variables['locales'][$data['locale_code']] = $data['locale_name'];
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.panels', 'template' => 'admin.panels.tpl', 'locale' => "admin.panels");
$template_variables['admin.panels'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
