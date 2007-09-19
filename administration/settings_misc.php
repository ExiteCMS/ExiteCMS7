<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| Some portions copyright ? 2002 - 2006 Nick Jones   |
| http://www.php-fusion.co.uk/                       |
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
if (!checkrights("S6") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		tinymce_enabled='".(isNum($_POST['tinymce_enabled']) ? $_POST['tinymce_enabled'] : "0")."',
		smtp_host='".stripinput($_POST['smtp_host'])."',
		smtp_username='".stripinput($_POST['smtp_username'])."',
		smtp_password='".stripinput($_POST['smtp_password'])."',
		bad_words_enabled='".(isNum($_POST['bad_words_enabled']) ? $_POST['bad_words_enabled'] : "0")."',
		bad_words='".addslash($_POST['bad_words'])."',
		bad_word_replace='".stripinput($_POST['bad_word_replace'])."',
		guestposts='".(isNum($_POST['guestposts']) ? $_POST['guestposts'] : "0")."',
		remote_stats='".(isNum($_POST['remote_stats']) ? $_POST['remote_stats'] : "0")."',
		numofshouts='".(isNum($_POST['numofshouts']) ? $_POST['numofshouts'] : "10")."',
		flood_interval='".(isNum($_POST['flood_interval']) ? $_POST['flood_interval'] : "15")."',
		forum_flags='".(isNum($_POST['forum_flags']) ? $_POST['forum_flags'] : "0")."',
		maintenance='".(isNum($_POST['maintenance']) ? $_POST['maintenance'] : "0")."',
		maintenance_message='".addslash(descript($_POST['maintenance_message']))."',
		maintenance_color='".$_POST['maintenance_color']."'
	");
	redirect(FUSION_SELF.$aidlink);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));
$settings2['maintenance_message'] = stripslashes($settings2['maintenance_message']);
$variables['settings2'] = $settings2;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_misc', 'template' => 'admin.settings_misc.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/settings.php");
$template_variables['admin.settings_misc'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>