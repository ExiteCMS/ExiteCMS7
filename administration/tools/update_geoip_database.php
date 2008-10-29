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
require_once dirname(__FILE__)."/../../includes/core_functions.php";

// check for the proper admin access rights
if (!CMS_CLI && (!checkrights("T") || !defined("iAUTH") || $aid != iAUTH)) fallback(ADMIN."index.php");

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

/*---------------------------------------------------+
| main code                                          |
+----------------------------------------------------*/

// give this module some memory and execution time
ini_set('memory_limit', '64M');
ini_set('max_execution_time', '0');

// load the theme functions when not in CLI mode
if (!CMS_CLI) {
	require_once PATH_INCLUDES."theme_functions.php";
} else {
	echo "Running in CLI mode...\n";
}

// load the GeoIP include module
require_once PATH_INCLUDES."geoip_include.php";

// define the array to store our progress messages in
$messages = array();

// *** update the GeoIP database ***

// download the new GeoIP zip file
display("* Downloading the GeoIP database file.");
copy('http://www.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip', '/tmp/GeoIPCountryCSV.zip');

// verify if the download succeeded
if (file_exists('/tmp/GeoIPCountryCSV.zip')) {

	// save the debug log setting, then disable it
	$db_log = $_db_log;
	$_db_log = false;
	
	// unzip the new file
	display("* Unzipping the downloaded GeoIP database file.");
	exec("`which unzip` /tmp/GeoIPCountryCSV.zip -d /tmp");
	
	if (file_exists('/tmp/GeoIPCountryWhois.csv')) {

		// drop the backup table
		display("* Deleting the old GeoIP backup table.");
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."GeoIP_backup");

		// create a temp table
		display("* Creating the new GeoIP import table.");
		$result = dbquery("CREATE TABLE ".$db_prefix."GeoIP_import (
				ip_start varchar(15) NOT NULL default '',
				ip_end varchar(15) NOT NULL default '',
				ip_start_num int(10) unsigned NOT NULL default '0',
				ip_end_num int(10) unsigned NOT NULL default '0',
				ip_code char(2) NOT NULL default '',
				ip_name varchar(50) NOT NULL default ''
			) ENGINE=MyISAM");
			
		// import the new file
		display("* Importing the CSV file into the new GeoIP import table.");
		$handle = @fopen('/tmp/GeoIPCountryWhois.csv', 'r');
		if ($handle) {
			// read the CSV file, and insert the records into the import database
			$row = 0;
			while (!feof($handle)) {
			    $csvline = fgets($handle, 4096);
			    $row++;
			    $result = dbquery("INSERT INTO ".$db_prefix."GeoIP_import VALUES(".$csvline.")");
			}
			display("* Imported ".$row." records into the GeoIP_import table.");
			fclose($handle);
			// delete the ip_name field, to make the table more compact
			display("* Remove the country name from the GeoIP table.");
			$result = dbquery("ALTER TABLE ".$db_prefix."GeoIP_import DROP ip_name");	
			// delete the GeoIP table, rename the temp table
			display("* Rename the old GeoIP table to GeoIP_backup, and the new GeoIP_import table to GeoIP.");
			$result = dbquery("RENAME TABLE ".$db_prefix."GeoIP TO ".$db_prefix."GeoIP_backup, ".$db_prefix."GeoIP_import TO ".$db_prefix."GeoIP");	

			// update the users table
			display("* Updating users with an unknown country code.");
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_cc_code = ''");
			while ($data = dbarray($result)) {
				if ($data['user_ip'] != "X") {
					$cc = GeoIP_IP2Code($data['user_ip']);
					if ($cc != "") { 
						display("  * Updating country code for user '".$data['user_name']."'.");
						$result2 = dbquery("UPDATE ".$db_prefix."users SET user_cc_code = '".$cc."' WHERE user_id = '".$data['user_id']."'");
					} else {
						display("  * No country code found for user '".$data['user_name']."'.");
					}
				}
			}

			// update the posts table
			display("* Updating posts with an unknown country code.");
			$result = dbquery("SELECT * FROM ".$db_prefix."posts WHERE post_cc = ''");
			while ($data = dbarray($result)) {
				if ($data['post_ip'] != "X") {
					$cc = GeoIP_IP2Code($data['post_ip']);
					if ($cc != "") { 
						display("  * Updating country code for post '".$data['post_id']."'.");
						$result2 = dbquery("UPDATE ".$db_prefix."posts SET post_cc = '".$cc."' WHERE post_id = '".$data['post_id']."'");
					} else {
						display("  * No country code for found  post '".$data['post_id']."'.");
					}
				}
			}

			// update the statistics ip table (if it exists)
			if (dbtable_exists($db_prefix."dlstats_ips")) {
				display("* Updating ip statistics with an unknown country code.");
				$result = dbquery("SELECT * FROM ".$db_prefix."dlstats_ips WHERE dlsi_ccode = ''");
				while ($data = dbarray($result)) {
					$cc = GeoIP_IP2Code($data['dlsi_ip']);
					if ($cc != "") { 
						display("  * Updating country code for statistics record '".$data['dlsi_id']."'.");
						$result2 = dbquery("UPDATE ".$db_prefix."dlstats_ips SET dlsi_ccode = '".$cc."' WHERE dlsi_id = '".$data['dlsi_id']."'");
					} else {
						display("  * No country code found for statistics record '".$data['dlsi_id']."', ip = ".$data['dlsi_ip'].".");
					}
				}
			}

		} else {
			display("* Failed to open the GeoIP CSV database file.");
		}

	} else {
		display("* Unzip of the latest GeoIP database failed!");
	}

} else {
	display("* Download of the latest GeoIP database failed!");
}
display(" ");
display("Update finished!");

// restore the debug log status
$_db_log = $db_log;

// delete the download and temporary files
if (file_exists('/tmp/GeoIPCountryWhois.csv')) unlink("/tmp/GeoIPCountryWhois.csv");
if (file_exists('/tmp/GeoIPCountryCSV.zip')) unlink("/tmp/GeoIPCountryCSV.zip");

// if not in CLI mode, prepare the template for display
if (!CMS_CLI) {
	// used to store template variables
	$variables = array();
	// create the html output
	$variables['html'] = "";
	foreach($messages as $message) {
		$variables['html'] .= $message."<br />"; 
	}
	
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'admin.tools.output', 'title' => "Update GeoIP Database", 'template' => '_custom_html.tpl');
	$template_variables['admin.tools.output'] = $variables;
	
	// Call the theme code to generate the output for this webpage
	require_once PATH_THEME."/theme.php";
}
?>
