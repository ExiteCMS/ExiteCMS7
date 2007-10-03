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
			<td width='50%' class='tbl'>
				{$locale.601}
				<br />
				<span class='small2'>{$locale.604}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='thumb_w' value='{$settings2.thumb_w}' maxlength='3' class='textbox' style='width:40px;' /> x
				<input type='text' name='thumb_h' value='{$settings2.thumb_h}' maxlength='3' class='textbox' style='width:40px;' />
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
			<td width='50%' class='tbl'>
				{$locale.609}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='thumbs_per_row' value='{$settings2.thumbs_per_row}' maxlength='2' class='textbox' style='width:40px;' />
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