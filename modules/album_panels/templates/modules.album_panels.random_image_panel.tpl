{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: album_panels.random_image.tpl                  *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-10-03 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template displays a random image from the albums and galleries     *}
{*                                                                         *}
{***************************************************************************}
{include file="_openside.tpl" name=$_name title=$_title state=$_state style='side-body-nm'}
<div style='width:100%;text-align:center'>
	{if $image.album_id}
		<a href='{$smarty.const.BASEDIR}albums.php?type=photo&action=view&album_id={$image.album_id}&photo_id={$image.photo_id}'><img width='100%' style='margin:0;padding:0;' class='' src='{$smarty.const.PHOTOS}{$image.photo_sized}' alt='{$image.album_photo_description}' title='{$image.album_photo_description}' /></a>
	{/if}
	{if $image.gallery_id}
		<a href='{$smarty.const.BASEDIR}albums.php?type=photo&action=view&gallery_id={$image.gallery_id}&photo_id={$image.photo_id}'><img width='100%' style='margin:0;padding:0;' class='' src='{$smarty.const.PHOTOS}{$image.photo_sized}' alt='{$image.gallery_photo_description}' title='{$image.gallery_photo_description}' /></a>
	{/if}
</div>
{include file="_closeside.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
