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
{* Template for the main module 'albums', delete an album photo            *}
{*                                                                         *}
{***************************************************************************}
{if $photo.gallery_id}
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}?type=photo&amp;action=delete&amp;gallery_id={$photo.gallery_id}&amp;photo_id={$photo.photo_id}'>
{elseif $photo.album_id}
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}?type=photo&amp;action=delete&amp;album_id={$photo.album_id}&amp;photo_id={$photo.photo_id}'>
{/if}
	<table width='100%' class='tbl-border' cellspacing='1' cellpadding='0'>
		<tr>
			<td width='33%' align='left' class='tbl1'>
			</td>
			<td width='34%' align='center' class='tbl1'>
				{if $photo.gallery_id}
					{buttonlink name=$locale.454 link=$smarty.const.FUSION_SELF|cat:"?type=gallery&amp;action=view&amp;gallery_id="|cat:$photo.gallery_id}	
				{elseif $photo.album_id}
					{buttonlink name=$locale.455 link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=view&amp;album_id="|cat:$photo.album_id}	
				{/if}
			</td>
			<td width='33%' align='right' class='tbl1'>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<a href=''>
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
				<br />
					{if $photo.gallery_id}
						{ssprintf format=$locale.463 var1=$photo.gallery_title}
						<input type='submit' name='delete' value='{$locale.522}' class='button' />
						&nbsp;
						<input type='submit' name='cancel' value='{$locale.507}' class='button' />
					{elseif $photo.album_id}
						{ssprintf format=$locale.464 var1=$photo.gallery_title}
						<input type='submit' name='delete' value='{$locale.522}' class='button' />
						&nbsp;
						<input type='submit' name='cancel' value='{$locale.507}' class='button' />
					{/if}
			</td>
		</tr>
	</table>
</form>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
