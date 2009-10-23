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
{* Template for the admin configuration module 'settings_languages'        *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.427}
			</td>
			<td width='50%' class='tbl'>
				<select name='browserlang' class='textbox'>
					<option value='1'{if $settings2.browserlang == 1} selected="selected"{/if}>{$locale.508}</option>
					<option value='0'{if $settings2.browserlang == 0} selected="selected"{/if}>{$locale.509}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.414}
			</td>
			<td width='50%' class='tbl'>
				<select name='localeset' class='textbox'>
					{foreach from=$locales item=file}
					<option value='{$file}'{if $settings2.locale == $file} selected="selected"{/if}>{$file}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.404}
			</td>
			<td width='50%' class='tbl'>
				{html_options name="country" options=$countries selected=$settings2.country|default:"--" class="textbox"}
			</td>
		</tr>
		<tr>
			<td class='tbl' align='center' colspan='2'>
			</td>
		</tr>
		<tr>
			<td class='tbl2' align='center' colspan='2'>
				{$locale.558}
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.570}
			</td>
			<td width='50%' class='tbl'>
				<select name='panels_localisation' class='textbox'>
					<option value='none'{if $settings2.panels_localisation == "none"} selected="selected"{/if}>{$locale.559}</option>
{*					<option value='single'{if $settings2.panels_localisation == "single"} selected="selected"{/if}>{$locale.560}</option> *}
					<option value='multiple'{if $settings2.panels_localisation == "multiple"} selected="selected"{/if}>{$locale.561}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.571}
			</td>
			<td width='50%' class='tbl'>
				<select name='sitelinks_localisation' class='textbox'>
					<option value='none'{if $settings2.sitelinks_localisation == "none"} selected="selected"{/if}>{$locale.559}</option>
{*					<option value='single'{if $settings2.sitelinks_localisation == "single"} selected="selected"{/if}>{$locale.560}</option> *}
					<option value='multiple'{if $settings2.sitelinks_localisation == "multiple"} selected="selected"{/if}>{$locale.561}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.572}
			</td>
			<td width='50%' class='tbl'>
				<select name='article_localisation' class='textbox'>
					<option value='none'{if $settings2.article_localisation == "none"} selected="selected"{/if}>{$locale.559}</option>
{*					<option value='single'{if $settings2.article_localisation == "single"} selected="selected"{/if}>{$locale.560}</option> *}
					<option value='multiple'{if $settings2.article_localisation == "multiple"} selected="selected"{/if}>{$locale.561}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.573}
			</td>
			<td width='50%' class='tbl'>
				<select name='news_localisation' class='textbox'>
					<option value='none'{if $settings2.news_localisation == "none"} selected="selected"{/if}>{$locale.559}</option>
{*					<option value='single'{if $settings2.news_localisation == "single"} selected="selected"{/if}>{$locale.560}</option> *}
					<option value='multiple'{if $settings2.news_localisation == "multiple"} selected="selected"{/if}>{$locale.561}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.574}
			</td>
			<td width='50%' class='tbl'>
				<select name='download_localisation' class='textbox'>
					<option value='none'{if $settings2.download_localisation == "none"} selected="selected"{/if}>{$locale.559}</option>
{*					<option value='single'{if $settings2.download_localisation == "single"} selected="selected"{/if}>{$locale.560}</option> *}
					<option value='multiple'{if $settings2.download_localisation == "multiple"} selected="selected"{/if}>{$locale.561}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tbl' align='center' colspan='2'>
			</td>
		</tr>
		<tr>
			<td class='tbl2' align='center' colspan='2' style='border: 1px solid red;'>
				<font style='color:red;'><b>Note that localisation is expirimental at the moment.</b></font>
			</td>
		</tr>
		<tr>
			<td class='tbl' align='center' colspan='2'>
			</td>
		</tr>
		<tr>
			<td class='tbl2' align='center' colspan='2'>
				{$locale.562}
			</td>
		</tr>
{*		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.563}
			</td>
			<td width='50%' class='tbl'>
				{$locale.559}
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.564}
			</td>
			<td width='50%' class='tbl'>
				{$locale.560}
			</td>
		</tr>
		<tr>
			<td class='tbl1' align='left' colspan='2'>
				<span class='small2'>{$locale.569a}</span>
				<hr />
			</td>
		</tr>
*}		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.563}
			</td>
			<td width='50%' class='tbl'>
				{$locale.559}
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.564}
			</td>
			<td width='50%' class='tbl'>
				{$locale.561}
			</td>
		</tr>
		<tr>
			<td class='tbl1' align='left' colspan='2'>
				<span class='small2'>{$locale.569b}</span>
				<hr />
			</td>
		</tr>
{*		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.563}
			</td>
			<td width='50%' class='tbl'>
				{$locale.560}
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.564}
			</td>
			<td width='50%' class='tbl'>
				{$locale.559}
			</td>
		</tr>
		<tr>
			<td class='tbl1' align='left' colspan='2'>
				<span class='small2'>{$locale.569c}</span>
				<hr />
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.563}
			</td>
			<td width='50%' class='tbl'>
				{$locale.560}
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.564}
			</td>
			<td width='50%' class='tbl'>
				{$locale.561}
			</td>
		</tr>
		<tr>
			<td class='tbl1' align='left' colspan='2'>
				<span class='small2'>{$locale.569d}</span>
				<hr />
			</td>
		</tr>
*}		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.563}
			</td>
			<td width='50%' class='tbl'>
				{$locale.561}
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.564}
			</td>
			<td width='50%' class='tbl'>
				{$locale.559}
			</td>
		</tr>
		<tr>
			<td class='tbl1' align='left' colspan='2'>
				<span class='small2'>{$locale.569e}</span>
				<hr />
			</td>
		</tr>
{*		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.563}
			</td>
			<td width='50%' class='tbl'>
				{$locale.561}
			</td>
		</tr>
		<tr>
			<td align='right' width='50%' class='tbl'>
				{$locale.564}
			</td>
			<td width='50%' class='tbl'>
				{$locale.560}
			</td>
		</tr>
		<tr>
			<td class='tbl1' align='left' colspan='2'>
				<span class='small2'>{$locale.569f}</span>
				<br />
			</td>
		</tr>
*}		<tr>
			<td align='center' colspan='2' class='tbl'>
				<br />
				<input type='hidden' name='old_localeset' value='{$settings2.locale}' />
				<input type='hidden' name='old_country' value='{$settings2.country|default:""}' />
				<input type='submit' name='savesettings' value='{$locale.750}' class='button' />
			</td>
		</tr>
	</table>
</form>
{include file="_closetable.tpl"}
<script type='text/javascript'>
{literal}
{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
