{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.albums.tpl                                      *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2008-09-19 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Template for the main module 'albums'                                   *}
{*                                                                         *}
{***************************************************************************}
{foreach from=$errormessages item=errormessage name=errormessage}
	{if $smarty.foreach.errormessage.first}
		{include file="_opentable.tpl" name=$_name title="" state=$_state style=$_style}
		<div class='errors'>
	{/if}
	{$errormessage}<br />
	{if $smarty.foreach.errormessage.last}
		</div>
		{include file="_closetable.tpl"}
	{/if}
{/foreach}

{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}

{if $type == "album"}

	{if $action == "add" || $action == "edit"}
		{include file="main.albums.albumedit.tpl"}
	{elseif $action == "view"}
		{include file="main.albums.albumview.tpl"}
	{elseif $action == "upload"}
		{include file="main.albums.upload.tpl"}
	{elseif $action == "delete"}
		{include file="main.albums.albumdelete.tpl"}
	{/if}

{elseif $type == "gallery"}

	{if $action == "add" || $action == "edit"}
		{include file="main.albums.galleryedit.tpl"}
	{elseif $action == "view"}
		{include file="main.albums.galleryview.tpl"}
	{elseif $action == "delete"}
		{include file="main.albums.gallerydelete.tpl"}
	{/if}

{elseif $type == "photo"}

	{if $action == "view"}
		{include file="main.albums.photoview.tpl"}
	{elseif $action == "delete"}
		{include file="main.albums.photodelete.tpl"}
	{/if}

{else}

	{include file="main.albums.list.tpl"}

{/if}

{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
