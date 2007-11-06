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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("admin.image_uploads");

// temp storage for template variables
$variables = array();

//check if the user has a right to be here. If not, bail out
if (!checkrights("IM") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// set a default if the selected folder is not given
if (!isset($ifolder)) $ifolder = "images";

// include the TinyMCE buildlist code if regeneration of the list is requested
if (isset($action) && $action = "update") include PATH_INCLUDES."buildlist.php";

// build the list of available image categories (skip internal CMS image subdirectories)
$variables['image_cats'] = array();
$dirlist = makefilelist(PATH_IMAGES, ".|..|advertising|avatars|flags|smiley", true, 'folders');
$variables['image_cats'][] = array('folder' => "images", 'name' => $locale['422'], 'path' => PATH_IMAGES, 'selected' => ($ifolder == "images"));
foreach($dirlist as $entry) {
	$name = ucwords(str_replace("_", " ", $entry));
	$variables['image_cats'][] = array('folder' => $entry, 'name' => $name, 'path' => PATH_IMAGES.$entry."/", 'selected' => ($ifolder == $entry));
}
$ufolder = IMAGES.($ifolder == "images" ? "" : $ifolder."/");
$afolder = PATH_IMAGES.($ifolder == "images" ? "" : $ifolder."/");

if (isset($status)) {
	if ($status == "del") {
		$title = $locale['400'];
		$variables['message'] = $locale['401'];
	} elseif ($status == "upn") {
		$title = $locale['420'];
		$variables['message'] = $locale['425'];
	} elseif ($status == "upy") {
		$title = $locale['420'];
		$variables['message'] = "<img src='".$ufolder.$img."' alt='$img' /><br /><br />".$locale['426'];
	}
	// define the message panel variables
	$variables['bold'] = true;
	$template_panels[] = array('type' => 'body', 'name' => 'admin.forums.status', 'title' => $title, 'template' => '_message_table_panel.tpl', 'locale' => "admin.forums");
	$template_variables['admin.forums.status'] = $variables;
	$variables = array();
}

if (isset($del)) {
	unlink($afolder."$del");
	if ($settings['tinymce_enabled'] == 1) include PATH_INCLUDES."buildlist.php";
	redirect(FUSION_SELF.$aidlink."&status=del&ifolder=$ifolder");
} else if (isset($_POST['uploadimage'])) {
	$error = "";
	$image_types = array(
		".gif",
		".GIF",
		".jpeg",
		".JPEG",
		".jpg",
		".JPG",
		".png",
		".PNG"
	);
	$imgext = strrchr($_FILES['myfile']['name'], ".");
	$imgname = $_FILES['myfile']['name'];
	$imgsize = $_FILES['myfile']['size'];
	$imgtemp = $_FILES['myfile']['tmp_name'];
	if (!in_array($imgext, $image_types)) {
		redirect(FUSION_SELF.$aidlink."&status=upn&ifolder=$ifolder");
	} elseif (is_uploaded_file($imgtemp)){
		move_uploaded_file($imgtemp, $afolder.$imgname);
		chmod($afolder.$imgname,0644);
		if ($settings['tinymce_enabled'] == 1) include PATH_INCLUDES."buildlist.php";
		redirect(FUSION_SELF.$aidlink."&status=upy&ifolder=$ifolder&img=$imgname");
	}
} else {
	$variables['ifolder'] = $ifolder;
	$variables['view'] = isset($view) ? $view : "";

	if (isset($view)) {
		$image_ext = strrchr($afolder.$view,".");
		if (in_array($image_ext, array(".gif",".GIF",".jpg",".JPG",".jpeg",".JPEG",".png",".PNG"))) {
			$variables['view_image'] = $ufolder.$view;
		} else {
			$variables['view_image'] = "";
		}
	} else {
		$variables['image_list'] = makefilelist($afolder, ".|..|imagelist.js|index.php", true);
	}
}

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.images', 'template' => 'admin.images.tpl', 'locale' => "admin.image_uploads");
$template_variables['admin.images'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>