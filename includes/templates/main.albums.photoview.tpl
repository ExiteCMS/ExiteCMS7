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
{* Template for the main module 'albums', view an album or gallery photo   *}
{*                                                                         *}
{***************************************************************************}
<script type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/mootools-1.2-core-yc.js"></script>
<script type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/mootools-1.2-more.js"></script> 
<script type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/milkbox.js"></script>
<script type="text/javascript">
var MilkboxHeight = readCookie('height') - 260;
var MilkboxWidth = readCookie('width') - 100;
	window.addEvent('load', function(){ldelim}
	Milkbox.setOptions({ldelim}'max_height':MilkboxHeight, 'max_width':MilkboxWidth{rdelim});
	{rdelim});
</script>
{if $photo.gallery_id}
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}?type={$type}&amp;action=edit&amp;gallery_id={$photo.gallery_id}&amp;photo_id={$photo.photo_id}'>
{elseif $photo.album_id}
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}?type={$type}&amp;action=edit&amp;album_id={$photo.album_id}&amp;photo_id={$photo.photo_id}'>
{/if}
	<table width='100%' class='tbl-border' cellspacing='1' cellpadding='0'>
		<tr>
			<td width='33%' align='left' class='tbl1'>
				{if $photo.previous}
					{if $photo.previous_type == "gallery"}
						{buttonlink name=$locale.452 link=$smarty.const.FUSION_SELF|cat:"?type=photo&amp;action=view&amp;gallery_id="|cat:$photo.gallery_id|cat:"&amp;photo_id="|cat:$photo.previous}
					{elseif $photo.previous_type == "album"}
						{buttonlink name=$locale.452 link=$smarty.const.FUSION_SELF|cat:"?type=photo&amp;action=view&amp;album_id="|cat:$photo.album_id|cat:"&amp;photo_id="|cat:$photo.previous}
					{/if}
				{/if}
			</td>
			<td width='34%' align='center' class='tbl1'>
				{if $photo.gallery_id}
					{buttonlink name=$locale.454 link=$smarty.const.FUSION_SELF|cat:"?type=gallery&amp;action=view&amp;gallery_id="|cat:$photo.gallery_id}	
				{elseif $photo.album_id}
					{buttonlink name=$locale.455 link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=view&amp;album_id="|cat:$photo.album_id}	
				{/if}
			</td>
			<td width='33%' align='right' class='tbl1'>
				{if $photo.next}
					{if $photo.next_type == "gallery"}
						{buttonlink name=$locale.453 link=$smarty.const.FUSION_SELF|cat:"?type=photo&amp;action=view&amp;gallery_id="|cat:$photo.gallery_id|cat:"&amp;photo_id="|cat:$photo.next}
					{elseif $photo.next_type == "album"}
						{buttonlink name=$locale.453 link=$smarty.const.FUSION_SELF|cat:"?type=photo&amp;action=view&amp;album_id="|cat:$photo.album_id|cat:"&amp;photo_id="|cat:$photo.next}
					{/if}
				{/if}
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<a href='javascript:Milkbox.showThisImage("{$smarty.const.PHOTOS}{$photo.photo_original}", "{$photo.parsed_description|escape:"html"}");'>
					{if $photo.gallery_id}
						<img src='{$smarty.const.PHOTOS}{$photo.photo_sized}' alt='{$photo.gallery_photo_title}' title='{$photo.gallery_photo_title}' class='album_thumb' />
					{elseif $photo.album_id}
						<img src='{$smarty.const.PHOTOS}{$photo.photo_sized}' alt='{$photo.album_photo_title}' title='{$photo.album_photo_title}' class='album_thumb' />
					{/if}
				</a>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<b>{$locale.456}</b>
				{if $photo.album_id}
					{if $can_edit}
						<input class='textbox' name='album_photo_title' value='{$photo.album_photo_title}' style='width:300px;' />
					{else}
						{$photo.album_photo_title}
					{/if}
				{elseif $photo.gallery_id}
					{if $can_edit}
						<input class='textbox' name='gallery_photo_title' value='{$photo.gallery_photo_title}' style='width:300px;' />
					{else}
						{$photo.gallery_photo_title}
					{/if}
				{/if}
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<b>{$locale.457}</b> {$photo.photo_name|default:"?"}
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<b>{$locale.458}</b> {$photo.photo_datestamp|date_format:"longdate"}
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<b>{$locale.459}</b>
				{if iMEMBER && $photo.user_id}
					<a href='{$smarty.const.BASEDIR}profile.php?lookup={$photo.user_id}'>{$photo.user_name}</a>
				{else}
					{$photo.user_name|default:$settings.usera}
				{/if}
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<b>{$locale.460}</b> {ssprintf format=$locale.492 var1=$photo.photo_sized_count}
			</td>
		</tr>
		<tr>
			<td align='left' colspan='3' class='tbl1'>
				{if $can_edit}
					<b>{$locale.461}</b><br />
					{if $settings.hoteditor_enabled == 0 || ($smarty.const.iMEMBER && $userdata.user_hoteditor == 0)}
						<textarea name='album_photo_description' cols='80' rows='5' class='textbox' style='width:100%; height:{math equation='x/8' x=$smarty.const.BROWSER_HEIGHT format='%u'}px;'>{if $photo.gallery_id}{$photo.gallery_photo_description}{elseif $photo.album_id}{$photo.album_photo_description}{/if}</textarea>
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
						<br />
						{if $photo.gallery_id}
							{include file="_bbcode_editor.tpl" prefix="photo_" name="gallery_photo_description" id="gallery_photo_description" author="" message=$photo.gallery_photo_description width="100%" height="150px"}
						{elseif $photo.album_id}
							{include file="_bbcode_editor.tpl" prefix="photo_" name="album_photo_description" id="album_photo_description" author="" message=$photo.album_photo_description width="100%" height="150px"}
						{/if}
					{/if}
					<br />
					<div style='text-align:center;'>
						{if $photo.gallery_id}
							<input type='submit' name='save' value='{$locale.462}' class='button' onclick='javascript:get_hoteditor_data("gallery_photo_description", "photo_");' />
							<input type='hidden' name='gallery_photo_id' value='{$photo.gallery_photo_id}' />
						{elseif $photo.album_id}
							<input type='submit' name='save' value='{$locale.462}' class='button' onclick='javascript:get_hoteditor_data("album_photo_description", "photo_");' />
							<input type='hidden' name='album_photo_id' value='{$photo.album_photo_id}' />
						{/if}
					</div>
				{else}
					{$photo.parsed_description}
				{/if}
			</td>
		</tr>
	</table>
</form>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
