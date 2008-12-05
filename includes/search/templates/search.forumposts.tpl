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
{* Template for the main module 'search', forum posts                      *}
{*                                                                         *}
{***************************************************************************}
{if $action == "search"}
	<br /><br />
	{$output.relevance|string_format:"%u"}% - <a href='/forum/viewthread.php?forum_id={$output.forum_id}&amp;thread_id={$output.thread_id}&amp;pid={$output.post_id}#post_{$output.post_id}'>{$output.post_subject}</a>
	<br />
	<span class='small'><font class='smallalt'>{$locale.040}</font>
	{if iMEMBER}
		<a href='profile.php?lookup={$output.user_id}'>{$output.user_name}</a>
	{else}
		{$output.user_name}
	{/if}
	<font class='smallalt'>{$locale.src436}</font> {$output.post_datestamp|date_format:"longdate"}</span>
{else}
	<input type='radio' name='search_id' value='{$searches[id].search_id}.2' {if $search_id == $searches[id].search_id || $searches[id].search_order == $default_location}checked='checked'{/if}  onclick='javascript:show_filter("{$searches[id].search_filters}");'/> {$searches[id].search_title} {$locale.src517} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
	<input type='radio' name='search_id' value='{$searches[id].search_id}.1' onclick='javascript:show_filter("{$searches[id].search_filters}");'/> {$searches[id].search_title} {$locale.src516} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
