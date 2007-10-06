{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.viewpage.tpl                                    *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-09 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'viewpage', to display a custom page       *}
{*                                                                         *}
{***************************************************************************}
{if $country_name|default:"" == ""}
	{assign var=_title value=$locale.400}
{else}
	{assign var=_title value=$locale.400|cat:" "|cat:$locale.470|cat:" <b>"|cat:$country_name|cat:"</b>"}
{/if}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
{if !$smarty.const.iMEMBER}
	<center>
		<br />
		{$locale.003}
		<br /><br />
	</center>
{else}
	{section name=id loop=$members}
		{if $smarty.section.id.first}
		<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
			<tr>
				<td class='tbl2'>
					<b>{$locale.401}</b>
				</td>
				{if $userdata.user_level >= 102 || $settings.forum_flags}
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
					<b>{$locale.406}</b>
				</td>
				{/if}
				{if $smarty.const.iSUPERADMIN}
					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
						<b>{$locale.409}</b>
					</td>
					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
						<b>{$locale.405}</b>
					</td>
				{/if}
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
					<b>{$locale.402}</b>
				</td>
			</tr>
		{/if}
			<tr>
				<td class='{cycle values='tbl1,tbl2' advance=no}'>
					<a href='profile.php?lookup={$members[id].user_id}'>{$members[id].user_name}</a>
				</td>
				{if $userdata.user_level >= 102 || $settings.forum_flags}
					<td align='left' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
						{$members[id].cc_flag}{if $members[id].user_cc_code == ""}{$members[id].cc_name}{else}<a href='{$smarty.const.FUSION_SELF}?sortby={$sortby}&amp;country={$members[id].user_cc_code}'>{$members[id].cc_name}</a>{/if}
					</td>
				{/if}
				{if $smarty.const.iSUPERADMIN}
					<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
						{$members[id].user_email}
					</td>
					<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
						{if $members[id].user_lastvisit == 0}{$locale.407}{else}{$members[id].user_lastvisit|date_format:"forumdate"}{/if}
					</td>
				{/if}
				<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
					{$members[id].user_level}
				</td>
			</tr>
		{if $smarty.section.id.last}
		</table>
		<br />
		<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
			<tr>
				<td rowspan='2' class='tbl2'>
					<a href='{$smarty.const.FUSION_SELF}?sortby=all'>{$locale.404}</a>
				</td>
				{foreach from=$search item=letter name=search}
				{if $smarty.foreach.search.first}
					{math equation="x/2-1" x=$smarty.foreach.search.total format="%u" assign='break'}
				{/if}
				<td align='center' class='tbl1'>
					<div class='small'>
						<a href='{$smarty.const.FUSION_SELF}?sortby={$letter}{if $country !=""}&amp;country={$country}{/if}'>{$letter}</a>
					</div>
				</td>
			{if $smarty.foreach.search.index==$break}
						<td rowspan='2' class='tbl2'>
					<a href='{$smarty.const.FUSION_SELF}?sortby=all'>{$locale.404}</a>
				</td>
			</tr>
			<tr>
			{/if}
				{/foreach}
			</tr>
		</table>
		{/if}
	{sectionelse}
		<center>
			<br />
			{$error|default:$locale.403}
			<br /><br />
		</center>
	{/section}
{/if}
{include file="_closetable.tpl"}
{if $rows > $items_per_page}
	{makepagenav start=$rowstart count=$items_per_page total=$rows range=3 link=$pagenav_url}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}