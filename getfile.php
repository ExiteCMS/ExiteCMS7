<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";

// function to validate user rights without loading the user_functions include
function getfilegroup($group, $userlevel) {
	if ($userlevel == '103') return true;
	if ($userlevel == '103' && ($group == "0" || $group == "101" || $group == "102" || $group == "103")) { return true; }
	elseif ($userlevel == '102' && ($group == "0" || $group == "101" || $group == "102")) { return true; }
	elseif ($userlevel == '101' && ($group == "0" || $group == "101")) { return true; }
	elseif ($userlevel == '0' && ($group == "0" || $group == "100")) { return true; }
	elseif ($userlevel = '101' && in_array($group, explode(".", iUSER_GROUPS))) {
		return true;
	} else {
		return false;
	}
}

// determine the mimetype of the file
function setmime($filename) {
	$fileparts = pathinfo(strtolower($filename));

	// if the file has no extension, assume it's binary
	if (!isset($fileparts['extension'])) {
		return "application/octet-stream";
	}

	// set the mime type based on the file's extension (I know, it's a guess...)	
	switch ($fileparts['extension']) {
		case "dwg":
			return "application/acad";
		case "ez":
			return "application/andrew-inset";
		case "ccad":
			return "application/clariscad";
		case "drw":
			return "application/drafting";
		case "tsp":
			return "application/dsptype";
		case "dxf":
			return "application/dxf";
		case "unv":
			return "application/i-deas";
		case "hqx":
			return "application/mac-binhex40";
		case "cpt":
			return "application/mac-compactpro";
		case "ppz":
		case "ppt":
		case "pps":
		case "pot":
			return "application/mspowerpoint";
		case "doc":
		case "dot":
			return "application/msword";
		case "oda":
			return "application/oda";
		case "pdf":
			return "application/pdf";
		case "ps":
		case "eps":
		case "ai":
			return "application/postscript";
		case "prt":
			return "application/pro_eng";
		case "set":
			return "application/set";
		case "stl":
			return "application/SLA";
		case "smil":
		case "smi":
			return "application/smil";
		case "sol":
			return "application/solids";
		case "stp":
		case "step":
			return "application/STEP";
		case "vda":
			return "application/vda";
		case "mif":
			return "application/vnd.mif";
		case "xlm":
		case "xlw":
		case "xlc":
		case "xls":
		case "xll":
			return "application/vnd.ms-excel";
		case "bcpio":
			return "application/x-bcpio";
		case "vcd":
			return "application/x-cdlink";
		case "pgn":
			return "application/x-chess-pgn";
		case "cpio":
			return "application/x-cpio";
		case "csh":
			return "application/x-csh";
		case "dxr":
		case "dcr":
		case "dir":
			return "application/x-director";
		case "dvi":
			return "application/x-dvi";
		case "pre":
			return "application/x-freelance";
		case "spl":
			return "application/x-futuresplash";
		case "gtar":
			return "application/x-gtar";
		case "gz":
			return "application/x-gzip";
		case "hdf":
			return "application/x-hdf";
		case "ipx":
			return "application/x-ipix";
		case "ips":
			return "application/x-ipscript";
		case "js":
			return "application/x-javascript";
		case "skm":
		case "skp":
		case "skt":
		case "skd":
			return "application/x-koan";
		case "latex":
			return "application/x-latex";
		case "lsp":
			return "application/x-lisp";
		case "scm":
			return "application/x-lotusscreencam";
		case "cdf":
		case "nc":
			return "application/x-netcdf";
		case "sh":
			return "application/x-sh";
		case "shar":
			return "application/x-shar";
		case "swf":
			return "application/x-shockwave-flash";
		case "sit":
			return "application/x-stuffit";
		case "sv4cpio":
			return "application/x-sv4cpio";
		case "sv4crc":
			return "application/x-sv4crc";
		case "tar":
			return "application/x-tar";
		case "tcl":
			return "application/x-tcl";
		case "tex":
			return "application/x-tex";
		case "texi":
		case "texinfo":
			return "application/x-texinfo";
		case "t":
		case "tr":
		case "roff":
			return "application/x-troff";
		case "man":
			return "application/x-troff-man";
		case "me":
			return "application/x-troff-me";
		case "ms":
			return "application/x-troff-ms";
		case "ustar":
			return "application/x-ustar";
		case "src":
			return "application/x-wais-source";
		case "zip":
			return "application/zip";
		case "snd":
		case "au":
			return "audio/basic";
		case "midi":
		case "mid":
		case "kar":
			return "audio/midi";
		case "mp2":
		case "mpga":
		case "mp3":
			return "audio/mpeg";
		case "tsi":
			return "audio/TSP-audio";
		case "aif":
		case "aifc":
		case "aiff":
			return "audio/x-aiff";
		case "rm":
		case "ram":
			return "audio/x-pn-realaudio";
		case "rpm":
			return "audio/x-pn-realaudio-plugin";
		case "ra":
			return "audio/x-realaudio";
		case "wav":
			return "audio/x-wav";
		case "pdb":
		case "xyz":
			return "chemical/x-pdb";
		case "ras":
			return "image/cmu-raster";
		case "gif":
			return "image/gif";
		case "ief":
			return "image/ief";
		case "jpe":
		case "jpeg":
		case "jpg":
			return "image/jpeg";
		case "png":
			return "image/png";
		case "tif":
		case "tiff":
			return "image/tiff";
		case "pnm":
			return "image/x-portable-anymap";
		case "pbm":
			return "image/x-portable-bitmap";
		case "pgm":
			return "image/x-portable-graymap";
		case "ppm":
			return "image/x-portable-pixmap";
		case "rgb":
			return "image/x-rgb";
		case "xbm":
			return "image/x-xbitmap";
		case "xpm":
			return "image/x-xpixmap";
		case "xwd":
			return "image/x-xwindowdump";
		case "iges":
		case "igs":
			return "model/iges";
		case "silo":
		case "mesh":
		case "msh":
			return "model/mesh";
		case "vrml":
		case "wrl":
			return "model/vrml";
		case "css":
			return "text/css";
		case "html":
			return "text/html";
		case "htm":
			return "text/html";
		case "f":
		case "asc":
		case "h":
		case "m":
		case "f90":
		case "txt":
		case "cc":
		case "c":
		case "hh":
		case "php":
		case "log":
			return "text/plain";
		case "rtx":
			return "text/richtext";
		case "rtf":
			return "text/rtf";
		case "sgml":
		case "sgm":
			return "text/sgml";
		case "tsv":
			return "text/tab-separated-values";
		case "etx":
			return "text/x-setext";
		case "xml":
			return "text/xml";
		case "mpeg":
		case "mpg":
		case "mpe":
			return "video/mpeg";
		case "qt":
		case "mov":
			return "video/quicktime";
		case "vivo":
		case "viv":
			return "video/vnd.vivo";
		case "fli":
			return "video/x-fli";
		case "avi":
			return "video/x-msvideo";
		case "movie":
			return "video/x-sgi-movie";
		case "mime":
			return "www/mime";
		case "ice":
			return "x-conference/x-cooltalk";
		default:
			// in all other cases, the default is application/octet-stream
	}
	return "application/octet-stream";
}

