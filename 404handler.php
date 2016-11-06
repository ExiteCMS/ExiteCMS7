<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id::                                                               $|
+----------------------------------------------------------------------+
| Last modified by $Author::                                          $|
| Revision number $Rev::                                              $|
+---------------------------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";

// Check if we use IIS. If so, the $_SERVER['REDIRECT_URL'] variable is not available
if (strpos($_SERVER["SERVER_SOFTWARE"], "IIS") !== FALSE) {
	$_SERVER['REDIRECT_URL'] = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/'), strlen($_SERVER['REQUEST_URI']));
}

// requested url
$url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['SCRIPT_URL'];

// do we have a url?
if (!empty($url)) {

	// check if we have a redirect for this URL
	$result = dbquery("SELECT * from ".$db_prefix."redirects WHERE url_from = '".mysqli_real_escape_string($_db_link, $url)."'");

	// found one?
	if (dbrows($result)) {

		// found
		header("HTTP/1.1 200 OK");
		header("Status: 200 OK");
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
		// redirect to or load the new URL
		if ($redirect) {
			redirect($url);
		} else {
			// check for post variables
			$rawpostvars = file_get_contents("php://input");
			if (!empty($rawpostvars)) {
				$rawpostvars = explode("&", $rawpostvars);
				foreach($rawpostvars as $postvar) {
					$postvar = explode("=", $postvar);
					// define the variable
					eval("\$$postvar[0] = \"$postvar[1]\";");
				}
			}
			include PATH_ROOT.$url;
		}
	}
	exit;

} else {

	// not found
	header("HTTP/1.1 404 NOT FOUND");
	header("Status: 404 NOT FOUND");
	// if we had a request URL, and it was not a page (p.e. a missing image), exit without outputting anything
	if (isset($_SERVER['REDIRECT_URL']) && strrchr($url,".") != ".php" && strrchr($url,".") != ".html") {
		exit;
	}
	// load the 404 page for the current locale (or 'en' if not found), and display it
	foreach(array($settings['locale_code'], 'en') as $lc) {
		$result = dbquery("SELECT * from ".$db_prefix."locales WHERE locales_code = '$lc' AND locales_name = '404page' AND locales_key = '404page'");
		if (dbrows($result)) {
			// display the 404 page content
			require_once PATH_ROOT."/includes/theme_functions.php";
			$data = dbarray($result);
			// define the search body panel variables
			$template_panels[] = array('type' => 'body', 'name' => '404page', 'template' => '_message_table_panel.simple.tpl');
			$template_variables['404page'] = array('message' => $data['locales_value']);
			// Call the theme code to generate the output for this webpage
			require_once PATH_THEME."/theme.php";
			// and terminate the loop
			break;
		} else {
			terminate("<font size=6>404 - PAGE NOT FOUND</font></br /></br /><b>And the '404 page not found' page can not be loaded from the database...</b>");
		}
	}
	exit;
}
?>
