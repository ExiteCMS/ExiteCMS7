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

// Establish mySQL database connection
$_db_link = dbconnect($db_host, $db_user, $db_pass, $db_name);
if ($db_host != $user_db_host && $db_name != $user_db_name) {
	// if not, create a new database connection
	$_user_db_link = dbconnect($user_db_host, $user_db_user, $user_db_pass, $user_db_name);
} else {
	$_user_db_link = $_db_link;
}

// database global variables
$_db_debug = false;
$_db_log = true;
$_db_logs = array();
$_db_last_function = "";

// Check if this is a query on user tables
function ModUserTables(&$query) {
	global $db_prefix, $db_name, $user_db_prefix, $user_db_name;

	$isUserQuery = false;

	// only do this if there's a prefix defined
	if (!empty($db_prefix)) {
		// tables with user information
		$usertables = array("users", "new_users", "user_groups", "bad_login", "online", "blacklist");

		// check if this is a query on a user table
		foreach($usertables as $usertable) {
			if(strpos($query, " ".$db_prefix.$usertable)) {
				$isUserQuery = true;
				$query = str_replace(" ".$db_prefix.$usertable, " ".$user_db_name.".".$user_db_prefix.$usertable, $query);
				$query = str_replace("=".$db_prefix.$usertable, "=".$user_db_name.".".$user_db_prefix.$usertable, $query);
			}
		}
		// prefix all other tables with the database name as well
		$query = str_replace(" ".$db_prefix, " ".$db_name.".".$db_prefix, $query);
	}

	// and return the result
	return $isUserQuery;
}

// MySQL database functions
function dbquery($query, $display=false) {

	global $_db_link, $_user_db_link, $_db_last_function, $_db_debug, $_db_log, $_db_logs, $_loadstats, $settings;

	// update the query for relocated user tables
	$isUserQuery = ModUserTables($query);

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

	if ($isUserQuery) {
		$result = mysqli_query($_user_db_link, $query);
	} else {
		$result = mysqli_query($_db_link, $query);
	}

	$_e_loadtime = explode(" ", microtime());
	$_e_loadtime = (float)$_e_loadtime[1] + (float)$_e_loadtime[0];

	$_loadstats['querytime'] = $_loadstats['querytime'] + $_e_loadtime - $_s_loadtime;

	// bail out if an error occurred and we're NOT in CLI mode!
	if ((defined('CMS_CLI') && !CMS_CLI) && $result === false) {
		if ($display || $_db_log) {
			echo "<pre><br />Query: ".$query."<br />";
			echo $isUserQuery ? mysqli_error($_user_db_link) : mysqli_error($_db_link);
			echo "</pre>";
		}
		if ($settings['debug_php_errors'] == 1 && function_exists('debug_backtrace')) _debug(debug_backtrace());
		error_log("MSG: ".($isUserQuery ? mysqli_error($_user_db_link) : mysqli_error($_db_link)));
		error_log("QRY: ".$query);
		die("A database error has been detected that is not recoverable.<br />The error has been logged and an administrator has been notified.");
	}

	if ($_db_log) {
		$_db_logs[] = array($query, ($_e_loadtime - $_s_loadtime)*1000);
	}

	return $result;
}

// generate an insert or update query from an array with field data
function dbupdate($table, $index, $record) {
	global $db_prefix, $_db_link;

	// is this an insert or an update?
	$fields = "";
	$values = "";
	if (empty($record[$index])) {
		$sql = "INSERT INTO ".$db_prefix.$table." (";
		foreach($record as $name => $value) {
			if ($name == $index) continue;
			$fields .= ($fields == "" ? "" : ", ") . $name;
			if (is_string($value)) {
				$values .= ($values == "" ? "'" : ", '").mysqli_real_escape_string($_db_link, $value)."'";
			} else {
				$values .= ($values == "" ? "" : ", ").$value;
			}
		}
		$sql .= $fields . ") VALUES (".$values.")";
	} else {
		$sql = "UPDATE ".$db_prefix.$table." SET ";
		foreach($record as $name => $value) {
			if ($name == $index) continue;
			$fields .= ($fields == "" ? "" : ", ") . $name . " = ";
			if (is_string($value)) {
				$fields .= "'".mysqli_escape_string($_db_link, $value)."' ";
			} else {
				$fields .= $value . " ";
			}
		}
		$sql .= $fields . "WHERE " . $index . " = ";
		if (is_string($record[$index])) {
			$sql .= "'".mysqli_real_escape_string($_db_link, $record[$index])."'";
		} else {
			$sql .= $record[$index];
		}
	}

	return dbquery($sql);
}

