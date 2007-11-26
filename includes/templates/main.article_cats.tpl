{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.article_cats.tpl                                *}
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
{* Template for the main module 'article_cats'                             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{section name=cat loop=$article_cats}
{if $smarty.section.cat.first}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
{/if}
	<tr>
		<td width='1%' class='tbl1' style='vertical-align:top'>
			{if $article_cats[cat].article_cat_image|default:"" != ""}
				<img src='{$smarty.const.IMAGES_NC}{$article_cats[cat].article_cat_image}' alt='{$article_cats[cat].article_cat_name}' />
			{/if}
		</td>
		<td class='tbl1' style='vertical-align:top'>
			<table width='100%' cellspacing='0' cellpadding='0'>
				<tr>
					<td align='left' class='infobar'>
						<b>{$locale.401}</b> {$article_cats[cat].article_cat_name} - 
						<b>{$locale.402}</b> {if $article_cats[cat].more}{$overview_limit} {$locale.408} {/if}{$article_cats[cat].itemcount}
						{if $article_cats[cat].more}
							<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.FUSION_SELF}?cat_id={$article_cats[cat].article_cat_id}'>{$locale.405}</a>
						{/if}
					</td>
				</tr>
				<tr>
					<td align='left' class='infobar'>
						<b>{$article_cats[cat].article_cat_description}</b>
					</td>
				</tr>
			</table>
			<table width='100%' cellspacing='0' cellpadding='0'>
				<tr>
					<td class='tbl1' style='vertical-align:top'>
					{section name=item loop=$article_cats[cat].items}
						<img src='{$smarty.const.THEME}images/bullet.gif' alt='' />
						{if $article_cats[cat].items[item].article_article == ""}
							{$article_cats[cat].items[item].article_subject}
						{else}
							<a href='readarticle.php?article_id={$article_cats[cat].items[item].article_id}'>{$article_cats[cat].items[item].article_subject}</a>
						{/if}
						<br />
						<div style='margin-left:8px;'>{$article_cats[cat].items[item].article_snippet}</div>
						{if !$smarty.section.item.last}<br />{/if}
					{sectionelse}
						<center>
							<br /><br />
							<b>{$locale.404}</b>
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