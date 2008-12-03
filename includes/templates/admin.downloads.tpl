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
{* Template for the admin content module 'downloads'                       *}
{*                                                                         *}
{***************************************************************************}
{if $step == "add" || $step == "edit"}
	{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
		<form name='inputform' method='post' action='{$formaction}'>
			<table align='center' cellpadding='0' cellspacing='0' width='460'>
				<tr>
					<td width='1%' class='tbl' style='white-space:nowrap'>
						{$locale.480}
					</td>
					<td class='tbl'>
						<input type='text' name='download_title' value='{$download_title}' class='textbox' style='width:400px;' />
					</td>
				</tr>
				<tr>
					<td width='1%' valign='top' class='tbl' style='white-space:nowrap'>
						{$locale.481}
					</td>
					<td class='tbl'>
						{if $settings.hoteditor_enabled == 0 || $userdata.user_hoteditor == 0}
							<textarea name='download_description' rows='5' cols='80' class='textbox' style='width:400px'>{$download_description}</textarea><br />
							<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('download_description', '[b]', '[/b]');" />
							<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('download_description', '[i]', '[/i]');" />
							<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('download_description', '[u]', '[/u]');" />
							<input type='button' value='url' class='button' style='width:30px;' onclick="addText('download_description', '[url]', '[/url]');" />
							<input type='button' value='mail' class='button' style='width:35px;' onclick="addText('download_description', '[mail]', '[/mail]');" />
							<input type='button' value='img' class='button' style='width:30px;' onclick="addText('download_description', '[img]', '[/img]');" />
							<input type='button' value='center' class='button' style='width:45px;' onclick="addText('download_description', '[center]', '[/center]');" />
							<input type='button' value='small' class='button' style='width:40px;' onclick="addText('download_description', '[small]', '[/small]');" />
							<br />
						{else}
							<script language="javascript" type="text/javascript">
								// non-standard toolbars for this editor instance
								var toolbar1 ="SPACE,btFont_Name,btFont_Size,btFont_Color,btHighlight";
								var toolbar2 ="SPACE,btRemove_Format,SPACE,btBold,btItalic,btUnderline,SPACE,btAlign_Left,btCenter,btAlign_Right,SPACE,btStrikethrough,btSubscript,btSuperscript,btHorizontal";
								var toolbar3 ="SPACE,btHyperlink,btHyperlink_Email,btInsert_Image,btEmotions";

								var textarea_toolbar1 ="SPACE,btFont_Name,btFont_Size,btFont_Color,btHighlight";
								var textarea_toolbar2 ="SPACE,btRemove_Format,SPACE,btBold,btItalic,btUnderline,SPACE,btAlign_Left,btCenter,btAlign_Right,SPACE,btStrikethrough,btSubscript,btSuperscript,btHorizontal";
								var textarea_toolbar3 ="SPACE,btHyperlink,btHyperlink_Email,btInsert_Image,btEmotions";
							</script>
							{include file="_bbcode_editor.tpl" name="download_description" id="download_description" author="" message=$download_description width="400px" height="200px"}
						{/if}
					</td>
				</tr>
				{if $settings.download_via_http == "1"}
					<tr>
						<td width='1%' class='tbl' style='white-space:nowrap'>
							{$locale.482}
						</td>
						<td class='tbl'>
							<input type='text' name='download_url' value='{$download_url}' class='textbox' style='width:400px;' />
						</td>
					</tr>
				{else}
					<tr>
						<td width='1%' class='tbl' style='white-space:nowrap'>
							{$locale.494}
						</td>
						<td class='tbl'>
							<select name='download_url' class='textbox'>
							{foreach from=$download_files item=file}
								<option value='{$smarty.const.DOWNLOADS}{$file}' {if $download_url == $smarty.const.DOWNLOADS|cat:$file}selected='selected'{/if}>{$file}</option>
							{/foreach}
							</select>
						</td>
					</tr>
				{/if}
				<tr>
					<td width='1%' class='tbl' style='white-space:nowrap'>
						{$locale.491}
					</td>
					<td class='tbl'>
						<select name='download_external' class='textbox'>
							<option value='0'{if $download_external == 0} selected="selected"{/if}>{$locale.493}</option>
							<option value='1'{if $download_external == 1} selected="selected"{/if}>{$locale.492}</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width='1%' class='tbl' style='white-space:nowrap'>
						{$locale.483}
					</td>
					<td class='tbl'>
						<select name='download_cat' class='textbox'>
							<option value='0'{if $cats[id].selected} selected="selected"{/if}>{$locale.455}</option>
						{section name=id loop=$cats}
							<option value='{$cats[id].download_cat_id}'{if $cats[id].selected} selected="selected"{/if}>{$cats[id].download_cat_name}</option>
						{/section}
						</select>
						<br />
						<span class='small2'>{$locale.455|string_format:$locale.490}</span>
					</td>
				</tr>
				<tr>
					<td width='1%' class='tbl' style='white-space:nowrap'>
						{$locale.484}
					</td>
					<td class='tbl'>
						<input type='text' name='download_license' value='{$download_license}' class='textbox' style='width:150px;' />
					</td>
				</tr>
				<tr>
					<td width='1%' class='tbl' style='white-space:nowrap'>
						{$locale.485}
					</td>
					<td class='tbl'>
						<input type='text' name='download_os' value='{$download_os}' class='textbox' style='width:150px;' />
					</td>
				</tr>
				<tr>
					<td width='80' class='tbl'>
						{$locale.486}
					</td>
					<td class='tbl'>
						<input type='text' name='download_version' value='{$download_version}' class='textbox' style='width:150px;' />
					</td>
				</tr>
				<tr>
					<td width='1%' class='tbl' style='white-space:nowrap'>
						{$locale.487}
					</td>
					<td class='tbl'>
						<input type='text' name='download_filesize' value='{$download_filesize}' class='textbox' style='width:150px;' />
					</td>
				</tr>
				<tr>
					<td align='center' colspan='2' class='tbl'>
						{if $step == "edit"}
							<input type='checkbox' name='update_datestamp' value='1' /> {$locale.489}
							<br /><br />
						{/if}
						{if $settings.hoteditor_enabled == 0 || $userdata.user_hoteditor == 0}
							<input type='submit' name='save_download' value='{$locale.488}' class='button' />
						{else}
							<input type='submit' name='save_download' value='{$locale.488}' class='button' onclick='javascript:get_hoteditor_data("download_description");' />
						{/if}
					</td>
				</tr>
			</table>
		</form>
	{include file="_closetable.tpl"}
{/if}
{if $settings.download_localisation == "multiple"}
	{assign var="tabletitle" value=$locale.500|cat:" "|cat:$locale.513|cat:" '<b>"|cat:$cat_locale|cat:"</b>'"}
{else}
	{assign var="tabletitle" value=$locale.500}
{/if}
{include file="_opentable.tpl" name=$_name title=$tabletitle state=$_state style=$_style}
{if $settings.download_localisation == "multiple"}
	{assign var="url_locale" value="&amp;cat_locale="|cat:$cat_locale}
	<br />
	<div style='text-align:center;'>
		{$locale.515} {html_options name=cat_locale options=$locales selected=$cat_locale class="textbox" onchange="location = '"|cat:$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;cat_locale=' + this.options[this.selectedIndex].value;"}
	</div>
{else}
	{assign var="cat_locale" value=""}
{/if}
<br />
{section name=id loop=$tree}
	{if $smarty.section.id.first}
	<table align='center' cellpadding='0' cellspacing='0' width='400'>
	<tr>
		<td class='tbl2'>
			{$locale.501}
		</td>
		<td align='right' class='tbl2'>
			{$locale.502}
		</td>
	</tr>
	<tr>
		<td colspan='2' height='1'>
		</td>
	</tr>
	{/if}
	{if $tree[id].node == "P"}
	{if $tree[id].id == $download_cat_id}
		{assign var='open' value=true}
	{else}
		{assign var='open' value=false}
	{/if}
	<tr>
		<td class='tbl2'>
			{section name=nl loop=`$tree[id].nestlevel+1`}
			<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />&nbsp;
			{/section}
			{$tree[id].name}
		</td>
		<td class='tbl2' align='right'>
			<img onclick="javascript:flipBox('{$tree[id].id}')" src='{$smarty.const.THEME}images/panel_{if $open}off{else}on{/if}.gif' name='b_{$tree[id].id}' alt='' />
		</td>
	</tr>
	{assign var='in_box' value=false}
	{elseif $tree[id].node == "D"}
	{if $tree[id].first}
	<tr>
		<td colspan='2'>
			<div id='box_{$tree[id].cat_id}'{if !$open} style='display:none'{/if}>
				<table cellpadding='0' cellspacing='0' width='100%'>
	{/if}
					<tr>
						<td class='tbl'>
							<a href='{$tree[id].url}' target='_blank'>{$tree[id].name}</a>
						</td>
						<td align='right' width='100' class='tbl'>
							<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=edit&amp;download_cat_id={$tree[id].cat_id}&amp;download_id={$tree[id].id}{$url_locale}'><img src='{$smarty.const.THEME}/images/page_edit.gif' alt='{$locale.503}' title='{$locale.503}' /></a>&nbsp;
							<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;download_cat_id={$tree[id].cat_id}&amp;download_id={$tree[id].id}{$url_locale}' onclick='return DeleteItem()'><img src='{$smarty.const.THEME}/images/page_delete.gif' alt='{$locale.504}' title='{$locale.504}' /></a>
						</td>
					</tr>
	{if $tree[id].last}
				</table>
			</div>
		</td>
	</tr>
	{/if}
	{elseif $tree[id].node == "E"}
	<tr>
		<td colspan='2'>
			<div id='box_{$tree[id].id}' style='display:none'>
				<table cellpadding='0' cellspacing='0' width='100%'>
					<tr>
						<td class='tbl'>
							{$locale.505}
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	{/if}
	{if $smarty.section.id.last}
	</table>
	<div style='text-align:center;'>
		<br />
		{buttonlink name=$locale.516 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=add"|cat:$url_locale}
	</div>
	{/if}
{/section}
{include file="_closetable.tpl"}
{include file="_opentable.tpl" name=$_name title=$locale.518 state=$_state style=$_style}
<form name='download_upload' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=upload' enctype='multipart/form-data'>
	{if $upload_error != ""}
		<div class='errors'><br />{$upload_error}</div>
	{/if}
	<table align='center' width='100%' cellpadding='0' cellspacing='0'>
		<tr>
			<td width='175' class='tbl'>
				<br />{$locale.519}:<br />
			</td>
			<td class='tbl'>
				<br /><input type='file' name='myfile' class='textbox' style='width:250px;' /><br />
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='upload' value='{$locale.520}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
<script type='text/javascript'>
function DeleteItem()
{ldelim}
return confirm('{$locale.460}');
{rdelim}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
