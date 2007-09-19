{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.settings_registration.tpl                *}
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
{* Template for the admin configuration module 'settings_registration'     *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.551}
			</td>
			<td width='50%' class='tbl'>
				<select name='enable_registration' class='textbox'>
					<option value='1'{if $settings2.enable_registration == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.enable_registration == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.552}
			</td>
			<td width='50%' class='tbl'>
				<select name='email_verification' class='textbox'>
					<option value='1'{if $settings2.email_verification == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.email_verification == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.557}
			</td>
			<td width='50%' class='tbl'>
				<select name='admin_activation' class='textbox'>
					<option value='1'{if $settings2.admin_activation == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.admin_activation == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.553}
			</td>
			<td width='50%' class='tbl'>
				<select name='display_validation' class='textbox'>
					<option value='1'{if $settings2.display_validation == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.display_validation == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.554}
			</td>
			<td width='50%' class='tbl'>
				<select name='validation_method' class='textbox'>
					<option value='image'{if $settings2.validation_method == "image"} selected{/if}>{$locale.555}</option>
					<option value='text'{if $settings2.validation_method == "text"} selected{/if}>{$locale.556}</option>
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
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}