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
{* This template is called from the header when TinyMCE is needed on a page*}
{*                                                                         *}
{***************************************************************************}
{if $smarty.const.LOAD_TINYMCE}
	<script type='text/javascript' src='{$smarty.const.TINY_MCE}tiny_mce.js'></script>
	{literal}
	<script type='text/javascript'>
	function advanced() {
		tinyMCE.init({
		mode:'textareas',
		editor_deselector:'textbox',
		theme:'advanced',
		language:'{/literal}{$settings.tinyMCE_locale}{literal}',
		entities:'34,quot,38,amp,60,lt,62,gt',
		document_base_url:'{/literal}{$settings.siteurl}{literal}',
		relative_urls:false,
		remove_script_host: false,
		apply_source_formatting:false,
		inline_styles:true,
		convert_newlines_to_brs:false,
		convert_fonts_to_spans:true,
		force_br_newlines:false,
		force_p_newlines:false,
		remove_linebreaks:false,
		forced_root_block:'',
		fix_list_elements:false,
		fix_table_elements:true,
		fix_nesting:false,
		cleanup:true,
		cleanup_on_startup:false,
		plugins : "style,layer,table,save,advhr,advimage,ibrowser,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,|,image,ibrowser,cleanup,help,code,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,|,insertdate,inserttime",
		theme_advanced_toolbar_location:'bottom',
		theme_advanced_toolbar_align:'center',
		theme_advanced_path_location:'none',
		theme_advanced_toolbar_location:'top',
		theme_advanced_font_sizes:'8px=8px, 9px=9px, 10px=10px, 11px=11px, 12px=12px, 14px=14px, 16px=16px, 18px=18px, 24px=24px, 36px=36px, 48px=48px, 56px=56px, 72px=72px',
		content_css:'{/literal}{$smarty.const.THEME}{literal}editor_content.css',
		external_image_list_url:'{/literal}{$smarty.const.IMAGES}{literal}imagelist.js',
		plugin_insertdate_dateFormat:'%d-%m-%Y',
		plugin_insertdate_timeFormat:'%H:%M:%S',
		invalid_elements:'script,object,applet,iframe',
		extended_valid_elements:'a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]'
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
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
