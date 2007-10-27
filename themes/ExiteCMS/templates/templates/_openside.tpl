{***************************************************************************}
{*                                                                         *}
{* ExiteCMS include template: _openside.tpl                                *}
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
<table cellpadding='0' cellspacing='0' width='100%' class='side-panel'>
	<tr>
		<td style='padding:5px;'>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr>
					<td class='sub-cap-main'>{$title|default:"&nbsp;"}</td>
				</tr>
				<tr>
				<td class='{$style|default:"side-body"}'>

