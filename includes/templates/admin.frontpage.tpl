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
{* Template for the admin content module 'frontpage'                       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='latestnewsform' method='post' action='{$action}'>
	<table align='center' cellpadding='0' cellspacing='0' width='90%'>
		<tr>
			<td align='center' class='tbl'>
			{$locale.541}
			</td>
		</tr>
		<tr>
			<td align='center' class='tbl'>
			{foreach from=$headlines item=headline name=hl}
				{$locale.543} {$smarty.foreach.hl.iteration}:
				<select name='headlines[{$smarty.foreach.hl.iteration}]' class='textbox' style='width: 400px'>
				{section name=id loop=$headline}
					{if $headline[id].news_new_cat}
						{if !$smarty.section.id.first}</optgroup>{/if}
						<optgroup label='{$headline[id].news_cat_name}'>
						{assign var='hasvalues' value=false}
					{/if}
					<option value='{$headline[id].news_id}' {if $headline[id].selected}selected{/if}>{$headline[id].news_subject}</option>
					{assign var='hasvalues' value=true}
					{if $smarty.section.id.last && $hasvalues}</optgroup>{/if}
				{/section}
			</select>
			<br /><br />
			{/foreach}
			</td>
		</tr>
	</table>
	<hr />
	<table align='center' cellpadding='0' cellspacing='0' width='90%'>
		{if $newsitems|@count}
			<tr>
				<td align='center' class='tbl'>
				{$locale.542}
				</td>
			</tr>
			<tr>
				<td align='center' class='tbl'>
				{foreach from=$newsitems item=newsitem name=ni}
					{$locale.543} {$smarty.foreach.ni.iteration}:
					<select name='newsitems[{$smarty.foreach.ni.iteration}]' class='textbox' style='width: 400px'>
					{section name=id loop=$newsitem}
						{if $newsitem[id].news_new_cat}
							{if !$smarty.section.id.first}</optgroup>{/if}
							<optgroup label='{$newsitem[id].news_cat_name}'>
							{assign var='hasvalues' value=false}
						{/if}
						<option value='{$newsitem[id].news_id}' {if $newsitem[id].selected}selected{/if}>{$newsitem[id].news_subject}</option>
						{assign var='hasvalues' value=true}
						{if $smarty.section.id.last && $hasvalues}</optgroup>{/if}
					{/section}
				</select>
				<br /><br />
				{/foreach}
				</td>
			</tr>
		{/if}
		<tr>
			<td align='center' class='tbl'>
				<span class='small'>{$locale.545}</span>
			</td>
		</tr>
		<tr>
			<td align='center' class='tbl'>
				<input type='checkbox' name='news_latest' value='yes'{if $settings.news_latest} checked{/if}/> {$locale.546}<br />
			</td>
		</tr>
		<tr>
			<td align='center' class='tbl'>
				<br />
				<input type='submit' name='save_latest' value='{$locale.544}' class='button' />
			</td>
		</tr>
	</table>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
