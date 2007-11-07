<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// validate the parameters
if (!FUSION_QUERY || !isset($type)) fallback("index.php");

// load the locale for this module
locale_load("main.feeds");

// define how many items we want per RSS feed
define('ITEMS_PER_FEED', 50);

// check if authentication is valid. If not, reset it
if (isset($_SERVER['PHP_AUTH_USER'])) {
	$result = auth_validate_BasicAuthentication();
	if ($result != 0) unset($_SERVER['PHP_AUTH_USER']);
}

// define the channels and feeds arrays
$channels = array();
$feeds = array();
// process the feed type
switch (strtolower($type)) {
	case "forum":
		// required parameter id, must be numeric
		if (!isset($id) || !isNum($id)) fallback(FORUM."index.php");
		// check if the forum exists
		$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id = '$id'");
		if (!$result) 
			fallback("index.php");
		else
			$data = dbarray($result);
			if (!$data['forum_cat']) fallback(FORUM."index.php");
			if (!checkgroup($data['forum_access'])) {
				auth_BasicAuthentication();
			}
		// create the channel record for this RSS feed
		$channel = array();
		$channel['title'] = $locale['400']." ".$data['forum_name'];
		$channel['description'] = "<![CDATA[".$data['forum_description']."]]>";
		$channel['link'] = $settings['siteurl']."forum/viewforum.php?forum_id=".$data['forum_id'];
//		$channel['language'] = "";
//		$channel['pubDate'] = "";
//		$channel['lastBuildDate'] = "";
		$channel['generator'] = "ExiteCMS RSS Feed Generator v1.0";
		$channel['webMaster'] = $settings['siteemail'];
		$channels[] = $channel;
		$channel_count = count($channels);
		// create the feed resource
		$feed = array();
		$result = dbquery(
			"SELECT p.*, u.*, u2.user_name AS edit_name FROM ".$db_prefix."posts p
			LEFT JOIN ".$db_prefix."users u ON p.post_author = u.user_id
			LEFT JOIN ".$db_prefix."users u2 ON p.post_edituser = u2.user_id AND post_edituser > '0'
			WHERE p.forum_id='$id' ORDER BY post_datestamp DESC LIMIT ".ITEMS_PER_FEED
		);
		while ($data = dbarray($result)) {
			$item = array();
			$item['title'] = "<![CDATA[ ".$data['post_subject']." ]]>";
			$item['link'] = $settings['siteurl']."forum/viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;pid=".$data['post_id']."#post_".$data['post_id'];
			$item['description'] = "<![CDATA[ ".(strlen($data['post_message']) > 500 ? (substr($data['post_message'],0,496)." ...") : $data['post_message'])." ]]>";
			$item['pubDate'] = strftime("%a, %d %b %G %T %z", $data['post_datestamp']);
//			$item['guid'] = "";
			$feed[] = $item;
		}
		$feeds[] = $feed;
		$feed_count = count($feeds);
		break;
	default:
		fallback(FORUM."index.php");
}

// validate the feed selection, bail out if not correct
if (!isset($channels) || !is_array($channels) || !isset($channel_count) || $channel_count == 0) fallback(FORUM."index.php");
if (!isset($feeds) || !is_array($feeds) || !isset($feed_count) || $feed_count == 0) fallback(FORUM."index.php");

// start building the XML file
header("Content-type: text/xml; charset=".$settings['charset']);
echo "<?xml version=\"1.0\" encoding=\"".$settings['charset']."\"?>\n";
echo "<rss version=\"2.0\">\n";

// loop through the channels
foreach ($channels as $index => $channel) {
	// opening: channel information
	echo "\t<channel>\n";
	foreach ($channel as $tag => $value) {
		echo "\t\t<".$tag.">".$value."</".$tag.">\n";
	}
	// loop through the items
	foreach($feeds[$index] as $feed) {
		// opening: item information
		echo "\t\t<item>\n";
		// loop through the item tags
		foreach ($feed as $tag => $value) {
			echo "\t\t\t<".$tag.">".$value."</".$tag.">\n";
		}
		echo "\t\t</item>\n";
	}
	// close the channel
	echo "\t</channel>\n";
}
// close the rss feed
echo "</rss>\n";
?>