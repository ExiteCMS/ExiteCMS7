{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: _make_page_navigation.tpl                            *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-04 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template is is called from the custom smarty function MakePageNav  *}
{*                                                                         *}
{***************************************************************************}
<table align='center' cellspacing='1' cellpadding='1' border='0' class='tbl-border'>
	<tr>
		<td class='tbl2'>
			{$locale.052}{$mpn_cur_page}{$locale.053}{$mpn_pg_cnt}
		</td>
		{if $mpn_is_back}
		{if $mpn_is_farback}
		<td class='tbl2'>
			<a href='{$mpn_link}rowstart=0'>&lt;&lt;</a>
		</td>
		{/if}
		<td class='tbl2'>
			<a href='{$mpn_link}rowstart={$mpn_idx_back}'>&lt;</a>
		</td>
		{/if}
		{section name=page loop=$mpn_pages}
			{if $mpn_pages[page].current}
				<td class='tbl1'>
					<b>{$mpn_pages[page].count}</b>
				</td>
			{else}
				<td class='tbl1'>
					<a href='{$mpn_link}rowstart={$mpn_pages[page].offset}'>{$mpn_pages[page].count}</a>
				</td>
			{/if}
		{/section}
		{if $mpn_is_fwd}
		<td class='tbl2'>
			<a href='{$mpn_link}rowstart={$mpn_idx_next}'>&gt;</a>
		</td>
		{/if}
		{if $mpn_is_farfwd}
		<td class='tbl2'>
			<a href='{$mpn_link}rowstart={$mpn_last_row}'>&gt;&gt;</a>
		</td>
		{/if}
	</tr>
</table>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}