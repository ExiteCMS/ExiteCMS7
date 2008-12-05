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
	<br /><br />
	<a href='downloads.php?cat_id={$output.download_cat}&amp;download_id={$output.download_id}' target='_blank'>{$output.download_title}</a> - {$output.download_filesize}
	{if $output.download_description}
		&nbsp;&middot;&nbsp;{$output.download_description}<br />
	{/if}
	<span class='small'><font class='smallalt'>{$locale.src433}</font> {$output.download_license},
	<font class='smallalt'>{$locale.src434}</font> {$output.download_os},
	<font class='smallalt'>{$locale.src435}</font> {$output.download_version}
	<font class='smallalt'>{$locale.src436}</font> {$output.download_datestamp|date_format:"longdate"},
	<font class='smallalt'>{$locale.src437}</font> {$output.download_count}</span>
{else}
	<input type='radio' name='search_id' value='{$searches[id].search_id}' {if $search_id == $searches[id].search_id || $searches[id].search_order == $default_location}checked='checked'{/if}  onclick='javascript:show_filter("{$searches[id].search_filters}");'/> {$searches[id].search_title} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
