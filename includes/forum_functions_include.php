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
if (eregi("forum_functions_include.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// show code block with line wrapping (true) or scrollbar (false)
define('WRAP_CODE_IN_CODEBLOCK', false);

// these arrays need to be global
$current_message = array();
$codeblocks = array();
$exclblocks = array();
$blockcount = 0;
$raw_color_blocks = false;

// add a poll vote to the database
function fpm_vote() {
	global $db_prefix, $userdata, $fpm, $fpm_settings;

	if (iMEMBER && FPM_ACCESS && $fpm['poll_id'] != 0 && $fpm['vote_selection'] != 0) {
		$vote_count = dbcount("(poll_id)", "forum_poll_votes", "poll_id='".$fpm['poll_id']."' AND user_id='".$userdata['user_id']."'");
		if ($vote_count == 0) {
			$result = dbquery("INSERT INTO ".$db_prefix."forum_poll_votes (poll_id, user_id, vote_selection)
				VALUES ('".$fpm['poll_id']."', '".$userdata['user_id']."', '".$fpm['vote_selection']."')"
			);
		}
	}
}

function fpm_delete() {
	global $db_prefix, $fpm;

	if (FPM_ACCESS && isset($fpm['poll_id']) && isNum($fpm['poll_id'])) {
		$result = dbquery("DELETE FROM ".$db_prefix."forum_polls WHERE poll_id='".$fpm['poll_id']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."forum_poll_options WHERE poll_id='".$fpm['poll_id']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."forum_poll_votes WHERE poll_id='".$fpm['poll_id']."'");
	}
}

function fpm_save($post_id) {
	global $db_prefix, $thread_id, $fpm;

	if (FPM_ACCESS) {
		if (isset($fpm['reset_votes']) && $fpm['reset_votes'] != "" && isset($fpm['poll_id']) && isNum($fpm['poll_id'])) {
			$result = dbquery("DELETE FROM ".$db_prefix."forum_poll_votes WHERE poll_id='".$fpm['poll_id']."'");
		}
		if ($fpm['question'] == "" || $fpm['blank_options'] > ($fpm['option_show'] - 2)) {
			fpm_delete();
		} elseif ($fpm['question'] != "" && $fpm['blank_options'] < ($fpm['option_show'] - 1)) {
			if (isset($fpm['poll_id']) && isNum($fpm['poll_id'])) {
				$result = dbquery("UPDATE ".$db_prefix."forum_polls SET poll_question='".$fpm['question']."',
					poll_start='".$fpm['start']."', poll_end='".$fpm['end']."', poll_type='".$fpm['type']."' WHERE poll_id='".$fpm['poll_id']."'"
				);
				$result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_options
					WHERE poll_id='".$fpm['poll_id']."' ORDER BY option_order"
				);
				if (dbrows($result) != 0) {
					$i = 1;
					while($data = dbarray($result)) {
						if ($i <= $fpm['option_show'] && $fpm['option'][$i] != "") {
							$result2 = dbquery("UPDATE ".$db_prefix."forum_poll_options SET option_order='$i',
								option_text='".$fpm['option'][$i]."' WHERE option_id='".$data['option_id']."'"
							);
						} else {
							$result2 = dbquery("DELETE FROM ".$db_prefix."forum_poll_options WHERE option_id='".$data['option_id']."'");
						}
						$i ++;
					}
					while ($i <= $fpm['option_show'] && $fpm['option'][$i] != "") {
						$result = dbquery("INSERT INTO ".$db_prefix."forum_poll_options (option_id, poll_id,
							option_order, option_text) VALUES (NULL, '".$fpm['poll_id']."', '$i', '".$fpm['option'][$i]."')"
						);
						$i ++;
					}
				} else {
					$x = 1;
					for($i = 1; $i <= $fpm['option_show']; $i ++) {
						if ($fpm['option'][$i] != "") {
							$result = dbquery("INSERT INTO ".$db_prefix."forum_poll_options (option_id, poll_id,
								option_order, option_text) VALUES (NULL, '".$fpm['poll_id']."', '$x', '".$fpm['option'][$i]."')"
							);
							$x ++;
						}
					}
				}
			} else {
				$result = dbquery("INSERT INTO ".$db_prefix."forum_polls (poll_id, thread_id, post_id,
					poll_question, poll_start, poll_end, poll_type, poll_status) VALUES (NULL, '$thread_id',
					'$post_id', '".$fpm['question']."', '".$fpm['start']."', '".$fpm['end']."', '".$fpm['type']."', '1')"
				);
				$fpm['poll_id'] = mysql_insert_id(); $x = 1;
				for($i = 1; $i <= $fpm['option_show']; $i ++) {
					if ($fpm['option'][$i] != "") {
						$result = dbquery("INSERT INTO ".$db_prefix."forum_poll_options (option_id, poll_id,
							option_order, option_text) VALUES (NULL, '".$fpm['poll_id']."', '$x', '".$fpm['option'][$i]."')"
						);
						$x ++;
					}
				}
			}
		}
	}
}

function fpm_preview() {
	global $locale, $userdata, $fpm, $fpm_settings, $variables;

	if (FPM_ACCESS && (!isset($fpm['exists']) || $fpm['exists'] == 0) && $fpm['question'] != "" && $fpm['blank_options'] < ($fpm_settings['option_max'] - 1)) {
		$variables['poll'] = array();
		for($i = 1; $i <= $fpm_settings['option_max']; $i ++) {
			if(isset($fpm['option'][$i]) && $fpm['option'][$i] != "") {
				$poll = array('poll_id' => 0, 'option_id' => $i, 'option_order' => $i, 'option_text' => $fpm['option'][$i], 'option_votes' => 0, 'option_results' => 0, 'is_link' => 0);
				$variables['poll'][] = $poll;
			}
		}
		$variables['poll_options'] = array('poll_id' => 0, 'thread_id' => 0, 'post_id' => 0);
		$variables['poll_options']['poll_question'] = $fpm['question'];
		$variables['poll_options']['poll_start'] = $fpm['start'];
		$variables['poll_options']['poll_end'] = $fpm['end'];
		$variables['poll_options']['poll_status'] = 99;
		$variables['poll_options']['post_author'] = $userdata['user_id'];
		$variables['poll_options']['user_name'] = $fpm['user_name'];
		$variables['total_votes'] = 0;
		return true;
	} else {
		return false;
	}
}

