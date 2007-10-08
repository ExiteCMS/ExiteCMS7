{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: header.tpl                                           *}
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
{* This template generates the ExiteCMS website header.                    *}
{* If this header requires custom variables, assign them in the header     *}
{* preprocessing section of your theme.php                                 *}
{*                                                                         *}
{***************************************************************************}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>{$settings.sitename}</title>
	<meta http-equiv='Content-Type' content='text/html; charset={$locale.charset}' />
	<meta name='description' content='{$settings.description}' />
	<meta name='keywords' content='{$settings.keywords}' />
	<meta name='verify-v1' content='6uLZe0u5c6hJ3XE0LoGBQRuU7IdJ/B6BIa2Si7b1dkw=' />
	{if $headparms|default:false != false}{$headparms}{/if}
	<link rel='stylesheet' href='{$smarty.const.THEME}styles.css' type='text/css' />
	{if $favicon|default:false != false}<link rel='shortcut icon' href='{$favicon}' />{/if}
	<script type='text/javascript' src='{$smarty.const.INCLUDES}jscripts/core_functions.js'></script>
	{if $smarty.const.LOAD_TINYMCE}
		<script type='text/javascript' src='{$smarty.const.INCLUDES}jscripts/tiny_mce/tiny_mce_gzip.php'></script>
		{literal}
		<script type='text/javascript'>
		function advanced() {
			tinyMCE.init({
			mode:'textareas',
			theme:'advanced',
			language:'en',
			entities:'60,lt,62,gt',
			document_base_url:'{/literal}{$settings.siteurl}{literal}',
			relative_urls:'false',
			convert_newlines_to_brs:'true',
			force_br_newlines:'true',
			force_p_newlines:'false',
			plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking",
			theme_advanced_toolbar_location:'bottom',
			theme_advanced_toolbar_align:'center',
			theme_advanced_path_location:'none',
			theme_advanced_toolbar_location:'top',
			content_css:'{/literal}{$smarty.const.THEME}{literal}styles.css',
			external_image_list_url:'{/literal}{$smarty.const.IMAGES}{literal}imagelist.js',
			plugin_insertdate_dateFormat:'%d-%m-%Y',
			plugin_insertdate_timeFormat:'%H:%M:%S',
			invalid_elements:'script,object,applet,iframe',
			extended_valid_elements:'a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]'
			});
		}
		function simple() {
			tinyMCE.init({
			mode:'textareas',
			theme:'simple',
			language:'en',
			convert_newlines_to_brs:'true',
			force_br_newlines:'true',
			force_p_newlines:'false'
			});
		}
		
		function showtiny(EditorID) {
			tinyMCE.removeMCEControl(tinyMCE.getEditorId(EditorID));
			tinyMCE.addMCEControl(document.getElementById(EditorID),EditorID);
		}
		
		function hidetiny(EditorID) {
			tinyMCE.removeMCEControl(tinyMCE.getEditorId(EditorID));
		}
		</script>
		{/literal}
	{/if}
</head>

<body {if $bodyparms|default:false != false}{$bodyparms}{/if} {if $userdata.user_level == 103 || $settings.maintenance}class='body-maint'{else}class='body'{/if}>

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
fontReset(fontGrootte);

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

