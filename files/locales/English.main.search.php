<?php
// Search form
$locale['400'] = "Search ".$settings['sitename'];
$locale['401'] = "Search for:";
$locale['402'] = "Articles";
$locale['403'] = "News";
$locale['404'] = "Forum Posts";
$locale['405'] = "Downloads";
$locale['406'] = "Web Links";
$locale['407'] = "Members";
$locale['408'] = "Search";
$locale['409'] = "Search Results";
// Category matches
$locale['410'] = "Article";
$locale['411'] = "Articles";
$locale['412'] = "News item";
$locale['413'] = "News items";
$locale['414'] = "Forum Post";
$locale['415'] = "Forum Posts";
$locale['416'] = "Download";
$locale['417'] = "Downloads";
$locale['418'] = "Web Link";
$locale['419'] = "Web Links";
$locale['420'] = "Member";
$locale['421'] = "Members";
$locale['422'] = " items found in";
$locale['423'] = " matching your search criteria";
// Standard search results
$locale['430'] = "Match";
$locale['431'] = "Matches";
$locale['432'] = " found in ";
$locale['433'] = "article subject";
$locale['434'] = "article text";
$locale['435'] = "news subject";
$locale['436'] = "news text";
$locale['437'] = "extended news text";
$locale['438'] = "post subject";
$locale['439'] = "post message";
$locale['440'] = "article snippet";
// Download & Web link search results
$locale['450'] = "[NEW]";
$locale['451'] = "License:";
$locale['452'] = "O/S:";
$locale['453'] = "Version:";
$locale['454'] = "Date Added:";
$locale['455'] = "Downloads:";
$locale['456'] = "Visits:";
// No results
$locale['470'] = "No matches found.";
$locale['471'] = "Search text must be at least 3 characters long.";
$locale['472'] = "There is no content available for you to search.";
// help text
$locale['480'] = array();
$locale['480'][] = array('0' => '', '1' => "This search engine supports complex and boolean searches. You can use:");
$locale['480'][] = array('0' => '+', '1' => "A leading plus sign indicates that this word must be present in every row returned.");
$locale['480'][] = array('0' => '-', '1' => "A leading minus sign indicates that this word must not be present in any row returned.");
$locale['480'][] = array('0' => '&lt; &gt;', '1' => "These two operators are used to change a word's contribution to the relevance value that is assigned to a row. The &lt; operator decreases the contribution and the &gt; operator increases it.");
$locale['480'][] = array('0' => '( )', '1' => "Parentheses are put round sub-expressions to give them higher precedence in the search.");
$locale['480'][] = array('0' => '~', '1' => "A leading tilde acts as a negation operator, causing the word's contribution to the row relevance to be negative. It's useful for marking noise words. A row that contains such a word will be rated lower than others, but will not be excluded altogether, as it would be with the minus operator.");
$locale['480'][] = array('0' => '*', '1' => "An asterisk is the truncation operator. Unlike the other operators, it is appended to the word, or fragment, not prepended.");
$locale['480'][] = array('0' => '" "', '1' => "Double quotes at the beginning and end of a phrase, matches only rows that contain the complete phrase, as it was typed.");
?>