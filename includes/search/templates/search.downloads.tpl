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
{* Template for the main module 'search', downloads                        *}
{*                                                                         *}
{***************************************************************************}
{if $action == "search"}
	{section name=idx loop=$reportvars.output}
		{if !$smarty.section.idx.first}
			<br /><br />
		{/if}
		<a href='downloads.php?cat_id={$v[idx].download_cat}&amp;download_id={$reportvars.output[idx].download_id}' target='_blank'>{$reportvars.output[idx].download_title}</a> - {$reportvars.output[idx].download_filesize}
		<br />
		{if $reportvars.output[idx].download_description}
			{$reportvars.output[idx].download_description}<br />
		{/if}
		<span class='small'><font class='smallalt'>{$locale.433}</font> {$reportvars.output[idx].download_license},
		<font class='smallalt'>{$locale.434}</font> {$reportvars.output[idx].download_os},
		<font class='smallalt'>{$locale.435}</font> {$reportvars.output[idx].download_version}
		<br />
		<font class='smallalt'>{$locale.436}</font>{$reportvars.output[idx].download_datestamp|date_format:"longdate"},
		<font class='smallalt'>{$locale.437}</font> {$reportvars.output[idx].download_count}</span>
	{/section}
{else}
	<input type='radio' name='search_id' value='{$searches[id].search_id}' {if $search_id == $searches[id].search_id || $searches[id].search_order == $default_location}checked='checked'{/if}  onclick='javascript:show_filter("{$searches[id].search_filters}");'/> {$searches[id].search_title} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
