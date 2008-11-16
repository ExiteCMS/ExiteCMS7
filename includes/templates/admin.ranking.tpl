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
{* Template for the admin configuration module 'ranking'                   *}
{*                                                                         *}
{***************************************************************************}
{if $action == "edit" || $action == "add"}
	{assign var=tabletitle value=$locale.401}{if $action == "edit"}{assign var=tabletitle value=$locale.402}{/if}
	{include file="_opentable.tpl" name=$_name title=$tabletitle state=$_state style=$_style}
	<form name='layoutform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action={$action}'>
		<table align='center' cellpadding='0' cellspacing='1' width='100%'>
			<tr>
				<td class='tbl'>
					{$locale.421}
				</td>
				<td class='tbl'>
					{$locale.422}
					<input type='text' name='rank_posts_from' value='{$rank_posts_from}' maxlength='8' class='textbox' style='width:100px;' />
					{$locale.423}
					<input type='text' name='rank_posts_to' value='{$rank_posts_to}' maxlength='8' class='textbox' style='width:100px;' />
					{$locale.424} <span style='color:red;'>*</span>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.425}
				</td>
				<td class='tbl'>
					<input type='text' name='rank_title' value='{$rank_title}' maxlength='50' class='textbox' style='width:350px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.438}
				</td>
				<td class='tbl'>
					<input type='text' name='rank_color' value='{$rank_color}' maxlength='15' class='textbox' style='width:200px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.439}
				</td>
				<td class='tbl'>
					<select name='rank_tooltip' class='textbox' style=''>
						<option value='0'{if $rank_tooltip == "0"} selected="selected"{/if}>{$locale.440}</option>
						<option value='1'{if $rank_tooltip == "1"} selected="selected"{/if}>{$locale.441}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.434}
				</td>
				<td class='tbl'>
					<input type='text' name='rank_order' value='{$rank_order}' maxlength='3' class='textbox' style='width:50px;' />
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.426}
				</td>
				<td class='tbl'>
					<select name='rank_image' id='rank_image' class='textbox' style='width:352px;'>
					<option value=''>{$locale.427}</option>
					{foreach from=$imagelist item=image}
						<option{if $rank_image == $image} selected="selected"{/if}>{$image}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td class='tbl'>
					{$locale.428}
				</td>
				<td class='tbl'>
					<select name='rank_image_repeat' class='textbox' style='width:40px;'>
					{section name=repeat start=1 loop=16}
						<option value='{$smarty.section.repeat.index}'{if $rank_image_repeat == $smarty.section.repeat.index} selected='selected'{/if}>{$smarty.section.repeat.index}</option>
					{/section}
					</select>
					{$locale.429}
				</td>
			</tr>
		</table>
		<table align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<p class='small'>{$locale.433}</p>
				</td>
			</tr>
			<tr>
				<td width='50%' align='center' class='tbl'>
					{$locale.430}
					<br />
					<select multiple="multiple" size='15' name='grouplist1' id='grouplist1' class='textbox' style='width:150px' onchange="addUser('grouplist2','grouplist1');">
					{section name=id loop=$create_group_1}
						<option value='{$create_group_1[id].0}'>{$create_group_1[id].1}</option>
					{/section}
					</select>
				</td>
				<td width='50%' align='center' class='tbl'>
					{$locale.431}
					<br />
					<select multiple="multiple" size='15' name='grouplist2' id='grouplist2' class='textbox' style='width:150px' onchange="addUser('grouplist1','grouplist2');">
					{section name=id loop=$create_group_2}
						<option value='{$create_group_2[id].0}'>{$create_group_2[id].1}</option>
					{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					{$locale.435}
					<select name='rank_groups_and' class='textbox' style=''>
						<option value='0'{if $rank_groups_and == "0"} selected="selected"{/if}>{$locale.436}</option>
						<option value='1'{if $rank_groups_and == "1"} selected="selected"{/if}>{$locale.437}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<input type='hidden' name='rank_id' value='{$rank_id}' />
					<input type='hidden' name='rank_order_old' value='{$rank_order}' />
					<input type='hidden' name='rank_groups' />
					<input type='submit' name='save' value='{$locale.432}' class='button' onclick="savegroups();" />
				</td>
			</tr>
		</table>
	</form>
	{literal}
	<script type='text/javascript'>
	function addUser(toGroup,fromGroup) {
		var listLength = document.getElementById(toGroup).length;
		var selItem = document.getElementById(fromGroup).selectedIndex;
		var selText = document.getElementById(fromGroup).options[selItem].text;
		var selValue = document.getElementById(fromGroup).options[selItem].value;
		var i; var newItem = true;
		for (i = 0; i < listLength; i++) {
			if (document.getElementById(toGroup).options[i].text == selText) {
				newItem = false; break;
			}
		}
		if (newItem) {
			document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);
			document.getElementById(fromGroup).options[selItem] = null;
		}
	}
	function savegroups() {
		var strValuesCG = new Array();
		var c = 0;
		var boxLength = document.getElementById('grouplist2').length;
		if (boxLength != 0) {
			c = 0;
			for (i = 0; i < boxLength; i++) {
				if (document.getElementById('grouplist2').options[i].value > -1) {
					strValuesCG[c++] = document.getElementById('grouplist2').options[i].value;
				}
			}
		}
		document.forms['layoutform'].rank_groups.value = strValuesCG;
		document.forms['layoutform'].submit();
	}
	</script>
	{/literal}
	{include file="_closetable.tpl"}
{else}
	{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
	<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
		<tr>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.411}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.412}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.413}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.414}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.415}</b>
			</td>
			<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
				<b>{$locale.416}</b>
			</td>
		</tr>
		{assign var=start_rank value=0}
		{section name=id loop=$rankings}
			<tr>
				<td align='right' width='1%' class='tbl1' style='white-space:nowrap'>
					{$rankings[id].rank_posts_from} - {$rankings[id].rank_posts_to}
				</td>
				<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
					{if $rankings[id].rank_title}
						{if $rankings[id].rank_tooltip}<img src='{$smarty.const.THEME}images/tooltip.jpg' alt='' style='vertical-align:top;'/>{else}&nbsp;&nbsp;&nbsp;&nbsp;{/if}
						{if $rankings[id].rank_color}
							<span style='color:{$rankings[id].rank_color};'>{$rankings[id].rank_title}</span>
						{else}
							{$rankings[id].rank_title}
						{/if}
					{else}
						-
					{/if}
				</td>
				<td align='left' width='1%' class='tbl1' style='white-space:nowrap'>
					{if $rankings[id].rank_image}
						{section name=img start=0 loop=$rankings[id].rank_image_repeat}
						<img src='{$smarty.const.IMAGES}ranking/{$rankings[id].rank_image}' alt='' style='border:0px;' />
						{/section}
					{else}
						-
					{/if}
				</td>
				<td align='center' width='1%' class='tbl1' style=''>
					{$rankings[id].rank_groups|default:"-"}
				</td>
				<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
					{if !$smarty.section.id.first}
						{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=up&amp;rank_order="|cat:$rankings[id].rank_order image="up.gif" alt=$locale.417 title=$locale.417}
					{/if}
					{if !$smarty.section.id.last}
						{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=down&amp;rank_order="|cat:$rankings[id].rank_order image="down.gif" alt=$locale.418 title=$locale.418}
					{/if}
				</td>
				<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=edit&amp;rank_id="|cat:$rankings[id].rank_id image="page_edit.gif" alt=$locale.419 title=$locale.419}
					&nbsp;
					{imagelink link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=delete&amp;rank_id="|cat:$rankings[id].rank_id image="page_delete.gif" alt=$locale.420 title=$locale.420}
				</td>
			</tr>
		{sectionelse}
		 	<tr>
				<td align='center' colspan='6' class='tbl1'>
					{$locale.408}
				</td>
			</tr>
		{/section}
	 	<tr>
			<td align='center' colspan='6' class='tbl1'>
				{buttonlink name=$locale.401 link=$smarty.const.FUSION_SELF|cat:$aidlink|cat:"&amp;action=add"}
				<br /><br />
				<span class='small'>{$locale.409}</span>
			</td>
		</tr>
	</table>
	{include file="_closetable.tpl"}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
