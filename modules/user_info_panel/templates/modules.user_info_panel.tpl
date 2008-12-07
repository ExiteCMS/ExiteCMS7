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
	{if false}
		<hr />
		<div id='countdown' style='text-align:center'>
			Login session ends on<br />{$login_expiry|date_format:"subheaderdate"}
		</div>
	{/if}
	<div id='new_pm_panel' style='text-align:center;display:{if $new_pm_msg}block{else}none{/if};'>
		<hr />
		<div style='text-align:center'>
			<b><a href='{$smarty.const.BASEDIR}pm.php?action=show_new' class='side'><span id='new_pm_panel_value'>{if $new_pm_msg == 1}{$new_pm_msg|string_format:$locale.085}{else}{$new_pm_msg|string_format:$locale.086}{/if}</span></a></b>
		</div>
	</div>
	<div id='new_posts_panel' style='text-align:center;display:{if $new_post_msg}block{else}none{/if};'>
		<hr />
		<div style='text-align:center'>
			{if $new_posts_panel}
				<a href='{$smarty.const.MODULES}forum_threads_list_panel/new_posts.php' class='side'><b><span id='new_posts_panel_value'>{if $new_post_msg == 1}{$new_post_msg|string_format:$locale.088}{else}{$new_post_msg|string_format:$locale.089}{/if}</span></b></a>
				<hr />
				{buttonlink name=$locale.091 link=$smarty.const.MODULES|cat:"forum_threads_list_panel/new_posts.php?markasread="|cat:$user_id}
			{else}
				<b>{if $new_post_msg == 1}{$new_post_msg|string_format:$locale.088}{else}{$new_post_msg|string_format:$locale.089}{/if}</b>
			{/if}
		</div>
	</div>
	{include file="_closeside_x.tpl"}
{elseif $show_login}
	{include file="_openside.tpl" name=$_name title=$locale.060 state=$_state style=$_style}
	<div style='text-align:center'>
		{$loginerror|default:""}
	</div>
	<form name='loginform1' method='post' action='{$smarty.const.BASEDIR}setuser.php?login=yes'>
		{foreach from=$auth_templates item=method key=i}
			{include file=$i}
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
	{include file="_closeside.tpl"}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
