{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.forums.tpl                                     *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-19 - WW - Initial version                                       *}
{* 2007-10-03 - WW - Integrated the forum poll panel                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin content module 'forums'                          *}
{*                                                                         *}
{***************************************************************************}
{if $show_category_panel}
	{include file="_opentable.tpl" name=$_name title=$cat_title state=$_state style=$_style}
	<form name='addcat' method='post' action='{$cat_action}'>
		<table align='center' cellpadding='0' cellspacing='0' width='300'>
			<tr>
				<td class='tbl'>
					{$locale.440}
					<br />
					<input type='text' name='cat_name' value='{$cat_name}' class='textbox' style='width:230px;' />
				</td>
				<td width='50' class='tbl'>
					{if $action != "edit"}
						{$locale.441}
						<br />
						<input type='text' name='cat_order' value='{$cat_order}' class='textbox' style='width:45px;' />
					{/if}
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<input type='submit' name='save_cat' value='{$locale.442}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
{/if}
{if $show_forum_panel}
	{include file="_opentable.tpl" name=$_name title=$forum_title state=$_state style=$_style}
	<form name='addforum' method='post' action='{$forum_action}'>
		<table align='center' cellpadding='0' cellspacing='0' width='300'>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.460}
					<br />
					<input type='text' name='forum_name' value='{$forum_name}' class='textbox' style='width:285px;' />
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.461}
					<br />
					<textarea name='forum_description' rows='2' cols='80' class='textbox' style='width:285px;'>{$forum_description}</textarea>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.462}
					<br />
					<select name='forum_cat' class='textbox' style='width:225px;'>
					{section name=id loop=$cat_opts}
						<option value='{$cat_opts[id].forum_id}'{if $cat_opts[id].selected} selected="selected"{/if}>{$cat_opts[id].forum_name}</option>
					{/section}
					</select>
				</td>
				<td width='55' class='tbl'>
				{if $action != "edit"}
					{$locale.463}
					<br />
					<input type='text' name='forum_order' value='{$forum_order}' class='textbox' style='width:45px;' />
				{/if}
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.464}
					<br />
					<select name='forum_access' class='textbox' style='width:225px;'>
					{section name=id loop=$access_opts}
						<option value='{$access_opts[id].id}'{if $access_opts[id].selected} selected="selected"{/if}>{$access_opts[id].name}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.465}
					<br />
					<select name='forum_posting' class='textbox' style='width:225px;'>
					{section name=id loop=$posting_opts}
						<option value='{$posting_opts[id].id}'{if $posting_opts[id].selected} selected="selected"{/if}>{$posting_opts[id].name}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.468}
					<br />
					<select name='forum_modgroup' class='textbox' style='width:225px;'>
					{section name=id loop=$modgroup_opts}
						<option value='{$modgroup_opts[id].id}'{if $modgroup_opts[id].selected} selected="selected"{/if}>{$modgroup_opts[id].name}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.473}
					<br />
					<select name='forum_rulespage' class='textbox' style='width:225px;'>
					{section name=id loop=$rulespages}
						<option value='{$rulespages[id].page_id}'{if $rulespages[id].selected} selected="selected"{/if}>{$rulespages[id].page_title}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					&nbsp;<br />
					{$locale.475}:&nbsp;&nbsp;
					<select name='forum_banners' class='textbox' style='width:50px;'>
						<option value='1'{if $forum_banners} selected="selected"{/if}>{$locale.476}</option>
						<option value='0'{if !$forum_banners} selected="selected"{/if}>{$locale.477}</option>
					</select>
				</td>
			</tr>
			{if $settings.attachments}
			<tr>
				<td colspan='2' class='tbl'>
					<input type='checkbox' name='forum_attach' value='1' onchange='togglefields();'{if $forum_attach} checked{/if} /> {$locale.469}
				</td>
			</tr>
			{/if}
			<tr id='attachtypes'{if !$forum_attach} style='display:none'{/if}>
				<td colspan='2' class='tbl'>
					{$locale.470}
					<br />
					<input type='text' name='forum_attachtypes' value='{$forum_attachtypes}' class='textbox' style='width:285px;' />
					<br />
					<span class='small2'>{$locale.471}</span>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<input type='submit' name='save_forum' value='{$locale.466}' class='button' />
					<input type='hidden' name='forum_attach' value='{$forum_attach}' />
				</td>
			</tr>
		</table>
	</form>
{literal}<script type='text/javascript'>
function togglefields() {
	if(navigator.appName.indexOf('Microsoft') > -1) {
		state = 'block';
	} else {
		state = 'table-row';
	}
    if (document.getElementById('attachtypes').style.display == 'none') {
		document.getElementById('attachtypes').style.display = state;
	} else {
		document.getElementById('attachtypes').style.display = 'none';
	}
}
</script>{/literal}	
	{include file="_closetable.tpl"}
	{if $edit_forum_panel}
	{include file="_opentable.tpl" name=$_name title=$locale.FPM_023 state=$_state style=$_style}
	<form name='fp_settings_form' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;forum_id={$forum_id}'>
		<table align='center' cellspacing='0' cellpadding='0' width='400' border='0'>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_025}
				</td>
				<td class='tbl1'>
					<select name='enable_polls' class='textbox'>
						<option value='1'{if $settings.enable_polls == 1} selected="selected"{/if}>{$locale.FPM_042}</option>
						<option value='0'{if $settings.enable_polls == 0} selected="selected"{/if}>{$locale.FPM_043}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_026}
				</td>
				<td class='tbl1'>
					{buttonlink name=$locale.FPM_027 link="fp_show(\"creation\")" script="yes"}
				</td>
			</tr>
			<tr style='display: none;' id='creation' class='tbl-border'>
				<td colspan='2' style='white-space: nowrap;' class='tbl1'>
					<div style='position: relative; left: 18%;'>
						{$locale.FPM_052}
						<br />
						<select multiple="multiple" size='15' name='grouplist1' id='grouplist1' class='textbox' style='width:150px' onchange="addUser('grouplist2','grouplist1');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$create_group_1}
							<option value='{$create_group_1[id].0}'>{$create_group_1[id].1}</option>
						{/section}
						</select>
						<select multiple="multiple" size='15' name='grouplist2' id='grouplist2' class='textbox' style='width:150px' onchange="addUser('grouplist1','grouplist2');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$create_group_2}
							<option value='{$create_group_2[id].0}'>{$create_group_2[id].1}</option>
						{/section}
						</select>
						<br /><br />
						{$locale.FPM_053}
						<br />
						<select multiple="multiple" size='15' name='userlist1' id='userlist1' class='textbox' style='width:150px' onchange="addUser('userlist2','userlist1');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$create_user_1}
							<option value='{$create_user_1[id].0}'>{$create_user_1[id].1}</option>
						{/section}
						</select>
						<select multiple="multiple" size='15' name='userlist2' id='userlist2' class='textbox' style='width:150px' onchange="addUser('userlist1','userlist2');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$create_user_2}
							<option value='{$create_user_2[id].0}'>{$create_user_2[id].1}</option>
						{/section}
						</select>
						<br />
						<input type='checkbox' name='same_voters' value='1' class='textbox' /> {$locale.FPM_029}
					</div>
					<hr />
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
				</td>
				<td class='tbl1'>
					{buttonlink name=$locale.FPM_028 link="fp_show(\"voting\")" script="yes"}
				</td>
			</tr>
			<tr style='display: none' id='voting'>
				<td colspan='2' style='white-space: nowrap;' class='tbl1'>
					<div style='position: relative; left: 18%;'>
						{$locale.FPM_044}
						<br />
						<select multiple="multiple" size='15' name='grouplist3' id='grouplist3' class='textbox' style='width:150px' onchange="addUser('grouplist4','grouplist3');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$vote_group_1}
							<option value='{$vote_group_1[id].0}'>{$vote_group_1[id].1}</option>
						{/section}
						</select>
						<select multiple="multiple" size='15' name='grouplist4' id='grouplist4' class='textbox' style='width:150px' onchange="addUser('grouplist3','grouplist4');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$vote_group_2}
							<option value='{$vote_group_2[id].0}'>{$vote_group_2[id].1}</option>
						{/section}
						</select>
						<br /><br />
						{$locale.FPM_053}
						<br />
						<select multiple="multiple" size='15' name='userlist3' id='userlist3' class='textbox' style='width:150px' onchange="addUser('userlist4','userlist3');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$vote_user_1}
							<option value='{$vote_user_1[id].0}'>{$vote_user_1[id].1}</option>
						{/section}
						</select>
						<select multiple="multiple" size='15' name='userlist4' id='userlist4' class='textbox' style='width:150px' onchange="addUser('userlist3','userlist4');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$vote_user_2}
							<option value='{$vote_user_2[id].0}'>{$vote_user_2[id].1}</option>
						{/section}
						</select>
						<br />
						<input type='checkbox' name='same_creators' value='1' class='textbox' />{$locale.FPM_030}
					</div>
					<hr />
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_046}
				</td>
				<td class='tbl1'>
					<select name='guest_permissions' class='textbox'>
						<option value='0'{if $settings.guest_permissions == 0} selected="selected"{/if}>{$locale.FPM_047}</option>
						<option value='1'{if $settings.guest_permissions == 1} selected="selected"{/if}>{$locale.FPM_048}</option>
						<option value='2'{if $settings.guest_permissions == 2} selected="selected"{/if}>{$locale.FPM_049}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_031}
				</td>
				<td class='tbl1'>
					<select name='require_approval' class='textbox' disabled="disabled">
						<option value='1'{if $settings.require_approval == 1} selected="selected"{/if}>{$locale.FPM_042}</option>
						<option value='0'{if $settings.require_approval == 0} selected="selected"{/if}>{$locale.FPM_043}</option>
					</select> (not implemented yet)
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_032}
				</td>
				<td class='tbl1'>
					<select name='lock_threads' class='textbox' disabled="disabled">
						<option value='1'{if $settings.lock_threads == 1} selected="selected"{/if}>{$locale.FPM_042}</option>
						<option value='0'{if $settings.lock_threads == 0} selected="selected"{/if}>{$locale.FPM_043}</option>
					</select> (not implemented yet)
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_033}
				</td>
				<td class='tbl1'>
					<input type='text' name='option_max' value='{$settings.option_max}' maxlength='2' class='textbox' style='width:35px;' />
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_034}
				</td>
				<td class='tbl1'>
					<input type='text' name='option_show' value='{$settings.option_show}' maxlength='2' class='textbox' style='width:35px;' />
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_035}
				</td>
				<td class='tbl1'>
					<input type='text' name='option_increment' value='{$settings.option_increment}' maxlength='2' class='textbox' style='width:35px;' />
				</td>
			</tr>
			<tr>
				<td width='1%' class='tbl1'>
					{$locale.FPM_036}
				</td>
				<td class='tbl1'>
					<input type='text' name='duration_min' value='{$settings.duration_min}' maxlength='2' class='textbox' style='width:35px;' />&nbsp;
					{$locale.FPM_041}
				</td>
			</tr>
			<tr>
				<td width='1%' class='tbl1'>
					{$locale.FPM_037}
				</td>
				<td class='tbl1'>
					<input type='text' name='duration_max' value='{$settings.duration_max}' maxlength='2' class='textbox' style='width:35px;' />&nbsp;
					{$locale.FPM_041}
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_038}
				</td>
				<td class='tbl1'>
					<select name='hide_poll' class='textbox'>
						<option value='1'{if $settings.hide_poll == 1} selected="selected"{/if}>{$locale.FPM_042}</option>
						<option value='0'{if $settings.hide_poll == 0} selected="selected"{/if}>{$locale.FPM_043}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2' align='center' class='tbl1'>
					<input type='hidden' name='create_groups' />
					<input type='hidden' name='create_users' />
					<input type='hidden' name='vote_groups' />
					<input type='hidden' name='vote_users' />
					<input type='hidden' name='save_settings' />
					<input type='hidden' name='forum_id' value='{$forum_id}' />
					<input type='submit' name='reset' value='{$locale.FPM_039}' class='button' />&nbsp;
					<input type='button' name='save' value='{$locale.FPM_040}' class='button' onclick='save_fp_settings();' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
		{include file="_opentable.tpl" name=$_name title=$locale.408 state=$_state style=$_style}
		<form name='modsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
			<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
				<tr>
					<td>
						<select multiple="multiple" size='15' name='modlist1' id='modlist1' class='textbox' style='width:150' onchange="addUser('modlist2','modlist1');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$mods1}
							<option value='{$mods1[id].user_id}'>{$mods1[id].user_name}</option>
						{/section}
						</select>
					</td>
					<td align='center' valign='middle'>
					</td>
					<td>
						&nbsp;&nbsp;
						<select multiple="multiple" size='15' name='modlist2' id='modlist2' class='textbox' style='width:150' onchange="addUser('modlist1','modlist2');">
							<option value='-1'>&nbsp;</option>
						{section name=id loop=$mods2}
							<option value='{$mods2[id].user_id}'>{$mods2[id].user_name}</option>
						{/section}
						</select>
					</td>
				</tr>
				<tr>
					<td align='center' colspan='3'>
						<br />
						<input type='hidden' name='forum_mods' />
						<input type='hidden' name='forum_id' value='{$forum_id}' />
						<input type='hidden' name='save_forum_mods' />
						<input type='button' name='update' value='{$locale.467}' class='button' onclick='saveMods();' />
					</td>
				</tr>
			</table>
		</form>
		{include file="_closetable.tpl"}
{literal}
<script type='text/javascript'>
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

