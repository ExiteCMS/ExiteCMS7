<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.LOCALESET."webshop.php";

// load the GeoIP include module
require_once PATH_INCLUDES."geoip_include.php";

// temp storage for template variables
$variables = array();

// shop iframe dimensions
define('IFRAME_H', 1500);
define('IFRAME_W', 790);

// Shops, the first shop in the list is the default shop
$shoplist = array();
$shoplist[] = array("cc" => "eu", "link" => "http://pli-images.spreadshirt.net/", "default_for" => "");
$shoplist[] = array("cc" => "us", "link" => "http://pli-images.spreadshirt.com/", "default_for" => "us,ca,mx");

// validate the parameters.
if (isset($shop)) {
	$found = false;
	foreach($shoplist as $shopinfo) {
		if ($shopinfo['cc'] == $shop) {
			$found = true;
			break;
		}
	}
	if (!$found) $shop = "";
} else {
	$shop = "";
}
// If no shop is selected, make a guess based on the users IP
if (empty($shop)) {
	$cc_code = strtolower(GeoIP_IP2Code(USER_IP));
	// see if we can find a shop match
	if (!empty($cc_code)) {
		$found = false;
		foreach($shoplist as $shopinfo) {
			$cc_list = explode(",", $shopinfo['default_for']);
			if (!is_array($cc_list)) die('not an array!');
			foreach($cc_list as $cc_list_code) {
				if ($cc_code == $cc_list_code) {
					$found = true;
					$shop = $shopinfo['cc'];
				}
			}
			if ($found) break;
		}
		if (!$found) $shop = "";
	}
}
// if no guess could be made, default to the first shop in the list
if (empty($shop)) {
	$shop = $shoplist[0]['cc'];
}

foreach($shoplist as $index => $shopinfo) {
	$shoplist[$index]['flag'] = GeoIP_Code2Flag($shopinfo['cc']);
	$shoplist[$index]['name'] = GeoIP_Code2Name($shopinfo['cc']);
}

$variables['shop'] = $shop;
$variables['shoplist'] = $shoplist;

// define the search body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'webshop', 'template' => 'main.webshop.tpl', 'locale' => PATH_LOCALE.LOCALESET."webshop.php");
$template_variables['webshop'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>