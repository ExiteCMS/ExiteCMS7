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
{* Template for the admin configuration module 'settings_security'         *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{include file="admin.settings_links.tpl}
{if $errormessage|default:"" != ""}
	<div style='text-align:center;font-weight:bold;color:red;'>
		<br />
		{$errormessage}
		<br /><br />
	</div>
{/if} 
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.551}
			</td>
			<td width='50%' class='tbl'>
				<select name='enable_registration' class='textbox'>
					<option value='1'{if $settings2.enable_registration == "1"} selected="selected"{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.enable_registration == "0"} selected="selected"{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.552}
			</td>
			<td width='50%' class='tbl'>
				<select name='email_verification' class='textbox'>
					<option value='1'{if $settings2.email_verification == "1"} selected="selected"{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.email_verification == "0"} selected="selected"{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.557}
			</td>
			<td width='50%' class='tbl'>
				<select name='admin_activation' class='textbox'>
					<option value='1'{if $settings2.admin_activation == "1"} selected="selected"{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.admin_activation == "0"} selected="selected"{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.553}
			</td>
			<td width='50%' class='tbl'>
				<select name='display_validation' class='textbox'>
					<option value='1'{if $settings2.display_validation == "1"} selected="selected"{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.display_validation == "0"} selected="selected"{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.554}
			</td>
			<td width='50%' class='tbl'>
				<select name='validation_method' class='textbox'>
					<option value='image'{if $settings2.validation_method == "image"} selected="selected"{/if}>{$locale.555}</option>
					<option value='text'{if $settings2.validation_method == "text"} selected="selected"{/if}>{$locale.556}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<hr />
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.575}
			</td>
			<td width='50%' class='tbl'>
				<select name='auth_ssl' class='textbox'>
					<option value='0'{if $settings2.auth_ssl == "0"} selected="selected"{/if}>{$locale.509}</option>
					<option value='1'{if $settings2.auth_ssl == "1"} selected="selected"{/if}>{$locale.508}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.576}
			</td>
			<td width='50%' class='tbl'>
				<select name='auth_required' class='textbox'>
					<option value='0'{if $settings2.auth_required == "0"} selected="selected"{/if}>{$locale.509}</option>
					<option value='1'{if $settings2.auth_required == "1"} selected="selected"{/if}>{$locale.508}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.529}
			</td>
			<td width='50%' class='tbl'>
				<select name='login_expire' class='textbox'>
				{section name=min start=0 loop=721 step=15}
				<option value='{$smarty.section.min.index}' {if $smarty.section.min.index == $login_expire|default:0}selected='selected'{/if}>{if $smarty.section.min.index == 0}{$locale.714}{else}{$smarty.section.min.index} {$locale.531}{/if}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.530}
			</td>
			<td width='50%' class='tbl'>
				<select name='login_extended_expire' class='textbox'>
				{section name=days start=0 loop=1441 step=1}
				<option value='{$smarty.section.days.index}' {if $smarty.section.days.index == $login_extended_expire|default:0}selected='selected'{/if}>{if $smarty.section.days.index == 0}{$locale.714}{elseif $smarty.section.days.index == 1}1 {$locale.527}{else}{$smarty.section.days.index} {$locale.518}{/if}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.528}
			</td>
			<td width='50%' class='tbl'>
				<select name='session_timeout' class='textbox'>
				{section name=hours start=0 loop=24}
				{assign var=days value=`$smarty.section.hours.index/24`}
				<option value='{$days}' {if $days == $session_timeout|default:0}selected='selected'{/if}>{if $days == 0}{$locale.714}{else}{$smarty.section.hours.index} Hours{/if}</option>
				{/section}
				{section name=days start=1 loop=91}
				<option value='{$smarty.section.days.index}' {if $smarty.section.days.index == $session_timeout|default:0}selected='selected'{/if}>{if $smarty.section.days.index == 1}1 {$locale.527}{else}{$smarty.section.days.index} {$locale.518}{/if}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='savesettings' value='{$locale.750}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$locale.577 state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='1' width='80%' class='tbl-border'>
	<tr>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.578}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.579}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.580}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.581}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.582}</b>
		</td>
	</tr>
	<br />
	{section name=id loop=$methods}
		<tr>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{$methods[id].name}
			</td>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{$methods[id].class}
			</td>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{if $methods[id].status == 1}
					{if !$smarty.section.id.first}
						{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=up&amp;method_id="|cat:$smarty.section.id.index image="up.gif" alt=$locale.583 title=$locale.583}
					{/if}
					{if !$methods[id].last}
						{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=down&amp;method_id="|cat:$smarty.section.id.index image="down.gif" alt=$locale.584 title=$locale.584}
					{/if}
				{/if}
			</td>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{if $methods[id].status == 1}
					{$locale.586}
				{else}
					{$locale.588}
				{/if}
			</td>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{if $methods[id].status == 1}
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=setstatus&amp;status=0&amp;authmethod="|cat:$methods[id].name image="page_red.gif" alt=$locale.587 title=$locale.587}
				{else}
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=setstatus&amp;status=1&amp;authmethod="|cat:$methods[id].name image="page_green.gif" alt=$locale.585 title=$locale.585}
				{/if}
			</td>
		</tr>
	{/section}
</table>
<br />
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
