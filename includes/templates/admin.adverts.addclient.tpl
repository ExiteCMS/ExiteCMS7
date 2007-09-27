{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.adverts.addclient.tpl                          *}
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
{* generates a panel to add a new advertising client                       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='addsponsor' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=add'>
	<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
		<td class='tbl'>{$locale.410}:</td>
		<td class='tbl'>
			<select class='textbox' name='new_sponsor' onkeydown='incrementalSelect(this,event)'>
			{html_options options=$users}
			</select>
		</td>
	</tr>
	<tr>
		<td align='center' colspan='2' class='tbl'>
			<br />
			<input type='submit' name='cancel' value='{$locale.441}' class='button'>&nbsp;
			<input type='submit' name='save' value='{$locale.440}' class='button'>
		</td>
	</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}