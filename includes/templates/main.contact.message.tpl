{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.contact.message.tpl                             *}
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
{* This template is used to generate the message panel for the main module *}
{* contact, which is displayed after the user has sent a message           *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400|cat:" "|cat:$settings.siteusername state=$_state style=$_style}
{if $error|default:0 = 0}
	<center>
		<br />
		{$locale.440}
		<br /><br />
		{$locale.441}
	</center>
	<br />
{else}
	<center>
		<br />
		{$locale.442}
		<br /><br />
		<span class='small'>{$locale.423}</span>
		<br />
		{foreach from=$errors item=errmsg}
		    {$errmsg}<br />
		{/foreach}
		{$locale.443}
	</center>
	<br />
{/if}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}