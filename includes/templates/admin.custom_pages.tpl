{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.custom_pages.tpl                               *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-18 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'custom_pages'                    *}
{*                                                                         *}
{***************************************************************************}
{if $settings.tinymce_enabled == 1}<script type='text/javascript'>advanced();</script>{/if}
{include file="_opentable.tpl" name=$_name title=$locale.420 state=$_state style=$_style}
<form name='selectform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<center>
		<select name='page_id' class='textbox' style='width:250px;'>
		{section name=id loop=$pages}
			<option value='{$pages[id].page_id}'{if $pages[id].selected} selected='selected'{/if}>{$pages[id].page_title}</option>
		{/section}
		</select>
		<input type='submit' name='edit' value='{$locale.421}' class='button' />
		<input type='submit' name='delete' value='{$locale.422}' onclick='return DeletePage();' class='button' />
	</center>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$title state=$_state style=$_style}
<form name='inputform' method='post' action='{$action}' onSubmit='return ValidateForm(this)'>
	<table align='center' cellpadding='0' cellspacing='0' width='90%'>
		<tr>
			<td align='center' class='tbl'>
				{$locale.430}
				<input type='text' name='page_title' value='{$page_title}' class='textbox' style='width: 250px;' />
				&nbsp;{$locale.431}
				<select name='page_access' class='textbox' style='width:150px;'>
				{section name=id loop=$user_groups}
					<option value='{$user_groups[id].id}{if $user_groups[id].selected} selected{/if}'>{$user_groups[id].name}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.432}
				<br />
				<textarea name='page_content' cols='95' rows='20' class='{if $settings.tinymce_enabled != 1}textbox{/if}' style='width:100%; height:{math equation='x/2' format="%u" x=$smarty.const.BROWSER_HEIGHT}px'>{$page_content}</textarea>
		</tr>
		{if $settings.tinymce_enabled != 1}
		<tr>
			<td align='center' class='tbl'>
				<input type='button' value='<?php?>' class='button' style='width:60px;' onclick="addText('page_content', '<?php\n', '\n?>');">
				<input type='button' value='<p>' class='button' style='width:35px;' onclick="insertText('page_content', '<p>');">
				<input type='button' value='<br>' class='button' style='width:40px;' onclick="insertText('page_content', '<br>');">
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick="addText('body', '<b>', '</b>');">
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick="addText('body', '<i>', '</i>');">
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick="addText('body', '<u>', '</u>');">
				<input type='button' value='link' class='button' style='width:35px;' onClick="addText('body', '<a href=\'', '\' target=\'_blank\'>Link</a>');">
				<input type='button' value='img' class='button' style='width:35px;' onClick="addText('body', '<img src=\'{$img_src}\' style=\'margin:5px;\' align=\'left\' />');">
				<input type='button' value='center' class='button' style='width:45px;' onClick="addText('body', '<center>', '</center>');">
				<input type='button' value='small' class='button' style='width:40px;' onClick="addText('body', '<span class=\'small\'>', '</span>');">
				<input type='button' value='small2' class='button' style='width:45px;' onClick="addText('body', '<span class=\'small2\'>', '</span>');">
				<input type='button' value='alt' class='button' style='width:25px;' onClick="addText('body', '<span class=\'alt\'>', '</span>');">
				<br />
			</td>
		</tr>
		{/if}
		<tr>
			<td align='center' class='tbl'>
				<br>
				{if $new_page && false} {* hide this box for now *}
					<input type='checkbox' name='add_link' value='1'{$addlink} />  {$locale.433}&nbsp;&nbsp;
				{/if}
				<input type='checkbox' name='page_comments' value='1'{$comments} /> {$locale.434}&nbsp;&nbsp;
				<input type='checkbox' name='page_ratings' value='1'{$ratings} /> {$locale.435}
			</td>
		</tr>
		<tr>
			<td align='center' class='tbl'>
				<br />
				<input type='submit' name='preview' value='{$locale.436}' class='button' />&nbsp;&nbsp;
				<input type='submit' name='save' value='{$locale.437}' class='button' />
			</td>
		</tr>
	</table>
</form>
<script type='text/javascript'>
	function DeletePage() {ldelim}
		return confirm('{$locale.409}');
	{rdelim}
	function ValidateForm(frm) {ldelim}
		if(frm.subject.value=='') {ldelim}
			alert('{$locale.410}');
			return false;
		{rdelim}
	{rdelim}
</script>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
