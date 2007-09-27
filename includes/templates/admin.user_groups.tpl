{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.user_groups.tpl                                *}
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
{* Template for the admin user-admin module 'user_groups'                  *}
{*                                                                         *}
{***************************************************************************}
{section name=id loop=$groups}
	{if $smarty.section.id.first}
	{include file="_opentable.tpl" name=$_name title=$locale.420 state=$_state style=$_style}
	<form name='selectform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
		<center>
		<select name='group_id' class='textbox'>
	{/if}
			<option value='{$groups[id].group_id}'{if $groups[id].selected} selected{/if}>{$groups[id].group_name}</option>
	{if $smarty.section.id.last}
		</select>
			<input type='submit' name='edit' value='{$locale.421}' class='button'>
			<input type='submit' name='delete' value='{$locale.422}' onclick='return DeleteGroup();' class='button'>
		</center>
	</form>
	{include file="_closetable.tpl"}
	{/if}
{/section}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='editform' method='post' action='{$form_action}'>
	<table align='center' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='tbl'>
				{$locale.432}
			</td>
			<td class='tbl'>
				<input type='text' name='group_name' value='{$group_name}' class='textbox' style='width:200px' />
			</td>
		</tr>
		<tr>
			<td class='tbl'>
				{$locale.433}
			</td>
			<td class='tbl'>
				<input type='text' name='group_description' value='{$group_description}' class='textbox' style='width:200px' />
			</td>
		</tr>
		{section name=id loop=$displaytypes}
		<tr>
			<td class='tbl'>
				{$displaytypes[id].type}
			</td>
			<td class='tbl'>
				<select id='dt{$smarty.foreach.dt.index}' name='displaytype[]' class='textbox' onchange='toggleforumfields("dt{$smarty.foreach.dt.index}")'>
					<option value='0'{if !$displaytypes[id].visible} selected{/if}>{$locale.417}</option>
					<option value='1'{if $displaytypes[id].visible} selected{/if}>{$locale.418}</option>
				</select>
			</td>
		</tr>
		{/section}
		<tr id='foruminfo1' {if $group_visible == 0}style='display:none'{/if}>
			<td class='tbl'>
				{$locale.438}
			</td>
			<td class='tbl'>
				<input type='text' name='group_forumname' value='{$group_forumname}' class='textbox' style='width:200px' />
			</td>
		</tr>
		<tr id='foruminfo2' {if $group_visible == 0}style='display:none'{/if}>
			<td class='tbl'>
				{$locale.439}
			</td>
			<td class='tbl1'>
				<input type='text' name='group_color' value='{$group_color}' class='textbox' style='width:100px' />
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='save_group' value='{$locale.434}' class='button'>
			</td>
		</tr>
	</table>
</form>
{literal}<script type='text/javascript'>
function toggleforumfields(fieldid) {

	display = false;
	i = 0;
	while (true) {
		if (!document.getElementById('dt'+i)) break;
		value = document.getElementById('dt'+i).value;
		if (value == 1) {
			display = true;
			break;
		}
		i++;
	}
	if (display) {
		if(navigator.appName.indexOf('Microsoft') > -1) {
			state = 'block';
		} else {
			state = 'table-row';
		}
	} else {
	    state = 'none';
	}
    document.getElementById('foruminfo1').style.display = state;
    document.getElementById('foruminfo2').style.display = state;
	return;
}
</script>{/literal}
{include file="_closetable.tpl"}
{if $group_id}
	{include file="_opentable.tpl" name=$_name title=$locale.404 state=$_state style=$_style}
	<form name='groupform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
		<table align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td align='center'class='tbl'>
					{$locale.447}
				</td>
				<td align='center'class='tbl'>
				</td>
				<td align='center'class='tbl'>
					{$locale.448}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					<select multiple size='15' name='grouplist1' id='grouplist1' class='textbox' style='width:150' onChange="addUser('grouplist2','grouplist1');">
					{section name=id loop=$group1_users}
						<option value='{$group1_users[id].id}'>{$group1_users[id].name}</option>
					{/section}
					</select>
				</td>
				<td align='center' valign='middle' class='tbl'>
				</td>
				<td class='tbl'>
					<select multiple size='15' name='grouplist2' id='grouplist2' class='textbox' style='width:150' onChange="addUser('grouplist1','grouplist2');">
					{section name=id loop=$group2_users}
						<option value='{$group2_users[id].id}'>{$group2_users[id].name}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='3' class='tbl'>
					<input type='hidden' name='group_users' />
					<input type='hidden' name='group_id' value='{$group_id}' />
					<input type='submit' name='add_all_users' value='{$locale.435}' class='button' />
					<input type='submit' name='remove_all_users' value='{$locale.436}' class='button'>
					<br /><br />
					<input type='hidden' name='save_selected_users' />
					<input type='button' name='update' value='{$locale.437}' class='button' onclick='saveGroup();' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
	{include file="_opentable.tpl" name=$_name title=$locale.416 state=$_state style=$_style}
	<form name='groupsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
		<table align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td align='center'class='tbl'>
					{$locale.447}
				</td>
				<td align='center'class='tbl'>
				</td>
				<td align='center'class='tbl'>
					{$locale.448}
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					<select multiple size='5' name='grouplist3' id='grouplist3' class='textbox' style='width:150' onChange="addUser('grouplist4','grouplist3');">
					{section name=id loop=$group1_groups}
						<option value='{$group1_groups[id].id}'>{$group1_groups[id].name}</option>
					{/section}
					</select>
				</td>
				<td align='center' valign='middle' class='tbl'>
				</td>
				<td class='tbl'>
					<select multiple size='5' name='grouplist4' id='grouplist4' class='textbox' style='width:150' onChange="addUser('grouplist3','grouplist4');">
					{section name=id loop=$group2_groups}
						<option value='{$group2_groups[id].id}'>{$group2_groups[id].name}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='3' class='tbl'>
					<input type='hidden' name='group_groups'>
					<input type='hidden' name='group_id' value='{$group_id}'>
					<input type='submit' name='add_all_groups' value='{$locale.426}' class='button'>
					<input type='submit' name='remove_all_groups' value='{$locale.427}' class='button'><br><br>
					<input type='hidden' name='save_selected_groups'>
					<input type='button' name='update' value='{$locale.425}' class='button' onclick='saveGroups();'>
				</td>
			</tr>
		</table>
	</form>
{literal}
<script type='text/javascript'>
// Script Original Author: Kathi O'Shea (Kathi.O'Shea@internet.com)
// http://www.webdesignhelper.co.uk/sample_code/sample_code/sample_code10/sample_code10.shtml		
function addUser(toGroup,fromGroup) {
	var listLength = document.getElementById(toGroup).length;
	var selItem = document.getElementById(fromGroup).selectedIndex;
	var selText = document.getElementById(fromGroup).options[selItem].text;
	var selValue = document.getElementById(fromGroup).options[selItem].value;
	var i; var newItem = true;
	for (i = 0; i < listLength; i++) {
		if (document.getElementById(toGroup).options[i].text == selText) {
			newItem = false; break;
		}
	}
	if (newItem) {
		document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);
		document.getElementById(fromGroup).options[selItem] = null;
	}
}

