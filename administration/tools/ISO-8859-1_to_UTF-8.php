<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------+
| Some portions developed by CrappoMan               |
| email: simonpatterson@dsl.pipex.com                |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../../includes/core_functions.php";

// check for the proper admin access rights
if (!CMS_CLI && !checkrights("DB") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

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

// temp storage for template variables
$variables = array();

// make sure the script doesn't time out
set_time_limit(0);

// and give it plenty of memory!
ini_set('memory_limit', '512M');

// load the theme functions when not in CLI mode
if (!CMS_CLI) {
	require_once PATH_INCLUDES."theme_functions.php";
} else {
	while (@ob_end_flush());
	display("Running in CLI mode...\n");
	if (file_exists(PATH_ADMIN."db_backups/".$argv[1])) {
		$_POST['file'] = $argv[1];
		$_POST['btn_do_restore'] = 1;
		$_POST['user_password'] = "dummy";
		$userdata['user_password'] = md5(md5("dummy"));
		if (isset($argv[2])) {
			$restore_tblpre = $argv[2];
		}
	} else {
		die("Missing or incorrect commandline parameters\n");
	}
}

// load the locale for this module
locale_load("admin.db-backup");

// make sure the parameter is valid
if (!isset($action)) $action = "";

// a restore is requested
if (isset($_POST['btn_do_restore'])) {
	// validate the users password against the one entered (extra security precaution)
	$user_password = md5(md5($_POST['user_password']));
	$fd = @fopen(PATH_ADMIN."db_backups/".$_POST['file'], "r");
	if (!$fd) {
		$variables['error'] = 5;
	} elseif ($user_password != $userdata['user_password']) {
		$variables['error'] = 3;
	} else {
		// check the parameters
		if (isset($_POST['list_tbl']) && is_array($_POST['list_tbl'])) $list_tbl = $_POST['list_tbl']; else $list_tbl = array();
		$tbl_count = count($list_tbl);
		if (isset($_POST['list_ins']) && is_array($_POST['list_ins'])) $list_ins = $_POST['list_ins']; else $list_ins = array();
		$ins_count = count($list_ins);
		// read the header
		$result = array();
		for ($i = 0; $i < 7; $i++) {
			$result[] = fgets($fd);
		}
		if((preg_match("/# Database Name: `(.+?)`/i", $result[2], $tmp1)<>0)&&(preg_match("/# Table Prefix: `(.+?)`/i", $result[3], $tmp2)<>0)) {
			$inf_dbname = $tmp1[1];
			display("Processing database ".$inf_dbname);
			$inf_tblpre = $tmp2[1];
			while (!feof($fd)) {
				$line = trim(fgets($fd));
				while (!feof($fd) && substr($line, 0, 1) != "#" && substr($line, -1) != ";") {
					$line .= trim(fgets($fd));
				};
				if ($tbl_count > 0 || $ins_count > 0) {
					if (preg_match("/^DROP TABLE IF EXISTS `(.*?)`/im",$line,$tmp) <> 0) {
						$tbl = $tmp[1];
						display("* DROP TABLE ".$tbl);
						if (in_array($tbl, $list_tbl)) {
							$sql = preg_replace("/^DROP TABLE IF EXISTS `$inf_tblpre(.*?)`/im","DROP TABLE IF EXISTS `$restore_tblpre\\1`",$line);
							mysql_unbuffered_query($sql);
						}
					}
					if (preg_match("/^CREATE TABLE `(.*?)`/im",$line,$tmp) <> 0) {
						$tbl = $tmp[1];
						display("* CREATE TABLE ".$tbl);
						if (in_array($tbl, $list_tbl)) {
							$sql = preg_replace("/^CREATE TABLE `$inf_tblpre(.*?)`/im","CREATE TABLE `$restore_tblpre\\1`",$line);
							$sql = preg_replace("/(.*?)character set\s(.*?)\s(.*?)/im", "\\1\\3", $sql);
							$sql = preg_replace("/(.*?)collate\s(.*?)(\s|,|;)/im", "\\1\\3", $sql);
							$sql = preg_replace("/(.*?)default charset=(.*?);(.*?)/im", "\\1\\3", $sql);
							$sql .= "DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
							mysql_unbuffered_query($sql);
						}
					}
				}
				if ($ins_count > 0) {
					if (preg_match("/INSERT INTO `(.*?)`/i",$line,$tmp) <> 0) {
						$ins = $tmp[1];
						if (in_array($ins, $list_ins)) {
							$sql = preg_replace("/INSERT INTO `$inf_tblpre(.*?)`/i","INSERT INTO `$restore_tblpre\\1`",$line);
							if (is_utf8_string($sql)) {
								mysql_unbuffered_query($sql);
							} else {
								mysql_unbuffered_query(iconv("ISO-8859-1", "UTF-8", $sql));
							}
						}
					}
				}
			}
			fclose($fd);
			$variables['error'] = 4;
			$file = pathinfo($_POST['file']);
			if ($file['extension'] == "tmp") {
				@unlink(PATH_ADMIN."db_backups/".$_POST['file']);
			}
		} else {
			$variables['restore_error'] = true;
			fclose($fd);
			$file = pathinfo($_POST['file']);
			if ($file['extension'] == "tmp") {
				@unlink(PATH_ADMIN."db_backups/".$_POST['file']);
			}
		}
	}
} elseif ($action=="restore") {
	$temp_rand = rand(1000000, 9999999);
	$temp_hash = substr(md5($temp_rand), 8, 8);
	$sqlfile = "restore_".$temp_rand.".sql.tmp";
	if (isset($_POST['local_restore'])) {
		$file = pathinfo($_POST['local_file']);
		if ($file['extension'] == "gz") {
			$gzfile = stripinput($_POST['local_file']);
			$backup_name = $gzfile;
			$zd = gzopen(PATH_ADMIN."db_backups/".$gzfile, "r");
			$fd = fopen(PATH_ADMIN."db_backups/".$sqlfile, "w");
			if ($fd) {
				while (!gzeof($zd)) {
					$data = gzread($zd, 4096);
					fwrite($fd, $data);
				}
				fclose($fd);
				gzclose($zd);
			} else {
				fallback(FUSION_SELF.$aidlink);
			}
			$backup_name = $sqlfile;
		} else {
			$backup_name = $_POST['local_file'];
		}
	} elseif (is_uploaded_file($_FILES['upload_backup_file']['tmp_name'])) {
		$gzfile = "restore_".$temp_rand.".tmp";
		$backup_name = $_FILES['upload_backup_file']['name'];
		move_uploaded_file($_FILES['upload_backup_file']['tmp_name'], PATH_ADMIN."db_backups/".$gzfile);
		$file = pathinfo($backup_name);
		if ($file['extension'] == "gz") {
			$zd = gzopen(PATH_ADMIN."db_backups/".$gzfile, "r");
			$fd = fopen(PATH_ADMIN."db_backups/".$sqlfile, "w");
			if ($fd) {
				while (!gzeof($zd)) {
					$data = gzread($zd, 4096);
					fwrite($fd, $data);
				}
				fclose($fd);
				gzclose($zd);
				@unlink(PATH_ADMIN."db_backups/".$gzfile);
				$backup_name = $sqlfile;
			} else {
				fallback(FUSION_SELF.$aidlink);
			}
		}
	} else {
		fallback(FUSION_SELF.$aidlink);
	}
	$info_tbls=array();
	$info_ins_cnt=array();
	$info_ins=array();
	$fd = fopen(PATH_ADMIN."db_backups/".$backup_name, "r");
	while (!feof($fd)) {
		$resultline = fgets($fd);
		if(preg_match_all("/^# Database Name: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_dbname=$resultinfo[1][0]; }
		if(preg_match_all("/^# Table Prefix: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_tblpref=$resultinfo[1][0]; }
		if(preg_match_all("/^# Date: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_date=$resultinfo[1][0]; }
		if(preg_match_all("/^CREATE TABLE `(.+?)`/i", $resultline, $resultinfo)<>0){ $info_tbls[]=$resultinfo[1][0]; }
		if(preg_match_all("/^INSERT INTO `(.+?)`/i", $resultline, $resultinfo)<>0){
			if(!in_array($resultinfo[1][0], $info_ins)) { $info_ins[]=$resultinfo[1][0]; }
			$info_ins_cnt[]=$resultinfo[1][0];
		}
	}
	fclose($fd);
	sort($info_tbls);

	$insert_ins_cnt=array_count_values($info_ins_cnt);
	sort($info_ins);

	$info_inserts = array();
	foreach($info_tbls as $key=>$info_insert){
		$info_inserts[] = array('id' => $info_insert, 'name' => $info_insert." (".(isset($insert_ins_cnt[$info_insert])?$insert_ins_cnt[$info_insert]:0).")", 'selected' => true);
	}
	$maxrows=max(count($info_tbls),count($info_inserts));
	
	// tamplate variables
	$variables['backup_name'] = $backup_name;
	$variables['info_dbname'] = isset($info_dbname) ? $info_dbname : "";
	$variables['info_date'] = isset($info_date) ? $info_date : "";
	$variables['info_tables'] = $info_tbls;
	$variables['info_inserts'] = $info_inserts;
	$variables['info_tblpref'] = isset($info_tblpref) ? $info_tblpref : "";
	$variables['maxrows'] = $maxrows;
	$variables['file'] = $backup_name;

}

if (!CMS_CLI) {	

	// get a list of all backups on the server
	$variables['backup_files'] = makefilelist(PATH_ADMIN."db_backups/", ".|..|index.php", true);

	// template variables
	$variables['action'] = isset($action) ? $action : "";

	// define the admin body panel
	$template_panels[] = array('type' => 'body', 'name' => 'tools.iso2utf', 'template' => 'admin.tools.iso2utf.tpl', 'locale' => "admin.db-backup");
	$template_variables['tools.iso2utf'] = $variables;

	// Call the theme code to generate the output for this webpage
	require_once PATH_THEME."/theme.php";
}

/*---------------------------------------------------+
| Local functions                                    |
+----------------------------------------------------*/

function get_database_size($prefix=""){
	global $db_name;
	$db_size=0;
	$result=dbquery("SHOW TABLE STATUS FROM `".$db_name."`");
	while($row=dbarray($result)){
		if (!isset($row['Type'])) $row['Type'] = "";
		if (!isset($row['Engine'])) $row['Engine'] = "";
		if((eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Type'])) || (eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Engine'])) && (preg_match("/^".$prefix."/",$row['Name']))){
			$db_size+=$row['Data_length']+$row['Index_length'];
		}
	}
	return $db_size;
}

function get_table_count($prefix=""){
	global $db_name;
	$tbl_count=0;
	$result=dbquery("SHOW TABLE STATUS FROM `".$db_name."`");
	while($row=dbarray($result)){
		if (!isset($row['Type'])) $row['Type'] = "";
		if (!isset($row['Engine'])) $row['Engine'] = "";
		if((eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Type'])) || (eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Engine'])) && (preg_match("/^".$prefix."/",$row['Name']))){
			$tbl_count++;
		}
	}
	return $tbl_count;
}

function gzcompressfile($source,$level=false){
	$dest=$source.'.gz';
	$mode='wb'.$level;
	$error=false;
	if($fp_out=gzopen($dest,$mode)){
		if($fp_in=fopen($source,'rb')){
			while(!feof($fp_in))
			gzputs($fp_out,fread($fp_in,1024*512));
			fclose($fp_in);
		}else $error=true;
		gzclose($fp_out);
	}else $error=true;
	if($error)return false; else return $dest;
}

function is_utf8_string($string) {
	return preg_match('%(?:
		[\xC2-\xDF][\x80-\xBF]               # non-overlong 2-byte
		|\xE0[\xA0-\xBF][\x80-\xBF]          # excluding overlongs
		|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}   # straight 3-byte
		|\xED[\x80-\x9F][\x80-\xBF]          # excluding surrogates
		|\xF0[\x90-\xBF][\x80-\xBF]{2}       # planes 1-3
		|[\xF1-\xF3][\x80-\xBF]{3}           # planes 4-15
		|\xF4[\x80-\x8F][\x80-\xBF]{2}       # plane 16
	)+%xs', $string);
}
?>
