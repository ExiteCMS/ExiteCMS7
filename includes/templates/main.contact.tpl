{***************************************************************************}
{* ExiteCMS Content Management System                                      *}
{***************************************************************************}
{* Copyright 2006-2008 Exite BV, The Netherlands                           *}
{* for support, please visit http://www.exitecms.org                       *}
{*-------------------------------------------------------------------------*}
{* Released under the terms & conditions of v2 of the GNU General Public   *}
{* License. For details refer to the included gpl.txt file or visit        *}
{* http://gnu.org                                                          *}
{***************************************************************************}
{* $Id::                                                                  $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author::                                             $*}
{* Revision number $Rev::                                                 $*}
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
				{$locale.412} 
				{if $settings.validation_method == "text"}
					<span style='font-size:125%;font-weight:bold;'>{$validation_code|upper}</span>
				{else}
					<img id="captcha" src="{$smarty.const.INCLUDES}secureimage-1.0.3/securimage_show.php" alt="CAPTCHA Image" style='vertical-align:middle;'/>
				{/if}
				<br /><br />
				{$locale.413} <input type='text' name='captcha_code' class='textbox' style='width:100px' />
				{if $settings.validation_method == "image"}
					&nbsp;
					{buttonlink name=$locale.415 link="document.getElementById(\"captcha\").src=\""|cat:$smarty.const.INCLUDES|cat:"secureimage-1.0.3/securimage_show.php?\"+Math.random(); return false;" script="yes"}
				{/if}
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
