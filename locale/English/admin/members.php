<?php
// Members List
$locale['400'] = "Members List";
$locale['401'] = "User Name";
$locale['402'] = "User Type";
$locale['403'] = "Add a new user";
$locale['404'] = "Show All";
$locale['405'] = "Options";
$locale['406'] = "Country";
$locale['407'] = "Never";
$locale['408'] = "Unknown";
$locale['409'] = "Email address";
// View User Groups
$locale['410'] = "View User Group";
$locale['411'] = "%u member";
$locale['412'] = "%u members";
// Options
$locale['415'] = "Edit this user's profile";
$locale['416'] = "Activate this account";
$locale['417'] = "Ban this member";
$locale['418'] = "Delete this user";
$locale['419'] = "Activate the account";
// User Profile
$locale['420'] = "Member Profile";
$locale['422'] = "Statistics";
$locale['423'] = "User Groups";
// Ban/Unban/Delete Member
$locale['430'] = "Ban Imposed";
$locale['431'] = "Account Reactivated";
$locale['432'] = "Member Deleted";
$locale['433'] = "Are you sure you wish to delete this member?";
$locale['434'] = "Member Activated";
$locale['435'] = "Account activated at ";
$locale['436'] = "Hello [USER_NAME],\n
Your account at ".$settings['sitename']." has been activated.\n
You can now login using your chosen username and password.\n
Regards,
".$settings['siteusername'];
$locale['437'] = "When the user logs in, say";
$locale['438'] = "Ban expires in howmany days?";
$locale['439'] = "( 0 = ban does not expiry automatically )";
// Edit Profile
$locale['440'] = "Edit Profile";
$locale['441'] = "Profile successfully updated";
$locale['442'] = "Unable to update Profile:";
// Update Profile Errors
$locale['450'] = "Cannot edit primary administrator.";
$locale['451'] = "You must specify a user name & email address.";
$locale['452'] = "User name contains invalid characters.";
$locale['453'] = "The user name ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." is in use.";
$locale['454'] = "Invalid email address.";
$locale['455'] = "The email address ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." is in use.";
$locale['456'] = "New Passwords do not match.";
$locale['457'] = "Invalid password, use alpha numeric characters only.<br>
Password must be a minimum of 6 characters long.";
$locale['458'] = "<b>Warning:</b> unexpected script execution.";
// Edit Profile Form
$locale['460'] = "Update Profile";
// Ban Errors
$locale['463'] = "Ban reason my not be empty.";
$locale['464'] = "Days till ban expiry must be numeric and greater or equal zero.";
$locale['465'] = " ( Ban expires at %s )";
// Error message parts
$locale['470'] = " from ";
$locale['471'] = " beginning with ";
$locale['472'] = "There are no user names found";
// Add Member Errors
$locale['475'] = "Add Member";
$locale['476'] = "The member account has been created.";
$locale['477'] = "The member account could not be created.";
$locale['478'] = "Return to Members Admin";
$locale['479'] = "Return to Admin Index";
// Update Profile Errors
$locale['480'] = "You must specify a user name, full name & email address.";
$locale['481'] = "User name contains invalid characters.";
$locale['482'] = "The user name ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." is in use.";
$locale['483'] = "Invalid email address.";
$locale['484'] = "The email address ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." is in use.";
$locale['485'] = "New passwords do not match.";
$locale['486'] = "Invalid password, use alpha numeric characters only.<br>
Password must be a minimum of 6 characters long.";
$locale['487'] = "<b>Warning:</b> unexpected script execution.";
$locale['488'] = "No mailserver found for domain %s.";
$locale['489'] = "Mailserver for domain %s doesn't accept email messages.";
$locale['490'] = "Email address %s does not exist<br>(according to the mailserver %s).";
?>