// code the generate the poll panel
function fpm_view() {
	global $db_prefix, $locale, $userdata, $forum_id, $thread_id, $fpm_settings,
		$variables;

	if (FPM_ACCESS) {
		$result = dbquery("SELECT t1.*, t2.post_author, t3.user_name FROM ".$db_prefix."forum_polls t1
			LEFT JOIN ".$db_prefix."posts t2 ON t1.post_id=t2.post_id
			LEFT JOIN ".$db_prefix."users t3 ON t2.post_author=t3.user_id WHERE t1.thread_id='$thread_id'"
		);
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$variables['poll_options'] = $data;
			$result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_options WHERE poll_id='".$data['poll_id']."' ORDER BY option_order");
			$option_total = dbrows($result);
			if ($option_total > 1) {
				$total_votes = dbcount("(poll_id)", "forum_poll_votes", "poll_id='".$data['poll_id']."'");
				$variables['total_votes'] = $total_votes;
				if (iMEMBER) {
					$vote_count = dbcount("(poll_id)", "forum_poll_votes", "poll_id='".$data['poll_id']."' AND user_id='".$userdata['user_id']."'");
					$voted = $vote_count == 0 ? 0 : 1;
				} else {
					$voted = $fpm_settings['guest_permissions'] == 1 ? 0 : 1;
				}
				if ($data['poll_end'] != 0 && $data['poll_end'] <= date("U")) {
					$voted = 1;
				}
				$variables['voted'] = $voted;
				$variables['poll'] = array();
				while($data2 = dbarray($result)) {
					$option_votes = dbcount("(poll_id)", "forum_poll_votes", "poll_id='".$data['poll_id']."' AND vote_selection='".$data2['option_id']."'");
					if ($total_votes == 0) {
						$vote_results = 0;
					} else {
						$vote_results = round($option_votes / $total_votes * 100);
					}
					$data2['option_votes'] = $option_votes;
					$data2['vote_results'] = $vote_results;
					$data2['is_link'] = isURL($data2['option_text']);
					// get some extra information based on the poll type
					switch($data['poll_type']) {
						case 0:
							break;
						case 1:
							$result2 = dbquery("SELECT u.user_id, u.user_name FROM ".$db_prefix."users u, ".$db_prefix."forum_poll_votes p WHERE p.poll_id = '".$data['poll_id']."' AND p.vote_selection = '".$data2['option_id']."' and p.user_id = u.user_id");
							$user_votes = array();
							while ($user_vote = dbarray($result2)) {
								$user_votes[] = $user_vote;
							}
							$data2['user_votes'] = $user_votes;
							break;
					}
					$variables['poll'][] = $data2;
				}
				$variables['poll_ended'] = false;
				if($data['poll_end'] != 0 && $data['poll_end'] <= date("U")) {
					$variables['poll_ended'] = true;
				}
			}
			return true;
		}
	}
	return false;
}

// check if a poll exists for this forum and thread id
function fpm_poll_exists() {
	global $locale, $data;

die('fpm_foll_exists() - needs to be fixed');

	if (FPM_ACCESS) {
		$result = dbcount("(poll_id)", "forum_polls", "thread_id='".$data['thread_id']."'");
		$text = $result != 0 ? " <b>".$locale['FPM_200']."</b>" : "";
		return $text;
	}
}

// check if a poll exists for this forum and thread id
function fpm_panels_poll_exists($forum_id, $thread_id) {
	global $db_prefix, $locale;

	if (isNum($forum_id) && isNum($thread_id)) {
		$fpm_result = dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id='$forum_id'");
		if (dbrows($fpm_result) != 0) { $fpm_settings = dbarray($fpm_result); $fpm_settings['forum_exists'] = 1; }
		else { $fpm_settings = dbarray(dbquery("SELECT * FROM ".$db_prefix."forum_poll_settings WHERE forum_id='0'")); }
		if (!defined('FPM_ACCESS')) {
			if (!iMEMBER) {
				if ($fpm_settings['guest_permissions'] != 0) { define("FPM_ACCESS", true); }
			} elseif ($fpm_settings['enable_polls'] == 1 && $fpm_settings['vote_permissions'] != "") {
				$temp_array = explode(".", $fpm_settings['vote_permissions']);
				for($i = 0; $i < count($temp_array); $i ++) {
					if (isNum($temp_array[$i])) {
						if ($userdata['user_id'] == $temp_array[$i]) { define("FPM_ACCESS", true); break; }
					} else {
						if (checkgroup(substr($temp_array[$i], 1))) { define("FPM_ACCESS", true); break; }
					}
				}
			} else { define("FPM_ACCESS", false); }
			if (!defined('FPM_ACCESS')) { define("FPM_ACCESS", false); }
		}
		if (FPM_ACCESS) {
			$result = dbcount("(poll_id)", "forum_polls", "thread_id='$thread_id'");
			if ($result) return true;
		}
	}
	return false;
}

// function to display the action result dialog, and optionally redirect back to the post
function resultdialog($title, $message="", $redirect=true, $backlink=false, $timeout=4000) {

	global $locale, $forum_id, $thread_id, $post_id,
		$template_panels, $template_variables;

	// temp storage for template variables
	$variables = array();

	$variables['message'] = $message;
	$variables['redirect'] = $redirect;
	$variables['backlink'] = $backlink;
	$variables['timeout'] = $timeout;
	$variables['forum_id'] = (isset($forum_id) && isNum($forum_id)) ? $forum_id : false;
	$variables['thread_id'] = (isset($thread_id) && isNum($thread_id)) ? $thread_id : false;
	$variables['post_id'] = (isset($post_id) && isNum($post_id)) ? $post_id : false;

	// define the search body panel variables
	$template_panels[] = array('type' => 'body', 'name' => 'forum.resultdialog', 'title' => $title, 'template' => 'forum.resultdialog.tpl',
									'locale' => array("forum.main", "forum.post", "admin.forum_polls"));
	$template_variables['forum.resultdialog'] = $variables;
}

