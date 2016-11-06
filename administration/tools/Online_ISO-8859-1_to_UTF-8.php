<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2009 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id:: ISO-8859-1_to_UTF-8.php 2033 2008-11-15 19:51:44Z webmaster   $|
+----------------------------------------------------------------------+
| Last modified by $Author:: webmaster                                $|
| Revision number $Rev:: 2033                                         $|
+---------------------------------------------------------------------*/
require_once dirname(__FILE__)."/../../includes/core_functions.php";

// check for the proper admin access rights
if (!CMS_CLI && !checkrights("DB") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// give this module some memory and execution time
ini_set('memory_limit', '64M');
ini_set('max_execution_time', '0');

// load the theme functions when not in CLI mode
if (!CMS_CLI) {
	require_once PATH_INCLUDES."theme_functions.php";
} else {
	echo "Running in CLI mode...\n";
}

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

function is_utf8_string($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}

// connect to the database engine and select the database
function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysqli_connect($db_host, $db_user, $db_pass);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br />".mysqli_connect_errno()." : ".mysqli_connect_error()."</div>");
	} else {
		$db_select = mysqli_select_db($db_connect, $db_name);
		if (!$db_select) {
			die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br />".mysqli_errno($db_connect)." : ".mysqli_error($db_connect)."</div>");
		}
	}
	// switch the connection to utf8
	mysqli_query($db_connect, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	return $db_connect;
}

// MySQL database functions
function dbquery_1($query, $display=false) {

	global $_db_link_1, $_user_db_link, $_db_last_function, $_db_debug, $_db_log, $_db_logs, $_loadstats, $settings;

	if ($_db_debug) {
		echo "<pre><br />Query: ".$query."<br /></pre>";
	}
	$query_words = explode(" ", $query);
	$_db_last_function = strtoupper(trim($query_words[0]));
	$_loadstats['queries']++;
	switch ($_db_last_function) {
		case "SELECT":
			$_loadstats['selects']++;
			break;
		case "INSERT":
			$_loadstats['inserts']++;
			break;
		case "DELETE":
			$_loadstats['deletes']++;
			break;
		case "UPDATE":
			$_loadstats['updates']++;
			break;
		default:
			$_loadstats['others']++;
	}

	$_s_loadtime = explode(" ", microtime());
	$_s_loadtime = (float)$_s_loadtime[1] + (float)$_s_loadtime[0];

	$result = mysqli_real_query($_db_link_1, iconv("ISO-8859-1", "UTF-8", $query));

	$_e_loadtime = explode(" ", microtime());
	$_e_loadtime = (float)$_e_loadtime[1] + (float)$_e_loadtime[0];

	$_loadstats['querytime'] = $_loadstats['querytime'] + $_e_loadtime - $_s_loadtime;

	// bail out if an error occurred and we're NOT in CLI mode!
	if ((defined('CMS_CLI') && !CMS_CLI) && $result === false) {
		if ($display || $_db_log) {
			echo "<pre><br />Query: ".$query."<br />";
			echo mysqli_error($_db_link_1);
			echo "</pre>";
		}
		if ($settings['debug_php_errors'] == 1 && function_exists('debug_backtrace')) _debug(debug_backtrace());
		error_log("MSG: ".mysqli_error($_db_link_1));
		error_log("QRY: ".$query);
		die("A database error has been detected that is not recoverable.<br />The error has been logged and an administrator has been notified.");
	}

	if ($_db_log) {
		$_db_logs[] = array($query, ($_e_loadtime - $_s_loadtime)*1000);
	}

	return $result;
}

// connect to the database engine and select the database
function dbconnect_2($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysqli_connect($db_host, $db_user, $db_pass);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br />".mysqli_connect_errno()." : ".mysqli_connect_error()."</div>");
	} else {
		$db_select = mysqli_select_db($db_connect, $db_name);
		if (!$db_select) {
			die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br />".mysqli_errno($db_connect)." : ".mysqli_error($db_connect)."</div>");
		}
	}
	// switch the connection to utf8
	mysqli_query($db_connect, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	return $db_connect;
}

