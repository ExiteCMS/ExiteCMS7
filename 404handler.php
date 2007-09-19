<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2007 WanWizard
| http://www.epgcentral.com/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";

// check if we have a redirect record for this URL
if (isset($_SERVER['REDIRECT_URL'])) {
	$result = dbquery("SELECT * from ".$db_prefix."redirects WHERE url_from = '".$_SERVER['REDIRECT_URL']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		// check if the url_to has is an external URL
		if ($data['url_to']{0} != "/") {
			$url = $data['url_to'];
			$redirect = true;
		} else {
			// if not a redirection, check for parameters
			if ($data['url_redirect'] == 0) {
				$redirect = false;
				// preserve URL parameters (if any)
				if ($data['url_parms'] && isset($_SERVER['REDIRECT_QUERY_STRING'])) {
					if (strpos($data['url_to'], '?') === false) {
						$data['url_to'] .= "?" . $_SERVER['REDIRECT_QUERY_STRING'];
					} else {
						$data['url_to'] .= "&" . $_SERVER['REDIRECT_QUERY_STRING'];
					}
				}
				// strip the parameters from the URL, and convert them into variables
				$temp = explode("?", $data['url_to']);
				// get the base URL
				$url = substr($temp[0],1);
				if (count($temp) > 1) {
					// parameter(s) found, strip the anchor if present
					$temp = explode("#", $temp[1]);
					// now split the parameters
					$temp = explode("&", $temp[0]);
					// process every parameter found
					foreach($temp as $parm) {
						$parm = explode("=", $parm);
						// define the variable
						eval("\$$parm[0] = \"$parm[1]\";");
					}
				}
					
			} else {
				// it's a redirect. Use the URL as-is
				$redirect = true;
				$url = $data['url_to'];
			}
		}
		header("Status: 200 OK");
	} else {
		// no redirection found for this page. Prepare to show the custom 404 page
		$url = 'viewpage.php';
		$redirect = false;
		$page_id = 0;
		header("Status: 404 NOT FOUND");
	}
	
} else {
	// no redirection found for this page. Prepare to show the custom 404 page
	$url = 'viewpage.php';
	$redirect = false;
	$page_id = 0;
	header("Status: 404 NOT FOUND");
}

if ($redirect) {
	redirect($url);
} else {
	include PATH_ROOT.$url;
}
?>