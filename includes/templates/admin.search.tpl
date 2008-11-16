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
{* Template for the admin configuration module 'searches'                  *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{if $action == "add" || $action == "edit"}
	<form name='searchform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
		<table align='center' cellpadding='0' cellspacing='1' width='100%'>
			<tr>
				<td class='tbl'>
					{$locale.420}
				</td>
				<td class='tbl1'>
					{if $search.custom}
						<input type='text' name='name' value='{$search.search_name}' class='textbox' style='width:230px;' />
					{else}
						<b>{$search.search_name}</b>
					{/if}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.421}
				</td>
				<td class='tbl1'>
					{if $search.custom}
						<input type='text' name='module' value='{$search.mod_folder}' class='textbox' style='width:230px;' />
					{else}
						<b>{$search.mod_folder}</b>
					{/if}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.422}
				</td>
				<td class='tbl1'>
					{if $search.custom}
						<input type='text' name='module' value='{$search.search_title}' class='textbox' style='width:230px;' />
					{else}
						<b>{$search.search_title}</b>
					{/if}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.423}
				</td>
				<td class='tbl1'>
					<select name='search_visibility' class='textbox'>
					{section name=id loop=$usergroups}
						<option value='{$usergroups[id].0}'{if $usergroups[id].0 == $search.search_visibility} selected="selected"{/if}>{$usergroups[id].1}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<br />
					<input type='hidden' name='action' value='{$action}' class='button' />
					<input type='hidden' name='search_id' value='{$search_id}' class='button' />
					<input type='submit' name='save' value='{$locale.424}' class='button' />
				</td>
			</tr>
		</table>
	</form>
{else}
	<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
		<tr>
			<td class='tbl2'>
				<b>{$locale.401}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.402}</b>
			</td>
			<td colspan='2' align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.429}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.403}</b>
			</td>
		</tr>
		{section name=id loop=$searches}
		<tr>
			<td class='tbl1'>
				{$searches[id].search_title}
				{if $searches[id].mod_folder != ""}
					&nbsp;({$locale.410} {$searches[id].mod_folder})
				{/if}
			</td>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{$searches[id].groupname}
			</td>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{$searches[id].search_order}
			</td>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
			{if $searches[id].order_up != 0}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=swap&amp;order1="|cat:$searches[id].search_order|cat:"&amp;order2="|cat:$searches[id].order_up image="up.gif" alt="$locale.425 title=$locale.427}
			{/if}
			{if $searches[id].order_down != 0}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=swap&amp;order1="|cat:$searches[id].search_order|cat:"&amp;order2="|cat:$searches[id].order_down image="down.gif" alt="$locale.426 title=$locale.428}
			{/if}
			</td>
			<td align='left' width='1%' class='tbl1' style='white-space:nowrap'>
				{if $searches[id].search_active}
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=setstatus&amp;status=0&amp;search_id="|cat:$searches[id].search_id image=page_red.gif alt=$locale.404 title=$locale.404}
				{else}
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=setstatus&amp;status=1&amp;search_id="|cat:$searches[id].search_id image=page_green.gif alt=$locale.405 title=$locale.405}
				{/if}
				&nbsp;
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=edit&amp;search_id="|cat:$searches[id].search_id image="page_edit.gif" alt=$locale.406 title=$locale.406}
				{if $searches[id].search_mod_id == 0 && $searches[id].search_mod_core == 0}
					&nbsp;
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=delete&amp;search_id="|cat:$searches[id].search_id image="page_delete.gif" alt=$locale.407 title=$locale.407}
				{/if}
			</td>
		</tr>
		{sectionelse}
		<tr>
			<td align='center' colspan='5' class='tbl1'>
				{$locale.408}
				<br /><br />
			</td>
		</tr>
		{/section}
		<tr>
			<td align='center' colspan='5' class='tbl1'>
				<span class='smallalt'>{$locale.430}</span>
			</td>
		</tr>
{*		<tr>
			<td align='center' colspan='5' class='tbl1'>
				{buttonlink name=$locale.409 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=add"}
			</td>
		</tr>
*}	</table>
{/if}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
