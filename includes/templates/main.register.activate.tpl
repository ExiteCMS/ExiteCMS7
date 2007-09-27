{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.register.activate.tpl                           *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-06 - WW - Initial version                                       *}
{*                                                                         *}
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