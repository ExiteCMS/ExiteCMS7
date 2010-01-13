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
{* Template for the admin configuration module 'blogs'                     *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
<form name='optionsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td class='tbl' width='70%'>
				{$locale.401}
			</td>
			<td class='tbl' width='30%'>
				<select name='blogs_indexsize' class='textbox'>
					{section name=idx loop=11 start=1}
						<option value='{$smarty.section.idx.index}' {if $blogs_indexsize == $smarty.section.idx.index} selected="selected"{/if}>{$smarty.section.idx.index}</option>
					{/section}
					{section name=idx loop=55 start=15 step=5}
						<option value='{$smarty.section.idx.index}' {if $blogs_indexsize == $smarty.section.idx.index} selected="selected"{/if}>{$smarty.section.idx.index}</option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='70%'>
				{$locale.402}
			</td>
			<td class='tbl' width='30%'>
				<select name='blogs_indexage' class='textbox'>
					<option value='0' {if $blogs_indexage == "0"} selected="selected"{/if}>{$locale.404}</option>
					{section name=idx loop=361 start=15 step=15}
					<option value='{$smarty.section.idx.index}' {if $blogs_indexage == $smarty.section.idx.index} selected="selected"{/if}>{$smarty.section.idx.index} {$locale.403}</option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='saveoptions' value='{$locale.405}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
