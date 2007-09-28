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
{include file="_opentable.tpl" name=$_name title=$locale.450 state=$_state style=$_style}
<table cellspacing='0' cellpadding='0' width='100%'>
	<tr>
		<td style='font-size:10px;height:20px;'>
			<a href='{$smarty.const.BASEDIR}'>{$settings.sitename}</a> »
			<a href='index.php'>{$locale.400}</a> » 
			<a href='index.php#cat_{$forum_cat_id}'>{$forum_cat_name}</a> » 
			{$forum_name}
		</td>
		{if $smarty.const.iMEMBER && $user_can_post}
			<td align='right'>
				{if $unread_posts}
					{buttonlink name=$locale.573 link="viewforum.php?action=markallread&amp;forum_id="|cat:$forum_id}
				{/if}
				{buttonlink name=$locale.566 link="post.php?action=newthread&amp;forum_id="|cat:$forum_id}
			</td>
		{/if}
	</tr>
</table>
{if $rows > $smarty.const.ITEMS_PER_PAGE}
<div align='center' style='margin-top:5px;margin-bottom:5px;'>
	{makepagenav start=$rowstart count=$smarty.const.ITEMS_PER_PAGE total=$rows range=3 link=$pagenav_url}
</div>
{/if}
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
	<tr>
		<td>
			<table cellspacing='1' cellpadding='0' width='100%'>
			{if $forum.forum_banners && $advert|default:"" != ""}
				<tr>
					<td colspan='7' class='tbl2' align='center'>
						{$advert}
					</td>
				</tr>
			{/if}
			{if $user_can_post && $rulespage_defined}
				<tr>
					<td colspan='7' class='tbl2' align='center'>
						<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />&nbsp;<a class='tbl_top_mid' href='/viewpage.php?page_id={$forum.forum_rulespage}'>{$forum.forum_rulestitle}</a>&nbsp;<img src='{$smarty.const.THEME}images/bulletb.gif' alt='' />
					</td>
				</tr>
			{/if}
				<tr>
					<td width='20' class='tbl2'>
						&nbsp;
					</td>
					<td width='20' class='tbl2'>
						&nbsp;
					</td>
					<td class='tbl2'>
						<b>{$locale.451}</b>
					</td>
					<td width='150' class='tbl2'>
						<b>{$locale.452}</b>
					</td>
					<td align='center' width='50' class='tbl2'>
						<b>{$locale.453}</b>
					</td>
					<td align='center' width='50' class='tbl2'>
						<b>{$locale.454}</b>
					</td>
					<td width='145' class='tbl2'>
						<b>{$locale.404}</b>
					</td>
				</tr>
			{section name=id loop=$threads}				
				<tr>
					<td align='center' width='20' class='tbl2'>
						{if $threads[id].thread_locked && $threads[id].unread_posts == 0}
							<img src='{$smarty.const.THEME}images/folderlock.gif' title='{$locale.564}' alt='{$locale.564}' />
						{elseif $threads[id].thread_locked && $threads[id].unread_posts > 0}
							<img src='{$smarty.const.THEME}images/folderlocknew.gif' title='{$locale.564}' alt='{$locale.564}' />
						{elseif $threads[id].unread_posts == 0}
							<img src='{$smarty.const.THEME}images/folder.gif' title='{$locale.561}' alt='{$locale.561}' />
						{elseif $threads[id].unread_posts <= $smarty.const.FOLDER_HOT}
							<img src='{$smarty.const.THEME}images/foldernew.gif' title='{$locale.560}' alt='{$locale.560}' />
						{else}
							<img src='{$smarty.const.THEME}images/folderhot.gif' title='{$locale.562}' alt='{$locale.562}' />
						{/if}
					</td>
					<td align='center' width='20' class='tbl1'>
						<a href='viewthread.php?forum_id={$forum_id}&amp;thread_id={$threads[id].thread_id}&amp;pid={$threads[id].last_post}#post_{$threads[id].last_post}'>
							<img src='{$smarty.const.THEME}images/last_post.jpg' title='{$locale.574}' alt='{$locale.574}' />
						</a>
					</td>
					<td class='tbl1'>
						{if $threads[id].thread_sticky}
							<img src='{$smarty.const.THEME}images/stickythread.gif' title='{$locale.563}' alt='{$locale.563}' style='vertical-align:middle;' />
						{/if}
						<a href='viewthread.php?forum_id={$forum_id}&amp;thread_id={$threads[id].thread_id}'>{$threads[id].thread_subject}</a>
						{if $threads[id].thread_pages > 2}
							{$locale.412}
							(
							{section name=rs start=1 loop=$threads[id].thread_pages step=1}
								<a href='viewthread.php?forum_id={$forum_id}&amp;thread_id={$threads[id].thread_id}&amp;rowstart={math equation='(x-1)*y' x=$smarty.section.rs.index y=$smarty.const.ITEMS_PER_PAGE}'>{$smarty.section.rs.index}</a>
							{/section}
							)
							{if $threads[id].is_poll}
								<b>{$locale.FPM_200}</b>
							{/if}
						{/if}
					</td>
					<td class='tbl2'>
						{$threads[id].cc_flag}
						{if $threads[id].thread_author == 0}
							{$locale.sysusr}
						{else}
							{if $smarty.const.iMEMBER}
								<a href='{$smarty.const.BASEDIR}profile.php?lookup={$threads[id].thread_author}'>{$threads[id].user_author}</a>
							{else}
								{$threads[id].user_author}
							{/if}
						{/if}
					</td>
					<td align='center' class='tbl1'>
						{$threads[id].thread_views}
					</td>
					<td align='center' class='tbl2'>
						{$threads[id].thread_replies}
					</td>
					<td class='tbl1'>
						{$threads[id].thread_lastpost|date_format:"forumdate"}
						<br />
						<span class='small'>
							{$locale.406}
							{if $threads[id].thread_lastuser == 0}
								{$locale.sysusr}
							{else}
								{if $smarty.const.iMEMBER}
									<a href='{$smarty.const.BASEDIR}profile.php?lookup={$threads[id].thread_lastuser}'>{$threads[id].user_lastuser}</a>
								{else}
									{$threads[id].user_lastuser}
								{/if}
							{/if}
						</span>
					</td>
				</tr>
			{sectionelse}
				<tr>
					<td colspan='7' class='tbl1'>
						{$locale.455}
					</td>
				</tr>
			{/section}
			</table>
		</td>
	</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0' style='margin-top:5px;'>
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
		<td align='center' class='tbl'>
			{if $rows > $smarty.const.ITEMS_PER_PAGE}
			<div align='center' style='margin-top:5px;margin-bottom:5px;'>
				{makepagenav start=$rowstart count=$smarty.const.ITEMS_PER_PAGE total=$rows range=3 link=$pagenav_url}
			</div>
			{/if}
		</td>
		{if $smarty.const.iMEMBER && $user_can_post}
			<td align='right'>
			{if $unread_posts}
				{buttonlink name=$locale.573 link="viewforum.php?action=markallread&amp;forum_id="|cat:$forum_id}
			{/if}
			{buttonlink name=$locale.566 link="post.php?action=newthread&amp;forum_id="|cat:$forum_id}
			</td>
		{/if}
	</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
	<tr>
		<td class='tbl1'>
			<img src='{$smarty.const.THEME}images/foldernew.gif' alt='{$locale.560}' style='vertical-align:middle;' /> - {$locale.456}
			(<img src='{$smarty.const.THEME}images/folderhot.gif' alt='{$locale.562}' style='vertical-align:middle;' /> - {$smarty.const.FOLDER_HOT} {$locale.457} )
			<br />
			<img src='{$smarty.const.THEME}images/folder.gif' alt='{$locale.561}' style='vertical-align:middle;' /> - {$locale.458}
			<br />
			<img src='{$smarty.const.THEME}images/folderlock.gif' alt='{$locale.564}' style='vertical-align:middle;' /> - {$locale.459}
			<br />
			<img src='{$smarty.const.THEME}images/stickythread.gif' alt='{$locale.563}' style='vertical-align:middle;' /> - {$locale.460}
		</td>
	</tr>
</table>
{literal}<script type='text/javascript'>
function DeleteItem() {
	return confirm('Delete this thread?');
}
function jumpForum(forumid) {
	document.location.href='{/literal}{$smarty.const.FORUM}{literal}viewforum.php?forum_id='+forumid;
}
</script>{/literal}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}