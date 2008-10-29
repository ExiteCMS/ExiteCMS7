<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Code by Johs Lind, http://www.geltzer.dk/                            |
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
if (!defined("INIT_CMS_OK")) { header("Location: ../index.php"); exit; }

$image_files = array();

function getimages($filepath, $urlpath, $title) {
	global $image_files;

	$image_file = array();

	$temp = opendir($filepath);
	while ($file = readdir($temp)) {
		if (!in_array($file, array(".", "..", "/", ".svn", "index.php")) && !is_dir($filepath.$file) && $file != 'imagelist.js') {
			$image_file[0] = "['".$title.": ".$file."',";
			$image_file[1] = "'".$urlpath.$file."'],\n";
			$image_files[] = $image_file;
		}
	}
	closedir($temp);
}

// images ------------------------
getimages(PATH_IMAGES, IMAGES, "   images");

// articles ---------------
getimages(PATH_IMAGES_A, IMAGES_A, " articles");

// news -------------------
getimages(PATH_IMAGES_N, IMAGES_N, "     news");
getimages(PATH_IMAGES_NC, IMAGES_N, "     news");

// downloads ---------------
getimages(PATH_IMAGES_DC, IMAGES_DC, "downloads");

// compile list -----------------
if (isset($image_files)) {
	$indhold = "var tinyMCEImageList = new Array(\n";
	for ($i=0;$i < count($image_files);$i++){
		if (isset($image_files[$i][0]) && isset($image_files[$i][1])) {
			$indhold = $indhold.$image_files[$i][0].$image_files[$i][1];
		}
	}
	$lang = strlen($indhold)-2;
	$indhold = substr($indhold,0,$lang);
	$indhold = $indhold.");\n";
	$fp = fopen(PATH_IMAGES."imagelist.js","w");
	fwrite($fp, $indhold);
	fclose($fp);
}
?>
