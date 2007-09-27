{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.adverts.delclient.tpl                          *}
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
{* Template for the admin content module 'advertising'. This template      *}
{* generates a panel asking for a delete confirmation                      *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='delclient' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delclientconf&amp;id={$id}'>
	<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
		<td class='tbl'>
			<div align='center'><b>{$question}</b></div>
		</td>
	</tr>
	<tr>
		<td align='center' class='tbl'>
			<br />
			<input type='submit' name='no' value='{$locale.422}' class='button' />&nbsp;
			<input type='submit' name='yes' value='{$locale.423}' class='button' />
		</td>
	</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}