<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright � 2002 - 2006 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("ExiteCMS_INIT")) { header("Location:../index.php"); exit; }

function showsublinks($sep="&middot;",$class="") {

die('theme_functions_include,php -> showsublinks(): contains HTML and needs to be rewritten!')

	$i = 0; $res = "";
	$sres = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
	if (dbrows($sres) != 0) {
		while($sdata = dbarray($sres)) {
			if (checkgroup($sdata['link_visibility'])) {
				if ($sdata['link_url']!="---") {
					if ($i != 0) { $res .= " ".$sep."\n"; } else { $res .= "\n"; }
					$link_target = $sdata['link_window'] == "1" ? " target='_blank'" : "";
					$link_class = $class ? " class='$class'" : "";
					if (strstr($sdata['link_url'], "http://") || strstr($sdata['link_url'], "https://")) {
						$res .= "<a href='".$sdata['link_url']."'".$link_target.$link_class.">".$sdata['link_name']."</a>";
					} else {
						$res .= "<a href='".BASEDIR.$sdata['link_url']."'".$link_target.$link_class.">".$sdata['link_name']."</a>";
					}
				}
				$i++;
			}
		}
	}
	if ($i != 0) { return $res; } else { return "&nbsp;"; }
}

function showsubdate() {
	global $settings;
	return ucwords(showdate($settings['subheaderdate'], time()));
}

function newsposter($info,$sep="",$class="") {
	global $locale; $res = "";

die('theme_functions_include,php -> newsposter(): contains HTML and needs to be rewritten!')

	$link_class = $class ? " class='$class' " : "";
	$res = "<img src='".THEME."images/bullet.gif' alt=''> ";
        if (iGUEST) {
	    $res .= $info['user_name']." ";
	} else {
	    $res .= "<a href='profile.php?lookup=".$info['user_id']."'".$link_class.">".$info['user_name']."</a> ";
	}
	$res .= $locale['041'].showdate("longdate", $info['news_date']);
	$res .= $info['news_ext'] == "y" || $info['news_allow_comments'] ? $sep."\n" : "\n";
	return $res;
}

function newsopts($info,$sep,$class="") {
	global $locale; $res = "";

die('theme_functions_include,php -> newsopts(): contains HTML and needs to be rewritten!')

	$link_class = $class ? " class='$class' " : "";
	if (!isset($_GET['readmore']) && $info['news_ext'] == "y") $res = "<a href='news.php?readmore=".$info['news_id']."'".$link_class.">".$locale['042']."</a> ".$sep." ";
	if ($info['news_allow_comments']) $res .= "<a href='news.php?readmore=".$info['news_id']."'".$link_class.">".$info['news_comments'].$locale['043']."</a> ".$sep." ";
	if ($info['news_ext'] == "y" || $info['news_allow_comments']) $res .= $info['news_reads'].$locale['044']."\n";
	$res .= $sep." <a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>\n";
	return $res;
}

function articleposter($info,$sep="",$class="") {
	global $locale; $res = "";

die('theme_functions_include,php -> articleposter(): contains HTML and needs to be rewritten!')

	$link_class = $class ? " class='$class' " : "";
	$res = "<img src='".THEME."images/bullet.gif' alt=''>\n";
        if (iGUEST) {
	    $res .= $locale['040'].$info['user_name']." ";
	} else {
	    $res .= $locale['040']."<a href='profile.php?lookup=".$info['user_id']."'".$link_class.">".$info['user_name']."</a>\n";
	}
	$res .= $locale['041'].showdate("longdate", $info['article_date']);
	$res .= $info['article_allow_comments'] ? $sep."\n" : "\n";
	return $res;
}

function articleopts($info,$sep) {
	global $locale; $res = "";

die('theme_functions_include,php -> articleopts(): contains HTML and needs to be rewritten!')

	if ($info['article_allow_comments']) $res = $info['article_comments'].$locale['043']." ".$sep."\n";
	$res .= $info['article_reads'].$locale['044']." ".$sep."\n";
	$res .= "<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>\n";
	return $res;
}

function openform($item_type,$item_id) {
	global $aidlink;

die('theme_functions_include,php -> openform(): contains HTML and needs to be rewritten!')

	$res = "";
	if ($item_type == "N") {
		if (iADMIN && checkrights($item_type)) $res .= "<form name='editnews".$item_id."' method='post' action='".ADMIN."news.php".$aidlink."&amp;news_id=".$item_id."'>\n";
	} elseif ($item_type == "A") {
		if (iADMIN && checkrights($item_type)) $res .= "<form name='editarticle".$item_id."' method='post' action='".ADMIN."articles.php".$aidlink."&amp;article_id=".$item_id."'>\n";
	}
	return $res;
}

function closeform($item_type,$item_id) {
	global $locale; $res = "";

die('theme_functions_include,php -> closeform(): contains HTML and needs to be rewritten!')

	if ($item_type == "N") {
		if (iADMIN && checkrights($item_type)) $res .= "&middot; <input type='hidden' name='edit' value='edit'><a href='javascript:document.editnews".$item_id.".submit();'><img src='".IMAGES."edit.gif' alt='".$locale['048']."' title='".$locale['048']."' style='vertical-align:middle;border:0px;'></a>\n</form>\n";
	} elseif ($item_type == "A") {
		if (iADMIN && checkrights($item_type)) $res .= " &middot; <input type='hidden' name='edit' value='edit'><a href='javascript:document.editarticle".$item_id.".submit();'><img src='".IMAGES."edit.gif' alt='".$locale['048']."' title='".$locale['048']."' style='vertical-align:middle;border:0px;'></a>\n</form>\n";
	}
	return $res;
}

function showcopyright($class="") {
	global $settings;

die('theme_functions_include,php -> showcopyright(): contains HTML and needs to be rewritten!')

	$link_class = $class ? " class='$class' " : "";
	$res = "Powered by <a href='http://www.php-fusion.co.uk'".$link_class."target='_blank'>PHP-Fusion</a> copyright &copy; 2003-2006 by Nick Jones.<br>\n";
	$res .= "Released as free software under the terms of the GNU/GPL license.";
	return $res;
}

function showcounter() {
	global $locale,$settings;
	return number_format($settings['counter'])." ".($settings['counter'] == 1 ? $locale['140'] : $locale['141']);
}

function panelbutton($state,$bname) {

die('theme_functions_include,php -> panelbutton(): contains HTML and needs to be rewritten!')

	return "<img src='".THEME."images/panel_".($state == "on" ? "off" : "on").".gif' name='b_$bname' alt='' onclick=\"javascript:flipBox('$bname')\">";
}
?>