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
{* Template for the main module 'pm'.                                      *}
{*                                                                         *}
{***************************************************************************}
{if $errormessage|default:"" != ""}
	{include file="_opentable.tpl" name=$_name title=$locale.480 state=$_state style=$_style}
	<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		<tr>
			<td align='center' class='tbl1'>
				<b>{$errormessage}</b>
			</td>
		</tr>
	</table>
	{include file="_closetable.tpl"}
{/if}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	<tr>
		<td width='24%' align='center' class='{if $folder == $locale.402}tbl1{else}tbl2{/if}'>
			<a href='{$smarty.const.FUSION_SELF}?folder={$locale.402}'><b>{$locale.402} [{$totals.inbox} {$locale.445} {$global_options.pm_inbox}]</b></a>
		</td>
		<td width='25%' align='center' class='{if $folder == $locale.403}tbl1{else}tbl2{/if}'>
			<a href='{$smarty.const.FUSION_SELF}?folder={$locale.403}'><b>{$locale.403} [{$totals.outbox} {$locale.445} {$global_options.pm_sentbox}]</b></a>
		</td>
		<td width='24%' align='center' class='{if $folder == $locale.404}tbl1{else}tbl2{/if}'>
			<a href='{$smarty.const.FUSION_SELF}?folder={$locale.404}'><b>{$locale.404} [{$totals.archive} {$locale.445} {$global_options.pm_savebox}]</b></a>
		</td>
		<td width='13%' align='center' class='{if $folder == $locale.425}tbl1{else}tbl2{/if}'>
			<a href='{$smarty.const.FUSION_SELF}?folder={$locale.425}'><b>{$locale.425}</b></a>
		</td>
	</tr>
