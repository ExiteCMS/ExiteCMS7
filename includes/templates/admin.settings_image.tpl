{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.settings_misc.tpl                              *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-22 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin configuration module 'settings_misc'             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.611}
			</td>
			<td class='tbl' width='50%'>
				<select name='albums_create' class='textbox'>
				{section name=id loop=$allusergroups}
					<option value='{$usergroups[id].0}'{if $usergroups[id].0 == $settings2.albums_create} selected="selected"{/if}>{$usergroups[id].1}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.612}
			</td>
			<td class='tbl' width='50%'>
				<select name='albums_moderators' class='textbox'>
				{section name=id loop=$usergroups}
					<option value='{$allusergroups[id].0}'{if $allusergroups[id].0 == $settings2.albums_moderators} selected="selected"{/if}>{$allusergroups[id].1}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='50%'>
				{$locale.614}
			</td>
			<td class='tbl' width='50%'>
				<select name='albums_anonymous' class='textbox'>
					<option value='0' {if $settings2.albums_anonymous == "0"} selected="selected"{/if}>{$locale.509}</option>
					<option value='1' {if $settings2.albums_anonymous == "1"} selected="selected"{/if}>{$locale.508}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.613}
			</td>
			<td width='50%' class='tbl'>
				<select name='albums_columns' class='textbox'>
					<option value='1'{if $settings2.albums_columns == 1} selected="selected"{/if}>{$locale.417}</option>
					<option value='2'{if $settings2.albums_columns == 2} selected="selected"{/if}>{$locale.418}</option>
					<option value='3'{if $settings2.albums_columns == 3} selected="selected"{/if}>{$locale.420}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.609}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='albums_per_page' value='{$settings2.albums_per_page}' maxlength='2' class='textbox' style='width:40px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.610}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='thumbs_per_page' value='{$settings2.thumbs_per_page}' maxlength='2' class='textbox' style='width:40px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.601}
				<br />
				<span class='small2'>{$locale.604}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='thumb_w' value='{$settings2.thumb_w}' maxlength='3' class='textbox' style='width:40px;' /> x
				<input type='text' name='thumb_h' value='{$settings2.thumb_h}' maxlength='3' class='textbox' style='width:40px;' />
				&nbsp;
				<input type='submit' name='newthumbs' value='{$locale.753}' class='button' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.602}
				<br />
				<span class='small2'>{$locale.604}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='photo_w' value='{$settings2.photo_w}' maxlength='3' class='textbox' style='width:40px;' /> x
				<input type='text' name='photo_h' value='{$settings2.photo_h}' maxlength='3' class='textbox' style='width:40px;' />
				&nbsp;
				<input type='submit' name='newphotos' value='{$locale.754}' class='button' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.603}
				<br />
				<span class='small2'>{$locale.604}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='photo_max_w' value='{$settings2.photo_max_w}' maxlength='4' class='textbox' style='width:40px;' /> x
				<input type='text' name='photo_max_h' value='{$settings2.photo_max_h}' maxlength='4' class='textbox' style='width:40px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.605}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='photo_max_b' value='{$settings2.photo_max_b}' maxlength='10' class='textbox' style='width:100px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.606}
			</td>
			<td width='50%' class='tbl'>
				<select name='thumb_compression' class='textbox'>
					<option value='gd1'{if $settings2.thumb_compression == "gd1"} selected="selected"{/if}>{$locale.607}</option>
					<option value='gd2'{if $settings2.thumb_compression == "gd2"} selected="selected"{/if}>{$locale.608}</option>
				</select>
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
