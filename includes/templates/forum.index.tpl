{***************************************************************************}
{* ExiteCMS Content Management System                                      *}
{***************************************************************************}
{* Copyright 2006-2008 Exite BV, The Netherlands                           *}
{* for support, please visit http://www.exitecms.org                       *}
{*-------------------------------------------------------------------------*}
{* Released under the terms & conditions of v2 of the GNU General Public   *}
{* License. For details refer to the included gpl.txt file or visit        *}
{* http://gnu.org                                                          *}
{***************************************************************************}
{* $Id::                                                                  $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author::                                             $*}
{* Revision number $Rev::                                                 $*}
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
			{if $ad1|default:"" != ""}
				<tr>
					<td colspan='6' class='tbl2' align='center'>
						{$ad1}
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
						<td colspan='6' class='main-label'>
							<a name='cat_{$forums[id].cat_id}'></a>{$forums[id].forum_cat_name}
						</td>
					</tr>
					{if !iMEMBER && $settings.forum_guest_limit}
						<tr>
							<td class='tbl2' align='center' colspan='6'>
								<span class='small' style='font-size:90%;font-weight:bold;'>{if $settings.forum_guest_limit == 1}{$locale.462|sprintf:$locale.074}{else}{assign var=days value=$settings.forum_guest_limit|cat:" "|cat:$locale.075}{$locale.462|sprintf:$days}{/if}</span>
							</td>
						</tr>
					{/if}
					{/if}
					<tr>
						<td width='1%' align='center' class='{cycle values='tbl1,tbl2' advance=no}'>
							<a href='{$smarty.const.BASEDIR}feeds.php?type=forum&amp;id={$forums[id].forum_id}'><img src='{$smarty.const.THEME}images/rss-icon.gif' alt='' /></a>
						</td>
						<td width='1%' align='center' class='{cycle values='tbl1,tbl2' advance=no}'>
						{if $forums[id].unread_posts == 0}
					    	<img src='{$smarty.const.THEME}images/folder.gif' alt='{$locale.561}' />
						{elseif $forums[id].unread_posts <= $smarty.const.FOLDER_HOT}
					    	<img src='{$smarty.const.THEME}images/foldernew.gif' alt='{$locale.560}' />
						{else}
					    	<img src='{$smarty.const.THEME}images/folderhot.gif' alt='{$locale.561}' />
						{/if}
						</td>
						<td class='{cycle values='tbl1,tbl2' advance=no}'>
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
						<td align='center' class='{cycle values='tbl1,tbl2' advance=no}'>
							{$forums[id].thread_count}
						</td>
						<td align='center' class='{cycle values='tbl1,tbl2' advance=no}'>
							{$forums[id].total_posts}
						</td>
						<td class='{cycle values='tbl1,tbl2' advance=yes}'>
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
					<td colspan='6' class='tbl1'>
						{$locale.407}
					</td>
				</tr>
				{/section}
			{if $ad2|default:"" != ""}
				<tr>
					<td colspan='6' class='tbl2' align='center'>
						{$ad2}
					</td>
				</tr>
			{/if}
			</table>
		</td>
	</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
	<tr>
		<td class='forum'>
			<br />
			<img src='{$smarty.const.THEME}images/foldernew.gif' alt='{$locale.560}' style='vertical-align:middle;' /> - {$locale.409}
			<br />
			<img src='{$smarty.const.THEME}images/folder.gif' alt='{$locale.561}' style='vertical-align:middle;' /> - {$locale.410}
		</td>
		<td align='right' valign='bottom' class='forum'>
			{buttonlink name=$locale.415 link="tracking.php"}
			{buttonlink name=$locale.414 link="viewposts.php"}<br />
			<form name='searchform' method='post' style="margin-top:2px;" action='{$smarty.const.BASEDIR}search.php?action=search'>
				<input type='text' name='stext' class='textbox' style='width:150px' />
				<input type='hidden' name='boolean' value='1' />
				<input type='hidden' name='qtype' value='AND' />
				<input type='hidden' name='search_id' value='3.2' />
				<input type='hidden' name='sortby' value='datestamp' />
				<input type='hidden' name='order' value='0' />
				<input type='hidden' name='limit' value='0' />
				<input type='hidden' name='datelimit' value='0' />
				<input type='hidden' name='contentfilter_users' value='0' />
				<input type='hidden' name='contentfilter_forums' value='0' />
				<input type='submit' name='search' value='{$locale.550}' class='button' />
			</form>
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
