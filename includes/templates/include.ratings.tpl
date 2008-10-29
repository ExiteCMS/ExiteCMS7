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
{* Template for the include module: 'ratings_include'                      *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.r100 state=$_state style=$_style}
{if !$smarty.const.iMEMBER}
	<div align='center'>{$locale.r104}</div>
{elseif $rating_exists}
	<form name='removerating' method='post' action='{$rating_link}'>
		<div align='center'>{$rating_text|string_format:$locale.r105} {$locale.r111} {$rating_datestamp|date_format:"longdate"}<br /><br />
			<input type='submit' name='remove_rating' value='{$locale.r102}' class='button' />
			<br /><br />
		</div>
	</form>
{else}
	<form name='postrating' method='post' action='{$rating_link}'>
		<div align='center'>
			{$locale.r106}:&nbsp;
			<select name='rating' class='textbox'>
				<option value='0'>{$locale.r107}</option>
				{section name=rating loop=$ratings}
				<option value='{$ratings[rating].rating}'>{$ratings[rating].info}</option>
				{/section}
			</select>
			<input type='submit' name='post_rating' value='{$locale.r103}' class='button' />
			<br /><br />
		</div>
	</form>
{/if}
{if $total_votes|default:0 != 0}
	<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
		<tr>
			<td>
				<table align='center' cellpadding='0' cellspacing='0'>
					{section name=rating loop=$ratings}
					<tr>
						<td class='{cycle values="tbl1,tbl2" advance=false}'>
							{$ratings[rating].info}
						</td>
						<td width='250' class='{cycle values="tbl1,tbl2" advance=false}'>
							<img src='{$smarty.const.THEME}images/pollbar.gif' alt='{$ratings[rating].info}' height='12' width='{$ratings[rating].pct_rating}%' class='poll' />
						</td>
						<td class='{cycle values="tbl1,tbl2" advance=false}'>
							{$ratings[rating].pct_rating}%
						</td>
						<td class='{cycle values="tbl1,tbl2"}'>
							&nbsp;{$ratings[rating].votecount}&nbsp;
						</td>
					</tr>
					{/section}
				</table>
			</td>
		</tr>
	</table>
{else}
	<div align='center'>
		<b>{$locale.r101}</b>
	</div>
{/if}
<br />
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
