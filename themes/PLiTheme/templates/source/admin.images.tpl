{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.images.tpl                               *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-20 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'images'                          *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.420 state=$_state style=$_style}
<form name='uploadform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;ifolder={$ifolder}' enctype='multipart/form-data'>
	<table align='center' cellpadding='0' cellspacing='0' width='350'>
		<tr>
			<td width='80' class='tbl'>
				{$locale.421}
			</td>
			<td class='tbl'>
				<input type='file' name='myfile' class='textbox' style='width:250px;' />
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<input type='submit' name='uploadimage' value='{$locale.420}' class='button' style='width:100px;'>
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{if $view|default:"" != ""}
	{include file="_opentable.tpl" name=$_name title=$locale.440 state=$_state style=$_style}
	<center>
		<br />
		{if $view_image|default:"" != ""}
			<img src='{$view_image}' alt='{$view}' />
		{else}
			{$locale.441}
		{/if}
		<br /><br >
		<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;ifolder={$ifolder}&amp;del={$view}'><img src='{$smarty.const.THEME}forum/delete.gif' alt='{$locale.442}' title='{$locale.442}' /></a>&nbsp;
		<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;ifolder={$ifolder}'><img src='{$smarty.const.THEME}forum/cancel.gif' alt='{$locale.402}' title='{$locale.402}' /></a>
		<br /><br />
	</center>
	{include file="_closetable.tpl"}
{else}
	{include file="_opentable.tpl" name=$_name title=$locale.460 state=$_state style=$_style}
	<table align='center' cellpadding='0' cellspacing='1' width='450' class='tbl-border'>
		<tr>
			<td align='center' colspan='2' class='tbl2'>
				{section name=id loop=$image_cats}
				<span style='font-weight:{if $image_cats[id].selected}bold{else}normal{/if}'><a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;ifolder={$image_cats[id].folder}'>{$image_cats[id].name}</a></span>{if !$smarty.section.id.last} |{/if}
				{/section}
			</td>
		</tr>
		{foreach from=$image_list item=image name=image_list}
		<tr>
			<td class='{cycle values='tbl1,tbl2' advance=no}'>
				{$image}
			</td>
			<td align='center' width='50' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;ifolder={$ifolder}&amp;view={$image}'><img src='{$smarty.const.THEME}images/image_view.gif' alt='{$locale.461}' title='{$locale.461}' /></a>&nbsp;
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;ifolder={$ifolder}&amp;del={$image}'><img src='{$smarty.const.THEME}images/image_delete.gif' alt='{$locale.462}' title='{$locale.462}' /></a>
			</td>
		</tr>
		{if $smarty.foreach.image_list.last && $settings.tinymce_enabled}
			<tr>
				<td align='center' colspan='2' class='{cycle values='tbl1,tbl2'}'>
					<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;ifolder={$ifolder}&amp;action=update'>{$locale.464}</a>
				</td>
			</tr>
		{/if}
		{foreachelse}
		<tr>
			<td align='center' class='tbl1'>
				{$locale.463}
			</td>
		</tr>
		{/foreach}
	</table>
	{include file="_closetable.tpl"}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}