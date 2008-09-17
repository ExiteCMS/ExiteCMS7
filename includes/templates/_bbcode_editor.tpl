{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: _bbcode_editor.tpl                                   *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-08-26 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template is used to display the HotEditor BBcode editor            *}
{*                                                                         *}
{***************************************************************************}
<script language="javascript" type="text/javascript">

	// HotEditor KeyCode - replace this if you bought a licence!
	var Keycode ="8059051C55C81839D1E2BAA6355AC0253063E38835";

	// Editor titles
	var TitleText = "{$locale.hot001}";
	var TitleText_Textarea = "{$locale.hot002}";

	// Editor locales
	var pop_Select_Forecolor = "{$locale.hot003}";
	var pop_Select_Hilitecolor = "{$locale.hot004}";
	var pop_Select_Font = "{$locale.hot005}";
	var pop_Select_FontSize = "{$locale.hot006}";
	var pop_Select_Smile = "{$locale.hot007}";
	var pop_Select_WordArt = "{$locale.hot008}";
	var pop_Select_ClipArt = "{$locale.hot009}";
	var pop_Select_Calendar = "{$locale.hot010}";
	var pop_Select_Upload = "{$locale.hot011}";
	var pop_Insert_VK = "{$locale.hot012}";
	var pop_Insert_Moretags = "{$locale.hot013}";
	var pop_Insert_Symbol = "{$locale.hot014}";
	var safari_paste_command = "{$locale.hot015}";
	var safari_enter_text_link = "{$locale.hot016}";
	var safari_bullets_numbering_prompt = "{$locale.hot017}";
	var flash_enter_url = "{$locale.hot018}";
	var flash_width_number_text = "{$locale.hot019}";
	var flash_height_number_text = "{$locale.hot020}";
	var enter_url_text = "{$locale.hot021}";
	var enter_email_text = "{$locale.hot022}";
	var enter_image_url = "{$locale.hot023}";
	var capIESpell = "{$locale.hot024}";
	var alertNoIESpell = "{$locale.hot025|replace:"\n":"\\n"}";
	var IESpellURL = "{$locale.hot026}";
	var IESpellError = "{$locale.hot027}";
	var capDesignModeTitle = "{$locale.hot028}";
	var capFont_Name = "{$locale.hot029}";
	var capFont_Size = "{$locale.hot030}";
	var capFont_Color = "{$locale.hot031}";
	var capHighlight = "{$locale.hot032}";
	var capRemove_Format = "{$locale.hot033}";
	var capBold = "{$locale.hot034}";
	var capItalic = "{$locale.hot035}";
	var capUnderline = "{$locale.hot036}";
	var capAlign_Left = "{$locale.hot037}";
	var capCenter = "{$locale.hot038}";
	var capAlign_Right = "{$locale.hot039}";
	var capJustify = "{$locale.hot040}";
	var capBreakLine = "{$locale.hot041}";
	var capBullets = "{$locale.hot042}";
	var capNumbering = "{$locale.hot043}";
	var capDecrease_Indent = "{$locale.hot044}";
	var capIncrease_Indent = "{$locale.hot045}";
	var capDecrease_Size = "{$locale.hot046}";
	var capIncrease_Size = "{$locale.hot047}";
	var capQuote = "{$locale.hot048}";
	var capCode = "{$locale.hot049}";
	var capPHP = "{$locale.hot050}";
	var capHTML = "{$locale.hot051}";
	var capMoreTags = "{$locale.hot052}";
	var capFlash = "{$locale.hot053}";
	var capYouTube = "{$locale.hot054}";
	var promptYouTube = "{$locale.hot055}";
	var URLDefaultYouTube = "{$locale.hot056}";
	var capGoogle = "{$locale.hot057}";
	var promptGoogle = "{$locale.hot058}";
	var URLDefaultGoogle = "{$locale.hot059}";
	var capYahoo = "{$locale.hot060}";
	var promptYahoo = "{$locale.hot061}";
	var URLDefaultYahoo = "{$locale.hot062}";
	var capTable = "{$locale.hot063}";
	var capCut = "{$locale.hot064}";
	var capCopy = "{$locale.hot065}";
	var capPaste = "{$locale.hot066}";
	var capUndo = "{$locale.hot067}";
	var capRedo = "{$locale.hot068}";
	var capHyperlink = "{$locale.hot069}";
	var capHyperlink_Email = "{$locale.hot070}";
	var capRemovelink = "{$locale.hot071}";
	var capCalendar = "{$locale.hot072}";
	var capInsert_Image = "{$locale.hot073}";
	var capClipart = "{$locale.hot074}";
	var capWordArt = "{$locale.hot075}";
	var capEmotions = "{$locale.hot076}";
	var capUpload = "{$locale.hot077}";
	var capStrikethrough = "{$locale.hot078}";
	var capSubscript = "{$locale.hot079}";
	var capSuperscript = "{$locale.hot080}";
	var capHorizontal = "{$locale.hot081}";
	var capSymbol = "{$locale.hot082}";
	var capVirtualKeyboard = "{$locale.hot083}";
	var capViewHTML = "{$locale.hot084}";
	var capDelete_All = "{$locale.hot085}";
	var capOnOff_RichText = "{$locale.hot086}";
	var dropdownFonts = "{$locale.hot087}";
	var dropdownSize = "{$locale.hot088}";
	var dropdownColor = "{$locale.hot089}";
	var dropdownHighlight = "{$locale.hot090}";

	// paths
	var hoteditor_path = "{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/";
	var hoteditor_theme_path = "{$smarty.const.THEME}hoteditor";

	// encoding
	var iframe_encoding = "{$settings.charset}";

	// author name used in [quote=author]
	var hoteditor_reply_to = "{$author|default:""}";
