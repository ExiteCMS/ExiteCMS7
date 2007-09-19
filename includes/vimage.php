<?php
/*---------------------------------------------------+
| PLi-Fusion Content Management System               |
+----------------------------------------------------+
| Copyright 2007 WanWizard (wanwizard@gmail.com)     |
| http://www.pli-images.org/pli-fusion               |
+----------------------------------------------------+
| Some portions copyright ? 2002 - 2006 Nick Jones   |
| http://www.php-fusion.co.uk/                       |
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/core_functions.php";

// Create Validation image if $vimage is set and die();
// colorful capcha image generator by amra (www.sumotoy.net)
if (isset($_SERVER['SERVER_SOFTWARE'])) {
	$check_url = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
	if (isset($vimage)) {
		if (preg_match("/^[0-9a-z]{32}$/", $vimage)) {
			function rgb_grayscale( $rgb ) {
				$color['r'] = 0.299 * $rgb['r'] + 0.587 * $rgb['g'] + 0.114 * $rgb['b'];
				$color['g'] = 0.299 * $rgb['r'] + 0.587 * $rgb['g'] + 0.114 * $rgb['b'];
				$color['b'] = 0.299 * $rgb['r'] + 0.587 * $rgb['g'] + 0.114 * $rgb['b'];
				return $color;
			}
			function rgb_complementary($rgb) {
				$color['r'] = 255 - $rgb['r'];
				$color['g'] = 255 - $rgb['g'];
				$color['b'] = 255 - $rgb['b'];
				return $color;
			}
			function rgb_rand($min=0,$max=255) {
				$color['r'] = rand($min,$max);
				$color['g'] = rand($min,$max);
				$color['b'] = rand($min,$max);
				return $color;
			}
			function rgb_create($r=0,$g=0,$b=0) {
				$color['r'] = $r;
				$color['g'] = $g;
				$color['b'] = $b;
				return $color;
			}
			function rgb_merge($lhs, $rhs ) {
				$color['r'] = ($lhs['r'] + $rhs['r']) >> 1;
				$color['g'] = ($lhs['g'] + $rhs['g']) >> 1;
				$color['b'] = ($lhs['b'] + $rhs['b']) >> 1;
				return $color;
			}
			$vres = dbquery("SELECT * FROM ".$db_prefix."vcode WHERE vcode_2='$vimage'");
			if (dbrows($vres)) {
				$vdata = dbarray($vres);
				//srand((double) microtime() * 1000000);
				$im = imagecreate(120,30);
				$strt = 0;
				$rgb = array();
				$rgb['background'] = rgb_rand(0,255);
				$rgb['foreground'] = rgb_grayscale(rgb_complementary($rgb['background']));
				if ( $rgb['foreground']['r'] > 127) {
					$strt = -127;
					$rgb['foreground'] = rgb_merge($rgb['foreground'],rgb_create(255,255,255));
					$rgb['shadow'] = rgb_merge(rgb_complementary($rgb['foreground']),rgb_create(0,0,0 ));
				} else {
					$strt = 0;
					$rgb['foreground'] = rgb_merge($rgb['foreground'],rgb_create(0,0,0));
					$rgb['shadow'] = rgb_merge(rgb_complementary($rgb['foreground']),rgb_create(255,255,255));
				}
				$color = array();
				foreach($rgb as $name => $value) {
					$color[$name] = imagecolorallocate($im,$value['r'],$value['g'],$value['b']);
				}
				imagefilledrectangle($im,0,0,120,30,$color['background']);
				for ($i = 0; $i < rand(5,9); $i++ ) {
					$x = rand(0,120);
					$y = rand(0,30);
					$f = rand(0,5);
					$c = rgb_grayscale(rgb_rand(127 - $strt,254 - $strt));
					$color[$i] = imagecolorallocate($im,$c['r'],$c['g'],$c['b']);
					imagestring($im,$f,$x,$y,$vdata['vcode_1'],$color[$i] );
				}
				$x = (120 - (ImageFontWidth(7) * strlen($vdata['vcode_1']))) >> 1;
				$y = (30 - ImageFontHeight(7)) >> 1;
				imagestring($im,7,$x + 1,$y + 1,$vdata['vcode_1'],$color['shadow'] );
				imagestring($im,7,$x,$y,$vdata['vcode_1'],$color['foreground'] );
				header('Content-type: image/png');
				imagepng($im);
				foreach($color as $name => $value) {
					imagecolordeallocate($im,$value);
				}
				ImageDestroy($im);
			}
		}
		die();
		break;
	}
}
?>