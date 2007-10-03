{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.settings_messages.tpl                          *}
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
{* Template for the admin configuration module 'settings_messages'         *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='optionsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;folder=options'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td class='tbl2' align='center' colspan='2'>
				{$locale.707}
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.701}
				<br/>
				<span class='small2'>{$locale.704}</span>
			</td>
			<td class='tbl' width='50%'>
				<input type='text' name='pm_inbox' maxlength='4' class='textbox' style='width:40px;' value='{$pm_inbox}' />
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.702}
				<br />
				<span class='small2'>{$locale.704}</span>
			</td>
			<td class='tbl' width='50%'>
				<input type='text' name='pm_sentbox' maxlength='4' class='textbox' style='width:40px;' value='{$pm_sentbox}' />
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.703}
				<br />
				<span class='small2'>{$locale.704}</span>
			</td>
			<td class='tbl' width='50%'>
				<input type='text' name='pm_savebox' maxlength='4' class='textbox' style='width:40px;' value='{$pm_savebox}' />
			</td>
		</tr>
		<tr>
			<td class='tbl' align='center' colspan='2'>
				<br />
			</td>
		</tr>	
		<tr>
			<td class='tbl2' align='center' colspan='2'>
				{$locale.716}
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.721}
			</td>
			<td class='tbl' width='50%'>
				<select name='pm_hide_rcpts' class='textbox'>
					<option value='1' {if $pm_hide_rcpts == "1"} selected="selected"{/if}>{$locale.509}</option>
					<option value='0' {if $pm_hide_rcpts == "0"} selected="selected"{/if}>{$locale.508}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.715}
			</td>
			<td class='tbl' width='50%'>
				<select name='pm_read_notify' class='textbox'>
					<option value='0' {if $pm_read_notify == "0"} selected="selected"{/if}>{$locale.509}</option>
					<option value='1' {if $pm_read_notify == "1"} selected="selected"{/if}>{$locale.508}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.712}
			</td>
			<td class='tbl' width='50%'>
				<select name='pm_auto_archive' class='textbox'>
					<option value='0' {if $pm_auto_archive == "0"} selected="selected"{/if}>{$locale.714}</option>
					<option value='30' {if $pm_auto_archive == "30"} selected="selected"{/if}>&nbsp; 30 {$locale.713}</option>
					<option value='60' {if $pm_auto_archive == "60"} selected="selected"{/if}>&nbsp; 60 {$locale.713}</option>
					<option value='90' {if $pm_auto_archive == "90"} selected="selected"{/if}>&nbsp; 90 {$locale.713}</option>
					<option value='120' {if $pm_auto_archive == "120"} selected="selected"{/if}>120 {$locale.713}</option>
					<option value='150' {if $pm_auto_archive == "150"} selected="selected"{/if}>150 {$locale.713}</option>
					<option value='180' {if $pm_auto_archive == "180"} selected="selected"{/if}>180 {$locale.713}</option>
					<option value='210' {if $pm_auto_archive == "210"} selected="selected"{/if}>210 {$locale.713}</option>
					<option value='240' {if $pm_auto_archive == "240"} selected="selected"{/if}>240 {$locale.713}</option>
					<option value='270' {if $pm_auto_archive == "270"} selected="selected"{/if}>270 {$locale.713}</option>
					<option value='300' {if $pm_auto_archive == "300"} selected="selected"{/if}>300 {$locale.713}</option>
					<option value='330' {if $pm_auto_archive == "330"} selected="selected"{/if}>330 {$locale.713}</option>
					<option value='360' {if $pm_auto_archive == "360"} selected="selected"{/if}>360 {$locale.713}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.717}
			</td>
			<td class='tbl' width='50%'>
				<select name='pm_view' class='textbox'>
					<option value='0' {if $pm_view == "0"} selected="selected"{/if}>{$locale.718}</option>
					<option value='1' {if $pm_view == "1"} selected="selected"{/if}>{$locale.719}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.720}
			</td>
			<td class='tbl' width='50%'>
				<select name='pm_send2group' class='textbox'>
				{section name=id loop=$usergroups}
					<option value='{$usergroups[id].0}'{if $usergroups[id].0 == $pm_send2group} selected="selected"{/if}>{$usergroups[id].1}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' align='center' colspan='2'>
				<br />
			</td>
		</tr>	
		<tr>
			<td class='tbl2' align='center' colspan='2'>
				{$locale.708}
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.709}
			</td>
			<td class='tbl' width='50%'>
				<select name='pm_email_notify' class='textbox'>
					<option value='0' {if $pm_email_notify == "0"} selected="selected"{/if}>{$locale.509}</option>
					<option value='1' {if $pm_email_notify == "1"} selected="selected"{/if}>{$locale.508}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.710}
			</td>
			<td class='tbl' width='50%'>
				<select name='pm_save_sent' class='textbox'>
					<option value='0' {if $pm_save_sent == "0"} selected="selected"{/if}>{$locale.509}</option>
					<option value='1' {if $pm_save_sent == "1"} selected="selected"{/if}>{$locale.508}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<span class='small2'>{$locale.711}</span>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='saveoptions' value='{$locale.750}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}