// like stripinput, but convert the & to &amp; as well (otherwise we lose html entities in code blocks)
function stripmessageinput($text) {

	// Split off the [url] blocks to exclude them from url parsing
	$message = "";
	$urlblocks = array();

	// find the code [url] occurence
	$i = strpos($text, "[url");

	// loop through the message until all are found and processed
	while ($i !== false) {
		// strip the bit before the [url] BBcode, and add a placeholder
		$message .= substr($text, 0, $i+4)."{@@**@@}";
		// strip the processed bit
		$text = substr($text, $i+4);
		// find the end of the [url] block
		$j = strpos($text, "[/url]");
		// if not found, add the remaining bit, a forced [/url], and stop processing
		if ($j === false) {
			$message = str_replace("{@@**@@}", $text, $message);
			break;
		}
		// store this url block
		$urlblocks[] = substr($text, 0, $j);
		// strip the processed bit
		$text = substr($text, $j);
		// check if there are more code segments
		$i = strpos($text, "[url");
	}

	// any text left?
	if (strlen($text)) $message .= $text;

	// now strip and convert to html entities
	$message = stripinput(str_replace("&", "&amp;", $message));

	// re-insert the saved url blocks
	foreach($urlblocks as $urlblock) {
		// find the first placeholder
		$i = strpos($message, "{@@**@@}");
		// shorten and normalize the link if required
		if ($urlblock{0} == "=") {
			$message = substr($message, 0, $i).$urlblock.substr($message, $i+8);
		} else {
			$message = substr($message, 0, $i)."=".substr($urlblock,1)."]".shortenlink(substr($urlblock,1),70).substr($message, $i+8);
		}
	}

	return $message;
}

function _unhtmlentities($string) {

	$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
	$string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
	// replace literal entities
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}