// MySQL database functions
function dbquery_2($query, $display=false) {

	global $_db_link_2, $_user_db_link, $_db_last_function, $_db_debug, $_db_log, $_db_logs, $_loadstats, $settings;

	if ($_db_debug) {
		echo "<pre><br />Query: ".$query."<br /></pre>";
	}
	$query_words = explode(" ", $query);
	$_db_last_function = strtoupper(trim($query_words[0]));
	$_loadstats['queries']++;
	switch ($_db_last_function) {
		case "SELECT":
			$_loadstats['selects']++;
			break;
		case "INSERT":
			$_loadstats['inserts']++;
			break;
		case "DELETE":
			$_loadstats['deletes']++;
			break;
		case "UPDATE":
			$_loadstats['updates']++;
			break;
		default:
			$_loadstats['others']++;
	}

	$_s_loadtime = explode(" ", microtime());
	$_s_loadtime = (float)$_s_loadtime[1] + (float)$_s_loadtime[0];

	$result = mysqli_real_query($_db_link_2, $query);

	$_e_loadtime = explode(" ", microtime());
	$_e_loadtime = (float)$_e_loadtime[1] + (float)$_e_loadtime[0];

	$_loadstats['querytime'] = $_loadstats['querytime'] + $_e_loadtime - $_s_loadtime;

	// bail out if an error occurred and we're NOT in CLI mode!
	if ((defined('CMS_CLI') && !CMS_CLI) && $result === false) {
		if ($display || $_db_log) {
			echo "<pre><br />Query: ".$query."<br />";
			echo mysqli_error($_db_link_2);
			echo "</pre>";
		}
		if ($settings['debug_php_errors'] == 1 && function_exists('debug_backtrace')) _debug(debug_backtrace());
		error_log("MSG: ".mysqli_error($_db_link_2));
		error_log("QRY: ".$query);
		die("A database error has been detected that is not recoverable.<br />The error has been logged and an administrator has been notified.");
	}

	if ($_db_log) {
		$_db_logs[] = array($query, ($_e_loadtime - $_s_loadtime)*1000);
	}

	return $result;
}

function getPrimaryKeyOf($table) {
	global $_db_link_1;

	$pk = Array();

	$sql = 'SHOW KEYS FROM `'.$table.'`';
	$res = mysqli_query($_db_link_1, $sql) or die(mysqli_error($_db_link_1));
	while ($row = mysqli_fetch_assoc($res)) {
	if ($row['Key_name']=='PRIMARY')
	  $pk[] = $row['Column_name'];
	}
	return $pk;
}


function convert_table($tablename) {
	global $_db_link_1, $_db_link_2;

	display("*** Converting table: ".$tablename);

	// determine the start time
	$_s_loadtime = explode(" ", microtime());
	$_s_loadtime = (float)$_s_loadtime[1] + (float)$_s_loadtime[0];

	// fetch the primary key for this table, we need it to construct the update query
	$primary_keys = getPrimaryKeyOf($tablename);

	// does the table have primary keys?
	if (count($primary_keys)) {
		// fetch all records using the old-style connect
		$result = dbquery_2("SELECT * FROM ".$tablename);

		// get the number of records fetched
		$records = dbrows($result);

		// loop through the result
		while ($data = dbarray($result)) {
			// create the update query
			$sql = "UPDATE ".$tablename." SET";
			// add all fields
			$sep = " ";
			foreach ($data as $field => $value) {
				// no need to update primary keys
				if (! in_array($field, $primary_keys) ) {
					$sql .= $sep . "`" . $field . "` = '" . mysqli_real_escape_string($_db_link_1, $value). "'";
					$sep = ", ";
				}
			}
			// add the where clause
			$sql .= " WHERE";
			// add all key fields
			$sep = " ";
			foreach ($primary_keys as $field) {
				$sql .= $sep . "`" . $field . "` = '" . mysqli_real_escape_string($_db_link_1, $data[$field]). "'";
				$sep = " AND ";
			}

			// run the update query
			mysqli_real_query($_db_link_1, $sql);
		}

	} else {
		// fetch all records using the old-style connect
		$result = dbquery_2("SELECT * FROM ".$tablename);

		// get the number of records fetched
		$records = dbrows($result);

		// loop through the result
		while ($data = dbarray($result)) {

			// create the delete query
			$sql = "DELETE FROM ".$tablename." WHERE";
			// add all fields
			$sep = " ";
			foreach ($data as $field => $value) {
				$sql .= $sep . "`" . $field . "` = '" . mysqli_real_escape_string($_db_link_1, $value). "'";
				$sep = " AND ";
			}

			// delete the record
			mysqli_real_query($_db_link_2, $sql);

			// create the new insert query
			$sql = "INSERT INTO ".$tablename." (";
			// add all fields
			$sep = "";
			foreach ($data as $field => $value) {
				$sql .= $sep . "`" . $field . "` = '";
				$sep = ", ";
			}
			$sql .= ") VALUES (";
			// add all values
			$sep = " ";
			foreach ($data as $field => $value) {
				$sql .= $sep . "'" . mysqli_real_escape_string($_db_link_1, $value). "'";
				$sep = ", ";
			}
			$sql .= ")";

			// run the update query
			mysqli_real_query($_db_link_1, $sql);
		}
	}

	// restart the end time
	$_e_loadtime = explode(" ", microtime());
	$_e_loadtime = (float)$_e_loadtime[1] + (float)$_e_loadtime[0];

	if ($records > -1) {
		display(sprintf("       finished: converted %d records in %01.2f minutes", $records, ($_e_loadtime - $_s_loadtime) /60));
	}
	display("");
}

