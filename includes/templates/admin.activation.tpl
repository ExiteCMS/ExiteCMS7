{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.activation.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-12-16 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin module 'activation'                              *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
{section name=id loop=$newusers}
	{if $smarty.section.id.first}
	<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
		<tr>
			<td class='tbl2' style='font-weight:bold;text-align:center;'>
				{$locale.402}
			</td>
			<td class='tbl2' style='font-weight:bold;text-align:center;'>
				{$locale.403}
			</td>
			<td class='tbl2' style='font-weight:bold;text-align:center;width:1%;white-space:nowrap;'>
				{$locale.404}
			</td>
			<td class='tbl2' style='font-weight:bold;text-align:center;width:1%;white-space:nowrap;'>
				{$locale.405}
			</td>
		</tr>
	{/if}
		<tr>
			<td class='{cycle values='tbl1,tbl2' advance=no}' style='text-align:center;'>
				{$newusers[id].user_name}
			</td>
			<td class='{cycle values='tbl1,tbl2' advance=no}' style='text-align:center;'>
				{$newusers[id].user_email}
			</td>
			<td class='{cycle values='tbl1,tbl2' advance=no}' style='text-align:center;width:1%;white-space:nowrap;'>
				{$newusers[id].user_datestamp|date_format:"forumdate"}
			</td>
			<td class='{cycle values='tbl1,tbl2'}' style='text-align:center;width:1%;white-space:nowrap;'>
				{if $settings.email_activation == 1}
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=activate&amp;user_code="|cat:$newusers[id].user_code image="page_green.gif" alt="$locale.406 title=$locale.406}&nbsp;
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=delete&amp;user_code="|cat:$newusers[id].user_code image="page_delete.gif" alt="$locale.407 title=$locale.407}
				{else}
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=activate&amp;user_id="|cat:$newusers[id].user_id image="page_green.gif" alt="$locale.406 title=$locale.406}&nbsp;
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;step=delete&amp;user_id="|cat:$newusers[id].user_id image="page_delete.gif" alt="$locale.407 title=$locale.407}
				{/if}
			</td>
		</tr>
	{if $smarty.section.id.last}
	</table>
	{/if}
{sectionelse}
	<div style='text-align:center;font-weight:bold;'>
		<br />
		{$locale.401}
		<br /><br />
	</div>
{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
