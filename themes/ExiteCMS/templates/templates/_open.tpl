{***************************************************************************}
{*                                                                         *}
{* ExiteCMS include template: _open.tpl                                    *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-11-28 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates the standard opening of a header/footer panel   *}
{*                                                                         *}
{* Include parameters:                                                     *}
{*    title = panel title                                                  *}
{*    style = css tag name to use for the panel body. Default = 'main-body'*}
{*                                                                         *}
{***************************************************************************}
<table cellpadding='0' cellspacing='0' width='100%'>
	<tr>
		<td align='center'>
			<table cellpadding='0' cellspacing='0' width='{$smarty.const.THEME_WIDTH}' class='main-panel'>
				<tr align='center'>
					<td>
						<table cellpadding='0' cellspacing='0' width='100%'>
							<tr>
								<td class='cap-main'>{$title|default:"&nbsp;"}</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table width='100%' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='{$style|default:"main-body"}'>
