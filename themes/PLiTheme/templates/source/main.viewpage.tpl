{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: main.viewpage.tpl                              *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-09 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'viewpage', to display a custom page       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<table width='100%' cellpadding='0' cellspacing='0'>
	<tr>
		<td>
			{if !$content}<br /><br /><center><b>{/if}
			{$custompage}
			{if !$content}</b></center><br /><br />{/if}
		</td>
	</tr>
</table>

{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}