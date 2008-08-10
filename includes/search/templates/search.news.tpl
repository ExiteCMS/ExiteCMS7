{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: search.news.tpl                                      *}
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
{* Template for the main module 'search', news                             *}
{*                                                                         *}
{***************************************************************************}
{if $action == "search"}
	{section name=idx loop=$reportvars.output}
		{if !$smarty.section.idx.first}
			<br /><br />
		{/if}
		{$reportvars.output[idx].relevance|string_format:"%u"}% - <a href='news.php?readmore={$reportvars.output[idx].news_id}'>{$reportvars.output[idx].news_subject}</a>
		<br />
		<span class='small'><font class='smallalt'>{$locale.040}</font>
		{if iMEMBER}
			<a href='profile.php?lookup={$reportvars.output[idx].user_id}'>{$reportvars.output[idx].user_name}</a>
		{else}
			{$reportvars.output[idx].user_name}
		{/if}
		<font class='smallalt'>{$locale.041}</font> {$reportvars.output[idx].news_datestamp|date_format:"longdate"}</span>
	{/section}
{else}
	<input type='radio' name='search_id' value='{$searches[id].search_id}' {if $search_id == $searches[id].search_id}checked='checked'{/if} onclick='javascript:show_filter("date,users");' /> {$searches[id].search_title} {if $searches[id].search_fulltext}<span style='color:red;'>*</span>{/if}<br />
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
