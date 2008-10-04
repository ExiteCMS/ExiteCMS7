{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.albums.albumedit.tpl                            *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-09-19 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'albums', add/edit an album                *}
{*                                                                         *}
{***************************************************************************}
		<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}?type={$type}&amp;action={$action}&amp;album_id={$album.album_id}'>
		<table width='100%' cellpadding='0' cellspacing='0'>
			{if $action == "edit"}
			<tr>
				<td class='tbl1'>
					{$locale.512}
				</td>
				<td class='tbl1'>
					{$album.album_id}
					<input type='hidden' name='album_id' value='{$album_id}' />
				</td>
			</tr>
			{/if}
			<tr>
				<td class='tbl1'>
					{$locale.513}
				</td>
				<td class='tbl1'>
					<input class='textbox' name='album_title' value='{$album.album_title}' style='width:300px;' />
				</td>
			</tr>
			{if $action == "edit"}
			<tr>
				<td class='tbl1'>
					{$locale.514}
				</td>
				<td class='tbl1'>
					{ssprintf format=$locale.492 var1=$album.album_count}
				</td>
			</tr>
			{/if}
			<tr>
				<td class='tbl1'>
					{$locale.515}
				</td>
				<td class='tbl1'>
					<img class='album_thumb' src='{$album.photo_thumb}' width='{$album.photo_width}' height='{$album.photo_height}' />
				</td>
			</tr>
			<tr>
				<td class='tbl1'>
					{$locale.494}
				</td>
				<td class='tbl1'>
					{if $is_moderator}
						{section name=id loop=$user_list}
							{if $smarty.section.id.first}
								<select id='album_owner' name='album_owner' class='textbox'>
								{/if}
								<option value='{$user_list[id].user_id}'{if $user_list[id].user_id == $album.album_owner}selected='selected'{/if}>{$user_list[id].user_name}</option>
								{if $smarty.section.id.last}
								</select>
							{/if}
						{/section}
					{else}
						{$album.user_name}
					{/if}
				</td>
			</tr>
			<tr>
				<td class='tbl1'>
					{$locale.495}
				</td>
				<td class='tbl1'>
					<select id='album_read' name='album_read' class='textbox'>
						<option value='-1'{if $album.album_read == -1} selected='selected'{/if}>{$locale.496}</option>
					{section name=id loop=$all_user_groups}
						<option value='{$all_user_groups[id].0}'{if $all_user_groups[id].0 == $album.album_read} selected='selected'{/if}>{$all_user_groups[id].1}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl1'>
					{$locale.497}
				</td>
				<td class='tbl1'>
					<select id='album_write' name='album_write' class='textbox'>
						<option value='-1'{if $album.album_write == -1} selected='selected'{/if}>{$locale.496}</option>
					{if $settings.albums_anonymous}
						{section name=id loop=$all_user_groups}
							<option value='{$all_user_groups[id].0}'{if $all_user_groups[id].0 == $album.album_write} selected='selected'{/if}>{$all_user_groups[id].1}</option>
						{/section}
					{else}
						{section name=id loop=$user_groups}
							<option value='{$user_groups[id].0}'{if $user_groups[id].0 == $album.album_write} selected='selected'{/if}>{$user_groups[id].1}</option>
						{/section}
					{/if}
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl1'>
					{$locale.498}
				</td>
				<td class='tbl1'>
					<select name='album_allow_comments' class='textbox'>
						<option value='1'{if $album.album_allow_comments == "1"} selected="selected"{/if}>{$locale.499}</option>
						<option value='0'{if $album.album_allow_comments == "0"} selected="selected"{/if}>{$locale.500}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl1'>
					{$locale.501}
				</td>
				<td class='tbl1'>
					<select name='album_allow_ratings' class='textbox'>
						<option value='1'{if $album.album_allow_ratings == "1"} selected="selected"{/if}>{$locale.499}</option>
						<option value='0'{if $album.album_allow_ratings == "0"} selected="selected"{/if}>{$locale.500}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl1' colspan='2'>
					{$locale.516}
				</td>
			</tr>
			<tr>
				<td class='tbl1' colspan='2' align='center'>
					{if $settings.hoteditor_enabled == 0 || ($smarty.const.iMEMBER && $userdata.user_hoteditor == 0)}
						<textarea name='album_description' cols='80' rows='5' class='textbox' style='width:100%; height:{math equation='x/8' x=$smarty.const.BROWSER_HEIGHT format='%u'}px;'>{$album.album_description}</textarea>
						<br />
						<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('album_desciption', '[b]', '[/b]');" />
						<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('album_desciption', '[i]', '[/i]');" />
						<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('album_desciption', '[u]', '[/u]');" />
						<input type='button' value='ul' class='button' style='width:25px;' onclick="addText('album_desciption', '[ul]', '[/ul]');" />
						<input type='button' value='li' class='button' style='width:25px;' onclick="addText('album_desciption', '[li]', '[/li]');" />
						<input type='button' value='url' class='button' style='width:30px;' onclick="addURL('album_desciption');" />
						<input type='button' value='img' class='button' style='width:30px;' onclick="addText('album_desciption', '[img]', '[/img]');" />
						<input type='button' value='center' class='button' style='width:45px;' onclick="addText('album_desciption', '[center]', '[/center]');" />
						<input type='button' value='small' class='button' style='width:40px;' onclick="addText('album_desciption', '[small]', '[/small]');" />
					{else}
						{include file="_bbcode_editor.tpl" name="album_description" id="album_description" author="" message=$album.album_description width="100%" height="150px"}
					{/if}
				</td>
			</tr>
			<tr>
				<td class='tbl1' colspan='2' align='center'>
					<input type='hidden' name='album_id' value='{$album.album_id}' />
					<input type='hidden' name='album_highlight' value='{$album.album_highlight}' />
					<input type='hidden' name='album_count' value='{$album.album_count}' />
					<input type='hidden' name='album_datestamp' value='{$album.album_datestamp}' />
					{if $action == "add"}
						<input type='submit' name='save' value='{$locale.517}' class='button' onclick='javascript:get_hoteditor_data("album_description");' />
					{elseif $action == "edit"}
						<input type='submit' name='save' value='{$locale.518}' class='button' onclick='javascript:get_hoteditor_data("album_description");' />
					{/if}
				</td>
			</tr>
		</table>
		</form>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
