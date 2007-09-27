{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.settings_links.tpl                             *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-22 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the admin configuration module 'settings' - menu bar       *}
{* this template is included in the other settings templates               *}
{*                                                                         *}
{***************************************************************************}
<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
	<tr>
		<td class='{if $this_module == "settings_main.php"}tbl1{else}tbl2{/if}' style='padding-left:10px;padding-right:10px;'>
			<span class='small'>
				<a href='settings_main.php{$aidlink}'>{$locale.401}</a>
			</span>
		</td>
		<td class='{if $this_module == "settings_time.php"}tbl1{else}tbl2{/if}' style='padding-left:10px;padding-right:10px;'>
			<span class='small'>
				<a href='settings_time.php{$aidlink}'>{$locale.450}</a>
			</span>
		</td>
		<td class='{if $this_module == "settings_forum.php"}tbl1{else}tbl2{/if}' style='padding-left:10px;padding-right:10px;'>
			<span class='small'>
				<a href='settings_forum.php{$aidlink}'>{$locale.500}</a>
			</span>
		</td>
		<td class='{if $this_module == "settings_registration.php"}tbl1{else}tbl2{/if}' style='padding-left:10px;padding-right:10px;'>
			<span class='small'>
				<a href='settings_registration.php{$aidlink}'>{$locale.550}</a>
			</span>
		</td>
		<td class='{if $this_module == "settings_image.php"}tbl1{else}tbl2{/if}' style='padding-left:10px;padding-right:10px;'>
			<span class='small'>
				<a href='settings_image.php{$aidlink}'>{$locale.600}</a>
			</span>
		</td>
		<td class='{if $this_module == "settings_messages.php"}tbl1{else}tbl2{/if}' style='padding-left:10px;padding-right:10px;'>
			<span class='small'>
				<a href='settings_messages.php{$aidlink}'>{$locale.700}</a>
			</span>
		</td>
		<td class='{if $this_module == "settings_languages.php"}tbl1{else}tbl2{/if}' style='padding-left:10px;padding-right:10px;'>
			<span class='small'>
				<a href='settings_languages.php{$aidlink}'>{$locale.850}</a>
			</span>
		</td>
		<td class='{if $this_module == "settings_misc.php"}tbl1{else}tbl2{/if}' style='padding-left:10px;padding-right:10px;'>
			<span class='small'>
				<a href='settings_misc.php{$aidlink}'>{$locale.650}</a>
			</span>
		</td>
	</tr>
</table>
<br />
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}