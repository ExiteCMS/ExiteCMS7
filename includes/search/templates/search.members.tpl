{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: search.members.tpl                                   *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-08-10 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'search', members                          *}
{*                                                                         *}
{***************************************************************************}
{if $action == "search"}
	{section name=idx loop=$reportvars.output}
		{if !$smarty.section.idx.first}
			<br /><br />
		{/if}
		<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
		{if iMEMBER}
			<a href='profile.php?lookup={$reportvars.output[idx].user_id}'>{$reportvars.output[idx].user_name}</a>
		{else}
			{$reportvars.output[idx].user_name}
		{/if}
		<br />&nbsp;
		<span class='small'><font class='smallalt'>{$locale.438}</font> {$reportvars.output[idx].user_joined|date_format:"forumdate"},
		<font class='smallalt'>{$locale.439}</font> {if $reportvars.output[idx].user_lastvisit}{$reportvars.output[idx].user_lastvisit|date_format:"forumdate"}{else}{$locale.440}{/if}</span>
	{/section}
{else}
	<input type='radio' name='search_id' value='{$searches[id].search_id}' {if $search_id == $searches[id].search_id}checked='checked'{/if} onclick='javascript:show_filter("");' /> {$searches[id].search_title} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
