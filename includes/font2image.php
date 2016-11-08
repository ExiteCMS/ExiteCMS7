<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
+----------------------------------------------------------------------+
| Based on http://www.stewartpeak.com/headings/ by Stewart Rosenberger |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id::                                                               $|
+----------------------------------------------------------------------+
| Last modified by $Author::                                          $|
| Revision number $Rev::                                              $|
+---------------------------------------------------------------------*/
if (strpos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false || !defined('INIT_CMS_OK')) die();
/*
	usage: $image_resource = font2image($f2i_array);

	$f2i_array = array();
	$f2i_array['image'] = "png";	                   // Type of image to generate. If not specified, defaults to PNG
	$f2i_array['font_text'] = "Text to convert";       // Text to convert to an image. Required.
	$f2i_array['font_file'] = "font2use.ttf";          // FQN of the TTF font file to use. Required.
	$f2i_array['font_size'] = 10;                      // Font size. Optional. Valid values are 4 to 96. Default = 10.
	$f2i_array['font_color'] = "#000000";              // Font color (html style). Default = black.
	$f2i_array['font_spacing'] = false;                // Use the standard font spacing. Default = false.
	                                                   // Disables kerning, outline and shadows!!
	$f2i_array['font_kerning'] = 0;                    // Additional space between characters in pixels. Default = 0, Max = 20.
	$f2i_array['background_color'] = "#FFFFFF";        // Image background color (html style). Default = white.
	$f2i_array['background_shadow_color'] = "#000000"; // Image shadow background color for a 3D look (html style). Default black.
	$f2i_array['background_shadow_width'] = 0;         // Width of the background shadow of the image. Default = 0, Max = 10.
	$f2i_array['outline_color'] = "";                  // Outline color (html style). If not specified, width is set to 0.
	$f2i_array['outline_width'] = 0;                   // Width of the outline of the character in pixels. Default = 0, Max = 10.
	$f2i_array['shadow_color'] = "";                   // Shadow color (html style). If not specified, width is set to 0.
	$f2i_array['shadow_width'] = 0;                    // Width of the shadow of the character in pixels. Default = 0, Max = 10.
	$f2i_array['background_transparent'] = false;      // Should the background color be marked transparent? Boolean, default = false;
	$f2i_array['background_transparent_color'] = "";   // Optional. If defined, this color will be used for transparancy instead of
	                                                   // the specified background color. Required if a background shadow is defined
	$f2i_array['cache_images'] = false;                // Cache the image after generation? Boolean, default = false.
	$f2i_array['cache_folder'] = "";                   // Directory to store the cached images in.
	                                                   // If not defined or not valid, cache_images will be set to false.
	$f2i_array['cache_prefix'] = false;                // If defined, this will be prepended to the filename of the cached file
	$f2i_array['cache_hash'] = false;                  // Boolean. If false, the text will be used as filename, otherwise a hash is calculated
	$f2i_array['return_link'] = false;                 // If true, the function returns a relative link to the cached image instead of the
	                                                   // image itself. If true, cache_images MUST be set to true as well!!!

	Notes:
	* if the background has a shadow, background transparency is used to create the 3D effect. The text background
	  itself can therefore not be transparent.

*/

