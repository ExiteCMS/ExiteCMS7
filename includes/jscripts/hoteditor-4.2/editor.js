/*
+--------------------------------------------------------------------------
|
|	WARNING: REMOVING THIS COPYRIGHT HEADER IS EXPRESSLY FORBIDDEN
|
|   Rich Text Editor Version 4.2 (June 30, 2007) Working with Safari 1.3.2 or higher
|   ========================================
|   by Khoi Hong webmaster@cgi2k.com
|   (c) 1999 - 2007 CGI2K.COM - All right reserved 
|   http://www.cgi2k.com 
|   ========================================
|   Web: http://www.ecardmax.com
|   Email: webmaster@cgi2k.com
|   Purchase Info: http://www.ecardmax.com/index.php?step=Purchase
|   Support: http://www.ecardmax.com/index.php?step=Support
|
|	HotEditor homepage: http://www.ecardmax.com/index.php?step=Hoteditor 
|
|
|   > Script file name: editor.js
|   > Script written by Khoi Hong
|	
|	WARNING //--------------------------
|
|	Selling the code for this program without prior written consent is expressly forbidden. 
|	This computer program is protected by copyright law. 
|	Unauthorized reproduction or distribution of this program, or any portion of if,
|	may result in severe civil and criminal penalties and will be prosecuted to 
|	the maximum extent possible under the law.
|	
|	NOTE //--------------------------
|	
|	The ExiteCMS team decoded the original obfusicated code in order to make changes to
|	the way some BBcodes were parsed. We didn't tamper with the copyright en credit code
|	in any way. We hope this doesn't violate the author's wishes, as the licencing
|   conditions of the free version aren't exactly clear. 
|
|	NOTE //--------------------------
|
|   This version of editor.js has been modified by the ExiteCMS team:
|   - new wrapSelection() function that can handle scrolling in textarea's
|   - made sure all BBcode tags are in lower case
|   - changed relative font sizes (1-7) to absolute size in pixels
|   - make sure the script doesn't touch input in [code], [php] and [html] blocks
|   - added test to see  toolbars and text strings are already defined
|     (allows override of default settings by the calling ExiteCMS template)
|   - added option to define a keycode in the calling ExiteCMS template
|     so that a CMS user can buy one and doesn't have to change this script
|
+--------------------------------------------------------------------------
*/

// HotEditor free-use notice
var Credit ="Rich Text Editor by www.eCardMax.com";
if (Keycode == null) var Keycode ="8059051C55C81839D1E2BAA6355AC0253063E38835";

// ExiteCMS configuration
var enable_vietnamese_keyboard = 0;
var vietnamese_keyboard_default = 0;
var use_RichText = "1";
var show_switch = "1";
var show_mode_editor = "1";
var show_arrow_up_down = 1;
var mydirection = "ltr"; 
var vk_main = "";

// Path definitions
var styles_folder_path = hoteditor_theme_path;
var smiles_path = hoteditor_path + "show_it.php?what=smiley&cat=";
var wordarts_path = "";
var cliparts_path = "";
var celendar_path = "";
var VirtualKeyboard_path = "";
var upload_path = "";

//---------------- FOR WYSIWYG EDITOR (BBCODE EDITOR) ----------------

if (TitleText == null) var TitleText = "Rich Text Editor";
var iframe_meta_tag = "<meta http-equiv='Content-Type' content='text/html; charset=" + iframe_encoding + "'>\n";
var iframe_style = "BODY{font-family:Verdana,Arial,Sans-Serif,Tahoma;font-size:12px;color: black;}";
iframe_style += "TABLE{border-collapse: collapse;border-spacing: 0px;border: 1px solid #6CAFF7;background-color: #F4F4F4;font-family:Verdana,Arial,Sans-Serif,Tahoma;font-size:12px;color: black;}";
iframe_style += "TD{height:25px; border: 1px solid #6CAFF7}";
var iframe_image_background="<body background=" + styles_folder_path + "/iframe_background.gif" + " style=\"background-attachment: fixed; background-repeat: repeat;\">";

var show_custom_bbcode_bar=0;
var array_toolbar_user_custom=new Array();

if (toolbar1 == null) {
	var toolbar1 ="SPACE,btFont_Name,btFont_Size,btFont_Color,btHighlight,btRemove_Format,SPACE,btBold,btItalic,btUnderline";
}
if (toolbar2 == null) {
	var toolbar2 ="SPACE,btAlign_Left,btCenter,btAlign_Right,btJustify,SPACE,btCut,btCopy,btPaste,btUndo,SPACE,btStrikethrough,btSubscript,btSuperscript,btHorizontal,SPACE,btBullets,btNumbering,btIncrease_Indent";
}
if (toolbar3 == null) {
	var toolbar3 ="SPACE,btHyperlink,btHyperlink_Email,btInsert_Image,btEmotions,btYouTube,SPACE,btQuote,btCode";
}
if (minibar == null) {
	var minibar ="SPACE,btFont_Name,btFont_Color,btHighlight,SPACE,btBold,btItalic,btUnderline,SPACE,btHyperlink,btEmotions,SPACE,btQuote,btCode";
}

// Setup Popup layer Width & Height & Title bar here (for WYSIWYG Editor)
var forecolor_frame_width =235;		var forecolor_frame_height =185;	if (pop_Select_Forecolor == null) var pop_Select_Forecolor ="Font Color";
var hilitecolor_frame_width =165;	var hilitecolor_frame_height =110;	if (pop_Select_Hilitecolor == null) var pop_Select_Hilitecolor ="Text Highlight Color";
var fontname_frame_width =205;		var fontname_frame_height =300;		if (pop_Select_Font == null) var pop_Select_Font ="Font Face";
var fontsize_frame_width =80;		var fontsize_frame_height =249;		if (pop_Select_FontSize == null) var pop_Select_FontSize ="Font Size";
var simley_frame_width =370;		var simley_frame_height =340;		if (pop_Select_Smile == null) var pop_Select_Smile ="Insert your emotions to document";
var wordart_frame_width =370;		var wordart_frame_height =340;		if (pop_Select_WordArt == null) var pop_Select_WordArt ="Insert WordArt to document";
var clipart_frame_width =370;		var clipart_frame_height =340;		if (pop_Select_ClipArt == null) var pop_Select_ClipArt ="Insert ClipArt to document";
var calendar_frame_width =330;		var calendar_frame_height =350;		if (pop_Select_Calendar == null) var pop_Select_Calendar ="View Calendar / World Clock";
var upload_frame_width =385;		var upload_frame_height =250;		if (pop_Select_Upload == null) var pop_Select_Upload ="Upload your image files";
var vk_frame_width =520;			var vk_frame_height =250;			if (pop_Insert_VK == null) var pop_Insert_VK ="Virtual Keyboard";		
var moretags_frame_width =190;		var moretags_frame_height =150;		if (pop_Insert_Moretags == null) var pop_Insert_Moretags="Insert Forum Tags";
var symbol_frame_width =382;		var symbol_frame_height =300;		if (pop_Insert_Symbol == null) var pop_Insert_Symbol ="Insert Symbol - Special characters";

//---------------- FOR BBCODE EDITOR ----------------

if (TitleText_Textarea == null) var TitleText_Textarea ="BBCode Editor";

if (textarea_toolbar1 == null) {
	var textarea_toolbar1 ="SPACE,btFont_Name,btFont_Size,btFont_Color,btHighlight,btRemove_Format,SPACE,btBold,btItalic,btUnderline";
}
if (textarea_toolbar2 == null) {
	var textarea_toolbar2 ="SPACE,btAlign_Left,btCenter,btAlign_Right,btJustify,SPACE,btCut,btCopy,btPaste,SPACE,btStrikethrough,btSubscript,btSuperscript,btHorizontal,SPACE,btBullets,btNumbering,btIncrease_Indent";
}
if (textarea_toolbar3 == null) {
	var textarea_toolbar3 ="SPACE,btHyperlink,btHyperlink_Email,btInsert_Image,btEmotions,btYouTube,SPACE,btQuote,btCode";
}
if (textarea_minibar == null) {
	var textarea_minibar ="SPACE,btFont_Name,btFont_Color,btHighlight,SPACE,btBold,btItalic,btUnderline,SPACE,btHyperlink,btEmotions,SPACE,btQuote,btCode";
}

var array_fontname = new Array();
array_fontname[0] ="Arial";
array_fontname[1] ="Arial Black";
array_fontname[2] ="Arial Narrow";
array_fontname[3] ="Book Antiqua";
array_fontname[4] ="Century Gothic";
array_fontname[5] ="Comic Sans MS";
array_fontname[6] ="Courier New";
array_fontname[7] ="Fixedsys";
array_fontname[8] ="Franklin Gothic Medium";
array_fontname[9] ="Garamond";
array_fontname[10] ="Georgia";
array_fontname[11] ="Impact";
array_fontname[12] ="Lucida Console";
array_fontname[13] ="Lucida Sans Unicode";
array_fontname[14] ="Microsoft Sans Serif";
array_fontname[15] ="Palatino Linotype";
array_fontname[16] ="System";
array_fontname[17] ="Tahoma";
array_fontname[18] ="Times New Roman";
array_fontname[19] ="Trebuchet MS";
array_fontname[20] ="Verdana";
array_fontname[21] ="Wingdings";

var array_fontcolor = new Array();
array_fontcolor[0]	="#FFFFFF";array_fontcolor[6] ="#000000";array_fontcolor[12] ="#EEECE1";array_fontcolor[18] ="#1F497D";array_fontcolor[24] ="#4F81BD";array_fontcolor[30] ="#C0504D";array_fontcolor[36] ="#9BBB59";array_fontcolor[42] ="#8064A2";array_fontcolor[48] ="#4BACC6";array_fontcolor[54] ="#F79646";
array_fontcolor[1]	="#F2F2F2";array_fontcolor[7] ="#7F7F7F";array_fontcolor[13] ="#DDD9C3";array_fontcolor[19] ="#C6D9F0";array_fontcolor[25] ="#DBE5F1";array_fontcolor[31] ="#F2DCDB";array_fontcolor[37] ="#EBF1DD";array_fontcolor[43] ="#E5E0EC";array_fontcolor[49] ="#DBEEF3";array_fontcolor[55] ="#FDEADA";
array_fontcolor[2]	="#D8D8D8";array_fontcolor[8] ="#595959";array_fontcolor[14] ="#C4BD97";array_fontcolor[20] ="#8DB3E2";array_fontcolor[26] ="#B8CCE4";array_fontcolor[32] ="#E5B9B7";array_fontcolor[38] ="#D7E3BC";array_fontcolor[44] ="#CCC1D9";array_fontcolor[50] ="#B7DDE8";array_fontcolor[56] ="#FBD5B5";
array_fontcolor[3]	="#BFBFBF";array_fontcolor[9] ="#3F3F3F";array_fontcolor[15] ="#938953";array_fontcolor[21] ="#548DD4";array_fontcolor[27] ="#95B3D7";array_fontcolor[33] ="#D99694";array_fontcolor[39] ="#C3D69B";array_fontcolor[45] ="#B2A2C7";array_fontcolor[51] ="#92CDDC";array_fontcolor[57] ="#FAC08F";
array_fontcolor[4]	="#A5A5A5";array_fontcolor[10] ="#262626";array_fontcolor[16] ="#494429";array_fontcolor[22] ="#17365D";array_fontcolor[28] ="#366092";array_fontcolor[34] ="#953734";array_fontcolor[40] ="#76923C";array_fontcolor[46] ="#5F497A";array_fontcolor[52] ="#31859B";array_fontcolor[58] ="#E36C09";
array_fontcolor[5]	="#7F7F7F";array_fontcolor[11] ="#0C0C0C";array_fontcolor[17] ="#1D1B10";array_fontcolor[23] ="#0F243E";array_fontcolor[29] ="#244061";array_fontcolor[35] ="#632423";array_fontcolor[41] ="#4F6128";array_fontcolor[47] ="#3F3151";array_fontcolor[53] ="#205867";array_fontcolor[59] ="#974806";
array_fontcolor[60] ="#C00000";array_fontcolor[61] ="#FF0000";array_fontcolor[62] ="#FFC000";array_fontcolor[63] ="#FFFF00";array_fontcolor[64] ="#92D050";array_fontcolor[65] ="#00B050";array_fontcolor[66] ="#00B0F0";array_fontcolor[67] ="#0070C0";array_fontcolor[68] ="#002060";array_fontcolor[69] ="#7030A0";

var flash_width_number_default=425;
var flash_height_number_default=350;

// Hoteditor code - [ no more changes from here! ]---------------------------

var bbNumbering = "list=1,*";
var bbBullets = "list,*";
var bbFlash = "flash";
var starup = 0;
var isRichText = false;
var rng;
var currentRTE;
var currentTEXT;
var allRTEs = "";
var isIE;
var isIE_Mac;
var isGecko;
var isOpera9;
var isSafari;
var isSafari3;
var isKonqueror;
var isICab;
var isMacOS;
var HTML_ON;
var chkViewHTML;
var chkVK = 0;
var editor_size;
var editor_cookie;
var ImgSwitch = "";
var print_dir = "";
if (mydirection == "rtl") {
	print_dir = " dir=rtl ";
} else {
	print_dir = " dir=ltr ";
}
var currentwindow = "";
var currenteditor = "";
var editor_type;
var ua = navigator.userAgent.toLowerCase();
isIE = ua.indexOf("msie") != -1 && ua.indexOf("opera") == -1 && ua.indexOf("webtv") == -1;
isGecko = ua.indexOf("gecko") != -1 && ua.indexOf("safari") == -1;
isOpera9 = ua.indexOf("opera") != -1 && ua.indexOf("safari") == -1;
isSafari = ua.indexOf("gecko") != -1 && ua.indexOf("safari") != -1 && ua.indexOf("version/3") == -1;
isSafari3 = ua.indexOf("gecko") != -1 && ua.indexOf("safari") != -1 && ua.indexOf("version/3") != -1;
isKonqueror = ua.indexOf("konqueror") != -1;
isICab = ua.indexOf("icab") != -1;
isIE_Mac = ua.indexOf("msie") != -1 && ua.indexOf("mac") != -1;
isMacOS = ua.indexOf("macintosh") != -1;
if (document.getElementById && document.designMode && !isKonqueror && !isIE_Mac) {
	isRichText = true;
}

function Instantiate(a, b, c, d, e, f, g) {
	starup = 1;
	editor_size = parseInt(e);
	f = true;
	g = false;
	if (use_RichText == "0") {
		isRichText = false;
	}
	if (isRichText) {
		if (allRTEs.length > 0) {
			allRTEs += ";";
		}
		allRTEs += b;
		editor_cookie = hot_readCookie("hoteditor_cookie");
		if (editor_cookie == "1") {
			ImgSwitch = "switch_richtext_on.gif";
			editor_type = "1";
			c = BBCodeToHTML(c);
		} else if (editor_cookie == "0") {
			ImgSwitch = "switch_richtext_off.gif";
			editor_type = "0";
			c = HTMLToBBCode(c);
		} else {
			if (show_mode_editor == "1") {
				ImgSwitch = "switch_richtext_on.gif";
				editor_type = "1";
				c = BBCodeToHTML(c);
			} else {
				ImgSwitch = "switch_richtext_off.gif";
				editor_type = "0";
				c = HTMLToBBCode(c);
			}
		}
	} else {
		if (allRTEs.length > 0) {
			allRTEs += ";";
		}
		allRTEs += b;
		show_switch = "0";
		ImgSwitch = "switch_richtext_off.gif";
		editor_type = "0";
		c = HTMLToBBCode(c);
	}
	writeRTE(a, b, c, d, e, f, g);
}


