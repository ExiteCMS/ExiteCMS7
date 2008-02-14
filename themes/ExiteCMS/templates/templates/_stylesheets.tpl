{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: stylesheets.tpl                                      *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-10-15 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Include template for the website stylesheet, Only define them here!     *}
{*                                                                         *}
{***************************************************************************}
{literal}
<style type='text/css'>
/* ========================================================= */
/* ExiteCMS standard CSS tags. Do NOT remove these!          */
/* ========================================================= */

/* --- General settings ------------------------------------ */

						/* text anchors */
a						{ color:#333333; text-decoration:none; }
a:hover					{ color:#333333; text-decoration:underline; }

						/* text anchors in side panels */
a.side					{ color:#333333; text-decoration:none; }
a:hover.side			{ color:#333333; text-decoration:underline; }

						/* image anchors */
a img					{ border:none; }

						/* wiki links */
a.wiki_link:link,a.wiki_link:visited { color:#9c0204; text-decoration:none; border-width:0px 0px 1px 0px; border-style:dotted; border-color:#9c0204; }

						/* horizontal lines */
hr						{ border:none; color:#bbb; background-color:#bbb; height:1px; }

						/* dropdowns with options */
optgroup				{ padding-bottom:5px; font-style:normal; }

/* --- HTML form settings ---------------------------------- */

						/* general form definition */
form					{ margin:0px; }

						/* buttons */
.button					{ font-size:90%; font-weight:normal; font-family:"trebuchet ms", Tahoma, Arial, Verdana, Sans-Serif; width:auto; overflow:visible; font-weight: bold; color:#ffffff; background-color:#666; border-top:1px #bbb solid; border-left:1px #bbb solid; border-bottom:1px #000 solid; border-right:1px #000 solid; cursor:pointer; margin:2px 0px 2px 0px; padding:0px; }
.button:hover			{ color:#666; background-color:#ddd; border-top:1px #999 solid; border-left:1px #999 solid; border-bottom:1px #fff solid; border-right:1px #fff solid; }

						/* text entry fields */
.textbox				{ font-size:1em; font-family:Verdana, Arial,Sans-Serif; color:#444; background-color:#fff; border:1px #444 solid; }

/* --- Website template ------------------------------------ */

						/* page body (normal/user mode + maintenance/webmaster mode) */
body					{ font-size:1em; font-family:Verdana,Arial,Sans-Serif; margin:4px 20px 5px 20px; }
.body 					{ color:#444; background-color:#aaa; }
.body-maint				{ color:#444; background-color:{/literal}{$settings.maintenance_color}{literal}; }

						/* content body, can be different from the body when centered */
.content				{ }

						/* column definitions (left, center, right) */
.side-border-left		{ padding:0px 5px 0px 0px; }
.main-bg				{ color:#444; padding:0px 0px 5px 0px; }
.side-border-right		{ padding:0px 0px 0px 5px; }

						/* panels in the center column */
.main-body 				{ color:#444; background-color:#fff; padding:4px 4px 6px 4px; }

						/* panels in the left & right columns (with and without padding, p.e. for images) */
.side-body				{ color:#000; background-color:#fff; padding:4px 4px 6px 4px; }
.side-body-nm			{ color:#000; background-color:#fff; }

						/* tags for sub texts, captions, etc. */ 
.small					{ font-weight:normal; color:#444; }
.small2					{ font-weight:normal; color:#666; }
.smallalt				{ font-weight:normal; color:#888; }

						/* title bars within a panel */
.infobar 				{ font-weight:normal; font-family:"trebuchet ms", Verdana, Arial, Sans-serif; color:#333; background-color:#ddd; padding:3px 4px 3px 4px; }

/* --- Browser window centered div ------------------------- */

.splashscreen-h			{ font-size:0.8em; width:800px; position: absolute; top: 50%; left: 0px; width: 100%; margin-top: -150px; text-align: center; min-width: 900px; }
.splashscreen-v			{ color:#444; background-color:#fff; border-left:3px solid #ccc; border-top:3px solid #ccc; border-right:3px solid #333; border-bottom:3px solid #333; position: relative; text-align: left; width: 500px; height: 250px; margin: 0px auto; }

/* --- Header ---------------------------------------------- */

						/* header definition and background image */
.headerbanner			{ color:#333; background-color:#eee; background-image:url(/themes/ExiteCMS/images/exitecms_bg.gif); padding:0px; }

						/* header menu bar */
.headermenu				{ font-weight:normal; font-family:"trebuchet ms", Verdana, Arial, Sans-serif; color:#ffffff; background-color:#666666; padding:0px 2px 0px 2px; }

						/* header menu links */
.headermenuitem			{ font-weight:normal; color:#fff; }

/* --- Panels ---------------------------------------------- */

						/* center panel cell definitions */
.main-panel				{ padding: 5px 5px 5px 5px; margin:0px 0px 5px 0px; border:1px #ddd solid; }

						/* side panel cell definitions */
.side-panel				{ padding:0px 0px 0px 0px; margin:0px 0px 5px 0px; border:1px #ddd solid; }

						/* center panel title */
.cap-main				{ font-weight:normal; font-family:"trebuchet ms", Verdana, Arial, Sans-serif; color:#ffffff; background-color: #666666; font-size:1.2em; padding:0px 0px 0px 2px; }

						/* side panel title */
.sub-cap-main			{ font-weight:normal; font-family:"trebuchet ms", Verdana, Arial, Sans-serif; color:#ffffff; background-color:#666666; padding:0px 2px 0px 2px; }

						/* labels and separators in side panels */
.side-label				{ color:#333; background-color:#ddd; margin:2px 0px 2px 0px; padding:1px 0px 1px 8px; white-space:nowrap;overflow:hidden; }

						/* labels and separators in body panels */
.main-label				{ color:#333; background-color:#ddd; padding:4px 4px 4px 4px; margin:0px 0px 5px 0px; white-space:nowrap;overflow:hidden; }

						/* links in side panels */
.side-label-link		{ white-space:nowrap;overflow:hidden;}

						/* positioning of side-panel open/close buttons */
.side-label-button		{ position:relative; float:right; top:2px; right:2px; }

						/* horizontal lines in side-panels */
hr.side-hr				{ height:2px; }

/* --- Footer ---------------------------------------------- */

.footer					{ text-align:center; color: #555; line-height:125%; }
.footer img 			{ border: 0px; vertical-align: -30%; }

/* --- Panel content layouts-------------------------------- */

						/* standard table borders */
.tbl-border				{ background-color:#ddd; color:#ddd; }

						/* borderless tables */
.tbl					{ padding:4px; color:#444; background-color:#fff; }

						/* table cells - color 1 */
.tbl1					{ padding:4px; color:#444; background-color:#fff; }

						/* table cells - color 2 */
.tbl2					{ padding:4px; color:#444; background-color:#eee; }

						/* user-info cell header */
.tbl_top_left			{ color:#333; background-color:#eee; font-weight: bold; padding:4px; border-left: 1px solid #bbbbbb; border-top: 1px solid #bbbbbb; }

						/* user-info cell of a message */
.tbl_left				{ color:#333; background-color:#fff; padding:4px; border-left: 1px solid #bbbbbb; border-top: 1px solid #bbbbbb; }

						/* button row at the bottom the user-info cell */
.tbl_left_bottom		{ color:#333; background-color:#fff; padding:4px; border-left: 1px solid #bbbbbb; border-bottom: 1px solid #bbbbbb; }

						/* message subject  */
.tbl_top_mid			{ color:#333; background-color:#eee; font-weight: bold; padding:4px; border-top: 1px solid #bbbbbb; }

						/* button row in the message header */
.tbl_top_right			{ color:#333333; background-color:#eee; padding:4px; border-top: 1px solid #bbbbbb; border-right: 1px solid #bbbbbb; }

						/* message body */
.tbl_right		 		{ color:#333; background-color:#fff; padding:4px; border: 1px solid #bbbbbb; }

						/* message body, unread message */
.unread					{ color:#000; background-color:#eee; padding:4px; border: 1px solid #bbbbbb; }

						/* links in the message body */
.tbl_right a			{ text-decoration:underline; }

						/* quotes within the message body */
.quote					{ color:#333; background-color:#ddd; padding:4px; margin:5px 10px 5px 10px; border:1px #444 solid; }

						/* code sections within the message body */
.codeblock 				{ font-family: courier, monospace; font-size:110%; margin:5px 20px 5px 20px; overflow-x:auto; white-space: nowrap}
.code 					{ margin:0px; padding:0 0 0 5px; border:1px #bbb solid; background-color:#fff; overflow-x:hidden}
.codenr 				{ margin:0px; padding:0px 3px 0px 3px; border:1px #bbb solid; background-color:#ddd; float:left; }

						/* thumbnail caption in the message body */
.thumbnail				{ color:#fff;background-color:#333;text-align:center; }
a.thumbnail				{ color: #ddd; }

						/* horizontal pollbar definition */
.poll 					{ height:12px; border:1px #000 solid; }

/* --- Upgrade admin module -------------------------------- */

						/* revision title */
.rev_title				{ color:#333; font-weight:bold; }
						/* revision title for major revisions */
.rev_major				{ color:#9c0204; font-weight:bold; }

						/* revision description text */
.rev_desc				{ }

/* ========================================================= */
/* Add your own custom CSS tags below this line!             */
/* ========================================================= */
</style>
{/literal}
{***************************************************************************}
{* End of Template                                                         *}
{***************************************************************************}