function font2image($font2image) {

	// if the parameter array is not defined, check for URL parameters
	if (!isset($font2image) || !is_array($font2image)) {
		fatal_error('Invalid parameter passed.');
	}

	// validate parameters : required fields
	if (empty($font2image['image']) || !in_array($font2image['image'], array('png', 'gif', 'jpg'))) $font2image['image'] = "png";
	if (empty($font2image['font_text'])) fatal_error('No text specified.');
	if(get_magic_quotes_gpc()) {
		$font2image['font_text'] = stripslashes($font2image['font_text']) ;
	}
	$font2image['font_text'] = javascript_to_html($font2image['font_text']) ;

	if (empty($font2image['font_file'])) fatal_error('No font specified.');
	if (!is_readable($font2image['font_file'])) fatal_error('The server is missing the specified font.');

	// validate parameters : optional fields
	if (!isset($font2image['font_size'])) $font2image['font_size'] = 10;
	if (!isNum($font2image['font_size']) || $font2image['font_size'] < 4 || $font2image['font_size'] > 96) fatal_error('Invalid font size.');

	if (!isset($font2image['font_kerning'])) $font2image['font_kerning'] = 0;
	if (!isNum($font2image['font_kerning']) || $font2image['font_kerning'] < 0 || $font2image['font_kerning'] > 20) fatal_error('Invalid font kerning.');

	if (!isset($font2image['font_color'])) $font2image['font_color'] = '#000000';
	if (!preg_match('/^(#[A-F0-9]{6})$/i', $font2image['font_color'])) fatal_error('Invalid font color specified');

	if (!isset($font2image['background_color'])) $font2image['background_color'] = '#ffffff';
	if (!preg_match('/^(#[A-F0-9]{6})$/i', $font2image['background_color'])) fatal_error('Invalid background color specified');

	if (!isset($font2image['background_shadow_color'])) $font2image['background_shadow_color'] = '#000000';
	if (!preg_match('/^(#[A-F0-9]{6})$/i', $font2image['background_shadow_color'])) fatal_error('Invalid background shadow color specified');

	if (!isset($font2image['background_shadow_width'])) $font2image['background_shadow_width'] = 0;
	if (!isNum($font2image['background_shadow_width']) || $font2image['background_shadow_width'] < 0 || $font2image['background_shadow_width'] > 10) fatal_error('Invalid background shadow width.');
	if (!isset($font2image['background_shadow_color'])) $font2image['background_shadow_width'] = 0;
	if ($font2image['background_shadow_width'] && !preg_match('/^(#[A-F0-9]{6})$/i', $font2image['background_shadow_color'])) fatal_error('Invalid shadow color specified');

	if (isset($font2image['background_transparent_color']) && !preg_match('/^(#[A-F0-9]{6})$/i', $font2image['background_shadow_color'])) fatal_error('Invalid background shadow color specified');

	if (!isset($font2image['outline_width'])) $font2image['outline_width'] = 0;
	if (!isNum($font2image['outline_width']) || $font2image['outline_width'] < 0 || $font2image['outline_width'] > 10) fatal_error('Invalid outline width.');
	if (!isset($font2image['outline_color'])) $font2image['outline_width'] = 0;
	if ($font2image['outline_width'] && !preg_match('/^(#[A-F0-9]{6})$/i', $font2image['outline_color'])) fatal_error('Invalid outline color specified');

	if (!isset($font2image['shadow_width'])) $font2image['shadow_width'] = 0;
	if (!isNum($font2image['shadow_width']) || $font2image['shadow_width'] < 0 || $font2image['shadow_width'] > 10) fatal_error('Invalid shadow width.');
	if (!isset($font2image['shadow_color'])) $font2image['shadow_width'] = 0;
	if ($font2image['shadow_width'] && !preg_match('/^(#[A-F0-9]{6})$/i', $font2image['shadow_color'])) fatal_error('Invalid shadow color specified');

	if (!isset($font2image['background_transparent'])) $font2image['background_transparent'] = false;
	if (!isset($font2image['cache_images'])) $font2image['cache_images'] = false;
	if (!isset($font2image['return_link'])) $font2image['return_link'] = false;
	if (!isset($font2image['cache_folder'])) $font2image['cache_images'] = false;
	if ($font2image['cache_folder'] !="" && !is_dir($font2image['cache_folder'])) fatal_error("Cache folder does not exist.");
	if (!isset($font2image['cache_prefix']) || $font2image['cache_prefix'] == false) $font2image['cache_prefix'] = "";
	if (!isset($font2image['cache_hash'])) $font2image['cache_hash'] = false;
	if ($font2image['return_link'] && !$font2image['cache_images']) fatal_error("Can't link without cache.");

	if (!isset($font2image['font_spacing'])) $font2image['font_spacing'] = false;
	if ($font2image['font_spacing']) {
		$font2image['outline_width'] = 0;
		$font2image['shadow_width'] = 0;
		$font2image['font_kerning'] = 0;
	}

	// prerequisites check: check for GD support
	if(!function_exists('ImageCreate')) {
		fatal_error('Server does not support PHP image generation') ;
	}

	// split the text into an array of characters for better positioning later
	$text_length = strlen($font2image['font_text']);
	if ($font2image['font_spacing']) {
		$text = $font2image['font_text'];
	} else {
		$text = array();
		for ($c=0;$c<$text_length;$c++) {
			$text[] = substr($font2image['font_text'], $c, 1);
		}
	}

	// Create the filename for this image, use hash if requested
	if ($font2image['cache_hash']) {
		$hash = "";
		foreach ($font2image as $element) {
			$hash .= $element;
		}
		$hash = md5($hash);
	} else {
		$hash = str_replace(' ', '_', $font2image['font_text']);
	}
	$cache_filename = $font2image['cache_folder'] . '/' . $font2image['cache_prefix'] . $hash . '.' . $font2image['image'] ;

	// Do we have caching enabled for this image?
	if ($font2image['cache_images']) {
		// look for cached copy, if it exists, convert it to a resource and return it
		if($font2image['cache_images'] && is_readable($cache_filename)) {
			// convert the file to a resource
			$imagefile = @getimagesize($cache_filename);
			if (!is_array($imagefile)) {
				fatal_error("Cached image file '".$cache_filename."' is not a valid ".strtoupper($font2image['image'])." image!");
			}
			if ($font2image['return_link']) {
				// check if the cache path is inside the webroot
				if (substr($cache_filename,0,strlen(PATH_ROOT)) != PATH_ROOT) {
					fatal_error("Cached image is not inside the webroot!");
				} else {
					return substr($cache_filename, strlen(PATH_ROOT)-1);
				}
			} else {
				switch ($font2image['image']) {
					case "png":
						$image = imagecreatefrompng($cache_filename);
						break;
					case "jpg":
						$image = imagecreatefromjpeg($cache_filename);
						break;
					case "gif":
						$image = imagecreatefromgif($cache_filename);
						break;
				}
				return $image;
			}
		}
	}

	// create the basic image resource. Determine the exist size of the image, and the 'dip' of the font.
	$dip = get_dip($font2image['font_file'], $font2image['font_size']);
	if ($font2image['font_spacing']) {
		$box = @ImageTTFBBox($font2image['font_size'], 0, $font2image['font_file'], $font2image['font_text']) ;
	} else {
		$box = array(0,0,0,0,0,0,0,0);
		for ($c=0; $c<$text_length;$c++) {
			$charbox = @ImageTTFBBox($font2image['font_size'], 0, $font2image['font_file'], $text[$c]) ;
			$box[0] = max($box[0], $charbox[0]); $box[1] = max($box[1], $charbox[1]);
			$box[2] += $charbox[2]; $box[3] = max($box[3], $charbox[3]);
			$box[4] += $charbox[4]; $box[5] = min($box[5], $charbox[5]);
			$box[6] = max($box[6], $charbox[6]); $box[7] = min($box[7], $charbox[7]);
		}
		// adjust for the pixel between the characters
		$box[2] += $text_length; $box[4] += $text_length;
	}
	if(!$box) {
	    fatal_error('The server could not determine the image size.') ;
	}

	// adjust the box size for shadow and outline
	$box_x = abs($box[2] - $box[0] + 2) + $font2image['shadow_width'] + 2 * $font2image['outline_width'] + $font2image['font_kerning'] * ($text_length - 1) + $font2image['background_shadow_width'] + 1;
	$box_y = abs($box[5] - $dip) + $font2image['shadow_width'] + 2 * $font2image['outline_width'] + $font2image['background_shadow_width'] + 2;
	$image = @ImageCreateTrueColor($box_x, $box_y) ;
	if(!$image) {
	    fatal_error('The server could not create this image.') ;
	}
	// give the image a background color
	if (isset($font2image['background_transparent_color'])) {
		$background_rgb = hex_to_rgb($font2image['background_transparent_color']) ;
	} else {
		$background_rgb = hex_to_rgb($font2image['background_color']) ;
	}
	$font2image['background_transparent_color'] = @ImageColorAllocate($image, $background_rgb['red'], $background_rgb['green'], $background_rgb['blue']) ;
	imagefill($image, 0,0, $font2image['background_transparent_color']);
	// set transparency
	if ($font2image['background_transparent']) {
		ImageColorTransparent($image, $font2image['background_transparent_color']) ;
	}
	// background color and background shadow color
	$background_rgb = hex_to_rgb($font2image['background_shadow_color']) ;
	$font2image['background_shadow_color'] = @ImageColorAllocate($image, $background_rgb['red'], $background_rgb['green'], $background_rgb['blue']) ;
	$background_rgb = hex_to_rgb($font2image['background_color']) ;
	$font2image['background_color'] = @ImageColorAllocate($image, $background_rgb['red'], $background_rgb['green'], $background_rgb['blue']) ;
	// paint the shadow
	imagefilledrectangle($image, $font2image['background_shadow_width'], $font2image['background_shadow_width'], $box_x, $box_y, $font2image['background_shadow_color']);
	// and the text background
	imagefilledrectangle($image, 0, 0, $box_x-$font2image['background_shadow_width']-1, $box_y-$font2image['background_shadow_width']-1, $font2image['background_color']);

	// set shadow if needed
	if ($font2image['shadow_width'] != 0) {
		$shadow_rgb = hex_to_rgb($font2image['shadow_color']) ;
		$font2image['shadow_color'] = @ImageColorAllocate($image, $shadow_rgb['red'], $shadow_rgb['green'], $shadow_rgb['blue']) ;
		// offset
		$text_x = -$box[0] + ($font2image['shadow_color']?$font2image['shadow_width']:0) + $font2image['shadow_width'];
		$text_y = abs($box[5]-$box[3]) - $box[1] + ($font2image['shadow_color']?$font2image['shadow_width']:0) + $font2image['shadow_width'];
		if ($font2image['outline_color']) {
			$text_x += $font2image['outline_width']*2;
			$text_y += $font2image['outline_width']*2;
		}
		for ($c=0; $c<$text_length;$c++) {
			$bbox = ImageTTFText($image, $font2image['font_size'], 0, $text_x, $text_y, $font2image['shadow_color'], $font2image['font_file'], $text[$c]) ;
			$text_x = $bbox[2] + $font2image['font_kerning'];
		}
	}

	// set outline if needed
	if ($font2image['outline_width'] != 0) {
		$outline_rgb = hex_to_rgb($font2image['outline_color']) ;
		$font2image['outline_color'] = @ImageColorAllocate($image, $outline_rgb['red'], $outline_rgb['green'], $outline_rgb['blue']) ;
		$text_x = -$box[0] + ($font2image['outline_color']?$font2image['outline_width']:0) - 1;
		$text_y = abs($box[5]-$box[3])-$box[1] + ($font2image['outline_color']?$font2image['outline_width']:0);
		for ($c=0; $c<$text_length;$c++) {
			$offset = 0;
			for ($text_xo = $text_x-$font2image['outline_width'];$text_xo<=$text_x+1+2*$font2image['outline_width'];$text_xo++) {
				for ($text_yo = $text_y-$font2image['outline_width'];$text_yo<=$text_y+2*$font2image['outline_width'];$text_yo++) {
					$bbox = ImageTTFText($image,$font2image['font_size'], 0, $text_xo, $text_yo, $font2image['outline_color'], $font2image['font_file'], $text[$c]) ;
					if ($offset == 0) $offset = $bbox[2];
				}
			}
			$offset += $font2image['outline_width'];
			$text_x = $offset + $font2image['font_kerning'];
		}
	}

	// allocate colors and draw text
	$font_rgb = hex_to_rgb($font2image['font_color']) ;
	$font2image['font_color'] = @ImageColorAllocate($image, $font_rgb['red'], $font_rgb['green'],$font_rgb['blue']) ;
	$text_x = -$box[0] + $font2image['outline_width'];
	$text_y = abs($box[5]-$box[3])-$box[1] + $font2image['outline_width'];
	if ($font2image['font_spacing']) {
		$bbox = ImageTTFText($image, $font2image['font_size'], 0, $text_x, $text_y, $font2image['font_color'], $font2image['font_file'], $text) ;
	} else {
		for ($c=0; $c<$text_length;$c++) {
			$bbox = ImageTTFText($image,$font2image['font_size'], 0, $text_x, $text_y, $font2image['font_color'], $font2image['font_file'], $text[$c]) ;
			$text_x = $bbox[2] + $font2image['font_kerning'];
		}
	}

	// write it to the cache directory if needed
	if ($font2image['cache_images']) {
		switch($font2image['image']) {
			case "png":
				imagePNG($image, $cache_filename);
				break;
			case "jpg":
				imageJPEG($image, $cache_filename);
				break;
			case "gif":
				imageGIF($image, $cache_filename);
				break;
		}
	}
	if ($font2image['return_link']) {
		// check if the cache path is inside the webroot
		if (substr($cache_filename,0,strlen(PATH_ROOT)) != PATH_ROOT) {
			fatal_error("Cached image is not inside the webroot!");
		} else {
			return substr($cache_filename, strlen(PATH_ROOT)-1);
		}
	} else {
		return $image;
	}
}

