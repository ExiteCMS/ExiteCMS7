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
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once dirname(__FILE__)."/includes/theme_functions.php";

// logout requested, switch to setuser to perform the logout
if (isset($_POST['logout'])) {
	$_REQUEST['logout'] = "yes";
	include PATH_ROOT."setuser.php";
	exit;
}

// temp storage for template variables
$variables = array();
if (isset($reason) && $reason == "2") {
	$variables['message'] = $locale['189'];
} else {
	$variables['message'] = stripslashes(nl2br($settings['maintenance_message']));
}

// define the first body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'maintenance', 'template' => 'main.maintenance.tpl');
$template_variables['maintenance'] = $variables;

// make sure the session is closed
session_write_close();

load_templates('body', '');

// clean up and clean exit
theme_cleanup();
?>