</script>
<style type='text/css'>@import url({$smarty.const.THEME}hoteditor/style.css);</style>
<input type='hidden' id='{$id|default:"message"}' name='{$name|default:"message"}' value='{$message|default:""}' />
<script language="javascript" type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/editor__0002.js?version=4.2"></script>
<script language="javascript" type="text/javascript">
	var getdata = document.getElementById("{$id|default:"message"}").value;
	Instantiate("max","editor", getdata , "{$width|default:"100%"}", "{$height|default:"150px"}");
	{literal}
	//For Vietnamese User. Edit file editor.js to enable vietnamese keyboard
	if(enable_vietnamese_keyboard==1) {
		document.write("<script language=\"JavaScript\" type=\"text/javascript\" src={/literal}{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/avim.js{literal}><\/script>");
		var hoteditor_avim_method = hot_readCookie("hoteditor_avim_method");var him_auto_checked="";var him_telex_checked="";var him_vni_checked="";var him_viqr_checked="";var him_viqr2_checked="";var him_off_checked="";if(hoteditor_avim_method=="0"){him_auto_checked="checked";}else if(hoteditor_avim_method=="1"){him_telex_checked="checked";}else if(hoteditor_avim_method=="2"){him_vni_checked="checked";}else if(hoteditor_avim_method=="3"){him_viqr_checked="checked";}else if(hoteditor_avim_method=="4"){him_viqr2_checked="checked";}else if(hoteditor_avim_method=="-1"){him_off_checked="checked";}
		document.write("<div style='width:100%;text-align:center;font-family:Verdana;font-size:11px;'><input "+him_auto_checked+" id=him_auto onclick=setMethod(0); type=radio name=viet_method> Auto :: <input "+him_telex_checked+" id=him_telex onclick=setMethod(1); type=radio name=viet_method> Telex :: <input "+him_vni_checked+" id=him_vni onclick=setMethod(2); type=radio name=viet_method> VNI :: <input "+him_viqr_checked+" id=him_viqr onclick=setMethod(3); type=radio name=viet_method> VIQR :: <input "+him_viqr2_checked+" id=him_viqr2 onclick=setMethod(4); type=radio name=viet_method> VIQR* :: <input "+him_off_checked+" id=him_off onclick=setMethod(-1); type=radio name=viet_method> Off<br><img src="+styles_folder_path+"/vietnamese_symbol.gif></div>");
	}
	if(enable_vietnamese_keyboard==1) {
		var hoteditor_avim_method = hot_readCookie("hoteditor_avim_method");var him_auto_checked;var him_telex_checked;var him_vni_checked;var him_viqr_checked;var him_viqr2_checked;var him_off_checked;if(hoteditor_avim_method=="0"){him_auto_checked="checked";}else if(hoteditor_avim_method=="1"){him_telex_checked="checked";}else if(hoteditor_avim_method=="2"){him_vni_checked="checked";}else if(hoteditor_avim_method=="3"){him_viqr_checked="checked";}else if(hoteditor_avim_method=="4"){him_viqr2_checked="checked";}else if(hoteditor_avim_method=="-1"){him_off_checked="checked";}
		document.write("<script language=\"JavaScript\" type=\"text/javascript\" src={/literal}{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/avim.js{literal}><\/script><div style='width:100%;text-align:center;font-family:Verdana;font-size:11px;'><input "+him_auto_checked+" id=him_auto onclick=setMethod(0); type=radio name=viet_method> Auto :: <input "+him_telex_checked+" id=him_telex onclick=setMethod(1); type=radio name=viet_method> Telex :: <input "+him_vni_checked+" id=him_vni onclick=setMethod(2); type=radio name=viet_method> VNI :: <input "+him_viqr_checked+" id=him_viqr onclick=setMethod(3); type=radio name=viet_method> VIQR :: <input "+him_viqr2_checked+" id=him_viqr2 onclick=setMethod(4); type=radio name=viet_method> VIQR* :: <input "+him_off_checked+" id=him_off onclick=setMethod(-1); type=radio name=viet_method> Off</div>");
	}
	function get_hoteditor_data() {
		setCodeOutput();
		var bbcode_output=document.getElementById("hoteditor_bbcode_ouput_editor").value;//Output to BBCode
		document.getElementById("{/literal}{$id|default:"message"}{literal}").value = bbcode_output;
	}					
	{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
