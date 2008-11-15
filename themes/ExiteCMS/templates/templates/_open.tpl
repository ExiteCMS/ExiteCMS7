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
{* $Id:: _open.tpl 1935 2008-10-29 23:42:42Z WanWizard                    $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author:: WanWizard                                   $*}
{* Revision number $Rev:: 1935                                            $*}
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
