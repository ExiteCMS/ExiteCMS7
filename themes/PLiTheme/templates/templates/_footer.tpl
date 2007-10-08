{***************************************************************************}
{*                                                                         *}
{* ExiteCMS - template: footer.tpl                                         *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-01 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates the ExiteCMS website footer.                    *}
{* If this footer requires custom variables, assign them in the footer     *}
{* preprocessing section of your theme.php                                 *}
{*                                                                         *}
{***************************************************************************}
<table align='center' cellpadding='0' cellspacing='0' width='{$smarty.const.THEME_WIDTH}'>
	<tr>
		<td class='footer'>
			<table align='center' cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td>
						v.{$settings.version} <a href='http://exitecms.exite.eu' target='_blank'><img src='{$smarty.const.IMAGES}cms-logo-small.png' alt='' /></a> rev.{$settings.revision}<br />
						{$settings.counter} {if $settings.counter == 1}{$locale.140}{else}{$locale.141}{/if}
					</td>
				</tr>
				{if $settings.siteurl != "http://www.pli-images.org/"}
				<tr>
					<td>
						<br />
						The webserver needed {$_loadstats.time|string_format:"%01.3f"} sec. to process this page. Of that, the database engine needed {$_loadstats.querytime|string_format:"%01.3f"} sec.
					</td>
				</tr>
				<tr>
					<td>
						Total number of queries: {$_loadstats.queries}&nbsp;&nbsp;(Selects: {$_loadstats.selects}&nbsp;&nbsp;Inserts: {$_loadstats.inserts}&nbsp;&nbsp;Deletes: {$_loadstats.deletes}&nbsp;&nbsp;Updates: {$_loadstats.updates}&nbsp;&nbsp;Others: {$_loadstats.others})
					</td>
				</tr>
				{if $_loadstats.compression}
				<tr>
					<td>
						zlib compression is enabled for this website
					</td>
				</tr>
				{/if}
				{/if}
			</table>
		</td>
	</tr>
</table>
{***************************************************************************}
{* End of Template                                                         *}
{***************************************************************************}