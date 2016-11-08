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
if (strpos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false || !defined('INIT_CMS_OK')) die();

// internal replacement for checkdnsrr, which works cross-platform
function CMS_checkdnsrr($host, $type = '' ) {

	// if not given, set the type to the default value
	if ($type == '') {
		$type = "MX";
	}

	// for non-windows platforms, use the internal function
	if (CMS_getOS() != "Windows") {
		return checkdnsrr($host, $type);
	}

	if (!empty($host)) {

		@exec("nslookup -type=$type $host", $output );

		while ( list( $k, $line ) = each( $output ) ) {
			# Valid records begin with host name:
			if ( preg_match( "~^$host~", $line ) ) {
				# record found:
				return true;
			}
		}
		return false;

	} else {

		trigger_error("CMS_checkdnsrr: missing parameter 'host'", E_USER_ERROR);

	}

}

function CMS_getmxrr($hostname, &$mxhosts) {

	if (!is_array($mxhosts)) {
		$mxhosts = array();
	}

	// for non-windows platforms, use the internal function
	if (CMS_getOS() != "Windows") {
		$result = getmxrr($hostname, $hosts, $weight);
		if (!$result) {
			return false;
		} else {
			// sort the result based on weight
			$mxs = array();
			for ($i=0;$i<count($hosts);$i++) {
				$mxs[$hosts[$i]] = $weight[$i];
			}
			asort($mxs);
			$mxhosts = array_keys($mxs);
			return true;
		}
	}

	if (!empty($hostname)) {

		@exec ("nslookup -type=MX $hostname", $output, $ret);

		while ( list( $k, $line ) = each( $output ) ) {

			# Valid records begin with hostname:
			if (preg_match( "~^$hostname\tMX preference = ([0-9]+), mail exchanger = (.*)$~", $line, $parts ) ) {
				$mxhosts[ $parts[1] ] = $parts[2];
			}
		}

		if (count($mxhosts)) {
			reset($mxhosts);
			ksort($mxhosts);
			$i = 0;
			while (list( $pref, $host ) = each( $mxhosts ) ) {
				$mxhosts2[$i] = $host;
				$i++;
			}
			$mxhosts = $mxhosts2;
			return true;
		} else {
			return false;

		}
	} else {
		trigger_error("CMS_getmxrr: missing parameter 'host'", E_USER_ERROR);
	}
}
?>
