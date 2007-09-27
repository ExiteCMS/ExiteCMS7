{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.lostpassword.tpl                                *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-07 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'lostpassword'                             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<form name='passwordform' method='post' action='{$smarty.const.FUSION_SELF}'>
	<center>
		{$locale.407}
		<br /><br />
		<input type='text' name='email' class='textbox' maxlength='100' style='width:200px;' />
		<br /><br />
		<input type='submit' name='send_password' value='{$locale.408}' class='button' />
	</center>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}