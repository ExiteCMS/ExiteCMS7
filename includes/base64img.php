<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2009 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id:: ajax.response.php 2164 2009-01-21 19:09:17Z WanWizard         $|
+----------------------------------------------------------------------+
| Last modified by $Author:: WanWizard                                $|
| Revision number $Rev:: 2164                                         $|
+---------------------------------------------------------------------*/

// convert the query string into an image
if (!empty($_SERVER["QUERY_STRING"])) {
	if (strpos(";",$_SERVER["QUERY_STRING"]) !== false) {
		$data = split(";", $_SERVER["QUERY_STRING"]);
		$type = $data[0];
		$data = split(",", $data[1]);
	} else {
		// blank 1x1 gif image
		$data = array("", "R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAQAICRAEAOw==");
		$type = "gif";
	}
} else {
		// blank 1x1 gif image
	$data = array("", "R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAQAICRAEAOw==");
	$type = "gif";
}

header("Content-type: ".$type);
echo base64_decode($data[1]);
exit;
?>
