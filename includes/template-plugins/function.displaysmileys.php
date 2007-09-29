<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {displaysmileys} function plugin
 *
 * Type:     function<br>
 * Name:     displaysmileys<br>
 * Purpose:  returns a list of <img> tags for all defined smileys
 * @author WanWizard <wanwizard at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_displaysmileys($params, &$smarty)
{

    $field = (isset($params['field'])) ? $params['field'] : false;
	if (!$field) return null;
	
	$smiles = "";
	$smileys = array (
		";)" => "wink.gif",
		":|" => "frown.gif",
		":(" => "sad.gif",
		":o" => "shock.gif",
		":p" => "pfft.gif",
		"B)" => "cool.gif",
		":D" => "grin.gif",
		":@" => "angry.gif",
		":thumbleft" => "more/icon_thumleft.gif",
		":thumbright" => "more/icon_thumright.gif",
		"=D&gt;" => "more/eusa_clap.gif",
		"\\:D/" => "more/eusa_dance.gif",
		":D" => "more/biggrin.gif",
		":smt014" => "more/014.gif",
		":boring" => "more/015.gif",
		":smt018" => "more/018.gif",
		":smt022" => "more/022.gif",
		":smt071" => "more/071.gif",
		":smt102" => "more/102.gif",
		":smt100" => "more/100.gif",
		":-D" => "more/003.gif",
		":-)" => "more/001.gif",
		":(" => "more/sad.gif",
		":o" => "more/surprised.gif",
		":shock:" => "more/shock.gif",
		":?" => "more/confused.gif",
		"8)" => "more/cool.gif",
		":lol:" => "more/lol.gif",
		":x" => "more/mad.gif",
		":-x" => "more/icon_mad.gif",
		":P" => "more/icon_razz.gif",
		":razz:" => "more/razz.gif",
		":oops:" => "more/redface.gif",
		":cry:" => "more/cry.gif",
		":evil:" => "more/evil.gif",
		":twisted:" => "more/icon_twisted.gif",
		":roll:" => "more/rolleyes.gif",
		":wink:" => "more/wink.gif",
		";-)" => "more/002.gif",
		":!:" => "more/exclaim.gif",
		":?:" => "more/question.gif",
		":idea:" => "more/idea.gif",
		":arrow:" => "more/arrow.gif",
		":|" => "more/neutral.gif",
		":mrgreen:" => "more/icon_mrgreen.gif",
		":badgrin:" => "more/badgrin.gif",
		":doubt:" => "more/doubt.gif",
		"#-o" => "more/eusa_doh.gif",
		"=P~" => "more/eusa_drool.gif",
		":^o" => "more/eusa_liar.gif",
		"[-X" => "more/eusa_naughty.gif",
		"[-o&lt;" => "more/eusa_pray.gif",
		"8-[" => "more/eusa_shifty.gif",
		"[-(" => "more/eusa_snooty.gif",
		":-k" => "more/eusa_think.gif",
		"](*,)" => "more/eusa_wall.gif",
//		":-\"" => "more/eusa_whistle.gif",
		"O:)" => "more/eusa_angel.gif",
		"=;" => "more/eusa_hand.gif",
		":-&amp;" => "more/eusa_sick.gif",
		":-({|=" => "more/eusa_boohoo.gif",
		":-$" => "more/eusa_shhh.gif",
		":-s" => "more/eusa_eh.gif",
		":-#" => "more/eusa_silenced.gif",
		":smt004" => "more/004.gif",
		":smt005" => "more/005.gif",
		":smt006" => "more/006.gif",
		":smt007" => "more/007.gif",
		":smt008" => "more/008.gif",
		":smt009" => "more/009.gif",
		":smt010" => "more/010.gif",
		":smt011" => "more/011.gif",
		":smt012" => "more/012.gif",
		":smt013" => "more/013.gif",
		":smt016" => "more/016.gif",
		":smt017" => "more/017.gif",
		":smt019" => "more/019.gif",
		":smt020" => "more/020.gif",
		":smt021" => "more/021.gif",
		":smt023" => "more/023.gif",
		":smt024" => "more/024.gif",
		":smt025" => "more/025.gif",
//		":smt026" => "more/026.gif",
//		":smt027" => "more/027.gif",
		":smt028" => "more/028.gif",
		":smt029" => "more/029.gif",
		":smt030" => "more/030.gif",
//		":smt031" => "more/031.gif",
		":smt032" => "more/032.gif",
		":smt033" => "more/033.gif",
		":smt034" => "more/034.gif",
		":smt035" => "more/035.gif",
		":smt036" => "more/036.gif",
		":smt037" => "more/037.gif",
		":smt038" => "more/038.gif",
		":smt039" => "more/039.gif",
		":smt040" => "more/040.gif",
		":smt041" => "more/041.gif",
		":smt042" => "more/042.gif",
		":smt043" => "more/043.gif",
		":smt044" => "more/044.gif",
		":smt045" => "more/045.gif",
		":smt046" => "more/046.gif",
		":smt047" => "more/047.gif",
		":smt048" => "more/048.gif",
		":smt049" => "more/049.gif",
		":smt050" => "more/050.gif",
		":smt051" => "more/051.gif",
		":smt052" => "more/052.gif",
		":smt053" => "more/053.gif",
		":smt054" => "more/054.gif",
//		":smt055" => "more/055.gif",
		":smt056" => "more/056.gif",
		":smt057" => "more/057.gif",
		":smt058" => "more/058.gif",
//		":smt059" => "more/059.gif",
//		":smt060" => "more/060.gif",
		":smt061" => "more/061.gif",
		":smt062" => "more/062.gif",
//		":smt063" => "more/063.gif",
		":smt064" => "more/064.gif",
		":smt065" => "more/065.gif",
//		":smt066" => "more/066.gif",
		":smt067" => "more/067.gif",
		":smt068" => "more/068.gif",
		":smt069" => "more/069.gif",
//		":smt070" => "more/070.gif",
//		":smt073" => "more/073.gif",
		":smt074" => "more/074.gif",
		":smt075" => "more/075.gif",
//		":smt076" => "more/076.gif",
		":smt077" => "more/077.gif",
//		":smt078" => "more/078.gif",
//		":smt079" => "more/079.gif",
		":smt080" => "more/080.gif",
		":smt081" => "more/081.gif",
		":smt082" => "more/082.gif",
		":smt083" => "more/083.gif",
		":smt084" => "more/084.gif",
		":smt085" => "more/085.gif",
		":smt086" => "more/086.gif",
		":smt087" => "more/087.gif",
		":smt088" => "more/088.gif",
		":smt089" => "more/089.gif",
		":smt090" => "more/090.gif",
//		":smt091" => "more/091.gif",
		":smt092" => "more/092.gif",
		":smt093" => "more/093.gif",
		":smt084" => "more/094.gif",
		":smt095" => "more/095.gif",
		":smt096" => "more/096.gif",
		":smt097" => "more/097.gif",
		":smt098" => "more/098.gif",
		":smt099" => "more/099.gif",
		":smt101" => "more/101.gif",
		":smt103" => "more/103.gif",
		":smt104" => "more/104.gif",
		":smt105" => "more/105.gif",
		":smt106" => "more/106.gif",
		":smt107" => "more/107.gif",
		":smt108" => "more/108.gif",
		":smt109" => "more/109.gif",
		":smt110" => "more/110.gif",
		":smt111" => "more/111.gif",
		":smt112" => "more/112.gif",
		":smt113" => "more/113.gif",
		":smt114" => "more/114.gif",
//		":smt115" => "more/115.gif",
//		":smt116" => "more/116.gif",
//		":smt117" => "more/117.gif",
		":smt118" => "more/118.gif",
		":smt119" => "more/119.gif",
		":smt120" => "more/120.gif",
	":)" => "smile.gif"
	);
	foreach($smileys as $key=>$smiley) $smiles .= "<img src='".IMAGES."smiley/$smiley' alt='smiley' onclick=\"insertText('$field', '$key');\" />\n";
	return $smiles;
}
?>