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
{* Template for the main module 'setuser'                                  *}
{*                                                                         *}
{***************************************************************************}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$settings.locale_code|truncate:2:""}" lang="{$settings.locale_code|truncate:2:""}" dir="{$smarty.const.LOCALEDIR}">
	<head>
		<title>{$settings.sitename}{if defined('PAGETITLE')} - {$smarty.const.PAGETITLE}{/if}</title>
		<meta http-equiv='Content-Type' content='text/html; charset={$settings.charset}' />
		<meta http-equiv='Content-Language' content='{$settings.locale_code|truncate:2:""}' />
		<meta http-equiv='refresh' content='{$refresh}; url={$url}'>
		<link href="{$smarty.const.THEME}exitecms__0001.css" rel="stylesheet" type="text/css" />
	</head>
	<body class='body'>
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
				{$message.line1|default:""}<br />
				{$message.line2|default:""}<br />
				{$message.line3|default:""}<br />
				{$message.line4|default:""}<br />
				{if $refresh < 99999}
					<br />
					{$locale.183}
					<br />
					{if $refresh > 1}[ <a href='{$url}'>{$locale.184}</a> ]{/if}
					<br /><br />
				{/if}
				</center>
			</div>
		</div>
	</body>

</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
