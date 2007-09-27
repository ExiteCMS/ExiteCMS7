{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: forum.post.move.tpl                                  *}
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
{* This template is used to generate the move-a-post panels                *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.412 state=$_state style=$_style}
{if $stage == 1}
<form name='moveform' method='post' action='{$smarty.const.FUSION_SELF}?action=edit&forum_id={$forum_id}&amp;thread_id={$thread_id}&amp;post_id={$post_id}'>
	<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		<tr>
			<td>
				<table cellpadding='0' cellspacing='1' width='100%'>
					<tr>
						<td class='tbl2' width='150'>
							{$locale.486}
						</td>
						<td class='tbl1'>
							<select name='new_forum_id' class='textbox' style='width:250px;'>
							{section name=id loop=$forums}
								{if $forums[id].forum_new_cat}
									{if !$smarty.section.id.first}</optgroup>{/if}
									<optgroup label='{$forums[id].forum_cat_name}'>
									{assign var='hasvalues' value=false}
								{/if}
								{if !$forums[id].selected}
									<option value='{$forums[id].forum_id}'>{$forums[id].forum_name}</option>
									{assign var='hasvalues' value=true}
								{/if}
								{if $smarty.section.id.last && $hasvalues}</optgroup>{/if}
							{/section}
							</select>
						</td>
					</tr>
					<tr>
						<td colspan='2' class='tbl2' style='text-align:center;'>
							<input type='submit' name='move_post' value='{$locale.413}' class='button' />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
{elseif $stage == 2}
<form name='moveform' method='post' action='{$smarty.const.FUSION_SELF}?action=edit&forum_id={$forum_id}&amp;thread_id={$thread_id}&amp;post_id={$post_id}'>
	<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		<tr>
			<td>
			<table cellpadding='0' cellspacing='1' width='100%'>
				<tr>
					<td class='tbl2' width='150'>
						{$locale.486}
					</td>
					<td class='tbl1'>
						{$forum_name}
					</td>						
				</tr>
				<tr>
					<td class='tbl2' width='150'>
						{$locale.487}
					</td>
					<td class='tbl1'>
						<input type='hidden' name='new_forum_id' value='{$new_forum_id}' />
						<select name='new_thread_id' class='textbox' style='width:250px;'>
						{section name=id loop=$threads}
							<option value='{$threads[id].thread_id}'{if $threads[id].thread_id == $thread_id} selected{/if}>{$threads[id].thread_ident}</option>
						{/section}
						</select>
					</td>
				</tr>
				<tr>
					<td colspan='2' class='tbl2' style='text-align:center;'>
						<input type='submit' name='move_post' value='{$locale.412}' class='button' />
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</form>
{/if}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}