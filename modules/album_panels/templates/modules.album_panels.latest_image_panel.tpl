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
{* This template displays the latest image from the albums and galleries   *}
{*                                                                         *}
{***************************************************************************}
{include file="_openside.tpl" name=$_name title=$_title state=$_state style='side-body-nm'}
<div id='latest_image_div' style='text-align:center;margin:0;padding:0;'>
	{if $image.album_id}
		<a href='{$smarty.const.BASEDIR}albums.php?type=photo&amp;action=view&amp;album_id={$image.album_id}&amp;photo_id={$image.photo_id}'><img id='latest_image' width='10' style='margin:0;padding:0;' class='' src='{$smarty.const.PHOTOS}{$image.photo_sized}' alt='{$image.album_photo_description}' title='{$image.album_photo_description}' /></a>
	{/if}
	{if $image.gallery_id}
		<a href='{$smarty.const.BASEDIR}albums.php?type=photo&amp;action=view&amp;gallery_id={$image.gallery_id}&amp;photo_id={$image.photo_id}'><img id='latest_image' width='10' style='margin:0;padding:0;' class='' src='{$smarty.const.PHOTOS}{$image.photo_sized}' alt='{$image.gallery_photo_description}' title='{$image.gallery_photo_description}' /></a>
	{/if}
</div>
{include file="_closeside.tpl"}
<script type='text/javascript'>
{literal}
// Dean Edwards/Matthias Miller/John Resig
var latestimage_done = false;
function latestimage() {
	// quit if this function has already been called
	if (latestimage_done) return;

	// flag this function so we don't do the same thing twice
	latestimage_done = true;

	// kill the timer
	if (_timer) clearInterval(_timer);

	// needed inside the loop
	var parent_left = 0;
	var parent_width = 0;
	var obj = 1;
	var i = 1;

	if (document.getElementById("latest_image_div") != null) {
		// get the info about the objects parent
		obj = document.getElementById("latest_image_div").parentNode;
		// fall back gracefully if the parentNode can not be found
		if (obj == null) obj = document.getElementById("latest_image_div").offsetParent;
		// adjust the width of the image
		document.getElementById("latest_image").style.width = obj.offsetWidth + "px";
	}
};

// add an onload event
addOnloadEvent( latestimage );
{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