// Parse smiley bbcode into HTML images
function parsesmileys($message) {
	$smiley = array(
		"\:oops\:" => "<img src='".IMAGES."smiley/more/redface.gif' alt='smiley' />",
		"\:doubt\:" => "<img src='".IMAGES."smiley/more/doubt.gif' alt='smiley' />",
		"\:thumbleft" => "<img src='".IMAGES."smiley/more/icon_thumleft.gif' alt='smiley' />",
		"\:thumbright" => "<img src='".IMAGES."smiley/more/icon_thumright.gif' alt='smiley' />",
		"\:smt004" => "<img src='".IMAGES."smiley/more/004.gif' alt='smiley' />",
		"\:smt005" => "<img src='".IMAGES."smiley/more/005.gif' alt='smiley' />",
		"\:smt006" => "<img src='".IMAGES."smiley/more/006.gif' alt='smiley' />",
		"\:smt007" => "<img src='".IMAGES."smiley/more/007.gif' alt='smiley' />",
		"\:smt008" => "<img src='".IMAGES."smiley/more/008.gif' alt='smiley' />",
		"\:smt009" => "<img src='".IMAGES."smiley/more/009.gif' alt='smiley' />",
		"\:smt010" => "<img src='".IMAGES."smiley/more/010.gif' alt='smiley' />",
		"\:smt011" => "<img src='".IMAGES."smiley/more/011.gif' alt='smiley' />",
		"\:smt012" => "<img src='".IMAGES."smiley/more/012.gif' alt='smiley' />",
		"\:smt013" => "<img src='".IMAGES."smiley/more/013.gif' alt='smiley' />",
		"\:smt014" => "<img src='".IMAGES."smiley/more/014.gif' alt='smiley' />",
		"\:smt016" => "<img src='".IMAGES."smiley/more/016.gif' alt='smiley' />",
		"\:smt017" => "<img src='".IMAGES."smiley/more/017.gif' alt='smiley' />",
		"\:smt018" => "<img src='".IMAGES."smiley/more/018.gif' alt='smiley' />",
		"\:smt019" => "<img src='".IMAGES."smiley/more/019.gif' alt='smiley' />",
		"\:smt020" => "<img src='".IMAGES."smiley/more/020.gif' alt='smiley' />",
		"\:smt021" => "<img src='".IMAGES."smiley/more/021.gif' alt='smiley' />",
		"\:smt022" => "<img src='".IMAGES."smiley/more/022.gif' alt='smiley' />",
		"\:smt023" => "<img src='".IMAGES."smiley/more/023.gif' alt='smiley' />",
		"\:smt024" => "<img src='".IMAGES."smiley/more/024.gif' alt='smiley' />",
		"\:smt025" => "<img src='".IMAGES."smiley/more/025.gif' alt='smiley' />",
		"\:smt026" => "<img src='".IMAGES."smiley/more/026.gif' alt='smiley' />",
		"\:smt027" => "<img src='".IMAGES."smiley/more/027.gif' alt='smiley' />",
		"\:smt028" => "<img src='".IMAGES."smiley/more/028.gif' alt='smiley' />",
		"\:smt029" => "<img src='".IMAGES."smiley/more/029.gif' alt='smiley' />",
		"\:smt030" => "<img src='".IMAGES."smiley/more/030.gif' alt='smiley' />",
		"\:smt031" => "<img src='".IMAGES."smiley/more/031.gif' alt='smiley' />",
		"\:smt032" => "<img src='".IMAGES."smiley/more/032.gif' alt='smiley' />",
		"\:smt033" => "<img src='".IMAGES."smiley/more/033.gif' alt='smiley' />",
		"\:smt034" => "<img src='".IMAGES."smiley/more/034.gif' alt='smiley' />",
		"\:smt035" => "<img src='".IMAGES."smiley/more/035.gif' alt='smiley' />",
		"\:smt036" => "<img src='".IMAGES."smiley/more/036.gif' alt='smiley' />",
		"\:smt037" => "<img src='".IMAGES."smiley/more/037.gif' alt='smiley' />",
		"\:smt038" => "<img src='".IMAGES."smiley/more/038.gif' alt='smiley' />",
		"\:smt039" => "<img src='".IMAGES."smiley/more/039.gif' alt='smiley' />",
		"\:smt040" => "<img src='".IMAGES."smiley/more/040.gif' alt='smiley' />",
		"\:smt041" => "<img src='".IMAGES."smiley/more/041.gif' alt='smiley' />",
		"\:smt042" => "<img src='".IMAGES."smiley/more/042.gif' alt='smiley' />",
		"\:smt043" => "<img src='".IMAGES."smiley/more/043.gif' alt='smiley' />",
		"\:smt044" => "<img src='".IMAGES."smiley/more/044.gif' alt='smiley' />",
		"\:smt045" => "<img src='".IMAGES."smiley/more/045.gif' alt='smiley' />",
		"\:smt046" => "<img src='".IMAGES."smiley/more/046.gif' alt='smiley' />",
		"\:smt047" => "<img src='".IMAGES."smiley/more/047.gif' alt='smiley' />",
		"\:smt048" => "<img src='".IMAGES."smiley/more/048.gif' alt='smiley' />",
		"\:smt049" => "<img src='".IMAGES."smiley/more/049.gif' alt='smiley' />",
		"\:smt050" => "<img src='".IMAGES."smiley/more/050.gif' alt='smiley' />",
		"\:smt051" => "<img src='".IMAGES."smiley/more/051.gif' alt='smiley' />",
		"\:smt052" => "<img src='".IMAGES."smiley/more/052.gif' alt='smiley' />",
		"\:smt053" => "<img src='".IMAGES."smiley/more/053.gif' alt='smiley' />",
		"\:smt054" => "<img src='".IMAGES."smiley/more/054.gif' alt='smiley' />",
		"\:smt055" => "<img src='".IMAGES."smiley/more/055.gif' alt='smiley' />",
		"\:smt056" => "<img src='".IMAGES."smiley/more/056.gif' alt='smiley' />",
		"\:smt057" => "<img src='".IMAGES."smiley/more/057.gif' alt='smiley' />",
		"\:smt058" => "<img src='".IMAGES."smiley/more/058.gif' alt='smiley' />",
		"\:smt059" => "<img src='".IMAGES."smiley/more/059.gif' alt='smiley' />",
		"\:smt060" => "<img src='".IMAGES."smiley/more/060.gif' alt='smiley' />",
		"\:smt061" => "<img src='".IMAGES."smiley/more/061.gif' alt='smiley' />",
		"\:smt062" => "<img src='".IMAGES."smiley/more/062.gif' alt='smiley' />",
		"\:smt063" => "<img src='".IMAGES."smiley/more/063.gif' alt='smiley' />",
		"\:smt064" => "<img src='".IMAGES."smiley/more/064.gif' alt='smiley' />",
		"\:smt065" => "<img src='".IMAGES."smiley/more/065.gif' alt='smiley' />",
		"\:smt066" => "<img src='".IMAGES."smiley/more/066.gif' alt='smiley' />",
		"\:smt067" => "<img src='".IMAGES."smiley/more/067.gif' alt='smiley' />",
		"\:smt068" => "<img src='".IMAGES."smiley/more/068.gif' alt='smiley' />",
		"\:smt069" => "<img src='".IMAGES."smiley/more/069.gif' alt='smiley' />",
		"\:smt070" => "<img src='".IMAGES."smiley/more/070.gif' alt='smiley' />",
		"\:smt073" => "<img src='".IMAGES."smiley/more/073.gif' alt='smiley' />",
		"\:smt074" => "<img src='".IMAGES."smiley/more/074.gif' alt='smiley' />",
		"\:smt075" => "<img src='".IMAGES."smiley/more/075.gif' alt='smiley' />",
		"\:smt076" => "<img src='".IMAGES."smiley/more/076.gif' alt='smiley' />",
		"\:smt077" => "<img src='".IMAGES."smiley/more/077.gif' alt='smiley' />",
		"\:smt078" => "<img src='".IMAGES."smiley/more/078.gif' alt='smiley' />",
		"\:smt079" => "<img src='".IMAGES."smiley/more/079.gif' alt='smiley' />",
		"\:smt080" => "<img src='".IMAGES."smiley/more/080.gif' alt='smiley' />",
		"\:smt081" => "<img src='".IMAGES."smiley/more/081.gif' alt='smiley' />",
		"\:smt082" => "<img src='".IMAGES."smiley/more/082.gif' alt='smiley' />",
		"\:smt083" => "<img src='".IMAGES."smiley/more/083.gif' alt='smiley' />",
		"\:smt084" => "<img src='".IMAGES."smiley/more/084.gif' alt='smiley' />",
		"\:smt085" => "<img src='".IMAGES."smiley/more/085.gif' alt='smiley' />",
		"\:smt086" => "<img src='".IMAGES."smiley/more/086.gif' alt='smiley' />",
		"\:smt087" => "<img src='".IMAGES."smiley/more/087.gif' alt='smiley' />",
		"\:smt088" => "<img src='".IMAGES."smiley/more/088.gif' alt='smiley' />",
		"\:smt089" => "<img src='".IMAGES."smiley/more/089.gif' alt='smiley' />",
		"\:smt090" => "<img src='".IMAGES."smiley/more/090.gif' alt='smiley' />",
		"\:smt091" => "<img src='".IMAGES."smiley/more/091.gif' alt='smiley' />",
		"\:smt092" => "<img src='".IMAGES."smiley/more/092.gif' alt='smiley' />",
		"\:smt093" => "<img src='".IMAGES."smiley/more/093.gif' alt='smiley' />",
		"\:smt084" => "<img src='".IMAGES."smiley/more/094.gif' alt='smiley' />",
		"\:smt095" => "<img src='".IMAGES."smiley/more/095.gif' alt='smiley' />",
		"\:smt096" => "<img src='".IMAGES."smiley/more/096.gif' alt='smiley' />",
		"\:smt097" => "<img src='".IMAGES."smiley/more/097.gif' alt='smiley' />",
		"\:smt098" => "<img src='".IMAGES."smiley/more/098.gif' alt='smiley' />",
		"\:smt099" => "<img src='".IMAGES."smiley/more/099.gif' alt='smiley' />",
		"\:smt101" => "<img src='".IMAGES."smiley/more/101.gif' alt='smiley' />",
		"\:smt103" => "<img src='".IMAGES."smiley/more/103.gif' alt='smiley' />",
		"\:smt104" => "<img src='".IMAGES."smiley/more/104.gif' alt='smiley' />",
		"\:smt105" => "<img src='".IMAGES."smiley/more/105.gif' alt='smiley' />",
		"\:smt106" => "<img src='".IMAGES."smiley/more/106.gif' alt='smiley' />",
		"\:smt107" => "<img src='".IMAGES."smiley/more/107.gif' alt='smiley' />",
		"\:smt108" => "<img src='".IMAGES."smiley/more/108.gif' alt='smiley' />",
		"\:smt109" => "<img src='".IMAGES."smiley/more/109.gif' alt='smiley' />",
		"\:smt110" => "<img src='".IMAGES."smiley/more/110.gif' alt='smiley' />",
		"\:smt111" => "<img src='".IMAGES."smiley/more/111.gif' alt='smiley' />",
		"\:smt112" => "<img src='".IMAGES."smiley/more/112.gif' alt='smiley' />",
		"\:smt113" => "<img src='".IMAGES."smiley/more/113.gif' alt='smiley' />",
		"\:smt114" => "<img src='".IMAGES."smiley/more/114.gif' alt='smiley' />",
		"\:smt115" => "<img src='".IMAGES."smiley/more/115.gif' alt='smiley' />",
		"\:smt116" => "<img src='".IMAGES."smiley/more/116.gif' alt='smiley' />",
		"\:smt117" => "<img src='".IMAGES."smiley/more/117.gif' alt='smiley' />",
		"\:smt118" => "<img src='".IMAGES."smiley/more/118.gif' alt='smiley' />",
		"\:smt119" => "<img src='".IMAGES."smiley/more/119.gif' alt='smiley' />",
		"\:smt120" => "<img src='".IMAGES."smiley/more/120.gif' alt='smiley' />",
		"\:boring" => "<img src='".IMAGES."smiley/more/015.gif' alt='smiley' />",
		"\:smt071" => "<img src='".IMAGES."smiley/more/071.gif' alt='smiley' />",
		"\:smt102" => "<img src='".IMAGES."smiley/more/102.gif' alt='smiley' />",
		"\:smt100" => "<img src='".IMAGES."smiley/more/100.gif' alt='smiley' />",
		"\:shock\:" => "<img src='".IMAGES."smiley/more/shock.gif' alt='smiley' />",
		"\:lol\:" => "<img src='".IMAGES."smiley/more/lol.gif' alt='smiley' />",
		"\:razz\:" => "<img src='".IMAGES."smiley/more/razz.gif' alt='smiley' />",
		"\:cry\:" => "<img src='".IMAGES."smiley/more/cry.gif' alt='smiley' />",
		"\:evil\:" => "<img src='".IMAGES."smiley/more/evil.gif' alt='smiley' />",
		"\:twisted\:" => "<img src='".IMAGES."smiley/more/icon_twisted.gif' alt='smiley' />",
		"\:roll\:" => "<img src='".IMAGES."smiley/more/rolleyes.gif' alt='smiley' />",
		"\:wink\:" => "<img src='".IMAGES."smiley/more/wink.gif' alt='smiley' />",
		"\:idea\:" => "<img src='".IMAGES."smiley/more/idea.gif' alt='smiley' />",
		"\:arrow\:" => "<img src='".IMAGES."smiley/more/arrow.gif' alt='smiley' />",
		"\:mrgreen\:" => "<img src='".IMAGES."smiley/more/icon_mrgreen.gif' alt='smiley' />",
		"\:badgrin\:" => "<img src='".IMAGES."smiley/more/badgrin.gif' alt='smiley' />",
		"\;\)" => "<img src='".IMAGES."smiley/wink.gif' alt='smiley' />",
		"\:\(" => "<img src='".IMAGES."smiley/sad.gif' alt='smiley' />",
		"\:\|" => "<img src='".IMAGES."smiley/frown.gif' alt='smiley' />",
		"\:o" => "<img src='".IMAGES."smiley/shock.gif' alt='smiley' />",
		"\:p" => "<img src='".IMAGES."smiley/pfft.gif' alt='smiley' />",
		"b\)" => "<img src='".IMAGES."smiley/cool.gif' alt='smiley' />",
		"\:d" => "<img src='".IMAGES."smiley/grin.gif' alt='smiley' />",
		"\:@" => "<img src='".IMAGES."smiley/angry.gif' alt='smiley' />",
		"=D&gt;" => "<img src='".IMAGES."smiley/more/eusa_clap.gif' alt='smiley' />",
		"\\\:D/" => "<img src='".IMAGES."smiley/more/eusa_dance.gif' alt='smiley' />",
		"\:D" => "<img src='".IMAGES."smiley/more/biggrin.gif' alt='smiley' />",
		"\:\-D" => "<img src='".IMAGES."smiley/more/003.gif' alt='smiley' />",
		"\:\-\)" => "<img src='".IMAGES."smiley/more/001.gif' alt='smiley' />",
		"\:\(" => "<img src='".IMAGES."smiley/more/sad.gif' alt='smiley' />",
		"\:o" => "<img src='".IMAGES."smiley/more/surprised.gif' alt='smiley' />",
		"8\)" => "<img src='".IMAGES."smiley/more/cool.gif' alt='smiley' />",
		"\:x" => "<img src='".IMAGES."smiley/more/mad.gif' alt='smiley' />",
		"\:\-x" => "<img src='".IMAGES."smiley/more/icon_mad.gif' alt='smiley' />",
		"\:P" => "<img src='".IMAGES."smiley/more/icon_razz.gif' alt='smiley' />",
		"\;\-\)" => "<img src='".IMAGES."smiley/more/002.gif' alt='smiley' />",
		"\:\!\:" => "<img src='".IMAGES."smiley/more/exclaim.gif' alt='smiley' />",
		"\:\?\:" => "<img src='".IMAGES."smiley/more/question.gif' alt='smiley' />",
		"\:\?" => "<img src='".IMAGES."smiley/more/confused.gif' alt='smiley' />",
		"\:\|" => "<img src='".IMAGES."smiley/more/neutral.gif' alt='smiley' />",
		"\#\-o" => "<img src='".IMAGES."smiley/more/eusa_doh.gif' alt='smiley' />",
		"\=P\~" => "<img src='".IMAGES."smiley/more/eusa_drool.gif' alt='smiley' />",
		"\:\^o" => "<img src='".IMAGES."smiley/more/eusa_liar.gif' alt='smiley' />",
		"\[\-X" => "<img src='".IMAGES."smiley/more/eusa_naughty.gif' alt='smiley' />",
		"\[\-o\<\;" => "<img src='".IMAGES."smiley/more/eusa_pray.gif' alt='smiley' />",
		"8\-\[" => "<img src='".IMAGES."smiley/more/eusa_shifty.gif' alt='smiley' />",
		"\[\-\(" => "<img src='".IMAGES."smiley/more/eusa_snooty.gif' alt='smiley' />",
		"\:\-k" => "<img src='".IMAGES."smiley/more/eusa_think.gif' alt='smiley' />",
		"\]\(\*\,\)" => "<img src='".IMAGES."smiley/more/eusa_wall.gif' alt='smiley' />",
		"\:\-\"" => "<img src='".IMAGES."smiley/more/eusa_whistle.gif' alt='smiley' />",
		"O\:\)" => "<img src='".IMAGES."smiley/more/eusa_angel.gif' alt='smiley' />",
		"\=\;" => "<img src='".IMAGES."smiley/more/eusa_hand.gif' alt='smiley' />",
		"\:\-\&" => "<img src='".IMAGES."smiley/more/eusa_sick.gif' alt='smiley' />",
		"\:\-\(\{\|\=" => "<img src='".IMAGES."smiley/more/eusa_boohoo.gif' alt='smiley' />",
		"\:\-\$" => "<img src='".IMAGES."smiley/more/eusa_shhh.gif' alt='smiley' />",
		"\:\-s" => "<img src='".IMAGES."smiley/more/eusa_eh.gif' alt='smiley' />",
		"\:\-\#" => "<img src='".IMAGES."smiley/more/eusa_silenced.gif' alt='smiley' />",
		"\:\)" => "<img src='".IMAGES."smiley/smile.gif' alt='smiley' />"
	);
	foreach($smiley as $key=>$smiley_img) {
		$search = "#(^|[[:space:]])".$key."([[:space:]]|$)?#si";
		$replace = "\\1".$smiley_img."\\2";
		$message = preg_replace($search, $replace, $message);
	}
	return $message;
}

