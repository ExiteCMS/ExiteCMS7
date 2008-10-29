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
{* Template for the admin user-admin module 'shoutbox'                     *}
{*                                                                         *}
{***************************************************************************}
{if $action == "edit"}
	{include file="_opentable.tpl" name=$_name title=$locale.420 state=$_state style=$_style}
	<form name='editform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;shout_id={$editdata.shout_id}'>
		<table align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl'>
					{$locale.421}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					<textarea name='shout_message' rows='3' class='textbox' style='width:250px;'>{$editdata.shout_message}</textarea>
				</td>
			</tr>
			<tr>
				<td align='center' class='tbl'>
					<input type='submit' name='saveshout' value='{$locale.422}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.440 state=$_state style=$_style}
{section name=id loop=$shouts}
	{if $smarty.section.id.first}
	{if $rowstart == 0}
	<form name='deleteform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=deleteshouts'>
		<div align='center'>
			{$locale.430}
			<select name='num_days' class='textbox' style='width:50px'>
				<option value='90'>90</option>
				<option value='60'>60</option>
				<option value='30'>30</option>
				<option value='20'>20</option>
				<option value='10'>10</option>
			</select>
			{$locale.431}
			<br /><br />
			<input type='submit' name='deleteshouts' value='{$locale.432}' class='button' />
			<br /><hr />
		</div>
	</form>
	{/if}
	<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	{/if}
		<tr>
			<td class='tbl2'>
				{$locale.406}
				<b>
					{if $shouts[id].user_name|default:"" != ""}
						<a href='{$smarty.const.BASEDIR}profile.php?lookup={$shouts[id].shout_name}'>{$shouts[id].user_name}</a>
					{else}
						{$shouts[id].shout_name}
					{/if}
				</b>
				{$locale.041}{$shouts[id].shout_datestamp|date_format:"longdate"}
			</td>
		</tr>
		<tr>
			<td class='tbl1'>
				{$shouts[id].shout_message} 
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				<div style='float:right;'>
					<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;shout_id={$shouts[id].shout_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.441}' title='{$locale.441}' /></a>&nbsp;
					<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;shout_id={$shouts[id].shout_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.442}' title='{$locale.442}' /></a>
				</div>
				<span class='small'>
					<b>{$locale.443}{$shouts[id].shout_ip}</b>
				</span>
			</td>
		</tr>
		{if !$smarty.section.id.last}
		<tr>
			<td class='tbl1' height='5'>
			</td>
		</tr>
		{/if}
	{if $smarty.section.id.last}
	</table>
	{/if}
{sectionelse}
{/section}
{include file="_closetable.tpl"}
{if $rows > $items_per_page}
	{makepagenav start=$rowstart count=$items_per_page total=$rows range=$settings.navbar_range link=$pagenavurl}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
