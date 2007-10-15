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
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<title>{$settings.sitename}</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<meta http-equiv='refresh' content='60; url={$smarty.const.BASEDIR}'>
{include file='_stylesheets.tpl'}
</head>
<body class='tbl2'>

<table width='100%' height='100%'>
	<tr>
		<td>
			<table align='center' cellpadding='0' cellspacing='1' width='800' class='tbl-border'>
				<tr>
					<td class='tbl1' style='font-size:11px'>
						<center>
							<br />
							<img src='{$smarty.const.THEME}images/{$settings.sitebanner}' alt='{$settings.sitename}'>
							<br /><br />
							<b>{$message}</b>
							<br /><br />
							<form name='loginform' method='post' action='{$smarty.const.BASEDIR}'>
								<input type='submit' name='back' value='{$locale.151}' class='button' />
							</form>
							<br /><br />
						</center>
					</td>
				</tr>
			{if !$smarty.const.iMEMBER}
				<tr>
					<td class='tbl1' style='font-size:11px'>
						<div style='text-align:center'>
							{$loginerror|default:""}
							<form name='loginform' method='post' action='{$smarty.const.FUSION_SELF}'>
								{$locale.061}: <input type='text' name='user_name' class='textbox' style='width:100px' />&nbsp;&nbsp;
								{$locale.062}: <input type='password' name='user_pass' class='textbox' style='width:100px' />
								<br /><br />
								<input type='submit' name='login' value='{$locale.064}' class='button' /><br />
								<input type='hidden' name='javascript_check' value='n' />
							</form>
						</div>
					</td>
				</tr>
			{/if}
			</table>
		</td>
	</tr>
</table>

</body>
</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}