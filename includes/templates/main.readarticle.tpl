{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.readarticle.tpl                                 *}
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
{* Template for the main module 'readarticle', to display a full article   *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.029 state=$_state style=$_style}
		{section name=item loop=$article}	
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<div style='width:100%;vertical-align:top;'>
							{$article[item].article_article}
						</div>
					</td>
				</tr>
			</table>
			<div style='margin-top:5px'>
				{if $allow_edit}<form name='editarticle{$article[item].article_id}' method='post' action='/administration/articles.php{$aidlink}&amp;article_id={$article[item].article_id}'>{/if}
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr>
							<td class='infobar'>
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> {$locale.040}
								{if $smarty.const.iMEMBER}<a href='profile.php?lookup={$article[item].user_id}'>{/if}
								{$article[item].user_name}
								{if $smarty.const.iMEMBER}</a>{/if}
								{$locale.041} {$article[item].article_datestamp|date_format:"longdate"}
							</td>
							<td align='right' class='infobar'>
								{$article[item].article_reads} {$locale.044}&nbsp;
								<a href='print.php?type=N&amp;item_id={$article[item].article_id}'> <img src='{$smarty.const.THEME}images/printer.gif' alt='{$locale.045}' style='border:0px;vertical-align:middle;' /></a>
							 	{if $allow_edit}
							 	 &middot; <input type='hidden' name='edit' value='edit' /><a href='javascript:document.editarticle{$article[item].article_id}.submit();'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.048}' title='{$locale.048}' style='vertical-align:middle;border:0px;' /></a>
							 	{/if}
							</td>
						</tr>
					</table>
				{if $allow_edit}</form>{/if}
			</div>
			{if $article[item].pagecount > 1}
				<br />
				{makepagenav start=$rowstart count=1 total=$article[item].pagecount range=$settings.navbar_range link=$smarty.const.FUSION_SELF|cat:"?article_id="|cat:$article[item].$article_id|cat:"&amp;"}
			{/if}
			{if $smarty.section.item.last}{else}<hr />{/if}
		{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}