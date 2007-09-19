{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.forum_polls.tpl                          *}
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
{* Template for the admin content module 'forum_polls'                     *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.FPM_010 state=$_state style=$_style}
<form name='forumselect' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellspacing='0' cellpadding='0' width='100%'>
		<tr>
			<td align='center' class='tbl'>
				&nbsp;
				<select name='forum_id' class='textbox'>
					<optgroup label='{$locale.FPM_021}'>
						<option value='0'>{$locale.FPM_022}</option>
					</optgroup>
				{section name=id loop=$forums}
					{if $forums[id].forum_new_cat}
						{if !$smarty.section.id.first}</optgroup>{/if}
						<optgroup label='{$forums[id].forum_cat_name}'>
						{assign var='hasvalues' value=false}
					{else}
						{assign var='hasvalues' value=true}
					{/if}
					<option value='{$forums[id].forum_id}'{if $forums[id].selected} selected{/if} style='color:{if $forums[id].defined}green{else}red{/if};'>{$forums[id].forum_name}</option>
					{if $smarty.section.id.last && $hasvalues}</optgroup>{/if}
				{/section}
				</select>
				&nbsp;
				<input type='submit' name='forum_select' value='{$locale.FPM_051}' class='button' />
			</td>
		</tr>
		<tr>
			<td align='center' class='tbl'>
				<span class='small2'>{$locale.FPM_400}</span>
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{if $edit_forum|default:"" != ""}
	<script type='text/javascript' src='{$smarty.const.INCLUDES}jscripts/forum_polls.js'></script>
	{include file="_opentable.tpl" name=$_name title=$locale.FPM_023|cat:": "|cat:$forum_name state=$_state style=$_style}
	<form name='fp_settings_form' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;forum_id={$edit_forum}'>
		<table align='center' cellspacing='0' cellpadding='0' width='400px' border='0'>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_025}
				</td>
				<td class='tbl1'>
					<select name='enable_polls' class='textbox'>
						<option value='1'{if $settings.enable_polls == 1} selected{/if}>{$locale.FPM_042}</option>
						<option value='0'{if $settings.enable_polls == 0} selected{/if}>{$locale.FPM_043}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_026}
				</td>
				<td class='tbl1'>
					<a href='javascript:void(0)' onClick="fp_show('creation')">{$locale.FPM_027}</a>
				</td>
			</tr>
			<tr style='display: none;' id='creation' class='tbl-border'>
				<td colspan='2' style='white-space: nowrap;' class='tbl1'>
					<div style='position: relative; left: 18%;'>
						{$locale.FPM_052}
						<br />
						<select multiple size='15' name='grouplist1' id='grouplist1' class='textbox' style='width:150px' onChange="addUser('grouplist2','grouplist1');">
						{section name=id loop=$create_group_1}
							<option value='{$create_group_1[id].0}'>{$create_group_1[id].1}</option>
						{/section}
						</select>
						<select multiple size='15' name='grouplist2' id='grouplist2' class='textbox' style='width:150px' onChange="addUser('grouplist1','grouplist2');">
						{section name=id loop=$create_group_2}
							<option value='{$create_group_2[id].0}'>{$create_group_2[id].1}</option>
						{/section}
						</select>
						<br /><br />
						{$locale.FPM_053}
						<br />
						<select multiple size='15' name='userlist1' id='userlist1' class='textbox' style='width:150px' onChange="addUser('userlist2','userlist1');">
						{section name=id loop=$create_user_1}
							<option value='{$create_user_1[id].0}'>{$create_user_1[id].1}</option>
						{/section}
						</select>
						<select multiple size='15' name='userlist2' id='userlist2' class='textbox' style='width:150px' onChange="addUser('userlist1','userlist2');">
						{section name=id loop=$create_user_2}
							<option value='{$create_user_2[id].0}'>{$create_user_2[id].1}</option>
						{/section}
						</select>
						<br />
						<input type='checkbox' name='same_voters' value='1' class='textbox'> {$locale.FPM_029}
					</div>
					<hr />
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
				</td>
				<td class='tbl1'>
					<a href='javascript:void(0)' onClick="fp_show('voting')">{$locale.FPM_028}</a>
				</td>
			</tr>
			<tr style='display: none' id='voting'>
				<td colspan='2' style='white-space: nowrap;' class='tbl1'>
					<div style='position: relative; left: 18%;'>
						{$locale.FPM_044}
						<br />
						<select multiple size='15' name='grouplist3' id='grouplist3' class='textbox' style='width:150px' onChange="addUser('grouplist4','grouplist3');">
						{section name=id loop=$vote_group_1}
							<option value='{$vote_group_1[id].0}'>{$vote_group_1[id].1}</option>
						{/section}
						</select>
						<select multiple size='15' name='grouplist4' id='grouplist4' class='textbox' style='width:150px' onChange="addUser('grouplist3','grouplist4');">
						{section name=id loop=$vote_group_2}
							<option value='{$vote_group_2[id].0}'>{$vote_group_2[id].1}</option>
						{/section}
						</select>
						<br /><br />
						{$locale.FPM_053}
						<br />
						<select multiple size='15' name='userlist3' id='userlist3' class='textbox' style='width:150px' onChange="addUser('userlist4','userlist3');">
						{section name=id loop=$vote_user_1}
							<option value='{$vote_user_1[id].0}'>{$vote_user_1[id].1}</option>
						{/section}
						</select>
						<select multiple size='15' name='userlist4' id='userlist4' class='textbox' style='width:150px' onChange="addUser('userlist3','userlist4');">
						{section name=id loop=$vote_user_2}
							<option value='{$vote_user_2[id].0}'>{$vote_user_2[id].1}</option>
						{/section}
						</select>
						<br />
						<input type='checkbox' name='same_creators' value='1' class='textbox'>{$locale.FPM_030}
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
						<option value='0'{if $settings.guest_permissions == 0} selected{/if}>{$locale.FPM_047}</option>
						<option value='1'{if $settings.guest_permissions == 1} selected{/if}>{$locale.FPM_048}</option>
						<option value='2'{if $settings.guest_permissions == 2} selected{/if}>{$locale.FPM_049}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_031}
				</td>
				<td class='tbl1'>
					<select name='require_approval' class='textbox' disabled>
						<option value='1'{if $settings.require_approval == 1} selected{/if}>{$locale.FPM_042}</option>
						<option value='0'{if $settings.require_approval == 0} selected{/if}>{$locale.FPM_043}</option>
					</select> (not implemented yet)
				</td>
			</tr>
			<tr>
				<td width='1%' style='white-space: nowrap;' class='tbl1'>
					{$locale.FPM_032}
				</td>
				<td class='tbl1'>
					<select name='lock_threads' class='textbox' disabled>
						<option value='1'{if $settings.lock_threads == 1} selected{/if}>{$locale.FPM_042}</option>
						<option value='0'{if $settings.lock_threads == 0} selected{/if}>{$locale.FPM_043}</option>
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
						<option value='1'{if $settings.hide_poll == 1} selected{/if}>{$locale.FPM_042}</option>
						<option value='0'{if $settings.hide_poll == 0} selected{/if}>{$locale.FPM_043}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2' align='center' class='tbl1'>
					<input type='hidden' name='create_groups'>
					<input type='hidden' name='create_users'>
					<input type='hidden' name='vote_groups'>
					<input type='hidden' name='vote_users'>
					<input type='hidden' name='save_settings'>
					<input type='hidden' name='forum_id' value='{$edit_forum}'>
					{if $edit_forum != 0}
						<input type='submit' name='reset' value='{$locale.FPM_039}' class='button' />&nbsp;
					{/if}
					<input type='button' name='save' value='{$locale.FPM_040}' class='button' onclick='save_fp_settings();'>
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}