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
{* This template is called from the header when HotEditor is needed        *}
{*                                                                         *}
{***************************************************************************}
{locale_load name="hoteditor"}
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

	function get_hoteditor_data(id, prefix) {ldelim}
		if (typeof id == "undefined") id = "message";
		if (typeof prefix == "undefined") prefix = "";
		setCodeOutput();
		var bbcode_output=document.getElementById("hoteditor_bbcode_ouput" + prefix + "_editor").value;//Output to BBCode
		document.getElementById(id).value = bbcode_output;
	{rdelim}
</script>
<style type='text/css'>@import url({$smarty.const.THEME}hoteditor/style.css);</style>
<script language="javascript" type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/hoteditor-4.2/editor__0001.js?version=4.2.1.1"></script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
