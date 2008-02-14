<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Some portions copyright 2002 - 2006 Nick Jones     |
| http://www.php-fusion.co.uk/                       |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// load the locale for this module
locale_load("admin.modules");

// temp storage for template variables
$variables = array();

//check if the user has a right to be here. If not, bail out
if (!checkrights("I") || !defined("iAUTH") || $aid != iAUTH) fallback(BASEDIR."index.php");

// variable initialisation
$mod_title = ""; $mod_description = ""; $mod_version = ""; $mod_developer = ""; $mod_email = ""; $mod_weburl = ""; 
$mod_folder = ""; $mod_admin_image = ""; $mod_admin_panel = ""; $mod_link_name = ""; $mod_link_url = ""; $mod_link_visibility = "";
$mod_newtables = 0; $mod_insertdbrows = 0; $mod_altertables = 0; $mod_deldbrows = 0;

// check if a filter has been defined
if (!isset($filter) || !isNum($filter)) $filter = isset($_POST['filter']) && isNum($_POST['filter']) ? $_POST['filter'] : 0;
$variables['filter'] = $filter;

// make sure this is initialised
if (!isset($action)) $action = "";

// install a new module
if ($action == 'install' && isset($module)) {

	// sanitise the information
	$module = stripinput($_GET['module']);

	// check if it exists, if so, bail out
	$result = dbquery("SELECT * FROM ".$db_prefix."modules WHERE mod_folder='$module'");
	if (dbrows($result) || !is_dir(PATH_MODULES.$module)) fallback(FUSION_SELF.$aidlink);

	// load the module installer
	include PATH_MODULES.$module."/module_installer.php";

	// if defined, install the module's admin panel
	if ($mod_admin_panel != "") {

		// check if module rights are defined, If not, use the default
		if (!isset($mod_admin_rights) || $mod_admin_rights == "" or strlen($mod_admin_rights) !=2) $mod_admin_rights = "IP";

		// check if the admin page for this module is defined, If not, use the modules and plugins page
		if (!isset($mod_admin_page) || !isNum($mod_admin_page) || $mod_admin_page < 1 || $mod_admin_page > 4) $mod_admin_page = 4;

		// check if an icon for the admin panel is defined, If not, use the modules and plugins default icon
		if ($mod_admin_image == "" || (!file_exists(PATH_MODULES.$mod_folder."/images/".$mod_admin_image) && !file_exists(PATH_ADMIN."images/".$mod_admin_image))) $mod_admin_image = "modules_panel.gif";

		// add the admin panel of this module
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('".$mod_admin_rights."', '$mod_admin_image', '$mod_title', '".MODULES."$mod_folder/$mod_admin_panel', '".$mod_admin_page."')");
		
		// make sure superadmins have rights to all defined admin modules
		$result = dbquery("SELECT admin_rights FROM ".$db_prefix."admin");
		$adminrights = "";
		while ($data = dbarray($result)) {
			$adminrights .= ($adminrights == "" ? "" : ".") . $data['admin_rights'];
		}
		$result = dbquery("UPDATE ".$db_prefix."users SET user_rights = '".$adminrights."' WHERE user_level = 103");
	}

	// if defined, install the menu links for this module
	if (isset($mod_site_links) && is_array($mod_site_links) && count($mod_site_links)) {
		
		// loop through the defined links
		foreach ($mod_site_links as $mod_link) {

			// create the correct URL for this panel
			$link_url = str_replace("../","",MODULES).$mod_folder."/".$mod_link['url'];
			if ($link_url{0} == "/") $link_url = substr($link_url,1);

			// determine to which menu panel this link needs to be added
			if (isset($mod_link['panel']) && $mod_link['panel'] != "") {
				if (substr($mod_link['panel'],-4) != ".php") {
					$link_panel = $mod_link['panel']."/".$mod_link['panel'].".php";
				}
				// check if this panel is installed
				$result = dbquery("SELECT panel_id FROM ".$db_prefix."panels WHERE panel_filename = '$link_panel'");
				if (dbrows($result) == 0) {
					// if the panel doesn't exist, try to find another menu panel (if multiple are installed, pick the first one in the ordered list)
					$result = dbquery("SELECT panel_filename FROM ".$db_prefix."panels WHERE panel_filename LIKE '%_menu_panel.php' ORDER BY panel_order LIMIT 1");
					if (dbrows($result)) {
						$data = dbarray($result);
						$link_panel = substr($data['panel_filename'],0,strpos($data['panel_filename'], "/"));
					} else {
						// if still not found, fall back to the CMS default
						$link_panel = "main_menu_panel";
					}
				} else {
					$link_panel = $mod_link['panel'];
				}
			} else {
				// use the CMS default
				$link_panel = "main_menu_panel";
			}

			// determine the next order in the menu for this link
			$link_order = dbresult(dbquery("SELECT MAX(link_order) FROM ".$db_prefix."site_links WHERE panel_name = '$link_panel'"),0) + 1;

			// add the new link
			switch ($settings['sitelinks_localisation']) {
				case "multiple":
					$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_active = '1'");
					while ($data = dbarray($result)) {
						$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_locale, link_url, link_visibility, link_position, link_window, link_order, panel_name) 
							VALUES ('".$mod_link['name']."', '".$data['locale_code']."', '$link_url', '".$mod_link['visibility']."', '1', '0', '$link_order', '$link_panel')");
					}
					break;
				default:
					$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) 
						VALUES ('".$mod_link['name']."', '$link_url', '".$mod_link['visibility']."', '1', '0', '$link_order', '$link_panel')");
			}
		}
	}

	// if locale strings are defined, load them into the database
	if (isset($localestrings) && is_array($localestrings)) {
		foreach($localestrings as $locales_code => $locales_strings) {
			load_localestrings($locales_strings, $locales_code, "modules.".$mod_folder);
		}
	}

	// process the command defined
	$mod_errors = array();
	if (isset($mod_install_cmds) && is_array($mod_install_cmds) && count($mod_install_cmds)) {
		foreach ($mod_install_cmds as $cmd) {
			// skip empty entries
			if (!is_array($cmd) || count($cmd) == 0) continue;
			switch($cmd['type']) {
				case "db":
					// put the correct prefix in place
					$dbcmd = str_replace('##PREFIX##', $db_prefix, $cmd['value']);
					// execute the command
					$result = dbquery($dbcmd, false);
					if (!$result) {
						// record the error
						$mod_errors[] = $locale['413'].$dbcmd."<br /><font color='red'>".mysql_error()."</font>";
					}
					break;
				case "function":
					$function = $cmd['value'];
					if (function_exists($function)) {
						$result = $function();
						if ($result) $mod_errors[] = $result;
					} else {
						$mod_errors[] = $locale['417'].$cmd['value']."()' is not defined.";
					}
					break;
				default:
					$mod_errors[] = $locale['419']."'".$cmd['type']."'";
			}
		}
	}
	$variables['mod_errors'] = $mod_errors;
	$variables['is_error'] = count($mod_errors);
	
	// register the installation of this module
	$result = dbquery("INSERT INTO ".$db_prefix."modules (mod_title, mod_folder, mod_version) VALUES ('$mod_title', '$mod_folder', '$mod_version')");

	// update the access rights of the site administrators to include this new module
	$result = dbquery("SELECT admin_rights FROM ".$db_prefix."admin");
	$adminrights = "";
	while ($data = dbarray($result)) {
		$adminrights .= ($adminrights == "" ? "" : ".") . $data['admin_rights'];
	}
	$result = dbquery("UPDATE ".$db_prefix."users SET user_rights = '".$adminrights."' WHERE user_level = 103");
}

