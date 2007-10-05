<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage custom-plugins
 */

/**
 * Smarty {imagelink} function plugin
 *
 * Type:     function<br>
 * Name:     imagelink<br>
 * Purpose:  generates html for a image that acts like a button link
 * Input:<br>
 *         - image: name of the image
 *         - link: URL to link to
 *         - title: optional title text
 *         - alt: optional alternative name
 *         - onclick: optional javascript to execute when clicked
 *         - new: if "yes", URL opens in new window
 *         - script: if "yes", URL is javascript code
 *
 * Examples: {imagelink name="google.jpg" link="http://www.google.com" new="yes"}
 * Output:   <a href='http://www.google.com' target='_blank'><img src='[THEME]/images/google.jpg' /></a>
 * Examples: {imagelink name="edit.gif" link="javascript: history.go(-1);" script="yes"}
 * Output:   <a href='#' onclick='javascript: history.go(-1);'><img src='[THEME]/images/edit.jpg' /></a>
 * @author WanWizard <wanwizard at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */

function smarty_function_imagelink($params, &$smarty)
{
	// parameter validation and initialisation
    if (!isset($params['image'])) {
        $smarty->trigger_error("buttonlink: missing 'image' parameter");
	} else {
		$image = $params['image'];
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
    if (!isset($params['alt'])) {
        $alt = false;
	} else {
		$alt = $params['alt'];
	}
    if (!isset($params['onclick'])) {
        $onclick = false;
	} else {
		$onclick = $params['onclick'];
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

	return "<a href='".($script ? "#" : $link )."' ".($new ? "target='_blank'" : "" )." ".($onclick ? ("onclick='".$onclick."'") : "" )."><img src='".THEME."images/".$image."' ".($alt ? ("alt='".$alt."'") : "")." ".($title ? ("title='".$title."'") : "")." /></a>";
}

/* vim: set expandtab: */

?>
