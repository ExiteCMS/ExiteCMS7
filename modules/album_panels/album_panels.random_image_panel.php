<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2008 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
if (eregi("album_panels.random_image_panel.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// array's to store the variables for this panel
$variables = array();

// temp storage for the available images
$images = array();

// get the photo's from the albums that are visible for this user
$result = dbquery("SELECT a.album_id, p.photo_id FROM ".$db_prefix."albums a
	INNER JOIN ".$db_prefix."album_photos p ON a.album_id = p.album_id
	WHERE ".(iMEMBER ? ("a.album_owner = '".$userdata['user_id']."' OR (") : "(").groupaccess('a.album_read').")");

// store the album and photo id's
while ($data = dbarray($result)) {
	$images[$data['photo_id']] = array('album_id' => $data['album_id'], 'photo_id' => $data['photo_id']);
}

// add the photo's from the galleries that are visible for this user
$result = dbquery("SELECT g.gallery_id, p.photo_id FROM ".$db_prefix."galleries g
	INNER JOIN ".$db_prefix."gallery_photos p ON g.gallery_id = p.gallery_id
	WHERE ".(iMEMBER ? ("g.gallery_owner = '".$userdata['user_id']."' OR (") : "(").groupaccess('g.gallery_read').")");

// store the gallery and photo id's
while ($data = dbarray($result)) {
	// don't add the same photo twice
	if (!isset($images[$data['photo_id']])) {
		$images[$data['photo_id']] = array('gallery_id' => $data['gallery_id'], 'photo_id' => $data['photo_id']);
	}
}

// extract a random photo
$photo = $images[array_rand($images)];

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

// if a record was  found
if (dbrows($result)) {
	// fetch it
	$variables['image'] = dbarray($result);
} else {
	// else disable the panel
	$no_panel_displayed = true;
}

$template_variables['modules.album_panels.random_image_panel'] = $variables;
?>
