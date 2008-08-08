{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: forum.renderpost.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-15 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template to render a single post in the forum                           *}
{*                                                                         *}
{***************************************************************************}
	<tr>
		<td class='tbl1' colspan='4' height='5'>
			<a name='post_{$posts[pid].post_id}' id='post_{$posts[pid].post_id}'></a>
		</td>
	</tr>
	<tr>
		<td width='140' class='tbl_top_left' style='white-space:nowrap'>
			{$posts[pid].post_datestamp|date_format:"forumdate"}
		</td>
		<td class='tbl_top_mid'>
			{if $posts[pid].post_sticky}
				<img src='{$smarty.const.THEME}images/stickythread.gif' alt='{$locale.572}' style='vertical-align:top;' />&nbsp;&nbsp;
			{/if}
			{if $posts[pid].thread_id != 0 && $posts[pid].post_id != 0}
				<a href='viewthread.php?forum_id={$forum_id}&amp;thread_id={$posts[pid].thread_id}&amp;pid={$posts[pid].post_id}#post_{$posts[pid].post_id}'>{$posts[pid].post_subject}</a>
			{else}
				{$posts[pid].post_subject}
			{/if}
			{if $posts[pid].post_reply_username|default:"" != ""}
			 [Re: <a href='viewthread.php?forum_id={$forum_id}&amp;thread_id={$posts[pid].thread_id}&amp;pid={$posts[pid].post_reply_id}#post_{$posts[pid].post_reply_id}'>{$posts[pid].post_reply_username}</a>]
	 		{/if}
		</td>
		{if $posts[pid].show_ip}
		<td class='tbl_top_mid' style='text-align:right;'>
		{else}
		<td colspan='2' class='tbl_top_right' style='text-align:right;'>
		{/if}
		{if $smarty.const.iMEMBER && $user_can_post}
			{if $posts[pid].user_can_edit}
				{buttonlink name=$locale.568 link="post.php?action=edit&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$posts[pid].thread_id|cat:"&amp;post_id="|cat:$posts[pid].post_id}
			{/if}
			{if $smarty.const.SHOW_REPLY_BUTTON && (!$thread.thread_locked || $smarty.const.iMOD)}
				{if $smarty.const.REPLY_AS_QUOTE}
					{buttonlink name=$locale.575 link="post.php?action=quote&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$posts[pid].thread_id|cat:"&amp;reply_id="|cat:$posts[pid].post_id}
				{else}
					{buttonlink name=$locale.575 link="post.php?action=postreply&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$posts[pid].thread_id|cat:"&amp;reply_id="|cat:$posts[pid].post_id}
				{/if}
			{/if}
			{if $smarty.const.SHOW_QUOTE_BUTTON && (!$thread.thread_locked || $smarty.const.iMOD)}
				{buttonlink name=$locale.569 link="post.php?action=quote&amp;forum_id="|cat:$forum_id|cat:"&amp;thread_id="|cat:$posts[pid].thread_id|cat:"&amp;reply_id="|cat:$posts[pid].post_id}
			{/if}
		{/if}
		</td>
		{if $posts[pid].show_ip}
		<td width='1%' class='tbl_top_right' valign='bottom'>
			{if $user_can_blacklist}
				<a href='{$smarty.const.ADMIN}blacklist.php{$aidlink}&amp;ip={$posts[pid].post_ip}'>
					<img src='{$smarty.const.THEME}images/ip.gif' alt='{$locale.570}' title='{$posts[pid].post_ip}{$locale.570a}' style='border:0px;' />
				</a>
			{else}
				<img src='{$smarty.const.THEME}images/ip.gif' alt='{$locale.570}' title='{$posts[pid].post_ip}' style='border:0px;' />
			{/if}
		</td>
		{/if}
	</tr>
	<tr>
		<td valign='top' width='140' class='tbl_left'>
			{$posts[pid].cc_flag}
			{if $smarty.const.iMEMBER && $posts[pid].user_status == 0 && $posts[pid].post_author > 0}
				<a href='{$smarty.const.BASEDIR}profile.php?lookup={$posts[pid].user_id}'>{$posts[pid].user_name}</a>
			{else}
				{$posts[pid].user_name}
			{/if}
			<br />
			{if $posts[pid].show_ranking}
				{if $posts[pid].ranking.rank_image}
					{section name=img start=0 loop=$posts[pid].ranking.rank_image_repeat}
						<img src='{$smarty.const.IMAGES}ranking/{$posts[pid].ranking.rank_image}' alt='' title='{if $posts[pid].ranking.rank_tooltip && $posts[pid].ranking.rank_title != ""}{$posts[pid].ranking.rank_title}{/if}' style='border:0px;' />
					{/section}
					<br />
				{/if}
			{/if}
			{* show a ranking title first *}
			{section name=ug loop=$posts[pid].group_names}
				{if $posts[pid].group_names[ug].type == "R"}
					{if $posts[pid].group_names[ug].color|default:"" != ""}
						<span class='small'><font color='{$posts[pid].group_names[ug].color}'>{$posts[pid].group_names[ug].name}</font></span>
					{else}
						<span class='small'>{$posts[pid].group_names[ug].name}</span>
					{/if}
					<br />
				{/if}
			{/section}
			{* show admin/superadmin level first *}
			{section name=ug loop=$posts[pid].group_names}
				{if $posts[pid].group_names[ug].type == "U"}
					{if $posts[pid].group_names[ug].level > 101}
						<span class='small'>{$posts[pid].group_names[ug].name}</span>
						<br />
					{/if}
				{/if}
			{/section}
			{* show group memberships second *}
			{assign var='groups_shown' value=false}
			{section name=ug loop=$posts[pid].group_names}
				{if $posts[pid].group_names[ug].type == "G"}
					{if $posts[pid].group_names[ug].color|default:"" != ""}
						<span class='small'><font color='{$posts[pid].group_names[ug].color}'>{$posts[pid].group_names[ug].name}</font></span>
						<br />
					{else}
						<span class='small'>{$posts[pid].group_names[ug].name}</span>
						<br />
					{/if}
					{assign var='groups_shown' value=true}
				{/if}
			{/section}
			{* if no groups shown, and no admin, show the users level *}
			{if !$groups_shown}
				{section name=ug loop=$posts[pid].group_names}
					{if $posts[pid].group_names[ug].type == "U"}
						{if $posts[pid].group_names[ug].level < 102}
							<span class='small'>{$posts[pid].group_names[ug].name}</span>
							<br />
						{/if}
					{/if}
				{/section}
			{/if}
			<br />
			{if $posts[pid].user_avatar|default:"" != ""}
				<img src='{$smarty.const.IMAGES}avatars/{$posts[pid].user_avatar}' alt='{$locale.567}' />
				<br /><br />
				{assign var='height' value='185'}
			{else}
				{assign var='height' value='70'}
			{/if}
			<span class='small'>{$locale.502}</span> {$posts[pid].user_posts}
			<br />
			{if $posts[pid].user_location|default:"" != ""}
				<span class='small'>{$locale.503}</span> {$posts[pid].user_location}
				<br />
			{/if}
			<span class='small'>{$locale.504}</span> {$posts[pid].user_joined|date_format:"%d.%m.%y"}
		</td>
		<td valign='top' colspan='3' height='{$height}' class='{if $posts[pid].unread}unread{else}tbl_right{/if}' style='border-bottom:none;'>
		{$posts[pid].post_message|default:" "}
		{section name=id loop=$posts[pid].attachments}
			{if $smarty.section.id.first}
				<br /><br />
				<hr />
				{$posts[pid].user_name}{$locale.507}
				<br />
				<table>
			{/if}
					<tr>
						<td>
							<br />
			{if $posts[pid].attachments[id].is_found}
				{if $posts[pid].attachments[id].is_image}
					{if $posts[pid].attachments[id].imagesize.x <= $settings.forum_max_w && $posts[pid].attachments[id].imagesize.y <= $settings.forum_max_h}
						<a href='{$smarty.const.BASEDIR}getfile.php?type=a&amp;file_id={$posts[pid].attachments[id].attach_id}' title='{$posts[pid].attachments[id].attach_comment}'>
							<img src='{$posts[pid].attachments[id].link}' title='{$posts[pid].attachments[id].attach_realname}' alt='{$posts[pid].attachments[id].attach_comment}' />
						</a>
						<br />
					{else}
						{if $posts[pid].attachments[id].has_thumbnail}
							<table cellpadding='0'cellspacing='0' class='thumbnail'>
								<tr>
									<td>
										{if $smarty.const.DOWNLOAD_IMAGES}
											<a href='{$smarty.const.BASEDIR}getfile.php?type=a&amp;file_id={$posts[pid].attachments[id].attach_id}' title='{$posts[pid].attachments[id].attach_comment}'>{$posts[pid].attachments[id].attach_realname}
										{else}
											<a href='{$posts[pid].attachments[id].link}' title='{$posts[pid].attachments[id].attach_comment}' target='_blank'>
											<img src='{$posts[pid].attachments[id].thumbnail}' style='border:1px solid black;' alt='{$posts[pid].attachments[id].attach_realname}' title='{$posts[pid].attachments[id].attach_realname}' />
										{/if}
										</a>
									</td>
								</tr>
								<tr>
									<td>
										{$posts[pid].attachments[id].imagesize.x}x{$posts[pid].attachments[id].imagesize.y} {$posts[pid].attachments[id].size}
									</td>
								</tr>
							</table>
						{else}
							{if $smarty.const.DOWNLOAD_IMAGES}
								<a href='{$smarty.const.BASEDIR}getfile.php?type=a&amp;file_id={$posts[pid].attachments[id].attach_id}' title='{$posts[pid].attachments[id].attach_comment}'>{$posts[pid].attachments[id].attach_realname}</a> ({$posts[pid].attachments[id].imagesize.x} x {$posts[pid].attachments[id].imagesize.y} {$locale.518})
							{else}
								<a href='{$posts[pid].attachments[id].link}' title='{$posts[pid].attachments[id].attach_comment}' target='_blank'>{$posts[pid].attachments[id].attach_realname}</a> ({$posts[pid].attachments[id].imagesize.x} x {$posts[pid].attachments[id].imagesize.y} {$locale.518})
							{/if}
						{/if}
					{/if}
				{else}
					{if $posts[pid].attachments[id].attach_id == 0}
						{$posts[pid].attachments[id].attach_realname} ( {$posts[pid].attachments[id].size} )
					{else}
						<a href='{$smarty.const.BASEDIR}getfile.php?type=a&amp;file_id={$posts[pid].attachments[id].attach_id}' title='{$posts[pid].attachments[id].attach_comment}'>{$posts[pid].attachments[id].attach_realname}</a> ( {$posts[pid].attachments[id].size} )
					{/if}

				{/if}
			{else}
				{$posts[pid].attachments[id].attach_realname} ( {$locale.506} )
			{/if}
			- {$posts[pid].attachments[id].attach_count} {$locale.430}
			<br />
			{if $posts[pid].attachments[id].attach_comment|default:"" != ""}
				<span class='small2'>{$posts[pid].attachments[id].attach_comment}</span>
				<br />
			{/if}
						</td>
					</tr>
			{if $smarty.section.id.last}
				</table>
			{/if}
		{/section}
		{if $posts[pid].post_edittime != "0"}
			{math equation='x+300' x=$posts[pid].post_datestamp assign=edit_time}
			{if $posts[pid].post_author != $posts[pid].post_edituser || $posts[pid].post_edittime > $edit_time}
				<br /><br />
				<span style='color:#666666;'>
					{$locale.508}
					{if $smarty.const.iMEMBER && $posts[pid].edit_status == 0}
						<a href='{$smarty.const.BASEDIR}profile.php?lookup={$posts[pid].post_edituser}'>{$posts[pid].edit_name}</a>
					{else}
						{$posts[pid].edit_name}
					{/if}
					{$locale.509}{$posts[pid].post_edittime|date_format:"forumdate"}
				</span>
			{/if}

		{/if}
		</td>
	</tr>
	<tr valign='bottom'>
		<td class='tbl_left_bottom'>
		{if $smarty.const.iMEMBER && $posts[pid].user_id > 0 && $userdata.user_id != $posts[pid].user_id}
			{buttonlink name=$locale.571 title=$locale.571a link=$smarty.const.BASEDIR|cat:"pm.php?action=post&amp;user_id="|cat:$posts[pid].user_id|cat:"&amp;msg_id=0"}
		{/if}
		{if $posts[pid].user_msn|default:"" != ""}
			{buttonlink name=$locale.576 link="mailto:"|cat:$posts[pid].user_msn}
		{/if}
		{if $posts[pid].user_icq|default:"" != ""}
			{buttonlink name=$locale.578 link="http://web.icq.com/people/about_me.php?uin="|cat:$posts[pid].user_icq new="yes"}
		{/if}
		{if $posts[pid].user_web|default:"" != ""}
			{buttonlink name=$locale.577 link=$posts[pid].user_web new="yes"}
		{/if}
		{if false && $posts[pid].user_aim|default:"" != ""}
			{buttonlink name=$locale.579 link="aim:goim?screenname="|cat:$posts[pid].user_aim|replace:" ":"+"}
		{/if}
		{* used the YAHOO field to store the Skype ID *}
		{if false && $posts[pid].user_yahoo|default:"" != ""}
			{buttonlink name=$locale.580 link="skype:"|cat:$posts[pid].user_yahoo|cat:"?call" new="yes"}
		{/if}
		</td>
		<td colspan='3' class='{if $posts[pid].unread}unread{else}tbl_right{/if}' style='border-top:none;'>
		{if $posts[pid].post_showsig && $posts[pid].user_sig|default:"" != ""}
			<hr />
			{$posts[pid].user_sig}
		{/if}
		</td>
	</tr>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