// DEPRECIATED. Function is replaced by the more generic dbfunction(), and will be removed in a later release of ExiteCMS
function dbcount($field,$table,$conditions="") {

	return dbfunction("COUNT".$field, $table, $conditions);
}

// perform a function on a table (COUNT, MAX, MIN, etc) and return the value, based on the field and conditions specified
function dbfunction($field,$table,$conditions="") {
	global $db_prefix, $_db_last_function, $_db_debug, $_db_log, $_db_logs, $settings;

	$cond = ($conditions ? " WHERE ".$conditions : "");
	$sql = "SELECT ".$field." FROM ".(strpos($table, ".") ? $table : $db_prefix.$table).$cond;

	$result = dbquery($sql, false);
	$rows = mysqli_fetch_array($result);
	return $rows[0];
}

// DEPRECIATED. Function definition left here to capture code that needs to be modified
function dbresult($resource, $row) {
	die("ExiteCMS: The function 'dbresult' is depreciated starting version 7.2. Please rewrite your code using dbfunction()!");
}

// return the number of rows affected in the most recent query
function dbrows($resource) {
	global $_db_last_function, $_db_debug;

	switch ($_db_last_function) {
		case "INSERT":
		case "UPDATE":
		case "DELETE":
			$result = mysqli_affected_rows($resource);
			break;
		default:
			$result = mysqli_num_rows($resource);
			break;
	}

	if ($_db_debug) {
		echo "<pre><br />Last query statement: ".$_db_last_function.", rows affected were: ".$result."<br /></pre>";
	}
	return $result;
}

// return a assoc array of the current row
function dbarray($resource) {
	global $settings;

	$result = mysqli_fetch_assoc($resource);
	return $result;
}

// return a numbered array of the current row
function dbarraynum($resource) {
	global $settings;

	$result = mysqli_fetch_row($resource);
	return $result;
}

// check if a table exists, optionally passing the name of a database (if empty, use the currently selected database)
function dbtable_exists($tbl, $db='') {
	global $db_name;
	global $_db_link, $_db_last_function, $_db_debug, $_db_log, $_db_logs, $_loadstats;

	$tables = array();

	$_s_loadtime = explode(" ", microtime());
	$_s_loadtime = $_s_loadtime[1] + $_s_loadtime[0];
	$_loadstats['querytime'] -= $_s_loadtime;

	if (!empty($db) && $db != $db_name) {
		$db_select = @mysqli_select_db($_db_link, $db);
		if (!$db_select) return false;
	}
	$result = mysqli_query($_db_link, "SHOW TABLES");
	while ($data = mysqli_fetch_array($result)) {
		$tables[] = $data[0];
	}
	mysqli_free_result($result);
	$db_select = mysqli_select_db($_db_link, $db_name );

	$_loadstats['queries']++;
	$_loadstats['others']++;
	$_e_loadtime = explode(" ", microtime());
	$_e_loadtime = $_e_loadtime[1] + $_e_loadtime[0];
	$_loadstats['querytime'] += $_e_loadtime;
	if ($_db_log) {
		$_db_logs[] = array("SHOW TABLES", ($_e_loadtime - $_s_loadtime)*1000);
	}

	if (in_array($tbl, $tables)) {
		return true;
	} else {
		return false;
	}
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

// convert MySQL date to a formatted date (default format is subheaderdate)
function showMySQLdate($date, $empty="", $error="", $format="subheaderdate") {
	global $locale, $settings;

	// test for invalid formats, need regex here!
	if (strlen($date) != 19)
		return $error;
	// test for empty dates
	if ($date == "0000-00-00 00:00:00" or $date == "")
		return $empty;

	$year=substr($date,0,4); $month=substr($date,5,2); $day=substr($date,8,2);
	$hour=substr($date,11,2); $minute=substr($date,14,2); $second=substr($date,17,2);
	return ucwords(showdate((isset($settings[$format])?$settings[$format]:$settings['subheaderdate']), mktime($hour,$minute,$second,$month,$day,$year)));
}

function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}

function mysqli_field_type($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->type : null;
}
?>
