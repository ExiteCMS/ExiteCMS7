{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.contact.tpl                                     *}
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
{* Template for the main module 'contact'                                  *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
<table align='center' width='500'>
	<tr>
		<td>
			<center>
				{$locale.401}{mailto address=$target encode="javascript_charcode"}{$locale.401a}
			</center>
			<br />
			<form name='userform' method='post' action='{$smarty.const.FUSION_SELF}'>
				<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
					<tr>
						<td>
							{$locale.402}<br />
							<input type='text' name='mailname' value='{$mailname}' maxlength='50' class='textbox' style='width: 320px;' />
							<br /><br />
						</td>
					</tr>
					<tr>
						<td>
							{$locale.403}<br />
							<input type='text' name='email' value='{$email}' maxlength='100' class='textbox' style='width: 320px;' />
							<br /><br />
						</td>
					</tr>
					<tr>
						<td>
							{$locale.404}<br />
							<input type='text' name='subject' value='{$subject}' maxlength='50' class='textbox' style='width: 320px;' />
							<br /><br />
						</td>
					</tr>
					<tr>
						<td>
							{$locale.405}<br />
							<textarea name='message' rows='10' cols='80' class='textbox' style='width: 320px'>{$message}</textarea>
							<br /><br />
						</td>
					</tr>
				</table>
				<center>
				{if $cic != ""}
					<span style='color:#CC0000;font-weight:bold;'>{$locale.410}<br /><br /></span>
				{/if}
				{$locale.411}
				<br /><br />
				{$locale.412} {make_captcha}
				{$locale.413} <input type='text' name='captcha_code' class='textbox' style='vertical-align:top;width:100px' />
				<br /><br />
				<input type='submit' name='sendmessage' value='{$locale.406}' class='button' />
				</center>
			</form>
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}