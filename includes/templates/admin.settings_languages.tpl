{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.settings.main.tpl                              *}
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
{* Template for the admin configuration module 'settings_main'             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='90%'>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.414}
			</td>
			<td width='50%' class='tbl'>
				<select name='localeset' class='textbox'>
					{foreach from=$locales item=file}
					<option value='{$file}'{if $settings2.locale == $file} selected="selected"{/if}>{$file}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.558}
			</td>
			<td width='50%' class='tbl'>
				<select name='localisation_method' class='textbox'>
					<option value='none'{if $settings2.localisation_method == "none"} selected="selected"{/if}>{$locale.559}</option>
{*					<option value='single'{if $settings2.localisation_method == "single"} selected="selected"{/if}>{$locale.560}</option> *}
					<option value='multiple'{if $settings2.localisation_method == "multiple"} selected="selected"{/if}>{$locale.561}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.404}
			</td>
			<td width='50%' class='tbl'>
				{html_options name="country" options=$countries selected=$settings2.country|default:"--" class="textbox"}
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='hidden' name='old_localeset' value='{$settings2.locale}' />
				<input type='hidden' name='old_country' value='{$settings2.country|default:""}' />
				<input type='submit' name='savesettings' value='{$locale.750}' class='button' />
			</td>
		</tr>
	</table>
</form>	
{include file="_closetable.tpl"}
<script type='text/javascript'>
{literal}
{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}