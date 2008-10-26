{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.search.tpl                                      *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-08-08 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'search'                                   *}
{*                                                                         *}
{***************************************************************************}
{if $action == ""}
	{include file="_opentable.tpl" name=$_name title=$locale.src400|cat:" "|cat:$settings.sitename state=$_state style=$_style}
	<form name='searchform' method='post' action='{$smarty.const.FUSION_SELF}?action=search'>
		<table width='100%' cellspacing='2' cellpadding='2'>
			<tr class='tbl2'>
				<td colspan='2'>
					<b>{$locale.src401}</b>
				</td>
			</tr>
			<tr class='tbl1' style='white-space:nowrap;'>
				<td>
					<input type='text' name='stext' value='{$searchtext}' class='textbox' style='width:225px;' />
					&nbsp;
					<input type='submit' name='search' value='{$locale.src402}' class='button' disabled='disabled' />
					<br />
					<input type='checkbox' name='boolean' value='1' class='textbox' />{$locale.src447}
				</td>
				<td>
					<input type='radio' name='qtype' value='OR' checked='checked' /> {$locale.src407}
					<input type='radio' name='qtype' value='AND' /> {$locale.src408}
					<br />
					&nbsp;<span class='smallalt'>{$locale.src427}</span>
				</td>
			</tr>
		</table>
		<table width='100%' cellspacing='2' cellpadding='2'>
			<tr class='tbl2'>
				<td>
					<b>{$locale.src403}</b>
				</td>
				<td>
					<b>{$locale.src404}</b>
				</td>
			</tr>
			<tr class='tbl1'>
				<td valign='top'>
					{section name=id loop=$searches}
						{include file=$searches[id].template}
					{sectionelse}
						{$locale.src409}
					{/section}
				</td>
				<td valign='top'>
					<table width='100%' cellspacing='2' cellpadding='2'>
						<tr>
							<td align='left' valign='top' class='tbl1'>
								{$locale.src444}: <select id='sortby' name='sortby' class='textbox'>
									<option value='score'>{$locale.src419}</option>
									<option value='author'>{$locale.src422}</option>
									<option value='subject'>{$locale.src421}</option>
									<option value='datestamp' selected='selected'>{$locale.src420}</option>
									<option value='count'>{$locale.src443}</option>
								</select>
							</td>
							<td align='left' class='tbl1'>
								<input type='radio' id='order1' name='order' value='0' checked='checked' /> {$locale.src423}
								<br />
								<input type='radio' id='order2' name='order' value='1' /> {$locale.src424}
							</td>
						</tr>
						<tr>
							<td align='left' class='tbl2' colspan='2'>
								<b>{$locale.src418}</b>
							</td>
						</tr>
						<tr>
							<td align='left' colspan='2'>
								{$locale.src425}:
								<select id='limit' name='limit' class='textbox'>
								<option value='25'>25</option>
								<option value='50'>50</option>
								<option value='100'>100</option>
								<option value='150'>150</option>
								<option value='200'>200</option>
								<option value='0' selected='selected'>{$locale.src410}</option>
								</select> {$locale.src426}
							</td>
						</tr>
						<tr>
							<td align='left' colspan='2'>
								<div id='div_contentfilter_date' style='display:none;'>
									{$locale.src448}:
									<select id='datelimit' name='datelimit' class='textbox'>
										<option value='0' selected='selected'>{$locale.src410}</option>
										<option value='86400'>{$locale.src411}</option>
										<option value='604800'>{$locale.src412}</option>
										<option value='1209600'>{$locale.src413}</option>
										<option value='2419200'>{$locale.src414}</option>
										<option value='7257600'>{$locale.src415}</option>
										<option value='14515200'>{$locale.src416}</option>
									</select>
								</div>
							</td>
						</tr>
						{if $content_filters|@count > 0}
							{foreach from=$content_filters item=contentfilter}
							<tr>
								<td align='left' valign='top' colspan='2'>
									<div id='div_{$contentfilter.field}' style='display:none;'>
									{$locale.src446} {$contentfilter.title|lower}:
									<select id='{$contentfilter.field}' name='{$contentfilter.field}' class='textbox'>
										<option value='0' selected='selected'>{$locale.src410}</option>
										{section name=uidx loop=$contentfilter.values}
										<option value='{$contentfilter.values[uidx].id}'>{$contentfilter.values[uidx].value}</option>
										{/section}
									</select>
									</div>
								</td>
							</tr>
							{/foreach}
						{/if}
					</table>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<hr />
					{$locale.src445}
				</td>
			</tr>
		</table>
	</form>
	{literal}
	<script type='text/javascript'>
	function show_filter(filter) {
		document.searchform.search.disabled = false;
	{/literal}
		document.getElementById("div_contentfilter_date").style.display = 'none';
		{foreach from=$content_filters item=contentfilter}
		document.getElementById("div_{$contentfilter.field}").style.display = 'none';
		{/foreach}
	{literal}
		var filters = filter.split(",");
		for(i = 0; i < filters.length; i++){
			document.getElementById('div_contentfilter_'+filters[i]).style.display = 'block';
		}
	}
	{/literal}
	show_filter("{$default_filter}");
	</script>
	{include file="_closetable.tpl"}
		{include file="_opentable.tpl" name=$_name title=$locale.src428 state=$_state style=$_style}
	<table width='100%' cellspacing='0' cellpadding='0' border='0' class='tbl-border'>
	{foreach from=$locale.src405 item=line}
		<tr>
			{if $line.0|default:"" != ""}
				<td align='center' class='tbl1' style='white-space:nowrap'>
					{$line.0}
				</td>
				<td align='center' class='tbl1'>
					{if $line.0 != ""} :{/if}
				</td>
				<td class='tbl1'>
					{$line.1}
				</td>
			{else}
				<td align='left' class='tbl1' style='white-space:nowrap' colspan='3'>
					{$line.1}
				</td>
			{/if}
		</tr>
	{/foreach}
	</table>
	{include file="_closetable.tpl"}
{else}
	{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
	<table width='100%'>
		<tr>
			<td align='left'>
				{$locale.src429} "<b>{$stext}</b>" {$locale.src430} <b>{$searches.0.search_title}</b> {$locale.src442|sprintf:$rows}
			</td>
			<td align='right'>
				<form name='return' method='post' action='{$smarty.const.FUSION_SELF}'>
					<input type='submit' class='button' name='view' value='{$locale.src431}' />
				</form>
			</td>
		</tr>
		{if $message|default:"" != ""}
		<tr>
			<td align='left'>
				<span style='color:red;font-weight:bold;'>{$message}</span>
			</td>
		</tr>
		{/if}
	</table>
	{if $message|default:"" == ""}
		<hr />
		{include file=$searches.0.template}
	{/if}
	{include file="_closetable.tpl"}
	{if $rows > $items_per_page}
		{makepagenav start=$rowstart count=$items_per_page total=$rows range=$settings.navbar_range link=$pagenav_url}
	{/if}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
