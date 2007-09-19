{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.faq.tpl                                  *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-18 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'faq'                             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$faq_cat_title state=$_state style=$_style}
<form name='add_faq_cat' method='post' action='{$faq_cat_action}'>
	<table align='center' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='tbl'>
				{$locale.440}:
			</td>
			<td class='tbl'>
				<input type='text' name='faq_cat_name' value='{$faq_cat_name}' class='textbox' style='width:210px' />
			</td>
		</tr>
		<tr>
			<td width='130' class='tbl'>
				{$locale.442}:
			</td>
			<td class='tbl'>
				<input type='text' name='faq_cat_description' value='{$faq_cat_description}' class='textbox' style='width:250px;' />
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<input type='submit' name='save_cat' value='{$locale.441}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{if $action != "edit" || $actiontype == "faq"}
{include file="_opentable.tpl" name=$_name title=$faq_title state=$_state style=$_style}
{section name=id loop=$cats}
	{if $smarty.section.id.first}
	<form name='inputform' method='post' action='{$faq_action}'>
		<table align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl'>
					{$locale.460}:&nbsp;
				</td>
				<td class='tbl'>
					<select name='faq_cat' class='textbox' style='width:250px;'>
	{/if}
						<option value='{$cats[id].faq_cat_id}'{if $cats[id].selected} selected{/if}>{$cats[id].faq_cat_name}</option>
	{if $smarty.section.id.last}
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.461}:&nbsp;
				</td>
				<td class='tbl'>
					<input type='text' name='faq_question' value='{$faq_question}' class='textbox' style='width:335px' />
				</td>
			</tr>
			<tr>
				<td valign='top' class='tbl'>
					{$locale.462}:&nbsp;
				</td>
				<td class='tbl'>
					<textarea name='faq_answer' rows='5' class='textbox' style='width:335px;'>{$faq_answer}</textarea>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
				</td>
				<td class='tbl'>
					<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick="addText('faq_answer', '<b>', '</b>');">
					<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick="addText('faq_answer', '<i>', '</i>');">
					<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick="addText('faq_answer', '<u>', '</u>');">
					<input type='button' value='link' class='button' style='width:35px' onClick="addText('faq_answer', '<a href=\'', '\' target=\'_blank\'>Link</a>');">
					<input type='button' value='img' class='button' style='width:35px' onClick="addText('faq_answer', '<img src=\'', '\' style=\'margin:5px\' align=\'left\'>');">
					<input type='button' value='center' class='button' style='width:45px' onClick="addText('faq_answer', '<center>', '</center>');">
					<input type='button' value='small' class='button' style='width:40px' onClick="addText('faq_answer', '<span class=\'small\'>', '</span>');">
					<input type='button' value='small2' class='button' style='width:45px' onClick="addText('faq_answer', '<span class=\'small2\'>', '</span>');">
					<input type='button' value='alt' class='button' style='width:25px' onClick="addText('faq_answer', '<span class=\'alt\'>', '</span>');">
					<br />
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<br />
					<input type='submit' name='save_faq' value='{$locale.463}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{/if}
{sectionelse}
	<center>
		{$locale.486}
		<br />
	</center>
{/section}
{include file="_closetable.tpl"}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.480 state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='0' width='400'>
{section name=id loop=$tree}
	{if $smarty.section.id.first}
	<tr>
		<td class='tbl2'>
			{$locale.481}
		</td>
		<td align='right' class='tbl2'>
			{$locale.482}
		</td>
	</tr>
	<tr>
		<td colspan='2' height='1'>
		</td>
	</tr>
	{/if}
	{if $tree[id].node == "C"}
	{if $tree[id].faq_cat_id == $faq_cat_id}
		{assign var='open' value=true}
	{else}
		{assign var='open' value=false}
	{/if}
	<tr>
		<td class='tbl2'>
			<img onclick="javascript:flipBox('{$tree[id].faq_cat_id}')" src='{$smarty.const.THEME}images/panel_{if $open}off{else}on{/if}.gif' name='b_{$tree[id].faq_cat_id}' />&nbsp;{$tree[id].faq_cat_name}
		</td>
		<td class='tbl2' align='right' style='font-weight:normal;'>
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;faq_cat_id={$tree[id].faq_cat_id}&amp;t=cat'>{$locale.483}</a> |
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;faq_cat_id={$tree[id].faq_cat_id}&amp;t=cat' onClick='return DeleteItem()'>{$locale.484}</a>
		</td>
	</tr>
	{elseif $tree[id].node == "A"}
	{if $tree[id].first}
	<tr>
		<td colspan='2'>
			<div id='box_{$tree[id].faq_cat_id}'{if !$open} style='display:none'{/if}>
				<table cellpadding='0' cellspacing='0' width='100%'>
	{/if}
					<tr>
						<td class='tbl'>
							<b>{$tree[id].faq_question}</b>
						</td>
						<td align='right' width='100' class='tbl'>
							<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;faq_cat_id={$tree[id].faq_cat_id}&amp;faq_id={$tree[id].faq_id}&amp;t=faq'>{$locale.483}</a> |
							<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;faq_cat_id={$tree[id].faq_cat_id}&amp;faq_id={$tree[id].faq_id}&amp;t=faq' onClick='return DeleteItem()'>{$locale.484}</a>
						</td>
					</tr>
	{if $tree[id].last}
				</table>
			</div>
		</td>
	</tr>
	{/if}
	{/if}
	{if $smarty.section.id.last}
	{/if}
{sectionelse}
	<center>
		{$locale.485}
		<br />
	</center>
{/section}
</table>
{include file="_closetable.tpl"}
<script type='text/javascript'>
function DeleteItem()
{ldelim}
	return confirm('{$locale.487}');
{rdelim}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}