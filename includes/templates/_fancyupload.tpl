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
{* $Id:: _swfupload.tpl 1935 2008-10-29 23:42:42Z WanWizard               $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author:: WanWizard                                   $*}
{* Revision number $Rev:: 1935                                            $*}
{***************************************************************************}
{*                                                                         *}
{* This template is used to activate FancyUpload functionality             *}
{*                                                                         *}
{***************************************************************************}
<script type="text/javascript" src="{$smarty.const.FANCYUPLOAD}mootools-1.2.1-core.js"></script>
<script type="text/javascript" src="{$smarty.const.FANCYUPLOAD}Swiff.Uploader.js"></script>
<script type="text/javascript" src="{$smarty.const.FANCYUPLOAD}Fx.ProgressBar.js"></script>
<script type="text/javascript" src="{$smarty.const.FANCYUPLOAD}FancyUpload2.js"></script>
<script type="text/javascript">/* <![CDATA[ */{literal}
window.addEvent('load', function() {

	var swiffy = new FancyUpload2($('fancyupload-status'), $('fancyupload-list'), {
		url: $('form-fancyupload').action,
		fieldName: 'Filedata',
		removeButton: '{/literal}{$locale.533}{literal}',
		fileProgress: '{/literal}{$locale.529}{literal}',
		uploadWith: '{/literal}{$locale.534}{literal}',
		timeLeft: '{/literal}{$locale.535}{literal}',
		uploadComplete: '{/literal}{$locale.536}{literal}',
		overallProgress: '{/literal}{$locale.528}{literal}',
		path: '{/literal}{$smarty.const.FANCYUPLOAD}{literal}Swiff.Uploader.swf',
		limitSize: {/literal}{$file_size}{literal},
		limitFiles: {/literal}{$queue_limit|default:25}{literal},
		onLoad: function() {
			$('fancyupload-fallback').destroy();
			$('fancyupload-status').removeClass('hide');
		},
		// The changed parts!
		debug: true, // enable logs, uses console.log
		target: 'fancyupload-browse' // the element for the overlay (Flash 10 only)
	});

	/**
	* Various interactions
	*/

	$('fancyupload-browse').addEvent('click', function() {
		/**
		* Doesn't work anymore with Flash 10: swiffy.browse();
		* FancyUpload moves the Flash movie as overlay over the link.
		* (see opeion "target" above)
		*/
		swiffy.browse();
		return false;
	});

	// define the filter
	var filter = "{/literal}{$file_mask}{literal}";
	if (filter != "") {
		swiffy.options.typeFilter = {'{/literal}{$file_desc}{literal} ({/literal}{$file_mask|replace:";":","}{literal})': '{/literal}{$file_mask}{literal}'};;
	}

	$('fancyupload-clear').addEvent('click', function() {
		swiffy.removeFile();
		return false;
	});

	$('fancyupload-start').addEvent('click', function() {
		swiffy.upload();
		return false;
	});

});
/* ]]> */</script>
{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