</table>
{assign var=hadbreak value=0}
{if !$global_options.pm_inbox_group && $totals.inbox == $global_options.pm_inbox}
	<br />
	{assign var=hadbreak value=1}
	<div class='infobar' style='font-weight:bold;color:red;text-align:center;'>{$locale.488}</div>
{/if}
{if !$global_options.pm_sentbox_group && $totals.outbox == $global_options.pm_sentbox && $user_options.pmconfig_save_sent}
	{if !$hadbreak}
		<br />
		{assign var=hadbreak value=1}
	{/if}
	<div class='infobar' style='font-weight:bold;color:red;text-align:center;'>{$locale.489}</div>
{/if}
{if !$global_options.pm_savebox_group && $totals.archive == $global_options.pm_savebox}
	{if !$hadbreak}
		<br />
		{assign var=hadbreak value=1}
	{/if}
	<div class='infobar' style='font-weight:bold;color:red;text-align:center;'>{$locale.490}</div>
{/if}
<form name='pm_form' method='post' action='{$smarty.const.FUSION_SELF}?folder={$folder}'>
{section name=id loop=$messages}
	{if $smarty.section.id.first}
		<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
			<tr>
				<td width='140' align='left' class='tbl1'>
					{buttonlink name=$locale.401 link=$smarty.const.FUSION_SELF|cat:"?action=post&amp;msg_id=0"}
				</td>
				<td class='tbl1' align='right'>
					<input type='button' class='button' name='{$locale.410|replace:" ":"_"}' value='{$locale.410}' onclick="javascript:setChecked('pm_form','check_mark[]',1);return false;" />
					<input type='button' class='button' name='{$locale.411|replace:" ":"_"}' value='{$locale.411}' onclick="javascript:setChecked('pm_form','check_mark[]',0);return false;" />
					<div style='display:inline; white-space:nowrap;'>
					&nbsp; {$locale.409}
					{if $folder == "inbox"}
						<input type='submit' name='multi_read' value='{$locale.414}' class='button' />
						<input type='submit' name='multi_unread' value='{$locale.415}' class='button' />
					{/if}
					{if $folder != $locale.404 && ($global_options.pm_savebox > $totals.archive || $global_options.pm_savebox_group)}
						<input type='submit' name='multi_archive' value='{$locale.448}' class='button' />
					{/if}
					{if $folder == $locale.404}
						{if ($messages[id].pmindex_user_id == $messages[id].pmindex_to_id && ($global_options.pm_inbox > $totals.inbox || $global_options.pm_inbox_group)) || ($messages[id].pmindex_user_id != $messages[id].pmindex_to_id && ($global_options.pm_sentbox > $totals.outbox || $global_options.pm_sentbox_group))}
							<input type='submit' name='multi_restore' value='{$locale.412}' class='button' />
						{/if}
					{/if}
					<input type='submit' name='multi_delete' value='{$locale.416}' class='button' />
					</div>
				</td>
			</tr>
		</table>
		{if $user_options.pmconfig_view == "0"}
			<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
		{elseif $user_options.pmconfig_view == "1"}
			<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		{/if}
	{/if}
	{if $user_options.pmconfig_view == "0"}
	{if $smarty.section.id.first}
		<tr>
			<td class='tbl2'>
				{$locale.405}
			</td>
			<td align='center' class='tbl2' width='1%' style='white-space:nowrap'>
				{if $folder == $locale.402}
					{$locale.408}
				{elseif $folder == $locale.403}
					{$locale.407}
				{elseif $folder == $locale.404}
					{$locale.407} &bull; {$locale.408}
				{/if}
			</td>
			{if $folder != $locale.403}
				<td align='center' class='tbl2' width='1%' style='white-space:nowrap'>
					{$locale.406}
				</td>
			{/if}
			{if $folder != $locale.402}
				<td align='center' class='tbl2' width='1%' style='white-space:nowrap'>
					{$locale.421}
				</td>
			{/if}
			{if $folder == $locale.403}
			<td align='center' class='tbl2' width='1%' style='white-space:nowrap'>
				{$locale.542}
			</td>
			{/if}
			<td class='tbl2' width='20'>
			</td>
		</tr>
	{/if}
	{if $messages[id].pmindex_id == $view_id}
	<tr>
		<td colspan='5' class='tbl2'>
			<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
				{assign var='is_inline' value=true}
				{include file="main.pm.renderpm.tpl"}
			<tr>
				<td colspan='4' class='tbl1'>
					<span class='small'>
					{section name=rc loop=$messages[id].readstatus}
						{if $smarty.section.rc.first}
							{$locale.542}:<br />
						{/if}
						<a href='{$smarty.const.BASEDIR}profile.php?lookup={$messages[id].readstatus[rc].user_id}'>{$messages[id].readstatus[rc].user_name}</a>:
						{if $messages[id].readstatus[rc].read}
							{$messages[id].readstatus[rc].datestamp|date_format:"forumdate"}
						{else}
							{$locale.543}
						{/if}
						{if !$smarty.section.rc.last},{/if}
					{/section}
					</span>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	{else}
	<tr>
		<td class='tbl1'>
			{if $messages[id].pmindex_read_datestamp == 0}<b>{/if}
			<a href='{$smarty.const.FUSION_SELF}?folder={$folder}&amp;rowstart={$rowstart}&amp;action=view&amp;msg_id={$messages[id].pmindex_id}#view_{$messages[id].pmindex_id}'>{$messages[id].pm_subject}</a>
			{if $messages[id].pmindex_read_datestamp == 0} - {$locale.449}</b>{/if}
		</td>
		<td class='tbl1' style='white-space:nowrap'>
			{if $messages[id].pm_datestamp != 0}{$messages[id].pm_datestamp|date_format:"forumdate"}{/if}
		</td>
		{if $folder != $locale.403}
		<td class='tbl1' style='white-space:nowrap'>
			{if $messages[id].sender.user_id}
				{$messages[id].sender.cc_flag}
				<a href='{$smarty.const.BASEDIR}profile.php?lookup={$messages[id].sender.user_id}'>{$messages[id].sender.user_name}</a>
			{else}
				{$messages[id].sender.user_name}
			{/if}
		</td>
		{/if}
		{if $folder != $locale.402}
		<td class='tbl1' style='white-space:nowrap'>
			{if $folder == $locale.404 && $messages[id].pmindex_to_id && $global_options.pm_hide_rcpts && $messages[id].recipient_count > 1}
				{$messages[id].recipient.cc_flag}
				<a href='{$smarty.const.BASEDIR}profile.php?lookup={$messages[id].recipient.user_id}'>{$messages[id].recipient.user_name}</a>
			{else}
				{section name=rid loop=$messages[id].recipients}
					{if !$smarty.section.rid.first}<br />{/if}
					{if $messages[id].recipients[rid].id >= 0}
						{$messages[id].recipients[rid].cc_flag}
						<a href='{$smarty.const.BASEDIR}profile.php?lookup={$messages[id].recipients[rid].user_id}'>{$messages[id].recipients[rid].user_name}</a>
					{else}
						{$messages[id].recipients[rid].cc_flag}
						<a href='{$smarty.const.BASEDIR}profile.php?group_id={$messages[id].recipients[rid].group_id}'>{$messages[id].recipients[rid].group_name}</a>
					{/if}
				{/section}
			{/if}
		</td>
		{/if}
		{if $folder == $locale.403}
		<td class='tbl1' style='white-space:nowrap'>
			{section name=rc loop=$messages[id].readstatus}
				{if $messages[id].readstatus[rc].user_id == 0}
					{$locale.544}
				{elseif $messages[id].readstatus[rc].datestamp == 0}
					{$locale.543}
				{else}
					{$messages[id].readstatus[rc].datestamp|date_format:"forumdate"}
				{/if}
					<br />
			{/section}
		</td>
		{/if}
		<td class='tbl1' width='20'>
			<input type='checkbox' name='check_mark[]' value='{$messages[id].pmindex_id}' />
		</td>
	</tr>
	{/if}
	{elseif $user_options.pmconfig_view == "1"}
		{include file="main.pm.renderpm.tpl"}
	{/if}
	{if $smarty.section.id.last}
	</table>
	<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		<tr>
			<td class='tbl1' align='right'>
				<input type='button' class='button' name='{$locale.410|replace:" ":"_"}' value='{$locale.410}' onclick="javascript:setChecked('pm_form','check_mark[]',1);return false;" />
				<input type='button' class='button' name='{$locale.411|replace:" ":"_"}' value='{$locale.411}' onclick="javascript:setChecked('pm_form','check_mark[]',0);return false;" />
				{$locale.409}
				{if $folder == $locale.402}
					<input type='submit' name='multi_read' value='{$locale.414}' class='button' />
					<input type='submit' name='multi_unread' value='{$locale.415}' class='button' />
				{/if}
				{if $folder != $locale.404 && ($global_options.pm_savebox > $totals.archive || $global_options.pm_savebox_group)}
					<input type='submit' name='multi_archive' value='{$locale.448}' class='button' />
				{/if}
				{if $folder == $locale.404}
					{if ($messages[id].pmindex_user_id == $messages[id].pmindex_to_id && ($global_options.pm_inbox > $totals.inbox || $global_options.pm_inbox_group)) || ($messages[id].pmindex_user_id != $messages[id].pmindex_to_id && ($global_options.pm_sentbox > $totals.outbox || $global_options.pm_sentbox_group))}
						<input type='submit' name='multi_restore' value='{$locale.412}' class='button' />
					{/if}
				{/if}
				<input type='submit' name='multi_delete' value='{$locale.416}' class='button' />
				<br /><br />
			</td>
		</tr>
	</table>
	{/if}
{sectionelse}
	<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
		<tr>
			<td width='140' align='left' class='tbl1'>
				{buttonlink name=$locale.401 link=$smarty.const.FUSION_SELF|cat:"?action=post&amp;msg_id=0"}
			</td>
			<td class='tbl1' align='right'>
			</td>
		</tr>
	<tr>
		<td align='center' colspan='2' class='tbl1'>
			<br>
			<b>{$folder|string_format:$locale.461}</b>
			<br><br>
		</td>
	</tr>
