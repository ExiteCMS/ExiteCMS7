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
if (!checkrights("S3") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['prune'])) require_once PATH_ADMIN."forums_prune.php";

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		numofthreads='".(isNum($_POST['numofthreads']) ? $_POST['numofthreads'] : "5")."',
		attachments='".(isNum($_POST['attachments']) ? $_POST['attachments'] : "0")."',
		attachmax='".(isNum($_POST['attachmax']) ? $_POST['attachmax'] : "150000")."',
		forum_max_w='".(isNum($_POST['forum_max_w']) ? $_POST['forum_max_w'] : "400")."',
		forum_max_h='".(isNum($_POST['forum_max_h']) ? $_POST['forum_max_h'] : "200")."',
		attachtypes='".$_POST['attachtypes']."',
		thread_notify='".(isNum($_POST['thread_notify']) ? $_POST['thread_notify'] : "0")."'
	");
	redirect(FUSION_SELF.$aidlink);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));
$variables['settings2'] = $settings2;

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_forum', 'template' => 'admin.settings_forum.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/settings.php");
$template_variables['admin.settings_forum'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>