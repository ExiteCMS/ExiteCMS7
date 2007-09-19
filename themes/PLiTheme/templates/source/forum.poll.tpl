{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: forum.poll.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-12 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the forum modules. Displays a forum poll                   *}
{*                                                                         *}
{***************************************************************************}
<form name='fpm_voting_form' method='POST' action='{$smarty.const.FUSION_SELF}?forum_id={$forum_id}&amp;thread_id={$thread_id}'>
	<table align='center' width='100%' cellspacing='1' cellpadding='3' class='tbl-border'>
		<tr>
			<td colspan='2' class='tbl2'>
				<table align='center' width='100%' cellspacing='0' cellpadding='0'>
					<tr>
						<td class='tbl2'>
							<b>{$locale.FPM_201}{$poll_options.poll_question}</b>
						</td>
						{if !$poll_preview|default:false}
							{if ($userdata.user_id|default:"" == $poll_options.post_author && $total_votes == 0) || $smarty.const.iMOD || $smarty.const.iSUPERADMIN}
							<td align='right' width='1%' class='tbl2'>
								<a href='post.php?action=edit&forum_id={$forum_id}&thread_id={$thread_id}&post_id={$poll_options.post_id}'>
									<img src='{$smarty.const.THEME}forum/edit.gif' alt='{$locale.568}' style='border:0px;'>
								</a>
							</td>
							{/if}
						{/if}
					</tr>
				</table>
			</td>
		</tr>
		{section name=item loop=$poll}
		<tr>
			<td class='tbl2' width='75' align='center'>
			{if $voted == 0}
					<input type='radio' name='fpm[option]' value='{$poll[item].option_id}' />
				</td>
				{if $poll[item].is_link}
				<td class='tbl1'>
					<a href='{$poll[item].option_text}' target='_blank'>{$poll[item].option_text}</a>
				{else}
				<td class='tbl1'>
					{$poll[item].option_text}
				{/if}
			{else}
				<b>{$poll[item].vote_results}%</b>
				<br />
				<small>{if $poll[item].option_votes == 1}1 {$locale.FPM_202}{else}{$poll[item].option_votes} {$locale.FPM_203}{/if}</small>
			</td>
			<td class='tbl1' valign='middle'>
				<small>
				{if $poll[item].is_link}
					<a href='{$poll[item].option_text}' target='_blank'>{$poll[item].option_text}</a>
				{else}
					{$poll[item].option_text}
				{/if}
				</small>
				<br />
				<img src='{$smarty.const.THEME}images/pollbar.gif' height='12' width='{$poll[item].vote_results}%' class='poll' alt='{$poll[item].option_text}' />
				{if $poll_options.poll_type == 0}
					{* DUMMY *}
				{elseif $poll_options.poll_type == 1}
					{section name=user loop=$poll[item].user_votes}
						{if $smarty.section.user.first}
							<br />{$locale.FPM_204}
						{else}
							, 
						{/if}
						<a href='/profile.php?lookup={$poll[item].user_votes[user].user_id}'>{$poll[item].user_votes[user].user_name}</a>						
					{/section}
				{/if}
			{/if}
			</td>
		</tr>
		{/section}
		<tr>
			<td align='center' colspan='2' class='tbl2'>
				{if !$smarty.const.iMEMBER && $voted == 0}
				<input type='button' name='fpm_guest' value='{$locale.FPM_050}' class='button' onClick="javascript:location.href='{$smarty.const.BASEDIR}login.php'" />
				{elseif $voted == 0}
				<input type='submit' name='fpm[vote]' value='{$locale.FPM_202}' class='button'{if $poll_preview|default:false} disabled{/if}/>
				{/if}
				<div style='padding-top: 5px;'>
					{$locale.FPM_205}
					{if $poll_preview|default:false}
						{$poll_options.user_name}
					{else}
						<a href='{$smarty.const.BASEDIR}profile.php?lookup={$poll_options.post_author}'>{$poll_options.user_name}</a>
					{/if}
					|
					{$locale.FPM_206}{$poll_options.poll_start|date_format:"forumdate"} |
					{if $poll_ended}
						{$locale.FPM_207}{$poll_options.poll_end|date_format:"forumdate"} | 
					{elseif $poll_options.poll_end == 0}
						{$locale.FPM_208} {$locale.FPM_209} | 
					{else}
						{$locale.FPM_208}{$poll_options.poll_end|date_format:"forumdate"} | 
					{/if}
					{$locale.FPM_204}{$total_votes}
				</div>
			</td>
		</tr>
	</table>
	<input type='hidden' name='fpm[id]' value='{$poll_options.poll_id}' />
</form>
<table width='100%' cellspacing='0' cellpadding='0'>
	<tr>
		<td height='5'></td>
	</tr>
</table>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}