<?php
// panel titles
$locale['400'] = "Add an advertisement";
$locale['401'] = "Edit an advertisement";
$locale['402'] = "Advertisements";
$locale['403'] = "Expired advertisements";
$locale['404'] = "Advertising client";
$locale['405'] = "Upload an advertisement image";
$locale['406'] = "Advertisement image management";
$locale['407'] = "Advertisement image preview";
$locale['408'] = " for client ";
$locale['409'] = "Please visit our sponsor";

// add - edit advertisement
$locale['410'] = "Client name";
$locale['411'] = "Contract based on";
$locale['412'] = "Contract start date";
$locale['413'] = "Contract end date";
$locale['414'] = "Adverts currently purchased";
$locale['415'] = "Modify purchased amount";
$locale['416'] = "Advert location";
$locale['417'] = "Advert image";
$locale['418'] = "Advert click URL";
$locale['419'] = "Enable this advert";
$locale['420'] = "Increase by";
$locale['421'] = "Decrease by";
$locale['422'] = "No";
$locale['423'] = "Yes";
$locale['424'] = "Advert priority";
$locale['425'] = "Move to a new client";

// contract information
$locale['430'] = "Open ended period";
$locale['431'] = "Fixed time period";
$locale['432'] = "Number of displays";
$contract_types = array(0 => $locale['430'], 1 => $locale['431'], 2 => $locale['432']);

// buttons
$locale['440'] = "Save";
$locale['441'] = "Back";
$locale['442'] = "Expire";
$locale['443'] = "Activate";
$locale['444'] = "Change URL";
$locale['445'] = "Email statistics";
$locale['446'] = "Email All statistics";
$locale['447'] = "Add a new client";
$locale['448'] = "Advert image management";
$locale['449'] = "Upload image";

// advertisement location and type. 
// Don't forget to add new ones to the array! And don't change the order!!!
$locale['450'] = "Logo - left side panel";			// location = 0
$locale['451'] = "Banner - Discussion Forum";		// location = 1
$locale['452'] = "Banner - Forum index only";		// location = 2
$locale['453'] = "Banner - Thread index only";		// location = 3
$ad_locations = array(0 => $locale['450'], 1 => $locale['451'], 2 => $locale['452'], 3 => $locale['453']);
asort($ad_locations);	// sort the locations alphabetically

// maximum dimensions for each advertisement location
// Don't forget to add new ones to the array! And keep in sync with the locations!!!
$ad_dimensions = array(0 => "160x65", 1 => "468x60", 2 => "468x60", 3 => "468x60");

// current - finished advertisement
$locale['460'] = "ID";
$locale['461'] = "Client name";
$locale['462'] = "Advertisment type";
$locale['463'] = "Contract information";
$locale['464'] = "Clicks";
$locale['465'] = "Clicks %";
$locale['466'] = "Options";
$locale['467'] = "Enable";
$locale['468'] = "Disable";
$locale['469'] = "Edit";
$locale['470'] = "Delete";
$locale['471'] = "ends";
$locale['472'] = "starts";
$locale['473'] = "ended";
$locale['474'] = "Advertisements";
$locale['475'] = "Contact email";
$locale['476'] = "Remove this client";
$locale['477'] = "left";
$locale['478'] = "";
$locale['479'] = "Displayed";

// advertising statistics
$locale['500'] = "Advertising Statistics";
$locale['501'] = "Prio";
$locale['502'] = "Guest";

// client information - email messages
$locale['510'] = "Following are the complete stats for all your advertising investments at ".$settings['sitename'].":";
$locale['511'] = "Following are the complete stats for your advertising investment with ID %s at ".$settings['sitename'].":";
$locale['512'] = "Statistics report generated on %s\r\n\r\n";
$locale['513'] = "Adverts still available";

// advertisement - image upload
$locale['530'] = "Image filename";

// advertisement - image management
$locale['540'] = "View";
$locale['541'] = "Delete";
$locale['542'] = "Dimensions";
$locale['543'] = "Options";
$locale['544'] = "Used";

// messages
$locale['900'] = "The following errors are detected while validating your input:";
$locale['901'] = "The requested advertisement can not be found in the database.";
$locale['902'] = "The advertisement has been deleted.";
$locale['903'] = "The amount purchased must be numeric.";
$locale['904'] = "The total amount sold to this client can not be negative.";
$locale['905'] = "Are you sure you want to delete this?";
$locale['906'] = "The advertisement is succesfully added.";
$locale['907'] = "The advertisement is succesfully updated.";
$locale['908'] = "This client doesn't have any active advertisements.";
$locale['909'] = "You are about to remove '%s' as an advertising client.<br />This also removes all advertisements, and any images that belong to his client!<br /><br />Are you sure?";
$locale['910'] = "This client and all the clients advertisements have been removed.";
$locale['911'] = "This client doesn't have any expired advertisements.";
$locale['912'] = "The selected image is to big for the selected location.<br />The maximum size for this location is %s, the image selected is %s.";
$locale['913'] = "Advertisement with ID %s has been enabled.";
$locale['914'] = "Advertisement with ID %s has been disabled.";

// messages - moving an advert to a new client
$locale['920'] = "Advertisement succesfully moved from %s to %s.";
$locale['921'] = "The selected Advertisement can not be found.";
$locale['922'] = "Invalid Advertisement ID passed. Is this a hacking attempt?";
$locale['923'] = "The selected new Client record can not be found.";
$locale['924'] = "Invalid client ID passed. Is this a hacking attempt?";

// messages - advertising statistics
$locale['950'] = "%s, You do not appear to be an advertising client.<br /><br />Please <a href='/contact.php'>contact us</a> for more information on becoming a client.";
$locale['951'] = "The URL for the advertisement with ID %s has been updated.";
$locale['952'] = "Detailed statistics for the advertisement with ID %s have been emailed to you.";
$locale['953'] = "Detailed statistics for all your advertisements have been emailed to you.";
$locale['954'] = "%s, There are no advertisements defined for you in this category.";
$locale['955'] = "Please <a href='/contact.php'>contact us</a> if you feel this is not correct.";

// messages - image uploading
$locale['960'] = "Upload file does not have an approved file extension (.jpg, .gif or .png)!";
$locale['961'] = "Upload file is not a valid image!";
$locale['962'] = "Hacking attempt! This is not an uploaded file!";

// messages - image management
$locale['970'] = "There are no uploaded advertisement images";
$locale['971'] = "The advertisement image has been deleted.";
?>