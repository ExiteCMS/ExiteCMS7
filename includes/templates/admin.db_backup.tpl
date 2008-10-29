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
{* Template for the admin configuration module 'db_backup'                 *}
{*                                                                         *}
{***************************************************************************}
{if $error|default:0 != 0}
	{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
	<center><b>
	<br />
	{if $error == 1}{$locale.404}{/if}
	{if $error == 2}{$locale.405}{/if}
	{if $error == 3}{$locale.406}{/if}
	{if $error == 4}{$locale.407}{/if}
	{if $error == 5}{$locale.408}{/if}
	<br /><br />
	</b></center>
	{include file="_closetable.tpl"}
{/if}
{if $restore_error|default:false}
	{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
	<center>
	{$locale.401}
	<br /><br />
	{$locale.402}
	<br /><br />
	<form action='{$smarty.const.FUSION_SELF}{$aidlink}' name='frm_info' method='post'>
		<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='{$locale.403}' />
	</form>
	</center>
	{include file="_closetable.tpl"}
{/if}
{if $action == "restore"}
	{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}

	{literal}<script type='text/javascript'>
	<!--
	function tableSelectAll(){for(i=0;i<document.restoreform.elements['list_tbl[]'].length;i++){document.restoreform.elements['list_tbl[]'].options[i].selected=true;}}
	function tableSelectCore(){for(i=0;i<document.restoreform.elements['list_tbl[]'].length;i++){document.restoreform.elements['list_tbl[]'].options[i].selected=(document.restoreform.elements['list_tbl[]'].options[i].text).match(/^{/literal}{$db_prefix}{literal}/);}}
	function tableSelectNone(){for(i=0;i<document.restoreform.elements['list_tbl[]'].length;i++){document.restoreform.elements['list_tbl[]'].options[i].selected=false;}}
	function populateSelectAll(){for(i=0;i<document.restoreform.elements['list_ins[]'].length;i++){document.restoreform.elements['list_ins[]'].options[i].selected=true;}}
	function populateSelectCore(){for(i=0;i<document.restoreform.elements['list_ins[]'].length;i++){document.restoreform.elements['list_ins[]'].options[i].selected=(document.restoreform.elements['list_ins[]'].options[i].text).match(/^{/literal}{$db_prefix}{literal}/);}}
	function populateSelectNone(){for(i=0;i<document.restoreform.elements['list_ins[]'].length;i++){document.restoreform.elements['list_ins[]'].options[i].selected=false;}}
	//-->
	</script>{/literal}

	<form name='restoreform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
		<table align='center' cellspacing='0' cellpadding='0'>
			<tr>
				<td colspan='2' class='tbl2'>
					<b>{$locale.430}</b>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.431} <b>{$backup_name}</b>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.414} <b>{$info_dbname}</b>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.432} <b>{$info_date}</b>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='tbl'>
					{$locale.415}
					<input class='textbox' type='text' name='restore_tblpre' value='{$info_tblpref}' style='width:150px' />
				</td>
			</tr>
			<tr>
				<td valign='top' align='center' class='tbl'>
					{$locale.433}
					<br />
					<select style='width:250px;' class='textbox' id='list_tbl' name='list_tbl[]' size='{$maxrows}' multiple="multiple">
					{foreach from=$info_tables item=table}
						<option value='{$table}' selected="selected">{$table}</option>
					{/foreach}
					</select>
					<br /><br />{$locale.435}&nbsp;
					<div style='display:inline;text-align:center;vertical-align:top;'>
					<input type='button' class='button' name='{$locale.436}' value='{$locale.436}' onclick="javascript:tableSelectAll()" />
					<input type='button' class='button' name='{$locale.458}' value='{$locale.458}' onclick="javascript:tableSelectCore()" />
					<input type='button' class='button' name='{$locale.437}' value='{$locale.437}' onclick="javascript:tableSelectNone()" />
					</div>
				</td>
				<td valign='top' align='center' class='tbl'>
					{$locale.434}
					<br />
					<select style='width:250px;' class='textbox' id='list_ins' name='list_ins[]' size='{$maxrows}' multiple="multiple">
					{section name=id loop=$info_inserts}
						<option value='{$info_inserts[id].id}'{if $info_inserts[id].selected} selected="selected"{/if}>{$info_inserts[id].name}</option>
					{/section}
					</select>
					<br /><br />{$locale.435}&nbsp;
					<div style='display:inline;text-align:center;vertical-align:top;'>
						<input type='button' class='button' name='{$locale.436}' value='{$locale.436}' onclick="javascript:populateSelectAll()" />
						<input type='button' class='button' name='{$locale.458}' value='{$locale.458}' onclick="javascript:populateSelectCore()" />
						<input type='button' class='button' name='{$locale.437}' value='{$locale.437}' onclick="javascript:populateSelectNone()" />
					</div>
				</td>
			</tr>
			<tr>
				<td align='left' colspan='2' class='tbl'>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl2'>
					<b>{$locale.406}</b>
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					{$locale.460}
					<input type='password' name='user_password' value='' class='textbox' style='width:150px;' />
				</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<br />
					<input type='hidden' name='file' value='{$file}' />
					<input class='button' type='submit' name='btn_do_restore' style='width:100px;' value='{$locale.438}' />
					<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='{$locale.439}' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
{else}
	{include file="_opentable.tpl" name=$_name title=$locale.450 state=$_state style=$_style}

	{literal}<script type='text/javascript'>
	<!--
	function backupSelectCore(){for(i=0;i<document.backupform.elements['db_tables[]'].length;i++){document.backupform.elements['db_tables[]'].options[i].selected=(document.backupform.elements['db_tables[]'].options[i].text).match(/^{/literal}{$db_prefix}{literal}/);}}
	function backupSelectAll(){for(i=0;i<document.backupform.elements['db_tables[]'].length;i++){document.backupform.elements['db_tables[]'].options[i].selected=true;}}
	function backupSelectNone(){for(i=0;i<document.backupform.elements['db_tables[]'].length;i++){document.backupform.elements['db_tables[]'].options[i].selected=false;}}
	//-->
	</script>{/literal}

	<form action='{$smarty.const.FUSION_SELF}{$aidlink}' name='backupform' method='post'>
		<table align='center' cellspacing='0' cellpadding='0'>
			<tr>
				<td valign='top'>
					<table align='center' cellspacing='0' cellpadding='0'>
						<tr>
							<td colspan='2' class='tbl2' align='center'>
								<b>{$locale.451}</b>
							</td>
						</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.414}
						</td>
						<td class='tbl'>
							{$db_name}
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.415}
						</td>
						<td class='tbl'>
							{$db_prefix}</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.452}
						</td>
						<td class='tbl'>
							{$db_size} ({$db_tables} {$locale.419})
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.453}
						</td>
						<td class='tbl'>
							{$db_fusion_size} ({$db_fusion_tables} {$locale.419})
						</td>
					</tr>
					<tr>
						<td align='left' colspan='2' class='tbl'>
						</td>
					</tr>
					<tr>
						<td colspan='2' class='tbl2' align='center'>
							<b>{$locale.454}</b>
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.431}
						</td>
						<td class='tbl'>
							<input type='text' name='backup_filename' value='backup_{$smarty.now|date_format:"%Y-%m-%d-%H%I"}' class='textbox' style='width:200px;' />
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.455}
						</td>
						<td class='tbl'>
							<select name='backup_type' class='textbox' style='width:150px;'>
								<option value='.gz' selected="selected">.sql.gz {$locale.456}</option>
								<option value='.sql'>.sql</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.461}
						</td>
						<td class='tbl'>
							<select name='backup_keep' class='textbox' style='width:50px;'>
								<option value='1'>{$locale.420}</option>
								<option value='0' selected="selected">{$locale.421}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.462}
						</td>
						<td class='tbl'>
							<select name='backup_download' class='textbox' style='width:50px;'>
								<option value='1' selected="selected">{$locale.420}</option>
								<option value='0'>{$locale.421}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align='left' colspan='2' class='tbl'>
						</td>
					</tr>
					<tr>
						<td colspan='2' class='tbl2' align='center'>
							<b>{$locale.406}</b>
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl'>
							{$locale.460}
						</td>
						<td class='tbl'>
							<input type='password' name='user_password' value='' class='textbox' style='width:200px;' />
						</td>
					</tr>
				</table>
			</td>
			<td valign='top'>
				<table border='0' cellpadding='0' cellspacing='0'>
					<tr>
						<td class='tbl2'>
							<b>{$locale.457}</b>
						</td>
					</tr>
					<tr>
						<td class='tbl'>
							<select style='margin:5px 0px' class='textbox' id='tablelist' name='db_tables[]' size='17' multiple="multiple">
							{section name=id loop=$table_list}
								<option value='{$table_list[id].id}'{if $table_list[id].selected} selected="selected"{/if}>{$table_list[id].name}</option>
							{/section}
							</select>
							<br />{$locale.435}&nbsp;
							<div style='display:inline;text-align:center;vertical-align:top;'>
								<input type='button' class='button' name='{$locale.458}' value='{$locale.458}' onclick="javascript:backupSelectCore()" />
								<input type='button' class='button' name='{$locale.436}' value='{$locale.436}' onclick="javascript:backupSelectAll()" />
								<input type='button' class='button' name='{$locale.437}' value='{$locale.437}' onclick="javascript:backupSelectNone()" />
							</div>
						</td>
					</tr>
				</table>
			</td>
			</tr>
			<tr>
				<td align='center' colspan='2' class='tbl'>
					<hr />
					<input class='button' type='submit' name='btn_create_backup' style='width:100px;' value='{$locale.459}' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
	{include file="_opentable.tpl" name=$_name title=$locale.480 state=$_state style=$_style}
	{foreach from=$backup_files item=file name=files}
	{if $smarty.foreach.files.first}
	<form name='restore' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=restore' enctype='multipart/form-data'>
		<center>
			{$locale.431}
			<select name='local_file' class='textbox' style='width:300px;'>
	{/if}
				<option value='{$file}'>{$file}</option>
	{if $smarty.foreach.files.last}
			</select>
			<br /><br />
			<input class='button' type='submit' name='local_restore' style='width:100px;' value='{$locale.438}' />
			<input class='button' type='submit' name='local_delete' style='width:100px;' value='{$locale.440}' onclick='return DeleteBackup();' />
		</center>
	</form>
	<br />
	<hr />
	<br />
	{/if}
	{/foreach}
	<form name='restore' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=restore' enctype='multipart/form-data'>
		<center>
			{$locale.431}
			<input type='file' name='upload_backup_file' class='textbox' style='width:200px;' />
			<br /><br />
			<input class='button' type='submit' name='restore' style='width:100px;' value='{$locale.438}' />
		</center>
	</form>
	{include file="_closetable.tpl"}
{/if}
<script type='text/javascript'>
function DeleteBackup() {ldelim}
	return confirm('{$locale.409}');
{rdelim}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