/*---------------------------------------------------+
| Main                                               |
+----------------------------------------------------*/

// parameter validation
if (!isset($file_id) || !isNum($file_id)) {
	terminate("<b>Invalid or missing file ID.</b>");
}
if (!isset($type)) {
	terminate("<b>Missing file type.</b>");
}

// check if authentication is valid. If not, reset it
if (isset($_SERVER['PHP_AUTH_USER'])) {
	$result = auth_validate_BasicAuthentication();
	if ($result != 0) unset($_SERVER['PHP_AUTH_USER']);
}

// process the requested file type
switch (strtolower($type)) {
	case "a":	// attachments
	case "fa":	// forum attachments
		// check if the requested attachment exists, if so retrieve the information
		$attachment = dbarray(dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE attach_id='$file_id'"));
		if (!is_array($attachment)) {
			terminate("<b>Invalid file ID.</b>");
		}
		// check if the post this attachment belongs to exists, if so retrieve the information
		$post = dbarray(dbquery("SELECT * FROM ".$db_prefix."posts WHERE thread_id = '".$attachment['thread_id']."' AND post_id='".$attachment['post_id']."'"));
		if (!is_array($post)) {
			terminate("<b>Invalid file ID.</b>");
		}
		$forum = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id = '".$post['forum_id']."'"));
		if (!is_array($forum)) {
			terminate("<b>Invalid file ID.</b>");
		}
		// if logged in, check if the user has access to this file. if not, print an error and give up
		if (iMEMBER && !getfilegroup($forum['forum_access'], $userdata['user_level'])) {
			terminate("<b>You don't have access to the requested file ID.</b>");
		}
		// if not logged in, and authorisation required, check if userid and password is given and valid
		if (!iMEMBER && $forum['forum_access'] != 0) {
			// Not public, authentication is required
			auth_BasicAuthentication();
		}
		// everything ok, update the attachment download counter
		$result = dbquery("UPDATE ".$db_prefix."forum_attachments SET attach_count=attach_count+1 WHERE attach_id='$file_id'");
		// define the required parameters for the download
		$filename = $attachment['attach_name'];
		$filepath = PATH_ATTACHMENTS;
		$downloadname = $attachment['attach_realname'] == "" ? $attachment['attach_name'] : $attachment['attach_realname'];
		break;

	case "pa":	// personal message attachments
		// check if the requested attachment exists, if so retrieve the information
		$attachment = dbarray(dbquery("SELECT * FROM ".$db_prefix."pm_attachments WHERE pmattach_id='$file_id'"));
		if (!is_array($attachment)) {
			terminate("<b>Invalid file ID.</b>");
		}
		// if not logged in, check if userid and password is given and valid (authorisation is required!)
		if (!iMEMBER) {
			// Not public, authentication is required
			auth_BasicAuthentication();
		}
		// check if this attachment belongs to a post addressed to this user
		$result = dbquery("SELECT * FROM ".$db_prefix."pm_index WHERE pm_id = '".$attachment['pm_id']."' AND pmindex_user_id = '".$userdata['user_id']."'");
		if (dbrows($result) == 0) {
			terminate("<b>You don't have access to the requested file ID.</b>");
		}
		// define the required parameters for the download
		$filename = $attachment['pmattach_name'];
		$filepath = PATH_PM_ATTACHMENTS;
		$downloadname = $attachment['pmattach_realname'] == "" ? $attachment['pmattach_name'] : $attachment['pmattach_realname'];
		break;

	default:
		die("<b>Invalid file type.</b>");
}

// get the http download class
require_once PATH_INCLUDES."class.httpdownload.php";

// make sure any pending output is flushed
ob_end_clean();

// make sure zlib compression is off
ini_set('zlib.output_compression', 'Off');

// define the download parameters and start the download
$object = new httpdownload;
$object->set_mime(setmime($filename));
$object->set_byfile($filepath.$filename);
$object->set_filename($downloadname);
$object->use_resume = false;
$object->download();
?>