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

/*---------------------------------------------------+
| local functions                                    |
+----------------------------------------------------*/
function display($text) {

	global $messages;

	if (CMS_CLI) {
		// just output the message
		echo $text,"\n";
	} else {
		// replace leading spaces by &nbsp; to keep indentations
		$t = ltrim($text);
		$l = strlen($text) - strlen($t);
		$messages[] = str_repeat("&nbsp;", $l).$t;
	}
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files", $hidden=false) {
	$res = array();
	if (is_dir($folder)) {
		$filter = explode("|", $filter); 
		$temp = opendir($folder);
		while ($file = readdir($temp)) {
			if (!$hidden && $file{0} == ".") continue;
			if ($type == "files" && !in_array($file, $filter)) {
				if (!is_dir($folder.$file)) $res[] = $file;
			} elseif ($type == "folders" && !in_array($file, $filter)) {
				if (is_dir($folder.$file)) $res[] = $file;
			}
		}
		closedir($temp);
		if ($sort) sort($res);
	}
	return $res;
}

/*---------------------------------------------------+
| main code                                          |
+----------------------------------------------------*/

define("PATH_ROOT", realpath(dirname(__FILE__).'/../../').'/');
define("PATH_ADMIN", PATH_ROOT."administration/");
define("PATH_INCLUDES", PATH_ROOT."includes/");
define("PATH_MODULES", PATH_ROOT."modules/");

// mark that CMS Engine is properly initialized
define("INIT_CMS_OK", TRUE);

// force CLI mode for this module
define("CMS_CLI", TRUE);

// load the config file
if (file_exists(PATH_ROOT."config.php")) {
	@include_once PATH_ROOT."config.php";
}

// if config.php is absent or empty, bail out with an error
if (!isset($db_name)) die('FATAL ERROR: config file is missing. Check the documentation on how to run the setup');

// required array for db_functions.php
$_loadstats = array();
$_loadtime = explode(" ", microtime());
$_loadtime = $_loadtime[1] + $_loadtime[0];
$_loadstats['time'] = -$_loadtime;
$_loadstats['querytime'] = 0;
$_loadstats['queries'] = 0;
$_loadstats['selects'] = 0;
$_loadstats['inserts'] = 0;
$_loadstats['deletes'] = 0;
$_loadstats['updates'] = 0;
$_loadstats['others'] = 0;
$_loadstats['compression'] = (ini_get('zlib.output_compression') == "1");
unset($_loadtime);

// load the database functions, and establish a database connection
require_once PATH_INCLUDES."db_functions.php";

// fetch the CMSconfig from the database and store them in the $settings variable
if (dbtable_exists($db_prefix."CMSconfig")) {
	// get the settings from the CMSconfig table (introduced in revision 909)
	$settings = array();
	$result = dbquery("SELECT * FROM ".$db_prefix."CMSconfig");
	while ($data = dbarray($result)) {
		$settings[$data['cfg_name']] = $data['cfg_value'];
	}
	// and drop the settings table if it exists
	if (dbtable_exists($db_prefix."settings")) {
		$result = dbquery("DROP TABLE ".$db_prefix."settings");
	}
} else {
	// use the settings table
	$settings = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));
}

// load the locale functions
require_once PATH_INCLUDES."locale_functions.php";

// define the array to store our progress messages in when not in CLI mode
$messages = array();

// make sure we have a valid revision level from the settings
if (!isset($settings['revision']) || !is_numeric($settings['revision'])) $settings['revision'] = 0;

// check for available upgrades
$upgrades = array();
$temp = makefilelist(PATH_ADMIN."upgrade", ".|..");
foreach ($temp as $tempfile) {
	// make sure it's a valid rev file format
	if (strlen($tempfile) != 12 || substr($tempfile,0,3) != "rev" || substr($tempfile,-4) != ".php") continue;
	$thisrev = substr($tempfile,3,5);
	if (is_numeric($thisrev)) {
		if ($thisrev > $settings['revision']) {
			$upgrades[] = $tempfile;
		}
	}
}

// set a constant to define the upgrades available
define('UPGRADES', count($upgrades));

// check if there are upgrades available
$found_error = false;
if (UPGRADES) {
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
						$errtype = "Command";
						$errors[] = "Query: ".$dbcmd."<br /><font color='red'>".mysql_error()."</font>";
					}
					break;
				case "function":
					$errtype = "Function";
					$function = $cmd['value'];
					if (function_exists($function)) {
						$result = $function();
						if ($result) $errors[] = $result;
					} else {
						$errors[] = "Unknown upgrade function '".$cmd['value']."()' defined in '".$revfile."'";
					}
					break;
				default:
					$errtype = "Unknown";
					$errors[] = "Unknown command type '".$cmd['type']."' in '".$revfile."'";
			}
		}
		// if no errors occurred, update the revision number
		if (count($errors) == 0) {
			$new_revision = $_revision;
		} else {
			// errors in this upgrade. Break the process loop
			$found_error = true;
			break;
		}
	}
	// if (some) upgrades succeeded, update the revision number
	if ($settings['revision'] != $new_revision) {
		$result = dbquery("UPDATE ".$db_prefix."CMSconfig SET cfg_value = '".$new_revision."' WHERE cfg_name = 'revision'");
	}
}

// load the english locale if no errors detected
if (!$found_error) {
	// pretend we're doing the initial locale load from the setup procedure
	define("CMS_SETUP", TRUE);
	define("CMS_SETUP_LOAD", TRUE);
	require_once "language_pack_English.php";
} else {
	foreach($errors as $err) {
		display($err);
	}
}
echo "Done!";
?>