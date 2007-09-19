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
if (eregi("main_menu_panel.php", $_SERVER['PHP_SELF']) || !defined('IN_FUSION')) die();

// load the menu include
require_once PATH_INCLUDES."menu_include.php";

// array's to store the variables for this panel
$variables = array();

// define linkinfo as global, as this script is called from within a function!
global $linkinfo; $linkinfo = array(); 

// build the menu tree for this panel
menu_generate_tree('main_menu_panel');

$variables['linkinfo'] = $linkinfo;

// we want to auto-close submenu's that are open
$variables['close_open_submenus'] = true;

$template_variables['modules.main_menu_panel'] = $variables;
?>