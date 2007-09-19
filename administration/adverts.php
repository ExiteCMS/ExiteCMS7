<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| Some portions copyright ? 2002 - 2006 Nick Jones   |
| http://www.php-fusion.co.uk/                       |
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
require_once PATH_LOCALE.LOCALESET."admin/adverts.php";

// temp storage for template variables
$variables = array();

// check for the proper admin access rights
if (!checkrights("wE") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// define who is allowed to use the 'move ad to new client' function
// default only superadmins, change this to iADMIN to allow every member
// with access to this module to have access to it
define('ADVERT_MOVE_AD', iSUPERADMIN);

// assign default values to variables used
$errormessage = "";
$errortitle = "";

// add client requested?
if (isset($_POST['add'])) $action = "add";

// delete client requested?
if (isset($_POST['delclient'])) $action = "delclient";

// add a new ad requested?
if (isset($_POST['addad'])) $action = "addad";

// image management requested?
if (isset($_POST['images'])) $action = "images";

// cancel requested?
if (isset($_POST['cancel'])) $action = "list";

// expire requested
if (isset($_POST['expire'])) {
	$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_expired = '1' WHERE adverts_id = '".$adverts_id."'");
	$action = "list";
}

// activate requested
if (isset($_POST['activate'])) {
	$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_expired = '0' WHERE adverts_id = '".$adverts_id."'");
	$action = "list";
}

// move to a new client requested
if (isset($_POST['moveuser'])) {
	$result = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '$id' AND user_sponsor = '1'");
	if (dbrows($result)) {
		$user_from = dbarray($result);
		if (isset($_POST['newid']) && isNum($_POST['newid'])) {
			$result = dbquery("SELECT user_name, user_sponsor FROM ".$db_prefix."users WHERE user_id = '".$_POST['newid']."'");
			if (dbrows($result)) {
				$user_to = dbarray($result);
				if (isset($_POST['adverts_id']) && isNum($_POST['adverts_id'])) {
					$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_id = '".$_POST['adverts_id']."'");
					if (dbrows($result)) {
						$advert = dbarray($result);
						// make sure the new client is marked as sponsor
						$result = dbquery("UPDATE ".$db_prefix."users SET user_sponsor = '1' WHERE user_id = '".$_POST['newid']."'");
						// move the advert to the new client
						$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_userid = '".$_POST['newid']."' WHERE adverts_id = '".$_POST['adverts_id']."'");
						// copy the advert image if needed
						if (substr($advert['adverts_image'],0,strlen($user_from['user_name'])) == $user_from['user_name']) {
							$ad_image = substr($advert['adverts_image'],strlen($user_from['user_name'])+1);
							if (!copy(PATH_IMAGES_ADS.$advert['adverts_image'], PATH_IMAGES_ADS.$user_to['user_name']."_".$ad_image)) {
								die('copy failed!');
							} else {
								$errormessage = sprintf($locale['920'], $user_from['user_name'], $user_to['user_name']);
								$action = "list";
							}
						} 
					} else {
						$errormessage = $locale['921'];	// ad not found in the database
					}
				} else {
					$errormessage = $locale['922'];	// invalid advert_id passed in the POST
				}
			} else {
				$errormessage = $locale['923'];	// client ID not found in the database
			}
		} else {
			$errormessage = $locale['924'];	// invalid client ID passed in the POST
		}
	} else {
		$errormessage = $locale['923'];	// client ID not found in the database
	}
}

// save requested?
if (isset($_POST['save'])) {
	switch($action) {
		case "add":
			if (isNum($_POST['new_sponsor'])) $result = dbquery("UPDATE ".$db_prefix."users SET user_sponsor = '1' WHERE user_id = '".$_POST['new_sponsor']."'");
			$action = "list";
			break;
		case "addad":
		case "edit":
			// validate the input
			$adverts_userid = $_POST['adverts_userid'];
			$adverts_contract = $_POST['adverts_contract'];
			$adverts_priority = $_POST['adverts_priority'];
			$adverts_location = $_POST['adverts_location'];
			$adverts_url = $_POST['adverts_url'];
			$adverts_status = $_POST['adverts_status'];
			$adverts_image = $_POST['adverts_image'];
			$adverts_contract_start = 0;
			if (isset($_POST['contract_start']['mday']) && isset($_POST['contract_start']['mon']) && isset($_POST['contract_start']['year'])) $adverts_contract_start = mktime(0,0,0,$_POST['contract_start']['mon'],$_POST['contract_start']['mday'],$_POST['contract_start']['year']);
			if ($adverts_contract_start != 0) $adverts_contract_start = time_local2system($adverts_contract_start);
			$adverts_contract_end  = 0;
			if ($_POST['contract_end']['mday']!="--" && $_POST['contract_end']['mon']!="--" && $_POST['contract_end']['year']!="----") $adverts_contract_end = mktime(23,59,59,$_POST['contract_end']['mon'],$_POST['contract_end']['mday'],$_POST['contract_end']['year']);
			if ($adverts_contract_end != 0) $adverts_contract_end = time_local2system($adverts_contract_end);
			$adverts_sold = $_POST['adverts_sold'];
			if (!isNum($_POST['adverts_purchased'])) {
				$errormessage .= $locale['903']."<br />";
			} else {
				$adverts_sold = $adverts_sold + ($_POST['change']=="-"?-1:1) * $_POST['adverts_purchased'];
				if ($adverts_sold < 0) {
					$errormessage .= $locale['904']."<br />";
				}
			}
			// reset enddate if not based on a fixed date
			if ($adverts_contract != 1) $adverts_contract_end = 0;
			// reset sold if not based on display numbers
			if ($adverts_contract != 2) $adverts_sold = 0;
			// check image dimension against the selected advert type
			if ($adverts_image == "") {
				// can't be enabled without an image
				$adverts_status = 0;
			} else {
				$dimensions = @getimagesize(PATH_IMAGES_ADS.$adverts_image);
				$maxsize = explode("x", $ad_dimensions[$adverts_location]);
				if ($dimensions[0] > $maxsize[0] || $dimensions[1] > $maxsize[1]) {
					$errormessage = sprintf($locale['912'], $ad_dimensions[$adverts_location], $dimensions[0]."x".$dimensions[1]);
				}
			}
			if ($errormessage == "") {
				// save the posted data
				switch ($action) {
					case "addad":
						$result = dbquery("INSERT INTO ".$db_prefix."adverts (adverts_userid, adverts_contract, adverts_contract_start, adverts_contract_end, adverts_priority, adverts_location, adverts_url, adverts_sold, adverts_image, adverts_status) 
							VALUES ('".$adverts_userid."', '".$adverts_contract."', '".$adverts_contract_start."', '".$adverts_contract_end."', '".$adverts_priority."', '".$adverts_location."', '".$adverts_url."', '".$adverts_sold."', '".$adverts_image."', '".$adverts_status."')");
						$adverts_id = mysql_insert_id();
						$errormessage = $locale['906'];
						break;
					case "edit":
						$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_userid = '".$adverts_userid."', adverts_contract = '".$adverts_contract."', adverts_contract_start = '".$adverts_contract_start."', adverts_contract_end = '".$adverts_contract_end."',
							adverts_priority = '".$adverts_priority."', adverts_location = '".$adverts_location."', adverts_url = '".$adverts_url."', adverts_sold = '".$adverts_sold."', adverts_image = '".$adverts_image."', adverts_status = '".$adverts_status."'
							WHERE adverts_id = '".$adverts_id."'");
						$errormessage = $locale['907'];
						break;
					default:
						break;
				}
				if ($adverts_contract_start > time() || ($adverts_contract_end > 0  && $adverts_contract_end < time()))
					$adverts_expired = 1;
				else
					$adverts_expired = 0;
				$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_expired = '".$adverts_expired."' WHERE adverts_id = '".$adverts_id."'");
				$action = "list";	
			} else {
				$errortitle = $locale['900'];
			}
	}
}

// upload image (from add/edit or from image management)?
if (isset($_POST['upload']) || isset($_POST['uploadimage'])) {
		if (isset($_FILES['myfile']['name']) && $_FILES['myfile']['name'] != "") {
		$image_types = array(".gif",".GIF",".jpeg",".JPEG",".jpg",".JPG",".png",".PNG");
		$imgext = strrchr($_FILES['myfile']['name'], ".");
		$imgname = $_FILES['myfile']['name'];
		if (isset($_POST['ad_client'])) $imgname = $_POST['ad_client']."_".$imgname;
		$imgname = PATH_IMAGES_ADS.$imgname;
		$imgsize = $_FILES['myfile']['size'];
		$imgtemp = $_FILES['myfile']['tmp_name'];
		if (!in_array($imgext, $image_types)) {
			$errormessage = $locale['960'];
		} elseif (is_uploaded_file($imgtemp)){
			if (@getimagesize($imgtemp) && @verify_image($imgtemp)) {
				// check if the file exists. if so, suffix it
				$counter = 1;
				$destfile = $imgname;
				while (file_exists($destfile)) {
					$destfile = $imgname."_".$counter;
					$counter++;
				}
				// move the uploaded file
				move_uploaded_file($imgtemp, $destfile);
				chmod($destfile,0664);
			} else {
				$errormessage = $locale['961'];
			}
		} else {
			$errormessage = $locale['962'];
		}
		// save the action to be able to reload the form data later
		if (isset($_POST['upload'])) {
			$action_save = $action;
			$action = "upload";
		}
	}
}

// set default action if one isn't set
if (!isset($action)) $action = "list";

// delete client confirmation (outside the switch, need to fall trough to list
if ($action == "delclientconf") {
	if (isset($_POST['yes'])) {
		$result = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '$id' AND user_sponsor = '1'");
		if ($data = dbarray($result)) {
			$ad_client = $data['user_name'];
			// get the list of advert images
			$adverts_images = array();
			$temp = opendir(PATH_IMAGES_ADS);
			while ($file = readdir($temp)) {
				if ($file{0} != "." && !in_array($file, array("/", "index.php")) && !is_dir(PATH_IMAGES_ADS.$file)) {
					if (substr($file,0,strlen($ad_client))==$ad_client ) $adverts_images[] = $file;
				}
			}
			closedir($temp);
			foreach($adverts_images as $ad_image) {
				unlink(PATH_IMAGES_ADS.$ad_image);
			}
			$result = dbquery("DELETE FROM ".$db_prefix."adverts WHERE adverts_userid = '$id'");
			$result = dbquery("UPDATE ".$db_prefix."users SET user_sponsor = '0' WHERE user_id = '$id'");
			$errortitle = $locale['476'];
			$errormessage = $locale['910'];
		}
	}
	$action = "list";
}

if ($action == "enable") {
	$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_status = '1' WHERE adverts_id = '".$adverts_id."'");
	$errormessage = sprintf($locale['913'], $adverts_id);
	$action = "list";
}
if ($action == "disable") {
	$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_status = '0' WHERE adverts_id = '".$adverts_id."'");
	$errormessage = sprintf($locale['914'], $adverts_id);
	$action = "list";
}

// set the title and prepare the action
switch ($action) {
	case "delclient":
		$title = $locale['476'];
		$result = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '$id'");
		if ($data = dbarray($result)) {
			$ad_client = $data['user_name'];
		}
		break;
	case "imgview":
		$view_image = $image;
		$action = "images";
		break;
	case "imgdel":
		$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_status = '0' WHERE adverts_image = '".$image."'");
		unlink(PATH_IMAGES_ADS.$image);
		$errormessage = $locale['971'];
		$action = "images";
		break;
	case "images":
		break;
	case "addad":
		$title = $locale['400'];
		$result = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '$id'");
		if ($data = dbarray($result)) {
			$title .= $locale['408']."<b>".$data['user_name']."</b>";
			$ad_client = $data['user_name'];
			$ad_client_id = $id;
		}
		if (isset($_POST['adverts_userid'])) {
			$adverts_id = isset($_POST['adverts_id'])?$_POST['adverts_id']:0;
			$adverts_userid = $_POST['adverts_userid'];
			$adverts_contract = $_POST['adverts_contract'];
			$adverts_contract_start = 0;
			if (isset($_POST['contract_start']['mday']) && isset($_POST['contract_start']['mon']) && isset($_POST['contract_start']['year'])) $adverts_contract_start = mktime(0,0,0,$_POST['contract_start']['mon'],$_POST['contract_start']['mday'],$_POST['contract_start']['year']);
			$adverts_contract_end = 0;
			if ($_POST['contract_end']['mday']!="--" && $_POST['contract_end']['mon']!="--" && $_POST['contract_end']['year']!="----") $adverts_contract_end = mktime(23,59,59,$_POST['contract_end']['mon'],$_POST['contract_end']['mday'],$_POST['contract_end']['year']);
			$adverts_priority = $_POST['adverts_priority'];
			$adverts_location = $_POST['adverts_location'];
			$adverts_url = $_POST['adverts_url'];
			$adverts_sold = $_POST['adverts_sold'];
			$adverts_image = $_POST['adverts_image'];
			$adverts_html = "";
			$adverts_status = $_POST['adverts_status'];
			$adverts_expired = $_POST['adverts_expired'];
		}
		break;
	case "add":
		$title = $locale['447'];
		break;
	case "edit":
		$title = $locale['401'];
		$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_id = '$adverts_id'");
		if (dbrows($result) == 0) {
			$errormessage = $locale['901'];
		} else {
			// initialise variables
			if (isset($_POST['adverts_userid'])) {
				$adverts_id = isset($_POST['adverts_id'])?$_POST['adverts_id']:0;
				$adverts_userid = $_POST['adverts_userid'];
				$adverts_contract = $data['adverts_contract'];
				$adverts_contract = $_POST['adverts_contract'];
				$adverts_contract_start = 0;
				if (isset($_POST['contract_start']['mday']) && isset($_POST['contract_start']['mon']) && isset($_POST['contract_start']['year'])) $adverts_contract_start = mktime(0,0,0,$_POST['contract_start']['mon'],$_POST['contract_start']['mday'],$_POST['contract_start']['year']);
				$adverts_contract_end = 0;
				if ($_POST['contract_end']['mday']!="--" && $_POST['contract_end']['mon']!="--" && $_POST['contract_end']['year']!="----") $adverts_contract_end = mktime(23,59,59,$_POST['contract_end']['mon'],$_POST['contract_end']['mday'],$_POST['contract_end']['year']);
				$adverts_priority = $_POST['adverts_priority'];
				$adverts_location = $_POST['adverts_location'];
				$adverts_url = $_POST['adverts_url'];
				$adverts_sold = $_POST['adverts_sold'];
				$adverts_image = $_POST['adverts_image'];
				$adverts_html = "";
				$adverts_status = $_POST['adverts_status'];
				$adverts_expired = $_POST['adverts_expired'];
			} else {
				$data = dbarray($result);
				$adverts_id = $data['adverts_id'];
				$adverts_userid = $data['adverts_userid'];
				$adverts_contract = $data['adverts_contract'];
				$adverts_contract_start = $data['adverts_contract_start'];
				$adverts_contract_end = $data['adverts_contract_end'];
				$adverts_priority = $data['adverts_priority'];
				$adverts_location = $data['adverts_location'];
				$adverts_url = $data['adverts_url'];
				$adverts_shown = $data['adverts_shown'];
				$adverts_clicks = $data['adverts_clicks'];
				$adverts_sold = $data['adverts_sold'];
				$adverts_image = $data['adverts_image'];
				$adverts_html = $data['adverts_html'];
				$adverts_status = $data['adverts_status'];
				$adverts_expired = $data['adverts_expired'];
				if ($adverts_contract_start != 0) $adverts_contract_start = time_system2local($adverts_contract_start);
				if ($adverts_contract_end != 0) $adverts_contract_end = time_system2local($adverts_contract_end);
			}
			$result = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '$adverts_userid'");
			if ($data = dbarray($result)) {
				$title .= $locale['408']."<b>".$data['user_name']."</b>";
				$ad_client = $data['user_name'];
				$ad_client_id = $adverts_userid;
			}
		}
		break;
	case "delete":
		if (!isset($adverts_id) && !isNum($adverts_id)) fallback(BASEDIR."index.php");
		$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_id = '$adverts_id'");
		if (dbrows($result) == 0) {
			$errortitle = $locale['900'];
			$errormessage = $locale['901'];
		} else {
			$result = dbquery("DELETE FROM ".$db_prefix."adverts WHERE adverts_id = '$adverts_id'");
			$errormessage = $locale['902'];
		}
		$action = "list";
		break;
	case "upload":
		$adverts_id = isset($_POST['adverts_id'])?$_POST['adverts_id']:0;
		$adverts_userid = $_POST['adverts_userid'];
		$adverts_contract = $_POST['adverts_contract'];
		$adverts_contract_start = 0;
		if (isset($_POST['contract_start']['mday']) && isset($_POST['contract_start']['mon']) && isset($_POST['contract_start']['year'])) $adverts_contract_start = mktime(0,0,0,$_POST['contract_start']['mon'],$_POST['contract_start']['mday'],$_POST['contract_start']['year']);
		$adverts_contract_end = 0;
		if ($_POST['contract_end']['mday']!="--" && $_POST['contract_end']['mon']!="--" && $_POST['contract_end']['year']!="----") $adverts_contract_end = mktime(23,59,59,$_POST['contract_end']['mon'],$_POST['contract_end']['mday'],$_POST['contract_end']['year']);
		$adverts_priority = $_POST['adverts_priority'];
		$adverts_location = $_POST['adverts_location'];
		$adverts_url = $_POST['adverts_url'];
		$adverts_sold = $_POST['adverts_sold'];
		$adverts_image = isset($_POST['adverts_image'])?$_POST['adverts_image']:"";
		$adverts_html = "";
		$adverts_status = $_POST['adverts_status'];
		$adverts_expired = $_POST['adverts_expired'];
		$action = $action_save;
		$title = $action =="add"?$locale['400']:$locale['401'];
		$title .= $locale['408']."<b>".$ad_client."</b>";
		$ad_client_id = $id;
		break;
	case "list":
		break;
	default:
		fallback(BASEDIR."index.php");
}

// process the action 
switch ($action) {
	case "delclient":
		$variables['id'] = $id;
		$variables['question'] = sprintf($locale['909'], $ad_client);
		if (isset($errormessage)) {
			$variables['errormessage'] = $errormessage;
			$variables['errortitle'] = $errortitle;
		}
		$template_panels[] = array('type' => 'body', 'title' => $title, 'name' => 'admin.adverts.delclient', 'template' => 'admin.adverts.delclient.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/adverts.php");
		$template_variables['admin.adverts.delclient'] = $variables;
		break;
	case "add":
		$variables['users'] = array();
		$result = dbquery("SELECT user_id, user_name FROM ".$db_prefix."users ORDER BY user_name");
		while($data = dbarray($result)) { $variables['users'][$data['user_id']] = $data['user_name'];}
		if (isset($errormessage)) {
			$variables['errormessage'] = $errormessage;
			$variables['errortitle'] = $errortitle;
		}
		$template_panels[] = array('type' => 'body', 'title' => $title, 'name' => 'admin.adverts.addclient', 'template' => 'admin.adverts.addclient.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/adverts.php");
		$template_variables['admin.adverts.addclient'] = $variables;
		break;
	case "addad":
		// initialise variables
		$adverts_id = 0;
		$adverts_userid = $id;
		$adverts_contract = 0;
		$adverts_contract_start = time();
		$adverts_contract_end = 0;
		$adverts_priority = 3;
		$adverts_location = 0;
		$adverts_url = "http://";
		$adverts_shown = 0;
		$adverts_clicks  = 0;
		$adverts_sold  = 0;
		$adverts_image  = isset($imgname)?$imgname:"";
		$adverts_html = "";
		$adverts_status = 0;
		$adverts_expired = 0;
		$contract_start = 0;
		$contract_end = 0;
	case "edit":
		// get the list of advert images
		$adverts_images = array();
		$temp = opendir(PATH_IMAGES_ADS);
		while ($file = readdir($temp)) {
			if ($file{0} != "." && !in_array($file, array("/", "index.php")) && !is_dir(PATH_IMAGES_ADS.$file)) {
				if (!isset($ad_client) || substr($file,0,strlen($ad_client))==$ad_client ) $adverts_images[] = $file;
			}
		}
		closedir($temp);
		// convert dates
		$contract_start = getdate($adverts_contract_start);
		if ($adverts_contract_end > 0) $contract_end = getdate($adverts_contract_end);
		// paint the form
		$variables['action'] = $action;
		$variables['adverts_id'] = $adverts_id;
		$variables['adverts_userid'] = $adverts_userid;
		$variables['adverts_contract'] = $adverts_contract;
		$variables['adverts_contract_start'] = $adverts_contract_start;
		$variables['adverts_contract_end'] = $adverts_contract_end;
		$variables['adverts_priority'] = $adverts_priority;
		$variables['adverts_location'] = $adverts_location;
		$variables['adverts_url'] = $adverts_url;
		$variables['adverts_shown'] = $adverts_shown;
		$variables['adverts_clicks'] = $adverts_clicks;
		$variables['adverts_sold'] = $adverts_sold ;
		$variables['adverts_image'] = $adverts_image;
		$variables['adverts_html'] = $adverts_html;
		$variables['adverts_status'] = $adverts_status;
		$variables['adverts_expired'] = $adverts_expired;
		$variables['contract_start'] = $contract_start;
		$variables['contract_end'] = $contract_end;
		$variables['ad_client'] = $ad_client;
		$variables['ad_client_id'] = $ad_client_id;
		$variables['contract_types'] = $contract_types;
		$variables['locations'] = array();
		foreach($ad_locations as $loc_index => $location) {
			$variables['locations'][] = array('index' => $loc_index, 'location' => $location, 'dimension' => $ad_dimensions[$loc_index]);
		}
		$variables['ys'] = date('Y')-2;
		$variables['ye'] = date('Y')+11;
		$variables['ad_images'] = array();
		foreach($adverts_images as $idx => $ad_image) {
			$dimensions = @getimagesize(PATH_IMAGES_ADS.$ad_image);
			if (isset($ad_client)) 
				$img = substr($ad_image, strlen($ad_client)+1);
			else
				$img = $ad_image;
			$variables['ad_images'][$idx] = array('img' => $img, 'ad_image' => $ad_image, 'x' => $dimensions[0], 'y' => $dimensions[1]);
		}
		$variables['users'] = array();
		$result = dbquery("SELECT user_id, user_name FROM ".$db_prefix."users ORDER BY user_name");
		while($data = dbarray($result)) { $variables['users'][$data['user_id']] = $data['user_name'];}
		if (isset($errormessage)) {
			$variables['errormessage'] = $errormessage;
			$variables['errortitle'] = $errortitle;
		}
		$template_panels[] = array('type' => 'body', 'title' => $title, 'name' => 'admin.adverts.edit', 'template' => 'admin.adverts.edit.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/adverts.php");
		$template_variables['admin.adverts.edit'] = $variables;
		break;
	default:
		break;
}

if ($action == "images") {
	// images management panel
	$image_list = makefilelist(PATH_IMAGES_ADS, ".|..|index.php", true);
	$variables['image_list'] = array();
	foreach($image_list as $img) {
		$dimensions = @getimagesize(PATH_IMAGES_ADS.$img);
		$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_image = '".$img."'");
		$used = dbrows($result);
		$variables['image_list'][] = array('image' => $img, 'used' => $used, 'x' => $dimensions[0], 'y' => $dimensions[1]);
	}
	if (isset($view_image)) $variables['view_image'] = $view_image;
	if (isset($errormessage)) {
		$variables['errormessage'] = $errormessage;
		$variables['errortitle'] = $errortitle;
	}
	$template_panels[] = array('type' => 'body', 'name' => 'admin.adverts.images', 'template' => 'admin.adverts.images.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/adverts.php");
	$template_variables['admin.adverts.images'] = $variables;
}

// advertising overview panels
if ($action == "list") {

	// define the advertising menu body panel variables
	if (isset($errormessage)) {
		$variables['errormessage'] = $errormessage;
		$variables['errortitle'] = $errortitle;
	}
	$template_panels[] = array('type' => 'body', 'name' => 'admin.adverts', 'template' => 'admin.adverts.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/adverts.php");
	$template_variables['admin.adverts'] = $variables;

	// loop through all advertising clients
	$result = dbquery("SELECT DISTINCT user_id, user_name FROM ".$db_prefix."users WHERE user_sponsor = '1' ORDER BY user_name");
	$c = 0;
	while ($data = dbarray($result)) {
		$variables = array();
		$variables['data'] = $data;
		// retrieve all active ads
		$result2 = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_expired = '0' AND adverts_userid = '".$data['user_id']."' ORDER BY adverts_id DESC");
		$ads1 = array();
		if (dbrows($result2)) {
			while ($data2 = dbarray($result2)) {
				if ($data2['adverts_shown'] == 0)
					$percent = 0;
				else
					$percent = substr(100 * $data2['adverts_clicks'] / $data2['adverts_shown'], 0, 5);
				$data2['percentage'] = $percent;
				$data2['advert_type'] = $ad_locations[$data2['adverts_location']];
				$contract_type = $contract_types[$data2['adverts_contract']];
				switch ($data2['adverts_contract']) {
					case 0:
					case 1:
						if ($data2['adverts_contract_start'] > time()) {
							$contract_type .= " (".$locale['472']." ".showdate("%d-%m-%Y", $data2['adverts_contract_start']).")";
						} elseif ($data2['adverts_contract_end'] != 0) {
							$contract_type .= " (".$locale['471']." ".showdate("%d-%m-%Y", $data2['adverts_contract_end']).")";
						}
						break;
					case 2:
						$contract_type .= " (".($data2['adverts_sold']-$data2['adverts_shown'])." ".$locale['477'].")";
						break;
				}
				$data2['contract_type'] = $contract_type;
				$ads1[] = $data2;
			}
			$variables['ads1'] = $ads1;
		}
		// retrieve all expired ads
		$result2 = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_expired = '1' AND adverts_userid = '".$data['user_id']."' ORDER BY adverts_id DESC");
		$ads2 = array();
		if (dbrows($result2)) {
			while ($data2 = dbarray($result2)) {
				if ($data2['adverts_shown'] == 0)
					$percent = 0;
				else
					$percent = substr(100 * $data2['adverts_clicks'] / $data2['adverts_shown'], 0, 5);
				$data2['percentage'] = $percent;
				$data2['advert_type'] = trim(substr($ad_locations[$data2['adverts_location']], 0, strpos($ad_locations[$data2['adverts_location']], "(")));
				$contract_type = $contract_types[$data2['adverts_contract']];
				switch ($data2['adverts_contract']) {
					case 0:
					case 1:
						if ($data2['adverts_contract_start'] > time()) {
							$contract_type .= " (".$locale['472']." ".showdate("%d-%m-%Y", $data2['adverts_contract_start']).")";
						} elseif ($data2['adverts_contract_end'] != 0) {
							$contract_type .= " (".$locale['471']." ".showdate("%d-%m-%Y", $data2['adverts_contract_end']).")";
						}
						break;
					case 2:
						$contract_type .= " (".($data2['adverts_sold']-$data2['adverts_shown'])." ".$locale['477'].")";
						break;
				}
				$data2['contract_type'] = $contract_type;
				$ads2[] = $data2;
			}
			$variables['ads2'] = $ads2;
		}
		$c++;
		$template_panels[] = array('type' => 'body', 'name' => 'admin.adverts.overview'.$c, 'template' => 'admin.adverts.overview.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/adverts.php");
		$template_variables['admin.adverts.overview'.$c] = $variables;
	}
}

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>