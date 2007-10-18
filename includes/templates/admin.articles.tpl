{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.articles.tpl                                   *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-16 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'articles'			               *}
{*                                                                         *}
{***************************************************************************}
{if $settings.tinymce_enabled == 1}<script type='text/javascript'>advanced();</script>{/if}
{include file="_opentable.tpl" name=$_name title=$locale.508 state=$_state style=$_style}
<form name='selectform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<center>
		<select name='article_id' class='textbox' style='width:400px;'>
		{section name=id loop=$articles}
			<option value='{$articles[id].article_id}{if $articles[id].selected} selected{/if}'>{$articles[id].article_subject}</option>
		{/section}
		</select>
		&nbsp;
		<input type='submit' name='edit' value='{$locale.509}' class='button' />
		<input type='submit' name='delete' value='{$locale.510}' onclick='return DeleteArticle();' class='button' />
	</center>
</form>
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$title state=$_state style=$_style}
<form name='inputform' method='post' action='{$action}' onSubmit='return ValidateForm(this)'>
	<table align='center' cellpadding='0' cellspacing='0' width='90%'>
		<tr>
			<td align='center' class='tbl'>
				{$locale.511} <input type='text' name='subject' value='{$subject}' class='textbox' style='width: 225px'>&nbsp;&nbsp;&nbsp;{$locale.511}
				<select name='article_cat' class='textbox' style='width: 225px'>
					{section name=id loop=$catlist}
						<option value='{$catlist[id].article_cat_id}{if $catlist[id].selected} selected{/if}'>{$catlist[id].article_cat_name}</option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.513}
				<br /><br />
				<textarea name='body' cols='95' rows='10' class='{if $settings.tinymce_enabled != 1}textbox{/if}' style='width:100%; height:{math equation='x/5' format="%u" x=$smarty.const.BROWSER_HEIGHT}px'>{$body}</textarea>
			</td>
		</tr>
		{if $settings.tinymce_enabled != 1}
		<tr>
			<td class='tbl'>
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick="addText('body', '<b>', '</b>');">
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick="addText('body', '<i>', '</i>');">
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick="addText('body', '<u>', '</u>');">
				<input type='button' value='link' class='button' style='width:35px;' onClick="addText('body', '<a href=\'', '\' target=\'_blank\'>Link</a>');">
				<input type='button' value='img' class='button' style='width:35px;' onClick="addText('body', '<img src=\'{$img_src}\' style=\'margin:5px;\' align=\'left\' />');">
				<input type='button' value='center' class='button' style='width:45px;' onClick="addText('body', '<center>', '</center>');">
				<input type='button' value='small' class='button' style='width:40px;' onClick="addText('body', '<span class=\'small\'>', '</span>');">
				<input type='button' value='small2' class='button' style='width:45px;' onClick="addText('body', '<span class=\'small2\'>', '</span>');">
				<input type='button' value='alt' class='button' style='width:25px;' onClick="addText('body', '<span class=\'alt\'>', '</span>');"><br>
				<select name='setcolor' class='textbox' style='margin-top:5px;' onChange="addText('body', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;">
					<option value=''>{$locale.420}</option>
					<option value='maroon' style='color:maroon;'>Maroon</option>
					<option value='red' style='color:red;'>Red</option>
					<option value='orange' style='color:orange;'>Orange</option>
					<option value='brown' style='color:brown;'>Brown</option>
					<option value='yellow' style='color:yellow;'>Yellow</option>
					<option value='green' style='color:green;'>Green</option>
					<option value='lime' style='color:lime;'>Lime</option>
					<option value='olive' style='color:olive;'>Olive</option>
					<option value='cyan' style='color:cyan;'>Cyan</option>
					<option value='blue' style='color:blue;'>Blue</option>
					<option value='navy' style='color:navy;'>Navy Blue</option>
					<option value='purple' style='color:purple;'>Purple</option>
					<option value='violet' style='color:violet;'>Violet</option>
					<option value='black' style='color:black;'>Black</option>
					<option value='gray' style='color:gray;'>Gray</option>
					<option value='silver' style='color:silver;'>Silver</option>
					<option value='white' style='color:white;'>White</option>
				</select>
				<select name='insertimage' class='textbox' style='margin-top:5px;' onChange="insertText('body', '<img src=\'{$img_src}' + this.options[this.selectedIndex].value + '\' style=\'margin:5px;\' align=\'left\'>');this.selectedIndex=0;">
					<option value=''>{$locale.421}</option>
					{foreach from=$image_files item=file}
						<option value='{$file}'>{$file}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		{/if}
		<tr>
			<td class='tbl'>
				<br />
				{$locale.413}
				<br /><br />
				<textarea name='body2' cols='95' rows='10' class='{if $settings.tinymce_enabled != 1}textbox{/if}' style='width:100%; height:{math equation='x/2' format="%u" x=$smarty.const.BROWSER_HEIGHT}px'>{$body2}</textarea>
			</td>
		</tr>
		{if $settings.tinymce_enabled != 1}
		<tr>
			<td class='tbl'>
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick="addText('body2', '<b>', '</b>');">
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick="addText('body2', '<i>', '</i>');">
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick="addText('body2', '<u>', '</u>');">
				<input type='button' value='link' class='button' style='width:35px;' onClick="addText('body2', '<a href=\'', '\' target=\'_blank\'>Link</a>');">
				<input type='button' value='img' class='button' style='width:35px;' onClick="addText('body2', '<img src=\'{$img_src}\' style=\'margin:5px;\' align=\'left\' />');">
				<input type='button' value='center' class='button' style='width:45px;' onClick="addText('body2', '<center>', '</center>');">
				<input type='button' value='small' class='button' style='width:40px;' onClick="addText('body2', '<span class=\'small\'>', '</span>');">
				<input type='button' value='small2' class='button' style='width:45px;' onClick="addText('body2', '<span class=\'small2\'>', '</span>');">
				<input type='button' value='alt' class='button' style='width:25px;' onClick="addText('body2', '<span class=\'alt\'>', '</span>');"><br>
				<select name='setcolor' class='textbox' style='margin-top:5px;' onChange="addText('body2', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;">
					<option value=''>{$locale.420}</option>
					<option value='maroon' style='color:maroon;'>Maroon</option>
					<option value='red' style='color:red;'>Red</option>
					<option value='orange' style='color:orange;'>Orange</option>
					<option value='brown' style='color:brown;'>Brown</option>
					<option value='yellow' style='color:yellow;'>Yellow</option>
					<option value='green' style='color:green;'>Green</option>
					<option value='lime' style='color:lime;'>Lime</option>
					<option value='olive' style='color:olive;'>Olive</option>
					<option value='cyan' style='color:cyan;'>Cyan</option>
					<option value='blue' style='color:blue;'>Blue</option>
					<option value='navy' style='color:navy;'>Navy Blue</option>
					<option value='purple' style='color:purple;'>Purple</option>
					<option value='violet' style='color:violet;'>Violet</option>
					<option value='black' style='color:black;'>Black</option>
					<option value='gray' style='color:gray;'>Gray</option>
					<option value='silver' style='color:silver;'>Silver</option>
					<option value='white' style='color:white;'>White</option>
				</select>
				<select name='insertimage' class='textbox' style='margin-top:5px;' onChange="insertText('body2', '<img src=\'{$img_src}' + this.options[this.selectedIndex].value + '\' style=\'margin:5px;\' align=\'left\'>');this.selectedIndex=0;">
					<option value=''>{$locale.421}</option>
					{foreach from=$image_files item=file}
						<option value='{$file}'>{$file}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		{/if}
		<tr>
			<td align='center' class='tbl'>
			{if $settings.tinymce_enabled != 1}
				<input type='checkbox' name='line_breaks' value='yes'{if $breaks} checked{/if} /> {$locale.417}&nbsp;&nbsp;&nbsp;
			{/if}
				<input type='checkbox' name='article_comments' value='yes'{if $comments} checked{/if} /> {$locale.423}&nbsp;&nbsp;&nbsp;
				<input type='checkbox' name='article_ratings' value='yes'{if $ratings} checked{/if} /> {$locale.424}
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='preview' value='{$locale.515}' class='button' />
				<input type='submit' name='save' value='{$locale.516}' class='button' />
			</td>
		</tr>
	</table>
</form>
<script type='text/javascript'>
	function DeleteArticle() {ldelim}
		return confirm('{$locale.552}');
	{rdelim}
	function ValidateForm(frm) {ldelim}
		if(frm.subject.value=='') {ldelim}
			alert('{$locale.550}');
			return false;
		{rdelim}
	{rdelim}
</script>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}