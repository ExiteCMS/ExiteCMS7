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
	{assign var="columns" value="2"} 													{* number of columns *}
	{math equation="(100 - x) / x" x=$columns format="%u" assign="colwidth"}
	{section name=faq loop=$faqs}
		{cycle name=column values="1,2" assign="column" print=no} 						{* keep track of the current column *}
		{if $smarty.section.faq.first}
		<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		{/if}
			{if $column == 1}<tr>{/if}
			<td align='center' class='tbl1'>
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
				{section name=dummy start=$column loop=$columns}
					<td width='{math equation='x+1' x=$colwidth}%' colspan='2' align='center' class='tbl1'>
					-
					</td>
				{/section}
				</tr>
			{/if}
		</table>
		{/if}
		{include file="_closetable.tpl"}
	{sectionelse}
		<center>
			<br />
			{$locale.410}
			<br /><br />
		</center>
		{include file="_closetable.tpl"}
	{/section}
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
		{makepagenav start=$rowstart count=$items_per_page total=$rows range=3}
	{/if}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}