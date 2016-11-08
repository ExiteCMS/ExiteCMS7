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
| $Id:: swfupload_include.php 1935 2008-10-29 23:42:42Z WanWizard     $|
+----------------------------------------------------------------------+
| Last modified by $Author:: WanWizard                                $|
| Revision number $Rev:: 1935                                         $|
+---------------------------------------------------------------------*/
if (strpos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false || !defined('INIT_CMS_OK')) die();

// load this module's locales
//locale_load("fancyupload");

// return the result to the fancyupload applet
function UploadResult($result="success", $message="") {

	// tell the caller we're sending json data back
	if (!headers_sent() ) {
		header('Content-type: application/json');
	}

	// need json to send the upload result back
	require_once "json_include.php";

	// send the response
	if ($result == "success") {
		echo json_encode(array('result' => $result, 'size' => $message));
	} else {
		echo json_encode(array('result' => $result, 'error' => $message));
	}
	exit(0);
}

// validate and process the uploaded file
function ProcessUpload($FileName, $FileExt, $FilePath, $MaxSize = 2147483647, $ImgCheck = false, $Regex = false, $ValidExts = false) {

	// check if the required values exist
	if (empty($FileName) || empty($FileExt) || empty($FilePath)) {
		UploadResult("error", "Application error: Invalid configuration information passed to ProcessUpload!");
	}

	// create the uploadinfo array
	$UploadInfo = array('filename' => $FileName, 'fileext' => $FileExt, 'filepath' => $FilePath, 'verify_image' => $ImgCheck);

	// check for extra parameters
	if (!empty($Regex)) {
		$UploadInfo['filename_regex'] = $Regex;
	}
	if (!empty($ValidExts)) {
		$UploadInfo['extensions'] = $ValidExts;
	}

	// step 1: check if max upload size isn't exceeded

	$post_max_size = ini_get('post_max_size');
	$unit = strtoupper(substr($post_max_size, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));
	if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$post_max_size && $post_max_size) {
		UploadResult("failed", "POST exceeded maximum allowed size");
	}

	// define the possible errors

	$uploadErrors = array(
		1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
		2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
		3 => "The uploaded file was only partially uploaded",
		4 => "No file was uploaded",
		6 => "Missing a temporary folder"
	);

	// step 2: validate the upload

	if (!isset($_FILES["Filedata"])) {
		UploadResult("error", "No upload found in \$_FILES for Filedata");
	} else if (isset($_FILES["Filedata"]["error"]) && isset($uploadErrors[$_FILES["Filedata"]["error"]])) {
		UploadResult("error", $uploadErrors[$_FILES["Filedata"]["error"]]);
	} else if (!isset($_FILES["Filedata"]["tmp_name"]) || !@is_uploaded_file($_FILES["Filedata"]["tmp_name"])) {
		UploadResult("error", "Upload failed is_uploaded_file test. Hacking attempt?");
	} else if (!isset($_FILES["Filedata"]["name"])) {
		UploadResult("error", "File has no name.");
	}

	// step 3: validate the file size

	$file_size = @filesize($_FILES["Filedata"]["tmp_name"]);
	if (!$file_size || $file_size > $MaxSize) {
		UploadResult("error", "File exceeds the maximum allowed size of ".parsebytesize($MaxSize));
	}
	if ($file_size <= 0) {
		UploadResult("error", "File size outside allowed lower bound");
	}

	// step 4: validate the file name and the file extension

	if (!empty($UploadInfo['filename_regex'])) {
		$file_name = preg_replace('/[^'.$UploadInfo['filename_regex'].']|\.+$/i', "", basename($_FILES["Filedata"]['name']));
		if (strlen($file_name) == 0 || strlen($file_name) > 255) {
			UploadResult("error", "Invalid file name");
		}
	}
	if (isset($UploadInfo['extensions']) && is_array($UploadInfo['extensions'])) {
		$path_info = pathinfo($_FILES["Filedata"]['name']);
		$file_extension = $path_info["extension"];
		$is_valid_extension = false;
		foreach ($UploadInfo['extensions'] as $extension) {
			if (strcasecmp($file_extension, $extension) == 0) {
				$is_valid_extension = true;
				break;
			}
		}
		if (!$is_valid_extension) {
			UploadResult("error", "Invalid file extension");
		}
	}

	// step 5: validate the file as a valid image

	if (isset($UploadInfo['verify_image']) && $UploadInfo['verify_image']) {
		if (!verify_image($_FILES["Filedata"]["tmp_name"])) {
			UploadResult("error", "File is not a valid image");
		}
	}

	// step 6: move the uploaded file

	$UploadInfo['file'] = $UploadInfo['filepath'].$UploadInfo['filename'].".".$UploadInfo['fileext'];
	if (!@move_uploaded_file($_FILES["Filedata"]["tmp_name"], $UploadInfo['file'])) {
		UploadResult("error", "File could not be saved");
	}

	// use the UploadInfo array to return detailed info to the caller
	$UploadInfo = array_merge($_FILES, $UploadInfo);

	return $UploadInfo;
}

// debug function
function _upload_debug($message) {
	// debug code to capture upload errors
	$h = fopen("/tmp/exitecms_upload.log", "a");
	fwrite($h, $message."\n");
	fclose($h);
}
?>
