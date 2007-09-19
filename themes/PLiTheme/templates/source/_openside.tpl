{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS include template: _openside.tpl                          *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-01 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates the standard opening of a side panel            *}
{*                                                                         *}
{* Include parameters:                                                     *}
{*    title = panel title                                                  *}
{*    style = css tag name to use for the panel body. Default = 'side-body'*}
{*                                                                         *}
{***************************************************************************}
<table width='100%' cellpadding='0' cellspacing='0'>
	<tr>
		<td class='cap-left'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='21' alt='' /></td>
		<td width='100%' class='cap-main'>{$title|default:"&nbsp;"}</td>
		<td class='cap-right'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='21' alt='' /></td>
	</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
	<tr>
		<td class='border-left'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='1' alt='' /></td>
		<td class='{$style|default:"side-body"}'>
