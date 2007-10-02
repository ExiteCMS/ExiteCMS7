{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.adverts.tpl                                    *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-06 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'advertising'                     *}
{*                                                                         *}
{***************************************************************************}
{if $errormessage|default:"" != ""}
	{include file="_message_table_panel.tpl" name=$_name title=$locale.400 state=$_state style=$_style message=$errormessage}
{/if}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='addcat' method='post' action='{$formaction}'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td width='1%' class='tbl' style='white-space:nowrap'>
				{$locale.430}
			</td>
			<td class='tbl'>
				<input type='text' name='cat_name' value='{$cat_name}' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='1%' class='tbl' style='white-space:nowrap'>
				{$locale.431}
			</td>
			<td class='tbl'>
				<input type='text' name='cat_description' value='{$cat_description}' class='textbox' style='width:250px;' />
			</td>
		</tr>
		<tr>
			<td width='1%' class='tbl' style='white-space:nowrap'>
				{$locale.443}
			</td>
			<td class='tbl'>
				<select name='cat_sub' class='textbox'>
					<option value='0'{if $cat_sub == 0} selected{/if}>{$locale.455}</option>
				{section name=cat loop=$cats}
					{if $is_edit && $cats[cat].download_cat_id == $cat_id}
					{else}
					<option value='{$cats[cat].download_cat_id}'{if $cat_sub == $cats[cat].download_cat_id} selected{/if}>{$cats[cat].download_cat_name}</option>
					{/if}
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td width='1%' class='tbl' style='white-space:nowrap'>
				{$locale.450}
			</td>
			<td class='tbl'>
				<select name='cat_cat_sort_by' class='textbox'>
					<option value='1'{if $cat_cat_sort_by == "1"} selected{/if}>{$locale.451}</option>
					<option value='2'{if $cat_cat_sort_by == "2"} selected{/if}>{$locale.452}</option>
					<option value='3'{if $cat_cat_sort_by == "3"} selected{/if}>{$locale.453}</option>
					<option value='4'{if $cat_cat_sort_by == "4"} selected{/if}>{$locale.454}</option>
				</select> - 
				<select name='cat_cat_sort_order' class='textbox'>
					<option value='ASC'{if $cat_cat_sort_order == "ASC"} selected{/if}>{$locale.438}</option>
					<option value='DESC'{if $cat_cat_sort_order == "DESC"} selected{/if}>{$locale.439}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='1%' class='tbl' style='white-space:nowrap'>
				{$locale.434}
			</td>
			<td class='tbl'>
				<select name='cat_sort_by' class='textbox'>
					<option value='1'{if $cat_sort_by == "1"} selected{/if}>{$locale.435}</option>
					<option value='2'{if $cat_sort_by == "2"} selected{/if}>{$locale.436}</option>
					<option value='3'{if $cat_sort_by == "3"} selected{/if}>{$locale.437}</option>
				</select> - 
				<select name='cat_sort_order' class='textbox'>
					<option value='ASC'{if $cat_sort_order == "ASC"} selected{/if}>{$locale.438}</option>
					<option value='DESC'{if $cat_sort_order == "DESC"} selected{/if}>{$locale.439}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='1%' class='tbl' style='white-space:nowrap'>
				{$locale.433}
			</td>
			<td class='tbl'>
				<select name='cat_access' class='textbox' style='width:150px;'>
				{section name=id loop=$groups}
					<option value='{$groups[id].id}'{if $cat_access == $groups[id].id} selected{/if}>{$groups[id].name}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td width='130' class='tbl'>
				{$locale.442}
			</td>
			<td class='tbl'>
				<select name='cat_image' class='textbox' style='width:200px;'>
					<option value=''>&nbsp;</option>
					{foreach from=$images item=image}
					<option value='{$image}'{if $cat_image == $image} selected{/if}>{$image}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<input type='submit' name='save_cat' value='{$locale.432}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$locale.440 state=$_state style=$_style}
<table align='center' width='550' cellspacing='1' cellpadding='0' class='tbl-border'>
{section name=dc loop=$cats}
{if $smarty.section.dc.first}
	<tr>
		<td class='tbl2'>
			<b>{$locale.441}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.512}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.445}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.502}</b>
		</td>
	</tr>
{/if}
	<tr>
		<td class='{cycle values='tbl1,tbl2' advance=no}'>
			<b>{$cats[dc].download_cat_name}</b>
		<br>
		<span class='small2'>{$cats[dc].download_cat_description|truncate:50:"..."}</span>
		</td>
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
			{$cats[dc].parent_cat_name|default:$locale.455}
		</td>
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
			{$cats[dc].group_name}
		</td>
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=edit&amp;cat_id={$cats[dc].download_cat_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.503}' title='{$locale.503}' /></a>&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;cat_id={$cats[dc].download_cat_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.504}' title='{$locale.504}' /></a>
		</td>
</tr>
{sectionelse}
	<tr>
		<td align='center' class='tbl1'>
			{$locale.508}
		</td>
	</tr>
{/section}
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}