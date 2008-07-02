{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.reports.tpl                                    *}
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
{* Template for the admin configuration module 'reports'                   *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
	<tr>
		<td class='tbl2'>
			<b>{$locale.401}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.402}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.403}</b>
		</td>
	</tr>
	{section name=id loop=$reports}
	<tr>
		<td class='tbl1'>
			{$reports[id].report_title}
			{if $reports[id].mod_folder != ""}
				&nbsp;({$locale.410} {$reports[id].mod_folder})
			{/if}
		</td>
		<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
			{$reports[id].groupname}
		</td>
		<td align='left' width='1%' class='tbl1' style='white-space:nowrap'>
			{if $reports[id].report_active}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=setstatus&amp;status=0&amp;report_id="|cat:$reports[id].report_id image=page_red.gif alt=$locale.404 title=$locale.404}
			{else}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=setstatus&amp;status=1&amp;report_id="|cat:$reports[id].report_id image=page_green.gif alt=$locale.405 title=$locale.405}
			{/if}
			{if $reports[id].report_mod_id == 0}
				&nbsp;
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=edit&amp;report_id="|cat:$reports[id].report_id image="page_edit.gif" alt=$locale.406 title=$locale.406}
				&nbsp;
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=delete&amp;report_id="|cat:$reports[id].report_id image="page_delete.gif" alt=$locale.407 title=$locale.407}
			{/if}
			&nbsp;
			{imagelink link=$smarty.const.BASEDIR|cat:"reports.php"|cat:$aidlink|cat:"&amp;report_id="|cat:$reports[id].report_id image="image_view.gif" alt=$locale.411 title=$locale.411}
		</td>
	</tr>
	{sectionelse}
	<tr>
		<td align='center' colspan='3' class='tbl1'>
			{$locale.408}
			<br /><br />
		</td>
	</tr>
	{/section}
	<tr>
		<td align='center' colspan='3' class='tbl1'>
			{buttonlink name=$locale.409 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=add"}
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
