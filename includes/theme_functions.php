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
if (eregi("theme_functions.php", $_SERVER['PHP_SELF']) || !defined('ExiteCMS_INIT')) die();

// Smarty template engine definitions and initialisation
require_once PATH_INCLUDES."Smarty-2.6.18/Smarty.class.php";

// initialize the class
$template = & new Smarty();

// debugging needed?
$template->debugging = false;

// on-the-fly compilation needed?
$template->compile_check = true;

// set the compile ID for this website
$template->compile_id = $_SERVER['SERVER_NAME'];

// caching required?
$template->caching = 0;

// path definitions
$template->template_dir = PATH_THEME.'templates/source';
$template->compile_dir = PATH_THEME.'templates/templates';
$template->config_dir = PATH_THEME.'templates/configs';
$template->cache_dir = PATH_THEME.'cache';

// plugin's, where to find them?
$plugins_dir = array();
// first check if there's one defined in the current theme
if (is_dir(PATH_THEME."template/plugins")) $plugins_dir[] = PATH_THEME."template/plugins";
// next, check the CMS custom plugins
$plugins_dir[] = 'custom-plugins';
// and finaly, use the default Smarty plugins
$plugins_dir[] = 'smarty-plugins';

$template->plugins_dir = $plugins_dir;

// templates, where to find them?
$template_dir = array();
// first check if there's one defined in the current theme
if (is_dir(PATH_THEME."templates/source")) $template_dir[] = PATH_THEME."templates/source";
// next, check the CMS template directory
$template_dir[] = PATH_INCLUDES.'templates';

$template->template_dir = $template_dir;

// PHP in Templates? Don't think so!
$template->php_handling = SMARTY_PHP_REMOVE;

// Template security settings: allow PHP functions
$template->security = false;

// Register the panel template resource
$template->register_resource('panel', array('resource_panel_source', 'resource_panel_timestamp', 'resource_panel_secure', 'resource_panel_trusted'));

// Register the string template resource
$template->register_resource('string', array('resource_string_source', 'resource_string_timestamp', 'resource_string_secure', 'resource_string_trusted'));

// Array to store panels
$template_panels = array();

// Array to store panel variables
$template_variables = array();

/*-----------------------------------------------------+
| load_templates - process templates                   |
+-----------------------------------------------------*/
function load_templates($_type='', $_name='') {
	global $settings, $userdata, $db_prefix, $aidlink,
			$template, $template_panels, $template_variables, 
			$_loadstats, $_headparms, $_bodyparms;

	// reset all assigned template variables
	$template->clear_all_assign();

	// Initialise the $locale array
	$locale = array();
	
	// Load the global language file
	include PATH_LOCALE.LOCALESET."global.php";

	// assign CMS website settings to the template
	$template->assign("settings", $settings);

	// assign the current users record to the template
	$template->assign("userdata", $userdata);

	// find the requested template and variable definitions
	foreach($template_panels as $panel_name => $panel) {
		// are we interested in this panel?
		if (($_type == "" || $panel['type'] == $_type) && ($_name == "" || $panel['name'] == $_name)) {
			// panel preprocessing, if defined
			$no_panel_displayed = false;
			$template->assign('_style', '');
			if (isset($panel['panel_type'])) {
				switch($panel['panel_type']) {
					case "file":
						if (file_exists($panel['panel_code']))
							include $panel['panel_code'];
						else
							$no_panel_displayed = true;
						break;
					case "dynamic":
						$variables = array();
						eval(stripslashes($panel['panel_code']));
						// define the dynamic panel
						$panel['name'] = 'dynamic_panel.'.$panel['id'];
						$panel['template'] = 'panel:'.$panel['id'];
						$template_variables[$panel['name']] = $variables;
						break;
					default:
						break;
				}
			}
			if ($no_panel_displayed) continue;
			
			// assign the panel variables
			$panel_name = isset($panel['name']) ? $panel['name'] : "";
			if (isset($template_variables[$panel_name]) && is_array($template_variables[$panel_name])) {
				foreach($template_variables[$panel_name] as $varname => $var) {
					$template->assign($varname, $var);
				}
			}
			// need the panel definition to be available too...
			$template->assign('_name', isset($panel['name'])?$panel['name']:"");
			$template->assign('_title', isset($panel['title'])?$panel['title']:"");
			$template->assign('_state', isset($panel['state'])?$panel['state']:"");

			// if one or more locales are assigned to this panel, load them first
			if (isset($panel['locale']) && (is_array($panel['locale']) || $panel['locale'] != "")) {
				if (is_array($panel['locale'])) {
					foreach($panel['locale'] as $panel_locale) {
						include $panel_locale;
					}
				} else {
					include $panel['locale'];
				}
			}
			// then assign the locales to the template
			$template->assign("locale", $locale);
			
			// assign CMS admin security aidlink
			$template->assign("aidlink", $aidlink);

			// if defined, add header parameters
			if ($_type == 'header') {
				if (isset($_headparms)) $template->assign("headparms", $_headparms);
				if (isset($_bodyparms)) $template->assign("bodyparms", $_bodyparms);
			}

			// update the loadtime counter
			if ($_type == 'footer') {
				$_loadtime = explode(" ", microtime());
				$_loadstats['time'] += $_loadtime[1] + $_loadtime[0];
				// and assign it for use in the template
				$template->assign("_loadstats", $_loadstats);
			}

			//if this is a module template...
			$tpl_parts = explode(".", $panel['template']);
			if ($tpl_parts[0] == "modules") {
				// store the current template directories, we need to restore them later
				$td = $template->template_dir;
				$template->template_dir = array_merge(array(PATH_MODULES.$tpl_parts[1].'/templates'), $template->template_dir);
			} else {
				// we shouldn't get here
			}
		
			// if a template is defined, load the template, 
			if (isset($panel['template'])) $template->display($panel['template']);
			
			// restore the template direcory if needed
			if (isset($td) && is_array($td)) $template->template_dir = $td;
		}
	}
}