/* ****************************************************************************/

/*
	try to determine the "dip" (pixels dropped below baseline) of this
	font for this size.
*/
function get_dip($font,$size)
{
	$test_chars = 'abcdefghijklmnopqrstuvwxyz' .
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ' .
			'1234567890' .
			"!@#$%^&*()'" .
			'"\\/;.,`~<>[]{}-+_-=' ;
	$box = @ImageTTFBBox($size,0,$font,$test_chars) ;
	return $box[3] ;
}

/*
    attempt to create an image containing the error message given.
    if this works, the image is sent to the browser. if not, an error
    is logged, and passed back to the browser as a 500 code instead.
*/
function fatal_error($message)
{
    // send an image
    if(function_exists('ImageCreate'))
    {
        $width = ImageFontWidth(5) * strlen($message) + 10 ;
        $height = ImageFontHeight(5) + 10 ;
        if($image = ImageCreate($width,$height))
        {
            $background = ImageColorAllocate($image,255,255,255) ;
            $text_color = ImageColorAllocate($image,0,0,0) ;
            ImageString($image,5,5,5,$message,$text_color) ;
            header('Content-type: image/png') ;
            ImagePNG($image) ;
            ImageDestroy($image) ;
            exit ;
        }
    }

    // send 500 code
    header("HTTP/1.0 500 Internal Server Error") ;
    print($message) ;
    exit ;
}

