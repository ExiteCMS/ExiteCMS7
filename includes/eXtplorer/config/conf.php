<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );

//------------------------------------------------------------------------------
// Configuration Variables

// Send gzipped content if accepted by the browser?
$GLOBALS['use_gzip'] = 1;

//------------------------------------------------------------------------------
// Global User Variables
	
// show hidden files in eXtplorer: (hide files starting with '.', as in Linux/UNIX)
$GLOBALS["show_hidden"] = false;
	
// filenames not allowed to access: (uses PCRE regex syntax)
// Example: Hide files starting with ".ht" (like .htaccess):  ^\.ht
$GLOBALS["no_access"] = ''; // "^\.ht";
	
// user permissions bitfield: (1=modify, 2=password, 4=admin, add the numbers)
$GLOBALS["permissions"] = 5;

//------------------------------------------------------------------------------
/* NOTE:
	Users can be defined by using the Admin-section,
	or in the file "config/.htusers.php".
	For more information about PCRE Regex Syntax,
	go to http://www.php.net/pcre.pattern.syntax
*/
//------------------------------------------------------------------------------
?>
