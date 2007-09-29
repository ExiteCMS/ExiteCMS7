{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: admin.settings_misc.tpl                              *}
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
{* Template for the admin configuration module 'settings_misc'             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.662}
			</td>
			<td width='50%' class='tbl'>
				<select name='tinymce_enabled' class='textbox'>
					<option value='1'{if $settings2.tinymce_enabled == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.tinymce_enabled == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.664}
				<br />
				<span class='small2'>{$locale.665}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='smtp_host' value='{$settings2.smtp_host}' maxlength='200' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.666}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='smtp_username' value='{$settings2.smtp_username}' maxlength='100' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				<span class='small2'>{$locale.663}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='password' name='smtp_password' value='{$settings2.smtp_password}' maxlength='100' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.659}
			</td>
			<td width='50%' class='tbl'>
				<select name='bad_words_enabled' class='textbox'>
					<option value='1'{if $settings2.bad_words_enabled == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.bad_words_enabled == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign='top' width='50%' class='tbl'>
				{$locale.651}
				<br />
				<span class='small2'>{$locale.652}<br />{$locale.653}</span>
			</td>
			<td width='50%' class='tbl'>
				<textarea name='bad_words' rows='5' class='textbox' style='width:250px;'>{$settings2.bad_words}</textarea>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.654}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='bad_word_replace' value='{$settings2.bad_word_replace}' maxlength='128' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.655}
			</td>
			<td width='50%' class='tbl'>
				<select name='guestposts' class='textbox'>
					<option value='1'{if $settings2.guestposts == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.guestposts == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.656}
			</td>
			<td width='50%' class='tbl'>
				<select name='numofshouts' class='textbox' style='width:50px;'>
					<option{if $settings2.numofshouts == 5} selected{/if}>5</option>
					<option{if $settings2.numofshouts == 10} selected{/if}>10</option>
					<option{if $settings2.numofshouts == 15} selected{/if}>15</option>
					<option{if $settings2.numofshouts == 20} selected{/if}>20</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.660}
9E			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='flood_interval' value='{$settings2.flood_interval}' maxlength='2' class='textbox' style='width:50px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.668}
				<br />
				<span class='small2'>{$locale.670}</span>
			</td>
			<td width='50%' class='tbl'>
				<select name='remote_stats' class='textbox'>
					<option value='1'{if $settings2.remote_stats == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.remote_stats == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.520}
			</td>
			<td width='50%' class='tbl'>
				<select name='forum_flags' class='textbox'>
					<option value='1'{if $settings2.forum_flags == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.forum_flags == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' colspan='2'>
				<hr />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.657}
			</td>
			<td width='50%' class='tbl'>
				<select name='maintenance' class='textbox' style='width:50px;'>
					<option value='1'{if $settings2.maintenance == "1"} selected{/if}>{$locale.502}</option>
					<option value='0'{if $settings2.maintenance == "0"} selected{/if}>{$locale.503}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.669}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='maintenance_color' value='{$settings2.maintenance_color}' maxlength='10' class='textbox' style='width:100px;' />
			</td>
		</tr>
		<tr>
			<td valign='top' width='50%' class='tbl'>
				{$locale.658}
			</td>
			<td width='50%' class='tbl'>
				<textarea name='maintenance_message' rows='5' class='textbox' style='width:250px;'>{$settings2.maintenance_message}</textarea>
			</td>
		</tr>
		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='submit' name='savesettings' value='{$locale.750}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}