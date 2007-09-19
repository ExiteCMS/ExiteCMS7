{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: main.messages.post.tpl                         *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-15 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the post message panel of the main module 'pm'.            *}
{*                                                                         *}
{***************************************************************************}
{section name=id loop=$messages}
	{if $smarty.section.id.first}
	{include file="_opentable.tpl" name=$_name title=$locale.438 state=$_state style=$_style}
	<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
	{/if}
	{include file="main.pm.renderpm.tpl"}
	{if $smarty.section.id.last}
	</table>
	{include file="_closetable.tpl"}
	{/if}
{/section}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}' enctype='multipart/form-data'>
	<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
		<tr>
			<td align='right' width='145' class='tbl2' valign='top' style='white-space:nowrap'>
				{$locale.421}:
			</td>
			<td class='tbl1' valign='top' style='white-space:nowrap'>
				<div style='display:none'>
				<select multiple size='5' name='recipients[]' id='recipients' class='textbox' style='width:200px'>
				{section name=id loop=$recipients}
					<option value='{$recipients[id].0}' selected>{$recipients[id].1}</option>
				{/section}
				</select>
				</div>
				<div id='to_list'>
				{section name=id loop=$recipients}
					{if $recipient_given}
						{if $recipients[id].0 < 0}
							<input type='hidden' name='group_id' value='{$recipients[id].0|abs}'>
							<a href='{$smarty.const.BASEDIR}profile.php?group_id={$recipients[id].0|abs}' alt=''>
						{else}
							<input type='hidden' name='user_id' value='{$recipients[id].0}'>
							<a href='{$smarty.const.BASEDIR}profile.php?lookup={$recipients[id].0}' alt=''>
						{/if}
					{else}
						<a href='#' alt='' title='{$locale.467}' onclick='return RemoveRecipient("{$smarty.section.id.index}");'>
					{/if}
					{if $recipients[id].0 < 0}
						<b>@{$recipients[id].1}</b>
					{else}
						{$recipients[id].1}
					{/if}
					</a>{if !$smarty.section.id.last}, {/if}
				{sectionelse}
					-
				{/section}
				</div>
				{if !$recipient_given}
					<hr />
					<select id='user_ids' name='user_ids' class='textbox'>
					{section name=id loop=$user_list}
						<option value='{$user_list[id].user_id}'>{$user_list[id].user_name}</option>
					{/section}
					</select>
					<input type='submit' name='select_user' value='{$locale.468}' class='button' onclick="AddUser();return false;">
					{if $allow_sendtoall}
						&nbsp; - &nbsp;
						<select id='group_ids' name='group_ids' class='textbox'>
						{section name=id loop=$user_groups}
							<option value='{$user_groups[id].0}'>{$user_groups[id].1}</option>
						{/section}
						</select>
						<input type='submit' name='select_group' value='{$locale.469}' class='button' onclick="AddGroup();return false;">
					{/if}
				{/if}
			</td>
		</tr>
		<tr>
			<td align='right' width='145' class='tbl2' style='white-space:nowrap'>
				{$locale.405}:
			</td>
			<td class='tbl1'>
				<input type='text' name='subject' value='{$subject}' maxlength='32' class='textbox' style='width:400px;'>
			</td>
		</tr>
		{if $reply_message|default:"" != ""}
		<tr>
			<td align='right' width='145' class='tbl2' valign='top' style='white-space:nowrap'>
				<input type='submit' class='button' name='toggle_msg' value='{$locale.417}' onclick='javascript:flipDiv("org_message");return false;'>
			</td>
			<td class='tbl1'>
				<div id='org_message' style='display:none'>{$reply_message}<br /></div>
			</td>
		</tr>
		{/if}
		<tr>
			<td align='right' width='145' class='tbl2' valign='top' style='white-space:nowrap'>
				{if $action == "post"}
					{$locale.422}:
				{elseif $action != "forward"}
					{$locale.433}:
				{else}
					{$locale.423}:
				{/if}
			</td>
			<td class='tbl1'>
				<textarea name='message' cols='80' rows='15' class='textbox' style='width:100%; height:{math equation='x/4' x=$smarty.const.BROWSER_HEIGHT format='%u'}px;'>{$message}</textarea>
			</td>
		</tr>
		<tr>
			<td align='right' class='tbl2' valign='top'>
			</td>
			<td class='tbl1'>
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('message', '[b]', '[/b]');" />
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('message', '[i]', '[/i]');" />
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('message', '[u]', '[/u]');" />
				<input type='button' value='ul' class='button' style='width:25px;' onclick="addText('message', '[ul]', '[/ul]');" />
				<input type='button' value='li' class='button' style='width:25px;' onclick="addText('message', '[li]', '[/li]');" />
				<input type='button' value='url' class='button' style='width:30px;' onclick="addURL('message');" />
				<input type='button' value='mail' class='button' style='width:35px;' onclick="addText('message', '[mail]', '[/mail]');" />
				<input type='button' value='img' class='button' style='width:30px;' onclick="addText('message', '[img]', '[/img]');" />
				<input type='button' value='center' class='button' style='width:45px;' onclick="addText('message', '[center]', '[/center]');" />
				<input type='button' value='small' class='button' style='width:40px;' onclick="addText('message', '[small]', '[/small]');" />
				<input type='button' value='code' class='button' style='width:40px;' onclick="addText('message', '[code]', '[/code]');" />
				<input type='button' value='quote' class='button' style='width:45px;' onclick="addText('message', '[quote{if $orgauthor !=""}={$orgauthor}{/if}]', '[/quote]');" />
				&nbsp;
				{$locale.447}:
				<select name='bbcolor' class='textbox' style='width:90px;' onchange="addText('message', '[color=' + this.options[this.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;">
					<option value=''>Default</option>
					{foreach from=$locale.448 item=color}
						<option value='{$color}' style='color:{$color};'>{$color|capitalize}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='145' class='tbl2' valign='top' style='white-space:nowrap'>
				<input type='submit' class='button' name='toggle' value='{$locale.633}' onclick='javascript:flipDiv("smileys");return false;'>
			</td>
			<td class='tbl1'>
				<div id='smileys' style='display:none'>{displaysmileys field="message"}</div>
			</td>
		</tr>
		<tr>
			<td align='right' width='145' class='tbl2' valign='top' style='white-space:nowrap'>
				{$locale.425}:
			</td>
			<td class='tbl1'>
				<input type='checkbox' name='chk_disablesmileys' value='y'{if $is_disablesmileys} checked{/if}>{$locale.427}
			</td>
		</tr>
		{if $settings.attachments}
		<tr>
			<td align='right' width='145' valign='top' class='tbl2'{if $attachment_count > 0} rowspan='2'{/if}>
				{$locale.470}:
			</td>
			{section name=id loop=$attachments}
				{if $smarty.section.id.first}
				<td class='tbl1'>
				{/if}
				{if $attachments[id].new} 
					<input type='hidden' name='attach[{$attachments[id].key}][attach_tmp]' value='{$attachments[id].attach_tmp}' />
					<input type='hidden' name='attach[{$attachments[id].key}][type]' value='{$attachments[id].type}' />
					<input type='hidden' name='attach[{$attachments[id].key}][attach_size]' value='{$attachments[id].attach_size}' />
					<input type='hidden' name='attach[{$attachments[id].key}][attach_count]' value='{$attachments[id].attach_count}' />
					<input type='hidden' name='attach[{$attachments[id].key}][attach_comment]' value='{$attachments[id].attach_comment}' />
					<input type='hidden' name='attach[{$attachments[id].key}][attach_name]' value='{$attachments[id].attach_name}' />
					<input type='hidden' name='attach[{$attachments[id].key}][attach_ext]' value='{$attachments[id].attach_ext}'/>
					<input type='checkbox' name='delattach[{$attachments[id].index}]' value='-{$attachments[id].key}'{if $attachments[id].delete_checked} checked{/if} />
					{$locale.476} {$attachments[id].attach_name}
				{else}
					<input type='checkbox' name='delattach[{$attachments[id].index}]' value='{$attachments[id].attach_id}'{if $attachments[id].delete_checked} checked{/if} />
					{if $action == "forward"}{$locale.478}{else}{$locale.477}{/if} <a href='{$smarty.const.BASEDIR}getfile.php?type=a&amp;file_id={$attachments[id].attach_id}' alt='{$attachments[id].attach_comment}'> {if $attachments[id].attach_realname !=""}{$attachments[id].attach_realname}{else}{$attachments[id].attach_name}{/if}</a>
				{/if}
				<br />
				{if $smarty.section.id.last}
			</td>
		</tr>
		<tr>
				{/if}
			{/section}
			<td class='tbl1'>
				<input type='file' name='attach' class='textbox' style='width:200px;' />
				<input type='submit' name='upload' value='{$locale.473}' class='button' />
				<br /><br />
				<span class='small2'>{ssprintf format=$locale.472 var1=$attachmax var2=$attachtypes}</span>
				<br /><br />
				{$locale.471}
				<br />
				<textarea name='attach_comment' cols='50' rows='2' class='textbox'>{$comments}</textarea>
			</td>
		</tr>
		{/if}
	</table>
	<table border='0' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td align='center' class='tbl'>
				<input type='hidden' name='msg_id' value='{$msg_id}'>
				<input type='hidden' name='action' value='{$action}'>
				<input type='hidden' name='folder' value='{$folder}'>
				<input type='hidden' name='org_message' value='{$org_message}'>
				<input type='hidden' name='pmindex_from_id' value='{$pmindex_from_id}'>
				<input type='hidden' name='pmindex_to_id' value='{$pmindex_to_id}'>
				<input type='submit' name='close' value='{$locale.435}' class='button'>
				<input type='submit' name='send_preview' value='{$locale.429}' class='button' onclick="return ValidateForm()">
				<input type='submit' name='send_message' value='{$locale.430}' class='button' onclick="return ValidateForm()">
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{literal}<script type='text/javascript'>
	String.prototype.htmlEntities = function () {
		return this.replace(/</g,'&lt;').replace(/>/g,'&gt;');
	};

	function ValidateForm() {
		if (document.inputform.subject.value == "" || document.inputform.message.value == "" || document.inputform.recipients.length == 0) {
			alert("{/literal}{$locale.486}{literal}");return false; }
	}

	function AddUser() {

		var listLength = document.getElementById("recipients").length;
		var selItem = document.getElementById("user_ids").selectedIndex;
		var selText = document.getElementById("user_ids").options[selItem].text;
		var selValue = document.getElementById("user_ids").options[selItem].value;
		document.getElementById("recipients").options[listLength] = new Option(selText, selValue);
		document.getElementById("recipients").options[listLength].selected = true;
		document.getElementById("user_ids").options[selItem] = null;

		UpdateDisplayedList();
		return false;
	}
	
	function AddGroup() {

		var listLength = document.getElementById("recipients").length;
		var selItem = document.getElementById("group_ids").selectedIndex;
		var selText = document.getElementById("group_ids").options[selItem].text;
		var selValue = -1 * document.getElementById("group_ids").options[selItem].value;
		document.getElementById("recipients").options[listLength] = new Option(selText, selValue);
		document.getElementById("recipients").options[listLength].selected = true;
		document.getElementById("group_ids").options[selItem] = null;

		UpdateDisplayedList();
	}

	function RemoveRecipient(idx) {

		var selText = document.getElementById("recipients").options[idx].text;
		var selValue = document.getElementById("recipients").options[idx].value;
		document.getElementById("recipients").options[idx] = null;
		if (selValue > 0) {
			var listLength = document.getElementById("user_ids").length;
			document.getElementById("user_ids").options[listLength] = new Option(selText, selValue);
		} else {
			var listLength = document.getElementById("group_ids").length;
			document.getElementById("group_ids").options[listLength] = new Option(selText, -1 * selValue);
		}
		UpdateDisplayedList();
	}
	
	function UpdateDisplayedList() {

		var i = 0;
		var html = "";
		var selText = "";
		var selValue = 0;
		var listLength = document.getElementById("recipients").length;
		for (i=0; i < listLength; i++) {
			selText = document.getElementById("recipients").options[i].text;
			selValue = document.getElementById("recipients").options[i].value;
			if (html != "") html = html + ", ";
			if (selValue < 0) html = html + "<b>@";
			html = html + "<a href='#' alt='' title='{/literal}{$locale.467}{literal}' onclick='return RemoveRecipient(" + i + ");'>" + selText.htmlEntities() + "</a>";
			if (selValue < 0) html = html + "</b>";
		}
		if (html == "") html = "-";
		document.getElementById('to_list').innerHTML = html;
	}
</script>{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}