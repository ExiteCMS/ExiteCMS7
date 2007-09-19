{****************************************************************************}
{*                                                                          *}
{* PLi-Fusion CMS template: login_panel.tpl                                 *}
{*                                                                          *}
{****************************************************************************}
{*                                                                          *}
{* Author: WanWizard <wanwizard@gmail.com>                                  *}
{*                                                                          *}
{* Revision History:                                                        *}
{* 2007-07-09 - WW - Initial version                                        *}
{*                                                                          *}
{****************************************************************************}
{*                                                                          *}
{* This template generates the PLi-Fusion main panel: login                 *}
{*                                                                          *}
{****************************************************************************}
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<title>{$settings.sitename}</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<link rel='stylesheet' href='{$smarty.const.THEME}styles.css' type='text/css' />
</head>
<body class='tbl2'>

<table width='100%' height='100%'>
<tr>
<td>

<table align='center' cellpadding='0' cellspacing='1' width='500' class='tbl-border'>
<tr>
<td class='tbl1' style='font-size:11px'>
<center>
<br />
<img src='{$smarty.const.THEME}images/{$settings.sitebanner}' alt='{$settings.sitename}'><br /><br />
<form name='loginform' method='post' action='{$smarty.const.FUSION_SELF}'>
	{$locale.061}<br /><input type='text' name='user_name' class='textbox' style='width:100px' /><br />
	{$locale.062}<br /><input type='password' name='user_pass' class='textbox' style='width:100px' /><br />
	<br />
	<input type='checkbox' name='remember_me' value='y' title='{$locale.063}' style='vertical-align:middle;'{if $remember_me|default:"no" == "yes"} checked{/if}/>
	<input type='submit' name='login' value='{$locale.064}' class='button' />
	<input type='hidden' name='javascript_check' value='n' />
	<br /><br />
</form>
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