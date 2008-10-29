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
{* This template is is called from the custom smarty function MakePageNav  *}
{*                                                                         *}
{***************************************************************************}
<table align='center' cellspacing='1' cellpadding='1' border='0' class='tbl-border' style='margin-top:3px;margin-bottom:3px;'>
	<tr>
		<td class='tbl2'>
			{$locale.052}{$mpn_cur_page}{$locale.053}{$mpn_pg_cnt}
		</td>
		{if $mpn_is_back}
		{if $mpn_is_farback}
		<td class='tbl2'>
			<a href='{$mpn_link}rowstart=0'><img src='{$smarty.const.THEME}images/control_start.gif' alt=''></a>
		</td>
		{/if}
		<td class='tbl2'>
			<a href='{$mpn_link}rowstart={$mpn_idx_back}'><img src='{$smarty.const.THEME}images/control_rewind.gif' alt=''></a>
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
			<a href='{$mpn_link}rowstart={$mpn_idx_next}'><img src='{$smarty.const.THEME}images/control_fastforward.gif' alt=''></a>
		</td>
		{/if}
		{if $mpn_is_farfwd}
		<td class='tbl2'>
			<a href='{$mpn_link}rowstart={$mpn_last_row}'><img src='{$smarty.const.THEME}images/control_end.gif' alt=''></a>
		</td>
		{/if}
	</tr>
</table>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
