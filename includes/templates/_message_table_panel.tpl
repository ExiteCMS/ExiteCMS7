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
{* This template is used to generate a simple table panel, with a message, *}
{* and optionally some formatting of the message (info, error, warning, .) *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<center>
	<br />
	{if $color|default:"" !=""}<font color='{$color}'>{/if}{if $bold|default:false}<b>{/if}{$message}{if $bold|default:false}</b>{/if}{if $color|default:"" !=""}</font>{/if}
	<br /><br />
	{if $link|default:"" !=""}
		{buttonlink name=$linktext link=$link}
		<br /><br />
	{/if}
</center>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
