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
require_once dirname(__FILE__)."/includes/core_functions.php";

/*---------------------------------------------------+
| local functions                                    |
+----------------------------------------------------*/

// local function to check for album, gallery and/or photo access
function has_photo_access($id=0, $type="", $photo_id=0, $mode='read') {
	global $collection, $settings, $userdata, $db_prefix;

	// validate the parameters
	if (empty($id) || empty($type)) return false;
	if ($mode != 'read' && $mode != 'write') $mode = 'read';

	// result cache
	static $resultcache;
	if (isset($resultcache[$type][$id][$mode])) {
		return $resultcache[$type][$id][$mode];
	}

	// check if the requested id and type are part of the collection
	$match = false;
	foreach($collection as $item) {
		if ($item['type'] == $type && $item['id'] == $id) {
			// match found.
			if ($mode == 'write') {
				// Does the user have write access?
				switch ($type) {
					case "album":
						$album = dbarray(dbquery("SELECT * FROM ".$db_prefix."albums WHERE album_id = $id"));
						$match = $album && ((iMEMBER && $album['album_write'] == -1 && $album['album_owner'] == $userdata['user_id']) || checkgroup($album['album_write']));
						break;
					case "gallery":
						$gallery = dbarray(dbquery("SELECT * FROM ".$db_prefix."galleries WHERE gallery_id = $id"));
						$match = $gallery && ((iMEMBER && $gallery['gallery_write'] == -1 && $gallery['gallery_owner'] == $userdata['user_id']) || checkgroup($gallery['gallery_write']));
						break;
					default:
						$match = false;
						break;
				}
			} else {
				$match = true;
			}
			break;
		}
	}
	if ($match && !empty($photo_id)) {
		// check if this photo is part of the matched collection item
		switch ($type) {
			case "album":
				$match = dbfunction("COUNT(*)", "album_photos", "album_id = $id AND photo_id = $photo_id");
				break;
			case "gallery":
				$match = dbfunction("COUNT(*)", "gallery_photos", "gallery_id = $id AND photo_id = $photo_id");
				break;
			default:
				$match = false;
				break;
		}
	}
	
	// add the result to the resultcache
	if (!isset($resultcache)) $resultcache = array();
	if (!isset($resultcache[$type])) $resultcache[$type] = array();
	if (!isset($resultcache[$type][$id])) $resultcache[$type][$id] = array();
	$resultcache[$type][$id][$mode] = $match;
	
	// return the result
	return $match;
}

// delete an entire album
function delete_album($album_id) {
	global $db_prefix;

	// get all photo's in this album
	$result = dbquery("SELECT photo_id FROM ".$db_prefix."album_photos WHERE album_id = $album_id");
	while ($data = dbarray($result)) {
		// delete this photo
		delete_photo($album_id, $data['photo_id']);
	}
	// delete the album itself
	$result2 = dbquery("DELETE FROM ".$db_prefix."albums WHERE album_id = $album_id");
}

// delete an entire gallery
function delete_gallery($gallery_id) {
	global $db_prefix;

	// get all gallery_photo_id's in this gallery
	$result = dbquery("SELECT gallery_photo_id FROM ".$db_prefix."gallery_photos WHERE gallery_id = $gallery_id");
	while ($data = dbarray($result)) {
		// delete all comments and ratings
		$result2 = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_item_id = ".$data['gallery_photo_id']." AND comment_type = 'G'");
		$result2 = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_item_id = ".$data['gallery_photo_id']." AND rating_type = 'G'");
		// delete the gallery_photo record
		$result2 = dbquery("DELETE FROM ".$db_prefix."gallery_photos WHERE gallery_photo_id = ".$data['gallery_photo_id']);
	}
	// delete the gallery itself
	$result2 = dbquery("DELETE FROM ".$db_prefix."galleries WHERE gallery_id = $gallery_id");

}

// delete a photo from an album
function delete_photo($album_id, $photo_id) {
	global $db_prefix;
			
	// get the album_photo_id
	$result = dbquery("SELECT album_photo_id FROM ".$db_prefix."album_photos WHERE album_id = $album_id AND photo_id = $photo_id");
	if (dbrows($result)) {
		$data = dbarray($result);
		// delete all comments and ratings
		$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_item_id = ".$data['album_photo_id']." AND comment_type = 'B'");
		$result = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_item_id = ".$data['album_photo_id']." AND rating_type = 'B'");
		// delete the album_photo record
		$result = dbquery("DELETE FROM ".$db_prefix."album_photos WHERE album_photo_id = ".$data['album_photo_id']);
		// get any gallery_photo_id this photo belongs to
		$result = dbquery("SELECT gallery_photo_id FROM ".$db_prefix."gallery_photos WHERE photo_id = $photo_id");
		while ($data = dbarray($result)) {
			// delete all comments and ratings
			$result2 = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_item_id = ".$data['gallery_photo_id']." AND comment_type = 'G'");
			$result2 = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_item_id = ".$data['gallery_photo_id']." AND rating_type = 'G'");
			// delete the gallery_photo record
			$result2 = dbquery("DELETE FROM ".$db_prefix."gallery_photos WHERE gallery_photo_id = ".$data['gallery_photo_id']);
		}
	}
	// get the photo record
	$result = dbquery("SELECT * FROM ".$db_prefix."photos WHERE photo_id = $photo_id");
	if (dbrows($result)) {
		$data = dbarray($result);
		// delete the photo record itself
		$result2 = dbquery("DELETE FROM ".$db_prefix."photos WHERE photo_id = $photo_id");
		// delete the files on disk
		if ($data['photo_thumb'] != $data['photo_original']) @unlink(PATH_PHOTOS.$data['photo_thumb']);
		if ($data['photo_sized'] != $data['photo_original']) @unlink(PATH_PHOTOS.$data['photo_sized']);
		@unlink(PATH_PHOTOS.$data['photo_original']);
	}
}

/*---------------------------------------------------+
| main code                                          |
+----------------------------------------------------*/

// make sure the parameters passed are valid
if (!isset($type) || ($type != "album" && $type != "gallery" && $type != "photo")) $type = "";
if (!isset($action) || (!in_array($action, array("add","edit","delete","view","upload","swfupload","slideshow","highlight")))) $action = "";
if (!isset($photo_id) || !isNum($photo_id)) $photo_id = 0;
if (!isset($album_id) || !isNum($album_id)) $album_id = 0;
if (!isset($gallery_id) || !isNum($gallery_id)) $gallery_id = 0;

// load this module's locales
locale_load("main.albums");

// needed for parsemessage
include PATH_INCLUDES."forum_functions_include.php";

// temp storage for template variables
$variables = array();

// error and other messages
$variables['errormessages'] = array();

// number of columns in the panel
$variables['columns'] = $settings['albums_columns'];

// may add new albums?
$variables['can_create'] = checkgroup($settings['albums_create']) ? 1 : 0;

