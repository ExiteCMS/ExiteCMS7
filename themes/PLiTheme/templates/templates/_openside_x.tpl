{***************************************************************************}
{*                                                                         *}
{* ExiteCMS include template: _openside_x.tpl                              *}
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
{* This template generates the opening of a side panel with a close button *}
{*                                                                         *}
{* Include parameters:                                                     *}
{*    title = panel title                                                  *}
{*    state = state of the panel (on/off), default = 'on'                  *}
{*    style = css tag name to use for the panel body, default = 'side-body'*}
{*                                                                         *}
{***************************************************************************}
<table width='100%' cellpadding='0' cellspacing='0'>
	<tr>
		<td class='cap-left'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='21' alt='' /></td>
		<td width='100%' class='cap-main'>{$title|escape|default:"&nbsp;"}</td>
		<td align='right' class='cap-main'><img src='{$smarty.const.THEME}images/panel_{if $_state == 1}on{else}off{/if}.gif' alt='{if $_state == 0}on{else}off{/if}' name='b_{$_name}' onclick="javascript:flipBox('{$_name}')" /></td>
		<td class='cap-right'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='21' alt='' /></td>
	</tr>
</table>
	<table width='100%' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='border-left'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='1' alt='' /></td>
			<td class='{$style|default:"side-body"}'>
				<div id='box_{$_name}' {if $_state == 0}{else}style='display:none'{/if}>
	