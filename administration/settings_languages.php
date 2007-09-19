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
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
include PATH_LOCALE.LOCALESET."admin/settings.php";

// temp storage for template variables
$variables = array();

// store this modules name for the menu bar
$variables['this_module'] = FUSION_SELF;

// check for the proper admin access rights
if (!checkrights("S8") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

if (isset($_POST['savesettings'])) {
	$localeset = stripinput($_POST['localeset']);
	$old_localeset = stripinput($_POST['old_localeset']);
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		locale='$localeset'
	");
	if ($localeset != $old_localeset) {
		include PATH_LOCALE.$localeset."/admin/main.php";
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['201']."' WHERE admin_link='administrators.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['202']."' WHERE admin_link='article_cats.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['203']."' WHERE admin_link='articles.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['204']."' WHERE admin_link='blacklist.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['206']."' WHERE admin_link='custom_pages.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['207']."' WHERE admin_link='db_backup.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['208']."' WHERE admin_link='download_cats.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['209']."' WHERE admin_link='downloads.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['210']."' WHERE admin_link='faq.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['211']."' WHERE admin_link='forums.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['212']."' WHERE admin_link='images.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['213']."' WHERE admin_link='modules.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['215']."' WHERE admin_link='members.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['216']."' WHERE admin_link='news.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['235']."' WHERE admin_link='news_cats.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['217']."' WHERE admin_link='panels.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['218']."' WHERE admin_link='photoalbums.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['219']."' WHERE admin_link='phpinfo.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['220']."' WHERE admin_link='polls.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['221']."' WHERE admin_link='shoutbox.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['222']."' WHERE admin_link='site_links.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['223']."' WHERE admin_link='submissions.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['224']."' WHERE admin_link='upgrade.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['225']."' WHERE admin_link='user_groups.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['226']."' WHERE admin_link='weblink_cats.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['227']."' WHERE admin_link='weblinks.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['228']."' WHERE admin_link='settings_main.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['229']."' WHERE admin_link='settings_time.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['230']."' WHERE admin_link='settings_forum.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['231']."' WHERE admin_link='settings_registration.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['232']."' WHERE admin_link='settings_photo.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['233']."' WHERE admin_link='settings_misc.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['234']."' WHERE admin_link='settings_messages.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['236']."' WHERE admin_link='settings_languages.php'");
	}
	redirect(FUSION_SELF.$aidlink);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));
$variables['settings2'] = $settings2;

$variables['locale_files'] = makefilelist(PATH_LOCALE, ".|..", true, "folders");

// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.settings_languages', 'template' => 'admin.settings_languages.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/settings.php");
$template_variables['admin.settings_languages'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>