// message parser, strip [code], [img], [mail] and [url] sections, parse for BBcode, smiley's, then insert the sections again
// $postinfo -> array() -> should contain forum_id, thread_id, post_id , must contain post_message if msgbody is empty
// $msgboby -> message to parse if postinfo is not used
// $smileys -> true if the message needs to be parsed for smileys
// $limit -> limit UBB parsing when true (p.e. skip code, quote and wiki tags)
function parsemessage($postinfo, $msgbody = "", $smileys = true, $limit = false) {
	global $settings, $db_prefix, $codeblocks, $exclblocks, $current_message;

	// make sure we have something to parse
	if (!is_array($postinfo) || (empty($msgbody) && empty($postinfo['post_message'] ))) {
		return "";
	}
	$current_message = $postinfo;
	$rawmsg = empty($msgbody) ? $current_message['post_message'] : $msgbody;
	if (isset($current_message['post_smileys'])) $smileys = $current_message['post_smileys'];

	// make sure these are empty!
	$codeblocks = array();
	$exclblocks = array();

	// if not resticted, strip CODE bbcode, optionally perform Geshi color coding
	if (!$limit) $rawmsg = preg_replace_callback('#\[code(=.*?)?\](.*?)([\r\n]*)\[/code\]#si', '_parseubb_codeblock', $rawmsg);

	// strip IMG bbcode
	$rawmsg = preg_replace_callback('#\[img\](.*?)([\r\n]*)\[/img\]#si', '_parseubb_exclblock', $rawmsg);

	// strip MAIL bbcode
	$rawmsg = preg_replace_callback('#\[mail(=?.*?)\](.*?)([\r\n]*)\[/mail\]#si', '_parseubb_exclblock', $rawmsg);

	// strip FLASH bbcode
	$rawmsg = preg_replace_callback('#\[flash(.*?)\](.*?)([\r\n]*)\[/flash\]#si', '_parseubb_exclblock', $rawmsg);

	// strip URL bbcode
	$rawmsg = preg_replace_callback('#\[url(=?.*?)\](.*?)([\r\n]*)\[/url\]#si', '_parseubb_exclblock', $rawmsg);

    // find other URL's in the text, strip them and add them to $urlblocks for conversion to [URL] bbcodes
	$rawmsg = preg_replace_callback('#(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)?((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|(localhost)|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.[a-zA-Z]{2,4})(\:[0-9]+)?(/[a-zA-Z0-9\.\,\?\'\\/\+&amp;%\$\#\:\*\=~_\-@]*)*#si', '_parseubb_texturls', $rawmsg);

	// detect and convert wikitags to wiki bbcodes if needed
	if (!$limit && isset($settings['wiki_forum_links'])  && $settings['wiki_forum_links']) {
		// build the search and replace arrays
		$search = array();
		$replace = array();
		if (dbtable_exists($db_prefix."wiki_pages")) {
			$result = dbquery("SELECT DISTINCT tag FROM ".$db_prefix."wiki_pages");
			while ($data = dbarray($result)) {
				if (!empty($data['tag'])) {
					$search[] = "/(^|\s)(".$data['tag'].")($|\s|\.|,|:|;|\?|\!)/i";
					$replace[] = "\\1[wiki]\\2[/wiki]\\3";
				}
			}
		}
		if (dbtable_exists($db_prefix."wiki_aliases")) {
			$result = dbquery("SELECT DISTINCT from_tag FROM ".$db_prefix."wiki_aliases");
			while ($data = dbarray($result)) {
				if (!empty($data['from_tag'])) {
					$search[] = "/(^|\s)(".$data['from_tag'].")($|\s|\.|,|:|;|\?|\!)/i";
					$replace[] = "\\1[wiki]\\2[/wiki]\\3";
				}
			}
		}
		$rawmsg = preg_replace($search, $replace, $rawmsg);
	} else {
		// wiki links not enabled or filtered, remove any wiki BBcode's found
		$rawmsg = preg_replace('#\[wiki\](.*?)\[/wiki\]#si', '\1', $rawmsg);
	}

	// convert any newlines to html <br>
	$rawmsg = nl2br($rawmsg);

	// re-insert the excluded flash blocks
	foreach($exclblocks as $key => $exclblock) {
		switch ($exclblock[2]) {
			case "flash":
				if (isURL($exclblock[1]) || file_exists(PATH_ROOT.$exclblock[1])) {
					$rawmsg = str_replace("{@@*".$key."*@@}", "[flash ".$exclblock[0]."]".$exclblock[1]."[/flash]", $rawmsg);
				}
			default:
				break;
		}
	}

	// parse all ubbcode
	$rawmsg = parseubb($rawmsg, $limit);

	// parse the smileys in the message
	if ($smileys) $rawmsg = parsesmileys($rawmsg);

	// re-insert the saved code blocks
	foreach($codeblocks as $key => $codeblock) {
		$rawmsg = str_replace("{**@".$key."@**}", $codeblock[0], $rawmsg);
	}

	// re-insert the excluded blocks, url's first
	foreach($exclblocks as $key => $exclblock) {
		switch ($exclblock[2]) {
			case "url":
				$exclblock[0] = url_to_absolute($_SERVER['SCRIPT_URI'], $exclblock[0]);
				if (isURL($exclblock[0])) {
					// convert it into a link
					$rawmsg = str_replace("{@@*".$key."*@@}", "<a href='".$exclblock[0]."' alt='' target='_blank'>".parseubb($exclblock[1])."</a>", $rawmsg);
				} else {
					// strip the URL
					$rawmsg = str_replace("{@@*".$key."*@@}", $exclblock[1], $rawmsg);
				}
				break;
			default:
				break;
		}
	}
	foreach($exclblocks as $key => $exclblock) {
		switch ($exclblock[2]) {
			case "flash":
				$rawmsg = str_replace("{@@*".$key."*@@}", "[flash ".$exclblock[0]."]".$exclblock[1]."[/flash]", $rawmsg);
			case "img":
				if ((isURL($exclblock[0]) && verify_image($exclblock[0])) || file_exists(PATH_ROOT.$exclblock[0])) {
					$rawmsg = str_replace("{@@*".$key."*@@}", "<img src='".$exclblock[0]."' />", $rawmsg);
				} else {
					$rawmsg = str_replace("{@@*".$key."*@@}", "[img]".$exclblock[0]."[/img]", $rawmsg);
				}
				break;
			case "mail":
				$rawmsg = str_replace("{@@*".$key."*@@}", "<a href='mailto:".($exclblock[0]==""?$exclblock[1]:$exclblock[0])."'>".parseubb($exclblock[1], true)."</a>", $rawmsg);
				break;
			default:
				break;
		}
	}

	return $rawmsg;
}

