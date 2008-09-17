{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.tools.tpl                                      *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-11-05 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the administration webmasters toolbox module.              *}
{* Shows a iconised list of all available webmasters tools.                *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title="ExiteCMS v."|cat:$settings.version|cat:"."|cat:$settings.revision|cat:" &bull; "|cat:$locale.237|cat:" <b>"|cat:$settings.sitename|cat:"</b>"  state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	<tr>
		<td class='tbl1'>
			{counter start=1 print=false assign='column'}
			<table cellpadding='0' cellspacing='0' width='100%'>
				{section name=link loop=$modules}
					{if $column == 1}<tr>{/if}
					{counter print=false assign='column'}
					<td align='center' width='25%' class='tbl' style='vertical-align:top;'>
						{if $admin_images|default:false}
							<span class='small'>
								<a href='{$modules[link].admin_link}{$aidlink}'><img src='{$modules[link].admin_image}' alt='{$modules[link].admin_title|escape:"html"}' style='border:0px;' /></a>
								<br />
								<a href='{$modules[link].admin_link}{$aidlink}'>{$modules[link].admin_title|escape:"html"}</a>
								<br /><br />
							</span>
						{else}
							<span class='small'>
								<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /><a href='{$modules[link].admin_link}{$aidlink}'>{$modules[link].admin_title}</a>
							</span>
						{/if}
					</td>
					{if $column == 5}</tr>{counter start=1 print=false assign='column'}{/if}
				{sectionelse}
					<center><br />{$locale.401}<br /><br /></center>
				{/section}
				{if $column != 1}</tr>{/if}
			</table>
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
