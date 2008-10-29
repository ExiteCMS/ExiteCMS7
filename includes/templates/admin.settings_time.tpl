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
{* Template for the admin configuration module 'settings_time'             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='600'>
		<tr>
			<td class='tbl2'>
				{$locale.451}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='shortdatetext' class='textbox' style='width:201px;'>
				{foreach from=$options item=option key=optionkey}
				<option value='{$optionkey}'>{$localtime|date_format:$option}</option>
				{/foreach}
			</select>
			&nbsp;<input type='button' name='setshortdate' value='>>' onclick="if (shortdatetext.selectedIndex>0) {ldelim}shortdate.value=shortdatetext.options[shortdatetext.selectedIndex].value;shortdatetext.selectedIndex=0;{rdelim}" class='button' />
			&nbsp;<input type='text' name='shortdate' value='{$settings2.shortdate}' maxlength='50' class='textbox' style='width:180px;' />
			&nbsp;<span class='small'>( {$smarty.now|date_format:$settings2.shortdate} )</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				{$locale.452}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='longdatetext' class='textbox' style='width:201px;'>
				{foreach from=$options item=option key=optionkey}
				<option value='{$optionkey}'>{$localtime|date_format:$option}</option>
				{/foreach}
			</select>
			&nbsp;<input type='button' name='setlongdate' value='>>' onclick="if (longdatetext.selectedIndex>0) {ldelim}longdate.value=longdatetext.options[longdatetext.selectedIndex].value;longdatetext.selectedIndex=0;{rdelim}" class='button' />
			&nbsp;<input type='text' name='longdate' value='{$settings2.longdate}' maxlength='50' class='textbox' style='width:180px;' />
			&nbsp;<span class='small'>( {$smarty.now|date_format:$settings2.longdate} )</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				{$locale.453}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='forumdatetext' class='textbox' style='width:201px;'>
				{foreach from=$options item=option key=optionkey}
				<option value='{$optionkey}'>{$localtime|date_format:$option}</option>
				{/foreach}
			</select>
			&nbsp;<input type='button' name='setforumdate' value='>>' onclick="if (forumdatetext.selectedIndex>0) {ldelim}forumdate.value=forumdatetext.options[forumdatetext.selectedIndex].value;forumdatetext.selectedIndex=0;{rdelim}" class='button' />
			&nbsp;<input type='text' name='forumdate' value='{$settings2.forumdate}' maxlength='50' class='textbox' style='width:180px;' />
			&nbsp;<span class='small'>( {$smarty.now|date_format:$settings2.forumdate} )</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				{$locale.454}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='subheaderdatetext' class='textbox' style='width:201px;'>
				{foreach from=$options item=option key=optionkey}
				<option value='{$optionkey}'>{$localtime|date_format:$option}</option>
				{/foreach}
			</select>
			&nbsp;<input type='button' name='setsubheaderdate' value='>>' onclick="if (subheaderdatetext.selectedIndex>0) {ldelim}subheaderdate.value=subheaderdatetext.options[subheaderdatetext.selectedIndex].value;subheaderdatetext.selectedIndex=0;{rdelim}" class='button' />
			&nbsp;<input type='text' name='subheaderdate' value='{$settings2.subheaderdate}' maxlength='50' class='textbox' style='width:180px;' />
			&nbsp;<span class='small'>( {$smarty.now|date_format:$settings2.subheaderdate} )</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td class='tbl2'>
				{$locale.456}
			</td>
		</tr>
		<tr>
			<td class='tbl'>
			<select name='timeoffset' class='textbox' style='width:75px;'>
				{section name=offset max=24 loop=25 step=-1}
					<option{if $settings2.timeoffset == -$smarty.section.offset.index} selected="selected"{/if}>-{$smarty.section.offset.index/2}</option>
				{/section}
				{section name=offset start=0 loop=25 step=1}
					<option{if $settings2.timeoffset == $smarty.section.offset.index} selected="selected"{/if}>+{$smarty.section.offset.index/2}</option>
				{/section}
			</select>
			&nbsp;<span class='small'>{$serverzone}</span>
			<br /><br />
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<input type='submit' name='savesettings' value='{$locale.750}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
