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
include PATH_LOCALE.LOCALESET."admin/settings.php";

// temp storage for template variables
$variables = array();

// store this modules name for the menu bar
$variables['this_module'] = FUSION_SELF;

// check for the proper admin access rights
if (!checkrights("S5") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		thumb_w='".(isNum($_POST['thumb_w']) ? $_POST['thumb_w'] : "100")."',
		thumb_h='".(isNum($_POST['thumb_h']) ? $_POST['thumb_h'] : "100")."',
		photo_w='".(isNum($_POST['photo_w']) ? $_POST['photo_w'] : "400")."',
		photo_h='".(isNum($_POST['photo_h']) ? $_POST['photo_h'] : "300")."',
		photo_max_w='".(isNum($_POST['photo_max_w']) ? $_POST['photo_max_w'] : "1800")."',
		photo_max_h='".(isNum($_POST['photo_max_h']) ? $_POST['photo_max_h'] : "1600")."',
		photo_max_b='".(isNum($_POST['photo_max_b']) ? $_POST['photo_max_b'] : "150000")."',
		thumb_compression='".$_POST['thumb_compression']."',
		thumbs_per_row='".(isNum($_POST['thumbs_per_row']) ? $_POST['thumbs_per_row'] : "4")."',
		thumbs_per_page='".(isNum($_POST['thumbs_per_page']) ? $_POST['thumbs_per_page'] : "12")."'
	");
	redirect(FUSION_SELF.$aidlink);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));
$variables['settings2'] = $settings2;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_image', 'template' => 'admin.settings_image.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/settings.php");
$template_variables['admin.settings_image'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>