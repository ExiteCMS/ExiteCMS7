{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.setuser.tpl                                     *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-02 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'setuser'                                  *}
{*                                                                         *}
{***************************************************************************}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title>{$settings.sitename}</title>
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
		<meta http-equiv='refresh' content='{$refresh}; url=index.php' />
		{include file='_stylesheets.tpl'}
	</head>

	<body class='body'>
		<div class='splashscreen-h'>
			<div class='splashscreen-v'>
				<center>
				<br /><br />
				{if $settings.sitebanner|default:"" != ""}
					<img src='{$smarty.const.THEME}images/{$settings.sitebanner}' alt='{$settings.sitename}' /><br /><br />
				{/if}
				{$message}
				{$error}
				{$locale.183}
				{if $error != ""}<br /><br />[ <a href='/index.php'>{$locale.184}</a> ]{/if}
				<br /><br />
				</center>
			</div>
		</div>
	</body>

</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}