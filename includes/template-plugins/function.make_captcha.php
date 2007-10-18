<?php
/**
 * Smarty custom plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {make_captcha} function plugin
 *
 * Type:     function<br>
 * Name:     make_captcha<br>
 * Date:     October 19, 2007<br>
 * Purpose:  generate the html code for a captcha image
 * @author   Harro "WanWizard" Verton <wanwizard at gmail dot com>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_make_captcha($params, &$smarty)
{
	global $db_prefix, $settings;

	srand((double)microtime() * 1000000);
	$temp_num = md5(rand(0,9999));
	$captcha_string = substr($temp_num, 17, 5);
	$captcha_encode = md5($temp_num);
	$result = mysql_query("INSERT INTO ".$db_prefix."captcha (captcha_datestamp, captcha_ip, captcha_encode, captcha_string) VALUES('".time()."', '".USER_IP."', '$captcha_encode', '$captcha_string')");
	if ($settings['validation_method'] == "image") {
		return "<input type='hidden' name='captcha_encode' value='".$captcha_encode."'><img src='".INCLUDES."captcha.php?captcha_code=".$captcha_encode."' alt='' />\n";
	} else {
		return "<input type='hidden' name='captcha_encode' value='".$captcha_encode."'><strong>".$captcha_string."</strong>\n";
	}
}

/* vim: set expandtab: */

?>
