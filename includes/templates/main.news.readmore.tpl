{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.news.readmore.tpl                               *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-03 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'news', to display a full news item        *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.029 state=$_state style=$_style}
		{section name=item loop=$news}	
			<table width='100%'>
				<tr>
					<td class='infobar'>
						<b><a name='news_{$news[item].news_id}' id='news_{$news[item].news_id}'></a>{$news[item].news_subject|escape:"amp"}</b>
					</td>
				</tr>
			</table>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<div style='width:100%;vertical-align:top;'>
							<a href='news_cats.php?cat_id={$news[item].news_id}'>
							<img src='{$smarty.const.IMAGES_NC}{$news[item].news_cat_image}' alt='{$news[item].news_cat_name}' align='left' style='border:0px;margin-top:3px;margin-right:5px' /></a>
							{$news[item].news_news|escape:"amp"}
						</div>
					</td>
				</tr>
			</table>
			<div style='margin-top:5px'>
				{if $allow_edit}<form name='editnews{$news[item].news_id}' method='post' action='/administration/news.php?aid={$aidlink}&amp;news_id=$news[item].news_id}'>{/if}
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr>
							<td class='infobar'>
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> {$locale.040}
								{if $smarty.const.iMEMBER}<a href='profile.php?lookup={$news[item].user_id}'>{/if}
								{$news[item].user_name}
								{if $smarty.const.iMEMBER}</a>{/if}
								{$locale.041} {$news[item].news_datestamp|date_format:"longdate"}
							</td>
							<td align='right' class='infobar'>
								{$news[item].news_reads} {$locale.044} &middot;
								<a href='print.php?type=N&amp;item_id={$news[item].news_id}'> <img src='{$smarty.const.THEME}images/printer.gif' alt='{$locale.045}' style='border:0px;vertical-align:middle;' /></a>
							 	{if $allow_edit}
							 	 &middot; <input type='hidden' name='edit' value='edit' /><a href='javascript:document.editnews{$news[item].news_id}.submit();'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.048}' title='{$locale.048}' style='vertical-align:middle;border:0px;' /></a>
							 	{/if}
							</td>
						</tr>
					</table>
				{if $allow_edit}</form>{/if}
			</div>
			{if $smarty.section.item.last}{else}<hr />{/if}
		{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}