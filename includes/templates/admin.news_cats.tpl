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
{* Template for the admin content module 'news-cats'                       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='addcat' method='post' action='{$formaction}'>
	<table align='center' cellpadding='0' cellspacing='0' width='400'>
		<tr>
			<td width='130' class='tbl'>
				{$locale.436}
			</td>
			<td class='tbl'>
				<input type='text' name='cat_name' value='{$cat_name}' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='1%' class='tbl' style='white-space:nowrap'>
				{$locale.473}
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
				{foreach from=$image_list item=image name=image_list}
					<option value='{$image}' {if $cat_image == $image}selected='selected'{/if}>{$image}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'></td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<input type='submit' name='save_cat' value='{$locale.438}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$locale.440 state=$_state style=$_style}
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
	{section name=id loop=$cats}
		<tr>
			<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
				{if $cats[id].image_exists}
					<img src='{$smarty.const.IMAGES_NC}{$cats[id].news_cat_image}' alt='{$cats[id].news_cat_name}' style='padding:5px;' />
				{else}
					<img src='{$smarty.const.IMAGES}imagenotfound.jpg' alt='{$cats[id].news_cat_name}' style='padding:5px;' />
				{/if}
			</td>
			<td valign='top' class='{cycle values='tbl1,tbl2' advance=no}'>
				<b>{$cats[id].news_cat_name}</b>
			</td>
			<td valign='top' align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
				{$cats[id].access_group}
			</td>
			<td valign='top' align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;cat_id={$cats[id].news_cat_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.441}' title='{$locale.441}' /></a>&nbsp;
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;cat_id={$cats[id].news_cat_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.442}' title='{$locale.442}' /></a>
			</td>
		</tr>
	{sectionelse}
		<center>
		<br />
		{$locale.443}
		<br /><br />
		</center>
	{/section}
</table>
<center>
	<br />
	{buttonlink name=$locale.439 link=$smarty.const.ADMIN|cat:"images.php"|cat:$aidlink|cat:"&amp;ifolder=news_cats"}
	<br /><br />
</center>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
