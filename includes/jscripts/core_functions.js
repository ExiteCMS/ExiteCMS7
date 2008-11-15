/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id:: core_functions.js 1936 2008-10-30 00:09:50Z WanWizard         $|
+----------------------------------------------------------------------+
| Last modified by $Author:: WanWizard                                $|
| Revision number $Rev:: 1936                                         $|
+---------------------------------------------------------------------*/

// Flipbox written by CrappoMan, simonpatterson@dsl.pipex.com
function flipBox(who) {
	var tmp;
	var status;
	if (document.images['b_' + who].src.indexOf('_on') == -1) { 
		tmp = document.images['b_' + who].src.replace('_off', '_on');
		document.getElementById('box_' + who).style.display = 'none';
	    document.getElementById('box_' + who).style.visibility = 'hidden';
		document.images['b_' + who].src = tmp;
		status = '1';
	} else { 
		tmp = document.images['b_' + who].src.replace('_on', '_off');
		document.getElementById('box_' + who).style.display = 'block';
	    document.getElementById('box_' + who).style.visibility = 'visible';
		document.images['b_' + who].src = tmp;
		status = '0';
	}
	createCookie('box_'+who, status, 31);
	return false;
}

// Based on FlipBox, but usable for normal divs
function flipDiv(who) {
	if (document.getElementById(who).style.display == 'block') {
	    document.getElementById(who).style.display = 'none';
	    document.getElementById(who).style.visibility = 'hidden';
	} else {
	    document.getElementById(who).style.display = 'block';
	    document.getElementById(who).style.visibility = 'visible';
	}
	return false;
}

// load the smiley's html, and place it in the innerHTML of 'field'
function loadSmileys(elementid, triggerfield, url, spinner) {
	flipDiv(elementid);
	if (typeof spinner == 'undefined' ) {
		spinner = "ajax-loader.gif";
	}
	var elementHTML = document.getElementById(elementid).innerHTML;
	if (elementHTML.indexOf(spinner) > -1) {
		clientSideInclude(elementid, url)
	}
}

// update the innerHTML of 'id' using an AJAX call
function clientSideInclude(id, url, error) {

	var element = document.getElementById(id);
	if (!element) {
		alert("Bad id " + id + "passed to clientSideInclude. You need a div or span element with this id in your page.");
		return;
	}
	var response = AjaxCall(url);
	if (response != null) {
		element.innerHTML = response;
	} else {
		if (error != null && error) {
			element.innerHTML = "Sorry, your browser does not support XMLHTTPRequest objects. This page requires Internet Explorer 5 or better for Windows, or Firefox for any system, or Safari. Other compatible browsers may also exist.";
		}
	}
}

// simple synchronous AJAX call, return null when it fails
function AjaxCall(url) {

	var req = false;
	if (window.XMLHttpRequest) {
		// For Safari, Firefox, and other non-MS browsers
		try {
			req = new XMLHttpRequest();
		} catch (e) {
			req = false;
		}
	} else if (window.ActiveXObject) {
		// For Internet Explorer on Windows
		try {
			req = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				req = false;
			}
		}
	}
	if (req) {
		try {
			// Synchronous request, wait till we have it all
			req.open('GET', url, false);
			req.send(null);
			if (req.status != 200) {
				return null;
			} else {
				return req.responseText;
			}
		} catch (e) {
			return null;
		}
	} else {
		return null;
	}
}

