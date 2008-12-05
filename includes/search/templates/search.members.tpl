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
{* Template for the main module 'search', members                          *}
{*                                                                         *}
{***************************************************************************}
{if $action == "search"}
	<br /><br />
	<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
	{if iMEMBER}
		<a href='profile.php?lookup={$output.user_id}'>{$output.user_name}</a>
	{else}
		{$output.user_name}
	{/if}
	<br />&nbsp;
	<span class='small'><font class='smallalt'>{$locale.src438}</font> {$output.user_joined|date_format:"forumdate"},
	<font class='smallalt'>{$locale.src439}</font> {if $output.user_lastvisit}{$output.user_lastvisit|date_format:"forumdate"}{else}{$locale.src440}{/if}</span>
{else}
	<input type='radio' name='search_id' value='{$searches[id].search_id}' {if $search_id == $searches[id].search_id || $searches[id].search_order == $default_location}checked='checked'{/if}  onclick='javascript:show_filter("{$searches[id].search_filters}");'/> {$searches[id].search_title} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