if ($action == 'uninstall' && isset($id)) {
	
	// make sure the ID passed is numeric
	if (!isNum($id)) fallback(FUSION_SELF.$aidlink);
	
	// get the module data
	$result = dbquery("SELECT * FROM ".$db_prefix."modules WHERE mod_id='$id'");
	$data = dbarray($result);

	// load the module installer to start deinstallation
	include PATH_MODULES.$data['mod_folder']."/module_installer.php";

	// set some defaults if not defined in the installer
	if (!isset($mod_admin_rights) or $mod_admin_rights == "" or strlen($mod_admin_rights) !=2) $mod_admin_rights = "IP";
	if (!isset($mod_admin_page) or $mod_admin_page == "" or !isNum($mod_admin_page)) $mod_admin_page = 4;
	
	// if an admin panel is defined, remove it
	if ($mod_admin_panel != "") {
		$result = dbquery("DELETE FROM ".$db_prefix."admin WHERE admin_rights='".$mod_admin_rights."' AND admin_link='".MODULES."$mod_folder/$mod_admin_panel' AND admin_page='".$mod_admin_page."'");
		// get a list of all members with admin rights to this module, and remove it
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_rights LIKE '%.".$mod_admin_rights."%'");
		while ($data = dbarray($result)) {
			$mods = explode(".", $data['user_rights']);
			$newmods = "";
			foreach($mods as $mod) {
				$newmods .= ($mod == "" || $mod == $mod_admin_rights) ? "" : (($newmods == "" ? "" : ".") . $mod);
			}
			$result2 = dbquery("UPDATE ".$db_prefix."users SET user_rights = '".$newmods."' WHERE user_id = '".$data['user_id']."'");
		}
	}

	// if defined, remove the menu links for this module
	if (isset($mod_site_links) && is_array($mod_site_links) && count($mod_site_links)) {
		
		// loop through the defined links
		foreach ($mod_site_links as $mod_link) {

			// create the correct URL for this panel
			$link_url = str_replace("../","",MODULES).$mod_folder."/".$mod_link['url'];
			if ($link_url{0} == "/") $link_url = substr($link_url,1);

			// determine to which menu panel this link needs to be added
			if (isset($mod_link['panel']) && $mod_link['panel'] != "") {
				if (substr($mod_link['panel'],-4) != ".php") {
					$link_panel = $mod_link['panel']."/".$mod_link['panel'].".php";
				}
				// check if this panel is installed
				$result = dbquery("SELECT panel_id FROM ".$db_prefix."panels WHERE panel_filename = '$link_panel'");
				if (dbrows($result) == 0) {
					// if the panel doesn't exist, try to find another menu panel (if multiple are installed, pick the first one in the ordered list)
					$result = dbquery("SELECT panel_filename FROM ".$db_prefix."panels WHERE panel_filename LIKE '%_menu_panel.php' ORDER BY panel_order LIMIT 1");
					if (dbrows($result)) {
						$data = dbarray($result);
						$link_panel = substr($data['panel_filename'],0,strpos($data['panel_filename'], "/"));
					} else {
						// if still not found, fall back to the CMS default
						$link_panel = "main_menu_panel";
					}
				} else {
					$link_panel = $mod_link['panel'];
				}
			} else {
				// use the CMS default
				$link_panel = "main_menu_panel";
			}

			// check if we have a menu entry for this link
			$result2 = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_url='$link_url'");

			// and if so, remove it and adjust the link order
			if (dbrows($result2)) {
				$data2 = dbarray($result2);
				$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order-1 WHERE panel_name = '$link_panel' AND link_order>'".$data2['link_order']."'");
				$result = dbquery("DELETE FROM ".$db_prefix."site_links WHERE link_id='".$data2['link_id']."'");
			}

		}
	}
	// remove the panel from the available panels
	$result = dbquery("DELETE FROM ".$db_prefix."panels WHERE panel_filename='".$mod_folder."'");

	// if locale strings are defined, remove them
	if (isset($localestrings) && is_array($localestrings)) {
		$result = dbquery("DELETE FROM ".$db_prefix."locales WHERE locales_name = 'modules.".$mod_folder."'");
	}

	// process the command defined
	$mod_errors = array();
	if (isset($mod_uninstall_cmds) && is_array($mod_uninstall_cmds) && count($mod_uninstall_cmds)) {
		foreach ($mod_uninstall_cmds as $cmd) {
			// skip empty entries
			if (!is_array($cmd) || count($cmd) == 0) continue;
			switch($cmd['type']) {
				case "db":
					// put the correct prefix in place
					$dbcmd = str_replace('##PREFIX##', $db_prefix, $cmd['value']);
					// execute the command
					$result = dbquery($dbcmd, false);
					if (!$result) {
						// record the error
						$mod_errors[] = $locale['413'].$dbcmd."<br /><font color='red'>".mysql_error()."</font>";
					}
					break;
				case "function":
					$function = $cmd['value'];
					if (function_exists($function)) {
						$result = $function();
						if ($result) $mod_errors[] = $result;
					} else {
						$mod_errors[] = $locale['417'].$cmd['value']."()' is not defined.";
					}
					break;
				default:
					$mod_errors[] = $locale['419']."'".$cmd['type']."'";
			}
		}
	}
	$variables['mod_errors'] = $mod_errors;
	$variables['is_error'] = count($mod_errors);

	// remove the module from the installed modules list
	$result = dbquery("DELETE FROM ".$db_prefix."modules WHERE mod_id='$id'");
}

