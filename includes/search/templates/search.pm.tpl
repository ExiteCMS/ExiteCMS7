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
{* Template for the main module 'search', private messages                 *}
{*                                                                         *}
{***************************************************************************}
{if $action == "search"}
	{section name=idx loop=$reportvars.output}
		{if !$smarty.section.idx.first}
			<br /><br />
		{/if}
		{$reportvars.output[idx].relevance|string_format:"%u"}% - <a href='{$smarty.const.BASEDIR}pm.php?folder={$reportvars.output[idx].folder}&amp;action=view&amp;msg_id={$reportvars.output[idx].pmindex_id}#view_{$reportvars.output[idx].pmindex_id}'>{$reportvars.output[idx].pm_subject}</a>
		<br />
		<span class='small'><font class='smallalt'>{if $reportvars.output[idx].pmindex_user_id == $reportvars.output[idx].pmindex_from_id}{$locale.src522}{else}{$locale.src523}{/if}:</font>
		{section name=uid loop=$reportvars.output[idx].recipients}
			{if $reportvars.output[idx].recipients[uid].visible}
				{if $reportvars.output[idx].recipients[uid].is_group}
					<a href='{$smarty.const.BASEDIR}profile.php?group_id={$reportvars.output[idx].recipients[uid].id}'><b>{$reportvars.output[idx].recipients[uid].name}</b></a>{if !$smarty.section.uid.last},{/if}
				{else}
					<a href='{$smarty.const.BASEDIR}profile.php?lookup={$reportvars.output[idx].recipients[uid].id}'>{$reportvars.output[idx].recipients[uid].name}</a>{if !$smarty.section.uid.last},{/if}
				{/if}
			{else}
				{if $reportvars.output[idx].recipients[uid].is_group}
					<b>{$reportvars.output[idx].recipients[uid].name}</b>{if !$smarty.section.uid.last},{/if}
				{else}
					{$reportvars.output[idx].recipients[uid].name}{if !$smarty.section.uid.last},{/if}
				{/if}
			{/if}
		{/section}
		<br />
		<font class='smallalt'>{$locale.src436}</font> {$reportvars.output[idx].pm_datestamp|date_format:"longdate"}</span>
	{/section}
{elseif iMEMBER}
	<input type='radio' name='search_id' value='{$searches[id].search_id}' {if $search_id == $searches[id].search_id || $searches[id].search_order == $default_location}checked='checked'{/if}  onclick='javascript:show_filter("{$searches[id].search_filters}");'/> {$searches[id].search_title} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
