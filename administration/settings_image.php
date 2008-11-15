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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("admin.settings");

// temp storage for template variables
$variables = array();

// store this modules name for the menu bar
$variables['this_module'] = FUSION_SELF;

// check for the proper admin access rights
if (!checkrights("S5") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings']) || isset($_POST['newthumbs']) || isset($_POST['newphotos'])) {
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['albums_create']) ? $_POST['albums_create'] : "103")."' WHERE cfg_name = 'albums_create'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['albums_moderators']) ? $_POST['albums_moderators'] : "103")."' WHERE cfg_name = 'albums_moderators'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['albums_anonymous']) ? $_POST['albums_anonymous'] : "0")."' WHERE cfg_name = 'albums_anonymous'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['albums_columns']) ? $_POST['albums_columns'] : "103")."' WHERE cfg_name = 'albums_columns'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['thumb_w']) ? $_POST['thumb_w'] : "175")."' WHERE cfg_name = 'thumb_w'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['thumb_h']) ? $_POST['thumb_h'] : "125")."' WHERE cfg_name = 'thumb_h'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['photo_w']) ? $_POST['photo_w'] : "500")."' WHERE cfg_name = 'photo_w'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['photo_h']) ? $_POST['photo_h'] : "375")."' WHERE cfg_name = 'photo_h'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['photo_max_w']) ? $_POST['photo_max_w'] : "1800")."' WHERE cfg_name = 'photo_max_w'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['photo_max_h']) ? $_POST['photo_max_h'] : "1600")."' WHERE cfg_name = 'photo_max_h'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['photo_max_b']) ? $_POST['photo_max_b'] : "1024000")."' WHERE cfg_name = 'photo_max_b'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".$_POST['thumb_compression']."' WHERE cfg_name = 'thumb_compression'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['albums_per_page']) ? $_POST['albums_per_page'] : "5")."' WHERE cfg_name = 'albums_per_page'");
	$result = dbquery("UPDATE ".$db_prefix."configuration SET cfg_value = '".(isNum($_POST['thumbs_per_page']) ? $_POST['thumbs_per_page'] : "5")."' WHERE cfg_name = 'thumbs_per_page'");
}

// get all settings into an array
$settings2 = array();
$result = dbquery("SELECT * FROM ".$db_prefix."configuration");
while ($data = dbarray($result)) {
	$settings2[$data['cfg_name']] = $data['cfg_value'];
}
$variables['settings2'] = $settings2;

if (isset($_POST['newthumbs']) || isset($_POST['newphotos'])) {
	// needed to create the thumbnails
	include PATH_INCLUDES."photo_functions_include.php";
	// get all photo's
	$result = dbquery("SELECT * FROM ".$db_prefix."photos");
	while ($data = dbarray($result)) {
		// get info about the original file
		$imagefile = @getimagesize(PATH_PHOTOS.$data['photo_original']);
		// generate a new normalized image if needed
		if (is_array($imagefile) && isset($_POST['newphotos'])) {
			ini_set("max_execution_time", 0);
			// do we need to delete the old one?
			if ($data['photo_sized'] != $data['photo_original']) {
				@unlink(PATH_PHOTOS.$data['photo_sized']);
			}
			if ($imagefile[0] > $settings2['photo_w'] || $imagefile[1] > $settings2['photo_h']) {
				// Generate a new intermediate image
				$data['photo_sized'] = str_replace(".img", ".sized.img", $data['photo_original']);
				createthumbnail($imagefile[2], PATH_PHOTOS.$data['photo_original'], PATH_PHOTOS.$data['photo_sized'], $settings2['photo_w'], $settings2['photo_h']);
				$result2 = dbquery("UPDATE ".$db_prefix."photos SET photo_sized = '".$data['photo_sized']."' WHERE photo_id = ".$data['photo_id']);
			} else {
				$result2 = dbquery("UPDATE ".$db_prefix."photos SET photo_sized = photo_original WHERE photo_id = ".$data['photo_id']);
			} 
		}
		// generate a new thumbnail if needed
		if (is_array($imagefile) && isset($_POST['newthumbs'])) {
			ini_set("max_execution_time", 0);
			// do we need to delete the old one?
			if ($data['photo_thumb'] != $data['photo_original']) {
				@unlink(PATH_PHOTOS.$data['photo_thumb']);
			}
			if ($imagefile[0] > $settings2['thumb_w']) {
				// calculate a new thumb height
				$thumb_h = floor(($settings2['thumb_w'] / $imagefile[0]) * $imagefile[1]);
				// Generate a new intermediate image
				$data['photo_thumb'] = str_replace(".img", ".thumb.img", $data['photo_original']);
				createthumbnail($imagefile[2], PATH_PHOTOS.$data['photo_original'], PATH_PHOTOS.$data['photo_thumb'], $settings2['thumb_w'], $thumb_h);
				$result2 = dbquery("UPDATE ".$db_prefix."photos SET photo_thumb = '".$data['photo_thumb']."' WHERE photo_id = ".$data['photo_id']);
			} else {
				$result2 = dbquery("UPDATE ".$db_prefix."photos SET photo_thumb = photo_original WHERE photo_id = ".$data['photo_id']);
			} 
		}
	}
}

// get the list of available groups
$variables['usergroups'] = getusergroups(false);
$variables['allusergroups'] = getusergroups(true);

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_image', 'template' => 'admin.settings_image.tpl', 'locale' => "admin.settings");
$template_variables['admin.settings_image'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
