<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage custom-plugins
 */

/**
 * Smarty {load_templates} function plugin
 *
 * Type:     function<br>
 * Name:     load_templates<br>
 * Purpose:  generates the html for one or more templates
 * Input:<br>
 *         - location: location of the template (left, top, right, 
 *         - name: optional name of the template
 *
 * Examples: {load_templates location="left" name="main_menu_panel"}
 * @author WanWizard <wanwizard at exitecms dot org>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */

function smarty_function_load_templates($params, &$smarty)
{
	// parameter validation and initialisation
	if (!isset($params['location'])) {
		$smarty->trigger_error("load_templates: missing 'location' parameter");
	} else {
		$location = $params['location'];
	}
	if (!isset($params['name'])) {
		$name = "";
	} else {
		$name = strtolower($params['name']);
	}

	return load_templates($location, $name, "var");
}

/* vim: set expandtab: */

?>