/*---------------------------------------------------+
| main code                                          |
+----------------------------------------------------*/

// make sure our link identifier is global
global $_db_link_1, $_db_link_2;

// create an new-style connection to the database
$_db_link_1 = dbconnect_1($db_host, $db_user, $db_pass, $db_name);

// create an old-style connection to the database
$_db_link_2 = dbconnect_2($db_host, $db_user, $db_pass, $db_name);

// define the table skip list
$skip_list = array();
$skip_list[] = $db_prefix."admin";
$skip_list[] = $db_prefix."bad_login";
$skip_list[] = $db_prefix."dlstats_counters";
$skip_list[] = $db_prefix."dlstats_fcache";
$skip_list[] = $db_prefix."dlstats_files";
$skip_list[] = $db_prefix."dlstats_file_ips";
$skip_list[] = $db_prefix."dlstats_ips";
$skip_list[] = $db_prefix."flood_control";
$skip_list[] = $db_prefix."forum_poll_settings";
$skip_list[] = $db_prefix."forum_poll_votes";
$skip_list[] = $db_prefix."GeoIP";
$skip_list[] = $db_prefix."GeoIP_backup";
$skip_list[] = $db_prefix."GeoIP_exceptions";
$skip_list[] = $db_prefix."M2F_config";
$skip_list[] = $db_prefix."M2F_forums";
$skip_list[] = $db_prefix."M2F_subscriptions";
$skip_list[] = $db_prefix."news_frontpage";
$skip_list[] = $db_prefix."pm_config";
$skip_list[] = $db_prefix."pm_index";
$skip_list[] = $db_prefix."posts_unread";
$skip_list[] = $db_prefix."redirects";
$skip_list[] = $db_prefix."sessions";
$skip_list[] = $db_prefix."threads_read";
$skip_list[] = $db_prefix."thread_notify";

// make a list of tables in the current database
$table_list=array();
$result=dbquery_1("SHOW tables");
while($row=dbarraynum($result)){
	$table_list[] = array('name' => $row[0], 'selected' => preg_match("/^".$db_prefix."/i",$row[0]) );
}

// determine the start time
$_s_loadtime = explode(" ", microtime());
$_s_loadtime = (float)$_s_loadtime[1] + (float)$_s_loadtime[0];

// loop though the tables found
foreach ($table_list as $table) {

	// check the skiplist first. These tables don't contain text
	if (in_array($table['name'], $skip_list)) {
		display(sprintf("*** Table %s is in the skip list, skipping conversion of this table", $table['name']));
		display("");
	} else {
		// skip all non-CMS tables in this database
		if ($table['selected'] == 1) {

			convert_table($table['name']);
		}
	}
}

// determine the end time
$_e_loadtime = explode(" ", microtime());
$_e_loadtime = (float)$_e_loadtime[1] + (float)$_e_loadtime[0];

display(str_repeat('-', 55));
display(sprintf("finished the conversion process in %01.2f minutes", ($_e_loadtime - $_s_loadtime) /60));
display(str_repeat('-', 55));
display("");


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
	$template_panels[] = array('type' => 'body', 'name' => 'admin.tools.output', 'title' => "Online conversion to UTF-8", 'template' => '_custom_html.tpl');
	$template_variables['admin.tools.output'] = $variables;

	// Call the theme code to generate the output for this webpage
	require_once PATH_THEME."/theme.php";
}
?>
