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
{* This template displays a random image from the albums and galleries     *}
{*                                                                         *}
{***************************************************************************}
{include file="_openside.tpl" name=$_name title=$_title state=$_state style='side-body-nm'}
<div id='random_image_div' style='text-align:center;margin:0;padding:0;'>
	{if $image.album_id}
		<a href='{$smarty.const.BASEDIR}albums.php?type=photo&amp;action=view&amp;album_id={$image.album_id}&amp;photo_id={$image.photo_id}'><img id='random_image' width='10' style='margin:0;padding:0;' class='' src='{$smarty.const.PHOTOS}{$image.photo_sized}' alt='{$image.album_photo_description}' title='{$image.album_photo_description}' /></a>
	{/if}
	{if $image.gallery_id}
		<a href='{$smarty.const.BASEDIR}albums.php?type=photo&amp;action=view&amp;gallery_id={$image.gallery_id}&amp;photo_id={$image.photo_id}'><img id='random_image' width='10' style='margin:0;padding:0;' class='' src='{$smarty.const.PHOTOS}{$image.photo_sized}' alt='{$image.gallery_photo_description}' title='{$image.gallery_photo_description}' /></a>
	{/if}
</div>
{include file="_closeside.tpl"}
<script type='text/javascript'>
{literal}
// Dean Edwards/Matthias Miller/John Resig
var randomimage_done = false;
function randomimage() {
	// quit if this function has already been called
	if (randomimage_done) return;

	// flag this function so we don't do the same thing twice
	randomimage_done = true;

	// kill the timer
	if (_timer) clearInterval(_timer);

	// needed inside the loop
	var parent_left = 0;
	var parent_width = 0;
	var obj = 1;
	var i = 1;

	if (document.getElementById("random_image_div") != null) {
		// get the info about the objects parent
		obj = document.getElementById("random_image_div").parentNode;
		// fall back gracefully if the parentNode can not be found
		if (obj == null) obj = document.getElementById("random_image_div").offsetParent;
		// adjust the width of the image
		document.getElementById("random_image").style.width = obj.offsetWidth + "px";
	}
};

/* for Mozilla/Opera9 */
if (document.addEventListener) {
	document.addEventListener("DOMContentLoaded", randomimage, false);
}

/* for Internet Explorer */
/*@cc_on @*/
/*@if (@_win32)
	document.write("<script id=__ie_onload_randomimage defer src=javascript:void(0)><\/script>");
	var script = document.getElementById("__ie_onload_randomimage");
	script.onreadystatechange = function() {
		if (this.readyState == "complete") {
			randomimage(); // call the onload handler
		}
	};
/*@end @*/

/* for Safari and Konqueror */
if (/KHTML|WebKit/i.test(navigator.userAgent)) { // sniff
	var _timer = setInterval(function() {
		if (/loaded|complete/.test(document.readyState)) {
			randomimage(); // call the onload handler
		}
	}, 10);
}

/* other alternatives */
if (window.attachEvent) {
	window.attachEvent('onload', randomimage);
} else if (window.addEventListener) {
	window.addEventListener('load', randomimage, false);
}
{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
