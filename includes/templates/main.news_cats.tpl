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
{* Template for the main module 'news_cats'                                *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{section name=cat loop=$news_cats}
{if $smarty.section.cat.first}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
{/if}
	<tr>
		<td width='1%' class='tbl1' style='vertical-align:top'>
			{if $news_cats[cat].news_cat_image|default:"" != ""}
				<img src='{$smarty.const.IMAGES_NC}{$news_cats[cat].news_cat_image}' alt='{$news_cats[cat].news_cat_name}' />
			{/if}
		</td>
		<td class='tbl1' style='vertical-align:top'>
			<table width='100%' cellspacing='0' cellpadding='0'>
				<tr>
					<td align='left' class='infobar'>
						<b>{$locale.401}</b> {$news_cats[cat].news_cat_name} - 
						<b>{$locale.402}</b> {$news_cats[cat].itemcount}
					</td>
					{if $news_cats[cat].more}
					<td align='right' class='infobar'>
						<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.FUSION_SELF}?cat_id={$news_cats[cat].news_cat_id}'>{$locale.405}</a>
					</td>
					{/if}
				</tr>
			</table>
			<table width='100%' cellspacing='0' cellpadding='0'>
				<tr>
					<td class='tbl1' style='vertical-align:top'>
					{section name=item loop=$news_cats[cat].items}
						<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='news.php?readmore={$news_cats[cat].items[item].news_id}'>{$news_cats[cat].items[item].news_subject|escape}</a>
						<br />
					{sectionelse}
						<center>
							<br />
							{$locale.404}
							<br /><br />
						</center>
					{/section}
					</td>
				</tr>
			</table>
		</td>
	</tr>
{if $smarty.section.cat.last}
	{if $show_all}
	<tr>
		<td colspan='2' class='tbl1' style='text-align:center'>
			<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.FUSION_SELF}'>{$locale.406}</a> <img src='{$smarty.const.THEME}images/bulletb.gif' alt='' />
		</td>
	</tr>
	{/if}
</table>
{/if}
{sectionelse}
	<center>
		<br />
		<b>{$locale.407}</b>
		<br /><br />
	</center>
{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
