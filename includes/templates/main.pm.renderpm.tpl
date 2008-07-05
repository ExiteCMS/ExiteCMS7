{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.pm.renderpm.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-08-07 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template to render a single private message                             *}
{*                                                                         *}
{***************************************************************************}
	{if !$smarty.section.id.first && !$is_inline}
	<tr>
		<td class='tbl1' colspan='4' height='5'>
		</td>
	</tr>
	{/if}
	<tr>
		<td width='140' class='tbl_top_left'>
			<a name='view_{$messages[id].pmindex_id}'></a>
			<b>{$locale.422}
			{if $is_preview || $messages[id].pmindex_user_id != $messages[id].pmindex_to_id}
				{$locale.421}:
			{else}
				{$locale.406}:
			{/if}
			</b>
		</td>
		<td class='tbl_top_mid' style=''>
			{if $is_inline}<a href='{$smarty.const.FUSION_SELF}?folder={$folder}&amp;rowstart={$rowstart}'>{/if}
			{$messages[id].pm_subject}
			{if $is_inline}</a>{/if}
			<br /><span class='small2'>
			{if $messages[id].pm_datestamp != 0}{if $messages[id].pmindex_user_id == $messages[id].pmindex_to_id}{$locale.408}{else}{$locale.407}{/if}: {$messages[id].pm_datestamp|date_format:"forumdate"}{/if}
			</span>
		</td>
		<td class='tbl_top_mid' style='text-align:right;'>
			{if !$is_preview}
				{buttonlink name=$locale.423 link=$smarty.const.FUSION_SELF|cat:"?folder="|cat:$folder|cat:"&amp;action=forward&amp;msg_id="|cat:$messages[id].pmindex_id}&nbsp;
				{if $folder == $locale.402 && $messages[id].pmindex_folder == 0}
					{buttonlink name=$locale.433 link=$smarty.const.FUSION_SELF|cat:"?folder="|cat:$folder|cat:"&amp;action=reply&amp;msg_id="|cat:$messages[id].pmindex_id|cat:"&amp;user_id="|cat:$messages[id].pmindex_from_id}&nbsp;
					{buttonlink name=$locale.444 link=$smarty.const.FUSION_SELF|cat:"?folder="|cat:$folder|cat:"&amp;action=quote&amp;msg_id="|cat:$messages[id].pmindex_id|cat:"&amp;user_id="|cat:$messages[id].pmindex_from_id}&nbsp;
				{/if}
				{if $folder == $locale.404 && $messages[id].pmindex_folder == 2}
					{if ($messages[id].pmindex_user_id == $messages[id].pmindex_to_id && $global_options.pm_inbox > $totals.inbox) || ($messages[id].pmindex_user_id != $messages[id].pmindex_to_id && $global_options.pm_sentbox > $totals.outbox)}
						{buttonlink name=$locale.412 link=$smarty.const.FUSION_SELF|cat:"?folder="|cat:$folder|cat:"&amp;action=restore&amp;msg_id="|cat:$messages[id].pmindex_id}
					{/if}
				{else}
					{if $global_options.pm_savebox > $totals.archive}
						{buttonlink name=$locale.448 link=$smarty.const.FUSION_SELF|cat:"?folder="|cat:$folder|cat:"&amp;action=archive&amp;msg_id="|cat:$messages[id].pmindex_id}&nbsp;
					{/if}
				{/if}
				{buttonlink name=$locale.416 link=$smarty.const.FUSION_SELF|cat:"?folder="|cat:$folder|cat:"&amp;action=delete&amp;msg_id="|cat:$messages[id].pmindex_id}
			{/if}
		</td>
		<td align='center' width='10' class='tbl_top_right'>		
			{if !$is_preview}
			<input type='checkbox' name='check_mark[]' value='{$messages[id].pmindex_id}' />
			{/if}
		</td>
	</tr>
	<tr>
		<td valign='top' width='140' class='tbl_left'>
			{if $messages[id].pmindex_user_id == $messages[id].pmindex_to_id}
				{$messages[id].sender.cc_flag}
				<a href='{$smarty.const.BASEDIR}profile.php?lookup={$messages[id].sender.user_id}'>{$messages[id].sender.user_name}</a>
				<br />
				{* show admin/superadmin level first *}
				{section name=ug loop=$messages[id].sender.group_names}
					{if $messages[id].sender.group_names[ug].type == "U"}
						{if $messages[id].sender.group_names[ug].level > 101}
							<span class='small'>{$messages[id].sender.group_names[ug].name}</span>
							<br />
						{/if}
					{/if}
				{/section}
				{* show group memberships second *}
				{assign var='groups_shown' value=false}
				{section name=ug loop=$messages[id].sender.group_names}
					{if $messages[id].sender.group_names[ug].type == "G"}
						{if $messages[id].sender.group_names[ug].color|default:"" != ""}
							<span class='small'><font color='{$messages[id].sender.group_names[ug].color}'>{$messages[id].sender.group_names[ug].name}</font></span>
							<br />
						{else}
							<span class='small'>{$messages[id].sender.group_names[ug].name}</span>
							<br />
						{/if}
						{assign var='groups_shown' value=true}
					{/if}
				{/section}
				{* if no groups shown, and no admin, show the users level *}
				{if !$groups_shown}
					{section name=ug loop=$messages[id].sender.group_names}
						{if $messages[id].sender.group_names[ug].type == "U"}
							{if $messages[id].sender.group_names[ug].level < 102}
								<span class='small'>{$messages[id].sender.group_names[ug].name}</span>
								<br />
							{/if}
						{/if}
					{/section}
				{/if}
				<br />
				{if $messages[id].sender.user_avatar|default:"" != ""}
					<img src='{$smarty.const.IMAGES}avatars/{$messages[id].sender.user_avatar}' alt='{$locale.567}' />
					<br /><br />
					{assign var='height' value='185'}
				{else}
					{assign var='height' value='70'}
				{/if}
				<br />
				{if $messages[id].sender.user_location|default:"" != ""}
					<span class='small'>{$locale.540}</span> {$messages[id].sender.user_location}
					<br />
				{/if}
				<span class='small'>{$locale.541}</span> {$messages[id].sender.user_joined|date_format:"%d.%m.%y"}
			{else}
				{if $messages[id].recipient_count == 1}
					{$messages[id].recipient.cc_flag}
					<a href='{$smarty.const.BASEDIR}profile.php?lookup={$messages[id].recipient.user_id}'>{$messages[id].recipient.user_name}</a>
					<br />
					{* show admin/superadmin level first *}
					{section name=ug loop=$messages[id].recipient.group_names}
						{if $messages[id].recipient.group_names[ug].type == "U"}
							{if $messages[id].recipient.group_names[ug].level > 101}
								<span class='small'>{$messages[id].recipient.group_names[ug].name}</span>
								<br />
							{/if}
						{/if}
					{/section}
					{* show group memberships second *}
					{assign var='groups_shown' value=false}
					{section name=ug loop=$messages[id].recipient.group_names}
						{if $messages[id].recipient.group_names[ug].type == "G"}
							{if $messages[id].recipient.group_names[ug].color|default:"" != ""}
								<span class='small'><font color='{$messages[id].recipient.group_names[ug].color}'>{$messages[id].recipient.group_names[ug].name}</font></span>
								<br />
							{else}
								<span class='small'>{$messages[id].recipient.group_names[ug].name}</span>
								<br />
							{/if}
							{assign var='groups_shown' value=true}
						{/if}
					{/section}
					{* if no groups shown, and no admin, show the users level *}
					{if !$groups_shown}
						{section name=ug loop=$messages[id].recipient.group_names}
							{if $messages[id].recipient.group_names[ug].type == "U"}
								{if $messages[id].recipient.group_names[ug].level < 102}
									<span class='small'>{$messages[id].recipient.group_names[ug].name}</span>
									<br />
								{/if}
							{/if}
						{/section}
					{/if}
					<br />
					{if $messages[id].recipient.user_avatar|default:"" != ""}
						<img src='{$smarty.const.IMAGES}avatars/{$messages[id].recipient.user_avatar}' alt='{$locale.567}' />
						<br /><br />
						{assign var='height' value='185'}
					{else}
						{assign var='height' value='70'}
					{/if}
					<br />
					{if $messages[id].recipient.user_location|default:"" != ""}
						<span class='small'>{$locale.540}</span> {$messages[id].recipient.user_location}
						<br />
					{/if}
					<span class='small'>{$locale.541}</span> {$messages[id].recipient.user_joined|date_format:"%d.%m.%y"}
				{else}
					{section name=rid loop=$messages[id].recipients}
						{if !$smarty.section.rid.first}<br />{/if}
						{$messages[id].recipients[rid].cc_flag}
						{if $messages[id].recipients[rid].id >= 0}
							<a href='{$smarty.const.BASEDIR}profile.php?lookup={$messages[id].recipients[rid].user_id}'>{$messages[id].recipients[rid].user_name}</a>
						{else}
							<a href='{$smarty.const.BASEDIR}profile.php?group_id={$messages[id].recipients[rid].group_id}'>{$messages[id].recipients[rid].group_name}</a>
						{/if} 
					{/section}
				{/if}
			{/if}
		</td>
		<td valign='top' colspan='3' height='180' class='{if $messages[id].pmindex_read_datestamp == 0}unread{else}tbl_right{/if}' style='border-bottom:none;'>
		{$messages[id].pm_message|default:"&nbsp;"}
		{section name=aid loop=$messages[id].attachments}
			{if $smarty.section.aid.first}
				<br /><br />
				<hr />
				{$messages[id].from_user_name}{$locale.510}
				<br />
				<table>
			{/if}
					<tr>
						<td>
							<br />
			{if $messages[id].attachments[aid].is_found}
				{if $messages[id].attachments[aid].is_image}
					{if $messages[id].attachments[aid].imagesize.x <= $settings.forum_max_w && $messages[id].attachments[aid].imagesize.y <= $settings.forum_max_h}
						<img src='{$messages[id].attachments[aid].link}' title='{$messages[id].attachments[aid].pmattach_realname}' alt='{$messages[id].attachments[aid].pmattach_comment}' />
					{else}
						{if $messages[id].attachments[aid].has_thumbnail}
							<table cellpadding='0'cellspacing='0' bgcolor='#000000'>
								<tr>
									<td>
										{if $smarty.const.DOWNLOAD_IMAGES}
											<a href='{$smarty.const.BASEDIR}getfile.php?type=pa&amp;file_id={$messages[id].attachments[aid].pmattach_id}' alt='{$messages[id].attachments[aid].pmattach_comment}'>
										{else}
											<a href='{$messages[id].attachments[aid].link}' alt='{$messages[id].attachments[aid].pmattach_comment}' target='_blank'>
										{/if}
										<img src='{$messages[id].attachments[aid].thumbnail}' style='border:1px solid black;' alt='{$messages[id].attachments[aid].pmattach_realname}' title='{$messages[id].attachments[aid].pmattach_realname}' /></a>
									</td>
								</tr>
								<tr>
									<td style='color:#ffffff;font-size:10px;text-align:center'>
										{$messages[id].attachments[aid].imagesize.x}x{$messages[id].attachments[aid].imagesize.y} {$messages[id].attachments[aid].size}
									</td>
								</tr>
							</table>
						{else}
							{if $smarty.const.DOWNLOAD_IMAGES}
								<a href='{$smarty.const.BASEDIR}getfile.php?type=pa&amp;file_id={$messages[id].attachments[aid].pmattach_id}' alt='{$messages[id].attachments[aid].pmattach_comment}'>{$messages[id].attachments[aid].pmattach_realname}</a> ({$messages[id].attachments[aid].imagesize.x} x {$messages[id].attachments[aid].imagesize.y} {$locale.511})
							{else}
								<a href='{$messages[id].attachments[aid].link}' alt='{$messages[id].attachments[aid].pmattach_comment}' target='_blank'>{$messages[id].attachments[aid].pmattach_realname}</a> ({$messages[id].attachments[aid].imagesize.x} x {$messages[id].attachments[aid].imagesize.y} {$locale.511})
							{/if}
						{/if}
					{/if}
				{else}
					{if $messages[id].attachments[aid].pmattach_id == 0}
						{$messages[id].attachments[aid].pmattach_realname} ( {$messages[id].attachments[aid].size} )
					{else}
						<a href='{$smarty.const.BASEDIR}getfile.php?type=pa&amp;file_id={$messages[id].attachments[aid].pmattach_id}' alt='{$messages[id].attachments[aid].pmattach_comment}'>{$messages[id].attachments[aid].pmattach_realname}</a> ( {$messages[id].attachments[aid].size} )
					{/if}

				{/if}
			{else}
				{$messages[id].attachments[aid].pmattach_realname} ( {$locale.512} )
			{/if}
			<br />
			{if $messages[id].attachments[aid].pmattach_comment|default:"" != ""}
				<span class='small2'>{$messages[id].attachments[aid].pmattach_comment}</span>
				<br />
			{/if}
						</td>
					</tr>
			{if $smarty.section.aid.last}
				</table>
			{/if}
		{/section}
		</td>
	</tr>
	<tr valign='bottom'>
		<td class='tbl_left_bottom'>
		{if $messages[id].pmindex_user_id == $messages[id].pmindex_to_id}
			{buttonlink name=$locale.560 link=$smarty.const.FUSION_SELF|cat:"?action=post&amp;user_id="|cat:$messages[id].pmindex_from_id|cat:"&amp;msg_id=0"}
		{else}
			{buttonlink name=$locale.560 link=$smarty.const.FUSION_SELF|cat:"?action=post&amp;user_id="|cat:$messages[id].pmindex_to_id|cat:"&amp;msg_id=0"}
		{/if}
		{if $messages[id].user_msn|default:"" != ""}
			{buttonlink name=$locale.561 link="mailto:"|cat:$messages[id].user_msn}
		{/if}
		{if $messages[id].user_icq|default:"" != ""}
			{buttonlink name=$locale.562 link="http://web.icq.com/wwp?Uin="|cat:$messages[id].user_icq new="yes"}
		{/if}
		{if $messages[id].user_web|default:"" != ""}
			{buttonlink name=$locale.563 link=$messages[id].user_web new="yes"}
		{/if}
		{if false && $messages[id].user_aim|default:"" != ""}
			{buttonlink name=$locale.564 link="aim:goim?screenname="|cat:$messages[id].user_aim|replace:' ':'+'}
		{/if}
		{* used the YAHOO field to store the Skype ID *}
		{if false && $messages[id].user_yahoo|default:"" != ""}
			{buttonlink name=$locale.565 link="skype:"|cat:$messages[id].user_yahoo|cat:"?call"}
		{/if}
		</td>
		<td colspan='3' class='{if $messages[id].pmindex_read_datestamp == 0}unread{else}tbl_right{/if}' style='border-top:none;'>
		{if $messages[id].user_sig|default:"" != ""}
			<hr />
			{$messages[id].user_sig}
		{/if}
		</td>
	</tr>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
