{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: forum.post.tpl                                       *}
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
{* Template for the forum module 'post'                                    *}
{*                                                                         *}
{***************************************************************************}
{if $show_preview}
{include file="_opentable.tpl" name=$_name title=$preview_title state=$_state style=$_style}
{if $poll_preview}
	{include file="forum.poll.tpl"}
{/if}
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
{section name=pid loop=$posts}
	{include file="forum.renderpost.tpl"}
{/section}
</table>
<table width='100%' cellspacing='0' cellpadding='0'>
	<tr>
		<td height='5'></td>
	</tr>
</table>		
{include file="_closetable.tpl"}
{/if}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}?action={$action}&amp;forum_id={$forum_id}&amp;thread_id={$thread_id}&amp;post_id={$post_id}&amp;reply_id={$reply_id}' enctype='multipart/form-data'>
	<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		<tr>
			<td>
				<table width='100%' border='0' cellspacing='1' cellpadding='0'>
					<tr>
						<td align='right' width='145' valign='top' class='tbl2'>
							{$locale.460}:
						</td>
						<td class='tbl1'>
							<input type='text' name='subject' value='{$subject}' class='textbox' maxlength='255' style='width: 250px' />
						</td>
					</tr>
					{if $org_message|default:"" != ""}
					<tr>
						<td align='right' width='145' class='tbl2' valign='top' style='white-space:nowrap'>
							<input type='submit' class='button' name='toggle_msg' value='{$locale.474}' onclick='javascript:flipDiv("org_message");return false;' />
						</td>
						<td class='tbl1'>
							<div id='org_message' class='textbox' style='display:none'>{$org_message|stripinput|nl2br}<br /></div>
							<input type='hidden' name='org_message' value='{$org_message}' />
						</td>
					</tr>
					{/if}
					<tr>
						<td align='right' valign='top' width='145' class='tbl2'>
							{$locale.461}:
						</td>
						<td class='tbl1'>
							{if $settings.hoteditor_enabled == 0 || $userdata.user_hoteditor == 0}
								<textarea name='message' cols='80' rows='15' class='textbox' style='width:100%; height:{math equation='x/4' x=$smarty.const.BROWSER_HEIGHT format='%u'}px;'>{$message}</textarea>
								<br />
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
								{$locale.462}
								<select name='bbcolor' class='textbox' style='width:90px;' onchange="addText('message', '[color=' + this.options[this.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;">
									<option value=''>Default</option>
									{section name=id loop=$fontcolors}
										<option value='{$fontcolors[id].color}' style='color:{$fontcolors[id].color};'>{$fontcolors[id].name|capitalize}</option>
									{/section}
								</select>
							{else}
								<style type='text/css'>@import url({$smarty.const.THEME}hoteditor/style.css);</style>
								<input type='hidden' id='message' name='message' value='{$message}' />
								<script language="javascript" type="text/javascript">
									var hoteditor_path = "{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/";
									var hoteditor_theme_path = "{$smarty.const.THEME}hoteditor";
								</script>
								<script language="javascript" type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/editor.js?version=4.2"></script>				
								{literal}
								<script language="javascript" type="text/javascript">
									var getdata = document.getElementById("message").value;
									Instantiate("max","editor", getdata , "100%", "250px");
									
									//For Vietnamese User. Edit file editor.js to enable vietnamese keyboard
									if(enable_vietnamese_keyboard==1){
										document.write("<script language=\"JavaScript\" type=\"text/javascript\" src={/literal}{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/avim.js{literal}><\/script>");
										var hoteditor_avim_method = hot_readCookie("hoteditor_avim_method");var him_auto_checked="";var him_telex_checked="";var him_vni_checked="";var him_viqr_checked="";var him_viqr2_checked="";var him_off_checked="";if(hoteditor_avim_method=="0"){him_auto_checked="checked";}else if(hoteditor_avim_method=="1"){him_telex_checked="checked";}else if(hoteditor_avim_method=="2"){him_vni_checked="checked";}else if(hoteditor_avim_method=="3"){him_viqr_checked="checked";}else if(hoteditor_avim_method=="4"){him_viqr2_checked="checked";}else if(hoteditor_avim_method=="-1"){him_off_checked="checked";}
										document.write("<div style='width:100%;text-align:center;font-family:Verdana;font-size:11px;'><input "+him_auto_checked+" id=him_auto onclick=setMethod(0); type=radio name=viet_method> Auto :: <input "+him_telex_checked+" id=him_telex onclick=setMethod(1); type=radio name=viet_method> Telex :: <input "+him_vni_checked+" id=him_vni onclick=setMethod(2); type=radio name=viet_method> VNI :: <input "+him_viqr_checked+" id=him_viqr onclick=setMethod(3); type=radio name=viet_method> VIQR :: <input "+him_viqr2_checked+" id=him_viqr2 onclick=setMethod(4); type=radio name=viet_method> VIQR* :: <input "+him_off_checked+" id=him_off onclick=setMethod(-1); type=radio name=viet_method> Off<br><img src="+styles_folder_path+"/vietnamese_symbol.gif></div>");
									}

									if(enable_vietnamese_keyboard==1){
										var hoteditor_avim_method = hot_readCookie("hoteditor_avim_method");var him_auto_checked;var him_telex_checked;var him_vni_checked;var him_viqr_checked;var him_viqr2_checked;var him_off_checked;if(hoteditor_avim_method=="0"){him_auto_checked="checked";}else if(hoteditor_avim_method=="1"){him_telex_checked="checked";}else if(hoteditor_avim_method=="2"){him_vni_checked="checked";}else if(hoteditor_avim_method=="3"){him_viqr_checked="checked";}else if(hoteditor_avim_method=="4"){him_viqr2_checked="checked";}else if(hoteditor_avim_method=="-1"){him_off_checked="checked";}
										document.write("<script language=\"JavaScript\" type=\"text/javascript\" src={/literal}{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/avim.js{literal}><\/script><div style='width:100%;text-align:center;font-family:Verdana;font-size:11px;'><input "+him_auto_checked+" id=him_auto onclick=setMethod(0); type=radio name=viet_method> Auto :: <input "+him_telex_checked+" id=him_telex onclick=setMethod(1); type=radio name=viet_method> Telex :: <input "+him_vni_checked+" id=him_vni onclick=setMethod(2); type=radio name=viet_method> VNI :: <input "+him_viqr_checked+" id=him_viqr onclick=setMethod(3); type=radio name=viet_method> VIQR :: <input "+him_viqr2_checked+" id=him_viqr2 onclick=setMethod(4); type=radio name=viet_method> VIQR* :: <input "+him_off_checked+" id=him_off onclick=setMethod(-1); type=radio name=viet_method> Off</div>");
									}
									function get_hoteditor_data(){
										setCodeOutput();
										var bbcode_output=document.getElementById("hoteditor_bbcode_ouput_editor").value;//Output to BBCode
										document.getElementById("message").value = bbcode_output;
									}					
								</script>
								{/literal}
							{/if}
						</td>
					</tr>
					{if $settings.hoteditor_enabled == 0 || $userdata.user_hoteditor == 0}
					<tr>
						<td align='right' width='145' valign='top' class='tbl2'>
							<input type='button' name='toggle' class='button' value='{$locale.467}' onclick='javascript:loadSmileys("smileys", "smileys_loaded", "{$smarty.const.BASEDIR}includes/ajax.response.php?request=smileys&parms=message");return false;' />
						</td>
						<td class='tbl1'>
							<div id='smileys' style='display:none'><img src='{$smarty.const.THEME}images/ajax-loader.gif' title='' alt='' /></div>
						</td>
					</tr>
					{/if}
					<tr>
						<td align='right' width='145' valign='top' class='tbl2'>
							{$locale.463}:
						</td>
						<td class='tbl1'>
							{if $opt_sticky && ($smarty.const.iMOD || $smarty.const.iSUPERADMIN)}
								<input type='checkbox' name='sticky' value='1'{if $is_sticky} checked{/if} />{$locale.480}
								<br />
							{/if}
							{if $opt_smileys && ($settings.hoteditor_enabled == 0 || $userdata.user_hoteditor == 0)}
								<input type='checkbox' name='disable_smileys' value='1'{if $is_smileys_disabled} checked{/if} />{$locale.483}
								<br />
							{/if}
							{if $opt_showsig && $userdata.user_sig}
								<input type='checkbox' name='show_sig' value='1'{if $is_sig_shown} checked{/if} />{$locale.481}
								<br />
							{/if}
							{if $opt_notify && $settings.thread_notify}
								<input type='checkbox' name='notify_me' value='1'{if $is_notified} checked{/if} />{$locale.485}
								<br />
							{/if}
						</td>
					</tr>
					{if $settings.attachments}
					<tr>
						<td align='right' width='145' valign='top' class='tbl2'{if $attachment_count > 0} rowspan='2'{/if}>
							{$locale.464}:
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
								{$locale.488} {$attachments[id].attach_name}
							{else}
								<input type='checkbox' name='delattach[{$attachments[id].index}]' value='{$attachments[id].attach_id}'{if $attachments[id].delete_checked} checked{/if} />
								{$locale.484} <a href='{$smarty.const.BASEDIR}getfile.php?type=a&amp;file_id={$attachments[id].attach_id}' alt='{$attachments[id].attach_comment}'> {if $attachments[id].attach_realname !=""}{$attachments[id].attach_realname}{else}{$attachments[id].attach_name}{/if}</a>
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
							<br /><br />
							<span class='small2'>{ssprintf format=$locale.466 var1=$attachmax var2=$attachtypes}</span>
							<br /><br />
							{$locale.473}
							<br />
							<textarea name='attach_comment' cols='50' rows='2' class='textbox'>{$comments}</textarea>
							<br />
							<input type='submit' name='upload' value='{$locale.471}' class='button' />
						</td>
					</tr>
					{/if}
				</table>
			</td>
		</tr>
	</table>
	{if $smarty.const.FPM_ACCESS && $fpm.exists|default:0 == 0}
	<div id='poll_form'{if $fpm.question != "" || $fpm.add_options == 1 || $fpm_settings.hide_poll == 0}{else} style='display:none'{/if}>
		<table width='100%' cellspacing='0' cellpadding='0'>
			<tr>
				<td height='5'></td>
			</tr>
		</table>		
		<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
			<tr>
				<td colspan='2' class='tbl2'>
					{$locale.FPM_100}
				</td>
			</tr>
			<tr>
				<td width='145' valign='top' class='tbl2'>
					{$locale.FPM_101}
				</td>
				<td class='tbl1'>
					<input type='hidden' id='fpm_option_show' name='fpm[option_show]' value='{$fpm.option_show}' />
					<input type='text' id='fpm_question' name='fpm[question]' value='{$fpm.question}' class='textbox' maxlength='200' style='width:250px' />
					<input type='checkbox' id='fpm_use_subject' name='fpm[use_subject]' value='1' class='textbox'{if $fpm.use_subject == 1} checked{/if} /> {$locale.FPM_102}
				</td>
			</tr>
			{foreach from=$fpm.option key=opt item=option}
				<tr>
					<td width='145' valign='top' class='tbl2'>
						{$locale.FPM_103}{$opt}
					</td>
					<td class='tbl1'>
						<input type='text' id='fpm_option_{$opt}' name='fpm[option][{$opt}]' value='{$option}' class='textbox' maxlength='200' style='width:250px' />
						{if $opt == $fpm.option_show && $opt != $fpm_settings.option_max}
							<input type='submit' name='fpm[add_options]' class='button' value='{$locale.FPM_104}' />
						{/if}
					</td>
				</tr>
			{/foreach}
			<tr>
				<td width='145' valign='top' class='tbl2'>
					{$locale.FPM_122}
				</td>
				<td class='tbl1'>
					<select class='textbox' id='fpm_type' name='fpm[type]'>
						<option value='0'{if $fpm.type == 0} selected="selected"{/if}>{$locale.FPM_300}</option>
						<option value='1'{if $fpm.type == 1} selected="selected"{/if}>{$locale.FPM_301}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width='145' valign='top' class='tbl2'>
					{$locale.FPM_105}
				</td>
				<td class='tbl1'>
					<input type='text' id='fpm_duration' name='fpm[duration]' value='{$fpm.duration}' class='textbox' maxlength='3' style='width:30px' />
					 {$locale.FPM_106}
				</td>
			</tr>
			{if $post_id|default:0 != 0}
			<tr>
				<td width='145' valign='top' class='tbl2'>
					{$locale.463}
				</td>
				<td class='tbl1'>
					<input type='checkbox' name='fpm[reset_start]' value='1' class='textbox' {if $fpm.reset_start|default:0  == 1} checked{/if} />{$locale.FPM_120}
					<br />
					<input type='checkbox' name='fpm[reset_votes]' value='1' class='textbox' {if $fpm.reset_votes|default:0  == 1} checked{/if} />{$locale.FPM_121}
				</td>
			</tr>
			{/if}
		</table>
	</div>
	{/if}
	<table cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td align='center' colspan='2' class='tbl1'>
				{if $smarty.const.iMOD || $smarty.const.iSUPERADMIN}
					{if $action == "newthread"}
						{if $smarty.const.FPM_ACCESS && $fpm.exists|default:0 == 0}
							{if $fpm.question == "" && $fpm.add_options == 0 && $fpm_settings.hide_poll == 1}
								<input type='button' id='poll_button' name='show_poll' value='{$locale.FPM_110}' class='button' onclick="fpm_toggle_poll()" />
							{else}
								<input type='button' id='poll_button' name='show_poll' value='{$locale.FPM_119}' class='button' onclick="fpm_toggle_poll()" />
							{/if}
						{/if}
					{/if}
					{if $action == "edit"}
						{if $is_sticky}
							<input type='submit' name='sticky_off' value='{$locale.410}' class='button' />
						{else}
							<input type='submit' name='sticky_on' value='{$locale.411}' class='button' />
						{/if}
						<input type='submit' name='renew_post' value='{$locale.416}' class='button' />
						<input type='submit' name='move_post' value='{$locale.412}' class='button' />
						<input type='submit' name='delete_post' value='{$locale.407}' class='button' />
					{/if}
					&nbsp;&nbsp;&nbsp;&nbsp;
				{/if}
				<input type='submit' name='cancel' value='{$locale.417}' class='button' />
				<input type='submit' name='preview' value='{$button_preview}' class='button' onclick='javascript:get_hoteditor_data();' />
				<input type='submit' name='save' value='{$button_save}' class='button' onclick='javascript:get_hoteditor_data();' />
				<input type='hidden' name='post_author' value='{$post_author}' />
				<input type='hidden' name='random_id' value='{$random_id}' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{literal}<script type='text/javascript'>
function fpm_toggle_poll() {
	var poll_form = document.getElementById('poll_form');
	var poll_button = document.inputform.show_poll;
	if (poll_button.value == '{/literal}{$locale.FPM_110}{literal}') {
		poll_button.value = '{/literal}{$locale.FPM_119}{literal}';
		poll_form.style.display = '';
		document.getElementById('fpm_question').focus()
	} else {
		poll_button.value = '{/literal}{$locale.FPM_110}{literal}';
		poll_form.style.display = 'none';
		document.getElementById('fpm_question').value = '';
		document.getElementById('fpm_use_subject').checked = false;
		for(i = 1; i <= document.getElementById('fpm_option_show').value; i ++) {
			document.getElementById('fpm_option_' + i).value = '';
		}
		document.getElementById('fpm_duration').value = 0;
		document.inputform.message.focus()
	}
}
</script>{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
