{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: _swfupload.tpl                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-09-26 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template is used to active SWFUpload functionality                 *}
{*                                                                         *}
{***************************************************************************}
<script type="text/javascript" src="{$smarty.const.SWFUPLOAD}swfupload.js"></script>
<script type="text/javascript" src="{$smarty.const.SWFUPLOAD}swfupload.swfobject.js"></script>
<script type="text/javascript" src="{$smarty.const.SWFUPLOAD}swfupload.queue.js"></script>
<script type="text/javascript" src="{$smarty.const.SWFUPLOAD}fileprogress.js"></script>
<script type="text/javascript" src="{$smarty.const.SWFUPLOAD}handlers.js"></script>
{locale_load name="swfupload}
{literal}
<script type="text/javascript">
var swfu;

SWFUpload.onload = function () {
	var settings = {
		flash_url : "{/literal}{$smarty.const.SWFUPLOAD}swfupload_f9.swf{literal}",
		upload_url: "{/literal}{$upload_url|default:""}{literal}",
		post_params: {
			{/literal}{foreach from=$post_parms key=var item=value}
			"{$var}" : "{$value}",
			{/foreach}{literal}
			"{/literal}{$settings.session_name}{literal}" : "{/literal}{$session_name|default:""}{literal}",
			"SWFSESSIONID" : "{/literal}{$session_id|default:""}{literal}"
		},
		file_size_limit : "{/literal}{$file_size|default:"100 Mb"}{literal}",
		file_types : "{/literal}{$file_mask|default:"*.*"}{literal}",
		file_types_description : "{/literal}{$file_desc|default:"All Files"}{literal}",
		file_upload_limit : {/literal}{$upload_limit|default:100}{literal},
		file_queue_limit : {/literal}{$queue_limit|default:0}{literal},
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// The event handler functions are defined in handlers.js
		swfupload_loaded_handler : swfUploadLoaded,
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		queue_complete_handler : queueComplete,	// Queue plugin event
		
		// SWFObject settings
		minimum_flash_version : "9.0.28",
		swfupload_pre_load_handler : swfUploadPreLoad,
		swfupload_load_failed_handler : swfUploadLoadFailed
	};
	
	var locales = {{/literal}
		MovienameExists : "{$locale.MovienameExists}",
		BodyMissing : "{$locale.BodyMissing}",
		FlashMissing : "{$locale.FlashMissing}",
		ToManyArgs : "{$locale.ToManyArgs}",
		InvFunction : "{$locale.InvFunction}",
		EventHandler : "{$locale.EventHandler}",
		EHunknown : "{$locale.EHunknown}",
		ExtMethFailed : "{$locale.ExtMethFailed}",
		USHnoFunc : "{$locale.USHnoFunc}",
		Pending : "{$locale.Pending}",
		QueueToMany : "{$locale.QueueToMany}",
		LimitReached : "{$locale.LimitReached}",
		YouMaySelect : "{$locale.YouMaySelect}",
		UpTo : "{$locale.UpTo}",
		Files : "{$locale.Files}",
		OneFile : "{$locale.OneFile}",
		FileToBig : "{$locale.FileToBig}",
		ZeroByteFile : "{$locale.ZeroByteFile}",
		InvFileType : "{$locale.InvFileType}",
		UnkError : "{$locale.UnkError}",
		Uploading : "{$locale.Uploading}",
		Processing : "{$locale.Processing}",
		UploadError : "{$locale.UploadError}",
		UploadFailed : "{$locale.UploadFailed}",
		ServerIOError : "{$locale.ServerIOError}",
		SecurityError : "{$locale.SecurityError}",
		ValidationError : "{$locale.ValidationError}",
		Cancelled : "{$locale.Cancelled}",
		Stopped : "{$locale.Stopped}",
		FileOne : "{$locale.FileOne}",
		FileMore : "{$locale.FileMore}",
		Uploaded : "{$locale.Uploaded}",
		Remaining : "{$locale.Remaining}"
	{literal}}

	swfu = new SWFUpload(settings, locales);
}
</script>
{/literal}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
