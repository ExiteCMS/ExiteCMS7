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
{* $Id:: _header.tpl 1996 2008-11-10 11:30:08Z WanWizard                  $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author:: WanWizard                                   $*}
{* Revision number $Rev:: 1996                                            $*}
{***************************************************************************}
{*                                                                         *}
{* This template generates the ExiteCMS website header.                    *}
{* If this header requires custom variables, assign them in the header     *}
{* preprocessing section of your theme.php                                 *}
{*                                                                         *}
{***************************************************************************}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$settings.locale_code|truncate:2:""}" lang="{$settings.locale_code|truncate:2:""}" dir="{$smarty.const.LOCALEDIR|lower}">

<head>
	<title>{$settings.sitename}{if defined('PAGETITLE')} - {$smarty.const.PAGETITLE}{/if}</title>
	<meta http-equiv='Content-Type' content='text/html; charset={$settings.charset}' />
	<meta http-equiv='Content-Language' content='{$settings.locale_code|truncate:2:""}' />
	<meta name='description' content='{$settings.description}' />
	<meta name='keywords' content='{$settings.keywords}' />
	<meta name="verify-v1" content="Ek6JHBkP+IbfHNOB0DaMHmxpC9eAljv3JCcWmUpcF+U=" />
	{if $headparms|default:false != false}{$headparms}{/if}
	<link href="{$smarty.const.THEME}exitecms__0001.css" rel="stylesheet" type="text/css" />
	{literal}
	<style type="text/css">
		.body-maint { margin: 5px 5px 5px 5px; color:#000; background-color:{/literal}{$settings.maintenance_color}{literal}; }
	</style>
	{/literal}
	{if $favicon|default:false != false}<link rel='shortcut icon' href='{$favicon}' />{/if}
	<script type='text/javascript' src='{$smarty.const.INCLUDES}jscripts/core_functions__0001.js'></script>
	{if $smarty.const.LOAD_TINYMCE}
		{include file="_load_tinymce.tpl"}
	{/if}
	{if $smarty.const.LOAD_HOTEDITOR}
		{include file="_load_hoteditor.tpl"}
	{/if}
</head>

<body {if $bodyparms|default:false != false}{$bodyparms}{/if} {if $userdata.user_id == 1 || $settings.maintenance}class='body-maint'{else}class='body'{/if}>
<a name="page_top" id="page_top"></a>

{literal}
<script type='text/javascript'></script>
<script type='text/javascript'>
/* <![CDATA[ */
// assume standard window dimensions
var myWidth = 1024, myHeight = 768;
if( typeof( window.innerWidth ) == 'number' ) {
	// Non-IE
	myWidth = window.innerWidth;
	myHeight = window.innerHeight;
} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
	// IE 6+ in 'standards compliant mode'
	myWidth = document.documentElement.clientWidth;
	myHeight = document.documentElement.clientHeight;
} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
	// IE 4 compatible
	myWidth = document.body.clientWidth;
	myHeight = document.body.clientHeight;
}
createCookie('width', myWidth, 0);
createCookie('height', myHeight, 0);

var fontGrootte = 0.7;
var pliCookie = readCookie('pliFontSize');

if (pliCookie != null) {
	var fontSize = Number(pliCookie);
	// fix fontsize calculation change problem
	if (fontSize < 2.5) fontGrootte = fontSize;
}

function fontGroter(aantal) {
	if (Math.abs(aantal) < 1) {
		fontGrootte = fontGrootte + aantal;
	} else {
		fontGrootte = aantal;
	}
	if (fontGrootte > 2.5) fontGrootte = 0.7;
	fontGrootte = Math.round(fontGrootte*100)/100;
	document.body.style.fontSize = fontGrootte + 'em';
	createCookie('pliFontSize',fontGrootte,365);
}		

function fontReset(aantal) {
	fontGrootte = Math.round(aantal*100)/100;
	document.body.style.fontSize = fontGrootte + 'em';
	createCookie('pliFontSize',fontGrootte,365);
}		

fontReset(fontGrootte);

