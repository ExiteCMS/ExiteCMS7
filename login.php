<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2006 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once dirname(__FILE__)."/includes/theme_functions.php";

// redirect back to the homepage if already logged in
if (iMEMBER) {
	header("Location:".BASEDIR."index.php");
}

// temp storage for template variables
$variables = array();

// define the first body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'login', 'template' => 'main.login.tpl');
$template_variables['login'] = $variables;

load_templates('body', '');

// close the database connection
mysql_close();

// and flush any output remaining
ob_end_flush();
?>