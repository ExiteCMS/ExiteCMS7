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
{* This template is used to generate the message panel for the main module *}
{* contact, which is displayed after the user has sent a message           *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400|cat:" "|cat:$target state=$_state style=$_style}
{if $error|default:0 == 0}
	<div style='text-align:center;font-weight:bold;'>
		<br />
		{$locale.440}
		<br /><br />
		{$locale.441}
		<br /><br />
	</div>
{else}
	<div style='text-align:center;font-weight:bold;'>
		<br />
		{$locale.442}
		<br /><br />
		{foreach from=$errors item=errmsg}
		    {$errmsg}<br />
		{/foreach}
		<br />
		{$locale.443}
		<br /><br />
	</div>
{/if}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
