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
{* Template for the main module 'albums', image upload form                *}
{*                                                                         *}
{***************************************************************************}
{assign var=upload_limit value=25}
{include file="_fancyupload.tpl" file_size=$settings.photo_max_b file_mask=$file_mask file_desc=$locale.524}
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
</table>
<br />
<table cellpadding='1' cellspacing='1' width='100%' class='tbl-border'>
	<tr>
		<td class='tbl1'>
			{$locale.442} <b>{$album.album_title}</b>
		</td>
		<td class='tbl1'>
			{$locale.443} <b><div id='UploadCounter' style='display:inline;'>{$album.photo_count}</div></b>
		</td>
	</tr>
</table>
<br />
<form action="{$smarty.const.BASEDIR|cat:$smarty.const.FUSION_SELF|cat:"?type=album&action=fancyupload&album_id="|cat:$album.album_id|cat:"&"|cat:$settings.session_name|cat:"="|cat:$session_name|cat:"&FUID="|cat:$session_id}" method="post" enctype="multipart/form-data" id="form-fancyupload">
	<fieldset id="fancyupload-fallback">
		<legend>{$locale.525}</legend>
		<p>
			{$locale.526}<br />
		</p>
		<label for="fancyupload-upload">
			{$locale.527}
			<input type="file" name="upload" id="fancyupload-upload" />
		</label>
	</fieldset>
	<div id="fancyupload-status" class="hide">
		<div>
			<div class="overall-title">{$locale.528}</div>
			<img src="{$smarty.const.FANCYUPLOAD}images/bar.gif" class="progress overall-progress" />
		</div>
		<div>
			<div class="current-title">{$locale.529}</div>
			<img src="{$smarty.const.FANCYUPLOAD}images/bar.gif" class="progress current-progress" />
		</div>
		<div class="current-text"></div>
		<p>
			<a href="#" class="button" style="padding:2px;" id="fancyupload-browse">{$locale.530}</a> 
			<a href="#" class="button" style="padding:2px;" id="fancyupload-clear">{$locale.531}</a> 
			<a href="#" class="button" style="padding:2px;" id="fancyupload-start">{$locale.532}</a>
		</p>
	</div>
	<hr />
	<ul id="fancyupload-list"></ul>
</form>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
