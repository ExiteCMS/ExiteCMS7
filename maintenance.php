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

load_templates('body', '');

// close the database connection
mysql_close();

// and flush any output remaining
ob_end_flush();
?>