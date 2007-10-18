<?php 
require_once dirname(__FILE__)."/../../includes/core_functions.php";

// webmaster or CGI tool only!
if (isset($_SERVER['SERVER_SOFTWARE']) && (!isset($userdata) || $userdata['user_id'] != 1)) fallback(BASEDIR."index.php");

if (isset($_SERVER['SERVER_SOFTWARE'])) echo "<html><head></head><body><pre>";

// name of the dbsetup include file
$dbsetup_include = PATH_INCLUDES."dbsetup_include.php";

// variable to store the generated config
$config = "";

// table exclude_list
$excluded_tables = array();
$excluded_tables[] = $db_prefix."0_varlog";
$excluded_tables[] = $db_prefix."newsletters";
$excluded_tables[] = $db_prefix."donations";
$excluded_tables[] = $db_prefix."M2F_forums";
$excluded_tables[] = $db_prefix."M2F_config";
$excluded_tables[] = $db_prefix."M2F_status";
$excluded_tables[] = $db_prefix."M2F_subscriptions";
$excluded_tables[] = $db_prefix."dls_mapping";
$excluded_tables[] = $db_prefix."dls_statistics";
$excluded_tables[] = $db_prefix."wiki_acls";
$excluded_tables[] = $db_prefix."wiki_comments";
$excluded_tables[] = $db_prefix."wiki_links";
$excluded_tables[] = $db_prefix."wiki_pages";
$excluded_tables[] = $db_prefix."wiki_referrers";
$excluded_tables[] = $db_prefix."wiki_referrer_blacklist";
$excluded_tables[] = $db_prefix."wiki_users";
$excluded_tables[] = $db_prefix."wiki_images";

// get the list of fusion tables from the database
$table_list=array();
$result=dbquery("SHOW tables");
while ($data = dbarraynum($result)) {
	// only want the fusion tables
	if (substr($data[0], 0 , strlen($db_prefix)) == $db_prefix) {
		if (!in_array($data[0], $excluded_tables)) {
			$table_list[] = $data[0];
		}
	}
}

ob_start();
@ob_implicit_flush(0);
$crlf = "\n";

$config .= "<?php".$crlf;
$config .= "//----------------------------------------------------------".$crlf;
$config .= "// ExiteCMS file : dbsetup_include.php".$crlf;
$config .= "// Date generated  : `".date("d/m/Y H:i")."`".$crlf;
$config .= "//----------------------------------------------------------".$crlf;
$config .= $crlf."define('CMS_VERSION', '".$settings['version']."');".$crlf;
$config .= "define('CMS_REVISION', '".$settings['revision']."');".$crlf;
$config .= $crlf."if (\$step == 1) {".$crlf;
$config .= $crlf."$"."fail = ".'"0"'.";".$crlf;
$config .= "$"."failed = array();".$crlf;
dbquery('SET SQL_QUOTE_SHOW_CREATE=1');
foreach($table_list as $table){
	@set_time_limit(1200);
	$basetable = substr($table, strlen($db_prefix), strlen($table));
	$config .= $crlf."//".$crlf."// Code to create table `".$basetable."`".$crlf."//".$crlf;
	$config .= '$'.'result = dbquery("DROP TABLE IF EXISTS ".'.'$'.'db_prefix."'.$basetable.'");'.$crlf;
	$config .= '$'.'result = dbquery("CREATE TABLE IF NOT EXISTS ".'.'$'.'db_prefix."'.$basetable.' ';
	$row=dbarraynum(dbquery("SHOW CREATE TABLE $table"));
	// strip collating sequence and autonumber information
	while (strpos($row[1], 'collate') !== false) {
		$s = strpos($row[1], 'collate');
		$e = strpos(substr($row[1], $s+8, strlen($row[1])), ' ');
		$row[1] = substr($row[1],0,$s).substr($row[1], $s+$e+9, strlen($row[1]));
	}
	$s = strpos($row[1], ') ENGINE');
	$row[1] = substr($row[1],0,$s+1).' ENGINE=MYISAM';
	$config .= strstr($row[1], '(').';");'.$crlf;
	$result=dbquery("SELECT * FROM $table");
	if($result&&dbrows($result)){
		$column_list="";
		$num_fields=mysql_num_fields($result);
		for($i=0;$i<$num_fields;$i++){
			$column_list.=(($column_list!="")?", ":"")."`".mysql_field_name($result,$i)."`";
		}
	}
	$config .= 'if (!'.'$'.'result) '.'{'.$crlf;
	$config .= '	$'.'fail = "1";'.$crlf;
	$config .= '	$'.'failed[] = "'.$basetable.' : ".mysql_error();'.$crlf;
	$config .= '}'.$crlf;
}
$config .= $crlf."}".$crlf;
$config .= "//----------------------------------------------------------".$crlf;
$config .= "?>";

// write the file
$handle = fopen($dbsetup_include,"w");
if (!fwrite($handle, $config)) {
	echo "Unable to write to ".$dbsetup_include,'<br />Please check if your webserver has the rights to write to the include directory!';
	fclose($handle);
} else {
	echo $dbsetup_include,' succesfully created.<br /><br /><pre>'.$config;
	chmod($dbsetup_include, 0770);
	fclose($handle);
}
?>