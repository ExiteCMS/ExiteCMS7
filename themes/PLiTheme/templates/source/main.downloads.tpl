{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: main.downloads.tpl                             *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-07 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'downloads'                                *}
{*                                                                         *}
{***************************************************************************}
{if $subcats}{assign var="_title" value=$locale.417}{else}{assign var="_title" value=$locale.418}{/if}
{assign var="columns" value="2"} 													{* number of columns *}
{math equation="(100 - x) / x" x=$columns format="%u" assign="colwidth"}
{section name=cat loop=$download_cats}
{cycle name=column values="1,2" assign="column" print=no} 							{* keep track of the current column *}
{if $smarty.section.cat.first}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
{/if}
	{if $column == 1}<tr>{/if}
		<td width='1%' class='tbl1' style='vertical-align:top'>
			{if $download_cats[cat].download_cat_image|default:"" != ""}
				<a href='{$smarty.const.FUSION_SELF}?cat_id={$download_cats[cat].download_cat_id}'>
				<img src='{$smarty.const.IMAGES_DC}{$download_cats[cat].download_cat_image}' alt='{$download_cats[cat].download_cat_name}' />
				</a>
			{/if}
		</td>
		<td width='{$colwidth}%' class='tbl1' style='vertical-align:top'>
			<div class='forum-caption'>
				<img src='{$smarty.const.THEME}images/bullet.gif' alt=''>
				<a href='{$smarty.const.FUSION_SELF}?cat_id={$download_cats[cat].download_cat_id}'>{$download_cats[cat].download_cat_name}</a>
				<br />
				<span class='small2'>&nbsp; <b>{$locale.414}</b> {$download_cats[cat].download_datestamp|date_format:'%B %e, %Y'}
				- <b>{$locale.401}</b> {$download_cats[cat].download_count}</span>
			</div>
			{if $download_cats[cat].download_cat_description|default:"" != ""}
				<span class='small'>{$download_cats[cat].download_cat_description}</span>
			{/if}
		</td>
	{if $column == $columns}</tr>{/if}
{if $smarty.section.cat.last}
	{if $column != $columns}
	{section name=dummy start=$column loop=$columns}
		<td width='{math equation='x+1' x=$colwidth}%' colspan='2' class='tbl1' style='vertical-align:top'>
		</td>
	{/section}
	</tr>
	{/if}
</table>
{include file="_closetable.tpl"}
{/if}
{sectionelse}
	{if !$subcats}
		{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
		<center>
			<br />
			<b>{$locale.430}</b>
			<br /><br />
		</center>
		{include file="_closetable.tpl"}
	{/if}
{/section}
{section name=item loop=$downloads}
{if $smarty.section.item.first}
	{include file="_opentable.tpl" name=$_name title=$locale.415|cat:" "|cat:$parent.download_cat_name state=$_state style=$_style}
<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>
{/if}
	<tr>
		<td colspan='4' class='forum-caption'>
			<b>{$downloads[item].download_title}</b>
		</td>
	</tr>
	{if $downloads[item].download_description|default:"" != ""}
	<tr>
		<td colspan='4' class='tbl1'>
			{$downloads[item].download_description}
		</td>
	</tr>
	{/if}
	<tr>
		<td class='tbl2' style='white-space:nowrap;'>
			<b>{$locale.411}</b> {$downloads[item].download_license}
		</td>
		<td class='tbl1' style='white-space:nowrap;'>
			<b>{$locale.412}</b> {$downloads[item].download_os}
		</td>
		<td class='tbl2' style='white-space:nowrap;'>
			<b>{$locale.413}</b> {$downloads[item].download_version}
		</td>
		<td class='tbl1' rowspan='2' style='text-align:center;white-space:nowrap;'>
			{buttonlink name=$locale.416 link=$smarty.const.FUSION_SELF|cat:"?cat_id="|cat:$downloads[item].download_cat|cat:"&amp;download_id="|cat:$downloads[item].download_id}
		</td>
	</tr>
	<tr>
		<td class='tbl2' style='white-space:nowrap;'>
			<b>{$locale.414}</b> {$downloads[item].download_datestamp|date_format:'%A, %B %e, %Y'}
		</td>
		<td class='tbl1' style='white-space:nowrap;'>
			<b>{$locale.415}</b> {$downloads[item].download_count}
		</td>
		<td class='tbl2' style='white-space:nowrap;'>
			<b>{$locale.419}</b> {$downloads[item].download_filesize}
		</td>
	</tr>
{if !$smarty.section.item.last}
	<tr>
		<td colspan='4' class='tbl1'>
		</td>
	</tr>
{else}
	</table>
{/if}
{sectionelse}
	{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
	<center>
		<br />
		<b>{$locale.431}</b>
		<br /><br />
	</center>
{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}