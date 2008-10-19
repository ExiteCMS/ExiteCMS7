{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: main.albums.list.tpl                                 *}
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
{* Template for the main module 'albums', album/gallery overview list      *}
{*                                                                         *}
{***************************************************************************}
<script type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/mootools-1.2-core-yc.js"></script>
<script type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/mootools-1.2-more.js"></script> 
<script type="text/javascript" src="{$smarty.const.INCLUDES}jscripts/milkbox.js"></script>
<script type="text/javascript">
var MilkboxHeight = readCookie('height') - 260;
var MilkboxWidth = readCookie('width') - 100;
	window.addEvent('load', function(){ldelim}
	Milkbox.setOptions({ldelim}'max_height':MilkboxHeight, 'max_width':MilkboxWidth{rdelim});
	{rdelim});
</script>
{if $can_create}
	<table cellpadding='5' cellspacing='1' width='100%' class='tbl-border'>
		<tr>
			<td class='tbl1'>
				{buttonlink name=$locale.422 link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=add"}
			</td>
			<td class='tbl1'>
				{$locale.465}
			</td>
		</tr>
		{if $album_count}
		<tr>
			<td class='tbl1'>
				{buttonlink name=$locale.435 link=$smarty.const.FUSION_SELF|cat:"?type=gallery&amp;action=add"}
			</td>
			<td class='tbl1'>
				{$locale.466}
			</td>
		</tr>
		{/if}
	</table>
	{include file="_closetable.tpl"}
	{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
{/if}
{if $rows > $settings.albums_per_page}
	{makepagenav start=$rowstart count=$settings.albums_per_page total=$rows range=$settings.navbar_range link=$smarty.const.FUSION_SELF|cat:"?"}
{/if}
	{math equation="(100-x)/x" x=$columns format="%u" assign="colwidth"}							{* width per column  *}
	{if $columns == 1}{assign var="colcount" value="1"}
	{elseif $columns == 2}{assign var="colcount" value="1,2"}
	{elseif $columns == 3}{assign var="colcount" value="1,2,3"}
	{/if}
	{section name=id loop=$collection}
		{cycle name=column values=$colcount assign="column" print=no} 										{* keep track of the current column *}
		{if $smarty.section.id.first}
			{math equation="x - (x%y)" x=$collection_count y=$columns format="%u" assign="fullrows"}
			{math equation="x - y" x=$collection_count y=$fullrows format="%u" assign="remainder"}
			{if $remainder > 0}
				{math equation="(100 - z + y) / (z - y)" y=$fullrows z=$collection_count format="%u" assign="lastwidth"}	{* width last rows columns *}
			{/if}
			<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
		{/if}
			{if $column == 1}<tr>{/if}
				<td width='1%' class='tbl1' align='center'>
					{if $collection[id].album_id}
					<a href='{$smarty.const.FUSION_SELF}?type=album&amp;action=view&amp;album_id={$collection[id].album_id}'>
						<img src='{$collection[id].photo_thumb}' alt='{$collection[id].album_title}' title='{$collection[id].album_title} - {ssprintf format=$locale.467 var1=$collection[id].count}' class='album_thumb' />
					</a>
					{/if}
					{if $collection[id].gallery_id}
					<a href='{$smarty.const.FUSION_SELF}?type=gallery&amp;action=view&amp;gallery_id={$collection[id].gallery_id}'>
						<img src='{$collection[id].photo_thumb}' alt='{$collection[id].gallery_title}' title='{$collection[id].gallery_title} - {ssprintf format=$locale.468 var1=$collection[id].count}' class='album_thumb' />
					</a>
					{/if}
				</td>
				{if $smarty.section.id.last && $smarty.section.id.iteration > $fullrows}
				<td width='{$lastwidth}%' colspan='{math equation="1+(x-y)*2" x=$columns y=$remainder}' class='tbl1' style='vertical-align:top'>
				{else}
				<td width='{$colwidth}%' class='tbl1' style='vertical-align:top'>
				{/if}
					<table class='infobar' width='100%' cellspacing='0' cellpadding='0'>
					    <tr >
					        <td align='left'>
								{if $collection[id].album_id}
									<b>{$locale.469}</b>
									<a href='{$smarty.const.FUSION_SELF}?type=album&amp;action=view&amp;album_id={$collection[id].album_id}' title='{ssprintf format=$locale.467 var1=$collection[id].count}'>{$collection[id].album_title}</a>
								{/if}
								{if $collection[id].gallery_id}
									<b>{$locale.470}</b>
									<a href='{$smarty.const.FUSION_SELF}?type=gallery&amp;action=view&amp;gallery_id={$collection[id].gallery_id}' title='{ssprintf format=$locale.468 var1=$collection[id].count}'>{$collection[id].gallery_title}</a>
								{/if}
					        </td>
					        <td align='right'>
								{if $collection[id].album_id}
									{if $collection[id].slideshow|@count}
										{imagelink script="yes" link="Milkbox.autoPlay("|cat:$smarty.ldelim|cat:"gallery:Milkbox.galleries["|cat:$smarty.section.id.index|cat:"],index:0,delay:10"|cat:$smarty.rdelim|cat:");" image="images.gif" alt=$locale.471 title=$locale.471}
									{/if}
									{if $collection[id].can_edit}
										{imagelink link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=edit&amp;album_id="|cat:$collection[id].album_id image="image_edit.gif" alt=$locale.472 title=$locale.472}
										{imagelink link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=upload&amp;album_id="|cat:$collection[id].album_id image="image_add.gif" alt=$locale.473 title=$locale.473}
										{imagelink link=$smarty.const.FUSION_SELF|cat:"?type=album&amp;action=delete&amp;album_id="|cat:$collection[id].album_id image="image_delete.gif" alt=$locale.474 title=$locale.474}
									{/if}
								{/if}
								{if $collection[id].gallery_id}
									{if $collection[id].slideshow|@count}
										{imagelink script="yes" link="Milkbox.autoPlay("|cat:$smarty.ldelim|cat:"gallery:Milkbox.galleries["|cat:$smarty.section.id.index|cat:"],index:0,delay:10"|cat:$smarty.rdelim|cat:");" image="images.gif" alt=$locale.471 title=$locale.471}
									{/if}
									{if $collection[id].can_edit}
										{imagelink link=$smarty.const.FUSION_SELF|cat:"?type=gallery&amp;action=edit&amp;gallery_id="|cat:$collection[id].gallery_id image="image_edit.gif" alt=$locale.475 title=$locale.475}
										{imagelink link=$smarty.const.FUSION_SELF|cat:"?type=gallery&amp;action=delete&amp;gallery_id="|cat:$collection[id].gallery_id image="image_delete.gif" alt=$locale.476 title=$locale.476}
									{/if}
								{/if}
					        </td>
					    </tr>
					    <tr>
					    	<td colspan='2'>
								{if $collection[id].album_id}
									<span class='small2' style='white-space:nowrap'>
										<b>{$locale.477}</b> {if $smarty.const.iMEMBER}<a href='profile.php?lookup={$collection[id].user_id}'>{/if}{$collection[id].user_name}{if $smarty.const.iMEMBER}</a>{/if}
									</span>
									&middot;
									<span class='small2' style='white-space:nowrap'>
										<b>{$locale.478}</b> {$collection[id].album_datestamp|date_format:'%B %e, %Y'}
									</span>
									&middot;
									<span class='small2' style='white-space:nowrap'>
										<b>{$locale.460}</b> {$collection[id].album_count}
									</span>
								{/if}
								{if $collection[id].gallery_id}
									<span class='small2' style='white-space:nowrap'>
										<b>{$locale.477}</b> {if $smarty.const.iMEMBER}<a href='profile.php?lookup={$collection[id].user_id}'>{/if}{$collection[id].user_name}{if $smarty.const.iMEMBER}</a>{/if}
									</span>
									&middot;
									<span class='small2' style='white-space:nowrap'>
										<b>{$locale.478}</b> {$collection[id].gallery_datestamp|date_format:'%B %e, %Y'}
									</span>
									&middot;
									<span class='small2' style='white-space:nowrap'>
										<b>{$locale.460}</b> {$collection[id].gallery_count}
									</span>
								{/if}
								{section name=id2 loop=$collection[id].slideshow}
									<a href="{$smarty.const.PHOTOS}{$collection[id].slideshow[id2].photo_original}" rel="milkbox[gall{$smarty.section.id.index}]" title="{$collection[id].slideshow[id2].parsed_description|escape:'html'}"></a>
								{sectionelse}
									<a href="{$smarty.const.IMAGES}photonotfound.jpg" rel="milkbox[gall{$smarty.section.id.index}]" title=""></a>
								{/section}
					    	</td>
					    </tr>
					</table>
					{if $collection[id].description|default:"" != ""}
						{$collection[id].description}
					{/if}
				</td>
			{if $column == $columns}</tr>{/if}
		{if $smarty.section.id.last}
			{if $column != $columns}
				</tr>
			{/if}
		</table>
		{if $rows > $settings.albums_per_page}
			{makepagenav start=$rowstart count=$settings.albums_per_page total=$rows range=$settings.navbar_range link=$smarty.const.FUSION_SELF|cat:"?"}
		{/if}
		{/if}
	{sectionelse}
		<center>
			<br />
			{$locale.401}
			<br /><br />
		</center>
	{/section}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
