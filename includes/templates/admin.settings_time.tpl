{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.settings_time.tpl                              *}
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
{* Template for the admin configuration module 'settings_time'             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='tbl2'>
				{$locale.451}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='shortdatetext' class='textbox' style='width:201px;'>
				{foreach from=$options item=option}
				<option value='{$option}'>{$localtime|date_format:$option}</option>
				{/foreach}
			</select>
			&nbsp;<input type='button' name='setshortdate' value='>>' onclick="shortdate.value=shortdatetext.options[shortdatetext.selectedIndex].value;shortdatetext.selectedIndex=0;" class='button' />
			&nbsp;<input type='text' name='shortdate' value='{$settings2.shortdate}' maxlength='50' class='textbox' style='width:180px;' />
			&nbsp;<span class='alt'>( {$smarty.now|date_format:$settings2.shortdate} )</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				{$locale.452}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='longdatetext' class='textbox' style='width:201px;'>
				{foreach from=$options item=option}
				<option value='{$option}'>{$localtime|date_format:$option}</option>
				{/foreach}
			</select>
			&nbsp;<input type='button' name='setlongdate' value='>>' onclick="longdate.value=longdatetext.options[longdatetext.selectedIndex].value;longdatetext.selectedIndex=0;" class='button' />
			&nbsp;<input type='text' name='longdate' value='{$settings2.longdate}' maxlength='50' class='textbox' style='width:180px;' />
			&nbsp;<span class='alt'>( {$smarty.now|date_format:$settings2.longdate} )</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				{$locale.453}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='forumdatetext' class='textbox' style='width:201px;'>
				{foreach from=$options item=option}
				<option value='{$option}'>{$localtime|date_format:$option}</option>
				{/foreach}
			</select>
			&nbsp;<input type='button' name='setforumdate' value='>>' onclick="forumdate.value=forumdatetext.options[forumdatetext.selectedIndex].value;forumdatetext.selectedIndex=0;" class='button' />
			&nbsp;<input type='text' name='forumdate' value='{$settings2.forumdate}' maxlength='50' class='textbox' style='width:180px;' />
			&nbsp;<span class='alt'>( {$smarty.now|date_format:$settings2.forumdate} )</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				{$locale.454}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='subheaderdatetext' class='textbox' style='width:201px;'>
				{foreach from=$options item=option}
				<option value='{$option}'>{$localtime|date_format:$option}</option>
				{/foreach}
			</select>
			&nbsp;<input type='button' name='setsubheaderdate' value='>>' onclick="subheaderdate.value=subheaderdatetext.options[subheaderdatetext.selectedIndex].value;subheaderdatetext.selectedIndex=0;" class='button' />
			&nbsp;<input type='text' name='subheaderdate' value='{$settings2.subheaderdate}' maxlength='50' class='textbox' style='width:180px;' />
			&nbsp;<span class='alt'>( {$smarty.now|date_format:$settings2.subheaderdate} )</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				{$locale.456}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='timeoffset' class='textbox' style='width:75px;'>
				{foreach from=$settings.timezones item=timezone}
				<option{if $settings2.timeoffset == $timezone} selected="selected"{/if}>{$timezone}</option>
				{/foreach}
			</select>
			&nbsp;<span class='alt'>{$serverzone}</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<input type='submit' name='savesettings' value='{$locale.750}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}