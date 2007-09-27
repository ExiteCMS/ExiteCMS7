{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.article_cats.tpl                               *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-16 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'article_cats'                    *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
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
					<option value='1'{if  $cat_sort_by == "1"} selected{/if}>{$locale.468}</option>
					<option value='1'{if  $cat_sort_by == "2"} selected{/if}>{$locale.469}</option>
					<option value='1'{if  $cat_sort_by == "3"} selected{/if}>{$locale.470}</option>
				</select> - 
				<select name='cat_sort_order' class='textbox'>
					<option value='ASC'{if  $cat_sort_order == "ASC"} selected{/if}>{$locale.471}</option>
					<option value='DESC'{if  $cat_sort_order == "DESC"} selected{/if}>{$locale.472}</option>
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
					<option value='{$user_groups[id].id}{if $user_groups[id].selected} selected{/if}'>{$user_groups[id].name}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<input type='submit' name='save_cat' value='{$locale.459}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$locale.460 state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>
{section name=id loop=$articles}
	{if $smarty.section.id.first}
	<tr>
		<td class='tbl2'>
			<b>{$locale.461}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.466}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.462}</b>
		</td>
	</tr>
	{/if}
	<tr>
		<td class='{cycle values='tbl1,tbl2' advance=no}'>
			<b>{$articles[id].article_cat_name}</b>
			<br />
			<span class='small'>{$articles[id].article_cat_description|truncate:45}</span>
		</td>
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
			{$articles[id].access_group}
		</td>
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;cat_id={$articles[id].article_cat_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.509}' title='{$locale.509}' /></a>&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;cat_id={$articles[id].article_cat_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.510}' title='{$locale.510}' /></a>
		</td>
	</tr>
{sectionelse}
	<tr>
		<td align='center' class='tbl1'>
			{$locale.518}
		</td>
	</tr>
{/section}
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}