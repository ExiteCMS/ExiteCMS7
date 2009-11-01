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
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}' onsubmit='return ValidateForm(this);'>
		<table align='center' cellpadding='0' cellspacing='0' width='90%'>
			<tr>
				<td align='center' class='tbl'>
					<br />
					{$locale.411} <input type='text' name='blog_subject' value='{$blog_subject}' class='textbox' style='width: 350px' />
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
					<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('blog_text', '<b>', '</b>');" />
					<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('blog_text', '<i>', '</i>');" />
					<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('blog_text', '<u>', '</u>');" />
					<input type='button' value='link' class='button' style='width:35px;' onclick="addText('blog_text', '<a href=\'', '\' target=\'_blank\'>Link</a>');" />
					<input type='button' value='img' class='button' style='width:35px;' onclick="addText('blog_text', '<img src=\'{$img_src}\' style=\'margin:5px;\' align=\'left\' />');" />
					<input type='button' value='center' class='button' style='width:45px;' onclick="addText('blog_text', '<center>', '</center>');" />
					<input type='button' value='small' class='button' style='width:40px;' onclick="addText('blog_text', '<span class=\'small\'>', '</span>');" />
					<input type='button' value='small2' class='button' style='width:45px;' onclick="addText('blog_text', '<span class=\'small2\'>', '</span>');" />
					<input type='button' value='alt' class='button' style='width:25px;' onclick="addText('blog_text', '<span class=\'alt\'>', '</span>');" />
					<br />
					<select name='setcolor' class='textbox' style='width:90px;' onchange="addText('blog_text', '<span style=\'color=' + this.options[this.selectedIndex].value + ']', '</span>');this.selectedIndex=0;">
						<option value=''>{$locale.413}</option>
						{section name=id loop=$fontcolors}
							<option value='{$fontcolors[id].color}' style='color:{$fontcolors[id].color};'>{$fontcolors[id].name|capitalize}</option>
						{/section}
					</select>
					<select name='insertimage' class='textbox' style='margin-top:5px;' onchange="insertText('blog_text', '<img src=\'{$img_src}' + this.options[this.selectedIndex].value + '\' style=\'margin:5px;\' align=\'left\'>');this.selectedIndex=0;">
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
										<option {if $blog_datestamp.mday == $smarty.section.day.index}selected="selected"{/if}>{$smarty.section.day.index}</option>
									{/section}
								</select>
								<select name='blog_datestamp[mon]' class='textbox'>
									<option>--</option>
									{section name=month start=1 loop=13}
										<option {if $blog_datestamp.mon == $smarty.section.month.index}selected="selected"{/if}>{$smarty.section.month.index}</option>
									{/section}
								</select>
								<select name='blog_datestamp[year]' class='textbox'>
									<option>--</option>
									{assign var='year' value=$smarty.now|date_format:"%Y"}
									{section name=year start=2000 loop=$year+2}
										<option {if $blog_datestamp.year == $smarty.section.year.index}selected="selected"{/if}>{$smarty.section.year.index}</option>
									{/section}
								</select>
								<select name='blog_datestamp[hours]' class='textbox'>
									<option>--</option>
									{section name=hours start=1 loop=25}
										<option {if $blog_datestamp.hours == $smarty.section.hours.index}selected="selected"{/if}>{$smarty.section.hours.index}</option>
									{/section}
								</select>
								<select name='blog_datestamp[minutes]' class='textbox'>
									<option>--</option>
									{section name=minutes start=0 loop=61}
										<option {if $blog_datestamp.minutes === $smarty.section.minutes.index}selected="selected"{/if}>{$smarty.section.minutes.index}</option>
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
									<input type='checkbox' name='blog_breaks' value='yes'{if $blog_breaks} checked="checked"{/if} /> {$locale.416}<br />
								{/if}
								<input type='checkbox' name='blog_comments' value='yes' onclick='SetRatings();' {if $blog_comments} checked="checked"{/if} /> {$locale.417}<br />
								<input type='checkbox' name='blog_ratings' value='yes' {if $blog_ratings} checked="checked"{/if} /> {$locale.418}
							</td>
						</tr>
						<tr>
							<td colspan='2' align='center' class='tbl'>
								<br />
								<input type='hidden' name='blog_id' value='{$blog_id}' />
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
								{if $blog_id|default:0 != 0}
									{$bloglist[id].blog_text}
								{else}
									{assign var="readmore" value="<div class='small' style='float:right;'><a href='blogs.php?blog_id="|cat:$bloglist[id].blog_id|cat:"' alt='"|cat:$locale.422|cat:"' title='"|cat:$locale.422|cat:"'>"|cat:$locale.042|cat:"</a>...</div>"}
									{$bloglist[id].blog_text|trimhtml:500:$readmore}
								{/if}
							</td>
						</tr>
						<tr>
							<td align='center' class='infobar'>
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> {$locale.420}
								{if $smarty.const.iMEMBER}<a href='profile.php?lookup={$bloglist[id].blog_author}'>{/if}
								{$bloglist[id].user_name}{if $smarty.const.iMEMBER}</a>{/if}
								{$locale.421} {$bloglist[id].blog_datestamp|date_format:"longdate"}
								{if $bloglist[id].read_more && $blog_id|default:0 == 0}&middot; <a href='blogs.php?blog_id={$bloglist[id].blog_id}'>{$locale.422}</a>{/if}
								<img src='{$smarty.const.THEME}images/bulletb.gif' alt='' />
								<br />
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
								{if $bloglist[id].blog_comments && $bloglist[id].comments > 0}
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
			<td align='center' valign='top' class='tbl1' width='150'>
				<table cellpadding='0' cellspacing='0' width='100%'>
					<tr>
						<td align='left' class='infobar'>
							<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
							<a href='{$smarty.const.FUSION_SELF}'>{$locale.429}</a>
						</td>
					</tr>
					{if $is_author}
					<tr>
						<td align='left' class='infobar'>
							<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
							<a href='{$smarty.const.FUSION_SELF}?step=add'>{$locale.402}</a>
						</td>
					</tr>
					{/if}
				</table>
				<hr />
				{if !$author_filter}
					<table cellpadding='0' cellspacing='0' width='100%'>
						<tr>
							<td align='center' class='infobar'>
								{$locale.427}

							</td>
						</tr>
					</table>
				{/if}
				{section name=id loop=$list}
					<table cellpadding='0' cellspacing='1' width='100%' class='{if $author_filter}infobar{else}tbl-border{/if}'>
						<tr>
							<td align='left' class='{if !$author_filter}tbl2{/if}'>
								<div style='width:100px;margin-left:-1px;white-space:nowrap;overflow:hidden;'>
									<a href='{$smarty.const.FUSION_SELF}?author_id={$list[id].blog_author}'>
										{$list[id].user_name} ({$list[id].count})
									</a>
								</div>
							</td>
							{if $author_id == 0}
								<td align='right' class='tbl2' width='1%'>
									{if $list[id].blog_author == $author_id}
										<img src='{$smarty.const.THEME}images/panel_off.gif' alt='' name='b_blog{$list[id].blog_author}' onclick="javascript:flipBox('blog{$list[id].blog_author}')" />
									{else}
										<img src='{$smarty.const.THEME}images/panel_on.gif' alt='' name='b_blog{$list[id].blog_author}' onclick="javascript:flipBox('blog{$list[id].blog_author}')" />
									{/if}
								</td>
							{/if}
						</tr>
					</table>
					<div id='box_blog{$list[id].blog_author}' {if $list[id].blog_author == $author_id}{else}style='display:none'{/if}>
						<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
							{section name=id2 loop=$list[id].blogs}
							<tr>
								<td align='left' class='tbl1'>
									<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
									<a href='{$smarty.const.FUSION_SELF}?author_id={$author_id}&amp;blog_id={$list[id].blogs[id2].blog_id}' title='{$list[id].blogs[id2].blog_subject}'>
										{$list[id].blogs[id2].blog_subject|truncate:20:"...":true}
									</a>
								</td>
							</tr>
							{sectionelse}
							{/section}
						</table>
					</div>
				{sectionelse}
				{/section}
			</td>
		</tr>
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
