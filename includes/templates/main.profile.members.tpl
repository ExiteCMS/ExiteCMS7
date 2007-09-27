{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.profile.members.tpl                             *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-05 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'profile', to show the member profile      *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.420 state=$_state style=$_style}
<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
	<tr>
		<td colspan='3'>
			<table align='center' cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td class='tbl2'><b>{$data.user_name}</b></td>
					<td align='right' class='tbl2'>{$data.user_level}</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align='center' width='150' rowspan='6' class='tbl2'>
		{if $data.user_avatar|default:"" != ""}
			<img src='{$smarty.const.IMAGES}avatars/{$data.user_avatar}' alt='{$locale.u017}' />
		{else}
			{$locale.u046}
		{/if}
		</td>
		<td width='1%' class='tbl1' style='white-space:nowrap'>
			<b>{$locale.u009}</b>
		</td>
		<td class='tbl1'>
			{$data.user_location|default:$locale.u048}
		</td>
	</tr>
	<tr>
		<td width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.u025}</b>
		</td>
		<td class='tbl2'>
		{if $smarty.const.iUSER >= 102 || $settings.forum_flags|default:false}
			{$data.cc_flag}{if $data.user_cc_code|default:"" != ""}<a href='/members.php?country={$data.user_cc_code}'>{$data.cc_name}</a>{else}{$data.cc_name}{/if}
		{else}
			{$locale.u056}
		{/if}
		</td>
	</tr>
	<tr>
		<td width='1%' class='tbl1' style='white-space:nowrap'>
			<b>{$locale.u010}</b>
		</td>
		<td class='tbl1'>
			{$data.user_birthdate}
		</td>
	</tr>
	<tr>
		<td width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.u011}</b>
		</td>
		<td class='tbl2'>
			{$data.user_icq|default:$locale.u048}
		</td>
	</tr>
	<tr>
		<td width='1%' class='tbl1' style='white-space:nowrap'>
			<b>{$locale.u012}</b>
		</td>
		<td class='tbl1'>
			{$data.user_msn|default:$locale.u048}
		</td>
	</tr>
	<tr>
		<td width='1%' class='tbl2' style='white-space:nowrap'>
			<b>{$locale.u013}</b>
		</td>
		<td class='tbl2'>
			{$data.user_yahoo|default:$locale.u048}
		</td>
	</tr>
	<tr>
		<td align='center' class='tbl1'>
			{if $smarty.const.iUSER >= 102 || $data.user_hide_email != "1"}
				{buttonlink name=$locale.u051 link="mailto:"|cat:$data.user_email|strip encode='javascript_charcode'}&nbsp;
			{/if}
			{if $data.user_web|default:"" != ""}
				{buttonlink name=$locale.u052 link=$data.user_web new="yes"}&nbsp;
			{/if}
			{if $data.show_pm_button}
				{buttonlink name=$locale.u053 link=$smarty.const.BASEDIR|cat:"pm.php?action=post&amp;user_id="|cat:$data.user_id|cat:"&amp;msg_id=0"}
			{/if}
		</td>
		<td width='1%' class='tbl1' style='white-space:nowrap'>
			<b>{$locale.u021}</b>
		</td>
		<td class='tbl1'>
			{$data.user_aim|default:$locale.u048}
		</td>
	</tr>
</table>
<br />
<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
	<tr>
		<td class='tbl2' colspan='2'>
			<b>{$locale.422}</b>
		</td>
	</tr>
	<tr>
		<td width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>
			<b>{$locale.u040}</b>
		</td>
		<td class='{cycle values="tbl1,tbl2"}'>
			{$data.user_joined|date_format:"longdate"}
		</td>
	</tr>
	<tr>
		<td width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>
			<b>{$locale.u044}</b>
		</td>
		<td class='{cycle values="tbl1,tbl2"}'>
			{if $data.user_lastvisit != 0}{$data.user_lastvisit|date_format:"longdate"}{else}{$locale.u049}{/if}
		</td>
	</tr>
	{if $smarty.const.iSUPERADMIN}
	<tr>
		<td width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>
			<b>{$locale.u050}</b>
		</td>
		<td class='{cycle values="tbl1,tbl2"}'>
			{$data.user_ip|default:$locale.408}
		</td>
	</tr>
	{/if}
	<tr>
		<td width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>
			<b>{$locale.u041}</b>
		</td>
		<td class='{cycle values="tbl1,tbl2"}'>
			{$data.shout_count}
		</td>
	</tr>
	<tr>
		<td width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>
			<b>{$locale.u042}</b>
		</td>
		<td class='{cycle values="tbl1,tbl2"}'>
			{$data.comment_count}
		</td>
	</tr>
	<tr>
		<td width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>
			<b>{$locale.u043}</b>
		</td>
		<td class='{cycle values="tbl1,tbl2"}'>
			{$data.user_posts}
			{if $data.show_viewposts_button}
				&nbsp;&nbsp;&nbsp;[<a href='{$smarty.const.MODULES}forum_threads_list_panel/my_posts.php?id={$data.user_id}' title='{$locale.u061}'>{$locale.u055}</a>]
			{/if}
		</td>
	</tr>
	<tr>
		<td width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>
			<b>{$locale.u054}</b>
		</td>
		<td class='{cycle values="tbl1,tbl2"}'>
			{$data.unread_count}
		</td>
	</tr>
</table>
{section name=group loop=$data.user_groups}
	{if $smarty.section.group.first}
		<br />
		<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
			<tr>
				<td class='tbl2'>
					<b>{$locale.423}</b>
				</td>
			</tr>
			<tr>
				<td class='tbl1'>
	{else}
		,
	{/if}
					<a href='{$smarty.const.FUSION_SELF}?group_id={$data.user_groups[group].group}'>{$data.user_groups[group].name}</a>
	{if $smarty.section.group.last}
				</td>
			</tr>
		</table>
	{/if}
{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}