if ($action == 'upgrade' && isset($id)) {

	// make sure the ID passed is numeric
	if (!isNum($id)) fallback(FUSION_SELF.$aidlink);
	
	// check if it exists
	$result = dbquery("SELECT * FROM ".$db_prefix."modules WHERE mod_id='$id'");
	if (!$result)  fallback(FUSION_SELF.$aidlink);

	// get the module data
	$data = dbarray($result);

	// load the module installer to start the upgrade
	include PATH_MODULES.$data['mod_folder']."/module_installer.php";

	// if defined, install the module's admin panel (if it hasn't been installed before)
	if ($mod_admin_panel != "") {

		// check if module rights are defined, If not, use the default
		if (!isset($mod_admin_rights) || $mod_admin_rights == "" or strlen($mod_admin_rights) !=2) $mod_admin_rights = "IP";

		// check if the admin page for this module is defined, If not, use the modules and plugins page
		if (!isset($mod_admin_page) || !isNum($mod_admin_page) || $mod_admin_page < 1 || $mod_admin_page > 4) $mod_admin_page = 4;

		// check if an icon for the admin panel is defined, If not, use the modules and plugins default icon
		if ($mod_admin_image == "" || (!file_exists(PATH_MODULES.$mod_folder."/images/".$mod_admin_image) && !file_exists(PATH_ADMIN."images/".$mod_admin_image))) $mod_admin_image = "modules_panel.gif";

		// check if the admin panel is already defined
		$result = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_link = '".MODULES."$mod_folder/$mod_admin_panel'");
		// add the admin panel of this module if needed
		if (dbrows($result) == 0) {
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('".$mod_admin_rights."', '$mod_admin_image', '$mod_title', '".MODULES."$mod_folder/$mod_admin_panel', '".$mod_admin_page."')");
		} else {
			// already defined, update the admin record
			$result = dbquery("UPDATE ".$db_prefix."admin SET admin_rights = '".$mod_admin_rights."', admin_image = '$mod_admin_image', admin_title = '$mod_title', admin_page = '".$mod_admin_page."' WHERE admin_link = '".MODULES."$mod_folder/$mod_admin_panel'");
		}
		// make sure superadmins have rights to all defined admin modules
		$result2 = dbquery("SELECT admin_rights FROM ".$db_prefix."admin");
		$adminrights = "";
		while ($data2 = dbarray($result2)) {
			$adminrights .= ($adminrights == "" ? "" : ".") . $data2['admin_rights'];
		}
		$result2 = dbquery("UPDATE ".$db_prefix."users SET user_rights = '".$adminrights."' WHERE user_level = 103");
	}

	// if defined, install the menu links for this module
	if (isset($mod_site_links) && is_array($mod_site_links) && count($mod_site_links)) {
		
		// loop through the defined links
		foreach ($mod_site_links as $mod_link) {

			// create the correct URL for this panel
			$link_url = str_replace("../","",MODULES).$mod_folder."/".$mod_link['url'];
			if ($link_url{0} == "/") $link_url = substr($link_url,1);

			// determine to which menu panel this link needs to be added
			if (isset($mod_link['panel']) && $mod_link['panel'] != "") {
				if (substr($mod_link['panel'],-4) != ".php") {
					$link_panel = $mod_link['panel']."/".$mod_link['panel'].".php";
				}
				// check if this panel is installed
				$result = dbquery("SELECT panel_id FROM ".$db_prefix."panels WHERE panel_filename = '$link_panel'");
				if (dbrows($result) == 0) {
					// if the panel doesn't exist, try to find another menu panel (if multiple are installed, pick the first one in the ordered list)
					$result = dbquery("SELECT panel_filename FROM ".$db_prefix."panels WHERE panel_filename LIKE '%_menu_panel.php' ORDER BY panel_order LIMIT 1");
					if (dbrows($result)) {
						$data = dbarray($result);
						$link_panel = substr($data['panel_filename'],0,strpos($data['panel_filename'], "/"));
					} else {
						// if still not found, fall back to the CMS default
						$link_panel = "main_menu_panel";
					}
				} else {
					$link_panel = $mod_link['panel'];
				}
			} else {
				// use the CMS default
				$link_panel = "main_menu_panel";
			}

			// if the panel doesn't exist, use the default menu panel
			$result = dbquery("SELECT panel_id FROM ".$db_prefix."panels WHERE panel_filename = '$link_panel'");
			if (dbrows($result) == 0) $link_panel = "main_menu_panel/main_menu_panel.php";

			// check if the menu link is already defined
			$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_url = '".$link_url."'");
			if (dbrows($result) == 0) {
				// determine the next order in the menu for this link
				$link_order = dbresult(dbquery("SELECT MAX(link_order) FROM ".$db_prefix."site_links WHERE panel_name = '$link_panel'"),0) + 1;

				// add the new link
				switch ($settings['sitelinks_localisation']) {
					case "multiple":
						$result = dbquery("SELECT * FROM ".$db_prefix."locale WHERE locale_active = '1'");
						while ($data = dbarray($result)) {
							$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_locale, link_url, link_visibility, link_position, link_window, link_order, panel_name) 
								VALUES ('".$mod_link['name']."', '".$data['locale_code']."', '$link_url', '".$mod_link['visibility']."', '1', '0', '$link_order', '$link_panel')");
						}
						break;
					default:
						$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, panel_name) 
							VALUES ('".$mod_link['name']."', '$link_url', '".$mod_link['visibility']."', '1', '0', '$link_order', '$link_panel')");
				}

			} else {
				// do nothing
			}
		}
	}

	// if locale strings are defined, load them into the database
	if (isset($localestrings) && is_array($localestrings)) {
		foreach($localestrings as $locales_code => $locales_strings) {
			load_localestrings($locales_strings, $locales_code, "modules.".$mod_folder);
		}
	}

	$variables['mod_errors'] = array();
	$variables['is_error'] = 0;

	// if an upgrade function exists, call it
	if (function_exists('module_upgrade')) {
		$variables['mod_errors'] = module_upgrade($data['mod_version']);
	}
	
	// upgrade the modules version number if the upgrade was successful
	if ($variables['is_error'] == 0) $result2 = dbquery("UPDATE ".$db_prefix."modules SET mod_version='$mod_version' WHERE mod_id='".$data['mod_id']."'");
}

