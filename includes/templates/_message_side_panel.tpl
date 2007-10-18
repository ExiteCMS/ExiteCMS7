{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: _message_side_panel.tpl                              *}
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
{* This template is used to generate a simple side panel, with a message,  *}
{* and optionally some formatting of the message (info, error, warning, ..)*}
{*                                                                         *}
{***************************************************************************}
{include file="_openside.tpl" name=$_name title=$_title state=$_state style=$_style}
<center>
	<br />
	{if $color|default:"" !=""}<font color='{$color}'>{/if}{if $bold|default:false}<b>{/if}{$message}{if $bold|default:false}</b>{/if}{if $color|default:"" !=""}</font>{/if}
	<br />
	{if $link|default:"" !=""}
		{buttonlink name=$linktext link=$link}
		<br /><br />
	{/if}
</center>
{include file="_closeside.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}