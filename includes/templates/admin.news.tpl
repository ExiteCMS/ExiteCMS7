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
{* Template for the admin content module 'news'                            *}
{*                                                                         *}
{***************************************************************************}
{if $settings.tinymce_enabled == 1}<script type='text/javascript'>advanced();</script>{/if}
{include file="_opentable.tpl" name=$_name title=$locale.408 state=$_state style=$_style}
<form name='selectform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<center>
		{if $settings.news_localisation == "multiple"}
			{assign var="url_locale" value="&amp;news_locale="|cat:$news_locale}
			<br />
			{$locale.553} {html_options name=news_locale options=$locales selected=$news_locale class="textbox" onchange="location = '"|cat:$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;news_locale=' + this.options[this.selectedIndex].value;"}
			<br />
		{else}
			{assign var="news_locale" value=""}
		{/if}
		<br />
		<select name='news_id' class='textbox' style='width:400px'>
			{section name=item loop=$news}
				<option value='{$news[item].news_id}' {if $news[item].selected}selected='selected'{/if}>{$news[item].news_subject}</option>
			{/section}
		</select>
		&nbsp;
		<input type='submit' name='edit' value='{$locale.409}' class='button' />
		<input type='submit' name='delete' value='{$locale.410}' onclick='return DeleteNews();' class='button' />
	</center>
</form>
{include file="_closetable.tpl"}
{if $settings.article_localisation == "multiple"}
	{assign var="tabletitle" value=$_title|cat:" "|cat:$locale.554|cat:" '<b>"|cat:$news_locale|cat:"</b>'"}
{else}
	{assign var="tabletitle" value=$_title}
{/if}
{include file="_opentable.tpl" name=$_name title=$tabletitle state=$_state style=$_style}
<form name='inputform' method='post' action='{$action}' onsubmit='return ValidateForm(this);'>
	<table align='center' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td align='center' class='tbl'>
				{$locale.411}
				<input type='text' name='news_subject' value='{$news_subject}' class='textbox' style='width: 225px' />&nbsp;&nbsp;&nbsp;{$locale.511}
				<select name='news_cat' class='textbox' style='width: 225px'>
					<option value='0'>{$locale.425}</option>
					{section name=item loop=$news_cats}
						<option value='{$news_cats[item].news_cat_id}' {if $news_cats[item].selected}selected='selected'{/if}>{$news_cats[item].news_cat_name}</option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.412}
				<br /><br />
				<textarea name='body' cols='95' rows='10' class='{if $settings.tinymce_enabled !=1}textbox{/if}' style='width:100%; height:{math equation='x/5' format="%u" x=$smarty.const.BROWSER_HEIGHT}px'>{$body}</textarea>
			</td>
		</tr>
		{if $settings.tinymce_enabled != 1}
		<tr>
			<td align='center' class='tbl'>
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('body', '<b>', '</b>');">
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('body', '<i>', '</i>');">
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('body', '<u>', '</u>');">
				<input type='button' value='link' class='button' style='width:35px;' onclick="addText('body', '<a href=\'', '\' target=\'_blank\'>Link</a>');">
				<input type='button' value='img' class='button' style='width:35px;' onclick="addText('body', '<img src=\'{$img_src}\' style=\'margin:5px;\' align=\'left\' />');">
				<input type='button' value='center' class='button' style='width:45px;' onclick="addText('body', '<center>', '</center>');">
				<input type='button' value='small' class='button' style='width:40px;' onclick="addText('body', '<span class=\'small\'>', '</span>');">
				<input type='button' value='small2' class='button' style='width:45px;' onclick="addText('body', '<span class=\'small2\'>', '</span>');">
				<input type='button' value='alt' class='button' style='width:25px;' onclick="addText('body', '<span class=\'alt\'>', '</span>');"><br>
				<select name='setcolor' class='textbox' style='margin-top:5px;' onchange="addText('body', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;">
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
				<textarea name='body2' cols='95' rows='10' class='{if $settings.tinymce_enabled !=1}textbox{/if}' style='width:100%; height:{math equation='x/2' format="%u" x=$smarty.const.BROWSER_HEIGHT}px'>{$body2}</textarea>
			</td>
		</tr>
		{if $settings.tinymce_enabled != 1}
		<tr>
			<td align='center' class='tbl'>
				<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('body2', '<b>', '</b>');">
				<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('body2', '<i>', '</i>');">
				<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('body2', '<u>', '</u>');">
				<input type='button' value='link' class='button' style='width:35px;' onclick="addText('body2', '<a href=\'', '\' target=\'_blank\'>Link</a>');">
				<input type='button' value='img' class='button' style='width:35px;' onclick="addText('body2', '<img src=\'{$img_src}\' style=\'margin:5px;\' align=\'left\' />');">
				<input type='button' value='center' class='button' style='width:45px;' onclick="addText('body2', '<center>', '</center>');">
				<input type='button' value='small' class='button' style='width:40px;' onclick="addText('body2', '<span class=\'small\'>', '</span>');">
				<input type='button' value='small2' class='button' style='width:45px;' onclick="addText('body2', '<span class=\'small2\'>', '</span>');">
				<input type='button' value='alt' class='button' style='width:25px;' onclick="addText('body2', '<span class=\'alt\'>', '</span>');"><br>
				<select name='setcolor' class='textbox' style='margin-top:5px;' onchange="addText('body2', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;">
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
				<br />
				<table align='center' cellpadding='0' cellspacing='0'>
					<tr>
						<td align='right' class='tbl'>
							{$locale.414}
						</td>
						<td align='left' class='tbl'>
							<select name='news_start[mday]' class='textbox'>
								<option>--</option>
								{section name=day start=1 loop=32}
									<option {if $news_start.mday == $smarty.section.day.index}selected='selected'{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name='news_start[mon]' class='textbox'>
								<option>--</option>
								{section name=month start=1 loop=13}
									<option {if $news_start.mon == $smarty.section.month.index}selected='selected'{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name='news_start[year]' class='textbox'>
								<option>--</option>
								{assign var='year' value=$smarty.now|date_format:"%Y"}
								{section name=year start=2000 loop=$year+2}
									<option {if $news_start.year == $smarty.section.year.index}selected='selected'{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
							<select name='news_start[hours]' class='textbox'>
								<option>--</option>
								{section name=hours start=1 loop=25}
									<option {if $news_start.hours == $smarty.section.hours.index}selected='selected'{/if}>{$smarty.section.hours.index}</option>
								{/section}
							</select>
							<select name='news_start[minutes]' class='textbox'>
								<option>--</option>
								{section name=minutes start=0 loop=61}
									<option {if $news_start.minutes === $smarty.section.minutes.index}selected='selected'{/if}>{$smarty.section.minutes.index}</option>
								{/section}
							</select> : 00 <span class='small'>{$locale.416}</span>
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.415}
						</td>
						<td align='left' class='tbl'>
							<select name='news_end[mday]' class='textbox'>
								<option>--</option>
								{section name=day start=1 loop=32}
									<option {if $news_end.mday == $smarty.section.day.index}selected='selected'{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name='news_end[mon]' class='textbox'>
								<option>--</option>
								{section name=month start=1 loop=13}
									<option {if $news_end.mon == $smarty.section.month.index}selected='selected'{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name='news_end[year]' class='textbox'>
								<option>--</option>
								{assign var='year' value=$smarty.now|date_format:"%Y"}
								{section name=year start=2000 loop=$year+2}
									<option {if $news_end.year == $smarty.section.year.index}selected='selected'{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
							<select name='news_end[hours]' class='textbox'>
								<option>--</option>
								{section name=hours start=1 loop=25}
									<option {if $news_end.hours == $smarty.section.hours.index}selected='selected'{/if}>{$smarty.section.hours.index}</option>
								{/section}
							</select>
							<select name='news_end[minutes]' class='textbox'>
								<option>--</option>
								{section name=minutes start=0 loop=61}
									<option {if $news_end.minutes === $smarty.section.minutes.index}selected='selected'{/if}>{$smarty.section.minutes.index}</option>
								{/section}
							</select> : 00 <span class='small'>{$locale.416}</span>
						</td>
					</tr>
					{if $news_id}
					<tr>
						<td align='right' class='tbl'>
							{$locale.427}
						</td>
						<td align='left' class='tbl'>
							<select name='news_date[mday]' class='textbox'>
								<option>--</option>
								{section name=day start=1 loop=32}
									<option {if $news_date.mday == $smarty.section.day.index}selected='selected'{/if}>{$smarty.section.day.index}</option>
								{/section}
							</select>
							<select name='news_date[mon]' class='textbox'>
								<option>--</option>
								{section name=month start=1 loop=13}
									<option {if $news_date.mon == $smarty.section.month.index}selected='selected'{/if}>{$smarty.section.month.index}</option>
								{/section}
							</select>
							<select name='news_date[year]' class='textbox'>
								<option>--</option>
								{assign var='year' value=$smarty.now|date_format:"%Y"}
								{section name=year start=2000 loop=$year+2}
									<option {if $news_date.year == $smarty.section.year.index}selected='selected'{/if}>{$smarty.section.year.index}</option>
								{/section}
							</select>
							<select name='news_date[hours]' class='textbox'>
								<option>--</option>
								{section name=hours start=1 loop=25}
									<option {if $news_date.hours == $smarty.section.hours.index}selected='selected'{/if}>{$smarty.section.hours.index}</option>
								{/section}
							</select>
							<select name='news_date[minutes]' class='textbox'>
								<option>--</option>
								{section name=minutes start=0 loop=61}
									<option {if $news_date.minutes === $smarty.section.minutes.index}selected='selected'{/if}>{$smarty.section.minutes.index}</option>
								{/section}
							</select> : 00
						</td>
					</tr>
					{/if}
					<tr>
						<td align='right' class='tbl'>
							{$locale.422}
						</td>
						<td align='left' class='tbl'>
							<select name='news_visibility' class='textbox'>
								{section name=id loop=$usergroups}
									<option value='{$usergroups[id].id}'{if $news_visibility == $usergroups[id].id} selected='selected'{/if}>{$usergroups[id].name}</option>
								{/section}
							</select>
						</td>
					</tr>
					<tr>
						<td align='center' class='tbl'>
							&nbsp;
						</td>
						<td align='left' class='tbl'>
							{if $settings.tinymce_enabled != 1}
								<input type='checkbox' name='line_breaks' value='yes'{if $news_breaks} checked='checked'{/if}/> {$locale.417}<br />
							{/if}
							<input type='checkbox' name='news_comments' value='yes' onclick='SetRatings();' {if $news_comments} checked='checked'{/if}> {$locale.423}<br />
							<input type='checkbox' name='news_ratings' value='yes' {if $news_ratings} checked='checked'{/if}/> {$locale.424}
						</td>
					</tr>
					<tr>
						<td colspan='2' align='center' class='tbl'>
							<input type='hidden' name='news_locale' value='{$news_locale}' />
							<br />
							<input type='submit' name='preview' value='{$locale.418}' class='button' />
							<input type='submit' name='save' value='{$locale.419}' class='button' />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
<script type='text/javascript'>
function DeleteNews() {ldelim}
	return confirm('{$locale.551}');
{rdelim}
function ValidateForm(frm) {ldelim}
	if(frm.news_subject.value=='') {ldelim}
		alert('{$locale.550}');
		return false;
	{rdelim}
{rdelim}
{literal}
function SetRatings() {
	if (inputform.news_comments.checked == false) {
		inputform.news_ratings.checked = false;
		inputform.news_ratings.disabled = true;
	} else {
		inputform.news_ratings.disabled = false;
	}
}
{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
