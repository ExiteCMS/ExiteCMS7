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
if (strpos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false || !defined('INIT_CMS_OK')) die();

// array's to store the variables for this panel
$variables = array();

// temp storage for the latest image
$photo = array();

// get the most recent (updated) photo from the albums that are visible for this user
$result = dbquery("SELECT a.album_id, p.photo_id, p.album_photo_datestamp FROM ".$db_prefix."albums a
	INNER JOIN ".$db_prefix."album_photos p ON a.album_id = p.album_id
	WHERE ".(iMEMBER ? ("a.album_owner = '".$userdata['user_id']."' OR (") : "(").groupaccess('a.album_read').")
	ORDER BY p.album_photo_datestamp DESC LIMIT 1");

// if found, store the album and photo id
if (dbrows($result)) {
	$photo = dbarray($result);
}

// get the most recent (updated) photo from the galleries that are visible for this user
$result = dbquery("SELECT g.gallery_id, p.photo_id, p.gallery_photo_datestamp FROM ".$db_prefix."galleries g
	INNER JOIN ".$db_prefix."gallery_photos p ON g.gallery_id = p.gallery_id
	WHERE ".(iMEMBER ? ("g.gallery_owner = '".$userdata['user_id']."' OR (") : "(").groupaccess('g.gallery_read').")
	ORDER BY p.gallery_photo_datestamp DESC LIMIT 1");

// if found, get the gallery and photo id
if (dbrows($result)) {
	if (isset($photo['album_photo_datestamp'])) {
		// check if this image is newer
		$data = dbarray($result);
		if ($data['gallery_photo_datestamp'] > $photo['album_photo_datestamp']) {
			$photo = $data;
		}
	} else {
		// just store it
		$photo = dbarray($result);
	}
}

// get the photo record for that id
if (isset($photo['album_id'])) {
	// get the album and photo record
	$result = dbquery("SELECT * FROM ".$db_prefix."album_photos ap
		INNER JOIN ".$db_prefix."albums a ON a.album_id = ap.album_id
		INNER JOIN ".$db_prefix."photos p ON p.photo_id = ap.photo_id
		WHERE ap.album_id = ".$photo['album_id']." AND ap.photo_id = ".$photo['photo_id']);
} elseif (isset($photo['gallery_id'])) {
	// get the gallery and photo record
	$result = dbquery("SELECT * FROM ".$db_prefix."gallery_photos gp
		INNER JOIN ".$db_prefix."galleries g ON g.gallery_id = gp.gallery_id
		INNER JOIN ".$db_prefix."photos p ON p.photo_id = gp.photo_id
		WHERE gp.gallery_id = ".$photo['gallery_id']." AND gp.photo_id = ".$photo['photo_id']);
} else {
	// no record present
	$result = false;
}

// if a record was found
if (dbrows($result)) {
	// fetch it
	$variables['image'] = dbarray($result);
} else {
	// else disable the panel
	$no_panel_displayed = true;
}

$template_variables['modules.album_panels.latest_image_panel'] = $variables;
?>