function saveMods() {
	var strValues = "";
	var boxLength = document.getElementById('modlist2').length;
	var count = 0;
	if (boxLength != 0) {
		for (i = 0; i < boxLength; i++) {
			if (document.getElementById('modlist2').options[i].value != -1) {
				if (count == 0) {
					strValues = document.getElementById('modlist2').options[i].value;
				} else {
					strValues = strValues + "." + document.getElementById('modlist2').options[i].value;
				}
			}
			count++;
		}
	}
	if (strValues.length == 0) {
		document.forms['modsform'].submit();
	} else {
		document.forms['modsform'].forum_mods.value = strValues;
		document.forms['modsform'].submit();
	}
}
</script>
{/literal}
	<script type='text/javascript' src='{$smarty.const.INCLUDES}jscripts/forum_polls.js'></script>
	{/if}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.480 state=$_state style=$_style}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
{section name=id loop=$forums}
	{if $smarty.section.id.first}
	<tr>
		<td class='tbl2'>
			<b>{$locale.485}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.486}</b>
		</td>
		<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.487}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.488}</b>
		</td>
	</tr>
	{else}
	<tr>
		<td class='tbl1' colspan='5'>
			<br />
		</td>
	</tr>
	{/if}
	<tr>
		<td class='tbl2' colspan='2'>
			<b>{$forums[id].forum_name}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$forums[id].forum_order}</b>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
		{if $cat_count != 1}
			{assign var='up' value=`$forums[id].forum_order-1`}
			{assign var='down' value=`$forums[id].forum_order+1`}
			{if $smarty.section.id.first}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=movedown&amp;order={$down}&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.490}' title='{$locale.492}' style='border:0px;' /></a>
			{elseif $smarty.section.id.last}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=moveup&amp;order={$up}&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.489}' title='{$locale.491}' style='border:0px;' /></a>
			{else}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=moveup&amp;order={$up}&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.489}' title='{$locale.491}' style='border:0px;' /></a>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=movedown&amp;order={$down}&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.490}' title='{$locale.492}' style='border:0px;' /></a>
			{/if}
		{/if}
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.481}' title='{$locale.481}' /></a>&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.482}' title='{$locale.482}' /></a>
		</td>
	</tr>
	{section name=id2 loop=$forums[id].subforums}
	<tr>
		<td class='tbl1'>
			<span class='small'>{$forums[id].subforums[id2].forum_name}</span>
			<br />
			<span class='small'>{$forums[id].subforums[id2].forum_description}</span>
		</td>
		<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
			{$forums[id].subforums[id2].forum_access_name}
			<br />
			<span class='small2'>{$forums[id].subforums[id2].forum_posting_name}</span>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			{$forums[id].subforums[id2].forum_order}
		</td>
		<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
		{if $forums[id].forum_count != 1}
			{assign var='up' value=`$forums[id].subforums[id2].forum_order-1`}
			{assign var='down' value=`$forums[id].subforums[id2].forum_order+1`}
			{if $smarty.section.id2.first}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=movedown&amp;order={$down}&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum&amp;cat={$forums[id].subforums[id2].forum_cat}'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.490}' title='{$locale.492}' style='border:0px;' /></a>
			{elseif $smarty.section.id2.last}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=moveup&amp;order={$up}&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum&amp;cat={$forums[id].subforums[id2].forum_cat}'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.489}' title='{$locale.491}' style='border:0px;' /></a>
			{else}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=moveup&amp;order={$up}&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum&amp;cat={$forums[id].subforums[id2].forum_cat}'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.489}' title='{$locale.491}' style='border:0px;' /></a>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=movedown&amp;order={$down}&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum&amp;cat={$forums[id].subforums[id2].forum_cat}'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.490}' title='{$locale.492}' style='border:0px;' /></a>
			{/if}
		{/if}
		</td>
		<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=edit&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.481}' title='{$locale.481}' /></a>&nbsp;
			<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=delete&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.482}' title='{$locale.482}' /></a>
		</td>
	</tr>
	{sectionelse}
	<tr>
		<td align='center' colspan='5' class='tbl1'>
			{$locale.483}
		</td>
	</tr>
	{/section}
	{if $smarty.section.id.last}
	<tr>
		<td align='center' colspan='5' class='tbl1'>
			{buttonlink name=$locale.493 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=refresh"}
		</td>
	</tr>
	{/if}
{sectionelse}
	<tr>
		<td align='center' class='tbl1'>
			{$locale.484}
		</td>
	</tr>
{/section}
</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}