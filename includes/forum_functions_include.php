<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
if (eregi("forum_functions_include.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// show code block with line wrapping (true) or scrollbar (false)
define('WRAP_CODE_IN_CODEBLOCK', false);

// these arrays need to be global
$current_message = array();
$codeblocks = array();
$urlblocks = array();
$imgblocks = array();
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
	if (WRAP_CODE_IN_CODEBLOCK) {
		$codeblocks[] = array("<div class='codeblock'>".$matches[2]."</div><div class='codeblock' style='border-top:0px; text-align:right;'><a href='".BASEDIR."getfile.php?type=fc&amp;forum_id=".$current_message['forum_id']."&amp;thread_id=".$current_message['thread_id']."&amp;post_id=".$current_message['post_id']."&amp;id=".$id."' title='".sprintf($locale['583'],($matches[1]==""?"":($matches[1]." ")))."'>".$locale['584']."</a></div>", $matches[1]);
	} else {
		$codeblocks[] = array("<div class='codeblock_source' id='codeblock".$blockcount."a'>".$matches[2]."</div><div class='codeblock_cmds' id='codeblock".$blockcount."b'><input type='button' class='button' name='download' value='".$locale['584']."' onclick='window.open(\"".BASEDIR."getfile.php?type=fc&amp;forum_id=".$current_message['forum_id']."&amp;thread_id=".$current_message['thread_id']."&amp;post_id=".$current_message['post_id']."&amp;id=".$id."\");return false;' title='".sprintf($locale['583'],($matches[1]==""?"":($matches[1]." ")))."' /></div>", $matches[1]);
	}
	return "{**@".($id)."@**}";
}

function _parseubb_urlblock($matches) {
	global $urlblocks;

	$urlblocks[] = array($matches[1]=="="?$matches[2]:substr($matches[1],1), parseubb(shortenlink($matches[2],50)));
	return "{@@*".(count($urlblocks)-1)."*@@}";
}

function _parseubb_imgblock($matches) {
	global $imgblocks;

	$imgblocks[] = array($matches[1], $matches[1]);
	return "{@*@".(count($imgblocks)-1)."@*@}";
}

function _parseubb_texturls($matches) {
	global $urlblocks;

	// validate the URL before converting it
	if (isURL($matches[0])) {
		$urlblocks[] = array($matches[0], parseubb(shortenlink($matches[0], 50)));
		return "{@@*".(count($urlblocks)-1)."*@@}";
	} else {
		return $matches[0];
	}
}

// message parser, strip [code], [img] and [url] sections, parse for BBcode, smiley's, then insert the sections again
function parsemessage($msg_array) {
	global $settings, $db_prefix, $codeblocks, $urlblocks, $imgblocks, $current_message;

	// validate the parameters
	if (!is_array($msg_array) || !isset($msg_array['post_message']) || !isset($msg_array['post_smileys'])) {
		return "";
	}
	$current_message = $msg_array;
	$rawmsg = $msg_array['post_message'];
	$smileys = $msg_array['post_smileys'];

	// make sure these are empty!
	$codeblocks = array();
	$urlblocks = array();
	$imgblocks = array();

	// strip CODE bbcode, optionally perform Geshi color coding
	$rawmsg = preg_replace_callback('#\[code(=.*?)?\](.*?)([\r\n]*)\[/code\]#si', '_parseubb_codeblock', $rawmsg);

	// strip URL bbcode
	$rawmsg = preg_replace_callback('#\[url(=.*?)\](.*?)([\r\n]*)\[/url\]#si', '_parseubb_urlblock', $rawmsg);

	// strip IMG bbcode
	$rawmsg = preg_replace_callback('#\[img\](.*?)([\r\n]*)\[/img\]#si', '_parseubb_imgblock', $rawmsg);

    // find other URL's in the text, strip them and add them to $urlblocks for conversion to [URL] bbcodes
	$rawmsg = preg_replace_callback('#(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)?((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|(localhost)|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.[a-zA-Z]{2,4})(\:[0-9]+)?(/[^/][a-zA-Z0-9\.\,\?\'\\/\+&amp;%\$\#\:\*\=~_\-@]*)*#si', '_parseubb_texturls', $rawmsg);

	// convert any newlines to html <br>
	$rawmsg = nl2br($rawmsg);

	// detect and convert wikitags to wiki bbcodes if needed
	if (isset($settings['wiki_forum_links'])  && $settings['wiki_forum_links']) {
		// build the search and replace arrays
		$search = array();
		$replace = array();
		$result = dbquery("SELECT DISTINCT tag FROM ".$db_prefix."wiki_pages");
		while ($data = dbarray($result)) {
			if (!empty($data['tag'])) {
				$search[] = "/(\b)(".$data['tag'].")(\b)/i";
				$replace[] = "\\1[wiki]\\2[/wiki]\\3";
			}
		}
		$rawmsg = preg_replace($search, $replace, $rawmsg);
	}

	// re-insert the saved img blocks
	foreach($imgblocks as $key => $imgblock) {
		$rawmsg = str_replace("{@*@".$key."@*@}", "[img]".$imgblock[0]."[/img]", $rawmsg);
	}

	// parse the smileys in the message
	if ($smileys) $rawmsg = parsesmileys($rawmsg);

	// parse all ubbcode
	$rawmsg = parseubb($rawmsg);
	
	// re-insert the saved code blocks
	foreach($codeblocks as $key => $codeblock) {
		$rawmsg = str_replace("{**@".$key."@**}", $codeblock[0], $rawmsg);
	}

	// re-insert the saved url blocks
	foreach($urlblocks as $key => $urlblock) {
		if (isURL($urlblock[0])) {
			// check if the URL is prefixed. If not, assume http://
			if (!eregi("^((https?|s?ftp|mailto|svn|cvs|callto|mms|skype)\:\/\/){1}", $urlblock[0])) {
				$urlblock[0] = "http://".$urlblock[0];
			}
			// convert it into a link
			$rawmsg = str_replace("{@@*".$key."*@@}", "<a href='".$urlblock[0]."' alt='' target='_blank'>".$urlblock[1]."</a>", $rawmsg);
		} else {
			// make the URL harmless
			$rawmsg = str_replace("{@@*".$key."*@@}", "[url=".stripinput($urlblock[0])."]".stripinput($urlblock[1])."[/url]", $rawmsg);
		}
	}

	return $rawmsg;
}
?>
