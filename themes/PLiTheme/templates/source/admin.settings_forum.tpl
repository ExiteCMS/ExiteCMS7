{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.settings_forum.tpl                       *}
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
{* Template for the admin configuration module 'settings_forum'            *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.505}
				<br />
				<span class='small2'>{$locale.506}</span>
			</td>
			<td width='50%' class='tbl'>
				<select name='numofthreads' class='textbox'>
					<option{if $settings2.numofthreads == 5} selected{/if}>5</option>
					<option{if $settings2.numofthreads == 10} selected{/if}>10</option>
					<option{if $settings2.numofthreads == 15} selected{/if}>15</option>
					<option{if $settings2.numofthreads == 20} selected{/if}>20</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.507}
			</td>
			<td width='50%' class='tbl'>
				<select name='attachments' class='textbox'>
					<option value='1'{if $settings2.attachments == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.attachments == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.510}
				<br />
				<span class='small2'>{$locale.511}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='attachmax' value='{$settings2.attachmax}' maxlength='150' class='textbox' style='width:100px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.512}
				<br />
				<span class='small2'>{$locale.513}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='attachtypes' value='{$settings2.attachtypes}' maxlength='150' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.521}
				<br />
				<span class='small2'>{$locale.522}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='forum_max_w' value='{$settings2.forum_max_w}' maxlength='4' class='textbox' style='width:50px;' />
				x
				<input type='text' name='forum_max_h' value='{$settings2.forum_max_h}' maxlength='4' class='textbox' style='width:50px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.519}
			</td>
			<td width='50%' class='tbl'>
				<select name='thread_notify' class='textbox'>
					<option value='1'{if $settings2.thread_notify == "1"} selected{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.thread_notify == "0"} selected{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.514}
				<br />
				<span class='small2'><font color='red'>{$locale.515}</font> {$locale.516}</span>
			</td>
			<td width='50%' class='tbl'>
				<input type='submit' name='prune' value='{$locale.517}' class='button' />
				<select name='prune_days' class='textbox' style='width:50px;'>
					<option>10</option>
					<option>20</option>
					<option>30</option>
					<option>60</option>
					<option>90</option>
					<option>120</option>
					<option selected>180</option>
				</select>
				{$locale.518} 
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