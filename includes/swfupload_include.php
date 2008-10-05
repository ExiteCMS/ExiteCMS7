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
+----------------------------------------------------*/
if (eregi("swfupload_include.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// error handler, returns error messages back to SWFUpload
function HandleError($message) {
	header("HTTP/1.1 200 OK");
	echo "error|".$message;
	exit(0);
}

function _swf_debug($message) {
	// debug code to capture upload errors
	$h = fopen("/tmp/swfupload.log", "a");
	fwrite($h, $message."\n");
	fclose($h);
}

// check if the configuration array exists

if (!isset($SWFconfig) || !is_array($SWFconfig) || empty($SWFconfig['filename'])) {
	HandleError("Application error: SWF configuration array is not defined.");
}

// step 1: check if max upload size isn't exceeded

$post_max_size = ini_get('post_max_size');
$unit = strtoupper(substr($post_max_size, -1));
$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));
if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$post_max_size && $post_max_size) {
	HandleError("POST exceeded maximum allowed size");
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
	HandleError("No upload found in \$_FILES for Filedata");
} else if (isset($_FILES["Filedata"]["error"]) && isset($uploadErrors[$_FILES["Filedata"]["error"]])) {
	HandleError($uploadErrors[$_FILES["Filedata"]["error"]]);
} else if (!isset($_FILES["Filedata"]["tmp_name"]) || !@is_uploaded_file($_FILES["Filedata"]["tmp_name"])) {
	HandleError("Upload failed is_uploaded_file test. Hacking attempt?");
} else if (!isset($_FILES["Filedata"]["name"])) {
	HandleError("File has no name.");
}

// step 3: validate the file size

$file_size = @filesize($_FILES["Filedata"]["tmp_name"]);
if (!$file_size || $file_size > $settings['photo_max_b']) {
	HandleError("File exceeds the maximum allowed size of ".parsebytesize($settings['photo_max_b']));
}
if ($file_size <= 0) {
	HandleError("File size outside allowed lower bound");
}

// step 4: validate the file name and the file extension

if (!empty($SWFconfig['filename_regex'])) {
	$file_name = preg_replace('/[^'.$SWFconfig['filename_regex'].']|\.+$/i', "", basename($_FILES["Filedata"]['name']));
	if (strlen($file_name) == 0 || strlen($file_name) > 255) {
		HandleError("Invalid file name");
	}
}
if (isset($SWFconfig['extensions']) && is_array($SWFconfig['extensions'])) {
	$path_info = pathinfo($_FILES["Filedata"]['name']);
	$file_extension = $path_info["extension"];
	$is_valid_extension = false;
	foreach ($SWFconfig['extensions'] as $extension) {
		if (strcasecmp($file_extension, $extension) == 0) {
			$is_valid_extension = true;
			break;
		}
	}
	if (!$is_valid_extension) {
		HandleError("Invalid file extension");
	}
}

// step 5: validate the file as a valid image

if (isset($SWFconfig['verify_image']) && $SWFconfig['verify_image']) {
	if (!verify_image($_FILES["Filedata"]["tmp_name"])) {
		HandleError("File is not a valid image");
	}
}

// step 6: move the uploaded file

$SWFconfig['file'] = $SWFconfig['filepath'].$SWFconfig['filename'].".".$SWFconfig['fileext'];
if (!@move_uploaded_file($_FILES["Filedata"]["tmp_name"], $SWFconfig['file'])) {
	HandleError("File could not be saved");
}

// use the SWFconfig array to return detailed info to the caller
$SWFconfig = array_merge($_FILES, $SWFconfig);
?>
