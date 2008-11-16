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
{* Template for the admin configuration module 'settings_forum'            *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl"}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
<table align='center' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.507}
			</td>
			<td width='40%' class='tbl'>
				<select name='attachments' class='textbox'>
					<option value='1'{if $settings2.attachments == "1"} selected="selected"{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.attachments == "0"} selected="selected"{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.510}
				<br />
				<span class='small2'>{$locale.511}</span>
			</td>
			<td width='40%' class='tbl'>
				<input type='text' name='attachmax' value='{$settings2.attachmax}' maxlength='150' class='textbox' style='width:100px;' />
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.512}
				<br />
				<span class='small2'>{$locale.513}</span>
			</td>
			<td width='40%' class='tbl'>
				<input type='text' name='attachtypes' value='{$settings2.attachtypes}' maxlength='150' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.521}
				<br />
				<span class='small2'>{$locale.522}</span>
			</td>
			<td width='40%' class='tbl'>
				<input type='text' name='forum_max_w' value='{$settings2.forum_max_w}' maxlength='4' class='textbox' style='width:50px;' />
				x
				<input type='text' name='forum_max_h' value='{$settings2.forum_max_h}' maxlength='4' class='textbox' style='width:50px;' />
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.519}
			</td>
			<td width='40%' class='tbl'>
				<select name='thread_notify' class='textbox'>
					<option value='1'{if $settings2.thread_notify == "1"} selected="selected"{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.thread_notify == "0"} selected="selected"{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.523}
				<br />
				<span class='small2'>{$locale.524}</span>
			</td>
			<td width='40%' class='tbl'>
				<select name='unread_threshold' class='textbox'>
				{section name=days start=0 loop=361 step=30}
				<option value='{$smarty.section.days.index}' {if $smarty.section.days.index == $settings2.unread_threshold|default:0}selected='selected'{/if}>{if $smarty.section.days.index == 0}{$locale.544}{else}{$smarty.section.days.index} {$locale.518}{/if}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.525}
			</td>
			<td width='40%' class='tbl'>
				<select name='folderhotlevel' class='textbox'>
					{section name=lines start=5 loop=55 step=5}
						<option{if $settings2.folderhotlevel == $smarty.section.lines.index} selected="selected"{/if}>{$smarty.section.lines.index}</option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.534}
			</td>
			<td width='40%' class='tbl'>
				<select name='forum_edit_timeout' class='textbox'>
				<option value='0' {if $settings2.forum_edit_timeout}selected='selected'{/if}>{$locale.714}</option>
				{section name=hours start=1 loop=25}
				<option value='{$smarty.section.hours.index}' {if $settings2.forum_edit_timeout == $smarty.section.hours.index}selected='selected'{/if}>{$smarty.section.hours.index} {if $smarty.section.hours.index == 1}{$locale.535}{else}{$locale.536}{/if}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.543}
			</td>
			<td width='40%' class='tbl'>
				<select name='forum_guest_limit' class='textbox'>
				<option value='0' {if $settings2.forum_guest_limit}selected='selected'{/if}>{$locale.544}</option>
				{section name=days start=1 loop=91}
				<option value='{$smarty.section.days.index}' {if $settings2.forum_guest_limit == $smarty.section.days.index}selected='selected'{/if}>{$smarty.section.days.index} {if $smarty.section.days.index == 1}{$locale.527}{else}{$locale.518}{/if}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.545}
			</td>
			<td width='40%' class='tbl'>
				<select name='forum_user_status' class='textbox'>
					<option value='0'{if $settings2.forum_user_status == "0"} selected="selected"{/if}>{$locale.509}</option>
					<option value='1'{if $settings2.forum_user_status == "1"} selected="selected"{/if}>{$locale.508}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='60%' class='tbl'>
				{$locale.514}
				<br />
				<span class='small2'><font color='red'>{$locale.515}</font> {$locale.516}</span>
			</td>
			<td width='40%' class='tbl'>
				<input type='submit' name='prune' value='{$locale.517}' class='button' />
				<select name='prune_days' class='textbox' style='width:50px;'>
					<option>10</option>
					<option>20</option>
					<option>30</option>
					<option>60</option>
					<option>90</option>
					<option>120</option>
					<option selected="selected">180</option>
				</select>
				{$locale.518} 
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='savesettings' value='{$locale.750}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
