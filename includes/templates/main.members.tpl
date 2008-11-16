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
{* Template for the main module 'members', to display the member list      *}
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
		<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
			<tr>
				<td class='tbl2'>
					{if $order == "username"}
						 <b>{$locale.401}</b> <img src='{$smarty.const.THEME}images/panel_on.gif' alt='' />
					{else}
						<a href='{$smarty.const.FUSION_SELF}?order=username&amp;field={$field}&amp;sortby={$sortby}&amp;country={$country}'><b>{$locale.401}</b></a>
					{/if}
				</td>
				{if $userdata.user_level >= 102 || $settings.forum_flags}
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
					{if $order == "country"}
						 <b>{$locale.406}</b> <img src='{$smarty.const.THEME}images/panel_on.gif' alt='' />
					{else}
						<a href='{$smarty.const.FUSION_SELF}?order=country&amp;field={$field}&amp;sortby={$sortby}&amp;country={$country}'><b>{$locale.406}</b></a>
					{/if}
				</td>
				{/if}
				{if $smarty.const.iSUPERADMIN}
					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
						{if $order == "email"}
							 <b>{$locale.409}</b> <img src='{$smarty.const.THEME}images/panel_on.gif' alt='' />
						{else}
							<a href='{$smarty.const.FUSION_SELF}?order=email&amp;field={$field}&amp;sortby={$sortby}&amp;country={$country}'><b>{$locale.409}</b></a>
						{/if}
					</td>
					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
						{if $order == "lastvisit"}
							 <b>{$locale.409}</b> <img src='{$smarty.const.THEME}images/panel_on.gif' alt='' />
						{else}
							<a href='{$smarty.const.FUSION_SELF}?order=lastvisit&amp;field={$field}&amp;sortby={$sortby}&amp;country={$country}'><b>{$locale.405}</b></a>
						{/if}
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
						{$members[id].cc_flag}{if $members[id].user_cc_code == ""}{$members[id].cc_name}{else}<a href='{$smarty.const.FUSION_SELF}?order={$order}&amp;sortby={$sortby}&amp;field={$field}&amp;country={$members[id].user_cc_code}'>{$members[id].cc_name}</a>{/if}
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
					{foreach from=$search item=letter name=search}
					{if $smarty.foreach.search.first}
						{math equation="x/2-1" x=$smarty.foreach.search.total format="%u" assign='break'}
					{/if}
					<td align='center' class='tbl1'>
						<div class='small'>
							<a href='{$smarty.const.FUSION_SELF}?field={$field}&amp;sortby={$letter}{if $country !=""}&amp;country={$country}{/if}'>{$letter}</a>
						</div>
					</td>
				{if !$smarty.foreach.search.last && $smarty.foreach.search.index==$break}
				</tr>
				<tr>
				{/if}
					{/foreach}
				</tr>
			</table>
			<div style='text-align:center'>
				{buttonlink name=$locale.404|sprintf:$locale.401 link=$smarty.const.FUSION_SELF|cat:"?field=username"|cat:"&amp;order="|cat:$order|cat:"&amp;sortby=all&amp;country="|cat:$country}
				{if $smarty.const.iSUPERADMIN}
					&nbsp;
					{buttonlink name=$locale.404|sprintf:$locale.409 link=$smarty.const.FUSION_SELF|cat:"?field=email"|cat:"&amp;order="|cat:$order|cat:"&amp;sortby=all&amp;country="|cat:$country}
				{/if}
				{if $sortby != "all"}
					&nbsp;
					{buttonlink name=$locale.414 link=$smarty.const.FUSION_SELF|cat:"?field="|cat:$field|cat:"&amp;order="|cat:$order|cat:"&amp;sortby=all"}
				{/if}
			</div>
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
	{makepagenav start=$rowstart count=$items_per_page total=$rows range=$settings.navbar_range link=$pagenav_url}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
