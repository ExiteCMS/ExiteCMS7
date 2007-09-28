<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty abs modifier plugin
 *
 * Type:     modifier<br>
 * Name:     abs<br>
 * Purpose:  convert number to abs(number)
 * @author   WanWizard <wanwizard at gmail dot com>
 * @param number
 * @return number
 */
function smarty_modifier_abs($number)
{
    return is_numeric($number) ? abs($number) : $number;
}

?>
