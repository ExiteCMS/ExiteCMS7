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
			<b><a href='{$smarty.const.BASEDIR}pm.php?action=show_new' class='side'>{$new_pm_msg|string_format:$locale.085}{if $new_pm_msg == 1}{$locale.086}{else}{$locale.087}{/if}</a></b>
		</div>
	{/if}
	{if $new_post_msg != 0}
		<hr />
		<div style='text-align:center'>
			{if $new_posts_panel}
				<a href='{$smarty.const.MODULES}forum_threads_list_panel/new_posts.php' class='side'><b>{$new_post_msg|string_format:$locale.090}{if $new_post_msg == 1}{$locale.088}{else}{$locale.089}{/if}</b></a>
				<hr />
				{buttonlink name=$locale.091 link=$smarty.const.MODULES|cat:"forum_threads_list_panel/new_posts.php?markasread="|cat:$user_id}
			{else}
				<b>{$new_post_msg|string_format:$locale.090}{if $new_post_msg == 1}{$locale.088}{else}{$locale.089}{/if}</b>
			{/if}
		</div>
	{/if}
{else}
	{include file="_openside.tpl" name=$_name title=$locale.060 state=$_state style=$_style}
	<div style='text-align:center'>
		{$loginerror|default:""}
		<form name='loginform' method='post' action='{$smarty.const.FUSION_SELF}'>
			{$locale.061}<br /><input type='text' name='user_name' class='textbox' style='width:100px' /><br />
			{$locale.062}<br /><input type='password' name='user_pass' class='textbox' style='width:100px' /><br />
			<br /><input type='checkbox' name='remember_me' value='yes' title='{$locale.063}' style='vertical-align:middle;'{if $remember_me|default:"no" == "yes"} checked="checked"{/if}/>
			<input type='submit' name='login' value='{$locale.064}' class='button' /><br />
			<input type='hidden' name='javascript_check' value='n' />
		</form>
	</div>
	{literal}
<script type='text/javascript'>
/* <![CDATA[ */
	if (document.loginform.javascript_check.value == 'n')
	{
		document.loginform.javascript_check.value = 'y';
	}
	/* ]]> */
</script>
	{/literal}
	<br />
	{if $settings.enable_registration}{$settings.siteurl|string_format:$locale.065}<br /><br />{/if}
	{$settings.siteurl|string_format:$locale.066}
{/if}
{if $smarty.const.iMEMBER|default:false}
	{include file="_closeside_x.tpl"}
{else}
	{include file="_closeside.tpl"}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
