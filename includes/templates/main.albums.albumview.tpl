{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.albums.albumview.tpl                            *}
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
{* Template for the main module 'albums', view album photo's               *}
{*                                                                         *}
{***************************************************************************}
<table width='100%' class='tbl-border' cellspacing='1' cellpadding='0'>
	<tr>
		<td width='33%' align='left' class='tbl1'>
			{if $album.previous}
				{if $album.previous_type == "gallery"}
					{buttonlink name=$locale.479 link=$smarty.const.FUSION_SELF|cat:"?type=gallery&amp;action=view&amp;gallery_id="|cat:$album.previous}
				{elseif $album.previous_type == "album"}
					{buttonlink name=$locale.480 link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=view&amp;album_id="|cat:$album.previous}
				{/if}
			{/if}
		</td>
		<td width='34%' align='center' class='tbl1'>
			{buttonlink name=$locale.481 link=$smarty.const.FUSION_SELF}	
		</td>
		<td width='33%' align='right' class='tbl1'>
			{if $album.next}
				{if $album.next_type == "gallery"}
					{buttonlink name=$locale.482 link=$smarty.const.FUSION_SELF|cat:"?type=gallery&amp;action=view&amp;gallery_id="|cat:$album.next}
				{elseif $album.next_type == "album"}
					{buttonlink name=$locale.483 link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=view&amp;album_id="|cat:$album.next}
				{/if}
			{/if}
		</td>
	</tr>
</table>
<br />
{if $rows > $settings.thumbs_per_page}
	{makepagenav start=$rowstart count=$settings.thumbs_per_page total=$rows range=$settings.navbar_range link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=view&amp;album_id="|cat:$album.album_id|cat:"&amp;"}
{/if}
{math equation="(100-x)/x" x=$columns format="%u" assign="colwidth"}							{* width per column  *}
{if $columns == 1}{assign var="colcount" value="1"}
{elseif $columns == 2}{assign var="colcount" value="1,2"}
{elseif $columns == 3}{assign var="colcount" value="1,2,3"}
{/if}
{section name=id loop=$photos}
	{cycle name=column values=$colcount assign="column" print=no} 										{* keep track of the current column *}
	{if $smarty.section.id.first}
		{math equation="x - (x%y)" x=$photos|@count y=$columns format="%u" assign="fullrows"}
		{math equation="x - y" x=$photos|@count y=$fullrows format="%u" assign="remainder"}
		{if $remainder > 0}
			{math equation="(100 - z + y) / (z - y)" y=$fullrows z=$photos|@count format="%u" assign="lastwidth"}	{* width last rows columns *}
		{/if}
		<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	{/if}
	{if $column == 1}<tr>{/if}
		<td width='1%' class='tbl1' align='center' style='vertical-align:middle'>
			<a href='{$smarty.const.FUSION_SELF}?type=photo&amp;action=view&amp;album_id={$album.album_id}&amp;photo_id={$photos[id].photo_id}'>
				<img src='{$smarty.const.IMAGES}albums/{$photos[id].photo_thumb}' alt='{$photos[id].album_photo_title}' title='{$photos[id].album_photo_title}' class='album_thumb' />
			</a>
		</td>
		{if $smarty.section.id.last && $smarty.section.id.iteration > $fullrows}
			<td width='{$lastwidth}%' colspan='{math equation="1+(x-y)*2" x=$columns y=$remainder}' class='tbl1' style='vertical-align:top'>
		{else}
			<td width='{$colwidth}%' class='tbl1' style='vertical-align:top'>
		{/if}
				<table width='100%' cellspacing='0' cellpadding='0' class='infobar' style='line-height:1.2em;'>
				    <tr>
				        <td align='left'>
							<a href='{$smarty.const.FUSION_SELF}?type=photo&amp;action=view&amp;album_id={$album.album_id}&amp;photo_id={$photos[id].photo_id}'>{$photos[id].album_photo_title}</a>
				        </td>
				        <td align='right'>
							{if $photos[id].can_edit}
								{imagelink link=$smarty.const.FUSION_SELF|cat:"?type=photo&amp;action=highlight&amp;photo_id="|cat:$photos[id].photo_id|cat:"&amp;album_id="|cat:$album.album_id|cat:"&amp;rowstart="|cat:$rowstart image="photo_add.gif" alt=$locale.508 title=$locale.508}
								{imagelink link=$smarty.const.FUSION_SELF|cat:"?type=photo&amp;action=view&amp;photo_id="|cat:$photos[id].photo_id|cat:"&amp;album_id="|cat:$album.album_id image="picture_edit.gif" alt=$locale.485 title=$locale.485}
								{imagelink link=$smarty.const.FUSION_SELF|cat:"?type=photo&amp;action=delete&amp;photo_id="|cat:$photos[id].photo_id|cat:"&amp;album_id="|cat:$album.album_id image="picture_delete.gif" alt=$locale.486 title=$locale.486}
							{/if}
				        </td>
				    </tr>
				    <tr>
				        <td colspan='2'>
							<span class='small2'>
								<b>{$locale.478}</b> {$photos[id].photo_datestamp|date_format:'%B %e, %Y'}
								&middot; <b>{$locale.460}</b> {$photos[id].photo_thumb_count}
							</span>
				        </td>
				    </tr>
				</table>
				{if $album.galleries}
					<div style='margin-top:5px;'>
						<form name='inputform{$smarty.section.id.index}' method='post' action='{$smarty.const.FUSION_SELF}?type=album&amp;action=view&amp;album_id={$album.album_id}&amp;rowstart={$rowstart}'>
							<input type='hidden' name='photo_id' value='{$photos[id].photo_id}' />
							<input type='hidden' name='photo_title' value='{$photos[id].album_photo_title}' />
							{$locale.509} <select id='gallery' name='gallery_id' class='textbox'>
							{foreach from=$album.collection item=item}
								{if $item.type == "gallery"}
									<option value='{$item.id}'>{$item.title}</option>
								{/if}
							{/foreach}
							</select>
							<input type='submit' class='button' name='assign' value='{$locale.510}' />
							<hr />
						</form>
					</div>
				{/if}
				{if $photos[id].album_photo_description|default:"" != ""}
					<span class='small'>{$photos[id].parsed_album_photo_description}</span>
				{elseif $photos[id].can_edit}
					<span class='small'>{$locale.487}</span>
				{/if}
			</td>
		{if $column == $columns}</tr>{/if}
		{if $smarty.section.id.last}
			{if $column != $columns}
				</tr>
			{/if}
		</table>
		{if $rows > $settings.thumbs_per_page}
			{makepagenav start=$rowstart count=$settings.thumbs_per_page total=$rows range=$settings.navbar_range link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=view&amp;album_id="|cat:$album.album_id|cat:"&amp;"}
		{/if}
	{/if}
{sectionelse}
	<center>
		<br />
		<b>{$locale.511}</b>
		<br />
	</center>
{/section}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
