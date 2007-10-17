<?php
require_once dirname(__FILE__)."/../../includes/core_functions.php";

// webmaster or CGI tool only!
if ((!iMEMBER || $userdata['user_id'] != 1) && isset($_SERVER['SERVER_SOFTWARE'])) fallback(BASEDIR."index.php");

if (isset($_SERVER['SERVER_SOFTWARE'])) echo "<html><head></head><body><pre>";

// get all user records which an avatar defined

echo "* Getting all user records with an avatar...\n";

$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_avatar != ''");
while ($data = dbarray($result)) {

	// check if the avatar exists on disk
	if (!file_exists(PATH_IMAGES."avatars/".$data['user_avatar'])) {
		// not found on disk. Remove the avatar from the user record
		$result2 = dbquery("UPDATE ".$db_prefix."users SET user_avatar='' WHERE user_id = '".$data['user_id']."'");
		echo "- avatar '".$data['user_avatar']."' for user '".$data['user_name']."' not found on disk!\n";
	} else {
		// generate a new filename
		$avatarext = strtolower(strrchr($data['user_avatar'],"."));
		while ($avatarname = md5(microtime())) {
			if (!file_exists(PATH_IMAGES."avatars/".$avatarname.$avatarext)) {
				break;
			}
		};
		// rename the avatar on disk
		rename(PATH_IMAGES."avatars/".$data['user_avatar'], PATH_IMAGES."avatars/".$avatarname.$avatarext);
		// update the user record
		$result2 = dbquery("UPDATE ".$db_prefix."users SET user_avatar='".$avatarname.$avatarext."' WHERE user_id = '".$data['user_id']."'");
		echo "- avatar '".$data['user_avatar']."' for user '".$data['user_name']."' has been renamed to '".$avatarname.$avatarext."'.\n";
	}
}

if (isset($_SERVER['SERVER_SOFTWARE'])) echo "</pre></body></html>";
?>