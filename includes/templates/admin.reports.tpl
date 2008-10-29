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
{* Template for the admin configuration module 'reports'                   *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{if $action == "add" || $action == "edit"}
	<form name='reportform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
		<table align='center' cellpadding='0' cellspacing='1' width='600'>
			<tr>
				<td class='tbl'>
					{$locale.420}
				</td>
				<td class='tbl1'>
					{if $report.custom}
						<input type='text' name='name' value='{$report.report_name}' class='textbox' style='width:230px;' />
					{else}
						<b>{$report.report_name}</b>
					{/if}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.421}
				</td>
				<td class='tbl1'>
					{if $report.custom}
						<input type='text' name='module' value='{$report.mod_folder}' class='textbox' style='width:230px;' />
					{else}
						<b>{$report.mod_folder}</b>
					{/if}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.422}
				</td>
				<td class='tbl1'>
					{if $report.custom}
						<input type='text' name='module' value='{$report.report_title}' class='textbox' style='width:230px;' />
					{else}
						<b>{$report.report_title}</b>
					{/if}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.423}
				</td>
				<td class='tbl1'>
					<select name='report_visibility' class='textbox'>
					{section name=id loop=$usergroups}
						<option value='{$usergroups[id].0}'{if $usergroups[id].0 == $report.report_visibility} selected="selected"{/if}>{$usergroups[id].1}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<br />
					<input type='hidden' name='action' value='{$action}' class='button' />
					<input type='hidden' name='report_id' value='{$report_id}' class='button' />
					<input type='submit' name='save' value='{$locale.424}' class='button' />
				</td>
			</tr>
		</table>
	</form>
{else}
	<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
		<tr>
			<td class='tbl2'>
				<b>{$locale.401}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.402}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.403}</b>
			</td>
		</tr>
		{section name=id loop=$reports}
		<tr>
			<td class='tbl1'>
				{$reports[id].report_title}
				{if $reports[id].mod_folder != ""}
					&nbsp;({$locale.410} {$reports[id].mod_folder})
				{/if}
			</td>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{$reports[id].groupname}
			</td>
			<td align='left' width='1%' class='tbl1' style='white-space:nowrap'>
				{if $reports[id].report_active}
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=setstatus&amp;status=0&amp;report_id="|cat:$reports[id].report_id image=page_red.gif alt=$locale.404 title=$locale.404}
				{else}
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=setstatus&amp;status=1&amp;report_id="|cat:$reports[id].report_id image=page_green.gif alt=$locale.405 title=$locale.405}
				{/if}
				&nbsp;
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=edit&amp;report_id="|cat:$reports[id].report_id image="page_edit.gif" alt=$locale.406 title=$locale.406}
				{if $reports[id].report_mod_id == 0 && $reports[id].report_mod_core == 0}
					&nbsp;
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=delete&amp;report_id="|cat:$reports[id].report_id image="page_delete.gif" alt=$locale.407 title=$locale.407}
				{/if}
				&nbsp;
				{imagelink link=$smarty.const.BASEDIR|cat:"reports.php"|cat:$aidlink|cat:"&amp;report_id="|cat:$reports[id].report_id image="image_view.gif" alt=$locale.411 title=$locale.411}
			</td>
		</tr>
		{sectionelse}
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				{$locale.408}
				<br /><br />
			</td>
		</tr>
		{/section}
{* NO OPTION TO ADD A REPORT MANUALLY AT THE MOMENT *
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				{buttonlink name=$locale.409 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=add"}
			</td>
		</tr> 
*}
	</table>
{/if}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
