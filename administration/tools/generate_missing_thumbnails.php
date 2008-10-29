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
require_once dirname(__FILE__)."/../../includes/core_functions.php";

// check for the proper admin access rights
if (!CMS_CLI && (!checkrights("T") || !defined("iAUTH") || $aid != iAUTH)) fallback(ADMIN."index.php");

/*---------------------------------------------------+
| local functions                                    |
+----------------------------------------------------*/
function display($text="") {

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
display("* Creating thumbnails for all uploaded image files.");

// give this module some memory and execution time
ini_set('memory_limit', '64M');
ini_set('max_execution_time', '0');

// load the theme functions when not in CLI mode
if (!CMS_CLI) {
	require_once PATH_INCLUDES."theme_functions.php";
} else {
	echo "Running in CLI mode...\n";
}

// get the photo functions
require_once PATH_INCLUDES."photo_functions_include.php";

// define the array to store our progress messages in
$messages = array();

// directories to scan
$dirs = array(PATH_ATTACHMENTS, PATH_PM_ATTACHMENTS);

// get all image files from the files/attachments directories
foreach($dirs as $dir) {
	// get the file list
	$files = makefilelist($dir, ".|..");
	// loop through the files found
	foreach($files as $file) {
		// check if it's a file we can create a thumbnail from
		if (!in_array(substr(strrchr($file, "."),0),$thumbtypes)) continue;
		display($file.":");
		// check if a thumbnail exists
		if (file_exists($dir.$file.".thumb")) {
			display("  -> thumbnail already exists...");
			continue;
		}
		// no thumb found. Check if we need to create one
		$imagefile = @getimagesize($dir.$file);
		if ($imagefile[0] > $settings['forum_max_w'] || $imagefile[1] > $settings['forum_max_h']) {
			// image is bigger than the defined maximum image size. Generate a thumb image
			createthumbnail($imagefile[2], $dir.$file, $dir.$file.".thumb", $settings['thumb_w'], $settings['thumb_h']);
			display("  -> thumbnail created...");
		} else {
			display("  -> image to small for a thumbnail");
		}
	}
}

display(" ");
display("Update finished!");

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
	$template_panels[] = array('type' => 'body', 'name' => 'admin.tools.output', 'title' => "Update image thumbnails", 'template' => '_custom_html.tpl');
	$template_variables['admin.tools.output'] = $variables;
	
	// Call the theme code to generate the output for this webpage
	require_once PATH_THEME."/theme.php";
}
?>