/*
    decode an HTML hex-code into an array of R,G, and B values.
    accepts these formats: (case insensitive) #ffffff, ffffff, #fff, fff
*/
function hex_to_rgb($hex)
{
    // remove '#'
    if(substr($hex,0,1) == '#')
        $hex = substr($hex,1) ;

    // expand short form ('fff') color
    if(strlen($hex) == 3)
    {
        $hex = substr($hex,0,1) . substr($hex,0,1) .
               substr($hex,1,1) . substr($hex,1,1) .
               substr($hex,2,1) . substr($hex,2,1) ;
    }

    if(strlen($hex) != 6)
        fatal_error('Error: Invalid color "'.$hex.'"') ;

    // convert
    $rgb['red'] = hexdec(substr($hex,0,2)) ;
    $rgb['green'] = hexdec(substr($hex,2,2)) ;
    $rgb['blue'] = hexdec(substr($hex,4,2)) ;

    return $rgb ;
}

/*
    convert embedded, javascript unicode characters into embedded HTML
    entities. (e.g. '%u2018' => '&#8216;'). returns the converted string.
*/
function javascript_to_html($text)
{
    $matches = null ;
    preg_match_all('/%u([0-9A-F]{4})/i',$text,$matches) ;
    if(!empty($matches)) for($i=0;$i<sizeof($matches[0]);$i++)
        $text = str_replace($matches[0][$i],
                            '&#'.hexdec($matches[1][$i]).';',$text) ;

    return $text ;
}
?>
