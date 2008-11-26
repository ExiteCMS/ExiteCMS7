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
{* Template for the admin user-admin module 'administrators'               *}
{*                                                                         *}
{***************************************************************************}
{if $show_edit_panel}
	{assign var='page' value=''}
	{assign var='columns' value='2'}	{* when you change this, also change the values of the cycle counters ! *}
	{assign var='column' value=$columns}
	{section name=id loop=$modules}
		{if $smarty.section.id.first}
			{include file="_opentable.tpl" name=$_name title=$locale.420|cat:" <b>"|cat:$admin.user_name|cat:"</b>" state=$_state style=$_style}
			<form name='rightsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;user_id={$edit}'>
				<table align='center' cellpadding='0' cellspacing='1' width='{math equation='x*200' x=$columns}' class='tbl-border'>
		{/if}
		{if $page != $modules[id].page_name}
			{assign var='page' value=`$modules[id].page_name`}
			{section name=x start=$column loop=$columns}
				<td width='{math equation='100/x' x=$columns format='%u'}%' class='tbl1'>
					&nbsp;
				</td>
				{if $smarty.section.x.last}</tr>{/if}
				{cycle name=x assign='column' values='1,2' print=false} {* make sure values equals number of columns *}
			{/section}
			{if $column != $columns}
					</tr>
			{/if}
					<tr>
						<td align='center' colspan='{$columns}' class='tbl2'>
							<b>{$page|escape:"html"}</b>
						</td>
					</tr>
		{/if}		
		{cycle name=x assign='column' values='1,2' print=false} {* make sure values equals number of columns *}
		{if $column == 1}
					<tr>
		{/if}
						<td width='{math equation='100/x' x=$columns format='%u'}%' class='tbl1'>
		        			<input type='checkbox' name='rights[]' value='{$modules[id].admin_rights}'{if $modules[id].assigned} checked="checked"{/if} /> {$modules[id].admin_title|escape:"html"}
						</td>
		{if $column == $columns}
					</tr>
		{/if}
		{if $smarty.section.id.last}
					{section name=x start=$column loop=$columns}
						<td width='{math equation='100/x' x=$columns format='%u'}%' class='tbl1'>
							&nbsp;
						</td>
						{if $smarty.section.x.last}</tr>{/if}
					{/section}
					<tr>
						<td align='center' colspan='{$columns}' class='tbl1'>
							<input type='button' class='button' onclick="setChecked('rightsform','rights[]',1);" value='{$locale.425}' />
							<input type='button' class='button' onclick="setChecked('rightsform','rights[]',0);" value='{$locale.426}' />
							<br /><br />
							<input type='submit' name='update_admin' value='{$locale.424}' class='button' />
						</td>
					</tr>
				</table>
			</form>
			{include file="_closetable.tpl"}
		{/if}
	{/section}
{else}
	{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
		<table align='center' cellpadding='0' cellspacing='1' width='500' class='tbl-border'>
			<tr>
				<td class='tbl2'>
					<b>{$locale.401}</b>
				</td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
					<b>{$locale.402}</b>
				</td>
				<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
					<b>{$locale.403}</b>
				</td>
			</tr>
			{section name=id loop=$admins}
				<tr>
					<td class='{cycle values='tbl1,tbl2' advance=no}'>
						<span title='{$admins[id].user_rights}' style='cursor:hand;'>{$admins[id].user_name}</span>
					</td>
					<td align='center' width='1%' class='{cycle values='tbl1,tbl2' advance=no}' style='white-space:nowrap'>
						{$admins[id].user_level}
					</td>
					<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
					{if $admins[id].can_edit && $admins[id].user_id != "1"}
						<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;edit={$admins[id].user_id}'><img src='{$smarty.const.THEME}images/page_edit.gif' alt='{$locale.406}' title='{$locale.406}' /></a>&nbsp;
						<a href='{$smarty.const.FUSION_SELF}{$aidlink}&amp;remove={$admins[id].user_id}'><img src='{$smarty.const.THEME}images/page_delete.gif' alt='{$locale.407}' title='{$locale.407}' /></a>
					{/if}
					</td>
				</tr>
			{/section}
			{section name=id loop=$users}
				{if $smarty.section.id.first}
				<tr>
					<td align='center' colspan='3' class='tbl1'>
						<form name='adminform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
							<select name='user_id' class='textbox'>
				{/if}
								<option value='{$users[id].user_id}'>{$users[id].user_name}</option>
				{if $smarty.section.id.last}
							</select>
							<input type='submit' name='edit_rights' value='{$locale.410}' class='button' />
							<br />
							<input type='checkbox' name='all_rights' value='1' /> {$locale.411}
							{if $userdata.user_id == "1"}
								<br />
								<input type='checkbox' name='make_super' value='1' /> {$locale.user3|string_format:$locale.412}
							{/if}
						</form>
					</td>
				</tr>
				{/if}
			{/section}
		</table>
	{include file="_closetable.tpl"}
{/if}
{literal}
<script type='text/javascript'>
//<![CDATA[
	function setChecked(frmName,chkName,val) {
	dml=document.forms[frmName]; len=dml.elements.length;
	for(i=0;i<len;i++) {
		if (dml.elements[i].name==chkName) {
			dml.elements[i].checked=val;
		}
	}
}
//]]>
</script>
{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
