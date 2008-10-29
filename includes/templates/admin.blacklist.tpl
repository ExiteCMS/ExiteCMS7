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
{* Template for the admin user-admin module 'blacklist'                    *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$form_title state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='0' width='450'>
	<tr>
		<td colspan='2' class='tbl'>
			{$locale.440}
			<hr />
		</td>
	</tr>
</table>
<form name='blacklist_form' method='post' action='{$form_action}'>
	<table align='center' width='450' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='tbl'>
				{$locale.441}
			</td>
			<td class='tbl'>
				<input type='text' name='blacklist_ip' value='{$blacklist_ip}' class='textbox' style='width:150px' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.442}
			</td>
			<td class='tbl'>
				<input type='text' name='blacklist_email' value='{$blacklist_email}' class='textbox' style='width:250px' />
			</td>
		</tr>
		<tr>
			<td valign='top' class='tbl'>
				{$locale.443}
			</td>
			<td class='tbl'>
				<textarea name='blacklist_reason' cols='46' rows='3' class='textbox'>{$blacklist_reason}</textarea>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='blacklist_user' value='{$locale.444}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$locale.460 state=$_state style=$_style}
{section name=id loop=$blacklist}
	{if $smarty.section.id.first}
	<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>
		<tr>
			<td class='tbl2'>
				{$locale.461}
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				{$locale.462}
			</td>
		</tr>
	{/if}
		<tr>
			<td class='{cycle values='tbl1,tbl2' advance=no}'>
				{if $blacklist[id].blacklist_ip}{$blacklist[id].blacklist_ip}{else}{$blacklist[id].blacklist_email}{/if}
				<br />
				<span class='small2'>{$blacklist[ip].blacklist_reason}</span>
			</td>
			<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=edit&amp;blacklist_id={$blacklist[id].blacklist_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.463}' title='{$locale.463}' /></a>&nbsp;
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;blacklist_id={$blacklist[id].blacklist_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.464}' title='{$locale.464}' /></a>
			</td>
		</tr>
	{if $smarty.section.id.last}
	</table>
	{/if}
{sectionelse}
	<center>
	<br />
	{$locale.465}
	<br /><br />
	</center>
{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
