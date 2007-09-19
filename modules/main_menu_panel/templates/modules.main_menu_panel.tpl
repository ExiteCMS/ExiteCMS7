{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: menu_panel.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-02 - WW - Initial version                                       *}
{* 2007-09-01 - WW - Added foldable submenu's (on MickeyMs suggestion)     *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates the PLi-Fusion infusion panel: menu_panel       *}
{*                                                                         *}
{***************************************************************************}
{section name=link loop=$linkinfo}
	{if $smarty.section.link.first}
		{include file="_openside.tpl" name=$_name title=$_title state=$_state style=$_style}
	{/if}
	{if $linkinfo[link].menu_first && $linkinfo[link].link_parent != 0}
		{if $linkinfo[link].div_state == 0}
		<div id='box_menu{$linkinfo[link].link_parent}' style='display:block;'>
		{else}
		<div id='box_menu{$linkinfo[link].link_parent}' style='display:none;'>
		{/if}
	{/if}
	{if $linkinfo[link].link_url == "---"}
		{if $linkinfo[link].link_name == "---"}
			<hr class='side-hr' />
		{else}
			{if $linkinfo[link].has_submenu}
				<div style='cursor:pointer;' onclick="javascript:flipMenu('{$linkinfo[link].menu_depth+1}', 'menu{$linkinfo[link].link_id}')">
					{if $linkinfo[link].menu_state == 0}
					<div class='side-label-button'><img src='{$smarty.const.THEME}images/menu_off.gif' alt='close' name='b_menu{$linkinfo[link].link_id}' /></div>
					{else}
					<div class='side-label-button'><img src='{$smarty.const.THEME}images/menu_on.gif' alt='open' name='b_menu{$linkinfo[link].link_id}' /></div>
					{/if}
					<div class='side-label'>
						{section name=depth start=0 loop=$linkinfo[link].menu_depth}
						<img src='{$smarty.const.IMAGES}spacer.gif' width='4' height='6' alt='' />
						{/section}
						{$linkinfo[link].link_name}
					</div>
				</div>
			{else}
				<div class='side-label'>
					{section name=depth start=0 loop=$linkinfo[link].menu_depth}
					<img src='{$smarty.const.IMAGES}spacer.gif' width='4' height='6' alt='' />
					{/section}
					{$linkinfo[link].link_name}
				</div>
			{/if}
		{/if}
	{else}
		<div class='side-label-link'>
		{section name=depth start=0 loop=$linkinfo[link].menu_depth}
		<img src='{$smarty.const.IMAGES}spacer.gif' width='4' height='6' alt='' />
		{/section}
		<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$linkinfo[link].link_url}' {if $linkinfo[link].link_window|default:0 == 1}target='_blank' {/if}class='side'>{$linkinfo[link].link_name}</a><br />
		</div>
	{/if}
	{if $linkinfo[link].menu_last && $linkinfo[link].link_parent != 0}
		</div>
	{/if}
	{if $smarty.section.link.last}
		{include file="_closeside.tpl"}
	{/if}
{/section}
<script type='text/javascript'>
function flipMenu(depth, divname) {ldelim}

{if $close_open_submenus|default:true}
	// close all open menu's
	{section name=link loop=$linkinfo}
		{if $linkinfo[link].menu_first && $linkinfo[link].link_parent != 0}
		if (depth == '{$linkinfo[link].menu_depth}' && 'menu{$linkinfo[link].link_parent}' != divname) {ldelim}
			if (document.images['b_menu{$linkinfo[link].link_parent}'].src.indexOf('_on') == -1) {ldelim}
				tmp = document.images['b_menu{$linkinfo[link].link_parent}'].src.replace('_off', '_on');
				document.getElementById('box_menu{$linkinfo[link].link_parent}').style.display = 'none';
				document.images['b_menu{$linkinfo[link].link_parent}'].src = tmp;
			{rdelim}
		{rdelim}
		{/if}
	{/section}
{/if}
	// and flip the display state of the menu	
	flipBox(divname);
{rdelim}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}