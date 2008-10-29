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
if (eregi("ratings_include.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// load the locale for this include
locale_load("main.ratings");

// function to display the ratings panel
function showratings($rating_type,$rating_item_id,$rating_link) {

	global $db_prefix, $locale, $userdata,
		$template_panels, $template_variables;

	$variables = array();
	
	if (iMEMBER) {
		$d_rating = dbarray(dbquery("SELECT rating_vote,rating_datestamp FROM ".$db_prefix."ratings WHERE rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."' AND rating_user='".$userdata['user_id']."'"));
		$rating_exists = isset($d_rating['rating_vote']);
		if (isset($_POST['post_rating'])) {
			if (isNum($_POST['rating']) && $_POST['rating'] > 0 && $_POST['rating'] < 6 && !$rating_exists) {
				$result = dbquery("INSERT INTO ".$db_prefix."ratings (rating_item_id, rating_type, rating_user, rating_vote, rating_datestamp, rating_ip) VALUES ('$rating_item_id', '$rating_type', '".$userdata['user_id']."', '".$_POST['rating']."', '".time()."', '".USER_IP."')");
			}
			redirect($rating_link);
		} elseif (isset($_POST['remove_rating'])) {
			$result = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_item_id='$rating_item_id' AND rating_type='$rating_type' AND rating_user='".$userdata['user_id']."'");
			redirect($rating_link);
		}
	} else {
		$rating_exists = false;
	}
	$total_votes = dbcount("(rating_item_id)", "ratings", "rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."'");

	$ratingtext = array(5 => $locale['r120'], 4 => $locale['r121'], 3 => $locale['r122'], 2 => $locale['r123'], 1 => $locale['r124']);

	$ratings = array();
	foreach($ratingtext as $rating => $rating_info) {
		$temp = array();
		$temp['rating'] = $rating;
		$num_votes = dbcount("(rating_item_id)", "ratings", "rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."' AND rating_vote='".$rating."'");
		if ($num_votes == 0) {
			$temp['votecount'] = "[".$locale['r108']."]";
		} elseif ($num_votes == 1) {
			$temp['votecount'] = "[1 ".$locale['r109']."]";
		} else {
			$temp['votecount'] = "[".$num_votes." ".$locale['r110']."]";
		}
		$temp['num_votes'] = $num_votes;
		$temp['info'] = $rating_info;
		$temp['pct_rating'] = $total_votes == 0 ? 0 : number_format(100 / $total_votes * $num_votes);
		$ratings[] = $temp;
	}
	
	$variables['rating_link'] = $rating_link;
	$variables['rating_exists'] = $rating_exists;
	if ($rating_exists) {
		$variables['rating_text'] = $ratingtext[$d_rating['rating_vote']];
		$variables['rating_datestamp'] = $d_rating['rating_datestamp'];
	}
	$variables['rating_timestamp'] = 0;
	$variables['total_votes'] = $total_votes;
	$variables['ratings'] = $ratings;
	// define the body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'ratings_include', 'template' => 'include.ratings.tpl', 'locale' => "main.ratings");
	$template_variables['ratings_include'] = $variables;
}
?>