// get the directory listing of the modules folder
$temp = opendir(PATH_MODULES);
$modules = array();
$moduleindex = array();
// scan through the list..
while ($folder = readdir($temp)) {
	// skipping current and parent folder entries...
	if (!in_array($folder, array("..",".")) && $folder{0} != ".") {
		// if it's a directory, and a module_installer can be found in the directory...
		if (is_dir(PATH_MODULES.$folder) && file_exists(PATH_MODULES.$folder."/module_installer.php")) {
			// array to store the information about this module
			$this_module = array();
			$mod_errors = "";
			// load the module ...
			include PATH_MODULES.$folder."/module_installer.php";
			// verify the required identification of this module
			$mod_check = (isset($mod_title) && $mod_title != "");
			$mod_check = $mod_check && (isset($mod_version) && $mod_version!= "");
			$mod_check = $mod_check && (isset($mod_developer) && $mod_developer!= "");
			$mod_check = $mod_check && (isset($mod_email) && $mod_email!= "");
			if (!$mod_check) {
				$mod_errors .= $locale['mod004'];
			} else {
				$this_module['title'] = $mod_title;
				$this_module['description'] = isset($mod_description) ? $mod_description : "";
				$this_module['version'] = $mod_version;
				$this_module['developer'] = $mod_developer;
				$this_module['email'] = $mod_email;
				$this_module['url'] = isset($mod_weburl) ? $mod_weburl : "";
				$this_module['type'] = isset($mod_type) ? $mod_type : "M";
			}
			// store any errors detected while loading
			$this_module['errors'] = $mod_errors;
			// store the directory
			$this_module['folder'] = $folder;
			// determine the status of this module
			if (isset($mod_errors) && $mod_errors != "") {
				if ($filter && $filter != 1) continue;
				$this_module['status'] = 1;
				$this_module['status_text'] = $locale['418'];
			} else {
				$result = dbquery("SELECT * FROM ".$db_prefix."modules WHERE mod_folder='$mod_folder'");
				if (dbrows($result)) {
					$data = dbarray($result);
					$this_module['id'] = $data['mod_id'];
					$this_module['version'] = $data['mod_version'];
					if ($mod_version > $data['mod_version']) {
						if ($filter && $filter != 2) continue;
						$this_module['status'] = 2;
						$this_module['status_text'] = $locale['416'];
					} else {
						if ($filter && $filter != 3) continue;
						$this_module['status'] = 3;
						$this_module['status_text'] = $locale['415'];
					}
				} else {
					if ($filter && $filter != 4) continue;
					$this_module['status'] = 4;
					$this_module['status_text'] = $locale['414'];
				}
			}
			$modules[] = $this_module;
			$moduleindex[] = $this_module['type'].'-'.$this_module['status'].'-'.$this_module['title'];
			$mod_title = ""; $mod_description = ""; $mod_version = ""; $mod_developer = ""; $mod_email = ""; $mod_weburl = ""; 
			$mod_folder = ""; $mod_admin_image = ""; $mod_admin_panel = ""; $mod_link_name = ""; $mod_link_url = ""; $mod_link_visibility = "";
			$mod_newtables = 0; $mod_insertdbrows = 0; $mod_altertables = 0; $mod_deldbrows = 0;
		}
	}
}
closedir($temp);

// sort the module index
asort($moduleindex);

// create the modules variables array
$variables['modules'] = array();
foreach($moduleindex as $index => $module) {
	$variables['modules'][] = $modules[$index];
}

// make sure the error switch has a value
if (!isset($variables['is_error'])) {
	$variables['is_error'] = 0;
}
 
// define the admin body panel
$template_panels[] = array('type' => 'body', 'name' => 'admin.modules', 'template' => 'admin.modules.tpl', 'locale' => "admin.modules");
$template_variables['admin.modules'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>