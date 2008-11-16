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
{* Template for the admin configuration module 'panels'                    *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{if $settings.panels_localisation == "multiple"}
	{assign var="link_locale" value="&amp;panel_locale="|cat:$panel_locale}
	<br />
	<div style='text-align:center;'>
		{$locale.488} {html_options name=panel_locale options=$locales selected=$panel_locale class="textbox" onchange="location = '"|cat:$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;panel_locale=' + this.options[this.selectedIndex].value;"}
	</div>
{else}
	{assign var="link_locale" value=""}
{/if}
<br />
{section name=id loop=$panels}
	{if $smarty.section.id.first}
		<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	{/if}
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
			{if $panels[id].panel_side == 0}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=mfooter&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;order="|cat:$panels[id].panel_order|cat:$link_locale image="down.gif" alt="$locale.444 title=$locale.494}
			{elseif $panels[id].panel_side == 1}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=mright&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;order="|cat:$panels[id].panel_order|cat:$link_locale image="right.gif" alt="$locale.442 title=$locale.431}
			{elseif $panels[id].panel_side == 2}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=mlower&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;order="|cat:$panels[id].panel_order|cat:$link_locale image="down.gif" alt="$locale.444 title=$locale.446}
			{elseif $panels[id].panel_side == 3}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=mupper&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;order="|cat:$panels[id].panel_order|cat:$link_locale image="up.gif" alt="$locale.443 title=$locale.445}
			{elseif $panels[id].panel_side == 4}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=mleft&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;order="|cat:$panels[id].panel_order|cat:$link_locale image="left.gif" alt="$locale.441 title=$locale.430}
			{elseif $panels[id].panel_side == 5}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=mheader&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;order="|cat:$panels[id].panel_order|cat:$link_locale image="up.gif" alt="$locale.443 title=$locale.495}
			{/if}
		</td>
		<td align='right' width='1%' class='tbl1' style='white-space:nowrap'>
			{$panels[id].panel_order}
		</td>
		<td width='1%' class='tbl1' style='white-space:nowrap'>
			{if $panels[id].order_up != 0}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=mup&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;panel_side="|cat:$panels[id].panel_side|cat:"&amp;order="|cat:$panels[id].order_up|cat:$link_locale image="up.gif" alt="$locale.443 title=$locale.432}
			{/if}
			{if $panels[id].order_down != 0}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=mdown&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;panel_side="|cat:$panels[id].panel_side|cat:"&amp;order="|cat:$panels[id].order_down|cat:$link_locale image="down.gif" alt="$locale.444 title=$locale.433}
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
			{imagelink link="panel_editor.php"|cat:$aidlink|cat:"&amp;step=edit&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;panel_side=1"|cat:$link_locale image="page_edit.gif" alt=$locale.434 title=$locale.434} &nbsp;
			{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=delete&amp;panel_id="|cat:$panels[id].panel_id|cat:"&amp;panel_side="|cat:$panels[id].panel_side|cat:$link_locale onclick="return DeleteItem()" image="page_delete.gif" alt=$locale.437 title=$locale.437} &nbsp;
			{if $panels[id].panel_status == 0}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=setstatus&amp;status=1&amp;panel_id="|cat:$panels[id].panel_id|cat:$link_locale image=page_green.gif alt=$locale.435 title=$locale.435}
			{else}
				{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=setstatus&amp;status=0&amp;panel_id="|cat:$panels[id].panel_id|cat:$link_locale image=page_red.gif alt=$locale.436 title=$locale.436}
			{/if}
		</td>
	</tr>
	{if $smarty.section.id.last}
		</table>
		<div style='text-align:center;'>
			<br />
			{buttonlink name=$locale.438 link="panel_editor.php"|cat:$aidlink|cat:$link_locale}
			{buttonlink name=$locale.439 link="panels.php"|cat:$aidlink|cat:"&amp;step=refresh"|cat:$link_locale}
		</div>
	{/if}
{sectionelse}
	<div style='text-align:center;'>
		<br />
		{buttonlink name=$locale.438 link="panel_editor.php"|cat:$aidlink|cat:$link_locale}
	</div>
{/section}
{include file="_closetable.tpl"}
<script type='text/javascript'>
	function DeleteItem() {ldelim}
		return confirm('{$locale.440}');
	{rdelim}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
