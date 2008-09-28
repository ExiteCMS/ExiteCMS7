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
if (eregi("db_functions.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// Establish mySQL database connection
$_db_link = dbconnect($db_host, $db_user, $db_pass, $db_name);
if ($db_host != $user_db_host && $db_name != $user_db_name) {
	// if not, create a new database connection
	$_user_db_link = dbconnect($user_db_host, $user_db_user, $user_db_pass, $user_db_name);
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

	global $_db_last_function, $_db_debug, $_db_log, $_db_logs, $_loadstats, $settings;

	// update the query for relocated user tables
	ModUserTables($query);

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

	$result = mysql_query($query);

	$_e_loadtime = explode(" ", microtime());
	$_e_loadtime = (float)$_e_loadtime[1] + (float)$_e_loadtime[0];

	$_loadstats['querytime'] = $_loadstats['querytime'] + $_e_loadtime - $_s_loadtime;

	if (!$result) {
		if ($display || $settings['debug_querylog']) {
			echo "<pre><br />Query: ".$query."<br />";
			echo mysql_error();
			echo "</pre>";
		}
		if ($settings['debug_php_errors'] && function_exists('debug_backtrace')) _debug(debug_backtrace());
		trigger_error("A MySQL error has been detected that is not recoverable:", E_USER_ERROR);
	}

	if ($_db_log) {
		$_db_logs[] = array($query, ($_e_loadtime - $_s_loadtime)*1000);
	}

	return $result;
}

// generate an insert or update query from an array with field data
function dbupdate($table, $index, $record) {
	global $db_prefix;

	// is this an insert or an update?
	$fields = "";
	$values = "";
	if (empty($record[$index])) {
		$sql = "INSERT INTO ".$db_prefix.$table." (";
		foreach($record as $name => $value) {
			if ($name == $index) continue;
			$fields .= ($fields == "" ? "" : ", ") . $name;
			if (is_string($value)) {
				$values .= ($values == "" ? "'" : ", '").mysql_escape_string($value)."'";
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
				$fields .= "'".mysql_escape_string($value)."' ";
			} else {
				$fields .= $value . " ";
			}
		}
		$sql .= $fields . "WHERE " . $index . " = ";
		if (is_string($record[$index])) {
			$sql .= "'".mysql_escape_string($record[$index])."'";
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
	$rows = mysql_result($result, 0);
	return $rows;
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
			$result = @mysql_affected_rows($resource);
			break;
		default:
			$result = @mysql_num_rows($resource);
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

	$result = @mysql_fetch_assoc($resource);
	return $result;
}

// return a numbered array of the current row
function dbarraynum($resource) {
	global $settings;

	$result = @mysql_fetch_row($resource);
	return $result;
}

// check if a table exists, optionally passing the name of a database (if empty, use the currently selected database)
function dbtable_exists($tbl, $db='') {
	global $db_name;
	global $_db_last_function, $_db_debug, $_db_log, $_db_logs, $_loadstats;

	$tables = array();

	$_s_loadtime = explode(" ", microtime());
	$_s_loadtime = $_s_loadtime[1] + $_s_loadtime[0];
	$_loadstats['querytime'] -= $_s_loadtime;

	if (!empty($db) && $db != $db_name) {
		$db_select = @mysql_select_db($db);
		if (!$db_select) return false;
	}
	$result = @mysql_query("SHOW TABLES");
	while ($data = @mysql_fetch_array($result)) { 
		$tables[] = $data[0]; 
	}
	@mysql_free_result($result);
	$db_select = @mysql_select_db($db_name );
	
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
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass, true);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br />".mysql_errno()." : ".mysql_error()."</div>");
	} else {
		$db_select = @mysql_select_db($db_name);
		if (!$db_select) {
			die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br />".mysql_errno()." : ".mysql_error()."</div>");
		}
	}
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

?>