// is this user a moderator?
$variables['is_moderator'] = checkgroup($settings['albums_moderators']) ? 1 : 0;

// default panel title
$title = $locale['400'];

// temp storage for the album and gallery collection
$collection = array();

// get the number of albums visible for this user
$result = dbquery("SELECT a.album_id, a.album_datestamp, a.album_title FROM ".$db_prefix."albums a
	WHERE ".(iMEMBER ? ("a.album_owner = '".$userdata['user_id']."' OR (") : "(").groupaccess('a.album_read').")
	ORDER BY a.album_datestamp DESC"
);
$variables['album_count'] = dbrows($result);
// add these albums to the collection
while ($data = dbarray($result)) {
	$collection[$data['album_datestamp']] = array("type" => 'album', "id" => $data['album_id'], "title" => $data['album_title']);
}

// get the number of galleries visible for this user
$result = dbquery("SELECT g.gallery_id, g.gallery_datestamp, g.gallery_title FROM ".$db_prefix."galleries g
	WHERE ".(iMEMBER ? ("g.gallery_owner = '".$userdata['user_id']."' OR (") : "(").groupaccess('g.gallery_read').")
	ORDER BY g.gallery_datestamp DESC"
);
$variables['gallery_count'] = dbrows($result);
// add these galleries to the collection
while ($data = dbarray($result)) {
	$collection[$data['gallery_datestamp']] = array("type" => 'gallery', "id" => $data['gallery_id'], "title" => $data['gallery_title']);
}
// sort the collection array, newest first
krsort($collection);

// store the number of collections found
$variables['rows'] = count($collection);

// handle SWF uploads first
if (isset($_POST['SWFSESSIONID'])) {
	// check if the user has upload rights to this album
	if (empty($_POST['album_id']) || !isNum($_POST['album_id']) || !has_photo_access($_POST['album_id'], "album", 0, "write")) {
		echo "error|".$locale['402'];
		exit(0);
	}
	// generate a random filename
	$filename = md5(time());
	while (file_exists(PATH_IMAGES."albums/".$filename.".img")) {
		$filename = md5(time());
	}
	$SWFconfig = array("filepath" => PATH_IMAGES."albums/", "filename" => $filename, "fileext" => "img", "verify_image" => true);
	// validate and process the uploaded file
	require PATH_INCLUDES."swfupload_include.php";
	// upload succeeded
	$SWFconfig['original'] = $SWFconfig['filename'].".".$SWFconfig['fileext'];
	// create the thumbnails
	include PATH_INCLUDES."photo_functions_include.php";
	// generate a normalized image if needed
	$imagefile = @getimagesize($SWFconfig['file']);
	if ($imagefile[0] > $settings['photo_w'] || $imagefile[1] > $settings['photo_h']) {
		// image is bigger than the defined standardized size. Generate an intermediate image
		$SWFconfig['sized'] = $SWFconfig['filename'].".sized.".$SWFconfig['fileext'];
		createthumbnail($imagefile[2], $SWFconfig['file'], $SWFconfig['filepath'].$SWFconfig['sized'], $settings['photo_w'], $settings['photo_h']);
	} else {
		// sized photo is the same as the original
		$SWFconfig['sized'] = $SWFconfig['original'];
	}
	// generate a thumbnail image if needed
	if ($imagefile[0] > $settings['thumb_w'] || $imagefile[1] > $settings['thumb_h']) {
		// image is bigger than the defined standardized size. Generate an intermediate image
		$SWFconfig['thumb'] = $SWFconfig['filename'].".thumb.".$SWFconfig['fileext'];
		createthumbnail($imagefile[2], $SWFconfig['file'], $SWFconfig['filepath'].$SWFconfig['thumb'], $settings['thumb_w'], $settings['thumb_h']);
	} else {
		// sized photo is the same as the original
		$SWFconfig['thumb'] = $SWFconfig['original'];
	}
	// create the new photo record
	$result = dbquery("INSERT INTO ".$db_prefix."photos (photo_name, photo_thumb, photo_sized, photo_original, photo_size, photo_uploaded_by, photo_datestamp) VALUES ('".mysql_escape_string($SWFconfig['Filedata']['name'])."', '".$SWFconfig['thumb']."', '".$SWFconfig['sized']."', '".$SWFconfig['original']."', ".$SWFconfig['Filedata']['size'].", ".(iMEMBER ? $userdata['user_id'] : 0).", ".time().")");
	$photo_id = mysql_insert_id();
	// add the photo to the album
	$result = dbquery("INSERT INTO ".$db_prefix."album_photos (album_id, photo_id, album_photo_title, album_photo_datestamp) VALUES (".$album_id.", ".$photo_id.", '".mysql_escape_string($SWFconfig['Filedata']['name'])."', ".time().")");
	echo $locale['403'];
	exit(0);
}

// we're interactive, so load the theme functions
require_once PATH_ROOT."/includes/theme_functions.php";

// check if the albums directory exists, if not, create it. if that fails, bail out!
if (!is_dir(PATH_PHOTOS)) {
	@mkdir(PATH_PHOTOS, 0770);
	@touch(PATH_PHOTOS."index.php");
	if (!file_exists(PATH_PHOTOS."index.php")) {
		// define the body panel variables
		$template_panels[] = array('type' => 'body', 'name' => 'albums', 'title' => "Photo Albums", 'template' => '_message_table_panel.tpl', 'locale' => array("main.albums"));
		$template_variables['albums'] = array('color' => 'red', 'bold' => true, 'message' => 'The photo album directory does not exist, and no rights to create it!<br /><br />Please rectify this problem by giving the webserver write access to<br />"'.PATH_PHOTOS.'"');
		// Call the theme code to generate the output for this webpage
		require_once PATH_THEME."/theme.php";
		// and quit
		exit(0);
	}
}

// process the type and action
if ($type == "photo") {

	if ($action == "edit" && isset($_POST['save'])) {
		if (!empty($album_id) && has_photo_access($album_id, "album", $photo_id, "write")) {
			$result = dbquery("UPDATE ".$db_prefix."album_photos SET 
				album_photo_title = '".mysql_escape_string(stripinput($_POST['album_photo_title']))."',
				album_photo_description = '".mysql_escape_string(stripinput($_POST['hoteditor_bbcode_ouput_photo_editor']))."' 
				WHERE album_photo_id = $album_photo_id");
			$variables['errormessages'][] = $locale['404'];
			// return to the photo view
			$action = "view";
		} else {
			if (!empty($gallery_id) && has_photo_access($gallery_id, "gallery", $photo_id, "write")) {
				$result = dbquery("UPDATE ".$db_prefix."gallery_photos SET 
					gallery_photo_title = '".mysql_escape_string(stripinput($_POST['gallery_photo_title']))."',
					gallery_photo_description = '".mysql_escape_string(stripinput($_POST['hoteditor_bbcode_ouput_photo_editor']))."'
					WHERE gallery_photo_id = $gallery_photo_id");
				$variables['errormessages'][] = $locale['404'];
				// return to the photo view
				$action = "view";
			} else {
				$variables['errormessages'][] = $locale['405'];
				// go back to the overview page
				$type = ""; $action = "";
			}
		}
	}

	if ($action == "view" || $action == "delete") {
		// check if the user has access to this album and this photo
		if (!empty($album_id) && has_photo_access($album_id, "album", $photo_id)) {
			$result = dbquery("SELECT ap.*, a.album_title, a.album_owner, a.album_allow_comments, a.album_allow_ratings, p.*, u.user_id, u.user_name
				FROM ".$db_prefix."album_photos ap
				INNER JOIN ".$db_prefix."albums a ON ap.album_id = a.album_id
				INNER JOIN ".$db_prefix."photos p ON ap.photo_id = p.photo_id
				LEFT JOIN ".$db_prefix."users u ON p.photo_uploaded_by = u.user_id
				WHERE ap.album_id = $album_id AND ap.photo_id = $photo_id
			");
			if (dbrows($result)) {
				$variables['photo'] = dbarray($result);
				// parse the photo description
				$variables['photo']['parsed_description'] = parsemessage(array(), is_null($variables['photo']['album_photo_description'])?"":$variables['photo']['album_photo_description'], true, true);
				// check if the user may edit the photo properties
				$variables['can_edit'] = has_photo_access($album_id, "album", $photo_id, "write");
				// find the previous and next photo
				$result = dbquery("SELECT photo_id FROM ".$db_prefix."album_photos
					WHERE album_id = $album_id AND photo_id < $photo_id
					ORDER BY photo_id DESC
					LIMIT 1;
				");
				if (dbrows($result)) {
					$data = dbarray($result);
					$variables['photo']['previous'] = $data['photo_id'];
					$variables['photo']['previous_type'] = "album";
				} else {
					$variables['photo']['previous'] = 0;
				}
				$result = dbquery("SELECT photo_id FROM ".$db_prefix."album_photos
					WHERE album_id = $album_id AND photo_id > $photo_id
					LIMIT 1;
				");
				if (dbrows($result)) {
					$data = dbarray($result);
					$variables['photo']['next'] = $data['photo_id'];
					$variables['photo']['next_type'] = "album";
				} else {
					$variables['photo']['next'] = 0;
				}
				// increment the viewed counter
				$result = dbquery("UPDATE ".$db_prefix."photos SET photo_sized_count = photo_sized_count + 1 WHERE photo_id = $photo_id");
				$variables['photo']['photo_sized_count']++;
				// no comments or ratings when deleting a photo_id
				if ($action == "delete") {
					unset($variables['photo']['album_allow_ratings']);
					unset($variables['photo']['album_allow_comments']);
					// update the panel title
					$title = $locale['406'];
				} else {
					// update the panel title
					$title = $locale['407'];
				}
			} else {
				$variables['errormessages'][] = $locale['408'];
				// return to album view
				$type = "album"; $action = "view";
			}
		} elseif (!empty($gallery_id) && has_photo_access($gallery_id, "gallery", $photo_id)) {
			$result = dbquery("SELECT gp.*, g.gallery_title, g.gallery_owner, g.gallery_allow_comments, g.gallery_allow_ratings, p.* 
				FROM ".$db_prefix."gallery_photos gp
				INNER JOIN ".$db_prefix."galleries g ON gp.gallery_id = g.gallery_id
				INNER JOIN ".$db_prefix."photos p ON gp.photo_id = p.photo_id
				WHERE gp.gallery_id = $gallery_id AND gp.photo_id = $photo_id
			");
			if (dbrows($result)) {
				$variables['photo'] = dbarray($result);
				// parse the photo description
				$variables['photo']['parsed_description'] = parsemessage(array(), is_null($variables['photo']['gallery_photo_description'])?"":$variables['photo']['gallery_photo_description'], true, true);
				// check if the user may edit the photo properties
				$variables['can_edit'] = has_photo_access($gallery_id, "gallery", $photo_id, "write");
				// find the previous and next photo
				$result = dbquery("SELECT photo_id FROM ".$db_prefix."gallery_photos
					WHERE gallery_id = $gallery_id AND photo_id < $photo_id
					ORDER BY photo_id DESC
					LIMIT 1;
				");
				if (dbrows($result)) {
					$data = dbarray($result);
					$variables['photo']['previous'] = $data['photo_id'];
					$variables['photo']['previous_type'] = "gallery";
				} else {
					$variables['photo']['previous'] = 0;
				}
				$result = dbquery("SELECT photo_id FROM ".$db_prefix."gallery_photos
					WHERE gallery_id = $gallery_id AND photo_id > $photo_id
					LIMIT 1;
				");
				if (dbrows($result)) {
					$data = dbarray($result);
					$variables['photo']['next'] = $data['photo_id'];
					$variables['photo']['next_type'] = "gallery";
				} else {
					$variables['photo']['next'] = 0;
				}
				// increment the viewed counter
				$result = dbquery("UPDATE ".$db_prefix."photos SET photo_sized_count = photo_sized_count + 1 WHERE photo_id = $photo_id");
				$variables['photo']['photo_sized_count']++;
				// no comments or ratings when deleting a photo_id
				if ($action == "delete") {
					unset($variables['photo']['gallery_allow_ratings']);
					unset($variables['photo']['gallery_allow_comments']);
					// update the panel title
					$title = $locale['406'];
				} else {
					// update the panel title
					$title = $locale['407'];
				}
			} else {
				$variables['errormessages'][] = $locale['408'];
				// return to album view
				$type = "album"; $action = "view";
			}
		} else {
			$variables['errormessages'][] = $locale['409'];
			// go back to the overview page
			$type = ""; $action = "";
		}
	}

	if ($action == "highlight") {
		// check if the user has access to this album
		if (!empty($album_id) && has_photo_access($album_id, "album", $photo_id, "write")) {
			// set the photo as highlight
			$result = dbquery("UPDATE ".$db_prefix."albums SET album_highlight = $photo_id WHERE album_id = $album_id");
			$variables['errormessages'][] = $locale['521'];
			// return to album view
			$type = "album"; $action = "view";
		} elseif (!empty($gallery_id) && has_photo_access($gallery_id, "gallery", $photo_id, "write")) {
			// set the photo as highlight
			$result = dbquery("UPDATE ".$db_prefix."galleries SET gallery_highlight = $photo_id WHERE gallery_id = $gallery_id");
			$variables['errormessages'][] = $locale['523'];
			// return to gallery view
			$type = "gallery"; $action = "view";
		} else {
			$variables['errormessages'][] = $locale['410'];
			// go back to the overview page
			$type = ""; $action = "";
		}
	}

	if ($action == "delete" && isset($_POST['cancel'])) {
		// return to the album
		$type = "album"; $action = "view";
	}

	if ($action == "delete" && isset($_POST['delete'])) {
		// check if the user has access to this album and this photo
		if (!empty($album_id) && has_photo_access($album_id, "album", $photo_id, "write")) {
			delete_photo($album_id, $photo_id);
			$variables['errormessages'][] = $locale['411'];
			// return to the album
			$type = "album"; $action = "view";
		} elseif (!empty($gallery_id) && has_photo_access($gallery_id, "gallery", $photo_id, "write")) {
			// delete the gallery_photo record
			$result = dbquery("DELETE FROM ".$db_prefix."gallery_photos WHERE gallery_id = $gallery_id AND photo_id = $photo_id");
			$result = dbquery("UPDATE ".$db_prefix."galleries SET gallery_highlight = 0 WHERE gallery_id = $gallery_id AND gallery_highlight = $photo_id");
			$variables['errormessages'][] = $locale['412'];
			// return to the album
			$type = "gallery"; $action = "view";
		} else {
			$variables['errormessages'][] = $locale['413'];
			// return to the album
			$type = "album"; $action = "view";
		}
	}
}

if ($type == "album") {

	// save "add" or "edit" album
	if (($action == "add" || $action == "edit") && isset($_POST['save'])) {
		// create the album record from the post values
		$variables['album'] = array();
		$variables['album']['album_id'] = isset($_POST['album_id']) && isNum($_POST['album_id']) && $_POST['album_id'] == $album_id ? $_POST['album_id'] : 0;
		$variables['album']['album_title'] = stripinput($_POST['album_title']);
		$variables['album']['album_description'] = stripinput($_POST['album_description']);
		$variables['album']['album_highlight'] = isset($_POST['album_highlight']) && isNum($_POST['album_highlight']) ? $_POST['album_highlight'] : 0;
		$variables['album']['album_count'] = isset($_POST['album_count']) && isNum($_POST['album_count']) ? $_POST['album_count'] : 0;
		$variables['album']['album_owner'] = isset($_POST['album_owner']) && isNum($_POST['album_owner']) ? $_POST['album_owner'] : 0;
		$variables['album']['album_read'] = isset($_POST['album_read']) && isNum($_POST['album_read']) ? $_POST['album_read'] : -1;
		$variables['album']['album_write'] = isset($_POST['album_write']) && isNum($_POST['album_write']) ? $_POST['album_write'] : -1;
		$variables['album']['album_allow_comments'] = isset($_POST['album_allow_comments']) && isNum($_POST['album_allow_comments']) ? $_POST['album_allow_comments'] : 1;
		$variables['album']['album_allow_ratings'] = isset($_POST['album_allow_ratings']) && isNum($_POST['album_allow_ratings']) ? $_POST['album_allow_ratings'] : 1;
		$variables['album']['album_datestamp'] = time();
		// validate the information
		if ($action == "add" && !$variables['can_create']) {
			$variables['errormessages'][] = $locale['414'];
		}
		if ($action == "edit" && !has_photo_access($variables['album']['album_id'], "album", 0, "write")) {
			$variables['errormessages'][] = $locale['415'];
		}
		if (empty($variables['album']['album_title'])) {
			$variables['errormessages'][] = $locale['416'];
		}
		if (empty($variables['album']['album_description'])) {
			$variables['errormessages'][] = $locale['417'];
		}
		// no errors? save the album
		if (count($variables['errormessages']) == 0) {
			if (dbupdate("albums", "album_id", $variables['album'])) {
				// if this was an add, add the new album to the collection
				if ($action == "add") {
					$collection[$variables['album']['album_datestamp']] = array("type" => 'album', "id" => mysql_insert_id(), "title" => $variables['album']['album_title']);
					$variables['rows']++;
				} elseif ($action == "edit") {
					unset($collection[$_POST['album_datestamp']]);
					$collection[$variables['album']['album_datestamp']] = array("type" => 'album', "id" => $variables['album']['album_id'], "title" => $variables['album']['album_title']);
				}
				krsort($collection);
				// return to the overview screen
				$variables['errormessages'][] = $locale['418'];
				$type = ""; $action = "";
			} else {
				$variables['errormessages'][] = $locale['419'];
				$type = ""; $action = "";
			}
		}
	}

	if ($action == "view" && isset($_POST['assign'])) {
		$photo_id = isset($_POST['photo_id']) && isNum($_POST['photo_id']) ? $_POST['photo_id'] : 0;
		$gallery_id = isset($_POST['gallery_id']) && isNum($_POST['gallery_id']) ? $_POST['gallery_id'] : 0;
		$photo_title = stripinput($_POST['photo_title']);
		if ($photo_id && has_photo_access($album_id, "album", $photo_id) && has_photo_access($gallery_id, "gallery", 0, "write")) {
			$result = dbquery("INSERT IGNORE INTO ".$db_prefix."gallery_photos (gallery_id, photo_id, gallery_photo_title, gallery_photo_datestamp) VALUES ($gallery_id, $photo_id, '".mysql_escape_string($photo_title)."', '".time()."')");
		}
	}

	if ($action == "delete" && isset($_POST['cancel'])) {
			$type = ""; $action = "";
	}

	if ($action == "delete" && isset($_POST['delete'])) {
		if (!has_photo_access($album_id, "album", 0, "write")) {
			$variables['errormessages'][] = $locale['420'];
			$type = ""; $action = "";
		} else {
			delete_album($album_id);
			$variables['errormessages'][] = $locale['421'];
			$type = ""; $action = "";
		}
	}

	switch ($action) {
		case "add":
			if (!$variables['can_create']) {
				$variables['errormessages'][] = $locale['414'];
				$type = ""; $action = "";
			} else {
				// update the panel title
				$title = $locale['422'];
				if (!isset($variables['album'])) {
					// initialize the album record
					$variables['album'] = array();
					$variables['album']['album_id'] = 0;
					$variables['album']['album_title'] = "";
					$variables['album']['album_description'] = "";
					$variables['album']['album_highlight'] = 0;
					$variables['album']['album_count'] = 0;
					$variables['album']['album_owner'] = iMEMBER ? $userdata['user_id'] : 0;
					$variables['album']['user_name'] = iMEMBER ? $userdata['user_name'] : $settings['usera'];
					$variables['album']['album_read'] = -1;
					$variables['album']['album_write'] = -1;
					$variables['album']['album_allow_comments'] = 1;
					$variables['album']['album_allow_ratings'] = 1;
					$variables['album']['album_datestamp'] = time();
				}
			}
			break;
		case "delete":
			if (!has_photo_access($album_id, "album", 0, "write")) {
				$variables['errormessages'][] = $locale['420'];
				$type = ""; $action = "";
			} else {
				// update the panel title
				$title = "Delete album";
				// load the album record
				$result = dbquery("SELECT a.*, p.photo_thumb FROM ".$db_prefix."albums a
					LEFT JOIN ".$db_prefix."photos p ON a.album_highlight = p.photo_id
					WHERE album_id = $album_id 
				");
				if (dbrows($result)) {
					$variables['album'] = dbarray($result);
					if (!empty($variables['album']['photo_thumb']) && file_exists(PATH_PHOTOS.$variables['album']['photo_thumb'])) {
						$variables['album']['photo_thumb'] = PHOTOS.$variables['album']['photo_thumb'];
					} else {
						$variables['album']['photo_thumb'] = file_exists(PATH_THEME."images/photonotfound.jpg") ? THEME."photonotfound.jpg" : IMAGES."photonotfound.jpg";
						$variables['album']['photo_height'] = $settings['thumb_h'];
					}
				} else {
					$variables['errormessages'][] = sprintf($locale['423'], $album_id);
					$type = ""; $action = "";
				}
			}
			break;
		case "edit":
			if (!has_photo_access($album_id, "album", 0, "write")) {
				$variables['errormessages'][] = $locale['415'];
				$type = ""; $action = "";
			} else {
				// update the panel title
				$title = $locale['424'];
				if (!isset($variables['album'])) {
					// load the album record
					$result = dbquery("SELECT a.*, p.photo_thumb FROM ".$db_prefix."albums a
						LEFT JOIN ".$db_prefix."photos p ON a.album_highlight = p.photo_id
						WHERE album_id = $album_id 
					");
					if (dbrows($result)) {
						$variables['album'] = dbarray($result);
						if ($variables['is_moderator'] || (iMEMBER && $userdata['user_id'] == $variables['album']['album_owner'])) {
						} else {
							$variables['errormessages'][] = $locale['415'];
							$type = ""; $action = "";
						}
					} else {
						$variables['errormessages'][] = sprintf($locale['423'], $album_id);
						$type = ""; $action = "";
					}
				}
			}
			break;
			if (!has_photo_access($album_id, "album", 0)) {
				$variables['errormessages'][] = $locale['425'];
				$type = ""; $action = "";
			} else {
				$variables['slideshow'] = array();
				$result = dbquery("SELECT a.*, p.* FROM ".$db_prefix."album_photos a
					INNER JOIN ".$db_prefix."photos p on a.photo_id = p.photo_id
					WHERE a.album_id = $album_id
				");
				while ($data = dbarray($result)) {
					$data['parsed_description'] = parsemessage(array(), is_null($data['album_photo_description'])?"":$data['album_photo_description'], true, true);
					$variables['slideshow'][] = $data;
				}
			}
		case "view":
			if (!has_photo_access($album_id, "album", 0)) {
				$variables['errormessages'][] = $locale['425'];
				$type = ""; $action = "";
			} else {
				// load the album record
				$result = dbquery("SELECT a.*, p.photo_thumb FROM ".$db_prefix."albums a
					LEFT JOIN ".$db_prefix."photos p ON a.album_highlight = p.photo_id
					WHERE album_id = $album_id 
				");
				if (dbrows($result)) {
					$variables['album'] = dbarray($result);
					// set the panel title
					$title = $locale['426']." ".$variables['album']['album_title'];
					// check if there are galleries present
					$variables['album']['galleries'] = 0;
					$variables['album']['collection'] = array();
					foreach($collection as $item) {
						if ($item['type'] == "gallery" && has_photo_access($item['id'], $item['type'], 0, "write")) {
							$variables['album']['galleries'] = 1;
							// add the collection to the variables if any galleries found
							$variables['album']['collection'][] = $item;
							break;
						}
					}
					// find the previous and text albums
					$variables['album']['next'] = 0;
					$variables['album']['previous'] = 0;
					$match = false;
					foreach($collection as $item) {
						// first match on id?
						if (!$match && $item['id'] == $album_id && $item['type'] == "album") {
							// mark the find
							$match = true;
							// and get the next record
							continue;
						}
						// next record
						if ($match) {
							// we found the current album or gallery, get the ID of the next and exit the loop
							$variables['album']['next'] = $item['id'];
							$variables['album']['next_type'] = $item['type'];
							break;
						} else {
							$variables['album']['previous'] = $item['id'];
							$variables['album']['previous_type'] = $item['type'];
						}
					}
					// increment the viewed counter
					$result = dbquery("UPDATE ".$db_prefix."albums SET album_count = album_count + 1 WHERE album_id = $album_id");
					// make sure we have a valid rowstart value
					if (empty($rowstart) || !isNum($rowstart)) $rowstart = 0;
					$variables['rowstart'] = $rowstart;
					// get the number of photo's in this album
					$variables['rows'] = dbfunction("COUNT(*)", "album_photos", "album_id=".$album_id);
					// get the photo's from the selected album page
					$result = dbquery("SELECT a.*, p.* FROM ".$db_prefix."album_photos a
						INNER JOIN ".$db_prefix."photos p ON a.photo_id = p.photo_id
						WHERE a.album_id = $album_id
						LIMIT $rowstart, ".$settings['thumbs_per_page']."
					");
					if (dbrows($result)) {
						$variables['photos'] = array();
						while ($data = dbarray($result)) {
							$data['can_edit'] = has_photo_access($album_id, "album", 0, "write");
							// update the thumb counter
							$result2 = dbquery("UPDATE ".$db_prefix."photos SET photo_thumb_count = photo_thumb_count + 1 WHERE photo_id = ".$data['photo_id']);
							$data['photo_thumb_count']++;
							$data['parsed_album_photo_description'] = parsemessage(array(), is_null($data['album_photo_description'])?"":$data['album_photo_description'], true, true);
							$variables['photos'][] = $data;
						}
					}
				} else {
					$variables['errormessages'][] = sprintf($locale['423'], $album_id);
					$type = ""; $action = "";
				}
			}
			break;
		case "upload":
			if (!has_photo_access($album_id, "album", 0, "write")) {
				$variables['errormessages'][] = $locale['402'];
				$type = ""; $action = "";
			} else {
				// load the album record
				$result = dbquery("SELECT a.*, p.photo_thumb FROM ".$db_prefix."albums a
					LEFT JOIN ".$db_prefix."photos p ON a.album_highlight = p.photo_id
					WHERE album_id = $album_id 
				");
				if (dbrows($result)) {
					$variables['album'] = dbarray($result);
					$variables['album']['photo_count'] = dbfunction("COUNT(*)", "album_photos", "album_id = ".$album_id);
					// SWFUpload needs this, Flash doesn't maintain the session
					$variables['session_id'] = _session_ua();
					$variables['session_name'] = $_COOKIE[$settings['session_name']];
					// to check security when uploading
					$variables['post_parms'] = array("album_id" => $variables['album']['album_id']);
					// allowed file types
					$variables['file_mask'] = "";
					foreach($imagetypes as $imagetype) {
						$variables['file_mask'] .= (empty($variables['file_mask']) ? "" : ";") . "*" . $imagetype;
					}
				} else {
					$variables['errormessages'][] = sprintf($locale['423'], $album_id);
					$type = ""; $action = "";
				}
			}
			break;
		default:
			// unknown action
			break;
	}

	// check if the thumb exists...
	switch ($action) {
		case "add":
			// break ommited intentionally
		case "edit":
			if (!empty($variables['album']['photo_thumb']) && file_exists(PATH_PHOTOS.$variables['album']['photo_thumb'])) {
				$variables['album']['photo_thumb'] = PHOTOS.$variables['album']['photo_thumb'];
			} else {
				$variables['album']['photo_thumb'] = file_exists(PATH_THEME."images/photonotfound.jpg") ? THEME."photonotfound.jpg" : IMAGES."photonotfound.jpg";
				$variables['album']['photo_height'] = $settings['thumb_h'];
			}

			// get the list of available groups
			$variables['user_groups'] = getusergroups(true);
			$variables['all_user_groups'] = getusergroups(false);

			// moderators may change the owner
			if ($variables['is_moderator']) {
				$variables['user_list'] = array();
				$result = dbquery("SELECT u.user_id, u.user_name FROM ".$db_prefix."users u WHERE user_status = 0 ORDER BY user_level DESC, user_name ASC");
				while ($data = dbarray($result)) {
					$variables['user_list'][] = $data;
				}
			}
			break;
		default:
			// unknown or non-relevant action
			break;
	}
}

if ($type == "gallery") {

	// save "add" or "edit" gallery
	if (($action == "add" || $action == "edit") && isset($_POST['save'])) {
		// create the gallery record from the post values
		$variables['gallery'] = array();
		$variables['gallery']['gallery_id'] = isset($_POST['gallery_id']) && isNum($_POST['gallery_id']) && $_POST['gallery_id'] == $gallery_id ? $_POST['gallery_id'] : 0;
		$variables['gallery']['gallery_title'] = isset($_POST['gallery_title']) ? stripinput($_POST['gallery_title']) : "";
		$variables['gallery']['gallery_description'] = isset($_POST['gallery_description']) ? stripinput($_POST['gallery_description']) : "";
		$variables['gallery']['gallery_highlight'] = isset($_POST['gallery_highlight']) && isNum($_POST['gallery_highlight']) ? $_POST['gallery_highlight'] : 0;
		$variables['gallery']['gallery_count'] = isset($_POST['gallery_count']) && isNum($_POST['gallery_count']) ? $_POST['gallery_count'] : 0;
		$variables['gallery']['gallery_owner'] = isset($_POST['gallery_owner']) && isNum($_POST['gallery_owner']) ? $_POST['gallery_owner'] : 0;
		$variables['gallery']['gallery_read'] = isset($_POST['gallery_read']) && isNum($_POST['gallery_read']) ? $_POST['gallery_read'] : -1;
		$variables['gallery']['gallery_write'] = isset($_POST['gallery_write']) && isNum($_POST['gallery_write']) ? $_POST['gallery_write'] : -1;
		$variables['gallery']['gallery_allow_comments'] = isset($_POST['gallery_allow_comments']) && isNum($_POST['gallery_allow_comments']) ? $_POST['gallery_allow_comments'] : 1;
		$variables['gallery']['gallery_allow_ratings'] = isset($_POST['gallery_allow_ratings']) && isNum($_POST['gallery_allow_ratings']) ? $_POST['gallery_allow_ratings'] : 1;
		$variables['gallery']['gallery_datestamp'] = time();
		// validate the information
		if ($action == "add" && !$variables['can_create']) {
			$variables['errormessages'][] = $locale['427'];
		}
		if ($action == "edit" && !has_photo_access($variables['gallery']['gallery_id'], "gallery", 0, "write")) {
			$variables['errormessages'][] = $locale['428'];
		}
		if (empty($variables['gallery']['gallery_title'])) {
			$variables['errormessages'][] = $locale['429'];
		}
		if (empty($variables['gallery']['gallery_description'])) {
			$variables['errormessages'][] = $locale['430'];
		}
		// no errors? save the gallery
		if (count($variables['errormessages']) == 0) {
			if (dbupdate("galleries", "gallery_id", $variables['gallery'])) {
				// if this was an add, add the new gallery to the collection
				if ($action == "add") {
					$collection[$variables['gallery']['gallery_datestamp']] = array("type" => 'gallery', "id" => mysql_insert_id(), "title" => $variables['gallery']['gallery_title']);
					$variables['rows']++;
				} elseif ($action == "edit") {
					unset($collection[$_POST['gallery_datestamp']]);
					$collection[$variables['gallery']['gallery_datestamp']] = array("type" => 'gallery', "id" => $variables['gallery']['gallery_id'], "title" => $variables['gallery']['gallery_title']);
				}
				krsort($collection);
				// return to the overview screen
				$variables['errormessages'][] = $locale['431'];
				$type = ""; $action = "";
			} else {
				$variables['errormessages'][] = $locale['432'];
				$type = ""; $action = "";
			}
		}
	}

	if ($action == "delete" && isset($_POST['cancel'])) {
			$type = ""; $action = "";
	}

	if ($action == "delete" && isset($_POST['delete'])) {
		if (!has_photo_access($gallery_id, "gallery", 0, "write")) {
			$variables['errormessages'][] = $locale['433'];
			$type = ""; $action = "";
		} else {
			delete_gallery($gallery_id);
			$variables['errormessages'][] = $locale['434'];
			$type = ""; $action = "";
		}
	}

	switch ($action) {
		case "add":
			if (!$variables['can_create']) {
				$variables['errormessages'][] = $locale['427'];
				$type = ""; $action = "";
			} else {
				// update the panel title
				$title = $locale['435'];
				if (!isset($variables['gallery'])) {
					// initialize the gallery record
					$variables['gallery'] = array();
					$variables['gallery']['gallery_id'] = 0;
					$variables['gallery']['gallery_title'] = "";
					$variables['gallery']['gallery_description'] = "";
					$variables['gallery']['gallery_highlight'] = 0;
					$variables['gallery']['gallery_count'] = 0;
					$variables['gallery']['gallery_owner'] = iMEMBER ? $userdata['user_id'] : 0;
					$variables['gallery']['user_name'] = iMEMBER ? $userdata['user_name'] : $settings['usera'];
					$variables['gallery']['gallery_read'] = -1;
					$variables['gallery']['gallery_write'] = -1;
					$variables['gallery']['gallery_allow_comments'] = 1;
					$variables['gallery']['gallery_allow_ratings'] = 1;
					$variables['gallery']['gallery_datestamp'] = time();
				}
			}
			break;
		case "delete":
			if (!has_photo_access($gallery_id, "gallery", 0, "write")) {
				$variables['errormessages'][] = $locale['433'];
				$type = ""; $action = "";
			} else {
				// update the panel title
				$title = $locale['436'];
				// load the gallery record
				$result = dbquery("SELECT g.*, p.photo_thumb FROM ".$db_prefix."galleries g
					LEFT JOIN ".$db_prefix."photos p ON g.gallery_highlight = p.photo_id
					WHERE g.gallery_id = $gallery_id 
				");
				if (dbrows($result)) {
					$variables['gallery'] = dbarray($result);
					if (!empty($variables['gallery']['photo_thumb']) && file_exists(PATH_PHOTOS.$variables['gallery']['photo_thumb'])) {
						$variables['gallery']['photo_thumb'] = PHOTOS.$variables['gallery']['photo_thumb'];
					} else {
						$variables['gallery']['photo_thumb'] = file_exists(PATH_THEME."images/photonotfound.jpg") ? THEME."photonotfound.jpg" : IMAGES."photonotfound.jpg";
						$variables['gallery']['photo_height'] = $settings['thumb_h'];
					}
				} else {
					$variables['errormessages'][] = sprintf($locale['437'], $gallery_id);
					$type = ""; $action = "";
				}
			}
			break;
		case "edit":
			if (!has_photo_access($gallery_id, "gallery", 0, "write")) {
				$variables['errormessages'][] = $locale['428'];
				$type = ""; $action = "";
			} else {
				// update the panel title
				$title = $locale['438'];
				if (!isset($variables['gallery'])) {
					// load the gallery record
					$result = dbquery("SELECT g.*, p.photo_thumb FROM ".$db_prefix."galleries g
						LEFT JOIN ".$db_prefix."photos p ON g.gallery_highlight = p.photo_id
						WHERE g.gallery_id = $gallery_id 
					");
					if (dbrows($result)) {
						$variables['gallery'] = dbarray($result);
						if ($variables['is_moderator'] || (iMEMBER && $userdata['user_id'] == $variables['gallery']['gallery_owner'])) {
						} else {
							$variables['errormessages'][] = $locale['428'];
							$type = ""; $action = "";
						}
					} else {
						$variables['errormessages'][] = sprintf($locale['437'], $gallery_id);
						$type = ""; $action = "";
					}
				}
			}
			break;
			if (!has_photo_access($gallery_id, "gallery", 0)) {
				$variables['errormessages'][] = $locale['439'];
				$type = ""; $action = "";
			} else {
				$variables['slideshow'] = array();
				$result = dbquery("SELECT g.*, p.* FROM ".$db_prefix."gallery_photos g
					INNER JOIN ".$db_prefix."photos p on g.photo_id = p.photo_id
					WHERE g.gallery_id = $gallery_id
				");
				while ($data = dbarray($result)) {
					$data['parsed_description'] = parsemessage(array(), is_null($data['gallery_photo_description'])?"":$data['gallery_photo_description'], true, true);
					$variables['slideshow'][] = $data;
				}
			}
		case "view":
			if (!has_photo_access($gallery_id, "gallery", 0)) {
				$variables['errormessages'][] = $locale['439'];
				$type = ""; $action = "";
			} else {
				// load the gallery record
				$result = dbquery("SELECT g.*, p.photo_thumb FROM ".$db_prefix."galleries g
					LEFT JOIN ".$db_prefix."photos p ON g.gallery_highlight = p.photo_id
					WHERE g.gallery_id = $gallery_id 
				");
				if (dbrows($result)) {
					$variables['gallery'] = dbarray($result);
					// set the panel title
					$title = $locale['440']." ".$variables['gallery']['gallery_title'];
					// find the previous and next
					$variables['gallery']['next'] = 0;
					$variables['gallery']['previous'] = 0;
					$match = false;
					foreach($collection as $item) {
						// first match on id?
						if (!$match && $item['id'] == $gallery_id && $item['type'] == "gallery") {
							// mark the find
							$match = true;
							// and get the next record
							continue;
						}
						// next record
						if ($match) {
							// we found the current album or gallery, get the ID of the next and exit the loop
							$variables['gallery']['next'] = $item['id'];
							$variables['gallery']['next_type'] = $item['type'];
							break;
						} else {
							$variables['gallery']['previous'] = $item['id'];
							$variables['gallery']['previous_type'] = $item['type'];
						}
					}
					// increment the viewed counter
					$result = dbquery("UPDATE ".$db_prefix."galleries SET gallery_count = gallery_count + 1 WHERE gallery_id = $gallery_id");
					// make sure we have a valid rowstart value
					if (empty($rowstart) || !isNum($rowstart)) $rowstart = 0;
					$variables['rowstart'] = $rowstart;
					// get the number of photo's in this gallery
					$variables['rows'] = dbfunction("COUNT(*)", "gallery_photos", "gallery_id=".$gallery_id);
					// get the photo's from the selected gallery page
					$result = dbquery("SELECT g.*, p.* FROM ".$db_prefix."gallery_photos g
						INNER JOIN ".$db_prefix."photos p ON g.photo_id = p.photo_id
						WHERE g.gallery_id = $gallery_id
						LIMIT $rowstart, ".$settings['thumbs_per_page']."
					");
					if (dbrows($result)) {
						$variables['photos'] = array();
						while ($data = dbarray($result)) {
							$data['can_edit'] = has_photo_access($gallery_id, "gallery", 0, "write");
							// update the thumb counter
							$result2 = dbquery("UPDATE ".$db_prefix."photos SET photo_thumb_count = photo_thumb_count + 1 WHERE photo_id = ".$data['photo_id']);
							$data['photo_thumb_count']++;
							$data['parsed_gallery_photo_description'] = parsemessage(array(), is_null($data['gallery_photo_description'])?"":$data['gallery_photo_description'], true, true);
							$variables['photos'][] = $data;
						}
					}
				} else {
					$variables['errormessages'][] = sprintf($locale['437'], $gallery_id);
					$type = ""; $action = "";
				}
			}
			break;
		default:
			// unknown action
			break;
	}

	// check if the thumb exists...
	switch ($action) {
		case "add":
			// break ommited intentionally
		case "edit":
			if (!empty($variables['gallery']['photo_thumb']) && file_exists(PATH_PHOTOS.$variables['gallery']['photo_thumb'])) {
				$variables['gallery']['photo_thumb'] = PHOTOS.$variables['gallery']['photo_thumb'];
			} else {
				$variables['gallery']['photo_thumb'] = file_exists(PATH_THEME."images/photonotfound.jpg") ? THEME."photonotfound.jpg" : IMAGES."photonotfound.jpg";
				$variables['gallery']['photo_height'] = $settings['thumb_h'];
			}

			// get the list of available groups
			$variables['user_groups'] = getusergroups(true);
			$variables['all_user_groups'] = getusergroups(false);

			// moderators may change the owner
			if ($variables['is_moderator']) {
				$variables['user_list'] = array();
				$result = dbquery("SELECT u.user_id, u.user_name FROM ".$db_prefix."users u WHERE user_status = 0 ORDER BY user_level DESC, user_name ASC");
				while ($data = dbarray($result)) {
					$variables['user_list'][] = $data;
				}
			}
			break;
		default:
			// unknown or non-relevant action
			break;
	}
}

// generate the overview page
if ($type == "") {

	// make sure we have a valid rowstart value
	if (empty($rowstart) || !isNum($rowstart)) $rowstart = 0;
	$variables['rowstart'] = $rowstart;

	// array to store the albums and galleries found
	$variables['collection'] = array();

	// get the albums and galleries from the collection
	$i = 0;
	foreach($collection as $key => $value) {
		// find the correct starting point
		if ($i++ < $rowstart) continue;
		// break if we've found enough
		if ($i - $rowstart > $settings['albums_per_page']) break;
		// add this entry to the collection
		if (isset($data)) unset($data);
		switch ($value['type']) {
			case "album":
				$result = dbquery("SELECT a.*, p.*, u.user_id, u.user_name FROM ".$db_prefix."albums a
					LEFT JOIN ".$db_prefix."photos p ON a.album_highlight = p.photo_id
					LEFT JOIN ".$db_prefix."users u ON a.album_owner = u.user_id
					WHERE a.album_id = ".$value['id']);
				if (dbrows($result)) {
					$data = dbarray($result);
					$data['description'] = parsemessage(array(), $data['album_description'], true, true);
					$data['owner'] = $data['album_owner'];
					$data['count'] = dbfunction("COUNT(*)", "album_photos", "album_id = ".$data['album_id']);
					// get the photo's of this album for the slideshow
					$slideshow = array();
					$result = dbquery("SELECT a.*, p.* FROM ".$db_prefix."album_photos a
						INNER JOIN ".$db_prefix."photos p on a.photo_id = p.photo_id
						WHERE a.album_id = ".$value['id']);
					while ($data2 = dbarray($result)) {
						$data2['parsed_description'] = parsemessage(array(), is_null($data2['album_photo_description'])?"":$data2['album_photo_description'], true, true);
						$slideshow[] = $data2;
					}
					$data['slideshow'] = $slideshow;
				}
				// check if this album is editable
				$data['can_edit'] = has_photo_access($value['id'], "album", 0, "write");
				break;
			case "gallery":
				$result = dbquery("SELECT g.*, p.*, u.user_id, u.user_name FROM ".$db_prefix."galleries g
					LEFT JOIN ".$db_prefix."photos p ON g.gallery_highlight = p.photo_id
					LEFT JOIN ".$db_prefix."users u ON g.gallery_owner = u.user_id
					WHERE g.gallery_id = ".$value['id']);
				if (dbrows($result)) {
					$data = dbarray($result);
					$data['description'] = parsemessage(array(), $data['gallery_description'], true, true);
					$data['owner'] = $data['gallery_owner'];
					$data['count'] = dbfunction("COUNT(*)", "gallery_photos", "gallery_id = ".$data['gallery_id']);
					// get the photo's of this album for the slideshow
					$slideshow = array();
					$result = dbquery("SELECT g.*, p.* FROM ".$db_prefix."gallery_photos g
						INNER JOIN ".$db_prefix."photos p on g.photo_id = p.photo_id
						WHERE g.gallery_id = ".$value['id']);
					while ($data2 = dbarray($result)) {
						$data2['parsed_description'] = parsemessage(array(), is_null($data2['gallery_photo_description'])?"":$data2['gallery_photo_description'], true, true);
						$slideshow[] = $data2;
					}
					$data['slideshow'] = $slideshow;
				// check if this album is editable
				$data['can_edit'] = has_photo_access($value['id'], "gallery", 0, "write");
				}
				break;
			default:
				// unknown type
				break;
		}
		if (isset($data)) {
			// check if the thumb exists...
			if (!empty($data['photo_thumb']) && file_exists(PATH_PHOTOS.$data['photo_thumb'])) {
				$data['photo_thumb'] = PHOTOS.$data['photo_thumb'];
			} else {
				$data['photo_thumb'] = file_exists(PATH_THEME."images/photonotfound.jpg") ? THEME."photonotfound.jpg" : IMAGES."photonotfound.jpg";
				$data['photo_height'] = $settings['thumb_h'];
			}
			$variables['collection'][] = $data;
		}
	}
	$variables['collection_count'] = count($variables['collection']);
}

// store the action and the type
$variables['type'] = $type;
$variables['action'] = $action;

// load the hoteditor if needed
if ($settings['hoteditor_enabled'] && (!iMEMBER || $userdata['user_hoteditor'])) {
	if (!defined('LOAD_HOTEDITOR')) define('LOAD_HOTEDITOR', true);
}

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'albums', 'title' => $title, 'template' => 'main.albums.tpl', 'locale' => array("main.albums"));
$template_variables['albums'] = $variables;

// check if we need to display ratings
if (isset($variables['photo']['album_allow_ratings']) && $variables['photo']['album_allow_ratings']) {
	include PATH_INCLUDES."ratings_include.php";
	showratings("B",$variables['photo']['album_photo_id'],FUSION_SELF."?type=photo&action=view&album_id=".$variables['photo']['album_id']."&photo_id=".$variables['photo']['photo_id']);
}
// check if we need to display ratings
if (isset($variables['photo']['gallery_allow_ratings']) && $variables['photo']['gallery_allow_ratings']) {
	include PATH_INCLUDES."ratings_include.php";
	showratings("G",$variables['photo']['gallery_photo_id'],FUSION_SELF."?type=photo&action=view&gallery_id=".$variables['photo']['gallery_id']."&photo_id=".$variables['photo']['photo_id']);
}

// check if we need to display comments
if (isset($variables['photo']['album_allow_comments']) && $variables['photo']['album_allow_comments']) {
	include PATH_INCLUDES."comments_include.php";
	showcomments("B","album_photos","album_photo_id",$variables['photo']['album_photo_id'],FUSION_SELF."?type=photo&action=view&album_id=".$variables['photo']['album_id']."&photo_id=".$variables['photo']['photo_id'],$variables['can_edit']);
}
// check if we need to display comments
if (isset($variables['photo']['gallery_allow_comments']) && $variables['photo']['gallery_allow_comments']) {
	include PATH_INCLUDES."comments_include.php";
	showcomments("G","gallery_photos","gallery_photo_id",$variables['photo']['gallery_photo_id'],FUSION_SELF."?type=photo&action=view&gallery_id=".$variables['photo']['gallery_id']."&photo_id=".$variables['photo']['photo_id'],$variables['can_edit']);
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>
