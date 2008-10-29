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
