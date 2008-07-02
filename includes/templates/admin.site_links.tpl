{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.site_links.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-22 - WW - Initial version                                       *}
{* 2007-11-21 - WW - moved edit panel to a separate template               *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin configuration module 'site_links'                *}
{*                                                                         *}
{***************************************************************************}
{if $action == "edit" || $action == "add"}
	{if $settings.sitelinks_localisation == "multiple"}
		{assign var="tabletitle" value=$_title|cat:" "|cat:$locale.452|cat:" '<b>"|cat:$link_locale|cat:"</b>'"}
	{else}
		{assign var="tabletitle" value=$_title}
	{/if}
	{include file="_opentable.tpl" name=$_name title=$tabletitle state=$_state style=$_style}
	<form name='layoutform' method='post' action='{$formaction}'>
		<table align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl' valign='top'>
					{$locale.445}
				</td>
				<td class='tbl'>
					<select name='panel_filename' id='panel_name' class='textbox' style='width:250px;' onchange='return updateparentlist();'>
					{foreach from=$panel_list item=panel}
						<option{if $panel_name == $panel} selected="selected"{/if}>{$panel}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl' valign='top'>
					{$locale.447}
				</td>
				<td class='tbl'>
					<select name='link_parent' id='link_parent' class='textbox' style='width:250px;'>
						<option>&nbsp;</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl' valign='top'>
					{$locale.420}
				</td>
				<td class='tbl'>
					<input type='text' name='link_name' value='{$link_name}' maxlength='100' class='textbox' style='width:250px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl' valign='top'>
					{$locale.421}
				</td>
				<td class='tbl'>
					<input type='text' name='link_url' value='{$link_url}' maxlength='200' class='textbox' style='width:350px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl' valign='top'>
				</td>
				<td class='tbl'>
					<input type='checkbox' name='link_aid' value='1'{if $link_aid} checked="checked"{/if} /> {$locale.450}
				</td>
			</tr>
			<tr>
				<td class='tbl' valign='top'>
				</td>
				<td class='tbl'>
					<input type='checkbox' name='link_window' value='1'{if $link_window} checked="checked"{/if} /> {$locale.428}
				</td>
			</tr>
			<tr>
				<td class='tbl' valign='top'>
					{$locale.423}
				</td>
				<td class='tbl'>
					<input type='text' name='link_order'  value='{$link_order}' maxlength='2' class='textbox' style='width:50px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl' valign='top'>
					{$locale.422}
				</td>
				<td class='tbl'>
					<select name='link_visibility' class='textbox'>
					{section name=id loop=$user_groups}
						<option value='{$user_groups[id].id}'{if $user_groups[id].selected} selected="selected"{/if}>{$user_groups[id].name}</option>
					{/section} 
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl' valign='top'>
					{$locale.424}
				</td>
				<td class='tbl'>
					<input type='radio' name='link_position' value='1'{if $link_position == 1} checked="checked"{/if} /> {$locale.425}
					<br />
					<input type='radio' name='link_position' value='2'{if $link_position == 2} checked="checked"{/if} /> {$locale.426}
					<br />
					<input type='radio' name='link_position' value='3'{if $link_position == 3} checked="checked"{/if} /> {$locale.427}
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<p class='small'>{$locale.446}</p>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<input type='hidden' name='link_locale' value='{$link_locale}' />
					<input type='submit' name='savelink' value='{$locale.429}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
{else}
	{section name=id loop=$panels}
		{include file="_opentable.tpl" name=$_name title=$panels[id].title state=$_state style=$_style}
		{if $settings.sitelinks_localisation == "multiple"}
			{assign var="url_locale" value="&amp;link_locale="|cat:$link_locale}
			<br />
			<div style='text-align:center;'>
				{$locale.451} {html_options name=link_locale options=$locales selected=$link_locale class="textbox" onchange="location = '"|cat:$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;link_locale=' + this.options[this.selectedIndex].value;"}
			</div>
		{else}
			{assign var="link_locale" value=""}
		{/if}
		<br />
		<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
			<tr>
				<td class='tbl2'>
					<b>{$locale.430}</b>
				</td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
					<b>{$locale.431}</b>
				</td>
				<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'>
					<b>{$locale.432}</b>
				</td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
					<b>{$locale.433}</b>
				</td>
			</tr>
			{section name=id2 loop=$panels[id].links}
			<tr>
				<td class='tbl1'>
					{if $panels[id].links[id2].link_position == 3}<i>{/if}
					{section name=depth start=0 loop=$panels[id].links[id2].menu_depth}
						<img src='{$smarty.const.IMAGES}spacer.gif' width='4' height='6' alt='' /> 
					{/section}
					{if $panels[id].links[id2].link_name != "---" && $panels[id].links[id2].link_url == "---"}
						<b>{$panels[id].links[id2].link_name}</b>
					{elseif $panels[id].links[id2].link_name == "---" && $panels[id].links[id2].link_url == "---"}
						<hr />
					{else}
						<img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> 
						{if $panels[id].links[id2].external}
							<a href='{$panels[id].links[id2].link_url}'>{$panels[id].links[id2].link_name}</a>
							<img src='{$smarty.const.THEME}images/external_link.gif' alt='' />
						{else}
							<a href='{$smarty.const.BASEDIR}{$panels[id].links[id2].link_url}'>{$panels[id].links[id2].link_name}</a>
						{/if}
					{/if}
					{if $panels[id].links[id2].link_position == 3}</i>{/if}
				</td>
				<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
					{$panels[id].links[id2].link_visibility_name}
				</td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
					{$panels[id].links[id2].link_order}
				</td>
				<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
				{if $panels[id].panel_count != 1}
					{if !$panels[id].links[id2].menu_first}
						{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=move&amp;swap="|cat:$panels[id].links[id2].up|cat:"&amp;with="|cat:$panels[id].links[id2].link_id|cat:"&amp;panel="|cat:$panels[id].panel|cat:$url_locale image="up.gif" alt=$locale.440 title=$locale.440}
					{/if}
					{if !$panels[id].links[id2].menu_last}
						{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=move&amp;swap="|cat:$panels[id].links[id2].down|cat:"&amp;with="|cat:$panels[id].links[id2].link_id|cat:"&amp;panel="|cat:$panels[id].panel|cat:$url_locale image="down.gif" alt=$locale.443 title=$locale.443}
					{/if}
				{/if}
				</td>
				<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=edit&amp;link_id="|cat:$panels[id].links[id2].link_id|cat:"&amp;panel="|cat:$panels[id].panel|cat:$url_locale image="page_edit.gif" alt=$locale.434 title=$locale.434}
					&nbsp;
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=delete&amp;link_id="|cat:$panels[id].links[id2].link_id|cat:"&amp;panel="|cat:$panels[id].panel|cat:$url_locale image="page_delete.gif" alt=$locale.435 title=$locale.435}
				</td>
			</tr>
			{sectionelse}
			<tr>
				<td align='center' colspan='5' class='tbl1'>
					{$locale.436}
					<br /><br />
					{buttonlink name=$locale.411 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=add&amp;panel="|cat:$panels[id].panel|cat:$url_locale}
				</td>
			</tr>
			{/section}
			{if $panels[id].panel_count > 0}
			<tr>
				<td align='center' colspan='5' class='tbl1'>
					{buttonlink name=$locale.411 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=add&amp;panel="|cat:$panels[id].panel|cat:$url_locale}
					&nbsp;
					{buttonlink name=$locale.444 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=refresh&amp;panel="|cat:$panels[id].panel|cat:$url_locale}
				</td>
			</tr>
			{/if}
		</table>
		{include file="_closetable.tpl"}
	{sectionelse}
		{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
		<br />
		{$locale.436}
		<br />
		{include file="_closetable.tpl"}
	{/section}
{/if}
<script type='text/javascript'>
var panels = new Array();

{foreach from=$panel_list key=key item=menu name=panel}
panels[{$smarty.foreach.panel.index}] = "{$menu}";
{/foreach}

var panelparents = new Array();
{section name=menu loop=$parents}

panelparents[{$smarty.section.menu.index}] = new Array();
	{section name=id loop=$parents[menu].parent_ids}
panelparents[{$smarty.section.menu.index}][{$smarty.section.id.index}] = new Array({$parents[menu].parent_ids[id].link_id}, "{$parents[menu].parent_ids[id].link_name}");
	{/section}
{/section}

update_link_parent('{$panel_name}');
{literal}

// make sure the dropdown is positioned on the current link parent
var i = 0;
var listLength = document.getElementById("link_parent").length;
for (i=0; i < listLength; i++) {
	if (document.getElementById("link_parent").options[i].value == {/literal}{$link_parent}{literal}) {
		document.getElementById("link_parent").options[i].selected = true;
		break;
	}
}

// called by a change of the panel_name dropdown, to update the contents of the parent link dropdown
function updateparentlist() {

	var selItem = document.getElementById("panel_name").options.selectedIndex;
	update_link_parent(document.getElementById("panel_name").options[selItem].text);
}

// called by updateparentlist, or at initial page load, with the name of the selected menu panel
// to prepopulate the parent link dropdown
function update_link_parent(menuname) {

	// empty the dropdown first
	document.getElementById("link_parent").length = 0;

	// find the selected menu index
	var listLength = panels.length;
	var i = 0;
	var j = -1;
	for (i=0; i < listLength; i++) {
		if (panels[i] == menuname) {
			j = i;
			break;
		}
	}

	// if found, fill the link_parent dropdown
	if (j != -1) {
		var i = 0;
		var listLength = panelparents[j].length;
		document.getElementById("link_parent").options[0] = new Option("", 0);
		for (i=0; i < listLength; i++) {
			document.getElementById("link_parent").options[i+1] = new Option(panelparents[j][i][1], panelparents[j][i][0]);
		}
	}
}
{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}