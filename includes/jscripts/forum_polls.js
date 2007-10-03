function fp_show(theValue) {
	var theItem = document.getElementById(theValue);
	if(theItem.style.display == '') {
		theItem.style.display = 'none';
	} else { theItem.style.display = ''; }
}
function addUser(toGroup, fromGroup) {
	var listLength = document.getElementById(toGroup).length;
	var selItem = document.getElementById(fromGroup).selectedIndex;
	var selText = document.getElementById(fromGroup).options[selItem].text;
	var selValue = document.getElementById(fromGroup).options[selItem].value;
	var i; var newItem = true;
	for (i = 0; i < listLength; i++) {
		if (document.getElementById(toGroup).options[i].text == selText) {
			newItem = false; break;
		}
	}
	if (newItem) {
		document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);
		document.getElementById(fromGroup).options[selItem] = null;
	}
}
function save_fp_settings() {
	var strValuesCG = new Array();
	var c = 0;
	var boxLength = document.getElementById('grouplist2').length;
	if (boxLength != 0) {
		c = 0;
		for (i = 0; i < boxLength; i++) {
			if (document.getElementById('grouplist2').options[i].value > -1) {
				strValuesCG[c++] = document.getElementById('grouplist2').options[i].value;
			}
		}
	}
	var strValuesCU = new Array();
	var boxLength = document.getElementById('userlist2').length;
	if (boxLength != 0) {
		c = 0;
		for (i = 0; i < boxLength; i++) {
			if (document.getElementById('userlist2').options[i].value > -1) {
				strValuesCU[c++] = document.getElementById('userlist2').options[i].value;
			}
		}
	}
	var strValuesVG = new Array();
	var boxLength = document.getElementById('grouplist4').length;
	if (boxLength != 0) {
		c = 0;
		for (i = 0; i < boxLength; i++) {
			if (document.getElementById('grouplist4').options[i].value > -1) {
				strValuesVG[c++] = document.getElementById('grouplist4').options[i].value;
			}
		}
	}
	var strValuesVU = new Array();
	var boxLength = document.getElementById('userlist4').length;
	if (boxLength != 0) {
		c = 0;
		for (i = 0; i < boxLength; i++) {
			if (document.getElementById('userlist4').options[i].value > -1) {
				strValuesVU[c++] = document.getElementById('userlist4').options[i].value;
			}
		}
	}
	document.forms['fp_settings_form'].create_groups.value = strValuesCG;
	document.forms['fp_settings_form'].create_users.value = strValuesCU;
	document.forms['fp_settings_form'].vote_groups.value = strValuesVG;
	document.forms['fp_settings_form'].vote_users.value = strValuesVU;
	document.forms['fp_settings_form'].submit();
}