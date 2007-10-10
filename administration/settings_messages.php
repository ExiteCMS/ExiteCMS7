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
if (!checkrights("S7") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

$count = 0;


if (isset($_POST['saveoptions'])) {
	// update the mailbox sizes
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['pm_inbox']."' WHERE cfg_name = 'pm_inbox'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['pm_sentbox']."' WHERE cfg_name = 'pm_sentbox'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['pm_savebox']."' WHERE cfg_name = 'pm_savebox'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['pm_send2group']."' WHERE cfg_name = 'pm_send2group'");
	$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$_POST['pm_hide_rcpts']."' WHERE cfg_name = 'pm_hide_rcpts'");
	// update the global pm settings
	dbquery("UPDATE ".$db_prefix."pm_config SET pmconfig_email_notify = '".$_POST['pm_email_notify']."', pmconfig_read_notify = '".$_POST['pm_read_notify']."', pmconfig_save_sent = '".$_POST['pm_save_sent']."', pmconfig_auto_archive = '".$_POST['pm_auto_archive']."', pmconfig_view = '".$_POST['pm_view']."' WHERE user_id='0'");
	// adjust the auto_archive user settings if needed
	dbquery("UPDATE ".$db_prefix."pm_config SET pmconfig_auto_archive = '".$_POST['pm_auto_archive']."' WHERE pmconfig_auto_archive > '".$_POST['pm_auto_archive']."'");
}

$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."CMSconfig WHERE cfg_name = 'pm_inbox'"));
$variables['pm_inbox'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."CMSconfig WHERE cfg_name = 'pm_sentbox'"));
$variables['pm_sentbox'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."CMSconfig WHERE cfg_name = 'pm_savebox'"));
$variables['pm_savebox'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."CMSconfig WHERE cfg_name = 'pm_send2group'"));
$variables['pm_send2group'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."CMSconfig WHERE cfg_name = 'pm_hide_rcpts'"));
$variables['pm_hide_rcpts'] = $data['cfg_value'];
$variables['usergroups'] = getusergroups(true);
$options = dbarray(dbquery("SELECT * FROM ".$db_prefix."pm_config WHERE user_id='0'"),0);
$variables['pm_email_notify'] = $options['pmconfig_email_notify'];
$variables['pm_read_notify'] = $options['pmconfig_read_notify'];
$variables['pm_save_sent'] = $options['pmconfig_save_sent'];
$variables['pm_auto_archive'] = $options['pmconfig_auto_archive'];
$variables['pm_view'] = $options['pmconfig_view'];

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_messages', 'template' => 'admin.settings_messages.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/settings.php");
$template_variables['admin.settings_messages'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>