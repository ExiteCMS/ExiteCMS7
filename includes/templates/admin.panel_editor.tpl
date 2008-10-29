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
{* Template for the edit panel of the admin configuration module 'panels'  *}
{*                                                                         *}
{***************************************************************************}
{if $settings.panels_localisation == "multiple"}
	{assign var="tabletitle" value=$_title|cat:" "|cat:$locale.489|cat:" '<b>"|cat:$panel_locale|cat:"</b>'"}
{else}
	{assign var="tabletitle" value=$_title}
{/if}
{include file="_opentable.tpl" name=$_name title=$tabletitle state=$_state style=$_style}
<form name='editform' method='post' action='{$action}'>
	<table align='center' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='tbl'>
				{$locale.452}
			</td>
			<td class='tbl'>
				<input type='text' name='panel_name' value='{$panel_name}' class='textbox' style='width:300px;' />
			</td>
		</tr>
		{if $panel_id == 0 || $panel_type == "file"}
		<tr>
			<td class='tbl'>
				{$locale.453}
			</td>
			<td class='tbl'>
				<select name='panel_filename' class='textbox' style='width:250px;'>
				{section name=id loop=$panel_list}
					{if $panel_list[id].new_module}
						{if !$smarty.section.id.first}</optgroup>{/if}
						<optgroup label='{if $panel_list[id].mod_folder != ""}{$locale.474} {$panel_list[id].mod_folder}{/if}'>
						{assign var='hasvalues' value=false}
					{/if}
						<option value='{$panel_list[id].panel_filename}' {if $panel_list[id].selected}selected='selected'{/if}>{$panel_list[id].panel_name}</option>
						{assign var='hasvalues' value=true}
					{if $smarty.section.id.last && $hasvalues}</optgroup>{/if}
				{/section}
				</select>
				{if $panel_id == 0}<br /><span class='small2'>{$locale.454}</span>{/if}
			</td>
		</tr>
		{/if}
		{if $panel_id == 0 || $panel_type == "dynamic"}
		<script language="javascript" type="text/javascript" src="{$smarty.const.INCLUDES}editarea/edit_area_full_with_plugins.js"></script>
		{literal}
			<script language="javascript" type="text/javascript">
			editAreaLoader.init({
				id : "panel_code"		// textarea id
				,syntax: "php"			// syntax to be uses for highgliting
				,start_highlight: true		// to display with highlight mode on start-up
				,language: "{/literal}{$settings.locale_code}{literal}"
				,display:"later"
				,font_size: 8
			});
			editAreaLoader.init({
				id : "panel_template"		// textarea id
				,syntax: "smarty"			// syntax to be uses for highgliting
				,start_highlight: true		// to display with highlight mode on start-up
				,language: "{/literal}{$settings.locale_code}{literal}"
				,display:"later"
				,font_size: 8
			});
			</script>
		{/literal}
		<tr>
			<td valign='top' class='tbl'>
				{$locale.455}
			</td>
			<td class='tbl'>
				<textarea id='panel_code' name='panel_code' cols='80' rows='15' class='textbox' style='width:450px;'>{$panel_code}</textarea>
			</td>
		</tr>
		<tr>
			<td valign='top' class='tbl'>
				{$locale.465}
			</td>
			<td class='tbl'>
				<textarea id='panel_template' name='panel_template' cols='80' rows='15' class='textbox' style='width:450px;'>{$panel_template}</textarea>
			</td>
		</tr>
		{/if}
		{if $smarty.const.PANEL_SIDE_MOVE || $panel_id == '0'}
		<tr>
			<td class='tbl'>
				{$locale.456}
			</td>
			<td class='tbl'>
				<select name='panel_side' class='textbox' style='width:150px;' onchange="showopts(this.options[this.selectedIndex].value);">
					<option value='0'{if $panel_side == "0"} selected="selected"{/if}>{$locale.492}</option>
					<option value='1'{if $panel_side == "1"} selected="selected"{/if}>{$locale.466}</option>
					<option value='2'{if $panel_side == "2"} selected="selected"{/if}>{$locale.467}</option>
					<option value='3'{if $panel_side == "3"} selected="selected"{/if}>{$locale.469}</option>
					<option value='4'{if $panel_side == "4"} selected="selected"{/if}>{$locale.468}</option>
					<option value='5'{if $panel_side == "5"} selected="selected"{/if}>{$locale.493}</option>
				</select>
			</td>
		</tr>
		{/if}
		<tr>
			<td class='tbl'>
				{$locale.462}
			</td>
			<td class='tbl'>
				<select name='panel_state' class='textbox' style='width:150px;'>
					<option value='0'{if $panel_state == "0"} selected="selected"{/if}>{$locale.463}</option>
					<option value='1'{if $panel_state == "1"} selected="selected"{/if}>{$locale.464}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.461}
			</td>
			<td class='tbl'>
				<select name='panel_usermod' class='textbox' style='width:150px;'>
					<option value='0'{if $panel_usermod == "0"} selected="selected"{/if}>{$locale.447}</option>
					<option value='1'{if $panel_usermod == "1"} selected="selected"{/if}>{$locale.448}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.457}
			</td>
			<td class='tbl'>
				<select name='panel_access' class='textbox' style='width:150px;'>
				{section name=id loop=$user_groups}
					<option value='{$user_groups[id].id}'{if $user_groups[id].selected} selected="selected"{/if}>{$user_groups[id].name}</option>
				{/section}
				</select>
			</td>
		</tr>
		{if $settings.panels_localisation == "single"}
			<tr>
				<td align='center' colspan='2' class='tbl'>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl2'>
				Localisatie
				</td>
			</tr>
			{if $settings.panels_localisation != "single"}
			{/if}
			{if $settings.panels_localisation != "multiple"}
			{/if}
		{/if}
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<div id='panelopts' {$panelopts}>
					<input type='checkbox' name='panel_display' value='1'{$panelon} />
					{$locale.460}
				</div>
				<br />
				{if $panel_id != 0}
					{if $panel_type == "dynamic"}
						<input type='hidden' name='panel_filename' value=''>
					{/if}
				{/if}
				{if !$smarty.const.PANEL_SIDE_MOVE}
					<input type='hidden' name='panel_side' value='{$panel_side}'>
				{/if}
				<input type='hidden' name='panel_locale' value='{$panel_locale}'>
				<input type='submit' name='preview' value='{$locale.458}' class='button' />
				<input type='submit' name='save' value='{$locale.459}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{literal}
<script type='text/javascript'>
	function showopts(panelside) {
		if (panelside == 2 || panelside == 3) {
			document.getElementById('panelopts').style.display = 'block';
		} else {
			document.getElementById('panelopts').style.display = 'none';
		}
	}
</script>
{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
