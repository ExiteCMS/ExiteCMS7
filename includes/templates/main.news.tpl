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
{* Template for the main module 'news'. The number of columns is defined   *}
{* in the variable _maxcols. If not defined, the default = single column   *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.029 state=$_state style=$_style}
{assign var='_maxcols' value=$_maxcols|default:1}
{section name=column loop=$news}
	{if !$smarty.section.column.first && $_table_open|default:0 == 0}
		<table width='100%' cellpadding='0' cellspacing='0'>
			<tr>
		{assign var='_table_open' value='1'}
	{/if}
	{if !$smarty.section.column.first && $_table_open|default:0 == 1}
		<td style='width:{math equation='100/x' x=$_maxcols format='%u'}%;vertical-align:top;'>
	{/if}
	{section name=item loop=$news[column]}
		<table width='100%' cellspacing='0' cellpadding='0'>
			<tr>
				<td class='infobar'>
					<b><a name='news_{$news[column][item].news_id}' id='news_{$news[column][item].news_id}'></a>{$news[column][item].news_subject|escape}</b>
				</td>
			</tr>
		</table>
		<table width='100%' cellspacing='0' cellpadding='0' style='margin-top:5px;margin-bottom:5px;'>
			<tr>
				<td style='width:100%;vertical-align:top;'>
					<a href='news_cats.php?cat_id={$news[column][item].news_cat_id}'>
					<img src='{$smarty.const.IMAGES_NC}{$news[column][item].news_cat_image}' alt='{$news[column][item].news_cat_name}' align='left' style='border:0px;margin-top:3px;margin-right:5px' /></a>
					{$news[column][item].news_news|escape:"amp"}<br />
				</td>
			</tr>
		</table>
		{if $allow_edit}<form name='editnews{$news[column][item].news_id}' method='post' action='/administration/news.php{$aidlink}&amp;news_id={$news[column][item].news_id}'>{/if}
			<table width='100%' cellspacing='0' cellpadding='0'>
				<tr>
					<td align='center' class='infobar'>
						<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> {$locale.040}
						{if $smarty.const.iMEMBER}<a href='profile.php?lookup={$news[column][item].user_id}'>{/if}
						{$news[column][item].user_name}{if $smarty.const.iMEMBER}</a>{/if}
						{if $_maxcols == 3}
							<img src='{$smarty.const.THEME}images/bulletb.gif' alt='' /><br />
							<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
						{/if}
						{$locale.041} {$news[column][item].news_datestamp|date_format:"longdate"}
						{if $_table_open}
							<img src='{$smarty.const.THEME}images/bulletb.gif' alt='' /><br />
							<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
						{else}
							&middot;
						{/if}
						{if $news[column][item].news_extended}<a href='news.php?readmore={$news[column][item].news_id}'>{$locale.042}</a> &middot;{/if}
						{if $news[column][item].allow_comments}
							<a href='news.php?readmore={$news[column][item].news_id}#comments'>{$news[column][item].news_comments} {$locale.043}</a> &middot;
							{if $_maxcols == 3}
								<img src='{$smarty.const.THEME}images/bulletb.gif' alt='' /><br />
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
							{/if}
						{/if}
						 {$news[column][item].news_reads} {$locale.044} &middot;
						 <a href='print.php?type=N&amp;item_id={$news[column][item].news_id}'> <img src='{$smarty.const.THEME}images/printer.gif' alt='{$locale.045}' style='border:0px;vertical-align:middle;' /></a>
					 	{if $allow_edit}
					 	 &middot; <input type='hidden' name='edit' value='edit' /><a href='javascript:document.editnews{$news[column][item].news_id}.submit();'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.048}' title='{$locale.048}' style='vertical-align:middle;border:0px;' /></a>
					 	{/if}
						<img src='{$smarty.const.THEME}images/bulletb.gif' alt='' />
					</td>
				</tr>
			</table>
		{if $allow_edit}</form>{/if}
		{if $smarty.section.column.first}
			 {if !$smarty.section.column.last || !$smarty.section.item.last}<hr />{/if}
		{else}
			{if !$smarty.section.item.last}<hr />{/if}
		{/if}
	{/section}
	{if !$smarty.section.column.first && $_table_open|default:0 == 1}
		</td>
		{if !$smarty.section.column.last}
			<td style='width:10px'><img src='{$smarty.const.THEME}images/blank.gif' alt='' width='10' height='1' /></td>
		{/if}
	{/if}
{/section}
{if $_table_open|default:0 == 1}
	</tr>
</table>
{/if}
{include file="_closetable.tpl"}
{if $rows > $items_per_page}
	{makepagenav start=$rowstart count=$items_per_page total=$rows range=$settings.navbar_range}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
