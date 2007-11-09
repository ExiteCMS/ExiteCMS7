{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.messages.options.tpl                            *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-15 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the options panel of the main module 'pm'.                 *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	<tr>
		<td width='24%' align='center' class='{if $folder == "inbox"}tbl1{else}tbl2{/if}'>
			<a href='{$smarty.const.FUSION_SELF}?folder=inbox'><b>{$locale.402} [{$totals.inbox} {$locale.445} {$global_options.pm_inbox}]</b></a>
		</td>
		<td width='25%' align='center' class='{if $folder == "outbox"}tbl1{else}tbl2{/if}'>
			<a href='{$smarty.const.FUSION_SELF}?folder=outbox'><b>{$locale.403} [{$totals.outbox} {$locale.445} {$global_options.pm_sentbox}]</b></a>
		</td>
		<td width='24%' align='center' class='{if $folder == "archive"}tbl1{else}tbl2{/if}'>
			<a href='{$smarty.const.FUSION_SELF}?folder=archive'><b>{$locale.404} [{$totals.archive} {$locale.445} {$global_options.pm_savebox}]</b></a>
		</td>
		<td width='13%' align='center' class='{if $folder == "options"}tbl1{else}tbl2{/if}'>
			<a href='{$smarty.const.FUSION_SELF}?folder=options'><b>{$locale.425}</b></a>
		</td>
	</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
	<tr>
		<td align='left' class='tbl1'>
			<br />
			{buttonlink name=$locale.401 link=$smarty.const.FUSION_SELF|cat:"?action=post&amp;msg_id=0"}
		</td>
	</tr>
</table>
<form name='options_form' method='post' action='{$smarty.const.FUSION_SELF}?folder=options'>
	<table align='center' cellpadding='0' cellspacing='1' class='tbl' width='500px'>
		<tr>
			<td align='right' class='tbl1' width='70%'>
				{$locale.621}
			</td>
			<td class='tbl1' width='30%'>
				<select name='pm_email_notify' class='textbox'>
					<option value='1'{if $user_options.pmconfig_email_notify == "1"} selected{/if}>{$locale.631}</option>
					<option value='0'{if $user_options.pmconfig_email_notify == "0"} selected{/if}>{$locale.632}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' class='tbl1' width='70%'>
				{$locale.634}
			</td>
			<td class='tbl1' width='30%'>
				<select name='pm_read_notify' class='textbox'>
					<option value='1'{if $user_options.pmconfig_read_notify == "1"} selected{/if}>{$locale.631}</option>
					<option value='0'{if $user_options.pmconfig_read_notify == "0"} selected{/if}>{$locale.632}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' class='tbl1' width='70%'>
				{$locale.403|string_format:$locale.622}
			</td>
			<td class='tbl1' width='30%'>
				<select name='pm_save_sent' class='textbox'>
					<option value='1'{if $user_options.pmconfig_save_sent == "1"} selected{/if}>{$locale.631}</option>
					<option value='0'{if $user_options.pmconfig_save_sent == "0"} selected{/if}>{$locale.632}</option>
				</select>
			</td>
		</tr>

{* FUTURE EXTENSION: AUTO ARCHIVE HASN'T BEEN ACTIVATED YET

		<tr>
			<td align='right' class='tbl1' width='70%'>
				{ssprintf format=$locale.635 var1=$locale.402 var2=$locale.403}
			</td>
			<td class='tbl1' width='30%'>
				<select disabled readonly name='pm_auto_archive' class='textbox'>
					{if $global_options.pmconfig_auto_archive == '0'}<option value='0' {if $user_options.pmconfig_auto_archive == "0"} selected{/if}>{$locale.636}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '30'}<option value='30' {if $user_options.pmconfig_auto_archive == "30"} selected{/if}>&nbsp; 30 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '60'}<option value='60' {if $user_options.pmconfig_auto_archive == "60"} selected{/if}>&nbsp; 60 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '90'}<option value='90' {if $user_options.pmconfig_auto_archive == "90"} selected{/if}>&nbsp; 90 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '120'}<option value='120' {if $user_options.pmconfig_auto_archive == "120"} selected{/if}>120 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '150'}<option value='150' {if $user_options.pmconfig_auto_archive == "150"} selected{/if}>150 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '180'}<option value='180' {if $user_options.pmconfig_auto_archive == "180"} selected{/if}>180 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '210'}<option value='210' {if $user_options.pmconfig_auto_archive == "210"} selected{/if}>210 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '240'}<option value='240' {if $user_options.pmconfig_auto_archive == "240"} selected{/if}>240 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '270'}<option value='270' {if $user_options.pmconfig_auto_archive == "270"} selected{/if}>270 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '300'}<option value='300' {if $user_options.pmconfig_auto_archive == "300"} selected{/if}>300 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive >= '330'}<option value='330' {if $user_options.pmconfig_auto_archive == "330"} selected{/if}>330 {$locale.637}</option>{/if}
					{if $global_options.pmconfig_auto_archive == '0' || $global_options.pmconfig_auto_archive == '360'}<option value='360' {if $user_options.pmconfig_auto_archive == "360"} selected{/if}>360 {$locale.637}</option>{/if}
				</select>
			</td>
		</tr>
*}
		<tr>
			<td align='right' class='tbl1' width='70%'>
				{$locale.638}
			</td>
			<td class='tbl1' width='30%'>
				<select name='pm_view' class='textbox'>
					<option value='0'{if $user_options.pmconfig_view == "0"} selected{/if}>{$locale.639}</option>
					<option value='1'{if $user_options.pmconfig_view == "1"} selected{/if}>{$locale.640}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl1'>
				<br />
				<input type='hidden' name='update_type' value='{$update_type}'>
				<input type='submit' name='save_options' value='{$locale.623}' class='button'>
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
