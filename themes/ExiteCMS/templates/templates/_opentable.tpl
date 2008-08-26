{***************************************************************************}
{*                                                                         *}
{* ExiteCMS include template: _opentable.tpl                               *}
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
{* This template generates the standard opening of a center panel          *}
{*                                                                         *}
{* Include parameters:                                                     *}
{*    title = panel title                                                  *}
{*    style = css tag name to use for the panel body. Default = 'main-body'*}
{*                                                                         *}
{***************************************************************************}
<table cellpadding='0' cellspacing='0' width='100%' class='main-panel'>
	<tr align='center'>
		<td>
			<table cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td class='cap-main'>
						<div style='display:inline; position:relative; float:right;margin-top:2px;'>
							<img src='{$smarty.const.THEME}images/panel_{if $_state == 1}on{else}off{/if}.gif' name='b_{$_name}' alt='' onclick="javascript:flipBox('{$_name}')" />
						</div>
						{$title|default:"&nbsp;"}
					</td>
				</tr>
				<tr>
					<td class='{$style|default:"main-body"}'>
						<div id='box_{$_name}' {if $_state == 0}{else}style='display:none'{/if}>