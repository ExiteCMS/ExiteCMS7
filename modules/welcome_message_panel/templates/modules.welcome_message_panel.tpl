{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: _message_table_panel.tpl                       *}
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
{* This template is used to generate a simple table panel, with a message, *}
{* and optionally some formatting of the message (info, error, warning, .) *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.024 state=$_state style=$_style}
<center>
	<br />{if $color|default:"" !=""}<font color='{$color}'>{/if}{if $bold|default:false}<b>{/if}{$message}{if $bold|default:false}</b>{/if}{if $color|default:"" !=""}</font>{/if}<br /><br />
	{if $link|default:"" !=""}
		<a href='{$link}' alt=''>{$linktext|default:"Click here"}</a>
		<br /><br />
	{/if}
</center>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}