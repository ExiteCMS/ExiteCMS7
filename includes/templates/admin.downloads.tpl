{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.downloads.tpl                                  *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-18 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'downloads'                       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
{if !$cats_found}
<center>
	{$locale.508}
	<br />
	{$locale.509}
	<br /><br />
	<a href='download_cats.php{$aidlink}'>{$locale.510}</a>{$locale.511}
</center>
{else}
	<form name='inputform' method='post' action='{$formaction}'>
		<table align='center' cellpadding='0' cellspacing='0' width='460'>
			<tr>
				<td width='80' class='tbl'>
					{$locale.480}
				</td>
				<td class='tbl'>
					<input type='text' name='download_title' value='{$download_title}' class='textbox' style='width:400px;' />
				</td>
			</tr>
			<tr>
				<td valign='top' width='80' class='tbl'>
					{$locale.481}
				</td>
				<td class='tbl'>
					<textarea name='download_description' rows='5' cols='80' class='textbox' style='width:400px;'>{$download_description}</textarea>
				</td>
			</tr>
			<tr>
				<td class='tbl'></td><td class='tbl'>
					<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onclick="addText('download_description', '<b>', '</b>');" />
					<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onclick="addText('download_description', '<i>', '</i>');" />
					<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onclick="addText('download_description', '<u>', '</u>');" />
					<input type='button' value='ul' class='button' style='width:25px;' onclick="addText('download_description', '<ul>', '</ul>');" />
					<input type='button' value='li' class='button' style='width:25px;' onclick="addText('download_description', '<li>', '</li>');" />
					<input type='button' value='link' class='button' style='width:35px' onclick="addText('download_description', '<a href=\'', '\' target=\'_blank\'>Link</a>');" />
					<input type='button' value='img' class='button' style='width:35px' onclick="addText('download_description', '<img src=\'', '\' style=\'margin:5px\' align=\'left\'>');" />
					<input type='button' value='center' class='button' style='width:45px' onclick="addText('download_description', '<center>', '</center>');" />
					<input type='button' value='small' class='button' style='width:40px' onclick="addText('download_description', '<span class=\'small\'>', '</span>');" />
					<input type='button' value='small2' class='button' style='width:45px' onclick="addText('download_description', '<span class=\'small2\'>', '</span>');" />
					<input type='button' value='alt' class='button' style='width:25px' onclick="addText('download_description', '<span class=\'alt\'>', '</span>');" />
					<br />
				</td>
			</tr>
			<tr>
				<td width='80' class='tbl'>
					{$locale.482}
				</td>
				<td class='tbl'>
					<input type='text' name='download_url' value='{$download_url}' class='textbox' style='width:400px;' />
				</td>
			</tr>
			<tr>
				<td width='80' class='tbl'>
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
					<span class='small2'>{$locale.490}</span>
				</td>
			</tr>
			<tr>
				<td width='80' class='tbl'>
					{$locale.484}
				</td>
				<td class='tbl'>
					<input type='text' name='download_license' value='{$download_license}' class='textbox' style='width:150px;' />
				</td>
			</tr>
			<tr>
				<td width='80' class='tbl'>
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
				<td width='80' class='tbl'>
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
					<input type='submit' name='save_download' value='{$locale.488}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
	{include file="_opentable.tpl" name=$_name title=$locale.500 state=$_state style=$_style}
	<table align='center' cellpadding='0' cellspacing='0' width='400'>
	{section name=id loop=$tree}
		{if $smarty.section.id.first}
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
								<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=edit&amp;download_cat_id={$tree[id].cat_id}&amp;download_id={$tree[id].id}'><img src='{$smarty.const.THEME}/images/page_edit.gif' alt='{$locale.503}' title='{$locale.503}' /></a>&nbsp;
								<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;download_cat_id={$tree[id].cat_id}&amp;download_id={$tree[id].id}' onclick='return DeleteItem()'><img src='{$smarty.const.THEME}/images/page_delete.gif' alt='{$locale.504}' title='{$locale.504}' /></a>
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
		{/if}
	{sectionelse}
		<tr>
			<td align='center'>
				<br />
				{$locale.506}
				<br /><br />
				<a href='download_cats.php{$aidlink}'>{$locale.507}
				<br /><br />
			</td>
		</tr>
	{/section}
	</table>
	<script type='text/javascript'>
	function DeleteItem()
	{ldelim}
	return confirm('{$locale.460}');
	{rdelim}
	</script>
	{include file="_closetable.tpl"}
	{include file="_opentable.tpl" name=$_name title=$locale.520 state=$_state style=$_style}
	{if $barmsg|default:"" != ""}
		<center><b>{$barmsg}</b></center><br />
	{/if}
	<form name='barform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=bar'>
		<table align='center' cellpadding='0' cellspacing='0' width='400'>
			<tr>
				<td class='tbl'>
					{$locale.524}:
					<br />
					<input type='text' name='bar_title' value='{$bar_title}' class='textbox' style='width:400px;' />
				</td>
			</tr>
			{section name=bar start=1 loop=`$smarty.const.MAX_BARS+1`}
			<tr>
				<td class='tbl'>
					{$locale.521} {$smarty.section.bar.index}:<br />
					<select name='download_bar[{$smarty.section.bar.index}]' class='textbox' style='width:400px;'>
						<option value='0'>&nbsp;</option>
					{section name=id loop=$barfiles}
						<option value='{$barfiles[id].download_id}'{if $barfiles[id].download_bar == $smarty.section.bar.index} selected="selected"{/if}>{$barfiles[id].download_cat_name} » {$barfiles[id].download_title}</option>
					{/section}
					</select>
				</td>
			</tr>
			{/section}
			<tr>
				<td align='center'>
					<input type='submit' name='save_bars' value='{$locale.522}' class='button' />
				</td>
			</tr>
		</table>
	</form>
{/if}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}