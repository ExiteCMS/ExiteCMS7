{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.panels.tpl                                     *}
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
{* Template for the admin configuration module 'panels'                    *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<br />
<table align='center' cellpadding='0' cellspacing='1' width='650' class='tbl-border'>
	{section name=id loop=$panels}
	{if $panels[id].new_panel_side}
	{if !$smarty.section.id.first}
	<tr>
		<td align='center' colspan='8' class='tbl1' height='10'></td>
	</tr>
	{/if}
	<tr>
		<td class='tbl2'>
			<b>{$panels[id].panel_side_name}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.402}</b>
		</td>
		<td align='center' width='1%' class='tbl2' colspan='2' style='white-space:nowrap'>
			<b>{$locale.403}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.407}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.404}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.405}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.406}</b>
		</td>
	</tr>
	{/if}
	<tr>
		<td class='tbl1'>
			{$panels[id].panel_name}
		</td>
		<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
			{if $panels[id].panel_side == 1}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=mright&amp;panel_id={$panels[id].panel_id}&amp;order={$panels[id].panel_order}'><img src='{$smarty.const.THEME}images/right.gif' alt='{$locale.442}' title='{$locale.431}' style='border:0px;' /></a>
			{elseif $panels[id].panel_side == 2}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=mlower&amp;panel_id={$panels[id].panel_id}&amp;order={$panels[id].panel_order}'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.444}' title='{$locale.446}' style='border:0px;' /></a>
			{elseif $panels[id].panel_side == 3}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=mupper&amp;panel_id={$panels[id].panel_id}&amp;order={$panels[id].panel_order}'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.443}' title='{$locale.445}' style='border:0px;' /></a>
			{elseif $panels[id].panel_side == 4}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=mleft&amp;panel_id={$panels[id].panel_id}&amp;order={$panels[id].panel_order}'><img src='{$smarty.const.THEME}images/left.gif' alt='{$locale.441}' title='{$locale.430}' style='border:0px;' /></a>
			{/if}
		</td>
		<td align='right' width='1%' class='tbl1' style='white-space:nowrap'>
			{$panels[id].panel_order}
		</td>
		<td width='1%' class='tbl1' style='white-space:nowrap'>
			{if $panels[id].order_up != 0}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=mup&amp;panel_id={$panels[id].panel_id}&amp;panel_side={$panels[id].panel_side}&amp;order={$panels[id].order_up}'>
					<img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.443}' title='{$locale.432}' style='border:0px;' />
				</a>
			{/if}
			{if $panels[id].order_down != 0}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=mdown&amp;panel_id={$panels[id].panel_id}&amp;panel_side={$panels[id].panel_side}&amp;order={$panels[id].order_down}'>
					<img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.444}' title='{$locale.433}' style='border:0px;' />
				</a>
			{/if}
		</td>
		<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
			{$panels[id].panel_state_name}
		</td>
		<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
			{$panels[id].panel_type_name}
		</td>
		<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
			{$panels[id].panel_access_name}
		</td>
		<td align='left' width='1%' class='tbl1' style='white-space:nowrap'>
			<a href='panel_editor.php{$aidlink}&amp;step=edit&amp;panel_id={$panels[id].panel_id}&amp;panel_side=1'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.434}' title='{$locale.434}' style='border:0px;' /></a> &nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;panel_id={$panels[id].panel_id}&amp;panel_side={$panels[id].panel_side}' onclick='return DeleteItem()'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.437}' title='{$locale.437}' style='border:0px;' /></a> &nbsp;
			{if $panels[id].panel_status == 0}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=setstatus&amp;status=1&amp;panel_id={$panels[id].panel_id}'><img src='{$smarty.const.THEME}images/page_green.gif' alt='{$locale.435}' title='{$locale.435}' style='border:0px;' /></a>
			{else}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=setstatus&amp;status=0&amp;panel_id={$panels[id].panel_id}'><img src='{$smarty.const.THEME}images/page_red.gif' alt='{$locale.436}' title='{$locale.436}' style='border:0px;' /></a>
			{/if}
		</td>
	</tr>	
	{/section}
</table>
<br />
<center>
	{buttonlink name=$locale.438 link="panel_editor.php"|cat:$aidlink}
	{buttonlink name=$locale.439 link="panels.php"|cat:$aidlink|cat:"&amp;step=refresh"}
</center>
<br />
{include file="_closetable.tpl"}
<script type='text/javascript'>
	function DeleteItem() {ldelim}
		return confirm('{$locale.440}');
	{rdelim}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}