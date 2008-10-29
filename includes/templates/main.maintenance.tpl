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
{* Template for the main module 'maintenance'                              *}
{*                                                                         *}
{***************************************************************************}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$settings.locale_code|truncate:2:""}" lang="{$settings.locale_code|truncate:2:""}" dir="{$smarty.const.LOCALEDIR}">
	<head>
		<title>{$settings.sitename}{if defined('PAGETITLE')} - {$smarty.const.PAGETITLE}{/if}</title>
		<meta http-equiv='Content-Type' content='text/html; charset={$settings.charset}' />
		<meta http-equiv='Content-Language' content='{$settings.locale_code|truncate:2:""}' />
		<meta http-equiv='refresh' content='60; url={$smarty.const.BASEDIR}'>
		<link href="{$smarty.const.THEME}exitecms__0001.css" rel="stylesheet" type="text/css" />
	</head>
	<body class='body'>
		<form name='maintform' method='post' action='{$smarty.const.FUSION_SELF}'>
		<div class='splashscreen-h'>
			<div class='splashscreen-v'>
				<center>
				<br />
				{if $settings.sitebanner|default:"" != ""}
					<img src='{$smarty.const.THEME}images/{$settings.sitebanner}' alt='{$settings.sitename}' width='400'/>
				{else}
					<br /><br />
				{/if}
				<br /><br />
				{$message}
				<br /><br />
				{if !$smarty.const.iMEMBER}
					<div style='text-align:center'>
						{$loginerror|default:"<br />"}
						{$locale.061}: <input type='text' name='user_name' class='textbox' style='width:100px' />&nbsp;&nbsp;
						{$locale.062}: <input type='password' name='user_pass' class='textbox' style='width:100px' />&nbsp;&nbsp;
						<input type='submit' name='login' value='{$locale.064}' class='button' /><br />
						<input type='hidden' name='javascript_check' value='n' />
					</div>
				{else}
					<div style='text-align:center'>
						<br />
						<input type='submit' name='back' value='{$locale.151}' class='button' />
						&nbsp;&nbsp;
						<input type='submit' name='logout' value='{$locale.084}' class='button' />
					</div>
				{/if}
				</center>
			</div>
		</div>
		</form>
	</body>

</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
