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
{include file="_opentable.tpl" name=$_name title=$locale.500 state=$_state style=$_style}
<table cellspacing='0' cellpadding='0' width='100%'>
	<tr>
		<td class='smallalt'>
			<a href='{$smarty.const.BASEDIR}'>{$settings.sitename}</a> � <a href='index.php'>{$locale.400}</a> � <a href='viewforum.php?forum_id={$forum_id}'>{$forum.forum_name}</a>
		</td>
		<td class='tbl_title'>
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
	</tr>
</table>
{if $smarty.const.iMEMBER && $user_can_post && $thread.thread_author != 0 && ($smarty.const.iMOD || $smarty.const.iSUPERADMIN)}
<table cellpadding='0' cellspacing='0' width='100%' style='margin-top:5px;'>
	<tr>
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
{if $rows > $smarty.const.ITEMS_PER_PAGE}
<div align='center' style='margin-top:5px;margin-bottom:5px;'>
	{makepagenav start=$rowstart count=$smarty.const.ITEMS_PER_PAGE total=$rows range=3 link=$pagenav_url}
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
{if $rows > $smarty.const.ITEMS_PER_PAGE}
<div align='center' style='margin-top:5px;margin-bottom:5px;'>
	{makepagenav start=$rowstart count=$smarty.const.ITEMS_PER_PAGE total=$rows range=3 link=$pagenav_url}
</div>
{/if}
<table cellspacing='0' cellpadding='0' width='100%'>
	<tr>
		<td class='tbl1' colspan='3' height='5'>
		</td>
	</tr>
	<tr>
		<td class='smallalt'>
			<a href='{$smarty.const.BASEDIR}'>{$settings.sitename}</a> � <a href='index.php'>{$locale.400}</a> � <a href='viewforum.php?forum_id={$forum_id}'>{$forum.forum_name}</a>
		</td>
		<td class='tbl_title'>
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
	</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%' style='margin-top:5px;'>
	<tr>
		<td align='left' class='tbl'>
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
		<td align='center'>
		{if $smarty.const.iMEMBER && $user_can_post}
			{if $unread_posts}
				{buttonlink name=$locale.573 link="viewforum.php?action=markallread&amp;forum_id="|cat:$forum_id}&nbsp;
			{/if}
		{/if}
		</td>
		<td align='right' class='tbl'>
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
				<div id='smileys' style='display:none'>{displaysmileys field="message"}</div>
				<br />
				<input type='checkbox' name='disable_smileys' value='1' />{$locale.513}
			</td>
		</tr>
		<tr>
			<td align='center' class='tbl1'>
				<input type='submit' name='postquickreply' value='{$locale.514}' class='button' />&nbsp; &nbsp;
				<input type='button' name='toggle' class='button' value='{$locale.517}' onclick='javascript:flipDiv("smileys");return false;' />
				<input type='hidden' name='random_id' value='{$random_id}' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{/if}
<script type='text/javascript'>
function jumpForum(forumid) {ldelim}
	document.location.href='{$smarty.const.FORUM}viewforum.php?forum_id='+forumid;
{rdelim}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}