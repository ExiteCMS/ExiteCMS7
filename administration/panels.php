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
locale_load("admin.panels");

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("P") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// make sure the parameters are valid
if (isset($panel_id) && !isNum($panel_id)) fallback(FUSION_SELF.$aidlink);
if (isset($panel_side) && !isNum($panel_side)) fallback(FUSION_SELF.$aidlink);
if (!isset($step)) $step = "";

if ($step == "refresh") {
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='4' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
}

if ($step == "delete") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id='$panel_id'"));
	$result = dbquery("DELETE FROM ".$db_prefix."panels WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' AND panel_order>='".$data['panel_order']."'");
	redirect(FUSION_SELF.$aidlink);
}

if ($step == "setstatus") {
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_status='$status' WHERE panel_id='$panel_id'");
}

if ($step == "mup") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$panel_side' AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='$panel_id'");
	redirect(FUSION_SELF.$aidlink);
}

if ($step == "mdown") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$panel_side' AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='$panel_id'");
	redirect(FUSION_SELF.$aidlink);
}

if ($step == "mleft") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='1', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='4' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink);
}

if ($step == "mright") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='4' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='4', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='1' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink);
}

if ($step == "mupper") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='2', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='3' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink);
}

if ($step == "mlower") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='3', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='2' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink);
}


$state = array($locale['463'], $locale['464'], $locale['465']);
$ps = 0; $i = 0;

$result = dbquery("SELECT * FROM ".$db_prefix."panels ORDER BY panel_side, panel_order");
$variables['panels'] = array();
while ($data = dbarray($result)) {
	$numrows = dbcount("(panel_id)", "panels", "panel_side='".$data['panel_side']."'");
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
	if ($data['panel_side'] == 1) { $data['panel_side_name'] = $locale['420'];
	} elseif ($data['panel_side'] == 2) { $data['panel_side_name'] = $locale['421'];
	} elseif ($data['panel_side'] == 3) { $data['panel_side_name'] = $locale['425'];
	} elseif ($data['panel_side'] == 4) { $data['panel_side_name'] = $locale['422']; 
	}
	$data['panel_state_name'] = $state[$data['panel_state']];
	$data['panel_type_name'] = ($data['panel_type'] == "file" ? $locale['423'] : $locale['424']);
	$data['panel_access_name'] = getgroupname($data['panel_access'], -1);
	$variables['panels'][] = $data;
	$i++;
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.panels', 'template' => 'admin.panels.tpl', 'locale' => "admin.panels");
$template_variables['admin.panels'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>