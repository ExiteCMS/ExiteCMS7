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
if (eregi("welcome_message_panel.php", $_SERVER['PHP_SELF']) || !defined('ExiteCMS_INIT')) die();

// array's to store the variables for this panel
$variables = array();

if (!empty($settings['siteintro'])) {
	$variables['message'] = stripslashes($settings['siteintro']);
	$template_variables['modules.welcome_message_panel'] = $variables;
} else {
    $no_panel_displayed = true;
}
?>