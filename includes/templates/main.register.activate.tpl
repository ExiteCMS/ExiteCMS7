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
{* Registration activation panel.                                          *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.401 state=$_state style=$_style}
<center>
	<br />
	{$locale.455}
	<br /><br />
	{if $color|default:"" !=""}<font color='{$color}'>{/if}{if $bold|default:false}<b>{/if}{$message}{if $bold|default:false}</b>{/if}{if $color|default:"" !=""}</font>{/if}<br /><br />
	{if $link|default:"" !=""}
		<a href='{$link}' alt=''>{$linktext|default:"Click here"}</a>
		<br /><br />
	{/if}
</center>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
