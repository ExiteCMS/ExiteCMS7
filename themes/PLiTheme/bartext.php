<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Mail2Forum - Copyright 2006 WanWizard
| http://www.epgcentral.com
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../../includes/core_functions.php";
include_once PATH_INCLUDES."font2image.php";

$font2png = array();
$font2png['font_text'] = isset($_GET['text']) ? $_GET['text'] : "";
$font2png['font_file'] = PATH_THEME."04B_03_.ttf";
$font2png['font_size'] = 6;
$font2png['font_color'] = "#8c8c8c";
$font2png['font_spacing'] = true;
$font2png['background_color'] = "#e7e7e7";
$font2png['background_transparent'] = true;
$font2png['font_kerning'] = 0;
$font2png['outline_width'] = 0;
$font2png['shadow_width'] = 0;
$font2png['cache_images'] = (isset($_GET['cache']) && $_GET['cache'] == "yes") ? true : false;
$font2png['cache_folder'] = PATH_ROOT."files/cache/";
$font2png['cache_prefix'] = $_SERVER['SERVER_NAME'].".".$settings['theme'].".";
$font2png['cache_hash'] = false;

$image = font2image($font2png);
if (!is_resource($image)) die('not a resource!');
header('Content-type: image/png') ;
ImagePNG($image) ;
ImageDestroy($image) ;
exit;

?>