</table>
{/section}
</form>
{include file="_closetable.tpl"}
{if $rows > $smarty.const.ITEMS_PER_PAGE}
	{makepagenav start=$rowstart count=$smarty.const.ITEMS_PER_PAGE total=$rows range=$settings.navbar_range link=$pagenav_url}
{/if}
<script type='text/javascript'>
{literal}
function setChecked(frmName,chkName,val) {
	dml=document.forms[frmName];
	len=dml.elements.length;
	for(i=0;i < len;i++) {
		if(dml.elements[i].name == chkName) {
			dml.elements[i].checked = val;
		}
	}
}
// Dean Edwards/Matthias Miller/John Resig
function init() {
	// quit if this function has already been called
	if (arguments.callee.done) return;

	// flag this function so we don't do the same thing twice
	arguments.callee.done = true;

	// kill the timer
	if (_timer) clearInterval(_timer);

	// needed inside the loop
	var parent_left = 0;
	var parent_width = 0;
	var obj = 1;
	var i = 1;

	// loop through all blocks found, and set the width correctly
	while (document.getElementById("codeblock"+i+"a") != null && document.getElementById("codeblock"+i+"b") != null) {
		// get the info about the objects parent
		obj = document.getElementById("codeblock"+i+"a").parentNode;
		// fall back gracefully if the parentNode can not be found
		if (obj == null) obj = document.getElementById("codeblock"+i+"a").offsetParent;
		parent_left = findPosX(obj);
		parent_width = obj.offsetWidth;
//		alert(parent_left + " : " + parent_width);
		// calculate the new width of the block, leave plenty of space to handle browser subtleties 
		block_width = parent_left + parent_width - findPosX(document.getElementById("codeblock"+i+"a")) - 30;
		// adjust the width of the code blocks
		document.getElementById("codeblock"+i+"a").style.width = block_width + "px";
		document.getElementById("codeblock"+i+"b").style.width = block_width - 10 + "px";
		i++;
	}
};

/* for Mozilla/Opera9 */
if (document.addEventListener) {
	document.addEventListener("DOMContentLoaded", init, false);
}

/* for Internet Explorer */
/*@cc_on @*/
/*@if (@_win32)
	document.write("<script id=__ie_onload defer src=javascript:void(0)><\/script>");
	var script = document.getElementById("__ie_onload");
	script.onreadystatechange = function() {
		if (this.readyState == "complete") {
			init(); // call the onload handler
		}
	};
/*@end @*/

/* for Safari and Konqueror */
if (/KHTML|WebKit/i.test(navigator.userAgent)) { // sniff
	var _timer = setInterval(function() {
		if (/loaded|complete/.test(document.readyState)) {
			init(); // call the onload handler
		}
	}, 10);
}

/* other alternatives */
if (window.attachEvent) {
	window.attachEvent('onload', init);
} else if (window.addEventListener) {
	window.addEventListener('load', init, false);
}

/* if all else fails try this */
window.onload = init;
{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