function checkMessages() {
	// check for new forum messages
	var newmsg = AjaxCall("{/literal}{$smarty.const.BASEDIR}{literal}includes/ajax.response.php?request=posts");
	if (newmsg != null && document.getElementById("new_posts_header")) {
		if (newmsg > 0) {
			document.getElementById("new_posts_header").innerHTML = "<a href='{/literal}{$smarty.const.BASEDIR}{literal}modules/forum_threads_list_panel/new_posts.php'><img src='{/literal}{$smarty.const.THEME}{literal}images/newposts.gif' height='9' alt='{/literal}{$locale.028}{literal}' /></a>&nbsp;";
		} else {
			document.getElementById("new_posts_header").innerHTML = "";
		}
	}
	if (newmsg != null && document.getElementById("new_posts_panel")) {
		if (newmsg > 0) {
			document.getElementById("new_posts_panel_value").innerHTML = AjaxCall("{/literal}{$smarty.const.BASEDIR}{literal}includes/ajax.response.php?request=posts&parms=text");
			document.getElementById("new_posts_panel").style.display = 'inline';
		} else {
			document.getElementById("new_posts_panel").style.display = 'none';
		}
	}
	// check for new pm messages
	var newpm = AjaxCall("{/literal}{$smarty.const.BASEDIR}{literal}includes/ajax.response.php?request=pm");
	if (newpm != null && document.getElementById("new_pm_header")) {
		if (newpm > 0) {
			document.getElementById("new_pm_header").innerHTML = "<a href='{/literal}{$smarty.const.BASEDIR}{literal}pm.php?action=show_new'><img src='{/literal}{$smarty.const.THEME}{literal}images/newmsgs.gif' height='9' alt='' /></a>&nbsp;";
		} else {
			document.getElementById("new_pm_header").innerHTML = "";
		}
	}
	if (newpm != null && document.getElementById("new_pm_panel")) {
		if (newpm > 0) {
			document.getElementById("new_pm_panel_value").innerHTML = AjaxCall("{/literal}{$smarty.const.BASEDIR}{literal}includes/ajax.response.php?request=pm&parms=text");
			document.getElementById("new_pm_panel").style.display = 'inline';
		} else {
			document.getElementById("new_pm_panel").style.display = 'none';
		}
	}
	// restart the timer for the next check, in 5 minutes
	msgtimerid = setTimeout("checkMessages()", 300000);
}
{/literal}
{if iMEMBER}
// start the timer for the first check, in 5 minutes
msgtimerid = setTimeout("checkMessages()", 300000);
{/if}
/* ]]> */
</script>
<table align='center' cellspacing='0' cellpadding='0' width='{$smarty.const.THEME_WIDTH}' class='main-bg'>
	<tr>
		<td>
			<table cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td class='headerbanner'>
						<table cellpadding='0' cellspacing='0' width='100%'>
							<tr  align='center'>
								<td >
									<table border='0' width='100%'>
										<tr style='height:55px'>
											<td align='right'>
												{* room for other text *}
											</td>
										</tr>
										<tr style='height:55px'>
											<td align='right'>
											{if $settings.maintenance}
												<br />
												<font size='6' color='{$settings.maintenance_color}'><b>MAINTENANCE&nbsp;</b></font>
											{/if}
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td class='headermenu'>
						<a href='.' onclick='fontGroter(-0.1); return false' title='Decrease font-size'><img src='{$smarty.const.THEME}images/minus.gif' alt='' border='0' /></a><a href='.' onclick='fontReset(0.7); return false' title='Restore default font-sizes'><img src='{$smarty.const.THEME}images/reset.gif' hspace='2' alt='' border='0' /></a><a href='.' onclick='fontGroter(0.1); return false' title='Increase font-size'><img src='{$smarty.const.THEME}images/plus.gif' alt='' border='0' /></a>
						<div id='new_posts_header' style='display:inline;'>
							{if $new_posts}
								<a href='{$smarty.const.BASEDIR}modules/forum_threads_list_panel/new_posts.php'><img src='{$smarty.const.THEME}images/newposts.gif' height='9' alt='{$locale.028}' /></a>
							{/if}
						</div>
						<div id='new_pm_header' style='display:inline;'>
							{if $new_pm}
								<a href='{$smarty.const.BASEDIR}pm.php?action=show_new'><img src='{$smarty.const.THEME}images/newmsgs.gif' height='9' alt='' /></a>&nbsp;
							{/if}
						</div>
						{section name=index loop=$headermenu}
							{if $smarty.section.index.first} &nbsp;{else} &middot;{/if} <a href='{$headermenu[index].link_url}' {if $headermenu[index].link_window == 1}target='_blank' {/if}><span class='headermenuitem'>{$headermenu[index].link_name}</span></a>
						{/section}
					</td>
					<td align='right' class='sub-cap-main'>
						{$smarty.now|date_format:"subheaderdate"}&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
{***************************************************************************}
{* End of Template                                                         *}
{***************************************************************************}
