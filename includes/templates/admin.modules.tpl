{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.modules.tpl                                    *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-24 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin configuration module 'modules'                   *}
{*                                                                         *}
{***************************************************************************}
{if $is_error}
	{include file="_opentable.tpl" name=$_name title=$locale.400|escape state=$_state style=$_style}
	<center>
	{foreach name=id from=$mod_errors item=error}
		{$error}<br />
	{/foreach}
	</center>
	{include file="_closetable.tpl"}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.400|escape state=$_state style=$_style}
<center>
<form name='filter' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
<br />
{$locale.403}: 
<select class='textbox' name='filter'>
	<option value='0'>&nbsp;</option>
	<option value='3'{if $filter == 3} selected{/if}>{$locale.415}</option>
	<option value='4'{if $filter == 4} selected{/if}>{$locale.414}</option>
	<option value='2'{if $filter == 2} selected{/if}>{$locale.416}</option>
	<option value='1'{if $filter == 1} selected{/if}>{$locale.418}</option>
</select>
<input type='submit' name='go' value='{$locale.423}' class='button' />
</form>
</center>
<br />
{section name=id loop=$modules}
{if $smarty.section.id.first}
<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
{/if}
	<tr>
		<td colspan='5' class='tbl1'>
			<b>{if $modules[id].type == "P"}{$locale.404}{else}{$locale.405}{/if} {$modules[id].title}</b>
			{if $modules[id].description|default:"" != ""}
				<br />
				<span class='small'>{$modules[id].description}</span>
			{/if}
		</td>
	</tr>
	<tr>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.406}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.407}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.408}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.420}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.421}</b>
		</td>
	</tr>
	<tr>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			{$modules[id].version}
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			{$modules[id].developer}
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			{if $modules[id].email != ""}{buttonlink name=$locale.409 link="mailto:"|cat:$modules[id].email}&nbsp;&nbsp;{/if}
			{if $modules[id].url != ""}{buttonlink name=$locale.410 link=$modules[id].url}{/if}
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			{if $modules[id].status == 1}<font color='#FF0000'>{/if}	{* not compatible *}
			{if $modules[id].status == 2}<font color='#00AA00'>{/if}	{* upgrade available *}
			{if $modules[id].status == 3}<font color='#000000'>{/if}	{* installed *}
			{if $modules[id].status == 4}<font color='#0000FF'>{/if}	{* not installed *}
			{$modules[id].status_text}
			</font>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
		{if $modules[id].status == 2}
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=upgrade&amp;id={$modules[id].id}&amp;filter={$filter}'><img src='{$smarty.const.THEME}images/cog_go.gif' alt='{$locale.422}' title='{$locale.422}' /></a>
			&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=uninstall&amp;id={$modules[id].id}&amp;filter={$filter}' onclick='return UnInstall();'><img src='{$smarty.const.THEME}images/cog_delete.gif' alt='{$locale.411}' title='{$locale.411}' /></a>
		{/if}
		{if $modules[id].status == 3}
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=uninstall&amp;id={$modules[id].id}&amp;filter={$filter}' onclick='return UnInstall();'><img src='{$smarty.const.THEME}images/cog_delete.gif' alt='{$locale.411}' title='{$locale.411}' /></a>
		{/if}
		{if $modules[id].status == 4}
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=install&amp;module={$modules[id].folder}&amp;filter={$filter}'><img src='{$smarty.const.THEME}images/cog_add.gif' alt='{$locale.401}' title='{$locale.401}' /></a>
		{/if}
		</td>
	</tr>
	{if $modules[id].errors}
	<tr>
		<td colspan='5' class='tbl1'>
		<font color='#FF0000'>{$modules[id].errors}</font>
		</td>
	</tr>
	{/if}
	<tr>
		<td colspan='5' class='tbl1' height='5'>
		</td>
	</tr>
	{cycle values='tbl1,tbl2' print=no}
{if $smarty.section.id.last}
</table>
{/if}
{sectionelse}
	<center>
	<br />
	<b>{if $filter == 0}{$locale.424}{else}{$locale.425}{/if}</b>
	<br /><br />
	</center>
{/section}
{include file="_closetable.tpl"}
<script type='text/javascript'>
	function UnInstall() {ldelim}
		return confirm('{$locale.412}');
	{rdelim}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
