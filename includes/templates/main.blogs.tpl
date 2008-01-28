{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.blogs.tpl                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-91-27 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'blogs'                                    *}
{*                                                                         *}
{***************************************************************************}
{if $step == "add" || $step == "edit"}
	{if $settings.tinymce_enabled == 1}<script type='text/javascript'>advanced();</script>{/if}
	{if $step == "add"}
		{include file="_opentable.tpl" name=$_name title=$locale.402 state=$_state style=$_style}
	{elseif $step == "edit"}
		{include file="_opentable.tpl" name=$_name title=$locale.403 state=$_state style=$_style}
	{/if}
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}' onSubmit='return ValidateForm(this);'>
		<table align='center' cellpadding='0' cellspacing='0' width='90%'>
			<tr>
				<td align='center' class='tbl'>
					<br />
					{$locale.411} <input type='text' name='blog_subject' value='{$blog_subject}' class='textbox' style='width: 350px'>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{if $step == "add"}
						{$locale.402}:
					{elseif $step == "edit"}
						{$locale.403}:
					{/if}
					<br /><br />
					<textarea name='blog_text' cols='95' rows='10' class='{if $settings.tinymce_enabled !=1}textbox{/if}' style='width:100%; height:{math equation='x/3' format="%u" x=$smarty.const.BROWSER_HEIGHT}px'>{$blog_text}</textarea>
				</td>
			</tr>
			{if $settings.tinymce_enabled != 1}
			<tr>
				<td class='tbl'>
					<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick="addText('blog_text', '<b>', '</b>');">
					<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick="addText('blog_text', '<i>', '</i>');">
					<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick="addText('blog_text', '<u>', '</u>');">
					<input type='button' value='link' class='button' style='width:35px;' onClick="addText('blog_text', '<a href=\'', '\' target=\'_blank\'>Link</a>');">
					<input type='button' value='img' class='button' style='width:35px;' onClick="addText('blog_text', '<img src=\'{$img_src}\' style=\'margin:5px;\' align=\'left\' />');">
					<input type='button' value='center' class='button' style='width:45px;' onClick="addText('blog_text', '<center>', '</center>');">
					<input type='button' value='small' class='button' style='width:40px;' onClick="addText('blog_text', '<span class=\'small\'>', '</span>');">
					<input type='button' value='small2' class='button' style='width:45px;' onClick="addText('blog_text', '<span class=\'small2\'>', '</span>');">
					<input type='button' value='alt' class='button' style='width:25px;' onClick="addText('blog_text', '<span class=\'alt\'>', '</span>');"><br>
					<select name='setcolor' class='textbox' style='margin-top:5px;' onChange="addText('blog_text', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;">
						<option value=''>{$locale.413}</option>
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
					<select name='insertimage' class='textbox' style='margin-top:5px;' onChange="insertText('blog_text', '<img src=\'{$img_src}' + this.options[this.selectedIndex].value + '\' style=\'margin:5px;\' align=\'left\'>');this.selectedIndex=0;">
						<option value=''>{$locale.414}</option>
						{foreach from=$image_files item=file}
							<option value='{$file}'>{$file}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			{/if}
			<tr>
				<td align='center' class='tbl'>
					<br />
					<table align='center' cellpadding='0' cellspacing='0'>
						{if $blog_id}
						<tr>
							<td align='right' class='tbl'>
								{$locale.415}
							</td>
							<td align='left' class='tbl'>
								<select name='blog_datestamp[mday]' class='textbox'>
									<option>--</option>
									{section name=day start=1 loop=32}
										<option {if $blog_datestamp.mday == $smarty.section.day.index}selected{/if}>{$smarty.section.day.index}</option>
									{/section}
								</select>
								<select name='blog_datestamp[mon]' class='textbox'>
									<option>--</option>
									{section name=month start=1 loop=13}
										<option {if $blog_datestamp.mon == $smarty.section.month.index}selected{/if}>{$smarty.section.month.index}</option>
									{/section}
								</select>
								<select name='blog_datestamp[year]' class='textbox'>
									<option>--</option>
									{assign var='year' value=$smarty.now|date_format:"%Y"}
									{section name=year start=2000 loop=$year+2}
										<option {if $blog_datestamp.year == $smarty.section.year.index}selected{/if}>{$smarty.section.year.index}</option>
									{/section}
								</select>
								<select name='blog_datestamp[hours]' class='textbox'>
									<option>--</option>
									{section name=hours start=1 loop=25}
										<option {if $blog_datestamp.hours == $smarty.section.hours.index}selected{/if}>{$smarty.section.hours.index}</option>
									{/section}
								</select>
								<select name='blog_datestamp[minutes]' class='textbox'>
									<option>--</option>
									{section name=minutes start=0 loop=61}
										<option {if $blog_datestamp.minutes === $smarty.section.minutes.index}selected{/if}>{$smarty.section.minutes.index}</option>
									{/section}
								</select> : 00
							</td>
						</tr>
						{/if}
						<tr>
							<td align='center' class='tbl'>
								&nbsp;
							</td>
							<td align='left' class='tbl'>
								{if $settings.tinymce_enabled != 1}
									<input type='checkbox' name='blog_breaks' value='yes'{if $blog_breaks} checked{/if}/> {$locale.416}<br />
								{/if}
								<input type='checkbox' name='blog_comments' value='yes' onClick='SetRatings();' {if $blog_comments} checked{/if}> {$locale.417}<br />
								<input type='checkbox' name='blog_ratings' value='yes' {if $blog_ratings} checked{/if}/> {$locale.418}
							</td>
						</tr>
						<tr>
							<td colspan='2' align='center' class='tbl'>
								<br />
								<input type='hidden' name='blog_id' value='{$blog_id}'/>
								<input type='submit' name='save' value='{if $blog_id}{$locale.428}{else}{$locale.419}{/if}' class='button' />
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
{else}
	{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
	<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
		<tr>
			<td align='center' valign='top' class='tbl1'>
				{section name=id loop=$bloglist}
					{if $bloglist[id].blog_author == $userdata.user_id || $is_moderator}
						<form name='editblog{$bloglist[id].blog_id}' method='post' action='blogs.php?step=edit&amp;blog_id={$bloglist[id].blog_id}'>
					{/if}
					<table width='100%'>
						<tr>
							<td align='left' class='infobar'>
								<a href='blogs.php?blog_id={$bloglist[id].blog_id}'>
									<b>{$bloglist[id].blog_subject}</b>
								</a>
							</td>
						</tr>
						<tr>
							<td style='width:100%;vertical-align:top;'>
								{if $blog_id|default:0 == 0}
									{assign var="rl" value=" <a href='blogs.php?blog_id="|cat:$bloglist[id].blog_id|cat:"'>...</a>"}
									{$bloglist[id].blog_text|truncate:500:$rl}<br />
								{else}
									{$bloglist[id].blog_text}<br />
								{/if}
							</td>
						</tr>
						<tr>
							<td align='center' class='infobar'>
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> {$locale.420}
								{if $smarty.const.iMEMBER}<a href='profile.php?lookup={$bloglist[id].blog_author}'>{/if}
								{$bloglist[id].user_name}{if $smarty.const.iMEMBER}</a>{/if}
								{$locale.421} {$bloglist[id].blog_datestamp|date_format:"longdate"}
								&middot; <a href='blogs.php?blog_id={$bloglist[id].blog_id}'>{$locale.422}</a> &middot;
								{if $bloglist[id].comments}
									<a href='blogs.php?blog_id={$bloglist[id].blog_id}#comments'>{$bloglist[id].comments} {$locale.423}</a> &middot;
								{/if}
								 {$bloglist[id].blog_reads} {$locale.424} &middot;
								{imagelink link="print.php?type=B&amp;item_id="|cat:$bloglist[id].blog_id image="printer.gif" alt=$locale.045 title=$locale.045 style="border:0px;vertical-align:middle;"}
								{if $bloglist[id].blog_author == $userdata.user_id || $is_moderator}
									&middot; {imagelink link="document.editblog"|cat:$bloglist[id].blog_id|cat:".submit();" script="yes" image="page_edit.gif" alt=$locale.048 title=$locale.048 style="border:0px;vertical-align:middle;"}
									&middot; {imagelink link=$smarty.const.FUSION_SELF|cat:"?blog_id="|cat:$bloglist[id].blog_id|cat:"&amp;step=delete" image="page_delete.gif" alt=$locale.425 title=$locale.425 onclick="return DeleteBlog();" style="border:0px;vertical-align:middle;"}
								{/if}
								<img src='{$smarty.const.THEME}images/bulletb.gif' alt='' />
							</td>
						</tr>
						{if $bloglist[id].blog_editor}
						<tr>
							<td style='width:100%;vertical-align:bottom;text-align:center;font-style:italic;' class='smallalt'>
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> {$locale.426}
								{if $smarty.const.iMEMBER}<a href='profile.php?lookup={$bloglist[id].blog_editor}'>{/if}
								{$bloglist[id].edit_name}{if $smarty.const.iMEMBER}</a>{/if}
								{$locale.421} {$bloglist[id].blog_edittime|date_format:"longdate"}								
								<img src='{$smarty.const.THEME}images/bulletb.gif' alt='' />
							</td>
						</tr>
						{/if}
					</table>
					{if $bloglist[id].blog_author == $userdata.user_id || $is_moderator}
						</form>
					{/if}
				 	{if !$smarty.section.id.last}
				 		<hr />
				 	{/if}
				{sectionelse}
					<table width='100%'>
						<tr>
							<td align='center'>
								{$locale.499}
							</td>
						</tr>
					</table>
				{/section}
			</td>
			<td align='center' valign='top' class='tbl1' width='145'>
			{if $is_author}
				<table cellpadding='0' cellspacing='0' width='100%'>
					<tr>
						<td align='center' class='infobar'>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align='center' class='tbl1'>
							{buttonlink name=$locale.402 link="blogs.php"|cat:"?step=add"}
						</td>
					</tr>
				</table>
			{/if}
			<table cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td align='center' class='infobar'>
						{$locale.427}
					</td>
				</tr>
			</table>
			{section name=id loop=$list}
				<table cellpadding='0' cellspacing='0' width='100%'>
					<tr>
						<td align='left' class='tbl2'>
							<b><a href='{$smarty.const.FUSION_SELF}?author_id={$list[id].blog_author}'>{$list[id].user_name} ({$list[id].count})</a></b>
						</td>
						<td align='right' class='tbl2' width='1%'>
							{if $list[id].blog_author == $author_id}
								<img src='{$smarty.const.THEME}images/panel_off.gif' alt='' name='b_blog{$list[id].blog_author}' onclick="javascript:flipBox('blog{$list[id].blog_author}')" />
							{else}
								<img src='{$smarty.const.THEME}images/panel_on.gif' alt='' name='b_blog{$list[id].blog_author}' onclick="javascript:flipBox('blog{$list[id].blog_author}')" />
							{/if}
						</td>
					</tr>
				</table>
				<div id='box_blog{$list[id].blog_author}' {if $list[id].blog_author == $author_id}{else}style='display:none'{/if}>
					<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
						{section name=id2 loop=$list[id].blogs}
						<tr>
							<td align='left' class='tbl1'>
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.FUSION_SELF}?blog_id={$list[id].blogs[id2].blog_id}'>{$list[id].blogs[id2].blog_subject}</a>
							</td>
						</tr>
						{sectionelse}
						{/section}
					</table>
				</div>
			{sectionelse}
			{/section}
			</td>
		<tr>
	</table>
{/if}
{include file="_closetable.tpl"}
<script type='text/javascript'>
function DeleteBlog() {ldelim}
	return confirm('{$locale.493}');
{rdelim}
function ValidateForm(frm) {ldelim}
	if(frm.blog_subject.value=='') {ldelim}
		alert('{$locale.498}');
		return false;
	{rdelim}
	if(frm.blog_text.value=='') {ldelim}
		alert('{$locale.497}');
		return false;
	{rdelim}
{rdelim}
{literal}
function SetRatings() {
	if (inputform.blog_comments.checked == false) {
		inputform.blog_ratings.checked = false;
		inputform.blog_ratings.disabled = true;
	} else {
		inputform.blog_ratings.disabled = false;
	}
}
{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}