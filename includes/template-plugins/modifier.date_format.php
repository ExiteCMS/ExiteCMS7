<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage exitecms-plugins
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared', 'make_timestamp');
/**
 * Smarty ExiteCMS date_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     date_format<br>
 * Purpose:  format datestamps via strftime<br>
 *           modifier takes the users timezone settings into account<br>
 * Input:<br>
 *         - string: input date string
 *         - format: ExitecMS standard format name
 *                   or a strftime format for output
 *         - default_date: default date if $string is empty
 * @author   WanWizard <wanwizard at gmail dot com>
 * @param string
 * @param string
 * @param string
 * @return string|void
 * @uses smarty_make_timestamp()
 * @user global array $settings
 */
function smarty_modifier_date_format($string, $format = '%b %e, %Y', $default_date = '')
{
	global $settings;

	switch($format) {
		case "shortdate":
		case "longdate":
		case "subheaderdate":
		case "forumdate":
			$format = $settings[$format];
	}
	switch($format) {
		case "localedate":
			$format = preg_replace("/[^a-zA-z%]/", " ", str_replace("%m", "%B", nl_langinfo(D_FMT)));
			break;
		case "localetime":
			$format = nl_langinfo(T_FMT);
			break;
		case "localedatetime":
			$format = preg_replace("/[^a-zA-z%]/", " ", str_replace("%m", "%B", nl_langinfo(D_FMT)))." ".nl_langinfo(T_FMT);
			break;
	}	
    if ($string != '') {
        $timestamp = smarty_make_timestamp($string);
    } elseif ($default_date != '') {
        $timestamp = smarty_make_timestamp($default_date);
    } else {
        return;
    }
    $timestamp = time_system2local($timestamp);
	
    if (DIRECTORY_SEPARATOR == '\\') {
        $_win_from = array('%D',       '%h', '%n', '%r',          '%R',    '%t', '%T');
        $_win_to   = array('%m/%d/%y', '%b', "\n", '%I:%M:%S %p', '%H:%M', "\t", '%H:%M:%S');
        if (strpos($format, '%e') !== false) {
            $_win_from[] = '%e';
            $_win_to[]   = sprintf('%\' 2d', date('j', $timestamp));
        }
        if (strpos($format, '%l') !== false) {
            $_win_from[] = '%l';
            $_win_to[]   = sprintf('%\' 2d', date('h', $timestamp));
        }
        $format = str_replace($_win_from, $_win_to, $format);
    }
    // check for custom %$, and replace it by swatch time
    $format = str_replace("%$", date("B", $timestamp), $format);

    return strftime($format, $timestamp);
}

/* vim: set expandtab: */

?>