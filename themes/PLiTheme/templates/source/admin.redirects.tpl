{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.redirects.tpl                            *}
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
{* Template for the admin content module 'redirects'                       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='{$action}_redirect' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action={$action}&amp;url_id={$url_id}'>
	<table align='center' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='tbl'>
				{$locale.420}:
			</td>
			<td class='tbl'>
				<input type='text' name='url_from' value='{$url_from}' class='textbox' style='width:500px' />
			</td>
		</tr>
		<tr>
			<td width='175' class='tbl'>
				{$locale.421}:
			</td>
			<td class='tbl'>
				<input type='text' name='url_to' value='{$url_to}' class='textbox' style='width:500px;' />
			</td>
		</tr>
		<tr>
			<td width='175' class='tbl'>
				{$locale.422}:
			</td>
			<td class='tbl'>
				<input type='checkbox' name='url_redirect' value='1'{if $url_redirect} checked{/if} />
			</td>
		</tr>
		<tr>
			<td width='175' class='tbl'>
				{$locale.426}:
			</td>
			<td class='tbl'>
				<input type='checkbox' name='url_regex' value='1'{if $url_regex} checked{/if} />&nbsp; <span class='small2'>{$locale.490}</span>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<input type='submit' name='save' value='{if $action == "add"}{$locale.430}{else}{$locale.431}{/if}' class='button' />
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<div class='small2'>{$locale.481}</div>
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$locale.403 state=$_state style=$_style}
{section name=id loop=$redirects}
	{if $smarty.section.id.first}
	<table align='center' cellpadding='0' cellspacing='1' width='700' class='tbl-border'>
		<tr>
			<td align='left' class='tbl2'>
				<b>{$locale.420}</b>
			</td>
			<td align='left' class='tbl2'>
				<b>{$locale.421}</b>
			</td>
			<td align='left' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.422}</b>
			</td>
			<td align='left' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.426}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.423}</b>
			</td>
		</tr>
	{/if}
	<tr>
		{if $redirects[id].url_regex}
			<td align='left' colspan='2' class='{cycle values='tbl1,tbl2' advance=no}'>
				<b>{$locale.426}</b>: {$redirects[id].url_from}
			</td>
		{else}
			<td align='left' class='{cycle values='tbl1,tbl2' advance=no}'>
				{$redirects[id].url_from}
			</td>
			<td align='left' class='{cycle values='tbl1,tbl2' advance=no}'>
				{$redirects[id].url_to}
			</td>
		{/if}
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
			{if $redirects[id].url_redirect}
				{$locale.424}
			{else}
				{$locale.425}
			{/if}
		</td>
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
			{if $redirects[id].url_regex}
				{$locale.424}
			{else}
				{$locale.425}
			{/if}
		</td>
		<td align='center' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;url_id={$redirects[id].url_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.432}' title='{$locale.432}' /></a>&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;url_id={$redirects[id].url_id}' onClick='return DeleteItem()'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.433}' title='{$locale.433}' /></a>&nbsp;
		</td>
	</tr>
	{if $smarty.section.id.last}
	</table>
	<script type='text/javascript'>
	function DeleteItem() {ldelim}
		return confirm('{$locale.488}');
	{rdelim}
	</script>
	{/if}
{sectionelse}
	<center>
	{$locale.480}
	<br />
	</center>
{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}