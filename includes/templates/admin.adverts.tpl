{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.adverts.tpl                                    *}
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
{* Template for the admin content module 'advertising'                     *}
{*                                                                         *}
{***************************************************************************}
{if $errormessage|default:"" != ""}
	{include file="_message_table_panel.tpl" name=$_name title=$errortitle state=$_state style=$_style message=$errormessage}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.402 state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
	<tr>
		<td align='center' class='tbl1'>
		<form name='subfunctions' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
			<br />
			<input type='submit' name='add' value='{$locale.447}' class='button' />&nbsp;
			<input type='submit' name='images' value='{$locale.448}' class='button' />
			<br /><br />
		</form>
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}