<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage custom-plugins
 */

/**
 * Smarty {buttonlink} function plugin
 *
 * Type:     function<br>
 * Name:     buttonlink<br>
 * Purpose:  generates html for a button that acts like a link
 * Input:<br>
 *         - name: text on the button
 *         - link: URL to link to
 *         - title: optional title text
 *         - new: if "yes", URL opens in new window
 *         - script: if "yes", URL is javascript code
 *
 * Examples: {buttonlink name="Google!" link="http://www.google.com" new="yes"}
 * Examples: {buttonlink name="Go Back" link="javascript: history.go(-1);" script="yes"}
 * Output:   <input type='button' value='Google!' onClick='window.location="http://www.google.com";' />
 * @author WanWizard <wanwizard at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */

function smarty_function_buttonlink($params, &$smarty)
{
	// parameter validation and initialisation
    if (!isset($params['name'])) {
        $smarty->trigger_error("buttonlink: missing 'name' parameter");
	} else {
		$name = $params['name'];
	}
    if (!isset($params['link'])) {
        $smarty->trigger_error("buttonlink: missing 'link' parameter");
	} else {
		$link = $params['link'];
	}
    if (!isset($params['title'])) {
        $title = false;
	} else {
		$title = $params['title'];
	}
    if (!isset($params['new'])) {
        $new = false;
	} else {
		$new = strtolower($params['new']) == "yes";
	}
    if (!isset($params['script'])) {
        $script = false;
	} else {
		$script = strtolower($params['script']) == "yes";
	}
	
	return "<input type='button' class='button' value='$name' ".($title?"title='$title' ":"")."onClick='".($script ? $link : ($new ? "window.open(\"$link\");'" : "window.location=\"$link\";'"))." />";
}

/* vim: set expandtab: */

?>