/*-----------------------------------------------------+
| load_panels - load the template array with panels    |
+-----------------------------------------------------*/
function load_panels($column) {
	global $db_prefix, $locale, $settings, $userdata, $template_panels;
	
	// parameter validation and processing
	$column = strtolower(trim($column));
	switch ($column) {
		case "left":
			// get the left-side panels
			$where = "panel_side='1'";
			break;
		case "right":
			// get the right-side panels
			$where = "panel_side='4'";
			break;			
		case "upper":
			// get the upper-center panels
			$where = "panel_side='2'";
			if (FUSION_URL != "/".$settings['opening_page']) {
				$where .= " AND panel_display='1'";
			}
			break;
		case "lower":
			// get the lower-center panels
			$where = "panel_side='3'";
			if (FUSION_URL != "/".$settings['opening_page']) {
				$where .= " AND panel_display='1'";
			}
			break;
		default:
			// invalid parameter. Generate a notice
			trigger_error("theme_functions: getpanels(): invalid 'column' parameter passed", E_USER_NOTICE);
			return false;
	}	

	$p_res = dbquery("SELECT * FROM ".$db_prefix."panels WHERE ".$where." AND panel_status='1' ORDER BY panel_order");
	if (dbrows($p_res) != 0) {
		// loop through the panels found
		while ($p_data = dbarray($p_res)) {
			// we only need panels the user has access to
			if (checkgroup($p_data['panel_access'])) {
				// initialize the panel array
				$_panel = array();
				$_panel['id'] = $p_data['panel_id'];
				$_panel['type'] = $column;
				$_panel['title'] = $p_data['panel_name'];
				$_panel['name'] = 'modules.'.$p_data['panel_filename'];
				$_panel['panel_type'] = $p_data['panel_type'];
				switch($p_data['panel_type']) {
					case "file":
						$_panel['template'] = 'modules.'.$p_data['panel_filename'].".tpl";
						$_panel['panel_code'] = PATH_MODULES.$p_data['panel_filename']."/".$p_data['panel_filename'].".php";
						break;
					case "dynamic":
						$_panel['panel_code'] = $p_data['panel_code'];
						break;
				}
				$_panel['state'] = $p_data['panel_state'];
				// check if there's a cookie, if so, restore the previous panel state
				if (isset($_COOKIE['box_modules_'.$p_data['panel_filename']])) $_panel['state'] = $_COOKIE['box_modules_'.$p_data['panel_filename']];
				// if this panel is not defined as hidden, add it to the template array
				if ($_panel['state'] < 2) {
					$template_panels[] = $_panel;
				}
			}
		}
	}
	return true;
}

