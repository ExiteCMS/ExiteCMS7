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
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
	<tr>
		<td>
			<table border='0' cellpadding='0' cellspacing='1' width='100%'>
			{if $advert|default:"" != ""}
				<tr>
					<td colspan='6' class='tbl2' align='center'>
						{$advert}
					</td>
				</tr>
			{/if}
				<tr>
					<td colspan='3' class='tbl2'>
						<b>{$locale.401}</b>
					</td>
					<td align='center' width='55' class='tbl2'>
						<b>{$locale.402}</b>
					</td>
					<td align='center' width='55' class='tbl2'>
						<b>{$locale.403}</b>
					</td>
					<td width='120' class='tbl2'>
						<b>{$locale.404}</b>
					</td>
				</tr>
				{section name=id loop=$forums}
					{if $forums[id].new_cat == true}
					<tr>
						<td colspan='6' class='forum-caption'>
							<a name='cat_{$forums[id].cat_id}'></a>{$forums[id].forum_cat_name}
						</td>
					</tr>
					{/if}
					<tr>
						<td class='tbl1'>
							<a href='{$smarty.const.BASEDIR}feeds.php?type=forum&amp;id={$forums[id].forum_id}'><img src='{$smarty.const.THEME}images/rss-icon.gif' alt='' /></a>
						</td>
						<td align='center' class='tbl2'>
						{if $forums[id].unread_posts == 0}
					    	<img src='{$smarty.const.THEME}forum/folder.gif' alt='{$locale.561}' />
						{elseif $forums[id].unread_posts <= $smarty.const.FOLDER_HOT}
					    	<img src='{$smarty.const.THEME}forum/foldernew.gif' alt='{$locale.560}' />
						{else}
					    	<img src='{$smarty.const.THEME}forum/folderhot.gif' alt='{$locale.561}' />
						{/if}
						</td>
						<td class='tbl1'>
							<a href='viewforum.php?forum_id={$forums[id].forum_id}'>{$forums[id].forum_name}</a>
							<br />
							<span class='small'>{$forums[id].forum_description}
							{section name=mod loop=$forums[id].moderators}
								{if $smarty.section.mod.first}<br />{$locale.411} {/if}
								{if $forums[id].moderators[mod].type == "G"}
									{if $smarty.const.iMEMBER}
    								<a href='{$smarty.const.BASEDIR}profile.php?group_id={$forums[id].moderators[mod].id}'>{$forums[id].moderators[mod].name}</a>
									{else}
    								<u>{$forums[id].moderators[mod].name}</u>
									{/if}
								{elseif $forums[id].moderators[mod].type == "U"}
									{if $smarty.const.iMEMBER}
									<a href='{$smarty.const.BASEDIR}profile.php?lookup={$forums[id].moderators[mod].id}'>{$forums[id].moderators[mod].name}</a>
									{else}
    								<u>{$forums[id].moderators[mod].name}</u>
									{/if}
								{/if}
								{if !$smarty.section.mod.last}, {/if}
							{/section}
							</span>
						</td>
						<td align='center' class='tbl2'>
							{$forums[id].thread_count}
						</td>
						<td align='center' class='tbl1'>
							{$forums[id].total_posts}
						</td>
						<td class='tbl2'>
						{if $forums[id].forum_lastpost == 0}
							{$locale.405}
						{else}
							{$forums[id].forum_lastpost|date_format:"forumdate"}
							<br />
							<span class='small'>
								{$locale.406}
								{if $forums[id].forum_lastuser == 0}
									{$locale.sysusr}
								{else}
									{if $smarty.const.iMEMBER}
										<a href='{$smarty.const.BASEDIR}profile.php?lookup={$forums[id].forum_lastuser}'>{$forums[id].user_name}</a>
									{else}
										{$forums[id].user_name}
									{/if}
								{/if}
							</span>
						{/if}
						</td>
				</tr>
				{sectionelse}
				<tr>
					<td colspan='5' class='tbl1'>
						{$locale.407}
					</td>
				</tr>
				{/section}
			</table>
		</td>
	</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
	<tr>
		<td class='forum'>
			<br />
			<img src='{$smarty.const.THEME}forum/foldernew.gif' alt='{$locale.560}' style='vertical-align:middle;' /> - {$locale.409}
			<br />
			<img src='{$smarty.const.THEME}forum/folder.gif' alt='{$locale.561}' style='vertical-align:middle;' /> - {$locale.410}
		</td>
		<td align='right' valign='bottom' class='forum'>
			<form name='searchform' method='post' action='{$smarty.const.BASEDIR}search.php?stype=f'>
				<input type='text' name='stext' class='textbox' style='width:150px' />
				<input type='submit' name='search' value='{$locale.550}' class='button' />
			</form>
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}