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
		<link rel='stylesheet' href='{$smarty.const.THEME}styles.css' type='text/css' />
	</head>
	<body class='tbl2' style='height:100%'>
		<div style='position:absolute; top:35%;'>
			<table align='center' cellpadding='0' cellspacing='1' width='800' class='tbl-border'>
				<tr>
					<td class='tbl1' style='font-size:11px'>
						<center>
						<br /><br />
						<img src='{$smarty.const.THEME}images/{$settings.sitebanner}' alt='{$settings.sitename}' /><br /><br />
						{$message}
						{$error}
						{$locale.183}
						{if $error != ""}<br /><br />[ <a href='/index.php'>{$locale.184}</a> ]{/if}
						<br /><br />
						</center>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}