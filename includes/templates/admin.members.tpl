{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.viewpage.tpl                                    *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-09 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'viewpage', to display a custom page       *}
{*                                                                         *}
{***************************************************************************}
{if $country_name|default:"" == ""}
	{assign var=_title value=$locale.400}
{else}
	{assign var=_title value=$locale.400|cat:" "|cat:$locale.470|cat:" <b>"|cat:$country_name|cat:"</b>"}
{/if}
{if $step == "ban"}
	{include file="_opentable.tpl" name=$_name title=$bantitle state=$_state style=$_style}
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&step=ban&act=on&sortby={$sortby}&rowstart={$rowstart}&user_id={$user_id}'>
		<table align='center' cellpadding='0' cellspacing='0' width='450'>
			<tr>
				<td width='200' class='tbl'>
					{$locale.437}
				</td>
				<td class='tbl'>
					<input type='text' name='user_ban_reason' value='{$user_ban_reason}' class='textbox' style='width:250px;' />
				</td>
			</tr>
			<tr>
				<td width='200' class='tbl'>
					{$locale.438}
				</td>
				<td class='tbl'>
					<input type='text' name='user_ban_expire' value='{$user_ban_expire}' class='textbox' style='width:50px;' />
					&nbsp;{$locale.439}
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<input type='submit' name='ban' value='{$locale.417}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
{/if}
{if $step == "add"}
	{if !$is_added}
	{include file="_opentable.tpl" name=$_name title=$locale.475 state=$_state style=$_style}
	<form name='addform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=add'>
		<table align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl'>
					{$locale.u001}<span style='color:#ff0000'>*</span>
				</td>
				<td class='tbl'>
					<input type='text' name='username' maxlength='30' class='textbox' style='width:200px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u901}<span style='color:#ff0000'>*</span>
				</td>
				<td class='tbl'>
					<input type='text' name='fullname' maxlength='50' class='textbox' style='width:200px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u002}<span style='color:#ff0000'>*</span>
				</td>
				<td class='tbl'>
					<input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u004}<span style='color:#ff0000'>*</span>
				</td>
				<td class='tbl'>
					<input type='password' name='password2' maxlength='20' class='textbox' style='width:200px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u005}<span style='color:#ff0000'>*</span>
				</td>
				<td class='tbl'>
					<input type='text' name='email' maxlength='100' class='textbox' style='width:200px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.u006}
				</td>
				<td class='tbl'>
					<input type='radio' name='hide_email' value='1' />
					{$locale.u007}
					<input type='radio' name='hide_email' value='0' checked="checked" />
					{$locale.u008}
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2'>
					<br />
					<input type='submit' name='add_user' value='{$locale.475}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
	{else}
	{include file="_opentable.tpl" name=$_name title=$locale.475 state=$_state style=$_style}
	<center>
	<br />
	{if $message|default:"" != ""} 
	{$locale.477}
	<br /><br />
	<b>{$message}</b>
	<br />
	{else}
	{$locale.476}
	<br />
	{/if}
	<br />
	<a href='members.php{$aidlink}'>{$locale.478}</a>
	<br /><br />
	<a href='index.php{$aidlink}'>{$locale.479}</a>
	<br /><br />
	</center>
	{include file="_closetable.tpl"}
	{/if}
{else}
	{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
	{if $message|default:"" != ""} 
		<table align='center' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
			<tr>
				<td align='center' colspan='2' class='tbl1'>
					<b>{$message}</b>
				</td>
			</tr>
		</table>
		<br />
		{/if}
	{if $profile_updated}
		<table align='center' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		{if $error|default:"" == ""}
		<tr>
			<td align='center' colspan='2' class='tbl'>
				{$locale.441}
				<br /><br />
			</td>
		</tr>
		{else}
		<tr>
			<td align='center' colspan='2' class='tbl'>
				{$locale.442}
				<br /><br />
				<b>{$error}</b>
				<br />
			</td>
		</tr>
		{/if}
		</table>
		<br />
	{/if}
	{if !$smarty.const.iMEMBER}
		<center>
			<br />
			{$locale.003}
			<br /><br />
		</center>
	{else}
		{section name=id loop=$members}
			{if $smarty.section.id.first}
			<table align='center' cellpadding='0' cellspacing='1' width='700' class='tbl-border'>
				<tr>
					<td class='tbl2'>
						<div style='float:right;'><a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=add'><img src='{$smarty.const.THEME}images/page_add.gif' alt='{$locale.403}' title='{$locale.403}' /></a></div>
						<b>{$locale.401}</b>
					</td>
					{if $userdata.user_level >= 102 || $settings.forum_flags}
					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
						<b>{$locale.406}</b>
					</td>
					{/if}
					{if $userdata.user_level >= 102}
						<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
							<b>{$locale.409}</b>
						</td>
					{/if}
					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
						<b>{$locale.402}</b>
					</td>
					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
						<b>{$locale.405}</b>
					</td>
				</tr>
			{/if}
				<tr>
					<td class='{cycle values='tbl1,tbl2' advance=no}'>
						<a href='{$smarty.const.BASEDIR}profile.php?lookup={$members[id].user_id}'>{$members[id].user_name}</a>
					</td>
					{if $userdata.user_level >= 102 || $settings.forum_flags}
						<td align='left' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
							{$members[id].cc_flag}{if $members[id].user_cc_code == ""}{$members[id].cc_name}{else}<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;sortby={$sortby}&amp;country={$members[id].user_cc_code}'>{$members[id].cc_name}</a>{/if}
						</td>
					{/if}
					{if $userdata.user_level >= 102}
						<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
							{$members[id].user_email}
						</td>
					{/if}
					<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
						{$members[id].user_level_name}
					</td>
					<td width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
						{if $members[id].can_edit}
							<a href='{$smarty.const.BASEDIR}edit_profile.php{$aidlink}&amp;{$aidlink}&amp;user_id={$members[id].user_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.415|escape:"html"}' title='{$locale.415|escape:"html"}' /></a>&nbsp;
						{/if}
						{if $members[id].can_delete}
							<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=delete&amp;sortby={$sortby}&amp;rowstart={$rowstart}&amp;user_id={$members[id].user_id}' onclick='return DeleteMember();'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.418}' title='{$locale.418}' /></a>&nbsp;
						{/if}
						{if $members[id].can_ban}
							{if $members[id].user_status == 2}
								<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=activate&amp;sortby={$sortby}&amp;rowstart={$rowstart}&amp;user_id={$members[id].user_id}'><img src='{$smarty.const.THEME}images/user_go.gif' alt='{$locale.419}' title='{$locale.419}' /></a>
							{elseif $members[id].user_status == 1}
								<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=unban&amp;sortby={$sortby}&amp;rowstart={$rowstart}&amp;user_id={$members[id].user_id}' title='{$members[id].user_ban_reason}'><img src='{$smarty.const.THEME}images/user_add.gif' alt='{$locale.416}' title='{$locale.416}' /></a>
							{else}
								<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;step=ban&amp;sortby={$sortby}&amp;rowstart={$rowstart}&amp;user_id={$members[id].user_id}'><img src='{$smarty.const.THEME}images/user_delete.gif' alt='{$locale.417}' title='{$locale.417}' /></a>
							{/if}
						{/if}
					</td>
				</tr>
			{if $smarty.section.id.last}
			</table>
			<br />
			<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
				<tr>
					<td rowspan='2' class='tbl2'>
						<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;sortby=all'>{$locale.404}</a>
					</td>
					{foreach from=$search item=letter name=search}
					{if $smarty.foreach.search.first}
						{math equation="x/2-1" x=$smarty.foreach.search.total format="%u" assign='break'}
					{/if}
					<td align='center' class='tbl1'>
						<div class='small'>
							<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;sortby={$letter}{if $country !=""}&amp;country={$country}{/if}'>{$letter}</a>
						</div>
					</td>
				{if $smarty.foreach.search.index==$break}
							<td rowspan='2' class='tbl2'>
						<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;sortby=all'>{$locale.404}</a>
					</td>
				</tr>
				<tr>
				{/if}
					{/foreach}
				</tr>
			</table>
			{/if}
		{sectionelse}
			<center>
				<br />
				{$error|default:$locale.472}
				<br /><br />
			</center>
		{/section}
	{/if}
	{include file="_closetable.tpl"}
	{if $rows > $items_per_page}
		{makepagenav start=$rowstart count=$items_per_page total=$rows range=3 link=$pagenav_url}
	{/if}	
	<script type='text/javascript'>
	function DeleteMember(username) {ldelim}
		return confirm('{$locale.433}');
	{rdelim}
	</script>
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}