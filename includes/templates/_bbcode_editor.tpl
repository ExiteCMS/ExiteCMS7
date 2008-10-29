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
{* This template is used to display the HotEditor BBcode editor            *}
{*                                                                         *}
{***************************************************************************}
<input type='hidden' id='{$id|default:"message"}' name='{$name|default:"message"}' value='{$message|default:""}' />
<script language="javascript" type="text/javascript">
	var {$prefix|default:""}getdata = document.getElementById("{$id|default:"message"}").value;
	Instantiate("max","{$prefix|default:""}editor", {$prefix|default:""}getdata , "{$width|default:"100%"}", "{$height|default:"150px"}");
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
	{/literal}
</script>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
