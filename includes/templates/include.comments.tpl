{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: include.comments.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-03 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the include module: 'comments_include'                     *}
{*                                                                         *}
{***************************************************************************}
<a name='comments'></a>
{include file="_opentable.tpl" name=$_name title=$locale.c100 state=$_state style=$_style}
{section name=item loop=$comments}
{if $smarty.section.item.first}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
{/if}
	<tr>
		<td class='tbl2'>
			<b>
				{if $smarty.const.iMEMBER && $comments[item].user_name != ""}
					<a href='{$smarty.const.BASEDIR}profile.php?lookup={$comments[item].comment_name}'>{$comments[item].user_name}</a>
				{else}
					{$comments[item].comment_name}
				{/if}
			</b>
			<span class='small'>{$locale.041}{$comments[item].comment_datestamp|date_format:"longdate"}</span>
		</td>
	</tr>
	<tr>
		<td class='tbl1'>
			{$comments[item].comment_message}<br /><br />
		</td>
	</tr>
	{if $allow_post && $smarty.section.item.last}
	<tr>
		<td align='right' class='tbl2'>
			{buttonlink name=$locale.c106 link=$smarty.const.ADMIN|cat:"comments.php"|cat:$aidlink|cat:"&amp;ctype="|cat:$comment_type|cat:"&amp;cid="|cat:$comment_id}
		</td>
	</tr>
	{/if}
	{cycle values="tbl1,tbl2" print=false}
{if $smarty.section.item.last}
</table>
{/if}
{sectionelse}	
	{$locale.c101}
{/section}	
{include file="_closetable.tpl"}
{assign var=_name value=$_name|cat:"_post"}
{include file="_opentable.tpl" name=$_name title=$locale.c102 state=$_state style=$_style}
{if $smarty.const.iMEMBER || $settings.guestposts == "1"}
	<form name='inputform' method='post' action='{$post_link}'>
		<table align='center' width='100%' cellspacing='0' cellpadding='0' class='tbl'>
		{if $smarty.const.iGUEST}
			<tr>
				<td colspan='2' align='center' class='tbl1'>
					{$locale.c103} <input type='text' name='comment_name' maxlength='30' class='textbox' style='width:200px;' />
					<span style='color:#CC0000;font-weight:bold;'>*</span>
				</td>
			</tr>
		{/if}
		<tr>
			{if $settings.hoteditor_enabled == 0 || (iMEMBER  && $userdata.user_hoteditor == 0)}
			<td colspan='2' align='center' class='tbl1'>
				<textarea name='comment_message' rows='6' cols='80' class='textbox' style='width:400px'></textarea><br />
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('comment_message', '[b]', '[/b]');" />
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('comment_message', '[i]', '[/i]');" />
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('comment_message', '[u]', '[/u]');" />
				<input type='button' value='url' class='button' style='width:30px;' onclick="addText('comment_message', '[url]', '[/url]');" />
				<input type='button' value='mail' class='button' style='width:35px;' onclick="addText('comment_message', '[mail]', '[/mail]');" />
				<input type='button' value='img' class='button' style='width:30px;' onclick="addText('comment_message', '[img]', '[/img]');" />
				<input type='button' value='center' class='button' style='width:45px;' onclick="addText('comment_message', '[center]', '[/center]');" />
				<input type='button' value='small' class='button' style='width:40px;' onclick="addText('comment_message', '[small]', '[/small]');" />
				<input type='button' value='code' class='button' style='width:40px;' onclick="addText('comment_message', '[code]', '[/code]');" />
				<input type='button' value='quote' class='button' style='width:45px;' onclick="addText('comment_message', '[quote]', '[/quote]');" />
				<br /><br />
				<div id='smileys' style='display:none'><img src='{$smarty.const.THEME}images/ajax-loader.gif' title='' alt='' /></div>
			{else}
			<td colspan='2' align='center' class='tbl1'>
				{include file="_bbcode_editor.tpl" name="comment_message" id="comment_message" author="" message="" width="100%" height="150px"}
			{/if}
			</td>
		</tr>
		{if $settings.hoteditor_enabled == 0 || (iMEMBER  && $userdata.user_hoteditor == 0)}
		<tr>
			<td colspan='2' align='center' class='tbl1'>
				<input type='submit' name='toggle' class='button' value='{$locale.c108}' onclick='javascript:loadSmileys("smileys", "smileys_loaded", "{$smarty.const.BASEDIR}includes/ajax.response.php?request=smileys&parms=comment_message");return false;' />
				<input type='checkbox' name='disable_smileys' value='1' />{$locale.c107}<br /><br />
			</td>
		</tr>
		{/if}
		{if $smarty.const.iGUEST}
			<tr>
				<td colspan='2' class='tbl1'>
				</td>
			</tr>
			{if $cic != ""}
				<tr>
					<td colspan='2' align='center'>
						<span style='color:#CC0000;font-weight:bold;'>{$locale.c110}<br /><br /></span>
					</td>
				</tr>
			{/if}
			<tr>
				<td colspan='2' class='tbl2'>
					{$locale.c111}
				</td>
			</tr>
			<tr>
				<td align='right' width='50%' class='tbl1'>
					{$locale.c112}
				</td>
				<td align='left' width='50%' class='tbl1'>
					{if $settings.validation_method == "text"}
						<span style='font-size:125%;font-weight:bold;'>{$validation_code|upper}</span>
					{else}
						<img id="captcha" src="{$smarty.const.INCLUDES}secureimage-1.0.3/securimage_show.php" alt="CAPTCHA Image" />
					{/if}
				</td>
			</tr>
			<tr>
				<td align='right' width='50%' class='tbl1'>
					{$locale.c113}
				</td>
				<td align='left' width='50%' class='tbl1'>
					<input type='text' name='captcha_code' class='textbox' style='vertical-align:middle;width:100px' />
					{if $settings.validation_method == "image"}
						&nbsp;
						{buttonlink name=$locale.c114 link="document.getElementById(\"captcha\").src=\""|cat:$smarty.const.INCLUDES|cat:"secureimage-1.0.3/securimage_show.php?\"+Math.random(); return false;" script="yes"}
					{/if}
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl1'>
					&nbsp;
				</td>
			</tr>
		{/if}
		<tr>
			<td colspan=2' align='center'>
				<input type='submit' name='post_comment' value='{$locale.c102}' class='button' onclick='javascript:get_hoteditor_data("comment_message");' />
			</td>
		</tr>
	</table>
</form>
{else}
	{$locale.c105}
{/if}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
