{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.edit_profile.tpl                                *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-10 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates the PLi-Fusion panel: edit_profile              *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.440 state=$_state style=$_style}
<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}{if $is_admin}{$aidlink}&amp;user_id={$this_userdata.user_id}{/if}' enctype='multipart/form-data'>
	<table align='center' cellpadding='0' cellspacing='0'>
		{if $update_profile}
			{if $error|default:"" == ""}
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<b>{$locale.441}</b>
					<br /><br />
				</td>
			</tr>
			{else}
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<b>{$locale.442}
					<br />
					{$error}</b>
					<br />
				</td>
			</tr>
			{/if}
		{/if}
		<tr>
			<td class='tbl'>
				{$locale.u001}<span style='color:#ff0000'>*</span>
			</td>
			<td class='tbl'>
				<input type='text' name='user_name' value='{$this_userdata.user_name}' maxlength='30' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u901}<span style='color:#ff0000'>*</span>
			</td>
			<td class='tbl'>
				<input type='text' name='user_fullname' value='{$this_userdata.user_fullname}' maxlength='50' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u003}
			</td>
			<td class='tbl'>
				<input type='password' name='user_newpassword' maxlength='20' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u004}</td>
			<td class='tbl'>
				<input type='password' name='user_newpassword2' maxlength='20' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u005}<span style='color:#ff0000'>*</span>
			</td>
			<td class='tbl'>
				<input type='text' name='user_email' value='{$this_userdata.user_email}' maxlength='100' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u006}
			</td>
			<td class='tbl'>
				<input type='radio' name='user_hide_email' value='1' {if $this_userdata.user_hide_email == "1"}checked="checked"{/if} />{$locale.u007}
				<input type='radio' name='user_hide_email' value='0' {if $this_userdata.user_hide_email == "0"}checked="checked"{/if} />{$locale.u008}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u026}
			</td>
			<td class='tbl'>
				<input type='radio' name='user_newsletters' value='1' {if $this_userdata.user_newsletters == "1"}checked="checked"{/if} />{$locale.u037}
				<input type='radio' name='user_newsletters' value='2' {if $this_userdata.user_newsletters == "2"}checked="checked"{/if} />{$locale.u038}
				<input type='radio' name='user_newsletters' value='0' {if $this_userdata.user_newsletters == "0"}checked="checked"{/if} />{$locale.u039}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u024}
			</td>
			<td class='tbl'>
				<input type='radio' name='user_forum_fullscreen' value='1' {if $this_userdata.user_forum_fullscreen == "1"}checked="checked"{/if} />{$locale.u007}
				<input type='radio' name='user_forum_fullscreen' value='0' {if $this_userdata.user_forum_fullscreen == "0"}checked="checked"{/if} />{$locale.u008}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u009}
			</td>
			<td class='tbl'>
				<input type='text' name='user_location' value='{$this_userdata.user_location}' maxlength='50' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
		<td class='tbl'>
			{$locale.u010}
		</td>
			<td class='tbl'>
				{html_select_date prefix='user_' time=$this_userdata.user_birthdate start_year="1900" end_year="-1" all_extra="class='textbox'"}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u021}
			</td>
			<td class='tbl'>
				<input type='text' name='user_aim' value='{$this_userdata.user_aim}' maxlength='16' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u011}
			</td>
			<td class='tbl'>
				<input type='text' name='user_icq' value='{$this_userdata.user_icq}' maxlength='15' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u012}
			</td>
			<td class='tbl'>
				<input type='text' name='user_msn' value='{$this_userdata.user_msn}' maxlength='100' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u013}
			</td>
			<td class='tbl'>
				<input type='text' name='user_yahoo' value='{$this_userdata.user_yahoo}' maxlength='100' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u014}
			</td>
			<td class='tbl'>
				<input type='text' name='user_web' value='{$this_userdata.user_web}' maxlength='100' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u028}
			</td>
			<td class='tbl'>
				<select name='user_locale' class='textbox' style='width:200px;'>
					{section name=locales loop=$locales}
						<option value='{$locales[locales].locale_id}'{if $locales[locales].selected} selected="selected"{/if}>{$locales[locales].locale_name}</option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u015}
			</td>
			<td class='tbl'>
				<select name='user_theme' class='textbox' style='width:200px;'>
					{foreach from=$theme_files item=theme}
						<option{if $this_userdata.user_theme ==  $theme} selected="selected"{/if}>{$theme}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.u016}
			</td>
			<td class='tbl'>
				<select name='user_offset' class='textbox'>
				{section name=offset loop=$settings.timezones}
					<option{if $this_userdata.user_offset == $settings.timezones[offset]} selected="selected"{/if} value='{$settings.timezones[offset]}'>{$settings.timezones[offset]}</option>
				{/section}
				</select>
				&nbsp;
				<input type='button' value='{$locale.u027}' class='button' onclick="autotimezone();return false;" />
				&nbsp;
				{$timezone}
			</td>
		</tr>
		{if $this_userdata.user_avatar|default:"" == ""}
		<tr>
			<td class='tbl'>
				{$locale.u017}
			</td>
			<td class='tbl'>
				<input type='file' name='user_avatar' class='textbox' style='width:200px;' />
				<br />
				<span class='small2'>{$locale.u018}</span>
				<br />
				<span class='small2'>{ssprintf format=$locale.u022 var1=$avatar.size var2=$avatar.x var3=$avatar.y}</span>
			</td>
		</tr>
		{/if}
		<tr>
			<td valign='top'>
				{$locale.u020}
			</td>
			<td class='tbl'>
				<textarea name='user_sig' rows='5' cols='80' class='textbox' style='width:295px'>{$this_userdata.user_sig}</textarea><br />
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('user_sig', '[b]', '[/b]');" />
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('user_sig', '[i]', '[/i]');" />
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('user_sig', '[u]', '[/u]');" />
				<input type='button' value='url' class='button' style='width:30px;' onclick="addText('user_sig', '[url]', '[/url]');" />
				<input type='button' value='mail' class='button' style='width:35px;' onclick="addText('user_sig', '[mail]', '[/mail]');" />
				<input type='button' value='img' class='button' style='width:30px;' onclick="addText('user_sig', '[img]', '[/img]');" />
				<input type='button' value='center' class='button' style='width:45px;' onclick="addText('user_sig', '[center]', '[/center]');" />
				<input type='button' value='small' class='button' style='width:40px;' onclick="addText('user_sig', '[small]', '[/small]');" />
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				{if $this_userdata.user_avatar|default:"" != ""}
					{$locale.u017}
					<br />
					<img src='{$smarty.const.IMAGES_AV}{$this_userdata.user_avatar}' alt='{$locale.u017}' />
					<br />
					<input type='checkbox' name='del_avatar' value='y' /> {$locale.u019}
					<input type='hidden' name='user_avatar' value='{$this_userdata.user_avatar}' />
					<br /><br />
				{/if}
				<input type='hidden' name='user_hash' value='{$this_userdata.user_password}' />
				<input type='submit' name='update_profile' value='{$locale.460}' class='button' />
			</td>
		</tr>
	</table>
</form>
{literal}<script type='text/javascript'>
//
// calculate the offset between browser and server time
//
function autotimezone() {
	var now = new Date();
	var serveroffset = {/literal}{$serveroffset|default:0}{literal};
	var offset = now.getTimezoneOffset() / -60 - serveroffset;
	hours = parseInt(offset);
	if (hours < 0) var minutes = (offset - hours) * -60; else var minutes = (offset - hours) * 60;
	if (minutes != 0) minutes = ':' + minutes; else minutes = '';
	offset = hours + minutes;
	if (hours >= 0) offset = "+" + offset;
	//
	// and preselect the correct time offset value
	//
	var dropdown = document.forms['edit_profile_form'].elements['user_offset'];
	for (var i=0; i < dropdown.options.length; i++) {
		if (dropdown.options[i].value == offset) {
			dropdown.selectedIndex = i;
			break;
		}
	}
}	
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
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}