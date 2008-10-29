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
| Revision number $$Rev::                                             $|
+---------------------------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";

if (file_exists(PATH_ROOT.$settings['opening_page'])) {
	include PATH_ROOT.$settings['opening_page'];
} else {
	// make sure the redirection happens from the root
	if (substr($settings['opening_page'],0,1) != "/")
	    redirect(BASEDIR.$settings['opening_page']);
	else
	    redirect($settings['opening_page']);
}
?>
