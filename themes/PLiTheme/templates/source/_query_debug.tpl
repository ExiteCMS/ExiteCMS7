{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: _query_debug.tpl                               *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-08-03 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template is used to dump the recorded list of executed DB queries  *}
{*                                                                         *}
{***************************************************************************}
<br />
{include file="_opentable.tpl" name=$_name title="DEBUG PANEL: List of logged database queries" state=$_state style=$_style}
<table width='100%' cellspacing='1' cellpadding='0' class='tbl-border'>
	{foreach name=id from=$queries item=query}
	<tr>
		<td width='100' class='tbl1'>
			{$smarty.foreach.id.iteration}
		</td>
		<td class='tbl1'>
			{$query}
		</td>
	</tr>
	{/foreach}
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}