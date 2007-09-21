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
if (eregi("db_functions.php", $_SERVER['PHP_SELF']) || !defined('ExiteCMS_INIT')) die();

// Establish mySQL database connection
$_db_link = dbconnect($db_host, $db_user, $db_pass, $db_name);
if ($db_host != $user_db_host && $db_name != $user_db_name) {
	// if not, create a new database connection
	$_user_db_link = dbconnect($user_db_host, $user_db_user, $user_db_pass, $user_db_name);
}

// MySQL global variables
$_db_last_function = "";
$_db_debug = false;
$_db_log = false;
$_db_logs = array();

// Check if this is a query on user tables
function ModUserTables(&$query) {
	global $db_prefix, $db_name, $user_db_prefix, $user_db_name;

	$isUserQuery = false;
		
	// tables with user information
	$usertables = array("users", "new_users", "user_groups", "bad_login", "online", "blacklist");
	
	// check if this is a query on a user table
	foreach($usertables as $usertable) {
		if(strpos($query, $db_prefix.$usertable)) {
			$isUserQuery = true;
			$query = str_replace($db_prefix.$usertable, $user_db_name.".".$user_db_prefix.$usertable, $query);
		}
	}
	// prefix all other tables with the database name as well
	$query = str_replace(" ".$db_prefix, " ".$db_name.".".$db_prefix, $query);
	
	// and return the result
	return $isUserQuery;
}

// MySQL database functions
function dbquery($query, $display=true) {

	global $_db_last_function, $_db_debug, $_db_log, $_db_logs, $_loadstats;

	// update the query for relocated user tables
	ModUserTables($query);

	$_loadtime = explode(" ", microtime());
	$_loadtime = $_loadtime[1] + $_loadtime[0];
	$_loadstats['querytime'] -= $_loadtime;

	if ($_db_debug) {
		echo "<pre><br />Query: ".$query."<br /></pre>";
	}
	if ($_db_log) {
		$_db_logs[] = $query;
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

	$result = @mysql_query($query);
	if (!$result) {
		if ($display) {
			echo "<pre><br />Query: ".$query."<br />";
			echo mysql_error();
			echo "</pre>";
		}
	}
	$_loadtime = explode(" ", microtime());
	$_loadtime = $_loadtime[1] + $_loadtime[0];
	$_loadstats['querytime'] += $_loadtime;
	return $result;
}

function dbcount($field,$table,$conditions="") {
	global $db_prefix, $_db_last_function, $_db_debug, $_db_log, $_db_logs;

	$cond = ($conditions ? " WHERE ".$conditions : "");
	$sql = "SELECT Count".$field." FROM ".$db_prefix.$table.$cond;
	
	$result = dbquery($sql, false);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		$rows = mysql_result($result, 0);
		return $rows;
	}
}

function dbresult($resource, $row) {

	$result = @mysql_result($resource, $row);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

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

function dbarray($resource) {
	$result = @mysql_fetch_assoc($resource);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbarraynum($resource) {
	$result = @mysql_fetch_row($resource);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass, true);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br />".mysql_errno()." : ".mysql_error()."</div>");
	} elseif (!$db_select) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br />".mysql_errno()." : ".mysql_error()."</div>");
	}
	return $db_connect;
}

// convert MySQL date to a formatted date (use subheaderdate setting) by WanWizard
function showMySQLdate($date, $empty="", $error="") {
	global $locale, $settings;

	// test for invalid formats, need regex here!
	if (strlen($date) != 19)
		return $error;
	// test for empty dates
	if ($date == "0000-00-00 00:00:00" or $date == "")
		return $empty;

	$year=substr($date,0,4); $month=substr($date,5,2); $day=substr($date,8,2);
	$hour=substr($date,11,2); $minute=substr($date,14,2); $second=substr($date,17,2);
	return ucwords(showdate($settings['subheaderdate'], mktime($hour,$minute,$second,$month,$day,$year)));
}

?>