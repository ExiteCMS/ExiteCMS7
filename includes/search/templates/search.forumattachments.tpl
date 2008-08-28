{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: search.forumattachments.tpl                          *}
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
{* Template for the main module 'search', forum attachments                *}
{*                                                                         *}
{***************************************************************************}
{if $action == "search"}
	{section name=idx loop=$reportvars.output}
		{if !$smarty.section.idx.first}
			<br /><br />
		{/if}
		{$reportvars.output[idx].relevance|string_format:"%u"}% - <a href='/forum/viewthread.php?forum_id={$reportvars.output[idx].forum_id}&amp;thread_id={$reportvars.output[idx].thread_id}&amp;pid={$reportvars.output[idx].post_id}#post_{$reportvars.output[idx].post_id}'>{$reportvars.output[idx].post_subject}</a>,
		{$locale.441} {$reportvars.output[idx].attach_realname}
		<br />
		<span class='small'><font class='smallalt'>{$locale.040}</font>
		{if iMEMBER}
			<a href='profile.php?lookup={$reportvars.output[idx].user_id}'>{$reportvars.output[idx].user_name}</a>
		{else}
			{$reportvars.output[idx].user_name}
		{/if}
		<font class='smallalt'>{$locale.436}</font> {$reportvars.output[idx].post_datestamp|date_format:"longdate"}</span>
	{/section}
{else}
	<input type='radio' name='search_id' value='{$searches[id].search_id}' {if $search_id == $searches[id].search_id || $searches[id].search_order == $default_location}checked='checked'{/if}  onclick='javascript:show_filter("{$searches[id].search_filters}");'/> {$searches[id].search_title} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
