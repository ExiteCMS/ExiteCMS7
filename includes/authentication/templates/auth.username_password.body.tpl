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
{* Template for the authentication body panels: username and password      *}
{*                                                                         *}
{***************************************************************************}
<div class='main-label'>
	<div style='display:inline; position:relative; float:right;margin-top:2px;'>
		<img src='{$smarty.const.THEME}images/panel_{if $method.state}off{else}on{/if}.gif' alt='' name='b_login_{$method.method}' onclick="javascript:flipBox('login_{$method.method}')" />
	</div>
	{$locale.060} {$locale.069} {$locale.061}:
</div>
<div id='box_login_{$method.method}' name='login_{$method.method}' style='display:{if $method.state}block{else}none{/if};'>
	{$locale.061}: <input type='text' name='user_name' class='textbox' style='width:140px' />
	&nbsp;&nbsp;&nbsp;
	{$locale.062}: <input type='password' name='user_pass' class='textbox' style='width:140px' /><br />
</div>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
