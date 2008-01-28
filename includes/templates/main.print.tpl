{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.print.tpl                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-07 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'print'                                    *}
{*                                                                         *}
{***************************************************************************}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{$settings.sitename}</title>
<meta http-equiv='Content-Type' content='text/html; charset={$settings.charset}' />
<meta name='description' content='{$settings.description}' />
<meta name='keywords' content='{$settings.keywords}' />
{literal}<style type="text/css">
body 	{ font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:14px; }
hr 		{ height:1px;color:#ccc; }
.small 	{ font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px; }
.small2 { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px;color:#666; }
</style>{/literal}
</head>
<body>
{if $type == "A"}
	<b>{$data.article_subject}</b>
	<br />
	<span class='small'>{$locale.400}{$data.user_name}{$locale.401}{$data.article_datestamp|date_format:"longdate"}</span>
	<hr />
	{$data.article}
{elseif $type == "B"}
	<b>{$data.blog_subject}</b>
	<br />
	<span class='small'>{$locale.400}{$data.user_name}{$locale.401}{$data.blog_datestamp|date_format:"longdate"}</span>
	<hr />
	{$data.blog_text}
{elseif $type == "N"}
	<b>{$data.news_subject}</b>
	<br />
	<span class='small'>{$locale.400}{$data.user_name}{$locale.401}{$data.news_datestamp|date_format:"longdate"}</span>
	<hr />
	{$data.news}
	{if $data.news_extended|default:"" != ""}
		<hr />
		<b>{$locale.402}</b>
		<hr />
		{$data.news_extended}
	{/if}
{/if}
</body>
</html>
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}