/* ]]> */
</script>{/literal}
<table align='center' width='{$smarty.const.THEME_WIDTH}' cellspacing='0' cellpadding='0'>
	<tr>
		<td class='border-tleft'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='16' alt='' /></td>
		<td colspan='2' class='border-tmain'><img src='{$smarty.const.THEME}images/blank.gif' width='1' height='16' alt='' style='display:block' /></td>
		<td class='border-tright'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='16' alt='' /></td>
	</tr>
	<tr>
		<td class='border-left'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='1' alt='' /></td>
		<td colspan='2' class='headerbanner'>
			<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
					<td width='10' style='background-image: url({$smarty.const.THEME}images/top-back.png); background-repeat:repeat-x;'></td>
					<td width='205'><a href='/modules/donations/index.php'><img src='{$smarty.const.THEME}images/top-left-donate.png' alt='' /></a></td>
					<td style='vertical-align: middle; background-image: url({$smarty.const.THEME}images/top-back.png); background-repeat:repeat-x;'>
						<table border='0' width='100%'>
							<tr style='height:55px'>
								<td>
								{if $settings.siteurl != "http://www.pli-images.org/"}
									{if $settings.siteurl == "http://dev.pli-images.org/"}
										<center><font size='6' color='{$settings.maintenance_color}'><b>DEVELOPMENT SITE</b></font></center>
									{else}
										<center><font size='6' color='{$settings.maintenance_color}'><b>LOCAL DEVELOPMENT</b></font></center>
									{/if}
								{/if}
								</td>
							</tr>
							<tr style='height:55px'>
								<td>
								{if $settings.maintenance}
									<br />
									<center><font size='6' color='{$settings.maintenance_color}'><b>MAINTENANCE MODE</b></font></center>
								{/if}
								</td>
							</tr>
						</table>
					</td>
					<td width='318' style='background-image: url({$smarty.const.THEME}images/top-right.png); background-repeat:repeat-x;'>
					</td>
					<td width='10' style='background-image: url({$smarty.const.THEME}images/top-back.png); background-repeat:repeat-x;'>
					</td>
				</tr>
			</table>
			<div style='float;padding:1px;margin-top:-66px;margin-right:2px;'>
				{section name=index loop=$downloadbars}
					<div class='bar' id='bar{$downloadbars[index].download_bar}' style='height:{$downloadbars[index].value}px;top:{$downloadbars[index].baseline}px;'></div>
				{/section}
			</div>
		</td>
		<td class='border-right'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='1' alt='' style='display:block' /></td>
	</tr>
	<tr>
		<td class='sub-cap-left'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='21' alt='' style='display:block' /></td>
		<td class='sub-cap-main'>
			<a href='.' onclick='fontGroter(-0.1); return false' title='Decrease font-size'><img src='{$smarty.const.THEME}images/minus.gif' alt='' border='0' /></a><a href='.' onclick='fontReset(0.7); return false' title='Restore default font-sizes'><img src='{$smarty.const.THEME}images/reset.gif' hspace='2' alt='' border='0' /></a><a href='.' onclick='fontGroter(0.1); return false' title='Increase font-size'><img src='{$smarty.const.THEME}images/plus.gif' alt='' border='0' /></a>
			{if $new_posts}
				<a href='{$smarty.const.BASEDIR}modules/forum_threads_list_panel/new_posts.php'><img src='{$smarty.const.THEME}images/newposts.gif' height='9' alt='{$locale.028}' /></a>
			{/if}
			{if $new_pm}
				<a href='{$smarty.const.BASEDIR}pm.php?action=show_new'><img src='{$smarty.const.THEME}images/newmsgs.gif' height='9' alt='' /></a>
			{/if}
			{section name=index loop=$headermenu}
				{if !$smarty.section.index.first} &middot;{/if} <a href='{$headermenu[index].link_url}' {if $headermenu[index].link_window == 1}target='_blank' {/if}><span class='small'>{$headermenu[index].link_name}</span></a>
			{/section}
		</td>
		<td align='right' class='sub-cap-main'>
			<table cellpadding='0' cellspacing='0'>
				<tr>
					<td id='total-downloads' class='boxheader'>
						{assign var='total' value='0'}
						{section name=index loop=$downloadbars}
							{assign var='total' value=`$total+$downloadbars[index].download_count`}
						{/section}
						<img src='{$smarty.const.THEME}bartext.php?text={$bartitle}%20:%20{$total}%20%20%20|%20&amp;cache=yes' alt='' />
					</td>
					{section name=index loop=$downloadbars}
						<td class='boxtypes'><a href='http://www.pli-images.org/downloads.php?download_id={$downloadbars[index].download_id}'><img src='{$smarty.const.THEME}bartext.php?text={$downloadbars[index].download_title}&amp;cache=yes' alt='' /></a></td>
					{/section}
				</tr>
			</table>
		</td>
		<td class='sub-cap-right'><img src='{$smarty.const.THEME}images/blank.gif' width='5' height='21' alt='' style='display:block' /></td>
	</tr>
</table>
{***************************************************************************}
{* End of Template                                                         *}
{***************************************************************************}