/*-----------------------------------------------------+
| count_panels - count the panels of a given type      |
+-----------------------------------------------------*/
function count_panels($column) {
	global $template_panels;

	if (!is_array($template_panels)) return false;

	$count = 0;
	
	// parameter validation and processing
	$column = strtolower(trim($column));
	switch ($column) {
		case "left":
		case "right":
		case "upper":
		case "lower":
		case "header":
		case "footer":
			foreach($template_panels as $panel_name => $panel) {
				if($panel['type'] == $column) $count++;
			}
			break;
		case "body":
			foreach($template_panels as $panel_name => $panel) {
				if(!in_array($panel['type'], array('left', 'right', 'upper', 'lower', 'header', 'footer'))) $count++;
			}
			break;
		case "all":
			$count = count($template_panels);
		default:
			return false;
	}
	return $count;
}

/*-----------------------------------------------------+
| theme initialisation function,called by theme.php    |
+-----------------------------------------------------*/
function theme_init() {

	// make sure this constant exists
	if (!defined('LOAD_TINYMCE')) define('LOAD_TINYMCE', false);
}

/*-----------------------------------------------------+
| theme cleanup function, to be called by theme.php    |
+-----------------------------------------------------*/
function theme_cleanup() {

	global $db_prefix, $userdata, $_db_logs, $template;

	// clean-up tasks, will be executed by all admins and super-admins
	// WANWIZARD - 20070716 - THIS NEEDS TO BE MOVED TO A CRON JOB !!!
	if ($userdata['user_level'] >= 102) {
		$minute = 60; $hour = $minute * 60; $day = $hour * 24;
		// flood control: set to 5 minutes
		$result = dbquery("DELETE FROM ".$db_prefix."flood_control WHERE flood_timestamp < '".(time() - $minute * 5)."'");
		// thread notifies: set to 14 days
		$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE notify_datestamp < '".(time() - $day * 14)."'");
		// vcode images: set to 6 minutes
		$result = dbquery("DELETE FROM ".$db_prefix."vcode WHERE vcode_datestamp < '".(time() - $minute * 6)."'");
		// new registered users: set to 3 days
		$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_datestamp < '".(time() - $day * 3)."'");
		// unread posts indicators: set to 30 days
		$result = dbquery("DELETE FROM ".$db_prefix."posts_unread WHERE post_time < ".(time() - $day * 30), false);
	}
	
	// close the database connection
	mysql_close();
	
	// and flush any output remaining
	ob_end_flush();
	
	// check if we have had query debugging active
	if (is_array($_db_logs) && count($_db_logs)) {
		$template->assign('queries', $_db_logs);
		$template->display('_query_debug.tpl');
	}
}

/*-----------------------------------------------------+
| _dvb - debug function to dump the $variables array   |
+-----------------------------------------------------*/
function _dvb() {
	global $variables;
	
	echo "<pre>";print_r($variables);die();
}

/*-----------------------------------------------------+
| resource_panel - Smarty panel resource callbacks     |
+-----------------------------------------------------*/
function resource_panel_source($tpl_name, &$tpl_source, &$smarty) {

	global $db_prefix;

	// get the panel record
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id = '$tpl_name'");

	if ($data = dbarray($result)) {
		// if the record exists, return it in the $tpl_source variable
        $tpl_source = stripslashes($data['panel_template']);
        return true;
	} else {
		// panel record not found
		return false;
	}
}

function resource_panel_timestamp($tpl_name, &$tpl_timestamp, &$smarty) {

	global $db_prefix;

	// get the panel record
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id = '$tpl_name'");

	if ($data = dbarray($result)) {
		// if the record exists, return the timestamp in the $tpl_timestamp variable
		$tpl_timestamp = $data['panel_datestamp'];
		return true;
	} else {
		// panel record not found
		return false;
	}
}

function resource_panel_secure($tpl_name, &$smarty) {

    // assume all templates are secure
	return true;
}

function resource_panel_trusted($tpl_name, &$smarty) {

    // not used for templates
}

/*-----------------------------------------------------+
| resource_string - Smarty string resource callbacks   |
+-----------------------------------------------------*/
function resource_string_source($tpl_name, &$tpl_source, &$smarty) {

	$tpl_source = $tpl_name;
	return true;
}

function resource_string_timestamp($tpl_name, &$tpl_timestamp, &$smarty) {

	$tpl_timestamp = time();
	return true;
}

function resource_string_secure($tpl_name, &$smarty) {

    // assume all templates are secure
	return true;
}

function resource_string_trusted($tpl_name, &$smarty) {

    // not used for templates
}
?>