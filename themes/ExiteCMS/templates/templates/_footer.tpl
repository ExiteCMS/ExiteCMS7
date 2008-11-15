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
{* $Id:: _footer.tpl 1935 2008-10-29 23:42:42Z WanWizard                  $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author:: WanWizard                                   $*}
{* Revision number $Rev:: 1935                                            $*}
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
						v.{$settings.version} <a href='http://www.exitecms.org' target='_blank' title='ExiteCMS v7.2 ©2008 Exite BV'><img src='{$smarty.const.IMAGES}cms-logo-small.png' alt='' /></a> rev.{$settings.revision}<br />
						{$settings.counter} {if $settings.counter == 1}{$locale.140}{else}{$locale.141}{/if}
					</td>
				</tr>
				<tr>
					<td>
						<br />
						The webserver needed {$_loadstats.time|string_format:"%01.3f"} sec. to process this page. Of that, the MySQL v{$MySQLinfo} engine needed {$_loadstats.querytime|string_format:"%01.3f"} sec.
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
			</table>
		</td>
	</tr>
</table>
{***************************************************************************}
{* End of Template                                                         *}
{***************************************************************************}
