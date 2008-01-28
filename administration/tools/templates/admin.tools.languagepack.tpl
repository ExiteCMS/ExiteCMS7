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
	<div align='center'>
		<br />
		<b>{$message}</b>
		<br /><br />
	</div>
	{include file="_closetable.tpl"}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.300 state=$_state style=$_style}
	<form name='languagepacks' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step={$step}'>
		<table align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl' align='center'>
					<br />
					<b>{ssprintf format=$locale.301 var1=$smarty.const.LP_LANGUAGE var2=$smarty.const.LP_LOCALE var3=$smarty.const.LP_VERSION var4=$smarty.const.LP_DATE|date_format:"%Y%m%d-%$"}</b>
				</td>
			</tr>
			{if $smarty.const.LP_VERSION != $settings.version}
			<tr>
				<td class='tbl' align='center'>
					<br />
					<b>{$locale.309} {$smarty.const.LP_VERSION}</b>
				</td>
			</tr>
			{else}
				<tr>
					<td class='tbl' align='center'>
						<br />
						{if $can_install}
							{buttonlink name=$locale.302 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=install"}&nbsp;
						{/if}
						{if $can_upgrade}
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