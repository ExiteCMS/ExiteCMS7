{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.upgrade.tpl                                    *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-11 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin administration module 'upgrade'                  *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{if $smarty.const.UPGRADES}
{if $stage == "2"}
	{section name=rev loop=$results}
	{if $smarty.section.rev.first}
	<br />
	<center>
	<b>{$locale.404}</b>
	</center>
	<br />
	<table align='center' cellpadding='0' cellspacing='1' width='90%' class='tbl-border'>
		<tr>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap;'>
				<b>{$locale.410} </b>
			</td>
			<td align='left' class='tbl2'>
				<b>{$locale.414}</b>
			</td>
		</tr>
	{/if}
		<tr>
			<td align='center' width='1%' class='tbl1' style='white-space:nowrap;'>
				{$results[rev].revision}
			</td>
			<td align='left' class='tbl1'>
				{foreach from=$results[rev].errors item=error}
				<img src='{$smarty.const.THEME}images/bullet.gif' /> {$results[rev].errtype}:
				<div style='margin-left:8px;'><span class='small2'>{$error}</span></div>
				<br />
				{/foreach}
			</td>
		</tr>
	{if $smarty.section.rev.last}
	</table>
	<br />
	<center>
	<b>{$locale.406}</b>
	</center>
	<br />
	{/if}
	{sectionelse}
	<br />
	<center>
	<b>{$revision|string_format:$locale.405}</b>
	</center>
	<br />
	{/section}
{else}
	<br />
	<center>
	<b>{$locale.403}</b>
	</center>
	<br />
	<table align='center' cellpadding='0' cellspacing='1' width='90%' class='tbl-border'>
		<tr>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap;'>
				<b>{$locale.410} </b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap;'>
				<b>{$locale.411}</b>
			</td>
			<td align='left' class='tbl2'>
				<b>{$locale.412}</b>
			</td>
		</tr>
		{section name=rev loop=$revisions}
		<tr>
			<td align='center' valign='top' width='1%' class='tbl1' style='white-space:nowrap;'>
				{$revisions[rev].revision}
			</td>
			<td align='center' valign='top' width='1%' class='tbl1' style='white-space:nowrap;'>
				{$revisions[rev].date|date_format:"forumdate"}
			</td>
			<td align='left' class='tbl1'>
				<div class='{$revisions[rev].class|default:"rev_title"}'>{$revisions[rev].title}</div>
				<br />
				<div class='rev_desc'>{$revisions[rev].description}</div>
				{if $revisions[rev].footer}
					<br />
					<div class='{$revisions[rev].class|default:"rev_title"}'>{$revisions[rev].footer}</div>
				{/if}
			</td>
		</tr>
		{/section}
	</table>
	<br />
	<center>
	<form name='upgradeform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<input type='hidden' name='stage' value='2'>
	<input type='submit' name='upgrade' value='{$locale.413}' class='button'>
	</form>
	</center>
	<br />
{/if}
{else}
<center>
	<br />
	<b>{$locale.401}</b>
	{if $new_upgrades|default:false}
	<br /><br />
	{$locale.402}
	{/if}
	<br /><br />
</center>
{/if}
{include file="_closetable.tpl"}
{if $smarty.const.UPGRADED}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
	<br />
	<center>
	<b>{$locale.407}</b>
	</center>
	<br />
	<table align='center' cellpadding='0' cellspacing='1' width='90%' class='tbl-border'>
		<tr>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap;'>
				<b>{$locale.410} </b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap;'>
				<b>{$locale.411}</b>
			</td>
			<td align='left' class='tbl2'>
				<b>{$locale.412}</b>
			</td>
		</tr>
		{section name=rev loop=$revisions_installed}
		<tr>
			<td align='center' valign='top' width='1%' class='tbl1' style='white-space:nowrap;'>
				{$revisions_installed[rev].revision}
			</td>
			<td align='center' valign='top' width='1%' class='tbl1' style='white-space:nowrap;'>
				{$revisions_installed[rev].date|date_format:"forumdate"}
			</td>
			<td align='left' class='tbl1'>
				<div class='rev_title'>{$revisions_installed[rev].title}</div>
				<br />
				<div class='rev_desc'>{$revisions_installed[rev].description}</div>				
				{if $revisions_installed[rev].footer}
					<br />
					<div class='rev_title'>{$revisions_installed[rev].footer}</div>
				{/if}
			</td>
		</tr>
		{/section}
	</table>
	<br />
	<center>
	<b>{$locale.430}</b>
	</center>
	<br />
{include file="_closetable.tpl"}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}