function saveGroup() {
	var strValues = "";
	var boxLength = document.getElementById('grouplist2').length;
	var elcount = 0;
	if (boxLength != 0) {
		for (i = 0; i < boxLength; i++) {
			if (elcount == 0) {
				strValues = document.getElementById('grouplist2').options[i].value;
			} else {
				strValues = strValues + "." + document.getElementById('grouplist2').options[i].value;
			}
			elcount++;
		}
	}
	if (strValues.length == 0) {
		document.forms['groupform'].submit();
	} else {
		document.forms['groupform'].group_users.value = strValues;
		document.forms['groupform'].submit();
	}
}
function saveGroups() {
	var strValues = "";
	var boxLength = document.getElementById('grouplist4').length;
	var elcount = 0;
	if (boxLength != 0) {
		for (i = 0; i < boxLength; i++) {
			if (elcount == 0) {
				strValues = document.getElementById('grouplist4').options[i].value;
			} else {
				strValues = strValues + "." + document.getElementById('grouplist4').options[i].value;
			}
			elcount++;
		}
	}
	if (strValues.length == 0) {
		document.forms['groupsform'].submit();
	} else {
		document.forms['groupsform'].group_groups.value = strValues;
		document.forms['groupsform'].submit();
	}
}
</script>
{/literal}
	{include file="_closetable.tpl"}
{/if}
{if $show_edit_panel}
	{assign var='page' value=''}
	{assign var='columns' value='2'}	{* when you change this, also change the values of the cycle counters ! *}
	{assign var='column' value=$columns}
	{section name=id loop=$modules}
		{if $smarty.section.id.first}
			{include file="_opentable.tpl" name=$_name title=$admin.group_name|default:""|cat:" - "|cat:$locale.440 state=$_state style=$_style}
			<form name='rightsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;group_id={$group_id}'>
				<table align='center' cellpadding='0' cellspacing='1' width='{math equation='x*200' x=$columns}' class='tbl-border'>
		{/if}
		{if $page != $modules[id].page_name}
			{assign var='page' value=`$modules[id].page_name`}
			{section name=x start=$column loop=$columns}
				<td width='{math equation='100/x' x=$columns format='%u'}%' class='tbl1'>
					&nbsp;
				</td>
				{cycle name=x assign='column' values='1,2' print=false} {* make sure values equals number of columns *}
			{/section}
			{if $column != $columns}
					</tr>
			{/if}
					<tr>
						<td align='center' colspan='{$columns}' class='tbl2'>
							<b>{$page}</b>
						</td>
					</tr>
		{/if}		
		{cycle name=x assign='column' values='1,2' print=false} {* make sure values equals number of columns *}
		{if $column == 1}
					<tr>
		{/if}
						<td width='{math equation='100/x' x=$columns format='%u'}%' class='tbl1'>
		        			<input type='checkbox' name='rights[]' value='{$modules[id].admin_rights}'{if $modules[id].assigned} checked{/if}> {$modules[id].admin_title}
						</td>
		{if $column == $columns}
					</tr>
		{/if}
		{if $smarty.section.id.last}
					{section name=x start=$column loop=$columns}
						<td width='{math equation='100/x' x=$columns format='%u'}%' class='tbl1'>
							&nbsp;
						</td>
						{if $smarty.section.x.last}</tr>{/if}
					{/section}
					<tr>
						<td align='center' colspan='{$columns}' class='tbl1'>
							<input type='button' class='button' onclick="setChecked('rightsform','rights[]',1);" value='{$locale.445}'>
							<input type='button' class='button' onclick="setChecked('rightsform','rights[]',0);" value='{$locale.446}'>
							<br /><br />
							<input type='submit' name='update_admin' value='{$locale.449}' class='button'>
						</td>
					</tr>
				</table>
			</form>
			{include file="_closetable.tpl"}
		{/if}
	{/section}
{/if}
{literal}
<script type='text/javascript'>
	function setChecked(frmName,chkName,val) {
	dml=document.forms[frmName]; len=dml.elements.length;
	for(i=0;i<len;i++) {
		if (dml.elements[i].name==chkName) {
			dml.elements[i].checked=val;
		}
	}
}
</script>
{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}