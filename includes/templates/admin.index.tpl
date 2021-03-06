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
{* Template for the administration index module. Shows the administration  *}
{* main menu, with the four admin sections in a tabbed view                *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title="ExiteCMS v."|cat:$settings.version|cat:"."|cat:$settings.revision|cat:" &bull; "|cat:$locale.200|cat:" <b>"|cat:$settings.sitename|cat:"</b>"  state=$_state style=$_style}
{assign var='cols' value='0'}
<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	<tr>
		{if $adminpage1|default:0 != 0}
			{assign var='cols' value=$cols+1}
			<td align='center' width='20%' class='{if $pagenum == 1}tbl1{else}tbl2{/if}'>
				<span class='small'>
				{if $pagenum == 1}<b>{$locale.ac01}</b>{else}<a href='index.php{$aidlink}&amp;pagenum=1'><b>{$locale.ac01}</b></a>{/if}
				</span>
			</td>
		{/if}
		{if $adminpage2|default:0 != 0}
			{assign var='cols' value=$cols+1}
			<td align='center' width='20%' class='{if $pagenum == 2}tbl1{else}tbl2{/if}'>
				<span class='small'>
				{if $pagenum == 2}<b>{$locale.ac02}</b>{else}<a href='index.php{$aidlink}&amp;pagenum=2'><b>{$locale.ac02}</b></a>{/if}
				</span>
			</td>
		{/if}
		{if $adminpage3|default:0 != 0}
			{assign var='cols' value=$cols+1}
			<td align='center' width='20%' class='{if $pagenum == 3}tbl1{else}tbl2{/if}'>
				<span class='small'>
				{if $pagenum == 3}<b>{$locale.ac03}</b>{else}<a href='index.php{$aidlink}&amp;pagenum=3'><b>{$locale.ac03}</b></a>{/if}
				</span>
			</td>
		{/if}
		{if $adminpage4|default:0 != 0}
			{assign var='cols' value=$cols+1}
			<td align='center' width='20%' class='{if $pagenum == 4}tbl1{else}tbl2{/if}'>
				<span class='small'>
				{if $pagenum == 4}<b>{$locale.ac04}</b>{else}<a href='index.php{$aidlink}&amp;pagenum=4'><b>{$locale.ac04}</b></a>{/if}
				</span>
			</td>
		{/if}
	</tr>
	<tr>
		<td colspan='{$cols}' class='tbl1'>
			{counter start=1 print=false assign='column'}
			<table cellpadding='0' cellspacing='0' width='100%'>
				{section name=link loop=$modules}
					{if $column == 1}<tr>{/if}
					{counter print=false assign='column'}
					<td align='center' valign='top' width='25%' class='tbl'>
						{if $admin_images|default:false}
							<span class='small'>
								<a href='{$modules[link].admin_link}{$aidlink}'><img src='{$modules[link].admin_image}' height='48' alt='{$modules[link].admin_title|escape:"html"}' style='border:0px;' /></a>
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
{include file="_opentable.tpl" name=$_name title=$locale.250 state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='0' width='100%'>
	<tr>
		<td valign='top' width='33%' class='small'>
			{$locale.251} {$statistics.members_registered}<br />
			{$locale.252} {$statistics.members_unactive}<br />
			{$locale.253} {$statistics.members_suspended}<br />
			{$locale.255} {$statistics.members_deleted}<br />
		</td>
		<td valign='top' width='33%' class='small'>
			{$locale.259} {$statistics.posts}<br />
		</td>
		<td valign='top' width='33%' class='small'>
			{$locale.257} {$statistics.comments}<br />
			{$locale.258} {$statistics.shouts}<br />
		</td>
	</tr>
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
