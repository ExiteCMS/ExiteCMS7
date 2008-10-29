{***************************************************************************}
{* ExiteCMS Content Management System                                      *}
{***************************************************************************}
{* Copyright 2006-2008 Exite BV, The Netherlands                           *}
{* for support, please visit http://www.exitecms.org                       *}
{*-------------------------------------------------------------------------*}
{* Released under the terms & conditions of v2 of the GNU General Public   *}
{* License. For details refer to the included gpl.txt file or visit        *}
{* http://gnu.org                                                          *}
{***************************************************************************}
{* $Id::                                                                  $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author::                                             $*}
{* Revision number $Rev::                                                 $*}
{***************************************************************************}
{*                                                                         *}
{* Template for the forum modules. Displays the several option dialogs     *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<div align='center'>
	<br />
{if $step == "renew"}
	{$locale.459}
	<br /><br />
	<a href='viewforum.php?forum_id={$forum_id}'>{$locale.402}</a>
	<br /><br />
	<a href='index.php'>{$locale.403}</a>
{elseif $step == "delete"}
	{if $delete_confirmed}
		<b>{$locale.401}</b>
		<br /><br />
		<a href='viewforum.php?forum_id={$forum_id}'>{$locale.402}</a>
		<br /><br />
		<a href='index.php'>{$locale.403}</a>
	{else}
		<form name='delform' method='post' action='{$smarty.const.FUSION_SELF}?step=delete&amp;forum_id={$forum_id}&amp;thread_id={$thread_id}'>
			{$locale.404}
			<br /><br />
			<input type='submit' name='deletethread' value='{$locale.405}' class='button' style='width:75px' />
			<input type='submit' name='canceldelete' value='{$locale.406}' class='button' style='width:75px' />
		</form>
	{/if}
{elseif $step == "lock"}
	<b>{$locale.411}</b>
	<br /><br />
	<a href='viewforum.php?forum_id={$forum_id}'>{$locale.402}</a>
	<br /><br />
	<a href='index.php'>{$locale.403}</a>
{elseif $step == "unlock"}
	<b>{$locale.421}</b>
	<br /><br />
	<a href='viewforum.php?forum_id={$forum_id}'>{$locale.402}</a>
	<br /><br />
	<a href='index.php'>{$locale.403}</a>
{elseif $step == "sticky"}
	<b>{$locale.431}</b>
	<br /><br />
	<a href='viewforum.php?forum_id={$forum_id}'>{$locale.402}</a>
	<br /><br />
	<a href='index.php'>{$locale.403}</a>
{elseif $step == "nonsticky"}
	<b>{$locale.441}</b>
	<br /><br />
	<a href='viewforum.php?forum_id={$forum_id}'>{$locale.402}</a>
	<br /><br />
	<a href='index.php'>{$locale.403}</a>
{elseif $step == "move"}
	{if $move_confirmed}
		{$locale.452}
		<br /><br />
		<a href='index.php'>{$locale.403}</a>
	{else}
		<form name='moveform' method='post' action='{$smarty.const.FUSION_SELF}?step=move&amp;forum_id={$forum_id}&amp;thread_id={$thread_id}'>
			<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
				<tr>
					<td>
						<table cellpadding='0' cellspacing='1' width='100%'>
							<tr>
								<td class='tbl2' width='150'>
									{$locale.451}
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
									<input type='submit' name='move_thread' value='{$locale.450}' class='button' />
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	{/if}
{elseif $step == "merge"}
	{if $merge_confirmed}
		{$locale.457}
		<br /><br />
		<a href='viewforum.php?forum_id={$forum_id}'>{$locale.402}</a>
	{else}
		<form name='mergeform' method='post' action='{$smarty.const.FUSION_SELF}?step=merge&amp;forum_id={$forum_id}&amp;thread_id={$thread_id}'>
			<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
				<tr>
					<td>
						<table cellpadding='0' cellspacing='1' width='100%'>
							<tr>
								<td class='tbl2' width='150'>
									{$locale.456}
								</td>
								<td class='tbl1'>
									<select name='new_thread_id' class='textbox' style='width:400px;'>
									{section name=id loop=$threads}
										<option value='{$threads[id].thread_id}'{if $threads[id].thread_id == $thread_id} selected{/if}>{$threads[id].thread_ident}</option>
									{/section}
									</select>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='tbl2' style='text-align:center;'><input type='submit' name='merge_threads' value='".$locale['455']."' class='button' /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	{/if}
{/if}
	<br /><br />
</div>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
