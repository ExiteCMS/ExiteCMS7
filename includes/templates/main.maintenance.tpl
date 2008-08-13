{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.maintenance.tpl                                 *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-07 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'maintenance'                              *}
{*                                                                         *}
{***************************************************************************}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title>{$settings.sitename}</title>
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
		<meta http-equiv='refresh' content='60; url={$smarty.const.BASEDIR}'>
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
				{$message}
				<br /><br />
				{if !$smarty.const.iMEMBER}
					<div style='text-align:center'>
						{$loginerror|default:"<br />"}
						<form name='loginform' method='post' action='{$smarty.const.FUSION_SELF}'>
							{$locale.061}: <input type='text' name='user_name' class='textbox' style='width:100px' />&nbsp;&nbsp;
							{$locale.062}: <input type='password' name='user_pass' class='textbox' style='width:100px' />&nbsp;&nbsp;
							<input type='submit' name='login' value='{$locale.064}' class='button' /><br />
							<input type='hidden' name='javascript_check' value='n' />
						</form>
					</div>
				{else}
					<div style='text-align:center'>
						<br />
						<form name='loginform' method='post' action='{$smarty.const.BASEDIR}'>
							<input type='submit' name='back' value='{$locale.151}' class='button' />
						</form>
					</div>
				{/if}
				</center>
			</div>
		</div>
	</body>

</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
