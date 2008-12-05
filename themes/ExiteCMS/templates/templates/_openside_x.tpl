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
{* This template generates the opening of a side panel with a close button *}
{*                                                                         *}
{* Include parameters:                                                     *}
{*    title = panel title                                                  *}
{*    state = state of the panel (on/off), default = 'on'                  *}
{*    style = css tag name to use for the panel body, default = 'side-body'*}
{*                                                                         *}
{***************************************************************************}
<table cellpadding='0' cellspacing='0' width='100%' class='side-panel'>
	<tr>
		<td style='padding:5px;'>
			<table cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td class='sub-cap-main'>
						<div style='display:inline; position:relative; float:right;margin-top:2px;'>
							<img src='{$smarty.const.THEME}images/panel_{if $_state == 1}on{else}off{/if}.gif' alt='' name='b_{$_name}' onclick="javascript:flipBox('{$_name}')" />
						</div>
						{$title|default:"&nbsp;"}
					</td>
				</tr>
				<tr>
					<td class='{$style|default:"side-body"}'>
						<div id='box_{$_name}' {if $_state == 0}{else}style='display:none'{/if}>
