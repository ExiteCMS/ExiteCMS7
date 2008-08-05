{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: forum.index.tpl                                      *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-11 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the forum module 'index'                                   *}
{*                                                                         *}
{***************************************************************************}
<script type='text/javascript'>
function jumpForum(forumid) {ldelim}
	document.location.href='{$smarty.const.FORUM}viewforum.php?forum_id='+forumid;
{rdelim}
</script>
{include file="_opentable.tpl" name=$_name title=$locale.500 state=$_state style=$_style}
<table id='width_check' cellspacing='0' cellpadding='0' width='100%'>
	<tr>
		<td class='smallalt'>
			<a href='{$smarty.const.BASEDIR}'>{$settings.sitename}</a> » 
			<a href='index.php'>{$locale.400}</a> » 
			<a href='index.php#cat_{$forum.forum_cat}'>{$forum.forum_cat_name}</a> » 
			<a href='viewforum.php?forum_id={$forum_id}'>{$forum.forum_name}</a>
			<br /><hr />
		</td>
	</tr>
</table>
{if $smarty.const.iMEMBER && $user_can_post && $thread.thread_author != 0 && ($smarty.const.iMOD || $smarty.const.iSUPERADMIN)}
<table cellpadding='0' cellspacing='0' width='100%'>
	<tr>
		<td align='left' class='tbl'>
		{if $smarty.const.iMEMBER}
			{if $settings.thread_notify}
				{if $has_thread_notify}
					{buttonlink name=$locale.515 link="post.php?action=track_off&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$thread_id}
				{else}
					{buttonlink name=$locale.516 link="post.php?action=track_on&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$thread_id}
				{/if}
			{/if}
			{if $user_can_post }
				{if !$thread.thread_locked}
					{buttonlink name=$locale.565 link="post.php?action=reply&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$thread_id}
				{/if}
				{buttonlink name=$locale.566 link="post.php?action=newthread&amp;forum_id="|cat:$forum_id}
			{/if}
		{/if}
		</td>
		<td align='right' class='tbl'>
			<form name='modopts1' method='post' action='options.php?forum_id={$forum_id}&amp;thread_id={$thread_id}'>
				{$locale.520}
				<select name='step' class='textbox'>
					<option value='none'>&nbsp;</option>
					<option value='renew'>{$locale.527}</option>
					<option value='delete'>{$locale.521}</option>
					{if !$thread.thread_locked}
					<option value='lock'>{$locale.522}</option>
					{else}
					<option value='unlock'>{$locale.523}</option>
					{/if}
					{if !$thread.thread_sticky}
					<option value='sticky'>{$locale.524}</option>
					{else}
					<option value='nonsticky'>{$locale.525}</option>
					{/if}
					<option value='move'>{$locale.526}</option>
					<option value='merge'>{$locale.529}</option>
				</select>
				<input type='submit' name='go' value='{$locale.528}' class='button' />
			</form>
		</td>
	</tr>
</table>
{/if}
{if $rows > $settings.numofthreads}
<div align='center' style='margin-top:5px;margin-bottom:5px;'>
	{makepagenav start=$rowstart count=$settings.numofthreads total=$rows range=$settings.navbar_range link=$pagenav_url}
</div>
{/if}
{if $thread_has_poll}
	{include file="forum.poll.tpl"}
{/if}
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
{section name=pid loop=$posts}
	{include file="forum.renderpost.tpl"}
{/section}
</table>
{if $rows > $settings.numofthreads}
<div align='center' style='margin-top:5px;margin-bottom:5px;'>
	{makepagenav start=$rowstart count=$settings.numofthreads total=$rows range=$settings.navbar_range link=$pagenav_url}
</div>
{/if}
<table cellspacing='0' cellpadding='0' width='100%'>
	<tr>
		<td class='tbl1' colspan='2'height='5'>
		</td>
	</tr>
	<tr>
		<td align='left' class='tbl'>
		{if $smarty.const.iMEMBER}
			{if $settings.thread_notify}
				{if $has_thread_notify}
					{buttonlink name=$locale.515 link="post.php?action=track_off&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$thread_id}
				{else}
					{buttonlink name=$locale.516 link="post.php?action=track_on&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$thread_id}
				{/if}
			{/if}
			{if $user_can_post }
				{if !$thread.thread_locked}
					{buttonlink name=$locale.565 link="post.php?action=reply&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$thread_id}
				{/if}
				{buttonlink name=$locale.566 link="post.php?action=newthread&amp;forum_id="|cat:$forum_id}
			{/if}
		{/if}
		</td>
		<td align='right' class='tbl'>
			{buttonlink name=$locale.581 link=$smarty.const.FUSION_REQUEST|cat:"#page_top"|escape:"entities"}
		</td>
	</tr>
	<tr>
		<td class='smallalt' colspan='2'>
			<a href='{$smarty.const.BASEDIR}'>{$settings.sitename}</a> » 
			<a href='index.php'>{$locale.400}</a> » 
			<a href='index.php#cat_{$forum.forum_cat}'>{$forum.forum_cat_name}</a> » 
			<a href='viewforum.php?forum_id={$forum_id}'>{$forum.forum_name}</a>
		</td>
	</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
	<tr>
		<td width='38%' align='left' class='tbl'>
			{$locale.540}
			<br />
			<select name='jump_id' class='textbox' onchange="jumpForum(this.options[this.selectedIndex].value);">
			{section name=id loop=$forums}
				{if $forums[id].forum_new_cat}
					{if !$smarty.section.id.first}</optgroup>{/if}
					<optgroup label='{$forums[id].forum_cat_name}'>
					{assign var='hasvalues' value=false}
				{/if}
					<option value='{$forums[id].forum_id}'>{$forums[id].forum_name}</option>
					{assign var='hasvalues' value=true}
				{if $smarty.section.id.last && $hasvalues}</optgroup>{/if}
			{/section}
			</select>
		</td>
		<td width='24%' align='center'>
		{if $smarty.const.iMEMBER && $user_can_post}
			{if $unread_posts}
				{buttonlink name=$locale.573 link="viewforum.php?action=markallread&amp;forum_id="|cat:$forum_id}&nbsp;
			{/if}
		{/if}
		</td>
		<td width='38%' align='right' class='tbl'>
		{if $smarty.const.iMEMBER && $user_can_post && $thread.thread_author != 0 && ($smarty.const.iMOD || $smarty.const.iSUPERADMIN)}
			<form name='modopts2' method='post' action='options.php?forum_id={$forum_id}&amp;thread_id={$thread_id}'>
				{$locale.520}
				<br />
				<select name='step' class='textbox'>
					<option value='none'>&nbsp;</option>
					<option value='renew'>{$locale.527}</option>
					<option value='delete'>{$locale.521}</option>
					{if !$thread.thread_locked}
					<option value='lock'>{$locale.522}</option>
					{else}
					<option value='unlock'>{$locale.523}</option>
					{/if}
					{if !$thread.thread_sticky}
					<option value='sticky'>{$locale.524}</option>
					{else}
					<option value='nonsticky'>{$locale.525}</option>
					{/if}
					<option value='move'>{$locale.526}</option>
					<option value='merge'>{$locale.529}</option>
				</select>
				<input type='submit' name='go' value='{$locale.528}' class='button' />
			</form>
		{/if}
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
<script type='text/javascript'>
{literal}
// Dean Edwards/Matthias Miller/John Resig
function init() {
	// quit if this function has already been called
	if (arguments.callee.done) return;

	// flag this function so we don't do the same thing twice
	arguments.callee.done = true;

	// kill the timer
	if (_timer) clearInterval(_timer);

	// calculate the width of a code block
	var blockwidth = document.getElementById("width_check").offsetWidth-141;
	var block_a = 0;
	var block_b = 0;

	// loop through all blocks found, and set the width correctly
	var i = 1;
	while (document.getElementById("codeblock"+i+"a") != null && document.getElementById("codeblock"+i+"b") != null) {
		// need to check and do something dynamic with margins and padding here...
		block_a = blockwidth - 40 + "px";
		block_b = blockwidth - 50 + "px";
		document.getElementById("codeblock"+i+"a").style.width = block_a;
		document.getElementById("codeblock"+i+"b").style.width = block_b;
		i++;
	}
};

/* for Mozilla/Opera9 */
if (document.addEventListener) {
	document.addEventListener("DOMContentLoaded", init, false);
}

/* for Internet Explorer */
/*@cc_on @*/
/*@if (@_win32)
	document.write("<script id=__ie_onload defer src=javascript:void(0)><\/script>");
	var script = document.getElementById("__ie_onload");
	script.onreadystatechange = function() {
		if (this.readyState == "complete") {
			init(); // call the onload handler
		}
	};
/*@end @*/

/* for Safari and Konqueror */
if (/KHTML|WebKit/i.test(navigator.userAgent)) { // sniff
	var _timer = setInterval(function() {
		if (/loaded|complete/.test(document.readyState)) {
			init(); // call the onload handler
		}
	}, 10);
}

/* other alternatives */
if (window.attachEvent) {
	window.attachEvent('onload', init);
} else if (window.addEventListener) {
	window.addEventListener('load', init, false);
}

/* if all else fails try this */
window.onload = init;
{/literal}
</script>
{if $smarty.const.iMEMBER && $user_can_post && !$thread.thread_locked}
{include file="_opentable.tpl" name=$_name title=$locale.512 state=$_state style=$_style}
<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}?forum_id={$forum_id}&amp;thread_id={$thread_id}'>
	<table align='center' cellpadding='0' cellspacing='1' width='75%' class='tbl-border'>
		<tr>
			<td align='center' class='tbl1'>
				<textarea name='message' cols='80' rows='7' class='textbox' style='width:100%; height:{math equation='x/4' x=$smarty.const.BROWSER_HEIGHT format='%u'}px;'></textarea>
				<br />
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('message', '[b]', '[/b]');" />
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('message', '[i]', '[/i]');" />
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('message', '[u]', '[/u]');" />
				<input type='button' value='ul' class='button' style='width:25px;' onclick="addText('message', '[ul]', '[/ul]');" />
				<input type='button' value='li' class='button' style='width:25px;' onclick="addText('message', '[li]', '[/li]');" />
				<input type='button' value='url' class='button' style='width:30px;' onclick="addURL('message');" />
				<input type='button' value='mail' class='button' style='width:35px;' onclick="addText('message', '[mail]', '[/mail]');" />
				<input type='button' value='img' class='button' style='width:30px;' onclick="addText('message', '[img]', '[/img]');" />
				<input type='button' value='center' class='button' style='width:45px;' onclick="addText('message', '[center]', '[/center]');" />
				<input type='button' value='small' class='button' style='width:40px;' onclick="addText('message', '[small]', '[/small]');" />
				<input type='button' value='code' class='button' style='width:40px;' onclick="addText('message', '[code]', '[/code]');" />
				<input type='button' value='quote' class='button' style='width:45px;' onclick="addText('message', '[quote]', '[/quote]');" />
			</td>
		</tr>
		<tr>
			<td align='center' class='tbl2'>
				<div id='smileys' style='display:none'><img src='{$smarty.const.THEME}images/ajax-loader.gif' title='' alt='' /></div>
				<br />
				<input type='hidden' id='smileys_loaded' name='smileys_loaded' value='0' />
			</td>
		</tr>
		<tr>
			<td align='center' class='tbl1'>
				<input type='submit' name='postquickreply' value='{$locale.514}' class='button' />&nbsp; &nbsp;
				<input type='button' name='toggle' class='button' value='{$locale.517}' onclick='javascript:loadSmileys("smileys", "smileys_loaded", "{$smarty.const.BASEDIR}includes/ajax.smileys.php?field=message");return false;' />
				<input type='hidden' name='random_id' value='{$random_id}' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{/if}
{***************************************************************************}

{* End of template                                                         *}
{***************************************************************************}

