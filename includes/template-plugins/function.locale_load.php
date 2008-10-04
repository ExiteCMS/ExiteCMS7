<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

// WANWIZARD - 20080929 - initial version

/**
 * Smarty {locale_load} function plugin
 *
 * Type:     function<br>
 * Name:     locale_load<br>
 * Date:     September 29, 2008
 * Purpose:  load ExiteCMS locale strings from within a template<br>
 * Input:<br>
 *         - name = name of the locale to load
 *
 * Examples:
 * <pre>
 * {locale_load name="hoteditor"}
 * {locale_load name="main.global"}
 * </pre>
 * @link http://exitecms.exite.eu/modules/wiki/index.php
 * @version  1.0
 * @author   WanWizard <wanwizard at gmail dot com>
 * @param    array
 * @param    Smarty
 * @return   void
 */
function smarty_function_locale_load($params, &$smarty)
{
	global $locale;

    if (empty($params['name'])) {
        $smarty->trigger_error("locale_load: missing 'name' parameter");
        return;
    } else {
        $name = $params['name'];
    }

	locale_load($name);

	// assign the new locales to the template
	$smarty->assign("locale", $locale);
}

/* vim: set expandtab: */

?>
