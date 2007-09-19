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
if (eregi("welcome_message_panel.php", $_SERVER['PHP_SELF']) || !defined('IN_FUSION')) die();

// array's to store the variables for this panel
$variables = array();

if (!empty($settings['siteintro'])) {
	$variables['message'] = stripslashes($settings['siteintro']);
	$template_variables['modules.welcome_message_panel'] = $variables;
} else {
    $no_panel_displayed = true;
}
?>