{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.register.tpl                                    *}
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
{* Template for the main module 'register'                                 *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<center>
	{$locale.500}
	{if $settings.email_verification == "1"}{$locale.501}{/if}
	{$locale.502}
	{if $settings.email_verification == "1"}{$locale.503}{/if}
</center>
<br />
<table align='center' cellpadding='0' cellspacing='0'>
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}' onSubmit='return ValidateForm(this)'>
		<tr>
			<td class='tbl'>
				{$locale.u001}
				<span style='color:#ff0000'>*</span>
			</td>
			<td class='tbl'>
				<input type='text' name='username' maxlength='30' class='textbox' style='width:200px;'>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u901}
				<span style='color:#ff0000'>*</span>
			</td>
			<td class='tbl'>
				<input type='text' name='fullname' maxlength='50' class='textbox' style='width:200px;'>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u002}
				<span style='color:#ff0000'>*</span>
			</td>
			<td class='tbl'>
				<input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;'>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u004}
				<span style='color:#ff0000'>*</span>
			</td>
			<td class='tbl'>
				<input type='password' name='password2' maxlength='20' class='textbox' style='width:200px;'>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u005}
				<span style='color:#ff0000'>*</span>
			</td>
			<td class='tbl'>
				<input type='text' name='email' maxlength='100' class='textbox' style='width:200px;'>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u006}
			</td>
			<td class='tbl'>
				<input type='radio' name='user_hide_email' value='1'>{$locale.u007}
				<input type='radio' name='user_hide_email' value='0' checked>{$locale.u008}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u016}
			</td>
			<td class='tbl'>
				<select name='user_offset' class='textbox' style='width:75px;'>
					{section name=offset max=24 loop=25 step=-1}
						<option{if $this_userdata.user_offset == $smarty.section.offset.index/-2} selected="selected"{/if}>{$smarty.section.offset.index/-2}</option>
					{/section}
					{section name=offset start=0 loop=25 step=1}
						<option{if $this_userdata.user_offset == $smarty.section.offset.index/2} selected="selected"{/if}>+{$smarty.section.offset.index/2}</option>
					{/section}
				</select>
				&nbsp;
				&nbsp;
				{$timezone}
			</td>
		</tr>
		{if $settings.display_validation == "1"}
			<tr>
				<td class='tbl'>
					{$locale.504}
				</td>
				<td class='tbl'>
					<img id="captcha" src="{$smarty.const.INCLUDES}secureimage-1.0.3/secureimage_show.php" alt="CAPTCHA Image" />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.505}<span style='color:#ff0000'>*</span>
				</td>
				<td class='tbl'>
					<input type='text' name='captcha_code' class='textbox' style='width:100px'>
				</td>
			</tr>
		{/if}
		{if $settings.email_verification == "0"}
			<tr>
				<td class='tbl'>
					{$locale.u009}</td>
				<td class='tbl'>
					<input type='text' name='user_location' maxlength='50' class='textbox' style='width:200px;'>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u010}
				</td>
				<td class='tbl'>}
					{html_select_date prefix='user_' time=$this_userdata.user_birthdate start_year="1900" end_year="-1" all_extra="class='textbox'"}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u021}
				</td>
				<td class='tbl'>
					<input type='text' name='user_aim' maxlength='16' class='textbox' style='width:200px;'>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u011}
				</td>
				<td class='tbl'>
					<input type='text' name='user_icq' maxlength='15' class='textbox' style='width:200px;'>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u012}
				</td>
				<td class='tbl'>
					<input type='text' name='user_msn' maxlength='100' class='textbox' style='width:200px;'>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u013}
				</td>
				<td class='tbl'>
					<input type='text' name='user_yahoo' maxlength='100' class='textbox' style='width:200px;'>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u014}
				</td>
				<td class='tbl'>
					<input type='text' name='user_web' maxlength='100' class='textbox' style='width:200px;'>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u015}
				</td>
				<td class='tbl'>
					<select name='user_theme' class='textbox' style='width:200px;'>
						{foreach from=$theme_files item=theme}
							<option>{$theme}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td valign='top'>
					{$locale.u020}
				</td>
				<td class='tbl'>
					<textarea name='user_sig' rows='5' class='textbox' style='width:295px'>{$userdata.user_sig}</textarea><br>
					<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick="addText('user_sig', '[b]', '[/b]');">
					<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick="addText('user_sig', '[i]', '[/i]');">
					<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick="addText('user_sig', '[u]', '[/u]');">
					<input type='button' value='url' class='button' style='width:30px;' onClick="addText('user_sig', '[url]', '[/url]');">
					<input type='button' value='mail' class='button' style='width:35px;' onClick="addText('user_sig', '[mail]', '[/mail]');">
					<input type='button' value='img' class='button' style='width:30px;' onClick="addText('user_sig', '[img]', '[/img]');">
					<input type='button' value='center' class='button' style='width:45px;' onClick="addText('user_sig', '[center]', '[/center]');">
					<input type='button' value='small' class='button' style='width:40px;' onClick="addText('user_sig', '[small]', '[/small]');">
				</td>
			</tr>
		{/if}
		<tr>
			<td align='center' colspan='2'>
				<br />
				<input type='submit' name='register' value='{$locale.506}' class='button'>
			</td>
		</tr>
	</form>
</table>
{include file="_closetable.tpl"}
{literal}<script language='JavaScript'>
//
// calculate the offset between browser and server time
//
var now = new Date();
var serveroffset = {/literal}{$serveroffset}{literal};
var offset = now.getTimezoneOffset() / -60 - serveroffset;
hours = parseInt(offset);
if (hours < 0) var minutes = (offset - hours) * -60; else var minutes = (offset - hours) * 60;
if (minutes != 0) minutes = ':' + minutes; else minutes = '';
offset = hours + minutes;
if (hours >= 0) offset = "+" + offset;
//
// and preselect the correct time offset value
//
var dropdown = document.forms['inputform'].elements['user_offset'];
for (var i=0; i < dropdown.options.length && dropdown.options[i].value != offset; i++);
if (i != dropdown.options.length) dropdown.selectedIndex = i;

function ValidateForm(frm) {
	if (frm.username.value=="") {
		alert("{/literal}{$locale.550}{literal}");
		return false;
	}
	if (frm.password1.value=="") {
		alert("{/literal}{$locale.551}{literal}");
		return false;
	}
	if (frm.email.value=="") {
		alert("{/literal}{$locale.552}{literal}");
		return false;
	}
}
</script>{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}