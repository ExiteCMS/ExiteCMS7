{***************************************************************************}
{*                                                                         *}
{* PLi-Fusion CMS template: admin.settings.main.tpl                        *}
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
{* Template for the admin configuration module 'settings_main'             *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400  state=$_state style=$_style}
{include file="admin.settings_links.tpl}
<form name='settingsform' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}'>
	<table align='center' cellpadding='0' cellspacing='0' width='500'>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.402}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='sitename' value='{$settings2.sitename}' maxlength='255' class='textbox' style='width:230px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.403}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='siteurl' value='{$settings2.siteurl}' maxlength='255' class='textbox' style='width:230px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.404}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='sitebanner' value='{$settings2.sitebanner}' maxlength='255' class='textbox' style='width:230px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.405}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='siteemail' value='{$settings2.siteemail}' maxlength='128' class='textbox' style='width:230px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.419}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='newsletter_email' value='{$settings2.newsletter_email}' maxlength='128' class='textbox' style='width:230px;'/>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.406}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='username' value='{$settings2.siteusername}' maxlength='32' class='textbox' style='width:230px;' />
			</td>
		</tr>
		<tr>
			<td valign='top' width='50%' class='tbl'>
				{$locale.407}
				<br />
				<span class='small2'>{$locale.408}</span>
			</td>
			<td width='50%' class='tbl'>
				<textarea name='intro' rows='6' class='textbox' style='width:230px;'>{$settings2.siteintro}</textarea>
			</td>
		</tr>
		<tr>
			<td valign='top' width='50%' class='tbl'>
				{$locale.409}
			</td>
			<td width='50%' class='tbl'>
				<textarea name='description' rows='6' class='textbox' style='width:230px;'>{$settings2.description}</textarea>
			</td>
		</tr>
		<tr>
			<td valign='top' width='50%' class='tbl'>
				{$locale.410}
				<br />
				<span class='small2'>{$locale.411}</span>
			</td>
			<td width='50%' class='tbl'>
				<textarea name='keywords' rows='6' class='textbox' style='width:230px;'>{$settings2.keywords}</textarea>
			</td>
		</tr>
		<tr>
			<td valign='top' width='50%' class='tbl'>
				{$locale.412}
			</td>
			<td width='50%' class='tbl'>
				<textarea name='footer' rows='6' class='textbox' style='width:230px;'>{$settings2.footer}</textarea>
			</td>
		</tr>
		<tr>
			<td valign='top' class='tbl'>
				{$locale.413}
			</td>
			<td width='50%' class='tbl'>
				<input type='text' name='opening_page' value='{$settings2.opening_page}' maxlength='100' class='textbox' style='width:200px;' />
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.416}
			</td>
			<td width='50%' class='tbl'>
				<select name='news_columns' class='textbox'>
					<option value='1'{if $settings2.news_columns == 1} selected{/if}>{$locale.417}</option>
					<option value='2'{if $settings2.news_columns == 2} selected{/if}>{$locale.418}</option>
					<option value='3'{if $settings2.news_columns == 3} selected{/if}>{$locale.420}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.421}
			</td>
			<td width='50%' class='tbl'>
				<select name='news_headline' id='news_headline' class='textbox' onchange='return validateNewsItems();'>
					<option value='0'{if $settings2.news_headline == 0} selected{/if}> 0 </option>
					{section name=cnt start=1 loop=5}
					<option value='{$smarty.section.cnt.index}'{if $settings2.news_headline == $smarty.section.cnt.index} selected{/if}> {$smarty.section.cnt.index} </option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.422}
			</td>
			<td width='50%' class='tbl'>
				<select name='news_items' id='news_items' class='textbox' onchange='return validateNewsItems();'>
					<option value='1'{if $settings2.news_items == 1} selected{/if}> 1 </option>
					<option value='2'{if $settings2.news_items == 2} selected{/if}> 2 </option>
					<option value='3'{if $settings2.news_items == 3} selected{/if}> 3 </option>
					<option value='4'{if $settings2.news_items == 4} selected{/if}> 4 </option>
					<option value='5'{if $settings2.news_items == 5} selected{/if}> 5 </option>
					<option value='6'{if $settings2.news_items == 6} selected{/if}> 6 </option>
					<option value='7'{if $settings2.news_items == 7} selected{/if}> 7 </option>
					<option value='8'{if $settings2.news_items == 8} selected{/if}> 8 </option>
					<option value='9'{if $settings2.news_items == 9} selected{/if}> 9 </option>
					<option value='0'{if $settings2.news_items == 0} selected{/if}>{$locale.423}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width='50%' class='tbl'>
				{$locale.415}
			</td>
			<td width='50%' class='tbl'>
				<select name='theme' class='textbox'>
					{foreach from=$theme_files item=file}
					<option value='{$file}'{if $settings2.theme == $file} selected{/if}>{$file}</option>
					{/foreach}
				</select>
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
<script type='text/javascript'>
{literal}
function validateNewsItems() {
	var selHeadline = document.getElementById("news_headline").options.selectedIndex;
	var selItems = document.getElementById("news_items").options.selectedIndex;
	var valHeadline = document.getElementById("news_headline").options[selHeadline].value;
	var valItems = document.getElementById("news_items").options[selItems].value;
	if (valHeadline > valItems) {
		alert('{/literal}{$locale.424}{literal}');
		document.getElementById("news_items").options[selHeadline-1].selected = true;
	} else {
		return true;
	}
}

{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}