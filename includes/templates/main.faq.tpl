{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.faq.tpl                                         *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-11 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates the PLi-Fusion panel: faq                       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
{if $cat_id|default:0 == 0}
	{math equation="(100-x)/x" x=$columns|default:2 format="%u" assign="colwidth"}							{* width per column  *}
	{if $columns == 1}{assign var="colcount" value="1"}
	{elseif $columns == 2}{assign var="colcount" value="1,2"}
	{elseif $columns == 3}{assign var="colcount" value="1,2,3"}
	{/if}
	{section name=faq loop=$faqs}
		{cycle name=column values=$colcount assign="column" print=no} 										{* keep track of the current column *}
		{if $smarty.section.faq.first}
			{math equation="x - (x%y)" x=$faqs|@count y=$columns|default:2 format="%u" assign="fullrows"}
			{math equation="x - y" x=$faqs|@count y=$fullrows format="%u" assign="remainder"}
			{if $remainder > 0}
				{math equation="(100 - z + y) / (z - y)" y=$fullrows z=$faqs|@count format="%u" assign="lastwidth"}	{* width last rows columns *}
			{/if}
			<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
		{/if}
		{if $column == 1}<tr>{/if}
		{if $smarty.section.faq.last && $smarty.section.faq.iteration > $fullrows}
			<td width='{$lastwidth}%' colspan='{math equation="1+(x-y)*2" x=$columns y=$remainder}' class='tbl1' style='vertical-align:top'>
		{else}
			<td width='{$colwidth}%' class='tbl1' style='vertical-align:top'>
		{/if}
				<a href='{$smarty.const.FUSION_SELF}?cat_id={$faqs[faq].faq_cat_id}'>{$faqs[faq].faq_cat_name}</a>
				<span class='small2'>({$faqs[faq].count})</span>
				{if $faqs[faq].faq_cat_description|default:"" != ""}
					<br />
					<span class='small'>{$faqs[faq].faq_cat_description}</span>
				{/if}
			</td>
		{if $column == $columns}</tr>{/if}
	{if $smarty.section.faq.last}
		{if $column != $columns}
			</tr>
		{/if}
	</table>
	{/if}
	{sectionelse}
		<center>
			<br />
			{$locale.410}
			<br /><br />
		</center>
	{/section}
	{include file="_closetable.tpl"}
{else}
	{section name=faq loop=$faqs}
		<b>{$locale.420}</b> {$faqs[faq].faq_question}
		<br />
		<b>{$locale.421}</b> {$faqs[faq].faq_answer}
		{if !$smarty.section.faq.last}
		<br /><br />
		{/if}
	{/section}
	{include file="_closetable.tpl"}
	{if $rows > $items_per_page}
		{makepagenav start=$rowstart count=$items_per_page total=$rows range=$settings.navbar_range}
	{/if}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
