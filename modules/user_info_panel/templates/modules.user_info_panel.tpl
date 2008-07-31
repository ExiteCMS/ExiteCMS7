{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: user_info_panel.tpl                            *}
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
{* This template generates the PLi-Fusion infusion panel: user_info_panel  *}
{*                                                                         *}
{***************************************************************************}
{if $smarty.const.iMEMBER|default:false}
	{include file="_openside_x.tpl" name=$_name title=$user_name state=$_state style=$_style}
	<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a class='side' href='{$smarty.const.BASEDIR}edit_profile.php'>{$locale.080}</a></div>
	<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a class='side' href='{$smarty.const.BASEDIR}pm.php'>{$locale.081}</a></div>
	<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a class='side' href='{$smarty.const.BASEDIR}members.php'>{$locale.082}</a></div>
	<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a class='side' href='{$smarty.const.BASEDIR}setuser.php?logout=yes'>{$locale.084}</a></div>
	{if $smarty.const.iADMIN|default:false && ($smarty.const.iUSER_RIGHTS != "" || $smarty.const.iUSER_RIGHTS != "C")}
		{if $adminpage1|default:0 != 0 || $adminpage2|default:0 != 0 || $adminpage3|default:0 != 0 || $adminpage4|default:0 != 0}
			<div class='side-label'>{$locale.083}</div>
		{/if}
		{if $adminpage1|default:0 != 0}<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.ADMIN}index.php{$aidlink}&amp;pagenum=1' class='side'>{$locale.ac01}</a></div>{/if}
		{if $adminpage2|default:0 != 0}<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.ADMIN}index.php{$aidlink}&amp;pagenum=2' class='side'>{$locale.ac02}</a></div>{/if}
		{if $adminpage3|default:0 != 0}<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.ADMIN}index.php{$aidlink}&amp;pagenum=3' class='side'>{$locale.ac03}</a></div>{/if}
		{if $adminpage4|default:0 != 0}<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.ADMIN}index.php{$aidlink}&amp;pagenum=4' class='side'>{$locale.ac04}</a></div>{/if}
		{if $adminpage5|default:0 != 0}<div class='side-label-link'><img src='{$smarty.const.THEME}images/bullet.gif' alt='' /> <a href='{$smarty.const.ADMIN}tools.php{$aidlink}' class='side'>{$locale.ac05}</a></div>{/if}
	{/if}
	{if $login_expiry}
		<hr />
		<div id='countdown' style='text-align:center'>
			Login session ends on<br />{$login_expiry|date_format:"subheaderdate"}
		</div>
	{/if}
	{if $new_pm_msg != 0}
		<hr />
		<div style='text-align:center'>
			<b><a href='{$smarty.const.BASEDIR}pm.php?action=show_new' class='side'>{if $new_pm_msg == 1}{$new_pm_msg|string_format:$locale.085}{else}{$new_pm_msg|string_format:$locale.086}{/if}</a></b>
		</div>
	{/if}
	{if $new_post_msg != 0}
		<hr />
		<div style='text-align:center'>
			{if $new_posts_panel}
				<a href='{$smarty.const.MODULES}forum_threads_list_panel/new_posts.php' class='side'><b>{if $new_post_msg == 1}{$new_post_msg|string_format:$locale.088}{else}{$new_post_msg|string_format:$locale.089}{/if}</b></a>
				<hr />
				{buttonlink name=$locale.091 link=$smarty.const.MODULES|cat:"forum_threads_list_panel/new_posts.php?markasread="|cat:$user_id}
			{else}
				<b>{if $new_post_msg == 1}{$new_post_msg|string_format:$locale.088}{else}{$new_post_msg|string_format:$locale.089}{/if}</b>
			{/if}
		</div>
	{/if}
{else}
	{include file="_openside.tpl" name=$_name title=$locale.060 state=$_state style=$_style}
	<div style='text-align:center'>
		{$loginerror|default:""}
	</div>
	<form name='loginform' method='post' action='{$smarty.const.FUSION_SELF}'>
		{foreach from=$auth_methods item=method key=i}
			{if $method_count > 1}
				{if $method == "ldap"}
					<div class='side-label'>
						<div style='display:inline; position:relative; float:right;margin-top:2px;'>
							<img src='{$smarty.const.THEME}images/panel_{if $auth_state.$i}off{else}on{/if}.gif' alt='' name='b_login{$i}' onclick="javascript:flipBox('login{$i}')" />
						</div>
						{$locale.069} {$method|upper} {$locale.061}:
					</div>
					<div id='box_login{$i}' name='login{$i}' style='display:{if $auth_state.$i}block{else}none{/if};'>
				{elseif $method == "ad"}
					<div class='side-label'>
						<div style='display:inline; position:relative; float:right;margin-top:2px;'>
							<img src='{$smarty.const.THEME}images/panel_{if $auth_state.$i}off{else}on{/if}.gif' alt='' name='b_login{$i}' onclick="javascript:flipBox('login{$i}')" />
						</div>
						{$locale.069} {$method|upper} {$locale.061}:
					</div>
					<div id='box_login{$i}' name='login{$i}' style='display:{if $auth_state.$i}block{else}none{/if};'>
				{elseif $method == "local"}
					<div class='side-label'>
						<div style='display:inline; position:relative; float:right;margin-top:2px;'>
							<img src='{$smarty.const.THEME}images/panel_{if $auth_state.$i}off{else}on{/if}.gif' alt='' name='b_login{$i}' onclick="javascript:flipBox('login{$i}')" />
						</div>
						{$locale.069} {$locale.061}:
					</div>
					<div id='box_login{$i}' name='login{$i}' style='display:{if $auth_state.$i}block{else}none{/if};'>
				{elseif $method == "openid"}
					<div class='side-label'>
						<div style='display:inline; position:relative; float:right;margin-top:2px;'>
							<img src='{$smarty.const.THEME}images/panel_{if $auth_state.$i}off{else}on{/if}.gif' alt='' name='b_login{$i}' onclick="javascript:flipBox('login{$i}')" />
						</div>
						{$locale.069} {$locale.067}:
					</div>
					<div id='box_login{$i}' name='login{$i}' style='display:{if $auth_state.$i}block{else}none{/if};'>
				{/if}
			{/if}
			<div style='padding-left:2px;'>
			{if $method == "ldap"}
				{$locale.061}:<br /><input type='text' name='ldap_name' class='textbox' style='width:145px' /><br />
				{$locale.062}:<br /><input type='password' name='ldap_pass' class='textbox' style='width:145px' /><br />
			{elseif $method == "ad"}
				{$locale.061}:<br /><input type='text' name='ad_name' class='textbox' style='width:145px' /><br />
				{$locale.062}:<br /><input type='password' name='ad_pass' class='textbox' style='width:145px' /><br />
			{elseif $method == "local"}
				{$locale.061}:<br /><input type='text' name='user_name' class='textbox' style='width:145px' /><br />
				{$locale.062}:<br /><input type='password' name='user_pass' class='textbox' style='width:145px' /><br />
			{elseif $method == "openid"}
				<input type='text' name='user_openid_url' class='textbox' style='width:128px;background: url({$smarty.const.IMAGES}openid_small_logo.png) no-repeat; padding-left: 18px;' /><br />
				<span class='small' style='font-size:90%;'>  <a href="http://{$settings.locale_code}.wikipedia.org/wiki/OpenID"  target="_blank">{$locale.068}</a></span><br />
			{/if}
			</div>
			{if $method_count > 1}
				</div>
			{/if}			
		{/foreach}
			<hr />
			<div style='text-align:center'>
				<input type='submit' name='login' value='{$locale.064}' class='button' />
				<input type='checkbox' name='remember_me' value='yes' title='{$locale.063}' style='vertical-align:middle;'{if $remember_me|default:"no" == "yes"} checked="checked"{/if}/><br />
				<input type='hidden' name='javascript_check' value='n' />
			</div>
		</form>
	{if $show_reglink || $show_passlink}
		<hr />
	{/if}
	{if $show_reglink}{$settings.siteurl|string_format:$locale.065}<br /><br />{/if}
	{if $show_passlink}{$settings.siteurl|string_format:$locale.066}{/if}
{/if}
{if $smarty.const.iMEMBER|default:false}
	{include file="_closeside_x.tpl"}
{else}
	{include file="_closeside.tpl"}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
