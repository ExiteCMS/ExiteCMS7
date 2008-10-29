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
{* Template for the admin content module '404pages'                        *}
{*                                                                         *}
{***************************************************************************}
{if $settings.tinymce_enabled == 1}<script type='text/javascript'>advanced();</script>{/if}
{include file="_opentable.tpl" name=$_name title=$locale.403 state=$_state style=$_style}
<form name='selectform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<center>
		<select name='lc' class='textbox' style='width:250px;'>
		{section name=id loop=$pages}
			<option value='{$pages[id].locales_code}'{if $pages[id].selected} selected='selected'{/if}>{$pages[id].locale_name}</option>
		{/section}
		</select>
		<input type='submit' name='edit' value='{$locale.404}' class='button' />
	</center>
</form>
{include file="_closetable.tpl"}
{if $preview != ""}
	{include file="_opentable.tpl" name=$_name title=$locale.436 state=$_state style=$_style}
	{$preview}
	{include file="_closetable.tpl"}
{/if}
{if $lc != ""}
	{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
	<form name='inputform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
		<table align='center' cellpadding='0' cellspacing='0' width='90%'>
			<tr>
				<td class='tbl'>
					{$locale.405}
					<br />
					<textarea name='page_content' cols='95' rows='20' class='{if $settings.tinymce_enabled != 1}textbox{/if}' style='width:100%; height:{math equation='x/2' format="%u" x=$smarty.const.BROWSER_HEIGHT}px'>{$page_content}</textarea>
				</td>
			</tr>
			{if $settings.tinymce_enabled != 1}
			<tr>
				<td align='center' class='tbl'>
					<input type='button' value='<?php?>' class='button' style='width:60px;' onclick="addText('page_content', '<?php\n', '\n?>');">
					<input type='button' value='<p>' class='button' style='width:35px;' onclick="insertText('page_content', '<p>');">
					<input type='button' value='<br>' class='button' style='width:40px;' onclick="insertText('page_content', '<br>');">
					<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick="addText('body', '<b>', '</b>');">
					<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick="addText('body', '<i>', '</i>');">
					<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick="addText('body', '<u>', '</u>');">
					<input type='button' value='link' class='button' style='width:35px;' onClick="addText('body', '<a href=\'', '\' target=\'_blank\'>Link</a>');">
					<input type='button' value='img' class='button' style='width:35px;' onClick="addText('body', '<img src=\'{$img_src}\' style=\'margin:5px;\' align=\'left\' />');">
					<input type='button' value='center' class='button' style='width:45px;' onClick="addText('body', '<center>', '</center>');">
					<input type='button' value='small' class='button' style='width:40px;' onClick="addText('body', '<span class=\'small\'>', '</span>');">
					<input type='button' value='small2' class='button' style='width:45px;' onClick="addText('body', '<span class=\'small2\'>', '</span>');">
					<input type='button' value='alt' class='button' style='width:25px;' onClick="addText('body', '<span class=\'alt\'>', '</span>');">
					<br />
				</td>
			</tr>
			{/if}
			<tr>
				<td align='center' class='tbl'>
					<br />
					<input type='hidden' name='lc' value='{$lc}' />
					<input type='submit' name='preview' value='{$locale.406}' class='button' />&nbsp;&nbsp;
					<input type='submit' name='save' value='{$locale.407}' class='button' />
				</td>
			</tr>
		</table>
	</form>
	{include file="_closetable.tpl"}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