function enableDesignMode(a, b, c) {
	b = b.replace(/&amp;#/gi, "&#");
	var d = "<html " + print_dir + " id=\"" + a + "\">\n";
	d += "<head>\n" + iframe_meta_tag + "\n<style><!--p { margin-top: 0px; margin-bottom: 0px; } " + iframe_style + " --></style>\n</head>\n";
	d += iframe_image_background;
	d += b + "\n";
	d += "</body>\n";
	d += "</html>";
	if (document.all) {
		var f = frames[a].document;
		f.open();
		f.write(d);
		f.close();
		if (!c) {
			f.designMode = "On";
		}
	} else {
		try {
			if (!c) {
				document.getElementById(a).contentDocument.designMode = "on";
			}
			try {
				var f = document.getElementById(a).contentWindow.document;
				f.open();
				if (isGecko || isSafari) {
					f.write(d + "<br><br>");
				} else {
					f.write(d);
				}
				f.close();
				if (isGecko && !c) {
					f.addEventListener("keypress", kb_handler, true);
				}
			} catch (e) {
				alert("Error preloading content.");
			}
		} catch (e) {
			if (isGecko) {
				setTimeout("enableDesignMode('" + a + "', '" + b + "');", 10);
			} else {
				return false;
			}
		}
	}
}


function setCodeOutput() {
	var a = allRTEs.split(";");
	for (var i = 0; i < a.length; i++) {
		updateRTE(a[i]);
	}
}


function updateRTE(a) {
	starup = 0;
	if (isKonqueror || isICab || isIE_Mac || use_RichText == "0") {
		document.getElementById("hoteditor_bbcode_ouput_" + a).value = document.getElementById("textarea_" + a).value;
		document.getElementById("hoteditor_html_ouput_" + a).value = BBCodeToHTML(document.getElementById("textarea_" + a).value);
	} else if (editor_type == "1") {
		if (HTML_ON == "no") {
			document.getElementById("chkSrc" + a).checked = false;
			toggleHTMLSrc(a);
		}
		var b = document.getElementById(a).contentWindow.document.body.innerHTML;
		b = b.replace(/<div><\/div>/gi, "");
		b = b.replace(/<br[^>]*>/gi, "<br>");
		b = b.replace(/[\n\r]/gi, "");
		b = HTMLToBBCode(b);
		b = b.replace(/\[table\]\n+/gi, "[table]");
		b = b.replace(/\n+\[td\]/gi, "[td]");
		b = b.replace(/\n+\[\/table\]/gi, "[/table]");
		b = b.replace(/\n+\[\/td\]/gi, "[/td]");
		b = b.replace(/\n+\[tr\]/gi, "[tr]");
		b = b.replace(/\n+\[\/tr\]/gi, "[/tr]");
		document.getElementById("hoteditor_bbcode_ouput_" + a).value = b;
		document.getElementById("hoteditor_html_ouput_" + a).value = BBCodeToHTML(b);
	} else if (editor_type == "0") {
		var b = document.getElementById("textarea_" + a).value;
		b = BBCodeToHTML(b);
		b = b.replace(/[\r\n]/gi, "");
		b = b.replace(/<br><(div|ul|ol)/gi, "<$1");
		document.getElementById(a).contentWindow.document.body.innerHTML = b;
		b = document.getElementById(a).contentWindow.document.body.innerHTML;
		b = b.replace(/[\r\n]/gi, "");
		document.getElementById("hoteditor_html_ouput_" + a).value = b;
		b = HTMLToBBCode(b);
		b = b.replace(/\[table\]\n+/gi, "[table]");
		b = b.replace(/\n+\[td\]/gi, "[td]");
		b = b.replace(/\n+\[\/table\]/gi, "[/table]");
		b = b.replace(/\n+\[\/td\]/gi, "[/td]");
		b = b.replace(/\n+\[tr\]/gi, "[tr]");
		b = b.replace(/\n+\[\/tr\]/gi, "[/tr]");
		document.getElementById("hoteditor_bbcode_ouput_" + a).value = b;
	}
}


function toggleHTMLSrc(a) {
	var b;
	if (isIE) {
		b = frames[a].document;
	} else {
		b = document.getElementById(a).contentWindow.document;
	}
	if (document.getElementById("chkSrc" + a).checked) {
		HTML_ON = "no";
		if (isIE) {
			b.body.innerText = b.body.innerHTML;
		} else {
			var c = b.createTextNode(b.body.innerHTML);
			b.body.innerHTML = "";
			b.body.appendChild(c);
		}
	} else {
		HTML_ON = "yes";
		if (isIE) {
			b.body.innerHTML = b.body.innerText;
		} else {
			var c = b.body.ownerDocument.createRange();
			c.selectNodeContents(b.body);
			b.body.innerHTML = c.toString();
		}
	}
}


function switch_editor(a) {
	if (HTML_ON == "no") {
		alert("Please uncheck the HTML checkbox");
		return false;
	}
	starup = 0;
	var b = editor_size + 70;
	currenteditor = a;
	if (editor_type == "1") {
		document.getElementById("textarea_" + a).value = "";
		editor_type = "0";
		document.getElementById("editor_switch" + a).src = styles_folder_path + "/" + "switch_richtext_off.gif";
		document.getElementById("switch_span" + a).className = "Hoteditor_DesignModeOff_TextColor";
		document.getElementById("change_title_editor" + a).innerHTML = TitleText_Textarea;
		var c = document.getElementById(a).contentWindow.document.body.innerHTML;
		c = c.replace(/[\n\r]/gi, "");
		hot_createCookie("hoteditor_cookie", "0", 365);
		c = HTMLToBBCode(c);
		if (isMacOS && isGecko) {
			hot_showid2("hoteditor_richtool" + a, "none");
			hot_showid2("hoteditor_texttool" + a, "block");
			document.getElementById(a).style.height = "0px";
			document.getElementById(a).style.width = "0px";
			document.getElementById("textarea_" + a).style.height = b + "px";
			document.getElementById("textarea_" + a).style.width = "98%";
		} else if (isSafari) {
			document.getElementById("hoteditor_richtool" + a).style.height = "0px";
			document.getElementById("hoteditor_richtool" + a).style.visibility = "hidden";
			hot_showid2("hoteditor_texttool" + a, "block");
		} else {
			hot_showid2("hoteditor_richtool" + a, "none");
			hot_showid2("hoteditor_texttool" + a, "block");
		}
		document.getElementById("textarea_" + a).value = c;
	} else {
		if (isMacOS && isGecko) {
			hot_showid2("hoteditor_richtool" + a, "block");
			hot_showid2("hoteditor_texttool" + a, "none");
			document.getElementById("textarea_" + a).style.height = "0px";
			document.getElementById("textarea_" + a).style.width = "0px";
			document.getElementById(a).style.height = b + "px";
			document.getElementById(a).style.width = "98%";
		} else if (isSafari) {
			document.getElementById("hoteditor_richtool" + a).style.visibility = "visible";
			document.getElementById("hoteditor_richtool" + a).style.height = b + "px";
			hot_showid2("hoteditor_texttool" + a, "none");
		} else {
			hot_showid2("hoteditor_richtool" + a, "block");
			hot_showid2("hoteditor_texttool" + a, "none");
		}
		if (isIE) {
			oRTE = frames[a];
		} else {
			oRTE = document.getElementById(a).contentWindow;
		}
		editor_type = "1";
		var d = document.getElementById("textarea_" + a).value;
		var e = BBCodeToHTML(d);
		hot_createCookie("hoteditor_cookie", "1", 365);
		oRTE.document.body.innerHTML = e;
		document.getElementById("editor_switch" + a).src = styles_folder_path + "/" + "switch_richtext_on.gif";
		document.getElementById("switch_span" + a).className = "Hoteditor_DesignModeOn_TextColor";
		document.getElementById("change_title_editor" + a).innerHTML = TitleText;
	}
}


function resize_editor(a, b, c) {
	currenteditor = b;
	if (a == "decrease_size") {
		if (isKonqueror) {
			var d = parseInt(document.getElementById(c).style.height);
			if (d > editor_size) {
				document.getElementById(c).style.height = d - 50 + "px";
			}
			return false;
		}
		if (editor_type == "1") {
			var d = parseInt(document.getElementById(b).style.height);
			if (d > editor_size) {
				document.getElementById(b).style.height = d - 50 + "px";
			}
		} else {
			var d = parseInt(document.getElementById(c).style.height);
			if (d > editor_size) {
				document.getElementById(c).style.height = d - 50 + "px";
			}
		}
	} else if (a == "increase_size") {
		if (isKonqueror) {
			var d = parseInt(document.getElementById(c).style.height);
			document.getElementById(c).style.height = d + 50 + "px";
			return false;
		}
		if (editor_type == "1") {
			var d = parseInt(document.getElementById(b).style.height);
			document.getElementById(b).style.height = d + 50 + "px";
		} else {
			var d = parseInt(document.getElementById(c).style.height);
			document.getElementById(c).style.height = d + 50 + "px";
		}
	}
}


function autoresize(b, c) {
	return;
	while (document.getElementById(c).clientHeight < document.getElementById(c).scrollHeight) {
		resize_editor('increase_size', b, c);
	}
}

function writeRTE(a, b, c, d, e, f, g) {
	if (Credit != "Rich Text Editor by www.eCardMax.com") {
		alert("Please do not remove or modify the Credit 'Rich Text Editor by www.eCardMax.com' inside the script editor.js");
		return false;
	}
	document.write("<table class=Hoteditor_Main_Border width=" + d + " cellspacing=0 cellpadding=0>\n");
	document.write("<tr><td><table width=100% class=Hoteditor_TitleBar><tr>\n");
	document.write("<td nowrap>\n");
	if (isRichText && use_RichText == "1" && show_switch == "1") {
		document.write("<span onclick=\"switch_editor('" + b + "');\" title='" + capOnOff_RichText + "' style=\"float:right;cursor:pointer;\" id=switch_span" + b + ">" + capDesignModeTitle + " <img id=editor_switch" + b + " align=absmiddle border=0 src=" + styles_folder_path + "/" + ImgSwitch + "></span>");
	} else {
		document.write("<span title='' id=switch_span" + b + "></span>");
	}
	if (editor_type == "1") {
		document.write("<img align=absmiddle border=0 src=" + styles_folder_path + "/logo.gif> <span id=change_title_editor" + b + ">" + TitleText + "</span></td>\n");
	} else {
		document.write("<img align=absmiddle border=0 src=" + styles_folder_path + "/logo.gif> <span id=change_title_editor" + b + ">" + TitleText_Textarea + "</span></td>\n");
	}
	if (show_arrow_up_down == 1) {
		document.write("<td width=1% nowrap align=right><div><img title=\"" + capDecrease_Size + "\" class=Hoteditor_Button style=\"cursor:pointer\" onmouseover=\"this.className='Hoteditor_Button_Over';\" onmouseout=\"this.className='Hoteditor_Button_Out';\" onclick=\"resize_editor('decrease_size','" + b + "','" + "textarea_" + b + "');\" border=0 src=" + styles_folder_path + "/arrow_up.gif></div><div><img title=\"" + capIncrease_Size + "\" class=Hoteditor_Button style=\"cursor:pointer\" onmouseover=\"this.className='Hoteditor_Button_Over';\" onmouseout=\"this.className='Hoteditor_Button_Out';\" onclick=\"resize_editor('increase_size','" + b + "','" + "textarea_" + b + "');\" border=0 src=" + styles_folder_path + "/arrow_dn.gif></div></td>\n");
	}
	document.write("</tr></table></td></tr>\n");
	document.write("<tr><td nowrap>\n");
	if (isRichText) {
		document.write("<div id=hoteditor_richtool" + b + ">\n");
		document.write("<table width=100% cellspacing=0 cellpadding=0>\n");
		if (toolbar1 != "" && a != "min") {
			document.write("<tr><td nowrap>\n");
			array = toolbar1.split(",");
			for (i = 0; i <= array.length; i++) {
				if (array[i]) {
					show_toolbar(array[i], b);
				}
			}
			document.write("</td></tr>\n");
		}
		if (toolbar2 != "" && a != "min") {
			document.write("<tr><td nowrap>\n");
			array = toolbar2.split(",");
			for (i = 0; i <= array.length; i++) {
				if (array[i]) {
					show_toolbar(array[i], b);
				}
			}
			document.write("</td></tr>\n");
		}
		if (toolbar3 != "" && a != "min") {
			document.write("<tr><td nowrap>\n");
			array = toolbar3.split(",");
			for (i = 0; i <= array.length; i++) {
				if (array[i]) {
					show_toolbar(array[i], b);
				}
			}
			document.write("</td></tr>\n");
		}
		if (show_custom_bbcode_bar == "1" && a != "min" && !isSafari) {
			document.write("<tr><td nowrap>\n");
			for (i = 0; i <= array_toolbar_user_custom.length; i++) {
				if (array_toolbar_user_custom[i]) {
					show_custom_toolbar(array_toolbar_user_custom[i], b);
				}
			}
			document.write("</td></tr>\n");
		}
		if (minibar != "" && a == "min") {
			document.write("<tr><td nowrap>\n");
			array = minibar.split(",");
			for (i = 0; i <= array.length; i++) {
				if (array[i]) {
					show_toolbar(array[i], b);
				}
			}
			document.write("</td></tr>\n");
		}
		document.write("</table>\n");
		if (isMacOS && isGecko) {
			document.write("</div>\n");
		}
		document.write("<center><iframe style='width:98%;height:" + e + ";background-color:white' frameborder=0 class=Hoteditor_iFrame id='" + b + "' name='" + b + "'></iframe></center>\n");
		if (isMacOS && isGecko) {
			var h = "";
		} else {
			document.write("</div>\n");
		}
	}
	document.write("<div id=hoteditor_texttool" + b + ">\n");
	document.write("<table width=100% cellspacing=0 cellpadding=0>\n");
	if (textarea_toolbar1 != "" && a != "min") {
		document.write("<tr><td nowrap>\n");
		array = textarea_toolbar1.split(",");
		for (i = 0; i <= array.length; i++) {
			if (array[i]) {
				show_toolbar_textarea(array[i], "textarea_" + b);
			}
		}
		document.write("</td></tr>\n");
	}
	if (textarea_toolbar2 != "" && a != "min") {
		document.write("<tr><td nowrap>\n");
		array = textarea_toolbar2.split(",");
		for (i = 0; i <= array.length; i++) {
			if (array[i]) {
				show_toolbar_textarea(array[i], "textarea_" + b);
			}
		}
		document.write("</td></tr>\n");
	}
	if (textarea_toolbar3 != "" && a != "min") {
		document.write("<tr><td nowrap>\n");
		array = textarea_toolbar3.split(",");
		for (i = 0; i <= array.length; i++) {
			if (array[i]) {
				show_toolbar_textarea(array[i], "textarea_" + b);
			}
		}
		document.write("</td></tr>\n");
	}
	if (show_custom_bbcode_bar == "1" && a != "min") {
		document.write("<tr><td nowrap>\n");
		for (i = 0; i <= array_toolbar_user_custom.length; i++) {
			if (array_toolbar_user_custom[i]) {
				show_custom_toolbar_bbcode(array_toolbar_user_custom[i], b);
			}
		}
		document.write("</td></tr>\n");
	}
	if (textarea_minibar != "" && a == "min") {
		document.write("<tr><td nowrap>\n");
		array = textarea_minibar.split(",");
		for (i = 0; i <= array.length; i++) {
			if (array[i]) {
				show_toolbar_textarea(array[i], "textarea_" + b);
			}
		}
		document.write("</td></tr>\n");
	}
	document.write("</table>\n");
	if (isMacOS && isGecko) {
		document.write("</div>\n");
	}
	var j = c.replace(/<br>/gi, "\n");
	j = j.replace(/&lt;/g, "<");
	j = j.replace(/&gt;/g, ">");
	j = j.replace(/&amp;#/gi, "&#");
	j = j.replace(/\[\/tr\]/gi, "\n[/tr]");
	j = j.replace(/\[tr\]/gi, "\n[tr]");
	j = j.replace(/\[td\]/gi, "\n[td]");
	j = j.replace(/\[\/table\]/gi, "\n[/table]");
	j = j.replace(/\[\/table\]$/gi, "[/table]\n");
	document.write("<center><textarea wrap=auto " + print_dir + " style='font-family:Verdana,Arial,Sans-Serif,Tahoma;font-size:12px;color: black;width:98%;height:" + e + "' class=Hoteditor_iTextarea id='textarea_" + b + "' onkeyup='autoresize(\"" + b + "\",\"" + "textarea_" + b + "\");' name='textarea_" + b + "'>" + j + "</textarea></center>\n");
	if (isMacOS && isGecko) {
		var h = "";
	} else {
		document.write("</div>\n");
	}
	document.write("</td></tr>\n");
	var k = document.URL;
	k = k.toLowerCase();
	k = k.replace(/http:/g, "");
	k = k.replace(/www./g, "");
	k = k.split("/");
	k = k[2];
	var l = Keycode;
	l = l.substr(5, l.length - 10);
	enc_str_leng = Math.round(l.length / 3);
	center = l.substr(enc_str_leng, enc_str_leng);
	l = l.replace(center, "");
	l = center + l;
	var m = "";
	var n = "hongthienkhoihoangthihienhongduytoan";
	for (var i = 0; i < n.length; i++) {
		m += n.charCodeAt(i).toString();
	}
	var o = Math.floor(m.length / 5);
	var p = parseInt(m.charAt(o) + m.charAt(o * 2) + m.charAt(o * 3) + m.charAt(o * 4) + m.charAt(o * 5));
	var q = Math.round(n.length / 2);
	var r = Math.pow(2, 31) - 1;
	var s = parseInt(l.substring(l.length - 8, l.length), 16);
	l = l.substring(0, l.length - 8);
	m += s;
	while (m.length > 10) {
		m = (parseInt(m.substring(0, 10)) + parseInt(m.substring(10, m.length))).toString();
	}
	m = (p * m + q) % r;
	var t = "";
	var u = "";
	for (var i = 0; i < l.length; i += 2) {
		t = parseInt(parseInt(l.substring(i, i + 2), 16) ^ Math.floor((m / r) * 255));
		u += String.fromCharCode(t);
		m = (p * m + q) % r;
	}
	var v = "";
	if (!isOpera9) {
		if (k != u) {
			v = "HotEditor Powered by <a href=http://www.eCardMAX.com target=_blank>www.eCardMAX.com</a>";
		}
	}
	document.write("<tr><td align=left nowrap style='height:7px;font-size: 8pt;font-family: Verdana, Tahoma, Arial, sans-serif;'>&nbsp;&nbsp;" + v + "&nbsp;</td></tr>\n");
	document.write("</table>\n");
	document.write("<input type=\"hidden\" id=\"hoteditor_html_ouput_" + b + "\" name=\"hoteditor_html_ouput_" + b + "\" value=\"\">");
	document.write("<input type=\"hidden\" id=\"hoteditor_bbcode_ouput_" + b + "\" name=\"hoteditor_bbcode_ouput_" + b + "\" value=\"\">");
	if (!document.getElementById("hotmem")) {
		document.write("<input type=\"hidden\" id=\"hotmem\" name=\"hotmem\" value=\"\">");
	}
	if (isRichText) {
		enableDesignMode(b, c, g);
	}
	if (editor_type == "0") {
		document.getElementById("switch_span" + b).className = "Hoteditor_DesignModeOff_TextColor";
	} else {
		document.getElementById("switch_span" + b).className = "Hoteditor_DesignModeOn_TextColor";
	}
	var w = editor_size + 92;
	if (isMacOS && isGecko) {
		if (editor_type == "1") {
			hot_showid2("hoteditor_richtool" + b, "block");
			hot_showid2("hoteditor_texttool" + b, "none");
			document.getElementById("textarea_" + b).style.height = "0px";
			document.getElementById("textarea_" + b).style.width = "0px";
		} else {
			hot_showid2("hoteditor_richtool" + b, "none");
			hot_showid2("hoteditor_texttool" + b, "block");
			document.getElementById(b).style.height = "0px";
			document.getElementById(b).style.width = "0px";
		}
	} else {
		if (editor_type == "1") {
			hot_showid2("hoteditor_richtool" + b, "block");
			hot_showid2("hoteditor_texttool" + b, "none");
		} else {
			if (isSafari) {
				document.getElementById("hoteditor_richtool" + b).style.height = "0px";
				document.getElementById("hoteditor_richtool" + b).style.visibility = "hidden";
				hot_showid2("hoteditor_texttool" + b, "block");
			} else {
				hot_showid2("hoteditor_richtool" + b, "none");
				hot_showid2("hoteditor_texttool" + b, "block");
			}
		}
	}
}


function show_custom_toolbar(a, b) {
	if (a == "SPACE") {
		document.write("<img align=absmiddle src=" + styles_folder_path + "/space.gif ><img src=" + styles_folder_path + "/button_space.gif>");
	} else {
		var c = a.split("::");
		document.write("<img align=absmiddle class=Hoteditor_Button title=\"" + c[1] + "\" src=" + styles_folder_path + "/" + c[0] + " onmouseover=\"this.className='Hoteditor_Button_Over';hide_it('" + b + "');\" onmouseout=\"this.className='Hoteditor_Button_Out';\" onclick=\"WriteHTML_Custom(" + "'" + c[2] + "'" + ", '" + c[3] + "', '" + b + "')\"><img src=" + styles_folder_path + "/button_space.gif>");
	}
}


function show_custom_toolbar_bbcode(a, b) {
	if (a == "SPACE") {
		document.write("<img align=absmiddle src=" + styles_folder_path + "/space.gif ><img src=" + styles_folder_path + "/button_space.gif>");
	} else {
		var c = a.split("::");
		document.write("<img align=absmiddle class=Hoteditor_Button title=\"" + c[1] + "\" src=" + styles_folder_path + "/" + c[0] + " onmouseover=\"this.className='Hoteditor_Button_Over';hide_it('" + b + "');\" onmouseout=\"this.className='Hoteditor_Button_Out';\" onclick=\"WriteTEXT_Custom(" + "'" + c[2] + "'" + ", '" + c[3] + "', '" + b + "')\"><img src=" + styles_folder_path + "/button_space.gif>");
	}
}


function show_toolbar(a, b) {
	a = a.replace(" ", "");
	if (a == "SPACE") {
		document.write("<img align=absmiddle src=" + styles_folder_path + "/space.gif ><img src=" + styles_folder_path + "/button_space.gif>");
	} else if (a == "btMoreTags" && !isSafari) {
		write_button_richtext(b, capMoreTags, "more_tags.gif", "more_tags", "", "more_tags_" + b);
	} else if (a == "btCalendar" && !isSafari) {
		write_button_richtext(b, capCalendar, "calendar.gif", "calendar", "", "calendar_" + b);
	} else if (a == "btSymbol" && !isSafari) {
		write_button_richtext(b, capSymbol, "symbol.gif", "symbol", "", "symbol_" + b);
	} else if (a == "btVirtualKeyboard" && !isSafari) {
		write_button_richtext(b, capVirtualKeyboard, "virtual_keyboard.gif", "vk", "", "vk_" + b);
	} else if (a == "btUpload" && !isSafari) {
		write_button_richtext(b, capUpload, "upload.gif", "upload", "", "upload_" + b);
	} else if (a == "btEmotions") {
		write_button_richtext(b, capEmotions, "insertsmile.gif", "smile", "", "smile_" + b);
	} else if (a == "btWordArt") {
		write_button_richtext(b, capWordArt, "insertwordart.gif", "wordart", "", "wordart_" + b);
	} else if (a == "btClipart") {
		write_button_richtext(b, capClipart, "insertclipart.gif", "clipart", "", "clipart_" + b);
	} else if (a == "btFont_Name") {
		write_button_richtext(b, capFont_Name, "fontname.gif", "fontname", "", "fontname_" + b);
	} else if (a == "btFont_Size") {
		write_button_richtext(b, capFont_Size, "fontsize.gif", "fontsize", "", "fontsize_" + b);
	} else if (a == "btFont_Color") {
		write_button_richtext(b, capFont_Color, "fontcolor.gif", "forecolor", "", "forecolor_" + b);
	} else if (a == "btHighlight") {
		write_button_richtext(b, capHighlight, "highlinght.gif", "hilitecolor", "", "hilitecolor_" + b);
	} else if (a == "btRemove_Format" && !isSafari) {
		write_button_richtext(b, capRemove_Format, "remove.gif", "removeformat", "", "remove_format_" + b);
	} else if (a == "btBold") {
		write_button_richtext(b, capBold, "bold.gif", "bold", "", "bold_" + b);
	} else if (a == "btItalic") {
		write_button_richtext(b, capItalic, "italic.gif", "italic", "", "itatlic_" + b);
	} else if (a == "btUnderline") {
		write_button_richtext(b, capUnderline, "underline.gif", "underline", "", "underline_" + b);
	} else if (a == "btAlign_Left") {
		write_button_richtext(b, capAlign_Left, "aleft.gif", "justifyleft", "", "aleft_" + b);
	} else if (a == "btCenter") {
		write_button_richtext(b, capCenter, "acenter.gif", "justifycenter", "", "acenter_" + b);
	} else if (a == "btAlign_Right") {
		write_button_richtext(b, capAlign_Right, "aright.gif", "justifyright", "", "aright_" + b);
	} else if (a == "btJustify") {
		write_button_richtext(b, capJustify, "ajustify.gif", "justifyfull", "", "ajustify_" + b);
	} else if (a == "btBullets") {
		write_button_richtext(b, capBullets, "listbullets.gif", "insertunorderedlist", "", "bullet_" + b);
	} else if (a == "btNumbering") {
		write_button_richtext(b, capNumbering, "listnumber.gif", "insertorderedlist", "", "numbering_" + b);
	} else if (a == "btDecrease_Indent" && !isSafari) {
		write_button_richtext(b, capDecrease_Indent, "indentleft.gif", "outdent", "", "indent1_" + b);
	} else if (a == "btIncrease_Indent" && !isSafari) {
		write_button_richtext(b, capIncrease_Indent, "indentright.gif", "indent", "", "indent2_" + b);
	} else if (a == "btTable") {
		write_button_richtext(b, capTable, "table.gif", "addtable", "", "table_" + b);
	} else if (a == "btHyperlink") {
		write_button_richtext(b, capHyperlink, "createlink.gif", "createlink", "", "link_" + b);
	} else if (a == "btHyperlink_Email") {
		write_button_richtext(b, capHyperlink_Email, "createlink_email.gif", "createlink_email", "", "email_" + b);
	} else if (a == "btRemovelink" && !isSafari) {
		write_button_richtext(b, capRemovelink, "removelink.gif", "unlink", "", "removelink_" + b);
	} else if (a == "btFlash") {
		write_button_richtext(b, capFlash, "flash.gif", "flash", "", "flash_" + b);
	} else if (a == "btYouTube") {
		write_button_richtext(b, capYouTube, "youtube.gif", "youtube", "", "youtube_" + b);
	} else if (a == "btGoogle") {
		write_button_richtext(b, capGoogle, "google.gif", "google", "", "google_" + b);
	} else if (a == "btYahoo") {
		write_button_richtext(b, capYahoo, "yahoo.gif", "yahoo", "", "yahoo_" + b);
	} else if (a == "btQuote" && !isSafari) {
		write_button_richtext(b, capQuote, "quote.gif", "quote", "", "quote_" + b);
	} else if (a == "btCode" && !isSafari) {
		write_button_richtext(b, capCode, "code.gif", "code", "", "code_" + b);
	} else if (a == "btPHP" && !isSafari) {
		write_button_richtext(b, capPHP, "php.gif", "php", "", "php_" + b);
	} else if (a == "btHTML" && !isSafari) {
		write_button_richtext(b, capHTML, "html_tag.gif", "html", "", "html_" + b);
	} else if (a == "btStrikethrough") {
		write_button_richtext(b, capStrikethrough, "strikethrough.gif", "Strikethrough", "", "strike_" + b);
	} else if (a == "btSubscript") {
		write_button_richtext(b, capSubscript, "subscript.gif", "Subscript", "", "sub_" + b);
	} else if (a == "btSuperscript") {
		write_button_richtext(b, capSuperscript, "superscript.gif", "Superscript", "", "sup_" + b);
	} else if (a == "btHorizontal") {
		write_button_richtext(b, capHorizontal, "line.gif", "inserthorizontalrule", "", "hr_" + b);
	} else if (a == "btCut") {
		write_button_richtext(b, capCut, "cut.gif", "cut", "", "cut_" + b);
	} else if (a == "btCopy") {
		write_button_richtext(b, capCopy, "copy.gif", "copy", "", "copy_" + b);
	} else if (a == "btPaste") {
		write_button_richtext(b, capPaste, "paste.gif", "paste", "", "paste_" + b);
	} else if (a == "btUndo") {
		write_button_richtext(b, capUndo, "undo.gif", "undo", "", "undo_" + b);
	} else if (a == "btRedo") {
		write_button_richtext(b, capRedo, "redo.gif", "redo", "", "redo_" + b);
	} else if (a == "btInsert_Image") {
		if (isSafari) {
			write_button_richtext(b, capInsert_Image, "insertimage.gif", "safari_InsertImage", "", "safariimg_" + b);
		} else {
			document.write("<img align=absmiddle class=Hoteditor_Button title=\"" + capInsert_Image + "\" src=" + styles_folder_path + "/insertimage.gif onmouseover=\"this.className='Hoteditor_Button_Over';\" onmouseout=\"this.className='Hoteditor_Button_Out';\" onclick=\"AddImage(" + "'" + b + "'" + ")\"><img src=" + styles_folder_path + "/button_space.gif>");
		}
	} else if (a == "btDeleteAll") {
		write_button_richtext(b, capDelete_All, "delete_all.gif", "delete_all", "", "deleteall_" + b);
	} else if (a == "btIESpell" && isIE) {
		write_button_richtext(b, capIESpell, "iespell.gif", "iespell", "", "iespell_" + b);
	} else if (a == "chkViewHTML") {
		document.write("<input title=\"" + capViewHTML + "\" type=\"checkbox\" id=\"chkSrc" + b + "\" onclick=\"toggleHTMLSrc('" + b + "');\" /><span style=\"font-size:10px;margin-top: 2px; margin-bottom: 0px; \"> HTML</span><img src=" + styles_folder_path + "/button_space.gif>");
	}
}


function write_button_richtext(a, b, c, d, e, f) {
	if (!isSafari) {
		document.write("<img align=absmiddle id='" + f + "' class=Hoteditor_Button title=\"" + b + "\" src=" + styles_folder_path + "/" + c + " onmouseover=\"this.className='Hoteditor_Button_Over';hide_it('" + a + "');\" onmouseout=\"this.className='Hoteditor_Button_Out';\" onclick=\"FormatText(" + "'" + a + "'" + ", '" + d + "', '" + e + "')\"><img src=" + styles_folder_path + "/button_space.gif>");
	} else {
		document.write("<img align=absmiddle id='" + f + "' class=Hoteditor_Button title=\"" + b + "\" src=" + styles_folder_path + "/" + c + " onmouseover=\"this.className='Hoteditor_Button_Over';hide_it('" + a + "');\" onmouseout=\"this.className='Hoteditor_Button_Out';\" onmousedown=\"return FormatText(" + "'" + a + "'" + ", '" + d + "', '" + e + "')\"><img src=" + styles_folder_path + "/button_space.gif>");
	}
}


function SafariSelection(a) {
	var b = "";
	if (a.getSelection) {
		b = a.getSelection();
	} else if (a.document.getSelection) {
		b = a.document.getSelection();
	} else if (a.document.selection) {
		b = a.document.selection.createRange().text;
	} else {
		return;
	}
	return b;
}


function show_toolbar_textarea(a, b) {
	a = a.replace(" ", "");
	if (a == "SPACE") {
		document.write("<img align=absmiddle src=" + styles_folder_path + "/space.gif ><img src=" + styles_folder_path + "/button_space.gif>");
	} else if (a == "btEmotions") {
		write_button_textarea(capEmotions, "insertsmile.gif", "", "insertsmile", b);
	} else if (a == "btWordArt") {
		write_button_textarea(capWordArt, "insertwordart.gif", "", "insertwordart", b);
	} else if (a == "btClipart") {
		write_button_textarea(capClipart, "insertclipart.gif", "", "insertclipart", b);
	} else if (a == "btFont_Name") {
		document.write("<select class=Hoteditor_iTextarea size=1 onchange=\"wrapSelection('font','=' + this.value,'" + b + "');this.selectedIndex='0';\"><option value=''>" + dropdownFonts + "</option>");
		for (i = 0; i < array_fontname.length; i++) {
			document.write("<option value='" + array_fontname[i] + "'>" + array_fontname[i] + "</option>");
		}
		document.write("</select><img src=" + styles_folder_path + "/button_space.gif>");
	} else if (a == "btFont_Size") {
		var c = new Array;
		var px = new Array(6, 8,10,12,14,18,24,36);
		document.write("<select style=\"width:52px\" class=Hoteditor_iTextarea size=1 onchange=\"wrapSelection('size','=' + this.value,'" + b + "');this.selectedIndex='0';\"><option value=''>" + dropdownSize + "</option>");
		for (i = 0; i < 8; i++) {
			document.write("<option value='" + px[i] + "'>" + px[i] + "px</option>");
		}
		document.write("</select><img src=" + styles_folder_path + "/button_space.gif>");
	} else if (a == "btFont_Color") {
		document.write("<select style=\"width:60px\" class=Hoteditor_iTextarea size=1 onchange=\"wrapSelection('color','=' + this.value,'" + b + "');this.selectedIndex='0';\"><option value=''>" + dropdownColor + "</option>");
		for (i = 0; i < array_fontcolor.length; i++) {
			document.write("<option style='background-color:" + array_fontcolor[i] + ";color:" + array_fontcolor[i] + "' value='" + array_fontcolor[i] + "'>" + array_fontcolor[i] + "</option>");
		}
		document.write("</select><img src=" + styles_folder_path + "/button_space.gif>");
	} else if (a == "btHighlight") {
		document.write("<select style=\"width:65px\" class=Hoteditor_iTextarea size=1 onchange=\"wrapSelection('highlight','=' + this.value,'" + b + "');this.selectedIndex='0';\"><option value=''>" + dropdownHighlight + "</option>");
		for (i = 0; i < array_fontcolor.length; i++) {
			document.write("<option style='background-color:" + array_fontcolor[i] + ";color:" + array_fontcolor[i] + "' value='" + array_fontcolor[i] + "'>" + array_fontcolor[i] + "</option>");
		}
		document.write("</select><img src=" + styles_folder_path + "/button_space.gif>");
	} else if (a == "btCut") {
		write_button_textarea(capCut, "cut.gif", "", "cut", b);
	} else if (a == "btCopy") {
		write_button_textarea(capCopy, "copy.gif", "", "copy", b);
	} else if (a == "btPaste") {
		write_button_textarea(capPaste, "paste.gif", "", "paste", b);
	} else if (a == "btBold") {
		write_button_textarea(capBold, "bold.gif", "b", "", b);
	} else if (a == "btItalic") {
		write_button_textarea(capItalic, "italic.gif", "i", "", b);
	} else if (a == "btUnderline") {
		write_button_textarea(capUnderline, "underline.gif", "u", "", b);
	} else if (a == "btFlash") {
		write_button_textarea(capFlash, "flash.gif", "flash", "btFlash", b);
	} else if (a == "btYouTube") {
		write_button_textarea(capYouTube, "youtube.gif", "youtube", "btYouTube", b);
	} else if (a == "btGoogle") {
		write_button_textarea(capGoogle, "google.gif", "google", "btGoogle", b);
	} else if (a == "btYahoo") {
		write_button_textarea(capYahoo, "yahoo.gif", "yahoo", "btYahoo", b);
	} else if (a == "btYouTube") {
		write_button_textarea(capYouTube, "youtube.gif", "youtube", "btYouTube", b);
	} else if (a == "btAlign_Left") {
		write_button_textarea(capAlign_Left, "aleft.gif", "left", "", b);
	} else if (a == "btCenter") {
		write_button_textarea(capCenter, "acenter.gif", "center", "", b);
	} else if (a == "btAlign_Right") {
		write_button_textarea(capAlign_Right, "aright.gif", "right", "", b);
	} else if (a == "btJustify") {
		write_button_textarea(capJustify, "ajustify.gif", "justify", "", b);
	} else if (a == "btBullets") {
		write_button_textarea(capBullets, "listbullets.gif", "list,*", "bbBullets", b);
	} else if (a == "btNumbering") {
		write_button_textarea(capNumbering, "listnumber.gif", "list=1,*", "bbNumbering", b);
	} else if (a == "btIncrease_Indent") {
		write_button_textarea(capIncrease_Indent, "indentright.gif", "blockquote", "", b);
	} else if (a == "btTable") {
		write_button_textarea(capTable, "table.gif", "", "Table", b);
	} else if (a == "btRemove_Format") {
		write_button_textarea(capRemove_Format, "remove.gif", "", "Removeformat", b);
	} else if (a == "btHyperlink") {
		write_button_textarea(capHyperlink, "createlink.gif", "url", "Hyperlink", b);
	} else if (a == "btHyperlink_Email") {
		write_button_textarea(capHyperlink_Email, "createlink_email.gif", "mail", "Hyperlink_Email", b);
	} else if (a == "btRemovelink") {
		write_button_textarea(capRemovelink, "removelink.gif", "", "Removelink", b);
	} else if (a == "btQuote") {
		write_button_textarea(capQuote, "quote.gif", "quote", "", b);
	} else if (a == "btCode") {
		write_button_textarea(capCode, "code.gif", "code", "", b);
	} else if (a == "btPHP") {
		write_button_textarea(capPHP, "php.gif", "php", "", b);
	} else if (a == "btHTML") {
		write_button_textarea(capHTML, "html_tag.gif", "html", "", b);
	} else if (a == "btStrikethrough") {
		write_button_textarea(capStrikethrough, "strikethrough.gif", "strike", "", b);
	} else if (a == "btSubscript") {
		write_button_textarea(capSubscript, "subscript.gif", "sub", "", b);
	} else if (a == "btSuperscript") {
		write_button_textarea(capSuperscript, "superscript.gif", "sup", "", b);
	} else if (a == "btHorizontal") {
		write_button_textarea(capHorizontal, "line.gif", "hr", "HR", b);
	} else if (a == "btInsert_Image") {
		write_button_textarea(capInsert_Image, "insertimage.gif", "img", "img", b);
	} else if (a == "btDeleteAll") {
		write_button_textarea(capDelete_All, "delete_all.gif", "", "delete_all", b);
	} else if (a == "btIESpell" && isIE) {
		write_button_textarea(capIESpell, "iespell.gif", "", "iespell", b);
	}
}


function write_button_textarea(a, b, c, d, e) {
	document.write("<img align=absmiddle class=Hoteditor_Button title=\"" + a + "\" src=" + styles_folder_path + "/" + b + " onmouseover=\"this.className='Hoteditor_Button_Over';\" onmouseout=\"this.className='Hoteditor_Button_Out';\" onclick=\"FormatText2(" + "'" + c + "'" + ",'" + d + "','" + e + "')\"><img src=" + styles_folder_path + "/button_space.gif>");
}


function run_iespell() {
	try {
		var a = new ActiveXObject("ieSpell.ieSpellExtension");
		a.CheckAllLinkedDocuments(document);
	} catch (exception) {
		if (exception.number == -2146827859) {
			if (confirm(alertNoIESpell)) {
				window.open(IESpellURL);
			}
		} else {
			alert(IESpellError);
		}
	}
}


function FormatText2(a, b, c) {
	currentwindow = c;
	var d = c.replace("textarea_", "");
	if (isIE) {
		var e;
		e = document.getElementById(c);
		var f = e.document.selection;
	}
	if (b == "delete_all") {
		document.getElementById(c).value = "";
		document.getElementById(c).focus();
	} else if (b == "iespell") {
		run_iespell();
	} else if (b == "img") {
		imagePath = prompt(enter_image_url, "http://");
		if (imagePath != null && (imagePath != "")) {
			WriteTEXT("[img]" + imagePath + "[/img]", d);
		}
	} else if (b == "insertsmile") {
		window.open(smiles_path, "win_hoteditor", "height=" + simley_frame_height + ",width=" + simley_frame_width + ",status=no,toolbar=no,menubar=no,location=no,scrollbars=no");
	} else if (b == "insertwordart") {
		window.open(wordarts_path, "win_hoteditor", "height=" + wordart_frame_height + ",width=" + wordart_frame_width + ",status=no,toolbar=no,menubar=no,location=no,scrollbars=no");
	} else if (b == "insertclipart") {
		window.open(cliparts_path, "win_hoteditor", "height=" + clipart_frame_height + ",width=" + clipart_frame_width + ",status=no,toolbar=no,menubar=no,location=no,scrollbars=no");
	} else if (b == "bbBullets" || b == "bbNumbering") {
		var g = GetSelection(c);
		var h = "";
		if (b == "bbBullets") {
			h = "[list]";
		} else {
			h = "[list=1]";
		}
		g = g.replace(/\n/g, "\n[*]");
		if (g != "") {
			WriteTEXT(h + "\n[*]" + g + "[/list]", d);
		} else {
			WriteTEXT(h + "\n" + "[*]\n[*]\n[*]\n[/list]", d);
		}
	} else if (b == "Table") {
		WriteTEXT("[table]\n[tr]\n[td][/td]\n[/tr][/table]", d);
	} else if (b == "Hyperlink" || b == "Hyperlink_Email") {
		if (b == "Hyperlink") {
			var i = enter_url_text;
			var j = "http://";
		} else {
			var i = enter_email_text;
			var j = "email@domain.com";
		}
		var g = GetSelection(c);
		if (g != "") {
			var k = prompt(i, g);
		} else {
			var k = prompt(i, j);
		}
		if (k != null) {
			wrapSelection(a, "=" + k, c);
		}
	} else if (b == "Removelink") {
		var l = GetSelection(c);
		mylink2 = l.toLowerCase();
		if (mylink2.indexOf("[url") != "-1" &&
			mylink2.indexOf("[/url]") != "-1" ||
			mylink2.indexOf("[mail") != "-1" &&
			mylink2.indexOf("[/mail]") != "-1") {
			l = l.replace(/\[url(.*?)\]/gi, "");
			l = l.replace(/\[\/url\]/gi, "");
			l = l.replace(/\[mail(.*?)\]/gi, "");
			l = l.replace(/\[\/mail\]/gi, "");
		}
		WriteTEXT(l, d);
	} else if (b == "Removeformat") {
		var l = GetSelection(c);
		l = l.replace(/\[(b|u|i|strike|s|sub|sup)\]/gi, "");
		l = l.replace(/\[\/(b|u|i|strike|s|sub|sup)\]/gi, "");
		l = l.replace(/\[(font|size|color|highlight)(.*?)\]/gi, "");
		l = l.replace(/\[\/(font|size|color|highlight)\]/gi, "");
		WriteTEXT(l, d);
	} else if (b == "btFlash") {
		var k = prompt(flash_enter_url, "http://");
		if (k == null) {
			return false;
		}
		var m = prompt(flash_width_number_text, flash_width_number_default);
		var n = prompt(flash_height_number_text, flash_height_number_default);
		if (k != null) {
			if (m == null) {
				m = flash_width_number_default;
			}
			if (n == null) {
				n = flash_height_number_default;
			}
			var o = "[" + a + " width=" + m + ", height=" + n + "]" + k + "[/" + a + "]";
			WriteTEXT(o, d);
		}
	} else if (b == "btYouTube") {
		var k = prompt(promptYouTube, URLDefaultYouTube);
		k = k.replace(/watch\?v=/gi, "v/");
		if (k != null) {
			var o = "[" + a + "]" + k + "[/" + a + "]";
			WriteTEXT(o, d);
		}
	} else if (b == "btGoogle") {
		var k = prompt(promptGoogle, URLDefaultGoogle);
		k = k.replace(/videoplay/i, "googleplayer.swf");
		k = k.replace(/\&hl=en/i, "");
		if (k != null) {
			var o = "[" + a + "=" + flash_width_number_default + "," + flash_height_number_default + "]" + k + "[/" + a + "]";
			WriteTEXT(o, d);
		}
	} else if (b == "btYahoo") {
		var k = prompt(promptYahoo, URLDefaultYahoo);
		k.match(/flashvars='id=(.*?)&emailUrl=(.*?)'/i);
		k = "http://us.i1.yimg.com/cosmos.bcst.yahoo.com/player/media/swf/FLVVideoSolo.swf?id=" + RegExp.$1;
		if (k != null) {
			var o = "[" + a + "=" + flash_width_number_default + "," + flash_height_number_default + "]" + k + "[/" + a + "]";
			WriteTEXT(o, d);
		}
	} else if (b == "cut") {
		if (isIE) {
			e.document.execCommand("cut", false);
		} else {
			var p = GetSelection(c);
			if (p != "") {
				document.getElementById("hotmem").value = p;
				WriteTEXT(" ", d);
			}
		}
	} else if (b == "copy") {
		if (isIE) {
			e.document.execCommand("copy", false);
		} else {
			var p = GetSelection(c);
			if (p != "") {
				document.getElementById("hotmem").value = p;
			}
		}
	} else if (b == "paste") {
		if (isIE) {
			document.getElementById(c).focus();
			e.document.execCommand("paste", true);
		} else {
			document.getElementById("hotmem").value = HTMLToBBCode(document.getElementById("hotmem").value);
			WriteTEXT(document.getElementById("hotmem").value, d);
		}
	} else {
		wrapSelection(a, b, c);
	}
}


function FormatText(a, b, c) {
	currenteditor = a;
	if (HTML_ON == "no") {
		alert("Please uncheck the HTML checkbox");
		return false;
	}
	var d;
	if (isIE) {
		d = frames[a];
		var f = d.document.selection;
		if (f != null) {
			rng = f.createRange();
		}
	} else {
		d = document.getElementById(a).contentWindow;
		var f = d.getSelection();
		if (f != "" && f.rangeCount > 0) {
			rng = f.getRangeAt(f.rangeCount - 1).cloneContents();
			var g = d.document.createElement("div");
			g.appendChild(rng);
		}
	}
	if (b == "forecolor" || b == "hilitecolor") {
		parent.command = b;
		if (isSafari) {
			buttonElement = document.getElementById(b + "_" + a);
			var X = getOffsetLeft(buttonElement);
			var Y = getOffsetTop(buttonElement) + buttonElement.offsetHeight;
			document.getElementById("Hoteditor_Select_Color").style.left = X + "px";
			document.getElementById("Hoteditor_Select_Color").style.top = Y + "px";
			document.getElementById("Hoteditor_Select_Color").style.display = "block";
			event.preventDefault();
			event.returnValue = false;
		} else {
			var h = forecolor_frame_width;
			if (isIE) {
				forecolor_frame_width = forecolor_frame_width - 20;
			}
			if (b == "forecolor") {
				var i = pop_Select_Forecolor;
			} else {
				var i = pop_Select_Hilitecolor;
			}
			open_insert_pop(a, b, styles_folder_path + "/select_color.htm", i, forecolor_frame_width, forecolor_frame_height);
			forecolor_frame_width = h;
		}
	} else if (b == "safari_InsertImage") {
		var j = prompt(enter_image_url, "http://");
		if (j != null && (j != "")) {
			d.document.execCommand("InsertText", false, "[imghot src=" + j + " imghot]");
			var k = document.getElementById(a).contentWindow.document.body.innerHTML;
			k = k.replace(/\[imghot(.*?)imghot\]/gi, "<img $1>");
			k = k.replace(/[\n\r]/gi, "");
			document.getElementById(a).contentWindow.document.body.innerHTML = k;
		}
	} else if (b == "delete_all") {
		if (isGecko || isSafari) {
			d.document.body.innerHTML = "<br>";
		} else {
			d.document.body.innerHTML = "";
		}
		d.focus();
	} else if (b == "iespell") {
		run_iespell();
	} else if (b == "vk") {
		parent.command = b;
		open_insert_pop(a, b, VirtualKeyboard_path, pop_Insert_VK, vk_frame_width, vk_frame_height);
		chkVK = 1;
	} else if (b == "flash") {
		var l = prompt(flash_enter_url, "http://");
		var m = prompt(flash_width_number_text, flash_width_number_default);
		var n = prompt(flash_height_number_text, flash_height_number_default);
		if (l != null) {
			if (m == null || isNaN(m)) {
				m = flash_width_number_default;
			}
			if (n == null || isNaN(n)) {
				n = flash_height_number_default;
			}
			var o = "[" + b.toLowerCase() + "=" + m + "," + n + "]" + l + "[/" + b.toLowerCase() + "]";
			if (isIE) {
				d.document.execCommand("removeformat", false, "");
				rng.pasteHTML(" ");
				rng.pasteHTML(o);
			} else if (isSafari) {
				d.document.execCommand("InsertText", false, o);
			} else {
				d.focus();
				d.document.execCommand("InsertHTML", false, o);
			}
		}
	} else if (b == "addtable") {
		var p = prompt("Number of Rows", "3");
		var q = prompt("Number of Columns", "2");
		if (p != null && q != null && !isNaN(p) && !isNaN(q)) {
			var r = "<div><table>";
			var t = "";
			for (irow = 0; irow < p; irow++) {
				t += "<tr>";
				for (icol = 0; icol < q; icol++) {
					t += "<td>&nbsp;</td>";
				}
				t += "</tr>";
			}
			r += t + "</table></div><div style='clear:both;'></div>";
			WriteHTML(r, a);
		}
	} else if (b == "youtube") {
		var l = prompt(promptYouTube, URLDefaultYouTube);
		if (l != null) {
			l = l.replace(/watch\?v=/gi, "v/");
		}
		if (l != null) {
			var o = "[" + b.toLowerCase() + "]" + l + "[/" + b.toLowerCase() + "]";
			if (isIE) {
				d.document.execCommand("removeformat", false, "");
				rng.pasteHTML("");
				rng.pasteHTML(o);
			} else if (isSafari) {
				d.document.execCommand("InsertText", false, o);
			} else {
				d.focus();
				d.document.execCommand("InsertHTML", false, o);
			}
		}
	} else if (b == "google" || b == "yahoo") {
		if (b == "google") {
			var l = prompt(promptGoogle, URLDefaultGoogle);
			if (l != null) {
				l = l.replace(/videoplay/i, "googleplayer.swf");
				l = l.replace(/\&hl=en/i, "");
			}
		} else if (b == "yahoo") {
			var l = prompt(promptYahoo, URLDefaultYahoo);
			if (l != null) {
				l.match(/flashvars='id=(.*?)&emailUrl=(.*?)'/i);
				l = "http://us.i1.yimg.com/cosmos.bcst.yahoo.com/player/media/swf/FLVVideoSolo.swf?id=" + RegExp.$1;
			}
		}
		if (l != null) {
			var o = "[" + bbFlash.toLowerCase() + "=" + flash_width_number_default + "," + flash_height_number_default + "]" + l + "[/" + bbFlash.toLowerCase() + "]";
			if (isIE) {
				d.document.execCommand("removeformat", false, "");
				rng.pasteHTML("");
				rng.pasteHTML(o);
			} else if (isSafari) {
				d.document.execCommand("InsertText", false, o);
			} else {
				d.focus();
				d.document.execCommand("InsertHTML", false, o);
			}
		}
	} else if (b == "quote" || b == "code" || b == "php" || b == "html") {
		b = b.toLowerCase();
		var u = "";
		if (isIE) {
			u = rng.htmlText;
		} else {
			if (f != "") {
				u = g.innerHTML;
			} else {
				u = "";
			}
		}
//		ExiteCMS: don't tamper with the contents of a code block!
//		if (b == "code" || b == "php" || b == "html") {
//			u = u.replace(/[\n\r]/gi, "");
//			u = u.replace(/<(br|p|div|li).*?>/gi, "[br/]");
//			u = u.replace(/<\/(p|div).*?>/gi, "");
//			u = u.replace(/(<([^>]+)>)/gi, "");
//			u = u.replace(/\[br\/\]/gi, "<br>");
//		}
		if (b == "quote" && hoteditor_reply_to != "") {
			u = "[" + b + "=" + hoteditor_reply_to + "]" + u + "[/" + b + "]";
		} else {
			u = "[" + b + "]" + u + "[/" + b + "]";
		}
		WriteHTML(u, a);
	} else if (b == "symbol") {
		parent.command = b;
		open_insert_pop(a, b, styles_folder_path + "/select_symbol.htm", pop_Insert_Symbol, symbol_frame_width, symbol_frame_height);
		chkVK = 1;
	} else if (b == "fontname") {
		parent.command = b;
		if (!isSafari) {
			open_insert_pop(a, b, styles_folder_path + "/select_fontface.htm", pop_Select_Font, fontname_frame_width, fontname_frame_height);
		} else {
			buttonElement = document.getElementById(b + "_" + a);
			var X = getOffsetLeft(buttonElement);
			var Y = getOffsetTop(buttonElement) + buttonElement.offsetHeight;
			document.getElementById("Hoteditor_Font_Name").style.left = X + "px";
			document.getElementById("Hoteditor_Font_Name").style.top = Y + "px";
			document.getElementById("Hoteditor_Font_Name").style.display = "block";
			event.preventDefault();
			event.returnValue = false;
		}
	} else if (b == "fontsize") {
		parent.command = b;
		if (!isSafari) {
			open_insert_pop(a, b, styles_folder_path + "/select_fontsize.htm", pop_Select_FontSize, fontsize_frame_width, fontsize_frame_height);
		} else {
			buttonElement = document.getElementById(b + "_" + a);
			var X = getOffsetLeft(buttonElement);
			var Y = getOffsetTop(buttonElement) + buttonElement.offsetHeight;
			document.getElementById("Hoteditor_Font_Size").style.left = X + "px";
			document.getElementById("Hoteditor_Font_Size").style.top = Y + "px";
			document.getElementById("Hoteditor_Font_Size").style.display = "block";
			event.preventDefault();
			event.returnValue = false;
		}
	} else if (b == "smile") {
		parent.command = b;
		if (!isSafari) {
			open_insert_pop(a, b, smiles_path, pop_Select_Smile, simley_frame_width, simley_frame_height);
		} else {
			var v = (new Date).getTime();
			d.document.execCommand("InsertText", false, v);
			var k = document.getElementById(a).contentWindow.document.body.innerHTML;
			k = k.replace(/<img>/gi, "");
			k = k.replace(v, "<img>");
			k = k.replace(/[\n\r]/gi, "");
			document.getElementById(a).contentWindow.document.body.innerHTML = k;
			window.open(smiles_path, "win_hoteditor", "height=" + simley_frame_height + ",width=" + simley_frame_width + ",status=no,toolbar=no,menubar=no,location=no,scrollbars=no");
		}
	} else if (b == "wordart") {
		parent.command = b;
		if (!isSafari) {
			open_insert_pop(a, b, wordarts_path, pop_Select_WordArt, wordart_frame_width, wordart_frame_height);
		} else {
			var v = (new Date).getTime();
			d.document.execCommand("InsertText", false, v);
			var k = document.getElementById(a).contentWindow.document.body.innerHTML;
			k = k.replace(/<img>/gi, "");
			k = k.replace(v, "<img>");
			k = k.replace(/[\n\r]/gi, "");
			document.getElementById(a).contentWindow.document.body.innerHTML = k;
			window.open(wordarts_path, "win_hoteditor", "height=" + wordart_frame_height + ",width=" + wordart_frame_width + ",status=no,toolbar=no,menubar=no,location=no,scrollbars=no");
		}
	} else if (b == "clipart") {
		parent.command = b;
		if (!isSafari) {
			open_insert_pop(a, b, cliparts_path, pop_Select_ClipArt, clipart_frame_width, clipart_frame_height);
		} else {
			var v = (new Date).getTime();
			d.document.execCommand("InsertText", false, v);
			var k = document.getElementById(a).contentWindow.document.body.innerHTML;
			k = k.replace(/<img>/gi, "");
			k = k.replace(v, "<img>");
			k = k.replace(/[\n\r]/gi, "");
			document.getElementById(a).contentWindow.document.body.innerHTML = k;
			window.open(cliparts_path, "win_hoteditor", "height=" + clipart_frame_height + ",width=" + clipart_frame_width + ",status=no,toolbar=no,menubar=no,location=no,scrollbars=no");
		}
	} else if (b == "upload") {
		parent.command = b;
		open_insert_pop(a, b, upload_path, pop_Select_Upload, upload_frame_width, upload_frame_height);
	} else if (b == "calendar") {
		parent.command = b;
		open_insert_pop(a, b, celendar_path, pop_Select_Calendar, calendar_frame_width, calendar_frame_height);
	} else if (b == "createlink" || b == "createlink_email") {
		if (b == "createlink") {
			var l = prompt(enter_url_text, "http://");
		} else {
			var l = prompt(enter_email_text, "email@domain.com");
		}
		if (l != null) {
			var w = l.split(" ");
			l = w[0];
		}
		if (isSafari) {
			var x = SafariSelection(d);
			if (x == "") {
				x = l;
			}
			var y = prompt(safari_enter_text_link, x);
		}
		if (b == "createlink_email") {
			l = "mailto:" + l;
		}
		if (isSafari) {
			if (l != null && y != null && l != "" && y != "") {
				d.document.execCommand("InsertText", false, "[ahot href=" + l + "]" + y + "[/ahot]");
				var k = document.getElementById(a).contentWindow.document.body.innerHTML;
				k = k.replace(/\[ahot(.*?)\]/gi, "<a$1>");
				k = k.replace(/\[\/ahot\]/gi, "</a>");
				k = k.replace(/[\n\r]/gi, "");
				document.getElementById(a).contentWindow.document.body.innerHTML = k;
			}
		} else {
			try {
				// ExiteCMS: no text selected, then use the link itself as text
				if (rng == null) {
					WriteHTML("<a href='" + l + "'>" + l +"</a>", a);
				} else {
					d.document.execCommand("Unlink", false, null);
					d.document.execCommand("CreateLink", false, l);
				}
			} catch (e) {
			}
		}
	} else if (b == "paste") {
		if (isSafari) {
			alert(safari_paste_command);
		} else if (isIE) {
			d.focus();
			d.document.execCommand(b, true);
		} else {
			document.getElementById("hotmem").value = document.getElementById("hotmem").value.replace(/\n/g, "<br>");
			WriteHTML(document.getElementById("hotmem").value, a);
		}
	} else {
		if (isSafari) {
			if (b == "inserthorizontalrule") {
				d.document.execCommand("InsertText", false, "[hr]");
				var k = document.getElementById(a).contentWindow.document.body.innerHTML;
				k = k.replace(/\[hr\]/g, "<hr>");
				k = k.replace(/[\n\r]/gi, "");
				document.getElementById(a).contentWindow.document.body.innerHTML = k + "<br>";
			} else if (b == "Strikethrough") {
				var x = SafariSelection(d);
				d.document.execCommand("InsertText", false, "[strikehot]" + x + "[/strikehot]");
				var k = document.getElementById(a).contentWindow.document.body.innerHTML;
				k = k.replace(/\[strikehot\]/gi, "<strike>");
				k = k.replace(/\[\/strikehot\]/gi, "</strike>");
				k = k.replace(/[\n\r]/gi, "");
				document.getElementById(a).contentWindow.document.body.innerHTML = k;
			} else if (b == "insertunorderedlist" || b == "insertorderedlist") {
				var z = "";
				for (var s = 0; s < 50; s++) {
					var A = prompt(safari_bullets_numbering_prompt, "");
					if (A != null && A != "") {
						z += "[lihot]" + A + "[/lihot]";
					} else {
						break;
					}
				}
				if (z != "") {
					if (b == "insertunorderedlist") {
						var B = "[ulhot]" + z;
					} else {
						var B = "[olhot]" + z;
					}
					if (b == "insertunorderedlist") {
						B += "[/ulhot]";
					} else {
						B += "[/olhot]";
					}
					d.document.execCommand("InsertText", false, B);
					var k = document.getElementById(a).contentWindow.document.body.innerHTML;
					k = k.replace(/\[ulhot\]/g, "<UL>");
					k = k.replace(/\[\/ulhot\]/g, "</UL>");
					k = k.replace(/\[olhot\]/g, "<OL>");
					k = k.replace(/\[\/olhot\]/g, "</OL>");
					k = k.replace(/\[lihot\]/g, "<LI>");
					k = k.replace(/\[\/lihot\]/g, "</LI>");
					k = k.replace(/[\n\r]/gi, "");
					document.getElementById(a).contentWindow.document.body.innerHTML = "<br>" + k;
					d.focus();
				}
			} else {
				d.document.execCommand(b, false, c);
				event.preventDefault();
				event.returnValue = false;
			}
		} else {
			if (!isIE && b == "cut" || !isIE && b == "copy") {
				f = g.innerHTML;
				if (f != "") {
					document.getElementById("hotmem").value = f;
					if (b == "cut") {
						WriteHTML(" ", a);
					}
				}
			} else {
				d.document.execCommand(b, false, c);
			}
		}
	}
}


function SetKeyboard(a) {
	VirtualKeyboard_path = a;
}


function Set_smiles_path(a) {
	smiles_path = a;
}


function Set_wordarts_path(a) {
	wordarts_path = a;
}


function Set_cliparts_path(a) {
	cliparts_path = a;
}


function InsertTextArea(a) {
	var b = currentwindow.replace("textarea_", "");
	WriteTEXT("[img]" + a + "[/img]", b);
}


function WriteHTML_Custom(a, b, c) {
	a = a.replace(/\n/g, "<br>");
	b = b.replace(/\n/g, "<br>");
	if (HTML_ON == "no") {
		alert("Please uncheck the HTML checkbox");
		return false;
	}
	var d;
	if (isIE) {
		d = frames[c];
		d.focus();
		var e = d.document.selection;
		if (e != null) {
			rng = e.createRange();
		}
		link = rng.htmlText;
		link = a + link + b;
		d.document.execCommand("removeformat", false, "");
		rng.pasteHTML("");
		rng.pasteHTML(link);
		d.focus();
	} else {
		d = document.getElementById(c).contentWindow;
		var e = d.getSelection();
		if (e != "" && e.rangeCount > 0) {
			rng = e.getRangeAt(e.rangeCount - 1).cloneContents();
			var f = d.document.createElement("div");
			f.appendChild(rng);
			e = f.innerHTML;
		}
		text = a + e + b;
		d = document.getElementById(c).contentWindow;
		d.focus();
		d.document.execCommand("insertHTML", false, " ");
		d.document.execCommand("removeformat", false, "");
		d.document.execCommand("insertHTML", false, text);
		d.document.execCommand("removeformat", false, "");
	}
}


function WriteHTML(a, b) {
	if (HTML_ON == "no") {
		alert("Please uncheck the HTML checkbox");
		return false;
	}
	var c;
	if (isIE) {
		c = frames[b];
		c.focus();
		var d = c.document.selection;
		if (d != null) {
			rng = d.createRange();
		}
		c.document.execCommand("removeformat", false, "");
		rng.pasteHTML("");
		rng.pasteHTML(a);
		c.focus();
	} else if (isSafari) {
		c = document.getElementById(b).contentWindow;
		c.focus();
		a = a.replace(/</g, "[hotagopen]");
		a = a.replace(/>/g, "[hotagclose]");
		c.document.execCommand("insertTEXT", false, a);
		var e = document.getElementById(b).contentWindow.document.body.innerHTML;
		e = e.replace(/\[hotagopen\]/g, "<");
		e = e.replace(/\[hotagclose\]/g, ">");
		e = e.replace(/[\n\r]/gi, "");
		document.getElementById(b).contentWindow.document.body.innerHTML = e;
		c.focus();
	} else {
		c = document.getElementById(b).contentWindow;
		c.focus();
		c.document.execCommand("insertHTML", false, " ");
		c.document.execCommand("removeformat", false, "");
		c.document.execCommand("insertHTML", false, a);
		c.document.execCommand("removeformat", false, "");
		c.focus();
	}
}


function WriteTEXT_Custom(a, b, c) {
	if (HTML_ON == "no") {
		alert("Please uncheck the HTML checkbox");
		return false;
	}
	if (isIE) {
		strSelection = document.selection.createRange().text;
		document.getElementById("textarea_" + c).focus();
		document.selection.createRange().text = a + strSelection + b;
	} else {
		document.getElementById("textarea_" + c).focus();
		var d = document.getElementById("textarea_" + c);
		var e = d.textLength;
		var f = d.selectionStart;
		var g = d.selectionEnd;
		if (g == 1 || g == 2) {
			g = e;
		}
		var h = d.value.substring(0, f);
		var i = d.value.substring(f, g);
		var j = d.value.substring(g, e);
		d.value = h + a + i + b + j;
		document.getElementById("textarea_" + c).focus();
	}
}


function WriteTEXT(a, b) {
	if (HTML_ON == "no") {
		alert("Please uncheck the HTML checkbox");
		return false;
	}
	if (isIE) {
		document.getElementById("textarea_" + b).focus();
		document.selection.createRange().text = a;
	} else {
		document.getElementById("textarea_" + b).focus();
		var c = document.getElementById("textarea_" + b);
		var d = c.textLength;
		var e = c.selectionStart;
		var f = c.selectionEnd;
		if (f == 1 || f == 2) {
			f = d;
		}
		var g = c.value.substring(0, e);
		var h = c.value.substring(e, f);
		var i = c.value.substring(f, d);
		c.value = g + a + i;
		document.getElementById("textarea_" + b).focus();
	}
}


function SafariInsertImage(a) {
	if (isSafari) {
		var b = currenteditor;
		var c = document.getElementById(b).contentWindow;
		c.focus();
		var d = document.getElementById(b).contentWindow.document.body.innerHTML;
		if (a != "") {
			d = d.replace(/<img>/gi, "<img src=" + a + ">");
		} else {
			d = d.replace(/<img>/gi, "");
		}
		d = d.replace(/[\n\r]/gi, "");
		document.getElementById(b).contentWindow.document.body.innerHTML = d;
	}
}


function InsertSymbol(a) {
	if (HTML_ON == "no") {
		alert("Please uncheck the HTML checkbox");
		return false;
	}
	var b = currenteditor;
	var c;
	if (a == "BF") {
		a = "\\";
	}
	if (a == "<") {
		a = "&lt;";
	}
	if (a == ">") {
		a = "&gt;";
	}
	if (a == "&") {
		a = "&amp;";
	}
	if (isIE) {
		c = frames[b];
		c.focus();
		rng.collapse(false);
		rng.pasteHTML(a);
		rng.select();
		var d = c.document.selection;
		if (d != null) {
			rng = d.createRange();
		}
	} else {
		c = document.getElementById(b).contentWindow;
		c.focus();
		c.document.execCommand("insertHTML", false, a);
	}
}


function SetFontFormat(a, b) {
	var c = currenteditor;
	var d;
	if (isIE) {
		d = frames[c];
	} else {
		d = document.getElementById(c).contentWindow;
	}
	var e = parent.command;
	if (isIE && e == "hilitecolor" || isSafari && e == "hilitecolor") {
		e = "backcolor";
	}
	if (isIE) {
		var f = d.document.selection;
		if (f != null) {
			var g = f.createRange();
			g = rng;
			g.select();
		}
	} else {
		d.focus();
	}
	d.document.execCommand(e, false, a);
	d.focus();
}


function hide_it(a) {
	var b;
	if (isIE) {
		b = frames[a];
		if (chkVK == 1) {
			var c = b.document.selection;
			if (c != null) {
				rng = c.createRange();
				rng = c.getRangeAt(c.rangeCount - 1).cloneRange();
			}
		}
	} else {
		b = document.getElementById(a).contentWindow;
	}
	b.focus();
	if (isSafari) {
		hot_showid2("Hoteditor_Font_Name", "none");
		hot_showid2("Hoteditor_Font_Size", "none");
		hot_showid2("Hoteditor_Select_Color", "none");
	}
}


function hide_it2() {
	var a;
	if (isIE) {
		a = frames[currenteditor];
		if (chkVK == 1) {
			var b = a.document.selection;
			if (b != null) {
				rng = b.createRange();
				rng = b.getRangeAt(b.rangeCount - 1).cloneRange();
			}
		}
	} else {
		a = document.getElementById(currenteditor).contentWindow;
	}
	a.focus();
}


function AddImage(a) {
	if (HTML_ON == "no") {
		alert("Please uncheck the HTML checkbox");
		return false;
	}
	imagePath = prompt(enter_image_url, "http://");
	if (imagePath != null && (imagePath != "")) {
		WriteHTML("<img src=" + imagePath + ">", a);
	}
}


function getOffsetTop(a) {
	var b = a.offsetTop;
	var c = a.offsetParent;
	while (c) {
		b += c.offsetTop;
		c = c.offsetParent;
	}
	return b;
}


function getOffsetLeft(a) {
	var b = a.offsetLeft;
	var c = a.offsetParent;
	while (c) {
		b += c.offsetLeft;
		c = c.offsetParent;
	}
	return b;
}


function kb_handler(a) {
	var b = a.target.id;
	if (a.ctrlKey) {
		var c = String.fromCharCode(a.charCode).toLowerCase();
		var d = "";
		switch (c) {
		  case "b":
			d = "bold";
			break;
		  case "i":
			d = "italic";
			break;
		  case "u":
			d = "underline";
			break;
		  default:;
		}
		if (d) {
			FormatText(b, d, true);
			a.preventDefault();
			a.stopPropagation();
		}
	}
}


function trim(a) {
	if (typeof a != "string") {
		return a;
	}
	var b = a;
	var c = b.substring(0, 1);
	while (c == " ") {
		b = b.substring(1, b.length);
		c = b.substring(0, 1);
	}
	c = b.substring(b.length - 1, b.length);
	while (c == " ") {
		b = b.substring(0, b.length - 1);
		c = b.substring(b.length - 1, b.length);
	}
	while (b.indexOf("  ") != -1) {
		b = b.substring(0, b.indexOf("  ")) + b.substring(b.indexOf("  ") + 1, b.length);
	}
	return b;
}

var ns4 = document.layers;
var ie4 = document.all;
var ns6 = document.getElementById && !document.all;
var steditor = 0;
var nsx;
var nsy;
var nstemp;

function drag_dropns(a) {
	if (!ns4) {
		return;
	}
	temp = eval(a);
	temp.captureEvents(Event.MOUSEDOWN | Event.MOUSEUP);
	temp.onmousedown = gons;
	temp.onmousemove = dragns;
	temp.onmouseup = stopns;
}


function gons(e) {
	temp.captureEvents(Event.MOUSEMOVE);
	nsx = e.x;
	nsy = e.y;
}


function dragns(e) {
	if (steditor == 1) {
		temp.moveBy(e.x - nsx, e.y - nsy);
		return false;
	}
}


function stopns() {
	temp.releaseEvents(Event.MOUSEMOVE);
}


function drag_drop(e) {
	if (ie4 && dragapproved) {
		crossobj.style.left = tempx + event.clientX - offsetx + "px";
		crossobj.style.top = tempy + event.clientY - offsety + "px";
		return false;
	} else if (ns6 && dragapproved) {
		crossobj.style.left = tempx + e.clientX - offsetx + "px";
		crossobj.style.top = tempy + e.clientY - offsety + "px";
		return false;
	}
}


function initializedrag(e) {
	crossobj = ns6 ? document.getElementById("insert_pop") : document.all.insert_pop;
	var a = ns6 ? e.target : event.srcElement;
	var b = ns6 ? "HTML" : "BODY";
	while (a.tagName != b && a.id != "insert_title") {
		a = ns6 ? a.parentNode : a.parentElement;
	}
	if (a.id == "insert_title") {
		offsetx = ie4 ? event.clientX : e.clientX;
		offsety = ie4 ? event.clientY : e.clientY;
		tempx = parseInt(crossobj.style.left);
		tempy = parseInt(crossobj.style.top);
		dragapproved = true;
		document.onmousemove = drag_drop;
	}
}

document.onmousedown = initializedrag;
document.onmouseup = new Function("dragapproved=false");

function close_insert_pop() {
	chkVK = 0;
	document.getElementById("insert_pop").style.display = "none";
}


function open_insert_pop(a, b, c, d, e, f) {
	buttonElement = document.getElementById(b + "_" + a);
	frames.insert_elm.location.href = c;
	var X = getOffsetLeft(buttonElement);
	var Y = getOffsetTop(buttonElement) + buttonElement.offsetHeight;
	var g = window.innerWidth ? window.innerWidth : document.body.clientWidth;
	if (X + e > g) {
		X = g - e - 30;
	} else if (X < 0) {
		X = 0;
	}
	document.getElementById("insert_pop").style.left = X + "px";
	document.getElementById("insert_pop").style.top = Y + "px";
	document.getElementById("insert_pop").style.display = "block";
	document.getElementById("insert_pop").style.width = e + "px";
	if (isIE) {
		document.getElementById("insert_elm").style.height = f + 8 + "px";
	} else {
		document.getElementById("insert_elm").style.height = f + "px";
	}
	document.getElementById("change_title").innerHTML = d;
}


function NoError() {
	return true;
}

onerror = NoError;


// ExiteCMS: New wrapSelection function to deal with textarea's with scrollbars
function wrapSelection(a, b, c) {
	var d = document.getElementById(c);
	var e = d.textLength;
	var f = d.selectionStart;
	var g = d.selectionEnd;
	if (g == 1 || g == 2) {
		g = e;
	}
	var h = d.value.substring(0, f);
	var i = d.value.substring(f, g);
	var j = d.value.substring(g, e);
	if (b == "HR" || b == "hr") {
		var startTag = "[" + a + "]";
		var endTag = "";
	} else {
		var k = "";
		if (a == "URL" || a == "url" || a == "MAIL" || a == "mail") {
			if (i == "") {
				k = b.replace("=", "");
			}
		}
		var startTag = "[" + a + b + "]"
		var endTag ="[/" + a + "]";
	}
	var s=d.scrollTop;
	if(typeof d.selectionStart == 'number') {
		// Mozilla, Opera, and other browsers
		if (endTag != "") {
			d.value = h + startTag + i + k + endTag + j;
		} else {
			d.value = h + startTag + j;
		}
		d.focus();
		d.selectionStart=f;
		d.selectionEnd=g+(d.value.length-e);
	} else if(document.selection) {
		// Internet Explorer
		d.focus();
		var range = document.selection.createRange();
		if(range.parentElement() != d) {
			return false;
		}
		if(typeof range.text == 'string') {
			document.selection.createRange().text = startTag + range.text + endTag;
		}
		d.focus();
	} else {
		d.value += startTag + endTag;
		d.focus();
	}
	d.scrollTop=s;
}


function GetSelection(a) {
	if (isIE) {
		return document.selection.createRange().text;
	} else {
		var b = document.getElementById(a);
		var c = b.textLength;
		var d = b.selectionStart;
		var e = b.selectionEnd;
		if (e == 1 || e == 2) {
			e = c;
		}
		return b.value.substring(d, e);
	}
}


function hot_showid2(a, b) {
	document.getElementById(a).style.display = b;
}


function hot_showid(a) {
	var b = document.getElementById(a);
	if (b.style.display == "block") {
		b.style.display = "none";
	} else {
		b.style.display = "block";
	}
}


function hot_createCookie(a, b, c) {
	if (c) {
		var d = new Date;
		d.setTime(d.getTime() + c * 24 * 60 * 60 * 1000);
		var e = "; expires=" + d.toGMTString();
	} else {
		var e = "";
	}
	document.cookie = a + "=" + b + e + "; path=/";
}


function hot_readCookie(a) {
	var b = a + "=";
	var d = document.cookie.split(";");
	for (var i = 0; i < d.length; i++) {
		var c = d[i];
		while (c.charAt(0) == " ") {
			c = c.substring(1, c.length);
		}
		if (c.indexOf(b) == 0) {
			return c.substring(b.length, c.length);
		}
	}
	return null;
}


function hot_eraseCookie(a) {
	hot_createCookie(a, "", -1);
}

document.write("<div id='insert_pop' style='display:none;width:50; position:absolute; top:0; left:0;' >\n");
document.write("<table class=Hoteditor_PopupLayer cellspacing='0' width=100%>\n");
document.write("<tr>\n");
document.write("<td width='100%'><table border='0' cellpadding='0' cellspacing='0' >\n");
document.write("<tr>\n");
document.write("<td id='insert_title' class=Hoteditor_PopupLayer_Title style='cursor: move' width='*'>\n");
document.write("<ilayer>\n");
document.write("<layer onmouseover='steditor=1;' onmouseout='steditor=0'>\n");
document.write("<span id=change_title></span>\n");
document.write("</layer>\n");
document.write("</ilayer>\n");
document.write("</td>\n");
document.write("<td width='1%' class=Hoteditor_PopupLayer_Title ><img title='Close' style=' cursor:pointer' onclick='close_insert_pop();return false' onmouseover=\"this.src='" + styles_folder_path + "/close_popup_over.gif';\" onmouseout=\"this.src='" + styles_folder_path + "/close_popup.gif';\" src=" + styles_folder_path + "/close_popup.gif align=absmiddle></td>\n");
document.write("</tr>\n");
document.write("<tr>\n");
document.write("<td width='100%' colspan='2'>\n");
document.write("<iframe onmouseover=\"hide_it2()\" name='insert_elm' id='insert_elm' frameborder=0 width='100%' height='250' src='' scrolling='no'></iframe>\n");
document.write("</td>\n");
document.write("</tr>\n");
document.write("</table>\n");
document.write("</td>\n");
document.write("</tr>\n");
document.write("</table>\n");
document.write("</div>\n");
if (isSafari) {
	var getSafariSize = "";
	var Color_Title = "blue";
	var array = new Array;
	array[0] = "1";
	array[1] = "2";
	array[2] = "3";
	array[3] = "4";
	array[4] = "5";
	array[5] = "6";
	array[6] = "7";
	document.writeln("<div onclick=\"document.getElementById('Hoteditor_Font_Size').style.display='none';\" class=Hoteditor_PopupLayer id=Hoteditor_Font_Size  style='cursor:pointer;display:none;position:absolute; top:0; left:0;height:" + fontsize_frame_height + ";width:" + fontsize_frame_width + "'><table class=Hoteditor_PopupLayer width=" + fontsize_frame_width + "><tr class=Hoteditor_PopupLayer_Title ><td nowrap>Font Size</td><td><img title='Close' style=' cursor:pointer' onmouseover=\"this.src='" + styles_folder_path + "/close_popup_over.gif';\" onmouseout=\"this.src='" + styles_folder_path + "/close_popup.gif';\" src=" + styles_folder_path + "/close_popup.gif align=absmiddle></td></tr></table><div style='width:" + fontsize_frame_width + ";height:" + fontsize_frame_height + "'><table class=Hoteditor_Select cellpadding=0 cellspacing=0 width=" + fontsize_frame_width + ">\n");
	for (i = 0; i < array.length; i++) {
		if (array[i] == "1") {
			getSafariSize = "8pt";
		} else if (array[i] == "2") {
			getSafariSize = "10pt";
		} else if (array[i] == "3") {
			getSafariSize = "12pt";
		} else if (array[i] == "4") {
			getSafariSize = "14pt";
		} else if (array[i] == "5") {
			getSafariSize = "18pt";
		} else if (array[i] == "6") {
			getSafariSize = "24pt";
		} else if (array[i] == "7") {
			getSafariSize = "36pt";
		}
		document.writeln("<tr><td height=30 valign=middle align=center><div style='cursor:pointer;width:100%' onmousedown=\"document.getElementById('Hoteditor_Font_Size').style.display = 'none';SetFontFormat('" + getSafariSize + "');\" onMouseover=\"this.className='Hoteditor_Select_Over'\" onMouseout=\"this.className='Hoteditor_Select'\"><b><font face= Arial size=" + array[i] + "\">" + array[i] + "</font></b></div></td></tr>\n\n");
	}
	document.writeln("<tr><td><br></td></tr>\n");
	document.writeln("</table></div></div>\n");
	document.writeln("<div onclick=\"document.getElementById('Hoteditor_Font_Name').style.display='none';\" class=Hoteditor_PopupLayer id=Hoteditor_Font_Name  style='cursor:pointer;display:none;position:absolute; top:0; left:0;height:" + fontname_frame_height + ";width:" + fontname_frame_width + "'><table class=Hoteditor_PopupLayer><tr class=Hoteditor_PopupLayer_Title ><td nowrap width=" + fontname_frame_width + "><span style='float:left'>Select Font Face</span><img title='Close' style='float:right; cursor:pointer' onmouseover=\"this.src='" + styles_folder_path + "/close_popup_over.gif';\" onmouseout=\"this.src='" + styles_folder_path + "/close_popup.gif';\" src=" + styles_folder_path + "/close_popup.gif align=absmiddle></td></table><div style='overflow:auto;width:" + fontname_frame_width + ";height:" + fontname_frame_height + "'><table class=Hoteditor_Select cellpadding=0 cellspacing=0 width=" + fontname_frame_width + " height=" + fontname_frame_height + ">\n");
	for (i = 0; i < array_fontname.length; i++) {
		document.writeln("<tr><td><div onmousedown=\"document.getElementById('Hoteditor_Font_Name').style.display = 'none';SetFontFormat('" + array_fontname[i] + "');\" class='Hoteditor_Select' onMouseover=\"this.className='Hoteditor_Select_Over'\" onMouseout=\"this.className='Hoteditor_Select'\"><font size=2 face='" + array_fontname[i] + "'>" + array_fontname[i] + "</font></div></td></tr>\n");
	}
	document.writeln("</table></div></div>\n");
	document.writeln("<div onclick=\"document.getElementById('Hoteditor_Select_Color').style.display='none';\" class=Hoteditor_PopupLayer id=Hoteditor_Select_Color style='cursor:pointer;display:none;position:absolute; top:0; left:0;'><table class=Hoteditor_PopupLayer><tr class=Hoteditor_PopupLayer_Title ><td width=78px nowrap><span style='float:left'>Color</span> <img style='float:right' title='Close' style=' cursor:pointer' onmouseover=\"this.src='" + styles_folder_path + "/close_popup_over.gif';\" onmouseout=\"this.src='" + styles_folder_path + "/close_popup.gif';\" src=" + styles_folder_path + "/close_popup.gif align=absmiddle></td></tr></table><div style='overflow:auto;height:" + fontsize_frame_height + "px'><table class=Hoteditor_Select cellpadding=0 cellspacing=0 >\n");
	for (i = 0; i < array_fontcolor.length; i++) {
		document.writeln("<tr><td><div style='cursor:pointer;width:100%;height:20px;color:" + array_fontcolor[i] + ";background-color:" + array_fontcolor[i] + "' onmousedown=\"document.getElementById('Hoteditor_Select_Color').style.display = 'none';SetFontFormat('" + array_fontcolor[i] + "');\" onMouseover=\"this.style.border='1px solid #F29536';\" onMouseout=\"this.style.border='0px solid #C0C0C0';\">" + array_fontcolor[i] + "</div></td></tr>\n\n");
	}
	document.writeln("</table></div></div>\n");
}

function html_entity_decode(a) {
	a = a.replace(/&lt;/g, "<");
	a = a.replace(/&gt;/g, ">");
	a = a.replace(/&nbsp;/g, " ");
	a = a.replace(/&amp;/g, "&");
	return a;
}


function htmlentities(a) {
	a = a.replace(/</g, "&lt;");
	a = a.replace(/>/g, "&gt;");
	a = a.replace(/&/g, "&amp;");
	return a;
}


function BBCodeToHTML(a) {
	a = a.replace(/&/g, "&amp;");
	a = a.replace(/</g, "&lt;");
	a = a.replace(/>/g, "&gt;");
	a = a.replace(/  /g, "&nbsp;&nbsp;");
	a = a.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
	a = a.replace(/\n+(\[\/list\])/gi, "[/LIST]");
	a = a.replace(/\[list\]\n+/gi, "[LIST]");
	a = a.replace(/\[list=1\]\n+/gi, "[LIST=1]");
	a = a.replace(/\[list=a\]\n+/gi, "[LIST=a]");
	a = a.replace(/\n+\[\/tr\]/gi, "[/TR]");
	a = a.replace(/\n+\[tr\]/gi, "[TR]");
	a = a.replace(/\n+\[td\]/gi, "[TD]");
	a = a.replace(/\n+\[\/table\]/gi, "[/TABLE]");
	a = a.replace(/\[\/table\]$/gi, "[/TABLE]\n");
	a = a.replace(/\n/g, "<br>");
	a = a.replace(/\[hr\]/gi, "<hr>");
	a = a.replace(/\[table\]/gi, "<div><table>");
	a = a.replace(/\[\/table\]/gi, "</table></div><div style='clear:both;'></div>");
	a = a.replace(/\[(\/|)tr\]/gi, "<$1tr>");
	a = a.replace(/\[(\/|)td\]/gi, "<$1td>");
	a = a.replace(/\[(\/|)indent\]/gi, "<$1blockquote>");
	a = a.replace(/\[(sub|sup|strike|s|blockquote|b|i|u)\]/gi, "<$1>");
	a = a.replace(/\[\/(sub|sup|strike|s|blockquote|b|i|u)\]/gi, "</$1>");
	a = a.replace(/\[font=(.*?)\]/gi, "<font face=\"$1\">");
	a = a.replace(/\[color=(.*?)\]/gi, "<font color=\"$1\">");
	a = a.replace(/\[size=(.*?)\]/gi, "<font style=\"font-size:$1px\">");
	a = a.replace(/\[\/(font|color|size)\]/gi, "</font>");
	a = a.replace(/\[highlight=(.*?)\]/gi, "<span style=\"background-color:$1\">");
	a = a.replace(/\[\/highlight\]/gi, "</span>");
	a = a.replace(/\[(center|left|right|justify)\]/gi, "<div align=\"$1\">");
	a = a.replace(/\[\/(center|left|right|justify)\]/gi, "</div>");
	a = a.replace(/\[align=(center|left|right|justify)\]/gi, "<div align=\"$1\">");
	a = a.replace(/\[\/align\]/gi, "</div>");
	a = a.replace(/\[mail=(.*?)\]/gi, "<a href=\"mailto:$1\">");
	a = a.replace(/\[mail\](.*?)\[\/mail\]/gi, "<a href=\"mailto:$1\">$1[/mail]");
	a = a.replace(/\[\/mail\]/gi, "</a>");
	a = a.replace(/\[url=(.*?)\]/gi, "<a href=\"$1\">");
	a = a.replace(/\[url\](.*?)\[\/url\]/gi, "<a href=\"$1\">$1[/url]");
	a = a.replace(/\[\/url\]/gi, "</a>");
	a = a.replace(/\[img\](.*?)\[\/img\]/gi, "<img src=\"$1\">");
	var b = a.match(/\[(list|list=1|list=a)\]/gi);
	a = a.replace(/\[list=1\]/gi, "<ol>");
	a = a.replace(/\[list=a\]/gi, "<ol style=\"list-style-type: lower-alpha\">");
	a = a.replace(/\[list\]/gi, "<ul>");
	a = a.replace(/\[\*\]/gi, "<li>");
	a = a.replace(/<br[^>]*><li>/gi, "<li>");
	a = a.replace(/<br[^>]*> <li>/gi, "<li>");
	a = a.replace(/<br[^>]*><\/li>/gi, "</li>")
	if (b) {
		for (var i = 0; i < b.length; i++) {
			if (b[i].toLowerCase() == "[list]") {
				a = a.replace(/\[\/list\]/i, "</ul>");
			} else if (b[i].toLowerCase() == "[list=1]" ||
				b[i].toLowerCase() == "[list=a]") {
				a = a.replace(/\[\/list\]/i, "</ol>");
			}
		}
	}

	if (isOpera9 || isIE) {
		a = a.replace(/<li>/gi, "</li><li>");
		a = a.replace(/<\/(ol|ul)>/gi, "</li></$1>");
	}
	if (isOpera9) {
		a = a.replace(/<\/table>/gi, "</tr></table>");
		a = a.replace(/<\/tr>/gi, "</td></tr>");
	}
	return a;
}


function AnalyzeHTMLBlock(a, b) {
	var c = "";
	var d = b.style.split(";");
	for (var j = 0; j < d.length; j++) {
		if (d[j] != "" && d[j] != null) {
			var e = d[j].split(":");
			var f = e[0].toLowerCase().replace(/ /g, "");
			f = f.replace(/style=/gi, "");
			if (e[1] == null) {
				var g = "";
			} else {
				var g = e[1].replace(/^ +| +$/g, "");
			}
			if (f == "background-color") {
				if (g.indexOf("rgb(") != -1) {
					var h = RGB2HTML(g);
				} else {
					var h = g;
				}
				c += "[highlight=" + h + "]";
			} else if (f == "vertical-align" && g == "sub") {
				c += "[sub]";
			} else if (f == "vertical-align" && g == "super") {
				c += "[sup]";
			} else if (f == "list-style-type" && g == "lower-alpha") {
				c += "[list=a]";
			} else if (f == "text-align") {
				g = g.toLowerCase();
				c += "[" + g + "]";
			} else if (f == "margin-left" || f == "margin-right") {
				g = parseInt(g) / 40;
				for (var z = 0; z < g; z++) {
					c += "[blockquote]";
				}
			} else if (f == "font-weight") {
				if (g.toUpperCase() == "BOLD" ||
					g.toUpperCase() == "700") {
					c += "[b]";
				}
			} else if (f == "font-style") {
				if (g.toUpperCase() == "ITALIC") {
					c += "[i]";
				}
			} else if (f == "font-family") {
				c += "[font=" + g + "]";
			} else if (f == "font-size") {
				if (g == "8pt" || g == "9pt" || g == "x-small") {
					c += "[size=8]";
				} else if (g == "10pt" || g == "11pt" || g == "small") {
					c += "[size=10]";
				} else if (g == "12pt" || g == "13pt" || g == "medium") {
					c += "[size=12]";
				} else if (parseInt(g) >= 14 && parseInt(g) < 18 ||
					g == "large") {
					c += "[size=14]";
				} else if (parseInt(g) >= 18 && parseInt(g) < 24 ||
					g == "x-large") {
					c += "[size=18]";
				} else if (parseInt(g) >= 24 && parseInt(g) < 36 ||
					g == "xx-large") {
					c += "[size=24]";
				} else if (parseInt(g) >= 36 || g == "-webkit-xxx-large") {
					c += "[size=36]";
				}
			} else if (f == "text-decoration") {
				if (g.toUpperCase() == "UNDERLINE") {
					c += "[u]";
				} else if (g.toUpperCase() == "LINE-THROUGH") {
					c += "[strike]";
				}
			} else if (f == "color") {
				if (g.indexOf("#") == -1) {
					var h = RGB2HTML(g);
				} else {
					var h = g;
				}
				c += "[color=" + h + "]";
			}
		}
	}
	return c;
}


function HTMLToBBCode(a) {
	if (starup == "0") {
		if (isIE) {
			a = a.replace(/<\/li>/gi, "");
			a = a.replace(/<li>/gi, "[*]");
		}
		a = a.replace(/<(abbr|acronym|applet|area|base|baseFont|bdo|bgSound|big|body|button|caption|center|cite|code|col|colGroup|comment|custom|dd|del|dfn|dir|dl|dt|embed|fieldSet|frame|frameSet|head|html|ins|isIndex|kbd|label|legend|link|listing|map|marquee|menu|meta|noBR|noFrames|noScript|optGroup|option|param|plainText|pre|q|rt|ruby|samp|small|tBody|tFoot|tHead|title|tt|wbr|xml|xmp|th|script|form|input|iframe|object|select|textarea)(.*?)>/gi, "");
		a = a.replace(/<\/(abbr|acronym|applet|area|base|baseFont|bdo|bgSound|big|body|button|caption|center|cite|code|col|colGroup|comment|custom|dd|del|dfn|dir|dl|dt|embed|fieldSet|frame|frameSet|head|html|ins|isIndex|kbd|label|legend|link|listing|map|marquee|menu|meta|noBR|noFrames|noScript|optGroup|option|param|plainText|pre|q|rt|ruby|samp|small|tBody|tFoot|tHead|title|tt|wbr|xml|xmp|th|script|form|iframe|object|select|textarea)(.*?)>/gi, "");
		a = a.replace(/\xA0/gi, " ");
		a = a.replace(/<br[^>]*><\/div>/gi, "</div>");
		a = a.replace(/<br[^>]*>/gi, "\n");
		a = a.replace(/<hr[^>]*>/gi, "[hr]");
		a = a.replace(/<\/hr>/gi, "");
		a = a.replace(/<(ul|ol)><\/li>/gi, "<$1>");
		if (isIE || isOpera9) {
			a = a.replace(/<blockquote[^>]*>/gi, "<blockquote>");
		}
		a = a.replace(/  /gi, " ");
		a = a.replace(/<p([^>]*)>/gi, "<DIV$1>");
		a = a.replace(/<\/p([^>]*)>/gi, "</DIV$1>\n");
		a = a.replace(/\t/g, "     ");
		a = a.replace(/\n /g, "\n");
	} else {
		a = htmlentities(a);
	}
	var px = new Array(6, 8,10,12,14,18,24,36);
	var b = a.split("<");
	var c = new Array;
	var e = 0;
	if (b.length > 1) {
		for (var i = 0; i < b.length; i++) {
			if (i > 0) {
				b[i] = "<" + b[i];
			}
			var f = b[i];
			if (f.match(/<(div|span|font|strong|b|u|i|em|var|address|h1|h2|h3|h4|h5|h6|blockquote|img|ol|ul|li|a|strike|s|sub|sup|hr|table|tr|td)( ([^>]{1,}.*?)){0,1}( {0,1}){0,1}>/i)) {
				var g = RegExp.$1;
				var h = RegExp.$3;
				if (h.toLowerCase().indexOf("style=") != -1 &&
					h.toLowerCase().indexOf("font-family:") != -1 &&
					h.toLowerCase().indexOf("face=") != -1) {
					h = h.replace(/face="(.*?)"/gi, "");
				} else if (h.toLowerCase().indexOf("style=") != -1 &&
					h.toLowerCase().indexOf("color:") != -1 &&
					h.toLowerCase().indexOf("color=") != -1) {
					h = h.replace(/color="(.*?)"/gi, "");
				}
				h = h.replace(/(color=|size=|face=|style=)/gi, "|$1");
				h = h.replace(/('|")/g, "");
				h = h.replace(/ \|/g, "|");
				var j = h.split("|");
				var k = new Array;
				if (j != null) {
					for (var z = 0; z < j.length; z++) {
						var l = j[z].split("=");
						k[l[0].toLowerCase()] = j[z].replace(l[0].toLowerCase() + "=", "");
					}
				}
				var m = "";
				var g = g.toLowerCase();
				if (g == "strike" || g == "s") {
					if (k.style) {
						m = "[strike]" + AnalyzeHTMLBlock(g, k);
					} else {
						m = "[strike]";
					}
				} else if (g == "sub") {
					if (k.style) {
						m = "[sub]" + AnalyzeHTMLBlock(g, k);
					} else {
						m = "[sub]";
					}
				} else if (g == "sup") {
					if (k.style) {
						m = "[sup]" + AnalyzeHTMLBlock(g, k);
					} else {
						m = "[sup]";
					}
				} else if (g == "blockquote") {
					if (k.style) {
						m = "[blockquote]" + AnalyzeHTMLBlock(g, k);
					} else {
						m = "[blockquote]";
					}
				} else if (g == "a") {
					var n = f.split(">");
					var o = f.replace(/<a(.*?)href="(.*?)"/gi, "$2");
					o = o.replace(">" + n[1], "");
					var p = o.split(" ");
					o = p[0];
					if (k.style) {
						if (n[1] == o) {
							m = "[url]" + AnalyzeHTMLBlock(g, k);
						} else {
							m = "[url=" + o + "]" + AnalyzeHTMLBlock(g, k);
						}
					} else {
						if (n[1] == o) {
							m = "[url]";
						} else {
							if (o.indexOf("mailto:") != -1) {
								var q = o.replace(/mailto:/i, "");
								if (q == n[1]) {
									m = "[mail]";
								} else {
									m = "[mail=" + q + "]";
								}
							} else {
								m = "[url=" + o + "]";
							}
						}
					}
				} else if (g == "li") {
					if (k.style) {
						m = "[*]" + AnalyzeHTMLBlock(g, k);
					} else {
						m = "[*]";
					}
				} else if (g == "strong" || g == "b") {
					if (k.style) {
						if (k.style.toLowerCase().indexOf("font-weight: bold") != -1 ||
							k.style.toLowerCase().indexOf("font-weight: 700") != -1) {
							m = AnalyzeHTMLBlock(g, k);
						} else {
							m = "[b]" + AnalyzeHTMLBlock(g, k);
						}
					} else {
						m = "[b]";
					}
				} else if (g == "em" ||
					g == "i" || g == "var" || g == "address") {
					if (k.style) {
						if (k.style.toLowerCase().indexOf("font-style: italic") != -1) {
							m = AnalyzeHTMLBlock(g, k);
						} else {
							m = "[i]" + AnalyzeHTMLBlock(g, k);
						}
					} else {
						m = "[i]";
					}
				} else if (g == "u") {
					if (k.style) {
						if (k.style.toLowerCase().indexOf("text-decoration: underline") != -1) {
							m = AnalyzeHTMLBlock(g, k);
						} else {
							m = "[u]" + AnalyzeHTMLBlock(g, k);
						}
					} else {
						m = "[u]";
					}
				} else if (g == "ol") {
					if (k.style) {
						m = AnalyzeHTMLBlock(g, k);
						if (m.indexOf("[list=a]") == -1) {
							m += "[list=1]";
						}
					} else if (k.align) {
						m = "[" + k.align.toLowerCase() + "]" + "[list=1]";
					} else {
						m = "[list=1]";
					}
				} else if (g == "ul") {
					if (k.style) {
						m = AnalyzeHTMLBlock(g, k) + "[list]";
					} else if (k.align) {
						m = "[" + k.align.toLowerCase() + "]" + "[list=1]";
					} else {
						m = "[list]";
					}
				} else if (g == "h1" ||	g == "h2" || g == "h3" || g == "h4" || g == "h5" || g == "h6") {
					m += "[size=" + px[8 - g.substr(1)] + "]";
				} else if (g == "font") {
					if (j.length > 0) {
						for (var r in k) {
							k[r] = k[r].replace(/^ +| +$/g, "");
							if (r == "color") {
								m += "[color=" + k.color + "]";
							} else if (r == "size") {
								if (px[k[r]]) {
									m += "[size=" + px[(k.size)] + "]";
								} else {
									if (k[r] < px[0]) {
										m += "[size=" + px[0] + "]";
									} else if (k[r] > px[7]) {
										m += "[size=" + px[7] + "]";
									} else {
										m += "[size=" + k[r] + "]";
									}
								}
							} else if (r == "face") {
								m += "[font=" + k.face + "]";
							} else if (r == "style") {
								m += AnalyzeHTMLBlock(g, k);
							}
						}
					}
				} else if (g == "div" || g == "span") {
					if (k.style) {
						m = AnalyzeHTMLBlock(g, k);
					} else if (k.align) {
						m = "[" + k.align.toLowerCase() + "]";
					} else {
						m = "[HOTEDITOR_NEW_LINE]";
					}
				} else if (g == "img") {
					if (isSafari) {
						f = f.replace(/<img(.*?)src="(.*?)">/gi, "[img]$2[/img]");
					} else {
						f.match(/<img(.*?)src="(.*?)"(.*?)>/gi);
//						var s = toAbsURL(RegExp.$2);
						var s = RegExp.$2;
						s = s.replace("./", "");
						if (s.toLowerCase().substr(0, 7) != "http://" && s.toLowerCase().substr(0, 1) != "/") {
							var t = document.URL;
							t = t.replace("http://", "");
							var u = t.split("/");
							var v = "http://";
							for (var d = 0; d < u.length; d++) {
								if (d < u.length - 1) {
									v += u[d] + "/";
								}
							}
							f = f.replace(/\<img(.*?)src="(.*?)"(.*?)>/gi, "[img]" + v + s + "[/img]");
						} else {
							f = f.replace(/<img(.*?)src="(.*?)"(.*?)>/gi, "[img]" + s + "[/img]");
						}
					}
				} else if (g == "table") {
					m = "[table]";
				} else if (g == "tr") {
					m = "[tr]";
				} else if (g == "td") {
					m = "[td]";
				}
				b[i] = f.replace(/(<([^>]+)>)/, m);
				if (g != "img") {
					c[e] = m;
					e++;
				}
			} else if (f.match(/<\/(div|span|font|strong|b|u|i|em|var|address|h1|h2|h3|h4|h5|h6|blockquote|ol|ul|li|a|strike|s|sub|sup|table|tr|td)>/i)) {
				e--;
				var w = c.pop();
				if (w != null) {
					var x = "";
					var A = w;
					A = A.replace(/=(.*?)\]/g, "]");
					A = A.replace(/\]/g, "],");
					A = A.replace(/\[(.*?)\]/g, "[/$1]");
					var B = A.split(",");
					B.reverse();
					for (var y = 0; y < B.length; y++) {
						x += B[y];
					}
					x = x.replace(/\[\/\*\]/gi, "");
					b[i] = b[i].replace(/(<([^>]+)>)/, x);
				} else {
					b[i] = b[i].replace(/(<([^>]+)>)/, "");
				}
			} else {
				if (i > 0) b[i] = "";
			}
		}
		var C = b.join("");
	} else {
		var C = a;
	}
	C = C.replace(/&lt;/g, "<");
	C = C.replace(/&gt;/g, ">");
	C = C.replace(/&nbsp;/g, " ");
	C = C.replace(/&amp;/g, "&");
	C = C.replace(/     /g, "\t");
	C = C.replace(/\[HOTEDITOR_NEW_LINE\]/g, "\n");
	C = C.replace(/\[\/HOTEDITOR_NEW_LINE\]\n+/g, "\n");
	C = C.replace(/\[\/HOTEDITOR_NEW_LINE\]/g, "\n");
	if (starup == "0") {
		C = C.replace(/\[\*\]/gi, "\n[*]");
		C = C.replace(/\n\n\[\*\]/gi, "\n[*]");
	}
	C = C.replace(/\[color=#.\w*\]\[\/color\]/gi, "");
	C = C.replace(/\[size=\d\]\[\/size\]/gi, "");
	C = C.replace(/\[highlight=#.\w*\]\[\/hightlight\]/gi, "");
	C = C.replace(/\[b\]\[\/b\]/gi, "");
	C = C.replace(/\[u\]\[\/u\]/gi, "");
	C = C.replace(/\[i\]\[\/i\]/gi, "");
	C = C.replace(/\[left\]\[\/left\]/gi, "");
	C = C.replace(/\[center\]\[\/center\]/gi, "");
	C = C.replace(/\[right\]\[\/right\]/gi, "");
	C = C.replace(/\[justify\]\[\/justify\]/gi, "");
	C = C.replace(/\[blockquote\]\[\/blockquote\]/gi, "");
	C = C.replace(/\[url\]\[\/url\]/gi, "");
	C = C.replace(/\[mail\]\[\/mail\]/gi, "");
	C = C.replace(/\[strike\]\[\/strike\]/gi, "");
	C = C.replace(/\[sub\]\[\/sub\]/gi, "");
	C = C.replace(/\[sup\]\[\/sup\]/gi, "");
	C = C.replace(/\[img\]\[\/img\]/gi, "");
	C = C.replace(/^\n+/, "");
	C = C.replace(/\n+$/, "");
	var D = C.match(/\[table\]/gi);
	var E = C.match(/\[\/table\]/gi);
	if (D && E) {
		if (D.length > E.length) {
			C += "[/table]";
		}
	} else if (D && !E) {
		C += "[/table]";
	}
	if (starup == "0") {
		C = C.replace(/\[\/tr\]/gi, "\n[/tr]");
		C = C.replace(/\[tr\]/gi, "\n[tr]");
		C = C.replace(/\[td\]/gi, "\n[td]");
		C = C.replace(/\[\/table\]/gi, "\n[/table]");
		C = C.replace(/\[\/table\]$/gi, "[/table]\n");
	}
	return C;
}


function RGB2HTML(a) {
	a = a.replace(/rgb\((.*?)\)/gi, "$1");
	a = a.replace(/ /, "");
	var c = a.split(",");
	var r = parseInt(c[0]).toString(16);
	var g = parseInt(c[1]).toString(16);
	var b = parseInt(c[2]).toString(16);
	if (r.length == 1) {
		r = "0" + r;
	}
	if (g.length == 1) {
		g = "0" + g;
	}
	if (b.length == 1) {
		b = "0" + b;
	}
	return "#" + r + g + b;
}

function toAbsURL(s) {
	var l = location, h, p, f, i;
	if (/^\w+:/.test(s)) {
		return s;
	}
	h = l.protocol + '//' + l.host;
	if (s.indexOf('/') == 0) {
		return h + s;
	}
	p = l.pathname.replace(/\/[^\/]*$/, '');
	f = s.match(/\.\.\//g);
	if (f) {
		s = s.substring(f.length * 3);
		for (i = f.length; i--;) {
			p = p.substring(0, p.lastIndexOf('/'));
		}
	}
	return h + p + '/' + s;
} 
