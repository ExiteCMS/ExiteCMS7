/* 
------------------------------------------
	Flipbox written by CrappoMan
	simonpatterson@dsl.pipex.com
------------------------------------------
*/
function flipBox(who) {
	var tmp;
	var status;
	if (document.images['b_' + who].src.indexOf('_on') == -1) { 
		tmp = document.images['b_' + who].src.replace('_off', '_on');
		document.getElementById('box_' + who).style.display = 'none';
		document.images['b_' + who].src = tmp;
		status = '1';
	} else { 
		tmp = document.images['b_' + who].src.replace('_on', '_off');
		document.getElementById('box_' + who).style.display = 'block';
		document.images['b_' + who].src = tmp;
		status = '0';
	}
	createCookie('box_'+who, status, 31);
	return false;
}

// function based on FlipBox, but usable for normal divs
function flipDiv(who) {
	if (document.getElementById(who).style.display == 'block')
	    document.getElementById(who).style.display = 'none';
	else
	    document.getElementById(who).style.display = 'block';
	return false;
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

function show_hide(msg_id) {
	msg_id.style.display = msg_id.style.display == 'none' ? 'block' : 'none';
}

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

function whichBrowser() {
	//test for Firefox/x.x or Firefox x.x (ignoring remaining digits);
	if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
		var ffversion=new Number(RegExp.$1); // capture x.x portion and store as a number
		if (ffversion>=3)
			return "FF 3";
		else if (ffversion>=2)
			return "FF 2";
		else if (ffversion>=1)
			return "FF 1";
	} else {
		//test for MSIE x.x;
		if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
			var ieversion=new Number(RegExp.$1); // capture x.x portion and store as a number
			if (ieversion>=8)
				return "IE 8";
			else if (ieversion>=7)
				return "IE 7";
			else if (ieversion>=6)
				return "IE 6";
			else if (ieversion>=5)
				return "IE 5";
		} else {
			//test for Opera/x.x or Opera x.x (ignoring remaining decimal places);
			if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
				var oprversion=new Number(RegExp.$1); // capture x.x portion and store as a number
				if (oprversion>=10)
					return "OP 10";
				else if (oprversion>=9)
					return "OP 9";
				else if (oprversion>=8)
					return "OP 8";
				else if (oprversion>=7)
					return "OP 7";
				else
					return("n/a");
			} else {
 				return("n/a");
 			}
		}
	}
}
