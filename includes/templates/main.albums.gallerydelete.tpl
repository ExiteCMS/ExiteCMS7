{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.albums.gallerydelete.tpl                        *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-09-29 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'albums', delete a gallery                 *}
{*                                                                         *}
{***************************************************************************}
<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}?type=gallery&amp;action=delete&amp;gallery_id={$gallery.gallery_id}'>
	<table width='100%' class='tbl-border' cellspacing='1' cellpadding='0'>
		<tr>
			<td width='33%' align='left' class='tbl1'>
			</td>
			<td width='34%' align='center' class='tbl1'>
				{buttonlink name=$locale.441 link=$smarty.const.FUSION_SELF}	
			</td>
			<td width='33%' align='right' class='tbl1'>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<a href=''>
					<img src='{$gallery.photo_thumb}' alt='{$gallery.album_title}' title='{$gallery.gallery_title}' class='album_thumb' />
				</a>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='3' class='tbl1'>
				<br />
				{ssprintf format=$locale.505 var1=$gallery.gallery_title}
				<input type='submit' name='delete' value='{$locale.506}' class='button' />
				&nbsp;
				<input type='submit' name='cancel' value='{$locale.507}' class='button' />
			</td>
		</tr>
	</table>
</form>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
