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
{* This template generates the ExiteCNS panel: menu_panel                  *}
{*                                                                         *}
{***************************************************************************}
{section name=link loop=$linkinfo}
	{if $smarty.section.link.first}
		{include file="_openside.tpl" name=$_name title=$_title state=$_state style=$_style}
	{/if}
	{if $linkinfo[link].menu_first && $linkinfo[link].menu_depth != 0}
		<div id='box_menu{$linkinfo[link].link_parent}' class='side-body-sub' style='display:none;'>
	{/if}
	{if $linkinfo[link].link_url == "---"}
		{if $linkinfo[link].link_name == "---"}
			<hr class='side-hr' />
		{else}
			{if $linkinfo[link].has_submenu}
				<div style='cursor:pointer;' onclick="javascript:flipMenu('{$linkinfo[link].menu_depth+1}', 'menu{$linkinfo[link].link_id}')">
					<div class='side-label-button'><img src='{$smarty.const.THEME}images/menu_on.gif' alt='open' name='b_menu{$linkinfo[link].link_id}' /></div>
					<div class='side-label'>
						{$linkinfo[link].link_name}
					</div>
				</div>
			{else}
				<div class='side-label'>{$linkinfo[link].link_name}</div>
			{/if}
		{/if}
	{else}
		<div class='side-label-link'>
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
