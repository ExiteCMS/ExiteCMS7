{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.search.tpl                                      *}
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
{* Template for the main module 'search'                                   *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<center>
	{section name=idx loop=$links}
	{if $smarty.section.idx.first}
	<form name='searchform' method='post' action='{$smarty.const.FUSION_SELF}'>
		{$locale.401} 
		<input type='text' name='stext' value='{$searchtext}' class='textbox' style='width:200px' />
		<input type='submit' name='search' value='{$locale.408}' class='button' />
		<br /><br />
	{/if}
	<input type='radio' name='stype' value='{$links[idx].value}'{if $links[idx].value == $stype} checked="checked"{/if} /> {$links[idx].link_name}
	{if $links[idx].value == $stype}{assign var='searched' value=$links[idx].link_name}{/if}
	{if $smarty.section.idx.last}
		{if $smarty.const.iMEMBER}
		<input type='radio' name='stype' value='m'{if 'm' == $stype} checked="checked"{/if} /> {$locale.407}
		{assign var='searched' value=$locale.407}
		{/if}
	</form>
	{/if}
	{sectionelse}
		<br />
		<b>{$locale.472}</b>
		<br /><br />
	{/section}
	{if $searchtext|default:"" == ""}
		<br /><hr />
		<table width='600' align='center' cellspacing='0' cellpadding='0' border='0' class='tbl-border'>
		{foreach from=$locale.480 item=line}
			<tr>
				<td align='center' class='tbl1' style='white-space:nowrap'>
					{$line.0}
				</td>
				<td align='center' class='tbl1'>
					{if $line.0 != ""} :{/if}
				</td>
				<td class='tbl1'>
					{$line.1}
				</td>
			</tr>
		{/foreach}
		</table>
	{/if}
</center>
{include file="_closetable.tpl"}
{if $searchtext|default:"" != ""}
{include file="_opentable.tpl" name=$_name title=$locale.409 state=$_state style=$_style}
{if $result_count == 0}
	{$locale.470}
{else}
	{$result_count} {$locale.422} {$searched}{$locale.423}:
{/if}
<br /><br />
{section name=idx loop=$results}
{if $stype == "a"}
	{$results[idx].relevance|string_format:"%u"}% - <a href='readarticle.php?article_id={$results[idx].data.article_id}'>{$results[idx].data.article_subject}</a>
	<br />
	<span class='small2'>{$locale.040} 
	{if iMEMBER}
		<a href='profile.php?lookup={$results[idx].data.user_id}'>{$results[idx].data.user_name}</a>
	{else}
		{$results[idx].data.user_name}
	{/if}
	{$locale.041} {$results[idx].data.article_datestamp|date_format:"longdate"}</span>
{elseif $stype == "d"}
	<a href='downloads.php?cat_id={$results[idx].data.download_cat}&amp;download_id={$results[idx].data.download_id}' target='_blank'>{$results[idx].data.download_title}</a> - {$results[idx].data.download_filesize}
	<br />
	{if $results[idx].data.download_description}
		{$results[idx].data.download_description}<br />
	{/if}
	<span class='small'><font class='small'>{$locale.451}</font> {$results[idx].data.download_license} |
	<font class='small'>{$locale.452}</font> {$results[idx].data.download_os} |
	<font class='small'>{$locale.453}</font> {$results[idx].data.download_version}
	<br />
	<font class='small'>{$locale.454}</font>{$results[idx].data.download_datestamp|date_format:"longdate"} |
	<font class='small'>{$locale.455}</font> {$results[idx].data.download_count}</span>
{elseif $stype == "f"}
	{$results[idx].relevance|string_format:"%u"}% - <a href='/forum/viewthread.php?forum_id={$results[idx].data.forum_id}&amp;thread_id={$results[idx].data.thread_id}&amp;pid={$results[idx].data.post_id}#post_{$results[idx].data.post_id}'>{$results[idx].data.post_subject}</a>
	<br />
	<span class='small2'>{$locale.040} 
	{if iMEMBER}
		<a href='profile.php?lookup={$results[idx].data.user_id}'>{$results[idx].data.user_name}</a>
	{else}
		{$results[idx].data.user_name}
	{/if}
	{$locale.041} {$results[idx].data.post_datestamp|date_format:"longdate"}</span>
{elseif $stype == "n"}
	{$results[idx].relevance|string_format:"%u"}% - <a href='news.php?readmore={$results[idx].data.news_id}'>{$results[idx].data.news_subject}</a>
	<br />
	<span class='small2'>{$locale.040} 
	{if iMEMBER}
		<a href='profile.php?lookup={$results[idx].data.user_id}'>{$results[idx].data.user_name}</a>
	{else}
		{$results[idx].data.user_name}
	{/if}
	{$locale.041} {$results[idx].data.news_datestamp|date_format:"longdate"}</span>
{elseif $stype == "m"}
	<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
	{if iMEMBER}
		<a href='profile.php?lookup={$results[idx].data.user_id}'>{$results[idx].data.user_name}</a>
	{else}
		{$results[idx].data.user_name}
	{/if}
{elseif $stype == "w"}
	{$results[idx].relevance|string_format:"%u"}% - <a href='weblinks.php?cat_id={$results[idx].data.weblink_cat}&amp;weblink_id={$results[idx].data.weblink_id}' target='_blank'>{$results[idx].data.weblink_name}</a>
	<br />
	{if $results[idx].data.weblink_description}
		{$results[idx].data.weblink_description}<br />
	{/if}
	<span class='small'><font class='small'>{$locale.451}</font> {$results[idx].data.weblink_datestamp|date_format:"longdate"} |
	<span class='small'>{$locale.456}</span> {$results[idx].data.weblink_count}</span>
{/if}
{if !$smarty.section.idx.last}
<br />{if $stype != "m"}<br />{/if}
{/if}
{/section}
{include file="_closetable.tpl"}
{if $result_count > $items_per_page}
	{makepagenav start=$rowstart count=$items_per_page total=$result_count range=4 link=$smarty.const.FUSION_SELF|cat:"?stype="|cat:$stype|cat:"&amp;stext="|cat:$searchtext|cat:"&amp;"}
{/if}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}