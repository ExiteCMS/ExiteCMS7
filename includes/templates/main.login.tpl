{****************************************************************************}
{*                                                                          *}
{* ExiteCMS template: login_panel.tpl                                       *}
{*                                                                          *}
{****************************************************************************}
{*                                                                          *}
{* Author: WanWizard <wanwizard@gmail.com>                                  *}
{*                                                                          *}
{* Revision History:                                                        *}
{* 2007-07-09 - WW - Initial version                                        *}
{* 2008-10-17 - WW - Rewritten to support OpenId etc.                       *}
{*                                                                          *}
{****************************************************************************}
{*                                                                          *}
{* This template generates the PLi-Fusion main panel: login                 *}
{*                                                                          *}
{****************************************************************************}
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
			<div class='splashscreen-v' style='height:250px;vertical-align:center;'>
				<table align='center' cellpadding='0' cellspacing='1' width='500'>
					<tr>
						<td class='tbl1' align='center'>
							<form name='loginform1' method='post' action='{$smarty.const.BASEDIR}setuser.php?login=yes'>
								<img src='{$smarty.const.THEME}images/{$settings.sitebanner}' alt='{$settings.sitename}' style='width:480px;margin:5px;'/>
								{foreach from=$auth_methods item=method key=i}
									{if $method_count > 1}
										{if $method == "ldap"}
											<div class='side-label'>
												{$locale.069} {$method|upper} {$locale.061}:
											</div>
											<div id='box_login{$i}' name='login{$i}' style='display:{if $auth_state.$i}block{else}none{/if};'>
										{elseif $method == "ad"}
											<div class='side-label'>
												{$locale.069} {$method|upper} {$locale.061}:
											</div>
											<div id='box_login{$i}' name='login{$i}' style='display:{if $auth_state.$i}block{else}none{/if};'>
										{elseif $method == "local"}
											<div class='side-label'>
												{$locale.069} {$locale.061}:
											</div>
											<div id='box_login{$i}' name='login{$i}' style='display:{if $auth_state.$i}block{else}none{/if};'>
										{elseif $method == "openid"}
											<div class='side-label'>
												{$locale.069} {$locale.067}:
											</div>
											<div id='box_login{$i}' name='login{$i}' style='display:{if $auth_state.$i}block{else}none{/if};'>
										{/if}
									{/if}
									<div style='padding:5px;'>
									{if $method == "ldap"}
										{$locale.061}: <input type='text' name='ldap_name' class='textbox' style='width:145px' />
										&nbsp;&nbsp;&nbsp;
										{$locale.062}: <input type='password' name='ldap_pass' class='textbox' style='width:145px' /><br />
									{elseif $method == "ad"}
										{$locale.061}: <input type='text' name='ad_name' class='textbox' style='width:145px' />
										&nbsp;&nbsp;&nbsp;
										{$locale.062}: <input type='password' name='ad_pass' class='textbox' style='width:145px' /><br />
									{elseif $method == "local"}
										{$locale.061}: <input type='text' name='user_name' class='textbox' style='width:145px' />
										&nbsp;&nbsp;&nbsp;
										{$locale.062}: <input type='password' name='user_pass' class='textbox' style='width:145px' /><br />
									{elseif $method == "openid"}
										<input type='text' name='user_openid_url' class='textbox' style='width:128px;background: url({$smarty.const.IMAGES}openid_small_logo.gif) no-repeat; padding-left: 18px;' />
										&nbsp;&nbsp;&nbsp;
										<span class='small' style='font-size:90%;'>  <a href="http://{$settings.locale_code}.wikipedia.org/wiki/OpenID"  target="_blank">{$locale.068}</a></span><br />
									{/if}
									</div>
									{if $method_count > 1}
										</div>
									{/if}			
								{/foreach}
									<hr />
									<div style='text-align:center'>
										<input type='checkbox' name='remember_me' value='yes' title='{$locale.063}' style='vertical-align:middle;'{if $remember_me|default:"no" == "yes"} checked="checked"{/if}/>
										<input type='submit' name='login' value='{$locale.064}' class='button' /><br />
										<input type='hidden' name='javascript_check' value='n' />
									</div>
								</form>
							{literal}
							<script type='text/javascript'>
							/* <![CDATA[ */
								if (document.loginform1.javascript_check.value == 'n')
								{
									document.loginform1.javascript_check.value = 'y';
								}
								/* ]]> */
							</script>
								{/literal}
							{if $show_reglink || $show_passlink}
								<hr />
							{/if}
							{if $show_reglink}{$settings.siteurl|string_format:$locale.065}<br /><br />{/if}
							{if $show_passlink}{$settings.siteurl|string_format:$locale.066}{/if}
						</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
