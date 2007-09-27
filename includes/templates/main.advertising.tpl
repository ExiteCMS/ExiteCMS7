{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.advertising.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-07 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'advertising'                              *}
{*                                                                         *}
{***************************************************************************}
{if $errormessage|default:"" != ""}
	{include file="_message_table_panel.tpl" name=$_name title=$errortitle state=$_state style=$_style message=$errormessage bold=true}
{elseif !$smarty.const.iMEMBER}
	{include file="_opentable.tpl" name=$_name title=$locale.500  state=$_state style=$_style}
	<div align='center'>
		<br />
		<b>{$locale.502|string_format:$locale.950}</b>
		<br /><br />
	</div>
	{include file="_closetable.tpl"}
{elseif !$is_client}
	{include file="_opentable.tpl" name=$_name title=$locale.500  state=$_state style=$_style}
	<div align='center'>
		<br />
		<b>{$userdata.user_name|string_format:$locale.950}</b>
		<br /><br />
	</div>
	{include file="_closetable.tpl"}
{else}
	{if $is_updated}
		{include file="_opentable.tpl" name=$_name title=$locale.500  state=$_state style=$_style}
		<div align='center'>
			<br />
			<b>{$id|string_format:$locale.951}</b>
			<br /><br />
		</div>
		{include file="_closetable.tpl"}
	{/if}
	{section name=ad loop=$ads1}
		{include file="_opentable.tpl" name=$_name title=$locale.402  state=$_state style=$_style}
		{if $smarty.section.ad.first}
		<table align='center' cellpadding='0' cellspacing='1' width='790' class='tbl-border'>
			<tr>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.460}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.461}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.462}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.501}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.463}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.479}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.464}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.465}</b></td>
			</tr>
		{/if}
			<tr>
				<td rowspan='2' align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].adverts_id}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].user_name}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].ad_location}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].adverts_priority}/5</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].contract_type}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].adverts_shown}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].adverts_clicks}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads1[ad].percentage}%</td>
			</tr>
			<tr>
				<td colspan='4' align='left' class='tbl1'>
					<a href='{$ads1[ad].adverts_url}'><img src='{$smarty.const.IMAGES_ADS}{$ads1[ad].adverts_image}' border='0' /></a>
				</td>
				<td colspan='3' align='center' class='tbl1'>
					<form name='edit_advert' method='post' action='{$smarty.const.FUSION_SELF}?action=update&amp;id={$ads1[ad].adverts_id}'>
						<input class='textbox' type='text' name='adverts_url' size='40' maxlength='200' value='{$ads1[ad].adverts_url}'>
						<br /><br />
						<input type='submit' name='change' value='{$locale.444}' class='button' />&nbsp;
						<input type='submit' name='email' value='{$locale.445}' class='button' />
					</form>
				</td>
			</tr>
		</table>
		<br />
		<form name='email_all' method='post' action='{$smarty.const.FUSION_SELF}?action=update&amp;id=all'>
			<div align='center'><input type='submit' name='email' value='{$locale.446}' class='button' /></div>
		</form>
		{include file="_closetable.tpl"}
	{sectionelse}
		{include file="_opentable.tpl" name=$_name title=$locale.402  state=$_state style=$_style}
		<div align='center'>
			<br />
			<b>{$userdata.user_name|string_format:$locale.954}</b>
			<br /><br />
			<b>{$locale.955}</b>
			<br /><br />
		</div>
		{include file="_closetable.tpl"}
	{/section}
	{section name=ad loop=$ads2}
		{if $smarty.section.ad.first}
		{include file="_opentable.tpl" name=$_name title=$locale.403  state=$_state style=$_style}
		<table align='center' cellpadding='0' cellspacing='1' width='790' class='tbl-border'>
			<tr>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.460}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.461}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.462}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.501}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.463}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.479}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.464}</b></td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.465}</b></td>
			</tr>
		{/if}
			<tr>
				<td rowspan='2' align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].adverts_id}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].user_name}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].ad_location}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].adverts_priority}/5</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].contract_type}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].adverts_shown}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].adverts_clicks}</td>
				<td align='center' class='tbl1' style='white-space:nowrap'>{$ads2[ad].percentage}%</td>
			</tr>
			<tr>
				<td colspan='4' align='left' class='tbl1'>
					<a href='{$ads2[ad].adverts_url}'><img src='{$smarty.const.IMAGES_ADS}{$ads2[ad].adverts_image}' border='0' /></a>
				</td>
				<td colspan='3' align='center' class='tbl1'>
					<form name='edit_advert' method='post' action='{$smarty.const.FUSION_SELF}?action=update&amp;id={$ads2[ad].adverts_id}'>
						<input class='textbox' type='text' name='adverts_url' size='40' maxlength='200' value='{$ads2[ad].adverts_url}'>
						<br /><br />
						<input type='submit' name='change' value='{$locale.444}' class='button' />&nbsp;
						<input type='submit' name='email' value='{$locale.445}' class='button' />
					</form>
				</td>
			</tr>
		</table>
		<br />
		<form name='email_all' method='post' action='{$smarty.const.FUSION_SELF}?action=update&amp;id=all'>
			<div align='center'><input type='submit' name='email' value='{$locale.446}' class='button' /></div>
		</form>
		{include file="_closetable.tpl"}
	{sectionelse}
		{include file="_opentable.tpl" name=$_name title=$locale.403  state=$_state style=$_style}
		<div align='center'>
			<br />
			<b>{$userdata.user_name|string_format:$locale.954}</b>
			<br /><br />
		</div>
		{include file="_closetable.tpl"}
	{/section}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}