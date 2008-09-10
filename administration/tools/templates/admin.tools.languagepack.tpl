{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.tools.languagepack.tpl                         *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-11-10 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates the webmaster toolbox languagepack installation *}
{* and removal template.                                                   *}
{*                                                                         *}
{***************************************************************************}
{if $message|default:"" != ""}
	{include file="_opentable.tpl" name=$_name title=$locale.300 state=$_state style=$_style}
	<div align='center' style='font-weight:bold;'>
		<p>{$message}</p>
	</div>
	{include file="_closetable.tpl"}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.300 state=$_state style=$_style}
	<form name='languagepacks' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step={$step}'>
		<table align='center' cellpadding='1' cellspacing='1' width='100%'>
			<tr>
				<td class='tbl' width='50%' align='right'>
					{$locale.301} :
				</td>
				<td class='tbl' align='left'>
					{$smarty.const.LP_VERSION}
				</td>
			</tr>
			<tr>
				<td class='tbl' width='50%' align='right'>
					{$locale.310} :
				</td>
				<td class='tbl' align='left'>
					{$smarty.const.LP_LANGUAGE} ({$smarty.const.LP_LOCALE})

				</td>
			</tr>
			<tr>
				<td class='tbl' width='50%' align='right'>
					{$locale.311} :
				</td>
				<td class='tbl' align='left'>
					{foreach from=$flags item=flag}
						<img src='{$smarty.const.IMAGES}flags/{$flag}.gif' alt='' />
					{/foreach}
				</td>
			</tr>
			<tr>
				<td class='tbl' width='50%' align='right'>
					{$locale.312} :
				</td>
				<td class='tbl' align='left'>
					{$smarty.const.LP_DATE|date_format:"%Y%m%d-%$"}
				</td>
			</tr>
			<tr>
				<td class='tbl' width='50%' align='right'>
					{$locale.313} :
				</td>
				<td class='tbl' align='left'>
					{$last_update|date_format:"%Y%m%d-%$"}
				</td>
			</tr>
			{if $smarty.const.LP_VERSION != $settings.version}
			<tr>
				<td colspan='2' class='tbl' align='center'>
					<div align='center' style='color:red;font-weight:bold;'>
						<p>{$locale.309} {$smarty.const.LP_VERSION}</p>
					</div>
				</td>
			</tr>
			{elseif $last_update > $smarty.const.LP_DATE}
			<tr>
				<td colspan='2' class='tbl' align='center'>
					<div align='center' style='color:red;font-weight:bold;'>
						<p>{$locale.314}</p>
					</div>
				</td>
			</tr>
			{else}
				<tr>
					<td colspan='2' class='tbl' align='center'>
						<br />
						{if $can_install}
							{buttonlink name=$locale.302 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=install"}&nbsp;
						{/if}
						{if $can_upgrade && $last_update != $smarty.const.LP_DATE}
						{buttonlink name=$locale.304 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=upgrade"}&nbsp;
						{/if}
						{if $can_remove && $smarty.const.LP_LOCALE != "en"}
						{buttonlink name=$locale.303 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=remove"}&nbsp;	
						{/if}
					</td>
				</tr>
			{/if}
		</table>
	</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
