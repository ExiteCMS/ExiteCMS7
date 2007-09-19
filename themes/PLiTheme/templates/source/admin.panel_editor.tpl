{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.panel_editor.tpl                         *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-22 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the edit panel of the admin configuration module 'panels'  *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='editform' method='post' action='{$action}'>
	<table align='center' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='tbl'>
				{$locale.452}
			</td>
			<td class='tbl'>
				<input type='text' name='panel_name' value='{$panel_name}' class='textbox' style='width:200px;' />
			</td>
		</tr>
		{if $panel_id == 0 || $panel_type == "file"}
		<tr>
			<td class='tbl'>
				{$locale.453}
			</td>
			<td class='tbl'>
				<select name='panel_filename' class='textbox' style='width:200px;'>
				{foreach from=$panel_list item=panel_file}
					<option{if $panel_filename == $panel_file} selected{/if}>{$panel_file}</option>				
				{/foreach}
				</select>
				{if $panel_id == 0}&nbsp;&nbsp;<span class='small2'>{$locale.454}</span>{/if}
			</td>
		</tr>
		{/if}
		{if $panel_id == 0 || $panel_type == "dynamic"}
		<tr>
			<td valign='top' class='tbl'>
				{$locale.455}
			</td>
			<td class='tbl'>
				<textarea name='panel_code' cols='95' rows='15' class='textbox'>{$panel_code}</textarea>
			</td>
		</tr>
		<tr>
			<td valign='top' class='tbl'>
				{$locale.465}
			</td>
			<td class='tbl'>
				<textarea name='panel_template' cols='95' rows='15' class='textbox'>{$panel_template}</textarea>
			</td>
		</tr>
		{/if}
		{if true || $panel_id == 0}
		<tr>
			<td class='tbl'>
				{$locale.456}
			</td>
			<td class='tbl'>
				<select name='panel_side' class='textbox' style='width:150px;' onchange="showopts(this.options[this.selectedIndex].value);">
					<option value='1'{if $panel_side == "1"} selected{/if}>{$locale.466}</option>
					<option value='2'{if $panel_side == "2"} selected{/if}>{$locale.467}</option>
					<option value='3'{if $panel_side == "3"} selected{/if}>{$locale.469}</option>
					<option value='4'{if $panel_side == "4"} selected{/if}>{$locale.468}</option>
				</select>
			</td>
		</tr>
		{/if}
		<tr>
			<td class='tbl'>
				{$locale.461}
			</td>
			<td class='tbl'>
				<select name='panel_usermod' class='textbox' style='width:150px;'>
					<option value='0'{if $panel_usermod == "0"} selected{/if}>{$locale.447}</option>
					<option value='1'{if $panel_usermod == "1"} selected{/if}>{$locale.448}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.462}
			</td>
			<td class='tbl'>
				<select name='panel_state' class='textbox' style='width:150px;'>
					<option value='0'{if $panel_state == "0"} selected{/if}>{$locale.463}</option>
					<option value='1'{if $panel_state == "1"} selected{/if}>{$locale.464}</option>
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
					<option value='{$user_groups[id].id}'{if $user_groups[id].selected} selected{/if}>{$user_groups[id].name}</option>
				{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<div id='panelopts' {$panelopts}>
					<input type='checkbox' name='panel_display' value='1'{$panelon} />
					{$locale.460}
				</div>
				<br />
				{if $panel_id != 0}
					{if $panel_type == "dynamic"}
						<input type='hidden' name='panel_filename' value='none'>
					{/if}
					<input type='hidden' name='panel_side' value='{$panel_side}'>
				{/if}
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
		if (panelside == 1 || panelside == 4) {
			document.getElementById('panelopts').style.display = 'none';
		} else {
			document.getElementById('panelopts').style.display = 'block';
		}
	}
</script>
{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}