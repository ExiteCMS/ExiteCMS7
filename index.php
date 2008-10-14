<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";

if (file_exists(PATH_ROOT.$settings['opening_page'])) {
	include PATH_ROOT.$settings['opening_page'];
} else {
	// make sure the redirection happens from the root
	if (substr($settings['opening_page'],0,1) != "/")
	    redirect($settings['siteurl'].$settings['opening_page']);
	else
	    redirect($settings['opening_page']);
}
?>
