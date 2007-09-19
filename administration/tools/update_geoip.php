<?php 
require_once dirname(__FILE__)."/../../maincore.php";

// webmaster or CGI tool only!
if ((!iMEMBER || $userdata['user_id'] != 1) && isset($_SERVER['SERVER_SOFTWARE'])) fallback(BASEDIR."index.php");

require_once PATH_INCLUDES."geoip_include.php";

if (isset($_SERVER['SERVER_SOFTWARE'])) echo "<html><head></head><body><pre>";

// download the new GeoIP zip file
echo "* Downloading the GeoIP database file...\n";
copy('http://www.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip', '/tmp/GeoIPCountryCSV.zip');

// verify if the download succeeded
if (file_exists('/tmp/GeoIPCountryCSV.zip')) {
	
	// unzip the new file
	echo "* Unzipping the downloaded GeoIP database file...\n";
	exec("`which unzip` /tmp/GeoIPCountryCSV.zip -d /tmp");
	
	if (file_exists('/tmp/GeoIPCountryWhois.csv')) {

		// drop the backup table
		echo "* Deleting the old GeoIP backup table...\n";
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."GeoIP_backup");

		// create a temp table
		echo "* Creating the new GeoIP import table...\n";
		$result = dbquery("CREATE TABLE ".$db_prefix."GeoIP_import (
				ip_start varchar(15) NOT NULL default '',
				ip_end varchar(15) NOT NULL default '',
				ip_start_num int(10) unsigned NOT NULL default '0',
				ip_end_num int(10) unsigned NOT NULL default '0',
				ip_code char(2) NOT NULL default '',
				ip_name varchar(50) NOT NULL default ''
			) ENGINE=MyISAM");
			
		// import the new file
		echo "* Importing the CSV file into the new GeoIP import table...\n";
		$handle = @fopen('/tmp/GeoIPCountryWhois.csv', 'r');
		if ($handle) {
			// read the CSV file, and insert the records into the import database
			$row = 0;
			while (!feof($handle)) {
			    $csvline = fgets($handle, 4096);
			    $row++;
			    $result = dbquery("INSERT INTO ".$db_prefix."GeoIP_import VALUES(".$csvline.")");
			}
			echo "* Imported ", $row, " records into the GeoIP_import table...\n";
			fclose($handle);
			// delete the GeoIP table, rename the temp table
			echo "* Rename the old GeoIP table to GeoIP_backup, and the new GeoIP_import table to GeoIP...\n";
			$result = dbquery("RENAME TABLE ".$db_prefix."GeoIP TO ".$db_prefix."GeoIP_backup, ".$db_prefix."GeoIP_import TO ".$db_prefix."GeoIP");	

			// update the users table
			echo "* Updating users with an unknown country code...\n";
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_cc_code = ''");
			while ($data = dbarray($result)) {
				if ($data['user_ip'] != "X") {
					$cc = GeoIP_IP2Code($data['user_ip']);
					if ($cc != "") { 
						echo "  * Updating country code for user '", $data['user_name'], "'...\n";
						$result2 = dbquery("UPDATE ".$db_prefix."users SET user_cc_code = '".$cc."' WHERE user_id = '".$data['user_id']."'");
					}
				}
			}
			// update the statistics table
			echo "* Updating download statistics with an unknown country code...\n";
			$result = dbquery("SELECT * FROM ".$db_prefix."dls_statistics WHERE ds_cc = ''");
			while ($data = dbarray($result)) {
				$cc = GeoIP_IP2Code($data['ds_ip']);
				if ($cc != "") { 
					echo "  * Updating country code for statistics record '", $data['ds_id'], "'...\n";
					$result2 = dbquery("UPDATE ".$db_prefix."dls_statistics SET ds_cc = '".$cc."' WHERE ds_id = '".$data['ds_id']."'");
				}
			}

		} else {
			echo "* Failed to open the GeoIP CSV database file...\n";
		}

	} else {
		echo "* Unzip of the latest GeoIP database failed!\n";
	}

} else {
	echo "* Download of the latest GeoIP database failed!\n";
}

if (file_exists('/tmp/GeoIPCountryWhois.csv')) unlink("/tmp/GeoIPCountryWhois.csv");
if (file_exists('/tmp/GeoIPCountryCSV.zip')) unlink("/tmp/GeoIPCountryCSV.zip");

if (isset($_SERVER['SERVER_SOFTWARE'])) echo "</pre></body></html>";
?>