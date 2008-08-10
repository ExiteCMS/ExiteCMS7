{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.reports.tpl                                     *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-07-01 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'reports'                                  *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.rpt400 state=$_state style=$_style}
{if $action == ""}
		{section name=id loop=$reports}
		<form name='form{$reports[id].report_id}' method='post' action='{$smarty.const.FUSION_SELF|cat:"?action=report&amp;report_id="|cat:$reports[id].report_id}'
			<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
				<tr>
					<td class='tbl2'>
						{$locale.rpt401}: <b>{$reports[id].report_title}</b>
					</td>
					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
						<input type='submit' class='button' name='view' value='{$locale.rpt402}' />
					</td>
				</tr>
				<tr>
					<td class='tbl1' colspan='2'>
						{include file=$reports[id].template}
					</td>
				</tr>
			</table>
		</form>
		{if !$smarty.section.id.last}
			<br />
		{/if}
		{sectionelse}
		<div style='text-align:center;'>
				<br />
				{$locale.rpt403}
				<br /><br />
		</div>
		{/section}
{else}
	<table width='100%'>
		<tr>
			<td align='left'>
				{$locale.rpt404}: <b>{$report_title}</b>
			</td>
			<td align='right'>
				<form name='return' method='post' action='{$smarty.const.FUSION_SELF}'>
					<input type='submit' class='button' name='view' value='{$locale.rpt405}' />
				</form>
			</td>
		</tr>
		{if $message|default:"" != ""}
		<tr>
			<td align='left'>
				<b>{$message}</b>
			</td>
		</tr>
		{/if}
	</table>
	{if $message|default:"" == ""}
		{include file=$reports.0.template}
	{/if}
{/if}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
