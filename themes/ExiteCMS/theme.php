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
if (eregi("theme.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

/*-----------------------------------------------------+
| ExiteCMS - Table based theme with a 3-column layout  |
|                                                      |
| /-------------------------------------------------\  |
| |                      HEADER                     |  |
| |-------------------------------------------------|  |
| |     |                                     |     |  |
| |     |                UPPER                |  R  |  |
| |  L  |                                     |     |  |
| |     |-------------------------------------|  I  |  |
| |  E  |                                     |     |  |
| |     |                 BODY                |  G  |  |
| |  F  |                                     |     |  |
| |     |-------------------------------------|  H  |  |
| |  T  |                                     |     |  |
| |     |                LOWER                |  T  |  |
| |     |                                     |     |  |
| |-------------------------------------------------|  |
| |                      FOOTER                     |  |
| \-------------------------------------------------/  |
|                                                      |
+-----------------------------------------------------*/

// load the ExiteCMS theme functions
require_once PATH_INCLUDES."theme_functions.php";

// load this theme's functions
require_once PATH_THEME."theme_functions.php";

//initialise the theme engine
theme_init();

/*----------------------------------------------------+
| Put your theme preprocessing code here              |
+----------------------------------------------------*/

// theme width definitions
define('THEME_WIDTH', "994");
define('SIDE_WIDTH', "170");

// make sure this is defined, we need it later
if (!defined('FULL_SCREEN')) define('FULL_SCREEN', false);

/*----------------------------------------------------+
| Load the header template                            |
+----------------------------------------------------*/
// temp storage for template variables
$variables = array();

// header menu definition (links generated in this theme's theme_functions include)
$variables['headermenu'] = $linkinfo;

// unread forum post indicator
if (iMEMBER) {
	if ($userdata['user_posts_unread']) {
		// include the users own posts
		$result = dbquery("SELECT count(*) as unread, sum(tr.thread_page) AS pages FROM ".$db_prefix."posts p LEFT JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id WHERE tr.user_id = '".$userdata['user_id']."' AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].") AND (p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)", false);
	} else {
		// filter the users own posts
		$result = dbquery("SELECT count(*) as unread, sum(tr.thread_page) AS pages FROM ".$db_prefix."posts p LEFT JOIN ".$db_prefix."threads_read tr ON p.thread_id = tr.thread_id WHERE tr.user_id = '".$userdata['user_id']."' AND p.post_author != '".$userdata['user_id']."' AND p.post_edituser != '".$userdata['user_id']."' AND (p.post_datestamp > ".$settings['unread_threshold']." OR p.post_edittime > ".$settings['unread_threshold'].") AND (p.post_datestamp > tr.thread_last_read OR p.post_edittime > tr.thread_last_read)", false);
	}
	$variables['new_posts'] = ($result ? mysql_result($result, 0) : 0);
} else {
	$variables['new_posts'] = 0;
}

// unread PM indicator
$variables['new_pm'] = (iMEMBER ? $variables['new_pm_msg'] = dbcount("(pmindex_id)", "pm_index", "pmindex_user_id='".$userdata['user_id']."' AND pmindex_to_id='".$userdata['user_id']."' AND pmindex_read_datestamp = '0'") : 0);

// Check if we have a favicon to show (first check global image
// directory, then theme image directory (for a theme override)
if (file_exists(PATH_ROOT."images/favicon.ico")) $variables['favicon'] = BASEDIR."images/favicon.ico";
if (file_exists(PATH_THEME."images/favicon.ico")) $variables['favicon'] = THEME."images/favicon.ico";

// Google API key (need it in the header template)
if (isset($google_key)) $variables['google_key'] = $google_key;

// Pass any other header parameters if needed
if (isset($headerparms)) $variables['headparms'] = $headerparms;

// define the header panel
$template_panels[] = array('type' => 'header', 'name' => '_header', 'template' => '_header.tpl');
$template_variables['_header'] = $variables;

// load any header panels
load_panels('header');

// load the header templates
load_templates('header', '');

/*----------------------------------------------------+
| start - 3-column table based layout                 |
+----------------------------------------------------*/
echo "<table class='content' align='center' width='".THEME_WIDTH."' cellspacing='0' cellpadding='0'>
	<tr>\n";
	
/*-----------------------------------------------------+
| Left column (only if not in full-screen mode)        |
+-----------------------------------------------------*/
if (!FULL_SCREEN) {
	// Get the config for all leftside panels
	load_panels('left');
	// if any leftside panel found
	if (count_panels('left') > 0) {
		echo "		<td valign='top' width='".SIDE_WIDTH."' class='side-border-left'>\n";
		// load the templates for the left-side column
		load_templates('left', '');
		echo "		</td>\n";
	}
}
/*-----------------------------------------------------+
| Center column                                        |
+-----------------------------------------------------*/
echo "		<td valign='top' class='main-bg'>\n";

// if in full-screen mode, activate the navigation header panel
if (FULL_SCREEN) {
	add_fullscreen_menu();
}

// Get the config for all upper-center panels
if (load_panels('upper')) {
	// load the templates for the left-side column
	load_templates('upper', '');
}

// Center-body
load_templates('body', '');

// Get the config for all lower-center panels
if (load_panels('lower')) {
	// load the templates for the left-side column
	load_templates('lower', '');
}
echo "		</td>\n";

/*-----------------------------------------------------+
| Right column (only if not in full-screen mode)       |
+-----------------------------------------------------*/
if (!FULL_SCREEN) {
	// Get the config for all rightside panels
	load_panels('right');
	// if any rightside panel found
	if (count_panels('right') > 0) {
		echo "		<td valign='top' width='".SIDE_WIDTH."' class='side-border-right'>\n";
		// load the templates for the right-side column
		load_templates('right', '');
		echo "		</td>\n";
	}
}

/*----------------------------------------------------+
| end - 3-column table based layout                   |
+----------------------------------------------------*/
echo "	</tr>
</table>\n";

/*---------------------------------------------------+
| Load the footer template                           |
+----------------------------------------------------*/
// temp storage for template variables
$variables = array();

// load the footer panels
load_panels('footer');

// define the footer template
$template_panels[] = array('type' => 'footer', 'name' => '_footer', 'template' => '_footer.tpl');
$template_variables['_footer'] = $variables;

// load the footer templates
load_templates('footer', '');

/*---------------------------------------------------+
| Put your theme post processing code here           |
+----------------------------------------------------*/

/*---------------------------------------------------+
| Theme closedown and cleanup.                       |
| This also outputs the </body> and </html> tags     |
+----------------------------------------------------*/
theme_cleanup();
?>