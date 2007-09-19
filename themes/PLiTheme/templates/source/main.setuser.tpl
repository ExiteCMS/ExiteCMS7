{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: main.setuser.tpl                               *}
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
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
	<head>
		<title>{$settings.sitename}</title>
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
		<meta http-equiv='refresh' content='{$refresh}; url=index.php'>
		<link rel='stylesheet' href='{$smarty.const.THEME}styles.css' type='text/css' />
	</head>
	<body class='tbl2'>
		<table width='100%' height='100%'>
			<tr>
				<td>
					<table align='center' cellpadding='0' cellspacing='1' width='800' class='tbl-border'>
						<tr>
							<td class='tbl1' style='font-size:11px'>
								<center>
								<br /><br />
								<img src='{$smarty.const.THEME}images/{$settings.sitebanner}' alt='{$settings.sitename}'><br /><br />
								{$message}
								{$error}
								{$locale.183}
								{if $error != ""}<br /><br />[ <a href='/index.php'>{$locale.184}</a> ]{/if}
								<br /><br />
								</center>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}