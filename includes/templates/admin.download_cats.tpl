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
{* Template for the admin content module 'download_cats'                   *}
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
		{if $settings.download_localisation == "multiple"}
			<tr>
				<td width='1%' class='tbl' style='white-space:nowrap'>
					{$locale.514}
				</td>
				<td class='tbl'>
					{html_options name=cat_locale options=$locales selected=$cat_locale class="textbox"}
				</td>
			</tr>
		{/if}
		<tr>
			<td width='1%' class='tbl' style='vertical-align:top;white-space:nowrap'>
				{$locale.431}
			</td>
			<td class='tbl'>
				{if $settings.hoteditor_enabled == 0 || $userdata.user_hoteditor == 0}
					<textarea name='cat_description' rows='5' cols='80' class='textbox' style='width:350px'>{$cat_description}</textarea><br />
					<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('cat_description', '[b]', '[/b]');" />
					<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('cat_description', '[i]', '[/i]');" />
					<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('cat_description', '[u]', '[/u]');" />
					<input type='button' value='url' class='button' style='width:30px;' onclick="addText('cat_description', '[url]', '[/url]');" />
					<input type='button' value='mail' class='button' style='width:35px;' onclick="addText('cat_description', '[mail]', '[/mail]');" />
					<input type='button' value='img' class='button' style='width:30px;' onclick="addText('cat_description', '[img]', '[/img]');" />
					<input type='button' value='center' class='button' style='width:45px;' onclick="addText('cat_description', '[center]', '[/center]');" />
					<input type='button' value='small' class='button' style='width:40px;' onclick="addText('cat_description', '[small]', '[/small]');" />
				{else}
					<script language="javascript" type="text/javascript">
						// non-standard toolbars for this editor instance
						var toolbar1 ="SPACE,btFont_Name,btFont_Size,btFont_Color,btHighlight";
						var toolbar2 ="SPACE,btRemove_Format,SPACE,btBold,btItalic,btUnderline,SPACE,btAlign_Left,btCenter,btAlign_Right,SPACE,btStrikethrough,btSubscript,btSuperscript,btHorizontal";
						var toolbar3 ="SPACE,btHyperlink,btHyperlink_Email,btInsert_Image,btEmotions";

						var textarea_toolbar1 ="SPACE,btFont_Name,btFont_Size,btFont_Color,btHighlight";
						var textarea_toolbar2 ="SPACE,btRemove_Format,SPACE,btBold,btItalic,btUnderline,SPACE,btAlign_Left,btCenter,btAlign_Right,SPACE,btStrikethrough,btSubscript,btSuperscript,btHorizontal";
						var textarea_toolbar3 ="SPACE,btHyperlink,btHyperlink_Email,btInsert_Image,btEmotions";
					</script>
					{include file="_bbcode_editor.tpl" name="cat_description" id="cat_description" author="" message=$cat_description width="350px" height="200px"}
				{/if}
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
						{* remove the selected category from the dropdown *}
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
			<td width='130' class='tbl'>
			</td>
			<td class='tbl'>
				<input type='checkbox' name='update_datestamp' value='0' /> {$locale.429}
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				{if $settings.hoteditor_enabled == 0 || $userdata.user_hoteditor == 0}
					<input type='submit' name='save_cat' value='{$locale.432}' class='button' />
				{else}
					<input type='submit' name='save_cat' value='{$locale.432}' class='button' onclick='javascript:get_hoteditor_data("cat_description");' />
				{/if}
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{if $settings.download_localisation == "multiple"}
	{assign var="tabletitle" value=$locale.440|cat:" "|cat:$locale.513|cat:" '<b>"|cat:$cat_locale|cat:"</b>'"}
{else}
	{assign var="tabletitle" value=$locale.440}
{/if}
{include file="_opentable.tpl" name=$_name title=$tabletitle state=$_state style=$_style}
{if $settings.download_localisation == "multiple"}
	{assign var="url_locale" value="&amp;cat_locale="|cat:$cat_locale}
	<br />
	<div style='text-align:center;'>
		{$locale.553} {html_options name=cat_locale options=$locales selected=$cat_locale class="textbox" onchange="location = '"|cat:$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;cat_locale=' + this.options[this.selectedIndex].value;"}
	</div>
{else}
	{assign var="cat_locale" value=""}
{/if}
<br />
<table align='center' width='100%' cellspacing='1' cellpadding='0' class='tbl-border'>
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
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=edit&amp;cat_id={$cats[dc].download_cat_id}&amp;cat_locale={$cat_locale}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.503}' title='{$locale.503}' /></a>&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;cat_id={$cats[dc].download_cat_id}&amp;cat_locale={$cat_locale}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.504}' title='{$locale.504}' /></a>&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=copy&amp;cat_id={$cats[dc].download_cat_id}&amp;cat_locale={$cat_locale}'><img src='{$smarty.const.THEME}images/page_copy.gif' alt='{$locale.517}' title='{$locale.517}' /></a>
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
