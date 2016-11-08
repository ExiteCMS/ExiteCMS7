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
if (!defined('INIT_CMS_OK')) require_once dirname(__FILE__)."/../includes/core_functions.php";

// make sure we have a valid revision level from the settings
if (!isset($settings['revision']) || !isNum($settings['revision'])) $settings['revision'] = 0;

// check for available upgrades
$upgrades = array();
$upgraded = array();
$temp = makefilelist(PATH_ADMIN."upgrade", ".|..");
foreach ($temp as $tempfile) {
	// make sure it's a valid rev file format
	if (strlen($tempfile) != 12 || substr($tempfile,0,3) != "rev" || substr($tempfile,-4) != ".php") continue;
	$thisrev = substr($tempfile,3,5);
	if (isNum($thisrev)) {
		if ($thisrev <= $settings['revision']) {
			$upgraded[] = $tempfile;
		} else {
			$upgrades[] = $tempfile;
		}
	}
}

// sort upgraded descending, to show the latest one first
krsort($upgraded);

// set a constant to define the upgrades available
define('UPGRADES', count($upgrades));

// set a constant to define the upgrades already installed
define('UPGRADED', count($upgraded));

// if it was called from the admin panel, continue interactively
if (strpos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false) {

	// bail out if the user has no rights here
	if (!checkrights("U") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

	// load the required theme functions
	require_once PATH_ROOT."/includes/theme_functions.php";

	// load the locale for this module
	locale_load("admin.upgrade");

	// temp storage for template variables
	$variables = array();

	// get some information from the upgrades already installed
	if (UPGRADED) {
		$revisions = array();
		// load and check the revision files
		foreach ($upgraded as $revfile) {
			require_once PATH_ADMIN."upgrade/".$revfile;
		}
		$variables['revisions_installed'] = $revisions;
	}

	// check if there are upgrades available
	if (UPGRADES) {
		if (isset($_POST['stage']) && $_POST['stage'] == 2) {
			// stage 2: perform the upgrades
			$variables['stage'] = 2;
			// switch to MySQL4 mode to avoid errors
			$result = dbquery("SET SESSION sql_mode='MYSQL40'");
			// start with the current revision number
			$new_revision = $settings['revision'];
			$results = array();
			// loop through the available revision updates
			foreach ($upgrades as $revfile) {
				// init the commands array before loading the upgrade code
				$commands = array();
				// load the upgrade code
				require_once PATH_ADMIN."upgrade/".$revfile;
				// reset the error tracking variable
				$errors = array();
				foreach ($commands as $cmd) {
					// skip empty entries
					if (!is_array($cmd) || count($cmd) == 0) continue;
					switch($cmd['type']) {
						case "db":
							// put the correct prefix in place
							$dbcmd = str_replace('##PREFIX##', $db_prefix, $cmd['value']);
							// execute the command
							$result = dbquery($dbcmd, false);
							if (!$result) {
								// record the error
								$errtype = $locale['420'];
								$errors[] = "Query: ".$dbcmd."<br /><font color='red'>".mysqli_error($_db_link)."</font>";
							}
							break;
						case "function":
							$errtype = $locale['421'];
							$function = $cmd['value'];
							if (function_exists($function)) {
								$result = $function();
								if ($result) $errors[] = $result;
							} else {
								$errors[] = "Unknown upgrade function '".$cmd['value']."()' defined in '".$revfile."'";
							}
							break;
						default:
							$errtype = $locale['422'];
							$errors[] = "Unknown command type '".$cmd['type']."' in '".$revfile."'";
					}
				}
				// if no errors occurred, update the revision number
				if (count($errors) == 0) {
					$new_revision = $_revision;
				} else {
					// errors in this upgrade. Break the process loop
					break;
				}
			}
			// if errors occurred, load the template variables
			if (count($errors) != 0) {
				$results[] = array('revision' => $_revision, 'errtype' => $errtype, 'errors' => $errors);
			}
			// if (some) upgrades succeeded, update the revision number
			if ($settings['revision'] != $new_revision) {
				$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$new_revision."' WHERE cfg_name = 'revision'");
			}
			$variables['results'] = $results;
			$variables['revision'] = $new_revision;
		} else {
			// stage 1: show the admin the available upgrades
			$revisions = array();
			$commands = array();
			// load and check the revision files
			foreach ($upgrades as $revfile) {
				require_once PATH_ADMIN."upgrade/".$revfile;
			}
			$variables['stage'] = 1;
			$variables['revisions'] = $revisions;
		}
	} else {
		// no upgrades, show a message
		$variables['message'] = $locale['401'];
		$variables['bold'] = true;
	}
	// check for newer revisions on the ExiteCMS website
	$variables['new_upgrades'] = false;

	$template_panels[] = array('type' => 'body', 'name' => 'admin.upgrade', 'template' => 'admin.upgrade.tpl', 'locale' => "admin.upgrade");
	$template_variables['admin.upgrade'] = $variables;

	// Call the theme code to generate the output for this webpage
	require_once PATH_THEME."/theme.php";

}
?>