// Parse bbcode into HTML code
function parseubb($text, $limit = false) {
	global $settings, $locale;

	// horizontal line
	$text = preg_replace('#\[hr\]#si', '<hr />', $text);

	// old style lists
	$text = preg_replace('#\[li\](.*?)\[/li\]#si', '<li style=\'margin-left:35px;\'>\1</li>', $text);
	$text = preg_replace('#\[ul\](.*?)\[/ul\]#si', '<ul style=\'margin-left:-15px;\'>\1</ul>', $text);

	// new style lists
	$text = preg_replace('#\[list=1\](.*?)\[/list\]#si', '<ol>\1</ol>', $text);
	$text = preg_replace('#\[list\](.*?)\[/list\]#si', '<ul>\1</ul>', $text);
	$text = preg_replace('#\r\n\[\*\]#si', '<li>', $text);

	// get rid of inserted breaks that ruin the layout
	$text = preg_replace('#<br />\r\n<ol><br />#si', '<ol>', $text);
	$text = preg_replace('#<br />\r\n<ul><br />#si', '<ul>', $text);

	// bbcode tables
	$text = preg_replace('#\[table\]#si', '<div><table align="left" valign="top" class="tbl-border">', $text);
	$text = preg_replace('#\[\/table\]#si', '</table></div><div style="clear:both;"></div>', $text);
	$text = preg_replace('#\[td\]#si', '<td class="tbl1">', $text);
	$text = preg_replace('#\[\/td\]#si', '</td>', $text);
	$text = preg_replace('#\[tr\]#si', '<tr>', $text);
	$text = preg_replace('#\[\/tr\]#si', '</tr>', $text);

	//get rid of line breaks after a list item, for better formatting
	$text=str_replace('</li><br />','</li>',$text);
	$text=str_replace('</ul><br />','</ul>',$text);

	// text formatting
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $text);
	$text = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $text);
	$text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
	$text = preg_replace('#\[strike\](.*?)\[/strike\]#si', '<span style=\'text-decoration: line-through;\'>\1</span>', $text);
	$text = preg_replace('#\[sup\](.*?)\[/sup\]#si', '<sup>\1</sup>', $text);
	$text = preg_replace('#\[sub\](.*?)\[/sub\]#si', '<sub>\1</sub>', $text);
	if (!$limit) {
		$text = preg_replace('#\[blockquote\](.*?)\[/blockquote\]#si', '<blockquote style=\'border:1px dotted;padding:2px;\'>\1</blockquote>', $text);
	}
	$text = preg_replace('#\[left\](.*?)\[/left\]#si', '<div align=\'left\'>\1</div>', $text);
	$text = preg_replace('#\[center\](.*?)\[/center\]#si', '<div align=\'center\'>\1</div>', $text);
	$text = preg_replace('#\[justify\](.*?)\[/justify\]#si', '<div align=\'justify\'>\1</div>', $text);
	$text = preg_replace('#\[right\](.*?)\[/right\]#si', '<div align=\'right\'>\1</div>', $text);

	$text = preg_replace('#\[font=(.*?)\](.*?)\[/font\]#si', '<span style=\'font-family:\1\'>\2</span>', $text);
	$text = preg_replace('#\[size=([0-3]?[0-9])\](.*?)\[/size\]#si', '<span style=\'font-size:\1px\'>\2</span>', $text);
	$text = preg_replace('#\[small\](.*?)\[/small\]#si', '<span class=\'small\'>\1</span>', $text);

	$text = preg_replace('#\[color=(\#[0-9a-fA-F]{6}|black|blue|brown|cyan|grey|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/color\]#si', '<span style=\'color:\1\'>\2</span>', $text);
	$text = preg_replace('#\[highlight=(\#[0-9a-fA-F]{6}|transparent|black|blue|brown|cyan|grey|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/highlight\]#si', '<span style=\'background-color:\1\'>\2</span>', $text);

	// fix some anomilies of the hoteditor
	$text = preg_replace('#\[color=\#NaNNaNNaN\](.*?)\[/size\]#si', '\1', $text);
	$text = preg_replace('#\[size=undefined\](.*?)\[/size\]#si', '\1', $text);

	// wiki links
	if (isset($settings['wiki_forum_links'])  && $settings['wiki_forum_links']) {
		$text = preg_replace('#\[wiki\](.*?)\[/wiki\]#si', '<a href="'.BASEDIR.'modules/wiki/index.php?wakka=\1" class="wiki_link" title="'.$settings['wiki_wakka_name'].'">\1</a>', $text);
	}

	// youtube bbcode
	$text = preg_replace('#\[youtube\](.*?)\[/youtube\]#si', '<object type="application/x-shockwave-flash" width="425" height="350" data="http://www.youtube.com/v/\1"><param name="movie" value="http://www.youtube.com/v/\1"></param><param name="wmode" value="transparent"></param></object>', $text);

	// flash movies
	$text = preg_replace('#\[flash width=([0-9]*?) height=([0-9]*?)\](.*?)(\.swf)\[/flash\]#si', '<object classid=\'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\' codebase=\'http://active.macromedia.com/flash6/cabs/swflash.cab#version=6,0,0,0\' id=\'\3\4\' width=\'\1\' height=\'\2\'><param name=movie value=\'\3\4\'><param name=\'quality\' value=\'high\'><param name=\'bgcolor\' value=\'#ffffff\'><embed src=\'\3\4\' quality=\'high\' bgcolor=\'#ffffff\' width=\'\1\' height=\'\2\' type=\'application/x-shockwave-flash\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\'></embed></object>', $text);

	// quote blocks
	if (!$limit) {
		$text = preg_replace('#\[quote=([\r\n]*)(.*?)\]#si', '<b>\2 '.$locale['199'].':</b><br />[quote]', $text);
		$qcount = substr_count(strtolower($text), "[quote]");
		for ($i=0;$i < $qcount;$i++) $text = preg_replace('#\[quote\](.*?)\[/quote\]#si', '<div class=\'quote\'>\1</div>', $text);
	}
	$text = descript($text,false);

	return $text;
}