function addText(elname, wrap1, wrap2) {
	if (document.selection) { // for IE 
		var str = document.selection.createRange().text;
		document.forms['inputform'].elements[elname].focus();
		var sel = document.selection.createRange();
		sel.text = wrap1 + str + wrap2;
		return;
	} else if ((typeof document.forms['inputform'].elements[elname].selectionStart) != 'undefined') { // for Mozilla
		var txtarea = document.forms['inputform'].elements[elname];
		var selLength = txtarea.textLength;
		var selStart = txtarea.selectionStart;
		var selEnd = txtarea.selectionEnd;
		var oldScrollTop = txtarea.scrollTop;
		//if (selEnd == 1 || selEnd == 2)
		//selEnd = selLength;
		var s1 = (txtarea.value).substring(0,selStart);
		var s2 = (txtarea.value).substring(selStart, selEnd)
		var s3 = (txtarea.value).substring(selEnd, selLength);
		txtarea.value = s1 + wrap1 + s2 + wrap2 + s3;
		txtarea.selectionStart = s1.length;
		txtarea.selectionEnd = s1.length + s2.length + wrap1.length + wrap2.length;
		txtarea.scrollTop = oldScrollTop;
		txtarea.focus();
		return;
	} else {
		insertText(elname, wrap1 + wrap2);
	}
}

function addURL(elname) {
	if (document.selection) { // for IE 
		var str = document.selection.createRange().text;
		document.forms['inputform'].elements[elname].focus();
		var sel = document.selection.createRange();
		if (str == "") {
			sel.text = "[url]" + str.substring(0,30) + "...[/url]";
		} else {
			sel.text = "[url=" + str + "]" + str.substring(0,30) + "...[/url]";
		}
		return;
	} else if ((typeof document.forms['inputform'].elements[elname].selectionStart) != 'undefined') { // for Mozilla
		var txtarea = document.forms['inputform'].elements[elname];
		var selLength = txtarea.textLength;
		var selStart = txtarea.selectionStart;
		var selEnd = txtarea.selectionEnd;
		var oldScrollTop = txtarea.scrollTop;
		//if (selEnd == 1 || selEnd == 2)
		//selEnd = selLength;
		var s1 = (txtarea.value).substring(0,selStart);
		var s2 = (txtarea.value).substring(selStart, selEnd)
		var s3 = (txtarea.value).substring(selEnd, selLength);
		if (s2 == "") {
			txtarea.value = s1 + "[url]" + s2.substring(0,30) + "...[/url]" + s3;
		} else {
			txtarea.value = s1 + "[url=" + s2 + "]" + s2.substring(0,30) + "...[/url]" + s3;
		}
		txtarea.selectionStart = s1.length;
		txtarea.selectionEnd = s1.length + s2.length + wrap1.length + wrap2.length;
		txtarea.scrollTop = oldScrollTop;
		txtarea.focus();
		return;
	} else {
		insertText(elname, "[url=<link here>]<text here>[/url]");
	}
}

function insertText(elname, what) {
	if (document.forms['inputform'].elements[elname].createTextRange) {
		document.forms['inputform'].elements[elname].focus();
		document.selection.createRange().duplicate().text = what;
	} else if ((typeof document.forms['inputform'].elements[elname].selectionStart) != 'undefined') { // for Mozilla
		var tarea = document.forms['inputform'].elements[elname];
		var selEnd = tarea.selectionEnd;
		var txtLen = tarea.value.length;
		var txtbefore = tarea.value.substring(0,selEnd);
		var txtafter =  tarea.value.substring(selEnd, txtLen);
		var oldScrollTop = tarea.scrollTop;
		tarea.value = txtbefore + what + txtafter;
		tarea.selectionStart = txtbefore.length + what.length;
		tarea.selectionEnd = txtbefore.length + what.length;
		tarea.scrollTop = oldScrollTop;
		tarea.focus();
	} else {
		document.forms['inputform'].elements[elname].value += what;
		document.forms['inputform'].elements[elname].focus();
	}
}

