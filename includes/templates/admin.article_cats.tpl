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
{* Template for the admin content module 'article_cats'                    *}
{*                                                                         *}
{***************************************************************************}
{if $action == "add" || $action == "edit"}
	{if $settings.article_localisation == "multiple"}
		{assign var="tabletitle" value=$_title|cat:" "|cat:$locale.554|cat:" '<b>"|cat:$cat_locale|cat:"</b>'"}
	{else}
		{assign var="tabletitle" value=$_title}
	{/if}
	{include file="_opentable.tpl" name=$_name title=$tabletitle state=$_state style=$_style}
	<form name='addcat' method='post' action='{$formaction}'>
		<table align='center' cellpadding='0' cellspacing='0' width='400'>
			<tr>
				<td width='1%' class='tbl' style='white-space:nowrap'>
					{$locale.457}
				</td>
				<td class='tbl'>
					<input type='text' name='cat_name' value='{$cat_name}' class='textbox' style='width:250px;' />
				</td>
			</tr>
			{if $settings.article_localisation == "multiple"}
				<tr>
					<td width='1%' class='tbl' style='white-space:nowrap'>
						{$locale.426}
					</td>
					<td class='tbl'>
						{html_options name=cat_locale options=$locales selected=$cat_locale class="textbox"}
					</td>
				</tr>
			{/if}
			<tr>
				<td width='1%' class='tbl' style='white-space:nowrap'>
					{$locale.458}
				</td>
				<td class='tbl'>
					<input type='text' name='cat_description' value='{$cat_description}' class='textbox' style='width:250px;' />
				</td>
			</tr>
			<tr>
				<td width='1%' class='tbl' style='white-space:nowrap'>
					{$locale.467}
				</td>
				<td class='tbl'>
					<select name='cat_sort_by' class='textbox'>
						<option value='1'{if  $cat_sort_by == "1"} selected='selected'{/if}>{$locale.468}</option>
						<option value='1'{if  $cat_sort_by == "2"} selected='selected'{/if}>{$locale.469}</option>
						<option value='1'{if  $cat_sort_by == "3"} selected='selected'{/if}>{$locale.470}</option>
					</select> - 
					<select name='cat_sort_order' class='textbox'>
						<option value='ASC'{if  $cat_sort_order == "ASC"} selected='selected'{/if}>{$locale.471}</option>
						<option value='DESC'{if  $cat_sort_order == "DESC"} selected='selected'{/if}>{$locale.472}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width='1%' class='tbl' style='white-space:nowrap'>
					{$locale.465}
				</td>
				<td class='tbl'>
					<select name='cat_access' class='textbox'>
					{section name=id loop=$user_groups}
						<option value='{$user_groups[id].id}'{if $user_groups[id].selected} selected='selected'{/if}>{$user_groups[id].name}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td width='130' class='tbl'>
					{$locale.437}
				</td>
				<td class='tbl'>
					<select name='cat_image' class='textbox' style='width:200px;'>
						<option value=''{if $cat_image == ""} selected='selected'{/if}>{$image}</option>
					{foreach from=$image_list item=image name=image_list}
						<option value='{$image}'{if $cat_image == $image} selected='selected'{/if}>{$image}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					{if $settings.article_localisation != "multiple"}
						<input type='hidden' name='cat_locale' value=''>
					{/if}
					<input type='submit' name='save_cat' value='{$locale.459}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.460 state=$_state style=$_style}
{if $settings.article_localisation == "multiple"}
	{assign var="url_locale" value="&amp;cat_locale="|cat:$cat_locale}
	<br />
	<div style='text-align:center;'>
		{$locale.553} {html_options name=cat_locale options=$locales selected=$cat_locale class="textbox" onchange="location = '"|cat:$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;cat_locale=' + this.options[this.selectedIndex].value;"}
	</div>
{else}
	{assign var="cat_locale" value=""}
{/if}
<br />
<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
	<tr>
		<td colspan='2' class='tbl2'>
			<b>{$locale.461}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.466}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.462}</b>
		</td>
	</tr>
	{section name=id loop=$articles}
		<tr>
			<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
				<img src='{$smarty.const.IMAGES_NC}{$articles[id].article_cat_image}' alt='' title='' />
			</td>
			<td valign='top' class='{cycle values='tbl1,tbl2' advance=no}'>
				<b>{$articles[id].article_cat_name}</b>
				<br />
				<span class='small'>{$articles[id].article_cat_description|truncate:45}</span>
			</td>
			<td valign='top' align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
				{$articles[id].access_group}
			</td>
			<td valign='top' align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;cat_id={$articles[id].article_cat_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.509}' title='{$locale.509}' /></a>&nbsp;
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;cat_id={$articles[id].article_cat_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.510}' title='{$locale.510}' /></a>
			</td>
		</tr>
	{sectionelse}
		<tr>
			<td colspan='3' align='center' class='tbl1'>
				{$locale.518}
			</td>
		</tr>
	{/section}
</table>
<div style='text-align:center;'>
	<br />
	{buttonlink name=$locale.456 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=add"|cat:$url_locale}
</div>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
