{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.adverts.overview.tpl                           *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-06 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'advertising'. This template      *}
{* generates a panel with all advertising of one single client.            *}
{*                                                                         *}
{***************************************************************************}
{literal}<script type='text/javascript'>
<!--
function confdel(url) {
	if (confirm('{/literal}{$locale.905}{literal}')) location.href = url;
}
// -->
</script>{/literal}
{include file="_opentable.tpl" name=$_name title=$locale.404|cat:" : <b>"|cat:$data.user_name|cat:"</b>" state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='1' width='90%' class='tbl-border'>
	<tr>
		<td colspan='7' align='center' class='tbl2'><b>{$locale.402}</b>
		</td>
	</tr>
{section name=ad loop=$ads1}
{if $smarty.section.ad.first}
	<tr>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.460}</b></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.462}</b></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.463}</b></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.479}</b></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.464}</b></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.465}</b></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.466}</b></td>
	</tr>
{/if}
	<tr>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].adverts_id}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].advert_type}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].contract_type}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].adverts_shown}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].adverts_clicks}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].percentage}%</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>
			<a href='adverts.php{$aidlink}&amp;action=edit&amp;adverts_id={$ads1[ad].adverts_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.469}' title='{$locale.469}' /></a>&nbsp;	
			<a href='javascript:confdel("adverts.php{$aidlink}&amp;action=delete&amp;adverts_id={$ads1[ad].adverts_id}");'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.470}' title='{$locale.470}' /></a>&nbsp;	
			{if $ads1[ad].adverts_status == 0}
				<a href='adverts.php{$aidlink}&amp;action=enable&amp;adverts_id={$ads1[ad].adverts_id}'><img src='{$smarty.const.THEME}images/page_green.gif' alt='{$locale.467}' title='{$locale.467}' /></a>
			{else}
				<a href='adverts.php{$aidlink}&amp;action=enable&amp;adverts_id={$ads1[ad].adverts_id}'><img src='{$smarty.const.THEME}images/page_red.gif' alt='{$locale.468}' title='{$locale.468}' /></a>
			{/if}
		</td>
	</tr>
{sectionelse}
	<tr>
		<td colspan='7' align='center' class='tbl1'>
			<b>{$locale.908}</b>
		</td>
	</tr>
{/section}
	<tr>
		<td colspan='7' align='center' class='tbl2'><b>{$locale.403}</b>
		</td>
	</tr>
{section name=ad loop=$ads2}
	<tr>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].adverts_id}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].advert_type}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].contract_type}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].adverts_shown}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].adverts_clicks}</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].percentage}%</td>
		<td align='center' class='tbl1' style='white-space:nowrap'>
			<a href='adverts.php{$aidlink}&amp;action=edit&amp;adverts_id={$ads2[ad].adverts_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.469}' title='{$locale.469}' /></a>&nbsp;	
			<a href='javascript:confdel("adverts.php{$aidlink}&amp;action=delete&amp;adverts_id={$ads2[ad].adverts_id}");'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.470}' title='{$locale.470}' /></a>&nbsp;	
			{if $ads2[ad].adverts_status == 0}
				<a href='adverts.php{$aidlink}&amp;action=enable&amp;adverts_id={$ads2[ad].adverts_id}'><img src='{$smarty.const.THEME}images/page_green.gif' alt='{$locale.467}' title='{$locale.467}' /></a>
			{else}
				<a href='adverts.php{$aidlink}&amp;action=enable&amp;adverts_id={$ads2[ad].adverts_id}'><img src='{$smarty.const.THEME}images/page_red.gif' alt='{$locale.468}' title='{$locale.468}' /></a>
			{/if}
		</td>
	</tr>
{sectionelse}
	<tr>
		<td colspan='7' align='center' class='tbl1'>
			<b>{$locale.911}</b>
		</td>
	</tr>
{/section}
</table>
<div align='center'>
	<form name='sf_{$data.user_name}' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;id={$data.user_id}'>
		<br />
		<input type='submit' name='addad' value='{$locale.400}' class='button' />&nbsp;
		<input type='submit' name='delclient' value='{$locale.476}' class='button' />
	</form>
</div>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}