function _parseubb_codeblock($matches) {
	global $codeblocks, $blockcount, $current_message, $raw_color_blocks, $locale;

	// empty code block?
	if (trim($matches[2]) == "") {
		// remove the code block entirely
		return "";
	}

	// remove leading CRLF
	if (substr($matches[2],0,2) == "\r\n") {
		$matches[2] = substr($matches[2],2);
	}

	// remove the leading '=' from the file type
	$matches[1] = trim(substr($matches[1],1));

	// colorize the code if requested
	if ($raw_color_blocks == false) {
		require_once PATH_GESHI."/geshi.php";
		$geshi =& new GeSHi("", "");
		$geshi->set_language($matches[1]);
		$geshi->set_header_type(GESHI_HEADER_DIV);
		$geshi->set_tab_width(4);
		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS,1);
		$geshi->set_source(_unhtmlentities($matches[2]));
		$geshi->set_code_style('font-weight:bold;', true);
		$geshi->enable_classes();
		$matches[2] = $geshi->parse_code();
		// did we already add the css code for this type
		$add_css = true;
		foreach($codeblocks as $codeblock) {
			if ($codeblock[1] == $matches[1]) {
				$add_css = true;
				break;
			}
		}
		if ($add_css = true) {
			$matches[2] = '<style type="text/css"><!--'.$geshi->get_stylesheet().'--></style>'.$matches[2];
		}
	}

	// if a raw block was requested, bail out here
	if ($raw_color_blocks) {
		$codeblocks[] = array($matches[2], $matches[1]);
		return true;
	}

	// generate the linenumbers
	$ln = "";
	$cnt = substr_count($matches[2], "\n")+1;
	for ($i=1;$i<=$cnt;$i++) {
		$ln .= $i."<br />";
	}
	$id = count($codeblocks);
	++$blockcount;
	// is this a forum post?
	if (isset($current_message['forum_id']) && isset($current_message['thread_id']) && isset($current_message['post_id'])) {
		if (WRAP_CODE_IN_CODEBLOCK) {
			$codeblocks[] = array("<div class='codeblock_source' id='codeblock".$blockcount."a' style='white-space:normal;'>".$matches[2]."</div><div class='codeblock_cmds' id='codeblock".$blockcount."b' style='border-top:0px; text-align:right;'><a href='".BASEDIR."getfile.php?type=fc&amp;forum_id=".$current_message['forum_id']."&amp;thread_id=".$current_message['thread_id']."&amp;post_id=".$current_message['post_id']."&amp;id=".$id."' title='".sprintf($locale['583'],($matches[1]==""?"":($matches[1]." ")))."'>".$locale['584']."</a></div>", $matches[1]);
		} else {
			$codeblocks[] = array("<div class='codeblock_source' id='codeblock".$blockcount."a'>".$matches[2]."</div><div class='codeblock_cmds' id='codeblock".$blockcount."b'><input type='button' class='button' name='download' value='".$locale['584']."' onclick='window.open(\"".BASEDIR."getfile.php?type=fc&amp;forum_id=".$current_message['forum_id']."&amp;thread_id=".$current_message['thread_id']."&amp;post_id=".$current_message['post_id']."&amp;id=".$id."\");return false;' title='".sprintf($locale['583'],($matches[1]==""?"":($matches[1]." ")))."' /></div>", $matches[1]);
		}
	} else {
		// is this a private message?
		if (isset($current_message['pm_id'])) {
			$codeblocks[] = array("<div class='codeblock_source' id='codeblock".$blockcount."a'>".$matches[2]."</div><div class='codeblock_cmds' id='codeblock".$blockcount."b'><input type='button' class='button' name='download' value='".$locale['643']."' onclick='window.open(\"".BASEDIR."getfile.php?type=pc&amp;pm_id=".$current_message['pm_id']."&amp;id=".$id."\");return false;' title='".sprintf($locale['642'],($matches[1]==""?"":($matches[1]." ")))."' /></div>", $matches[1]);
		} else {
			// no downloadable code, don't add a download button
			$codeblocks[] = array("<div class='codeblock_source' id='codeblock".$blockcount."a'>".$matches[2]."</div><div class='codeblock_cmds' id='codeblock".$blockcount."b'></div>", $matches[1]);
		}
	}
	return "{**@".($id)."@**}";
}

function _parseubb_exclblock($matches) {
	global $exclblocks;

	// determine the BBcode
	$type = substr($matches[0],1, strpos($matches[0], "]")-1);
	if (strpos($type, "=")) $type = substr($type,0,strpos($type, "="));
	if (strpos($type, " ")) $type = substr($type,0,strpos($type, " "));

	switch($type) {
		case "img":
			$exclblocks[] = array($matches[1],$matches[1],"img");
			break;
		case "flash":
			$exclblocks[] = array(trim($matches[1]),$matches[2],"flash");
			break;
		case "mail":
			$exclblocks[] = array($matches[1]=="="?$matches[2]:substr($matches[1],1), $matches[2], "mail");
			break;
		case "url":
		default:
			$exclblocks[] = array($matches[1]=="="?$matches[2]:substr($matches[1],1), $matches[2], "url");
			break;
	}
	return "{@@*".(count($exclblocks)-1)."*@@}";
}

function _parseubb_texturls($matches) {
	global $exclblocks;

	// validate the URL before converting it
	if (isURL($matches[0])) {
		$exclblocks[] = array($matches[0], $matches[0], "url");
		return "{@@*".(count($exclblocks)-1)."*@@}";
	} else {
		return $matches[0];
	}
}
?>
