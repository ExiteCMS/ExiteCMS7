{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.blogs.tpl                                      *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-02-07 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin configuration module 'blogs'                     *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
<form name='optionsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td class='tbl' width='70%'>
				{$locale.401}
			</td>
			<td class='tbl' width='30%'>
				<select name='blogs_indexsize' class='textbox'>
					{section name=idx loop=55 start=5 step=5}
						<option value='{$smarty.section.idx.index}' {if $blogs_indexsize == $smarty.section.idx.index} selected="selected"{/if}>{$smarty.section.idx.index}</option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' width='70%'>
				{$locale.402}
			</td>
			<td class='tbl' width='30%'>
				<select name='blogs_indexage' class='textbox'>
					<option value='30' {if $blogs_indexage == "30"} selected="selected"{/if}>&nbsp; 30 {$locale.403}</option>
					<option value='60' {if $blogs_indexage == "60"} selected="selected"{/if}>&nbsp; 60 {$locale.403}</option>
					<option value='90' {if $blogs_indexage == "90"} selected="selected"{/if}>&nbsp; 90 {$locale.403}</option>
					<option value='120' {if $blogs_indexage == "120"} selected="selected"{/if}>120 {$locale.403}</option>
					<option value='150' {if $blogs_indexage == "150"} selected="selected"{/if}>150 {$locale.403}</option>
					<option value='180' {if $blogs_indexage == "180"} selected="selected"{/if}>180 {$locale.403}</option>
					<option value='210' {if $blogs_indexage == "210"} selected="selected"{/if}>210 {$locale.403}</option>
					<option value='240' {if $blogs_indexage == "240"} selected="selected"{/if}>240 {$locale.403}</option>
					<option value='270' {if $blogs_indexage == "270"} selected="selected"{/if}>270 {$locale.403}</option>
					<option value='300' {if $blogs_indexage == "300"} selected="selected"{/if}>300 {$locale.403}</option>
					<option value='330' {if $blogs_indexage == "330"} selected="selected"{/if}>330 {$locale.403}</option>
					<option value='360' {if $blogs_indexage == "360"} selected="selected"{/if}>360 {$locale.403}</option>
					<option value='0' {if $blogs_indexage == "0"} selected="selected"{/if}>{$locale.404}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='saveoptions' value='{$locale.405}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}