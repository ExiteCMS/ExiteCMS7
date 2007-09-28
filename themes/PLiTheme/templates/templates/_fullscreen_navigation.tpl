{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: navigation_panel.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-02 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates the ExiteCMS fullscreen menu panel              *}
{*                                                                         *}
{***************************************************************************}
{section name=link loop=$linkinfo}
	{if $smarty.section.link.first}
		{include file="_openside.tpl" name=$_name title=$locale.001 state=$_state style=$_style}
	{/if}
	{if $linkinfo[link].link_url == "---"}
		<br />
		{if $linkinfo[link].link_name != "---"}
			{$linkinfo[link].link_name}</a>&nbsp;:
		{/if}
	{else}
		<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$linkinfo[link].link_url}' {if $linkinfo[link].link_window|default:0 == 1}target='_blank' {/if}class='side'>{$linkinfo[link].link_name}</a>&nbsp;&nbsp;
	{/if}
	{if $smarty.section.link.last}
		{include file="_closetable.tpl"}
	{/if}
{/section}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}