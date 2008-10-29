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
{* Template for the main module 'profile'.                                 *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.410 state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
	<tr>
		<td align='center' colspan='{if $smarty.const.iUSER >= 102}4{else}2{/if}' class='tbl1'>
			{if $smarty.const.iUSER >= 102}
				<div style='display:inline;float:right;'>{buttonlink name=$locale.u053 link=$smarty.const.BASEDIR|cat:"pm.php?action=post&amp;group_id="|cat:$data.group_id}</div>
			{/if}
			<b>{$data.group_name}</b> ({$data.member_count})
		</td>
	</tr>
	<tr>
		<td class='tbl2'>
			<b>{$locale.401}</b>
		</td>
		{if $smarty.const.iUSER >= 102}
			<td width='1%' class='tbl2' style='white-space:nowrap'>
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
	{section name=entry loop=$members}
	<tr>
		<td class='{cycle values='tbl1,tbl2' advance=false}'>
			{$members[entry].cc_flag} <a href='profile.php?lookup={$members[entry].user_id}'>{$members[entry].user_name}</a>
		</td>
		{if $smarty.const.iUSER >= 102}
			<td class='{cycle values='tbl1,tbl2' advance=false}' style='white-space:nowrap'>
				{$members[entry].user_email}
			</td>
			<td class='{cycle values='tbl1,tbl2' advance=false}' style='white-space:nowrap'>
				{if $members[entry].user_lastvisit != 0}
					{$members[entry].user_lastvisit|date_format:"forumdate"}
				{else}
					{$locale.u049}
				{/if}
			</td>
		{/if}
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
				{$members[entry].user_level}
		</td>
	</tr>
{/section}
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
