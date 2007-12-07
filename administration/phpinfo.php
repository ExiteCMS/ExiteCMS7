<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// show the output full screen
define('FULL_SCREEN', true);

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("PI") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

ob_start();
@ob_implicit_flush(0);
phpinfo();
$variables['message'] = ob_get_contents();
$variables['message'] = "<br />".stristr($variables['message'], '<body>');
$variables['message'] = str_replace('<body>', '', $variables['message']);
$variables['message'] = str_replace('</body>', '', $variables['message']);
$variables['message'] = str_replace('</html>', '', $variables['message']);
$variables['message'] = str_replace('<table border="0" cellpadding="3" width="600">', '<table border="0" align="center" cellpadding="3" cellspacing="1" class="tbl-border" width="700">',$variables['message']);
$variables['message'] = str_replace('<h1 class="p">', '<h1>', $variables['message']);
$variables['message'] = str_replace('<td class="e">', '<td class="tbl1" style="white-space:nowrap;font-weight:bold;">', $variables['message']);
$variables['message'] = str_replace('<tr class="h">', '<tr class="tbl2">', $variables['message']);
$variables['message'] = str_replace('<tr class="v">', '<tr class="tbl2">', $variables['message']);
$variables['message'] = str_replace('<td class="v">', '<td class="tbl2">', $variables['message']);
$variables['message'] = str_replace('<td>', '<td class="tbl2">', $variables['message']);
$variables['message'] = str_replace('<h1>', '<center><h1 style="font-size: 200%;">', $variables['message']);
$variables['message'] = str_replace('</h1>', '</h1></center>', $variables['message']);
$variables['message'] = str_replace('<h2>', '<center><h2 style="font-size: 150%;">', $variables['message']);
$variables['message'] = str_replace('</h2>', '</h2></center>', $variables['message']);
$variables['message'] = str_replace('<img ', '<img style="float: right; border: 0px;" ', $variables['message']);
$variables['message'] = str_replace('<a name', '<a style="font-size: 125%; color: #000000; text-decoration: none;" name', $variables['message']);

ob_end_clean();
// define the panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.phpinfo', 'title' => "PHPinfo", 'template' => '_message_table_panel.simple.tpl');
$template_variables['admin.phpinfo'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>