{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.forums.tpl                               *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-19 - WW - Initial version                                       *}
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
					<textarea name='forum_description' rows='2' class='textbox' style='width:285px;'>{$forum_description}</textarea>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.462}
					<br />
					<select name='forum_cat' class='textbox' style='width:225px;'>
					{section name=id loop=$cat_opts}
						<option value='{$cat_opts[id].forum_id}'{if $cat_opts[id].selected} selected{/if}>{$cat_opts[id].forum_name}</option>
					{/section}
					</select>
				</td>
				<td width='55' class='tbl'>
				{if $action != "edit"}
					{$locale.463}
					<br />
					<input type='text' name='forum_order' value='{$forum_order}' class='textbox' style='width:45px;'>
				{/if}
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.464}
					<br />
					<select name='forum_access' class='textbox' style='width:225px;'>
					{section name=id loop=$access_opts}
						<option value='{$access_opts[id].id}'{if $access_opts[id].selected} selected{/if}>{$access_opts[id].name}</option>
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
						<option value='{$posting_opts[id].id}'{if $posting_opts[id].selected} selected{/if}>{$posting_opts[id].name}</option>
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
						<option value='{$modgroup_opts[id].id}'{if $modgroup_opts[id].selected} selected{/if}>{$modgroup_opts[id].name}</option>
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
						<option value='{$rulespages[id].page_id}'{if $rulespages[id].selected} selected{/if}>{$rulespages[id].page_title}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					&nbsp;<br />
					{$locale.475}:&nbsp;&nbsp;
					<select name='forum_banners' class='textbox' style='width:50px;'>
						<option value='1'{if $forum_banners} selected{/if}>{$locale.476}</option>
						<option value='0'{if !$forum_banners} selected{/if}>{$locale.477}</option>
					</select>
				</td>
			</tr>
			{if $settings.attachments}
			<tr>
				<td colspan='2' class='tbl'>
					<input type='checkbox' name='forum_attach' value='1' onChange='togglefields();'{if $forum_attach} checked{/if}> {$locale.469}
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
					<input type='hidden' name='forum_attach' value='{$forum_attach}'>
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
		{include file="_opentable.tpl" name=$_name title=$locale.408 state=$_state style=$_style}
		<form name='modsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
			<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
				<tr>
					<td>
						<select multiple size='15' name='modlist1' id='modlist1' class='textbox' style='width:150' onChange="addUser('modlist2','modlist1');">
						{section name=id loop=$mods1}
							<option value='{$mods1[id].user_id}'>{$mods1[id].user_name}</option>
						{/section}
						</select>
					</td>
					<td align='center' valign='middle'>
					</td>
					<td>
						&nbsp;&nbsp;
						<select multiple size='15' name='modlist2' id='modlist2' class='textbox' style='width:150' onChange="addUser('modlist1','modlist2');">
						{section name=id loop=$mods2}
							<option value='{$mods2[id].user_id}'>{$mods2[id].user_name}</option>
						{/section}
					</td>
				</tr>
				<tr>
					<td align='center' colspan='3'><br>
						<input type='hidden' name='forum_mods'>
						<input type='hidden' name='forum_id' value='{$forum_id}'>
						<input type='hidden' name='save_forum_mods'>
						<input type='button' name='update' value='{$locale.467}' class='button' onclick='saveMods();'>
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
			if (count == 0) {
				strValues = document.getElementById('modlist2').options[i].value;
			} else {
				strValues = strValues + "." + document.getElementById('modlist2').options[i].value;
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
	{/if}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.408 state=$_state style=$_style}
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
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=movedown&amp;order={$down}&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.490}' title='{$locale.492}' style='border:0px;'></a>
			{elseif $smarty.section.id.last}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=moveup&amp;order={$up}&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.489}' title='{$locale.491}' style='border:0px;'></a>
			{else}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=moveup&amp;order={$up}&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.489}' title='{$locale.491}' style='border:0px;'></a>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=movedown&amp;order={$down}&amp;forum_id={$forums[id].forum_id}&amp;t=cat'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.490}' title='{$locale.492}' style='border:0px;'></a>
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
			<span class='alt'>{$forums[id].subforums[id2].forum_name}</span>
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
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=movedown&amp;order={$down}&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum&amp;cat={$forums[id].subforums[id2].forum_cat}'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.490}' title='{$locale.492}' style='border:0px;'></a>
			{elseif $smarty.section.id2.last}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=moveup&amp;order={$up}&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum&amp;cat={$forums[id].subforums[id2].forum_cat}'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.489}' title='{$locale.491}' style='border:0px;'></a>
			{else}
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=moveup&amp;order={$up}&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum&amp;cat={$forums[id].subforums[id2].forum_cat}'><img src='{$smarty.const.THEME}images/up.gif' alt='{$locale.489}' title='{$locale.491}' style='border:0px;'></a>
				<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=movedown&amp;order={$down}&amp;forum_id={$forums[id].subforums[id2].forum_id}&amp;t=forum&amp;cat={$forums[id].subforums[id2].forum_cat}'><img src='{$smarty.const.THEME}images/down.gif' alt='{$locale.490}' title='{$locale.492}' style='border:0px;'></a>
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
			[ <a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=refresh'>{$locale.493}</a> ]
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