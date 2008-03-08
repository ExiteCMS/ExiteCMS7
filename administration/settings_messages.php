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
locale_load("admin.settings");

// temp storage for template variables
$variables = array();

// store this modules name for the menu bar
$variables['this_module'] = FUSION_SELF;

// check for the proper admin access rights
if (!checkrights("S7") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

$count = 0;


if (isset($_POST['saveoptions'])) {
	// validate the input
	$pm_inbox = (isset($_POST['pm_inbox']) && isNum($_POST['pm_inbox'])) ? $_POST['pm_inbox'] : 25;
	$pm_sentbox = (isset($_POST['pm_sentbox']) && isNum($_POST['pm_sentbox'])) ? $_POST['pm_sentbox'] : 25;
	$pm_savebox = (isset($_POST['pm_savebox']) && isNum($_POST['pm_savebox'])) ? $_POST['pm_savebox'] : 100;
	$pm_inbox_group = (isset($_POST['pm_inbox_group']) && isNum($_POST['pm_inbox_group'])) ? $_POST['pm_inbox_group'] : 101;
	$pm_sentbox_group = (isset($_POST['pm_sentbox_group']) && isNum($_POST['pm_sentbox_group'])) ? $_POST['pm_sentbox_group'] : 101;
	$pm_savebox_group = (isset($_POST['pm_savebox_group']) && isNum($_POST['pm_savebox_group'])) ? $_POST['pm_savebox_group'] : 101;
	$pm_send2group = (isset($_POST['pm_send2group']) && isNum($_POST['pm_send2group'])) ? $_POST['pm_send2group'] : 103;
	$pm_hide_rcpts = (isset($_POST['pm_hide_rcpts']) && isNum($_POST['pm_hide_rcpts'])) ? $_POST['pm_hide_rcpts'] : 0;
	// update the mailbox sizes
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$pm_inbox."' WHERE cfg_name = 'pm_inbox'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$pm_sentbox."' WHERE cfg_name = 'pm_sentbox'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$pm_savebox."' WHERE cfg_name = 'pm_savebox'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$pm_inbox_group."' WHERE cfg_name = 'pm_inbox_group'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$pm_sentbox_group."' WHERE cfg_name = 'pm_sentbox_group'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$pm_savebox_group."' WHERE cfg_name = 'pm_savebox_group'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$pm_send2group."' WHERE cfg_name = 'pm_send2group'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$pm_hide_rcpts."' WHERE cfg_name = 'pm_hide_rcpts'");
	// update the global pm settings
	dbquery("UPDATE ".$db_prefix."pm_config SET pmconfig_email_notify = '".$_POST['pm_email_notify']."', pmconfig_read_notify = '".$_POST['pm_read_notify']."', pmconfig_save_sent = '".$_POST['pm_save_sent']."', pmconfig_auto_archive = '".$_POST['pm_auto_archive']."', pmconfig_view = '".$_POST['pm_view']."' WHERE user_id='0'");
	// adjust the auto_archive user settings if needed
	dbquery("UPDATE ".$db_prefix."pm_config SET pmconfig_auto_archive = '".$_POST['pm_auto_archive']."' WHERE pmconfig_auto_archive > '".$_POST['pm_auto_archive']."'");
}

$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."configuration WHERE cfg_name = 'pm_inbox'"));
$variables['pm_inbox'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."configuration WHERE cfg_name = 'pm_sentbox'"));
$variables['pm_sentbox'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."configuration WHERE cfg_name = 'pm_savebox'"));
$variables['pm_savebox'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."configuration WHERE cfg_name = 'pm_inbox_group'"));
$variables['pm_inbox_group'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."configuration WHERE cfg_name = 'pm_sentbox_group'"));
$variables['pm_sentbox_group'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."configuration WHERE cfg_name = 'pm_savebox_group'"));
$variables['pm_savebox_group'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."configuration WHERE cfg_name = 'pm_send2group'"));
$variables['pm_send2group'] = $data['cfg_value'];
$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."configuration WHERE cfg_name = 'pm_hide_rcpts'"));
$variables['pm_hide_rcpts'] = $data['cfg_value'];
$variables['usergroups'] = getusergroups(true);
$options = dbarray(dbquery("SELECT * FROM ".$db_prefix."pm_config WHERE user_id='0'"),0);
$variables['pm_email_notify'] = $options['pmconfig_email_notify'];
$variables['pm_read_notify'] = $options['pmconfig_read_notify'];
$variables['pm_save_sent'] = $options['pmconfig_save_sent'];
$variables['pm_auto_archive'] = $options['pmconfig_auto_archive'];
$variables['pm_view'] = $options['pmconfig_view'];

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_messages', 'template' => 'admin.settings_messages.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_messages'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>