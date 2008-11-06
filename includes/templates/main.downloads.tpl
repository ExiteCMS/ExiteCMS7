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
{* Template for the main module 'downloads'                                *}
{*                                                                         *}
{***************************************************************************}
{if $subcats}{assign var="_title" value=$locale.417}{else}{assign var="_title" value=$locale.418}{/if}
{math equation="(100-x)/x" x=$columns format="%u" assign="colwidth"}							{* width per column  *}
{if $columns == 1}{assign var="colcount" value="1"}
{elseif $columns == 2}{assign var="colcount" value="1,2"}
{elseif $columns == 3}{assign var="colcount" value="1,2,3"}
{/if}
{section name=cat loop=$download_cats}
{cycle name=column values=$colcount assign="column" print=no} 										{* keep track of the current column *}
{if $smarty.section.cat.first}
	{math equation="x - (x%y)" x=$download_cats|@count y=$columns format="%u" assign="fullrows"}
	{math equation="x - y" x=$download_cats|@count y=$fullrows format="%u" assign="remainder"}
	{if $remainder > 0}
		{math equation="(100 - z + y) / (z - y)" y=$fullrows z=$download_cats|@count format="%u" assign="lastwidth"}	{* width last rows columns *}
	{/if}
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
		{if $smarty.section.cat.last && $smarty.section.cat.iteration > $fullrows}
		<td width='{$lastwidth}%' colspan='{math equation="1+(x-y)*2" x=$columns y=$remainder}' class='tbl1' style='vertical-align:top'>
		{else}
		<td width='{$colwidth}%' class='tbl1' style='vertical-align:top'>
		{/if}
			<div class='main-label'>
				<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
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
	{if $download_count > $download_limit}
		{makepagenav start=$rowstart count=$download_limit total=$download_count range=$settings.navbar_range link=$smarty.const.FUSION_SELF|cat:"?cat_id="|cat:$parent.download_cat_id|cat:"&amp;"}
	{/if}
<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>
{/if}
	<tr>
		<td colspan='3' class='main-label'>
			<b>{$downloads[item].download_title}</b>
		</td>
		<td align='right' class='main-label'>
			<a href='{$smarty.const.FUSION_SELF}?cat_id={$downloads[item].download_cat}&amp;download_id={$downloads[item].download_id}'><span class='small2'>{$locale.420}</span></a>
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
	{if $download_count > $download_limit}
		{makepagenav start=$rowstart count=$download_limit total=$download_count range=$settings.navbar_range link=$smarty.const.FUSION_SELF|cat:"?cat_id="|cat:$parent.download_cat_id|cat:"&amp;"}
	{/if}
	{include file="_closetable.tpl"}
{/if}
{sectionelse}
	{if $have_cats && $cats_count == 0}
		{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
		<center>
			<br />
			{$locale.431}
			<br /><br />
		</center>
		{include file="_closetable.tpl"}
	{/if}
{/section}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
