<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Some code developed by CrappoMan, simonpatterson@dsl.pipex.com       |
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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// check for the proper admin access rights
if (!checkrights("DB") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// temp storage for template variables
$variables = array();

// make sure the script doesn't time out
set_time_limit(0);

$crlf = "\n";

// delete an on-the-server backup
if (isset($action) && $action == 'restore' && isset($_POST['local_delete'])) {
	@unlink(PATH_ADMIN."db_backups/".stripinput($_POST['local_file']));
	$action = "";
}

// create the database backup
if (isset($_POST['btn_create_backup'])) {
	$user_password = md5(md5($_POST['user_password']));
	$backup_keep = isset($_POST['backup_keep']) && isNum($_POST['backup_keep']) ? $_POST['backup_keep'] : 0;
	$backup_download = isset($_POST['backup_download']) && isNum($_POST['backup_download']) ? $_POST['backup_download'] : 1;
	$backup_compress = isset($_POST['backup_type']) && ($_POST['backup_type'] == ".gz") ? 1 : 0;
	if ($backup_keep == 0 && $backup_download == 0) {
		$variables['error'] = 2;
	} else if ($user_password != $userdata['user_password']) {
		$variables['error'] = 3;
	} else {
		$db_tables = $_POST['db_tables'];
		if(count($db_tables) > 0) {
			$filename = PATH_ADMIN."db_backups/".stripinput($_POST['backup_filename']).".sql";
			if ($backup_compress) {
				$filename .= ".gz";
				$fp = gzopen ($filename, 'w9');
			} else {
				$fp = fopen ($filename, 'w9');
			}
			if (!$fp) {
				$variables['error'] = 1;
			} else {
				if ($backup_compress) {
					gzwrite($fp, "#----------------------------------------------------------".$crlf);
					gzwrite($fp, "# ExiteCMS SQL Data Dump".$crlf);
					gzwrite($fp, "# Database Name: `".$db_name."`".$crlf);
					gzwrite($fp, "# Table Prefix: `".$db_prefix."`".$crlf);
					gzwrite($fp, "# Date: `".date("d/m/Y H:i")."`".$crlf);
					gzwrite($fp, "#----------------------------------------------------------".$crlf);
				} else {
					fwrite($fp, "#----------------------------------------------------------".$crlf);
					fwrite($fp, "# ExiteCMS SQL Data Dump".$crlf);
					fwrite($fp, "# Database Name: `".$db_name."`".$crlf);
					fwrite($fp, "# Table Prefix: `".$db_prefix."`".$crlf);
					fwrite($fp, "# Date: `".date("d/m/Y H:i")."`".$crlf);
					fwrite($fp, "#----------------------------------------------------------".$crlf);
				}
				dbquery('SET SQL_QUOTE_SHOW_CREATE=1');
				foreach($db_tables as $table) {
					@set_time_limit(1200);
					dbquery("OPTIMIZE TABLE $table");
					if ($backup_compress) {
						gzwrite($fp, $crlf."#".$crlf."# Structure for Table `".$table."`".$crlf."#".$crlf);
						gzwrite($fp, "DROP TABLE IF EXISTS `$table`;$crlf");
					} else {
						fwrite($fp, $crlf."#".$crlf."# Structure for Table `".$table."`".$crlf."#".$crlf);
						fwrite($fp, "DROP TABLE IF EXISTS `$table`;$crlf");
					}
					$row=dbarraynum(dbquery("SHOW CREATE TABLE $table"));
					if ($backup_compress) {
						gzwrite($fp, $row[1].";".$crlf);
					} else {
						fwrite($fp, $row[1].";".$crlf);
					}
					$result=dbquery("SELECT * FROM $table");
					if($result&&dbrows($result)){
						if ($backup_compress) {
							gzwrite($fp, $crlf."#".$crlf."# Table Data for `".$table."`".$crlf."#".$crlf);
						} else {
							fwrite($fp, $crlf."#".$crlf."# Table Data for `".$table."`".$crlf."#".$crlf);
						}
						$column_list="";
						$num_fields=mysqli_num_fields($result);
						for($i=0;$i<$num_fields;$i++){
							$column_list.=(($column_list!="")?", ":"")."`".mysqli_field_name($result,$i)."`";
						}
					}
					while($row=dbarraynum($result)){
						$dump="INSERT INTO `$table` ($column_list) VALUES (";
						for($i=0;$i<$num_fields;$i++){
							$dump.=($i>0)?", ":"";
							if(!isset($row[$i])){
								$dump.="NULL";
							}elseif($row[$i]=="0"||$row[$i]!=""){
								$type=mysqli_field_type($result,$i);
								if($type=="tinyint"||$type=="smallint"||$type=="mediumint"||$type=="int"||$type=="bigint"){
									$dump.=$row[$i];
								}else{
									$search_array=array('\\','\'',"\x00","\x0a","\x0d","\x1a");
									$replace_array=array('\\\\','\\\'','\0','\n','\r','\Z');
									$row[$i]=str_replace($search_array,$replace_array,$row[$i]);
									$dump.="'$row[$i]'";
								}
							}else{
							$dump.="''";
							}
						}
						$dump.=');';
						if ($backup_compress) {
							gzwrite($fp, $dump.$crlf);
						} else {
							fwrite($fp, $dump.$crlf);
						}
					}

				}
				if ($backup_compress) {
					gzclose($fp);
				} else {
					fclose($fp);
				}
				if ($backup_download) {
					$file = stripinput($_POST['backup_filename']).".sql";
					require_once PATH_INCLUDES."class.httpdownload.php";
					$dl = new httpdownload;
					$dl->use_resume = false;
					if ($_POST['backup_type'] == ".gz") {
						$dl->set_mime("application/x-gzip gz tgz");
						$dl->set_byfile($filename);
						$dl->set_filename($file.".gz");
					} else {
						$dl->set_mime("text/plain");
						$dl->set_byfile($filename);
						$dl->set_filename($file);
					}
					$dl->download();
				}
				if (!$backup_keep) {
					@unlink($filename);
				}
				fallback(FUSION_SELF.$aidlink);
				exit;
			}
		} else {
			fallback(FUSION_SELF.$aidlink);
			exit;
		}
	}
}

// load the locale for this module
locale_load("admin.db-backup");

// make sure the parameter is valid
if (!isset($action)) $action = "";

// a restore is cancelled. Remove the uploaded backup file
if (isset($_POST['btn_cancel'])) {
	@unlink(PATH_ADMIN."db_backups/".$_POST['file']);
	redirect(FUSION_SELF.$aidlink);
}

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
			$inf_tblpre = $tmp2[1];
			while (!feof($fd)) {
				if ($tbl_count > 0 || $ins_count > 0) {
					$line = trim(fgets($fd));
					while (!feof($fd) && substr($line, 0, 1) != "#" && substr($line, -1) != ";") {
						$line .= trim(fgets($fd));
					};
					if (preg_match("/^DROP TABLE IF EXISTS `(.*?)`/im",$line,$tmp) <> 0) {
						$tbl = $tmp[1];
						if (in_array($tbl, $list_tbl)) {
							$sql = preg_replace("/^DROP TABLE IF EXISTS `$inf_tblpre(.*?)`/im","DROP TABLE IF EXISTS `$restore_tblpre\\1`",$line);
							mysqli_real_query($_db_link, $sql);
						}
					}
					if (preg_match("/^CREATE TABLE `(.*?)`/im",$line,$tmp) <> 0) {
						$tbl = $tmp[1];
						if (in_array($tbl, $list_tbl)) {
							$sql = preg_replace("/^CREATE TABLE `$inf_tblpre(.*?)`/im","CREATE TABLE `$restore_tblpre\\1`",$line);
							mysqli_real_query($_db_link, $sql);
						}
					}
				}
				if ($ins_count > 0) {
					if (preg_match("/INSERT INTO `(.*?)`/i",$line,$tmp) <> 0) {
						$ins = $tmp[1];
						if (in_array($ins, $list_ins)) {
							$sql = preg_replace("/INSERT INTO `$inf_tblpre(.*?)`/i","INSERT INTO `$restore_tblpre\\1`",$line);
							mysqli_real_query($_db_link, $sql);
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

// make a list of tables in the current database
$table_list=array();
$result=dbquery("SHOW tables");
while($row=dbarraynum($result)){
	$table_list[] = array('id' => $row[0], 'name' => $row[0], 'selected' => preg_match("/^".$db_prefix."/i",$row[0]) );
}

// get a list of all backups on the server
$variables['backup_files'] = makefilelist(PATH_ADMIN."db_backups/", ".|..|index.php", true);

$variables['table_list'] = $table_list;
$variables['db_name'] = $db_name;
$variables['db_size'] = parseByteSize(get_database_size(),2,false);
$variables['db_prefix'] = $db_prefix;
$variables['db_tables'] = get_table_count();
$variables['db_fusion_size'] = parseByteSize(get_database_size($db_prefix),2,false);
$variables['db_fusion_tables'] = get_table_count($db_prefix);

// template variables
$variables['action'] = isset($action) ? $action : "";

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.db_backup', 'template' => 'admin.db_backup.tpl', 'locale' => "admin.db-backup");
$template_variables['admin.db_backup'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";

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
?>
