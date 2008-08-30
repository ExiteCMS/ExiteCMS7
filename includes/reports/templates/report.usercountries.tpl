{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: report.usercountries.tpl                             *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-08-08 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* report template include: generate an overview of users per country      *}
{*                                                                         *}
{***************************************************************************}
{if $action == "report"}
	<table width='100%' class='tbl-border' cellspacing='1'>
		<tr>
			<td align='left' class='tbl2' colspan='2'>
				<b>{$locale.rpt507}</b>
			</td>
			<td align='center' class='tbl2'>
				<b>{$locale.rpt508}</b>
			</td>
		</tr>
		{section name=id loop=$reportvars.output}
		<tr>
			<td align='right' width='1' class='{cycle values="tbl1,tbl2" advance=false}'>
				{$reportvars.output[id]._rownr}
			</td>
			<td align='left' class='{cycle values="tbl1,tbl2" advance=false}'>
				{if $reportvars.output[id].user_cc_code != ""}
					<a href='/members.php?order=username&sortby=all&field=username&country={$reportvars.output[id].user_cc_code}'>{$reportvars.output[id].country}</a>
				{else}
					{$reportvars.output[id].country}
				{/if}
			</td>
			<td align='center' class='{cycle values="tbl1,tbl2"}'>
				{$reportvars.output[id].count}
			</td>
		</tr>
		{sectionelse}
		<tr>
			<td align='center' class='tbl1' colspan='2'>
				<b>{$locale.rpt951}</b>
			</td>
		</tr>
		{/section}
	</table>
	{if $rows > $settings.numofthreads}
		<br />
		{makepagenav start=$rowstart count=$settings.numofthreads total=$rows range=3 link=$pagenav_url}
	{/if}
{else}
	<table width='100%'>
		<tr>
			<td align='left' colspan='2'>
				{$locale.rpt501}
				<select name='top' class='textbox'>
					{section name=cnt loop=55 start=5 step=5}
					<option value='{$smarty.section.cnt.index}'>{$locale.rpt502} {$smarty.section.cnt.index}</option>
					{/section}
					<option value='0'>{$locale.rpt503}</option>
				</select>
				{$locale.rpt504}
				<select name='sortorder' class='textbox'>
					<option value='0'>{$locale.rpt505}</option>
					<option value='1'>{$locale.rpt506}</option>
				</select>
			</td>
		</tr>
	</table>
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
