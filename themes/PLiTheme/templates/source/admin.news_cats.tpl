{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.news_cats.tpl                            *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-22 - WW - Initial version                                       *}
{*                                                                         *}
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
			<td width='130' class='tbl'>
				{$locale.437}
			</td>
			<td class='tbl'>
				<select name='cat_image' class='textbox' style='width:200px;'>
				{foreach from=$image_list item=image name=image_list}
					<option value='{$image}'>{$image}</option>
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
{assign var="columns" value="4"} 													{* number of columns *}
{math equation="(100 - x) / x" x=$columns format="%u" assign="colwidth"}
{section name=id loop=$cats}
{cycle name=column values="1,2,3,4" assign="column" print=no} 						{* keep track of the current column *}
	{if $smarty.section.id.first}
	<table align='center' cellpadding='0' cellspacing='1' width='600'>
	{/if}
	{if $column == 1}<tr>{/if}
	<td align='center' width='{$colwidth}%' class='tbl'>
		<b>{$cats[id].news_cat_name}</b>
		<br />
		{if $cats[id].image_exists}
			<img src='{$smarty.const.IMAGES_NC}{$cats[id].news_cat_image}' alt='{$cats[id].news_cat_name}' style='padding:5px;' />
		{else}
			<img src='{$smarty.const.IMAGES}imagenotfound.jpg' alt='{$cats[id].news_cat_name}' style='padding:5px;' />
		{/if}
		<br />
		<span class='small'>
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;cat_id={$cats[id].news_cat_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.441}' title='{$locale.441}' /></a>&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;cat_id={$cats[id].news_cat_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.442}' title='{$locale.442}' /></a>
		</span>
	</td>
	{if $column == $columns}</tr>{/if}
	{if $smarty.section.id.last}
		{if $column != $columns}
		{section name=dummy start=$column loop=$columns}
			<td width='{math equation='x+1' x=$colwidth}%' colspan='2' class='tbl1' style='vertical-align:top'>
			</td>
		{/section}
		</tr>
		{/if}
	</table>
	{/if}
{sectionelse}
	<center>
	<br />
	{$locale.443}
	<br /><br />
	</center>
{/section}
<center>
	<br />
	{buttonlink name=$locale.439 link=$smarty.const.ADMIN|cat:"images.php"|cat:$aidlink|cat:"&amp;ifolder=news_cats"}
	<br /><br />
</center>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}