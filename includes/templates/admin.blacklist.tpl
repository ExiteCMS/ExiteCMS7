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
{if !$blacklist_user && !$blacklist_ip}
<table align='center' cellpadding='0' cellspacing='0' width='600'>
	<tr>
		<td colspan='2' class='tbl'>
			{$locale.440}
			<hr />
		</td>
	</tr>
</table>
{/if}
<form name='blacklist_form' method='post' action='{$form_action}'>
	<table align='center' cellpadding='0' cellspacing='0' width='600'>
		{if $blacklist_user|default:"" == ""}
		<tr>
			<td class='tbl' style='white-space:nowrap;'>
				{$locale.441}
			</td>
			<td class='tbl'>
				<input type='text' name='blacklist_ip' value='{$blacklist_ip}' class='textbox' style='width:150px' />
			</td>
		</tr>
		{/if}
		{if $blacklist_ip|default:"" =="" && $blacklist_user|default:"" == ""}
		<tr>
			<td class='tbl' colspan='2'>
				{$locale.430}
			</td>
		</tr>
		<tr>
			<td class='tbl' style='white-space:nowrap;'>
				{$locale.442}
			</td>
			<td class='tbl'>
				<input type='text' name='blacklist_email' value='{$blacklist_email}' class='textbox' style='width:250px' />
			</td>
		</tr>
		<tr>
			<td class='tbl' colspan='2'>
				{$locale.430}
			</td>
		</tr>
		{/if}
		{if $blacklist_ip|default:"" ==""}
		<tr>
			<td class='tbl' style='white-space:nowrap;'>
				{$locale.445}
			</td>
			<td class='tbl'>
				<select name='blacklist_user' class='textbox' style='width:250px'>
					{if !$blacklist_user}<option value='0'></option>{/if}
				{section name=id loop=$users}
					<option value='{$users[id].user_id}'{if $users[id].user_id == $blacklist_user} selected="selected"{/if}>{$users[id].user_name}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' style='white-space:nowrap;'>
				{$locale.446}
			</td>
			<td class='tbl'>
				<input type='text' name='blacklist_timeout' value='{$blacklist_timeout}' class='textbox' style='width:100px' />
				{$locale.447}
			</td>
		</tr>
		{/if}
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<hr />
			</td>
		</tr>
		<tr>
			<td class='tbl' style='white-space:nowrap;'>
				{$locale.443}
			</td>
			<td class='tbl'>
				<textarea name='blacklist_reason' cols='46' rows='3' class='textbox'>{$blacklist_reason}</textarea>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='blacklist' value='{$locale.444}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$locale.460 state=$_state style=$_style}
{section name=id loop=$blacklist}
	{if $smarty.section.id.first}
	<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
		<tr>
			<td class='tbl2'>
				{$locale.461}
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				{$locale.462}
			</td>
		</tr>
	{/if}
	{if $blacklist[id].user_id|default:0}
		<tr>
			<td class='{cycle values='tbl1,tbl2' advance=no}'>
				{$blacklist[id].user_name}
				{if $blacklist[id].user_ban_expire}
					<span class='small2'>- {$locale.447} {$blacklist[id].user_ban_expire|date_format:"forumdate"}</span>
				{/if}
				<br />
				<span class='small2'>{$blacklist[id].user_ban_reason}</span>
			</td>
			<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=edit&amp;user_id={$blacklist[id].user_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.463}' title='{$locale.463}' /></a>&nbsp;
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;user_id={$blacklist[id].user_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.464}' title='{$locale.464}' /></a>
			</td>
		</tr>
	{else}
		<tr>
			<td class='{cycle values='tbl1,tbl2' advance=no}'>
				{if $blacklist[id].blacklist_ip}{$blacklist[id].blacklist_ip}{$blacklist[id].blacklist_email}{/if}
				<br />
				<span class='small2'>{$blacklist[ip].blacklist_reason}</span>
			</td>
			<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=edit&amp;blacklist_id={$blacklist[id].blacklist_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.463}' title='{$locale.463}' /></a>&nbsp;
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;blacklist_id={$blacklist[id].blacklist_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.464}' title='{$locale.464}' /></a>
			</td>
		</tr>
	{/if}
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
