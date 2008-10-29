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
{* This template generates the PLi-Fusion panel: webshop                   *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<table align='left' cellpadding='0' cellspacing='1' width='{$smarty.const.IFRAME_W}' class='tbl-border'>
	<tr>
		<td align='center' colspan='2' class='tbl2' style='white-space:nowrap;'>
			<b>{$locale.410}</b>
		</td>
	</tr>
	<tr>
	{foreach from=$shoplist item=shopinfo name=shop}
	{if $smarty.foreach.shopinfo.first}{math equation='(100-100%x)/x' x=$smarty.foreach.shop.total assign=colwidth}{/if}
		<td align='center' class='tbl1' width='{$colwidth}%' style='white-space:nowrap;'>
			{$shopinfo.flag}
			{if $shop == $shopinfo.cc}<b>{else}<a href='{$smarty.const.FUSION_SELF}?shop={$shopinfo.cc}'>{/if}
			{$shopinfo.name}
			{if $shop == $shopinfo.cc}</b>{else}</a>{/if}
		</td>
	{/foreach}
	</tr>
	<tr>
		<td align='left' colspan='2' class='tbl1'>{$locale.411}</td>
	</tr>
	<tr>
		<td align='center' colspan='2' class='tbl1'>
			<br />
			{foreach from=$shoplist item=shopinfo}
			{if $shop == $shopinfo.cc}
			<iframe height='{$smarty.const.IFRAME_H}' width='{$smarty.const.IFRAME_W}' src='{$shopinfo.link}' name='Spreadshop' id='Spreadshop' frameborder='0'></iframe>
			{/if}
			{/foreach}
		</td>
	</tr>
</table>
<br /><br />
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
