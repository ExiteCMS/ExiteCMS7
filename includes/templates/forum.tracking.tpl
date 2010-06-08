{***************************************************************************}
{* ExiteCMS Content Management System                                      *}
{***************************************************************************}
{* Copyright 2006-2010 Exite BV, The Netherlands                           *}
{* for support, please visit http://www.exitecms.org                       *}
{*-------------------------------------------------------------------------*}
{* Released under the terms & conditions of v2 of the GNU General Public   *}
{* License. For details refer to the included gpl.txt file or visit        *}
{* http://gnu.org                                                          *}
{***************************************************************************}
{* $Id:: forum.viewforum.tpl 2061 2008-11-20 15:59:24Z WanWizard          $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author:: WanWizard                                   $*}
{* Revision number $Rev:: 2061                                            $*}
{***************************************************************************}
{*                                                                         *}
{* Template for the forum module 'tracking'                                *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{if $rows > $settings.numofthreads}
	<div align='center' style='margin-top:5px;margin-bottom:5px;'>
		{makepagenav start=$rowstart count=$settings.numofthreads total=$rows range=$settings.navbar_range}
	</div>
{else}
	<br />
{/if}
{section name=id loop=$tracking}
	{if $smarty.section.id.first}
	<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
		<tr style="font-weight:bold;">
			<td align='left' class='tbl2'>
				{$locale.405}
			</td>
			<td align='left' class='tbl2'>
				{$locale.406}
			</td>
			<td align='center' class='tbl2'>
				{$locale.407}
			</td>
			<td align='center' class='tbl2'>
				{$locale.408}
			</td>
			<td align='center' class='tbl2'>
				{$locale.409}
			</td>
		</tr>
		{/if}
		<tr>
			<td align='left' class='tbl1' style="white-space:nowrap;">
				{$tracking[id].forum_name}
			</td>
			<td align='left' class='tbl1'>
				{$tracking[id].thread_subject}
			</td>
			<td align='center' class='tbl1' style="white-space:nowrap;">
				{$tracking[id].notify_datestamp|date_format:"forumdate"}
			</td>
			<td align='center' class='tbl1'>
				{if $tracking[id].notify_status == 1}
					{$locale.403}
				{else}
					{$locale.404}
				{/if}
			</td>
			<td align='center' class='tbl1'>
				<a href='{$smarty.const.BASEDIR}forum/tracking.php?rowstart={$rowstart}&amp;thread_id={$tracking[id].thread_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.410}' title='{$locale.410}' /></a>
			</td>
		</tr>
	{if $smarty.section.id.last}
	</table>
	{/if}
{sectionelse}
<table cellpadding='0' cellspacing='0' width='100%' class='tbl'>
	<tr>
		<td align='center' class='tbl1' style='font-weight:bold;'>
			{$locale.401}
		</td>
	</tr>
</table>
{/section}

<table cellpadding='0' cellspacing='0' width='100%' class='tbl'>
	<tr>
		<td width='33%' align='left'>
			{buttonlink name=$locale.402 link="index.php"}
		</td>
		<td width='33%' align='center'>
			{if $rows > $settings.numofthreads}
				{makepagenav start=$rowstart count=$settings.numofthreads total=$rows range=$settings.navbar_range}
			{/if}
		</td>
		<td width='33%' align='right'>
			{if $rows > 0}
				{buttonlink name=$locale.411 link="tracking.php?rowstart="|cat:$rowstart|cat:"&amp;thread_id=all"}
			{/if}
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
