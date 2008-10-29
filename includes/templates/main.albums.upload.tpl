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
{include file="_swfupload.tpl" upload_url=$smarty.const.BASEDIR|cat:$smarty.const.FUSION_SELF|cat:"?type=album&action=swfupload&album_id="|cat:$album.album_id session_id=$session_id file_size=$settings.photo_max_b|parsebytesize file_mask=$file_mask file_desc=$locale.524 upload_limit=$upload_limit queue_limit=0}
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
			{$locale.443} <b><div id='divUploadCounter' style='display:inline;'>{$album.photo_count}</div></b>
			<input type='hidden' name='UploadCounter' id='UploadCounter' value='{$album.photo_count}' />
		</td>
	</tr>
</table>
<br />
<form id="form1" action="{$smarty.const.FUSION_SELF}" method="post" enctype="multipart/form-data">
	<div id="divSWFUploadUI" style="visibility: hidden;">
		<fieldset id="fsUploadProgress" class="swf_uploadprogress">
			<legend class='swf_legend'>{$locale.444}</legend>
		</fieldset>
		<br />
		<table cellpadding='1' cellspacing='1' width='100%' class='tbl-border'>
			<tr>
				<td class='tbl1'>
					<p id="divStatus">{ssprintf format=$locale.445 var1=$upload_limit}</p>
				</td>
			</tr>
		</table>
		<p>
			<input id="btnBrowse" class="button" type="button" value="{ssprintf format=$locale.446 var1=$settings.photo_max_b|parsebytesize}" />
			<input id="btnCancel" class="button" type="button" value="{$locale.447}" disabled="disabled" />
			<br />
		</p>
	</div>
	<noscript style="display: block; background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px;">
		{$locale.448}
	</noscript>
	<div id="divLoadingContent" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
		{$locale.449}
	</div>
	<div id="divLongLoading" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
		{$locale.450}
	</div>
	<div id="divAlternateContent" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
		{$locale.451}
	</div>
</form>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