function incrementalSelect(oSelect, oEvent) {
	var sKeyCode = oEvent.keyCode;
	var sToChar = String.fromCharCode(sKeyCode);
	if(sKeyCode >47 && sKeyCode<91){
		var sNow = new Date().getTime();
		if (oSelect.getAttribute("finder") == null) {
			oSelect.setAttribute("finder", sToChar.toUpperCase())
			oSelect.setAttribute("timer", sNow)
		} else if( sNow > parseInt(oSelect.getAttribute("timer"))+2000) { //Rest all;
			oSelect.setAttribute("finder", sToChar.toUpperCase())
			oSelect.setAttribute("timer", sNow) //reset timer;
		} else {
			oSelect.setAttribute("finder", oSelect.getAttribute("finder")+sToChar.toUpperCase())
			oSelect.setAttribute("timer", sNow); //update timer;
		}
		var sFinder =  oSelect.getAttribute("finder");
		var arrOpt = oSelect.options
		var iLen = arrOpt.length
		for (var i = 0; i < iLen ; i++) {
			sTest  = arrOpt[i].text;
			if (sTest.toUpperCase().indexOf(sFinder) == 0) {
				arrOpt[i].selected = true;
				break;
			}
		}
		event.returnValue = false;
	} else{
		//Not a valid character;
	}
}

// Cookie functions

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

function findPosX(obj) {
	if (obj == null) return false;
	var curleft = 0;
	if(obj.offsetParent) {
		while(1) {
			curleft += obj.offsetLeft;
			if(!obj.offsetParent) break;
			obj = obj.offsetParent;
		}
	} else {
		if(obj.x)  curleft += obj.x;
	}
	return curleft;
}

function findPosY(obj) {
	if (obj == null) return false;
	var curtop = 0;
	if(obj.offsetParent) {
		while(1) {
			curtop += obj.offsetTop;
			if(!obj.offsetParent) break;
	  		obj = obj.offsetParent;
		}
	} else {
		if(obj.y) curtop += obj.y;
	}
	return curtop;
}

// Browser detection

var BrowserDetect = {
	init: function () {
		this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
		this.version = this.searchVersion(navigator.userAgent)
			|| this.searchVersion(navigator.appVersion)
			|| "an unknown version";
		this.OS = this.searchString(this.dataOS) || "an unknown OS";
	},
	searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i].identity;
			}
			else if (dataProp)
				return data[i].identity;
		}
	},
	searchVersion: function (dataString) {
		var index = dataString.indexOf(this.versionSearchString);
		if (index == -1) return;
		return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
	},
	dataBrowser: [
		{ 	string: navigator.userAgent,
			subString: "OmniWeb",
			versionSearch: "OmniWeb/",
			identity: "OmniWeb"
		},
		{
			string: navigator.vendor,
			subString: "Apple",
			identity: "Safari"
		},
		{
			prop: window.opera,
			identity: "Opera"
		},
		{
			string: navigator.vendor,
			subString: "iCab",
			identity: "iCab"
		},
		{
			string: navigator.vendor,
			subString: "KDE",
			identity: "Konqueror"
		},
		{
			string: navigator.userAgent,
			subString: "Firefox",
			identity: "Firefox"
		},
		{
			string: navigator.vendor,
			subString: "Camino",
			identity: "Camino"
		},
		{		// for newer Netscapes (6+)
			string: navigator.userAgent,
			subString: "Netscape",
			identity: "Netscape"
		},
		{
			string: navigator.userAgent,
			subString: "MSIE",
			identity: "Explorer",
			versionSearch: "MSIE"
		},
		{
			string: navigator.userAgent,
			subString: "Gecko",
			identity: "Mozilla",
			versionSearch: "rv"
		},
		{ 		// for older Netscapes (4-)
			string: navigator.userAgent,
			subString: "Mozilla",
			identity: "Netscape",
			versionSearch: "Mozilla"
		}
	],
	dataOS : [
		{
			string: navigator.platform,
			subString: "Win",
			identity: "Windows"
		},
		{
			string: navigator.platform,
			subString: "Mac",
			identity: "Mac"
		},
		{
			string: navigator.platform,
			subString: "Linux",
			identity: "Linux"
		}
	]

};
BrowserDetect.init();
