<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id:: theme_functions.php 1935 2008-10-29 23:42:42Z WanWizard       $|
+----------------------------------------------------------------------+
| Last modified by $Author:: WanWizard                                $|
| Revision number $Rev:: 1935                                         $|
+---------------------------------------------------------------------*/
if (eregi("theme_functions.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// include the menu functions to create the header menu bar
require_once PATH_INCLUDES."menu_include.php";

// generate the treelist for items in the header menu bar
$linkinfo = array();
menu_generate_tree("", array(2,3), false);

/*------------------------------------------------------+
| convert the navigation panel into a header menu bar   |
+-------------------------------------------------------*/
function add_fullscreen_menu() {
	global $db_prefix, $template_panels, $template_variables;

	// make sure the navigation infusion panel exists
	if (file_exists(PATH_MODULES."main_menu_panel/main_menu_panel.php")) {

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
		
		$template_panels[] = array('type' => 'upper', 'name' => 'modules.main_menu_panel', 'template' => '_fullscreen_navigation.tpl');
		$template_variables['modules.main_menu_panel'] = $variables;
	}
}
?>
