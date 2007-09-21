<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:    function
 * Name:    ssprintf
 * Version: 0.1
 * Date:    2004-3-22
 * Author:  srni <nsr_xh@yahoo.com.cn>
 * Purpose: Returns a string produced according to the formatting string format.
 * Params:   
 *      needed      string  format      The format string is composed of zero or more directives:
 *                                      ordinary characters (excluding %) that are copied directly to the result,
 *                                      and conversion specifications, each of which results in fetching its own parameter.
 *      needed      mixed   $var[$n]    args for sprintf.
 *
 * Usage:   {ssprintf format=$string var1=$var1 [var2=$var2 [...]]} - creates an unordered list
 * Install: Drop into the plugin directory
 * -------------------------------------------------------------
 *      NOTE:
 *          The order of params is very importent for sprintf()
 *          so please use this function carefully :)
 * -------------------------------------------------------------
 *      CHANGES:
 *
 * -------------------------------------------------------------
 */
function smarty_function_ssprintf($params, &$smarty)
{
    if (!is_array($params)) {
        $smarty->trigger_error("ssprintf: wrong paramter");
        return;
    }
   
    reset($params);

    if (!isset($params['format'])) {
        $smarty->trigger_error("ssprintf: missing 'format' parameter");
        return;
    }

    if($params['format'] == '') {
        return;
    }

    $format = $params['format'];
    unset($params['format']);

    $args   = array();
    foreach ($params as $key=>$val) {
        if (is_array($val)) {
            foreach ($val as $k=>$v) {
                array_push($args, $val);
            }
            continue;
        }
        if (preg_match("!var[\d+]!", $key)) {
            array_push($args, $val);
            continue;
        }
    }

    $s = "\$content = sprintf(\$format";
    foreach($args as $k=>$val) {
        $s .= ", \$args[$k]";
    }
    $s = $s . ");";
   
    eval($s);

    if ($content === false) {
        $smarty->trigger_error("ssprintf: params not match");
        return;
    }

    return $content;
}
?>