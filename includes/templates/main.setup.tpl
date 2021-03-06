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
{* Template for the main module 'setup'                                    *}
{*                                                                         *}
{***************************************************************************}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>{$locale.title}</title>
<meta http-equiv='Content-Type' content='text/html; charset={$settings.charset}'>
{literal}<style type="text/css">
<!--
a 			{ color:#003D71; text-decoration:none; }
a:hover 	{ color:#027AC6; text-decoration:underline; }
.button 	{ font-family:Tahoma,Arial,Verdana,Sans-Serif; font-size:11px; color:#000000; background-color:#E5E5E8; border:#7F98A7 1px solid; margin-top:2px; }
.textbox 	{ font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:11px; color:#000; background-color:#FFFFFF; border:1px #7F98A7 solid; }
td 			{ font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:11px; }
.tbl-border { background-color:#D1D8DD; }
.tbl 		{ font-size:11px; color:#000; background-color:#C1C1C1; }
.tbl1 		{ font-size:11px; color:#000; background-color:#F1F1F1; padding:4px; }
.tbl2 		{ font-size:11px; color:#000; background-color:#E6E6E6; padding:4px; }
.tbl3 		{ font-size:11px; color:#000; background-color:#E65656; padding:4px; }
.tbl4 		{ font-size:11px; color:#000; background-color:#56E656; padding:4px; }
.small		{ font-size:10px; color:#666; }
-->
</style>{/literal}
</head>
<body class='tbl'>
<table width='100%' height='100%'>
	<tr>
		<td>
		<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
			<tr>
				<td align='center' class='tbl1'>
					<br />
					<b>{$locale.410}</b>
					<br /><br />
				</td>
			</tr>
		</table>
		<br>
		<table align='center' cellpadding='0' cellspacing='0' width='600'>
			<tr>
				<td align='center'>
					<img width='598' src='images/cms-logo-big.png'>
				</td>
			</tr>
		</table>
		<br>
		{if $message|default:"" != ""}
		<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
			<tr>
				<td align='center' class='tbl4'>
					<br />{$message}<br /><br />
				</td>
			</tr>
		</table>
		<br />
		{/if}
		{if $error|default:"" != ""}
		<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
			<tr>
				<td align='center' class='tbl3'>
					<br />{$error}<br /><br />
				</td>
			</tr>
		</table>
		<br />
		{/if}
		{if $step == "0" || $fail}
			<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
				<tr>
					<td align='center' class='tbl2' colspan='4'>
						<b>{$locale.400}</b>
					</td>
				</tr>
			{foreach from=$locale_files item=loc name=x}
				{cycle values="1,2,3,4" print=no assign=column}
				{if $column == 1}<tr>{/if}
					<td align='center' width='25%' class='tbl1'>
						{if $localeset == $loc}
							<b>{$loc}</b>
						{else}
							<a href='{$smarty.const.FUSION_SELF}?localeset={$loc}'>{$loc}</a>
						{/if}
					</td>
				{if $smarty.foreach.x.last && $column < 4}
					{section name=y start=$column loop=4}
						<td align='center' width='25%' class='tbl1'>
						-
						</td>
					{/section}
				{/if}
				{if $column == 4}</tr>{/if}
			{/foreach}
			</table>
			<br />
			{if $write_check}
				<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
					<tr>
						<td align='center' class='tbl4'>
							<br />
							<b>{$locale.411}</b>
							<br /><br />
							<b>{$locale.414}</b>
							<br /><br />
						</td>
					</tr>
				</table>
				<br>
				<form name='setup' method='post' action='{$smarty.const.FUSION_SELF}?localeset={$localeset}'>
					<table align='center' width='600' cellpadding='0' cellspacing='1' class='tbl-border'>
						<tr>
							<td align='center' colspan='2' class='tbl2'>
								<b>{$locale.420}</b>
							</td>
						</tr>
						<tr>
							<td align='right' class='tbl1'>
								{$locale.421}
							</td>
							<td class='tbl1'>
								<input type='text' value='{$db_host|default:"localhost"}' name='db_host' class='textbox' />
							</td>
						</tr>
						<tr>
							<td align='right' class='tbl1'>
								{$locale.422}
							</td>
							<td class='tbl1'>
								<input type='text' value='{$db_user}' name='db_user' class='textbox' />
							</td>
						</tr>
						<tr>
							<td align='right' class='tbl1'>
								{$locale.423}
							</td>
							<td class='tbl1'>
								<input type='password' value='{$db_pass}' name='db_pass' class='textbox'>
							</td>
						</tr>
						<tr>
							<td align='right' class='tbl1'>
								{$locale.424}
							</td>
							<td class='tbl1'>
								<input type='text' value='{$db_name}' name='db_name' class='textbox' />
							</td>
						</tr>
						<tr>
							<td align='right' class='tbl1'>
								{$locale.425}
							</td>
							<td class='tbl1'>
								<input type='text' value='{$db_prefix|default:"cms_"}' name='db_prefix' class='textbox' /> <span class='small'>{$locale.436}</span>
							</td>
						</tr>
						<tr>
							<td align='center' colspan='2' class='tbl1'>
								<input type='hidden' name='step' value='1' />
								<input type='submit' name='next' value='{$locale.426}' class='button' />
							</td>
						</tr>
					</table>
				</form>
			{/if}
		{/if}
		{if $step == "1" && !$fail}
			<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
				<tr>
					<td align='center' colspan='2' class='tbl4'>
						<b>{$locale.432}</b>
						<b>{$locale.433}</b>
					</td>
				</tr>
			</table>
			<br />
			<form name='setup' method='post' action='{$smarty.const.FUSION_SELF}?localeset={$localeset}'>
				<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
					<tr>
						<td align='center' colspan='2' class='tbl2'>
							<b>{$locale.440}</b>
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl1'>
							{$locale.441}
						</td>
						<td class='tbl1'>
							<input type='text' value='{$username}' name='username' maxlength='30' class='textbox' />
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl1'>
							{$locale.442}
						</td>
						<td class='tbl1'>
							<input type='password' value='{$password1}' name='password1' maxlength='20' class='textbox' />
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl1'>
							{$locale.443}
						</td>
						<td class='tbl1'>
							<input type='password' value='{$password2}' name='password2' maxlength='20' class='textbox' />
						</td>
					</tr>
					<tr>
						<td align='right' class='tbl1'>
							{$locale.444}
						</td>
						<td class='tbl1'>
							<input type='text' value='{$email}' name='email' maxlength='100' class='textbox' />
						</td>
					</tr>
					<tr>
						<td colspan='2' align='center' class='tbl1'>
							<input type='hidden' name='step' value='2' />
							<input type='submit' name='next' value='{$locale.426}' class='button'>
						</td>
					</tr>
				</table>
			</form>
			<br />
		{/if}
		</td>
	</tr>
</table>
</body>
</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
