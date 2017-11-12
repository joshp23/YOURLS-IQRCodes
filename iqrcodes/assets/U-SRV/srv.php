<?php
/*	
 *	=======================================================================
 *
 *	U-SRV v 1.1.10
 *		
 *	This is a universal file server (for YOURLS)
 *	by Josh Panter <joshu at unfettered dot net>
 *		
 *	This script will fetch and return any file for YOURLS plugins (in our
 *	outside of the server document root) and display them inline. 
 *	Its primary intent is to obfuscate filesystem paths and allow easy 
 *	access to files between plugins, enhancing convenience and security, 
 *	opening the door for greater integration. 
 *
 *	The links used with this script will only live for at most one minute,
 *	so unwanted hotlinking to files is handled without any additional work.
 *
 *	Given the following parameters:
 *
 *		1. id => plugin ID
 *		2. fn  => filename
 *		3. key => Access key
 *	
 *	This script will:
 *
 *		1. Retrieve the file store location per plugin, can be from a 
 *		   database.
 *		2. Retrieve and return a file to the browser
 *			a. with a clean url (?) with htaccess assistence.
 *
 *	To use, just drop this script in '/path/to/YOURLS/pages/' and call it 
 *	from there. Read the rest of this file for detailed configuration.
 *
 *	This script could easily be modified and used outside of the YOURLS 
 *	environment.
 *
 *      =======================================================================
 *
 * 	U-SRV is distributed under LGPL 3
 *
 * 	This library is free software; you can redistribute it and/or
 * 	modify it under the terms of the GNU Lesser General Public
 * 	License as published by the Free Software Foundation; either
 * 	version 3 of the License, or any later version.
 *
 * 	This library is distributed in the hope that it will be useful,
 * 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 * 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * 	Lesser General Public License for more details.
 *
 * 	You should have received a copy of the GNU Lesser General Public
 * 	License along with this library; if not, write to the Free Software
 * 	Foundation, Inc., 51 Franklin St, 5th Floor, Boston, MA 02110-1301 USA
 *
 *      =======================================================================
*/
// get plugin id or die
if( isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	die('FAIL: missing id');
}

// get file name or die
if( isset($_GET['fn'])) {
	$fn = $_GET['fn'];
} else {
	die('FAIL: missing filename');
}

// get access key or die
if( isset($_GET['key'])) {
	$key = $_GET['key'];
} else {
	die('FAIL: missing passkey');
}

/*
 *
 * 	Access Key: The url's created with this script only live
 *	for 1 minute. This helps prevent unwanted hotlinking, etc.
 *
 *	To set up your own access key just add something like the
 *	following to your plugin or script:
 *
 *		$now = date("YmdGi");
 *		$id = 'My_Fancy_Plugin';
 *		$key = md5($now . $id);
 *
 *	and make sure to send 'id' => $id and 'key' => $key
 *	from the above example.
 *
*/
// check access key
$now = round(time()/60);
$lock = md5($now . $id);
// set a cookie to help with javascript calls
$cname = "usrv_" . $id;
setcookie($cname,$now, 0, "/");
if($lock !== $key) die('FAIL: bad access key');

/*
 *
 * 	Plugin ID list: To add your plugin, just add a new case
 *	with your plugin ID and include your file store location. 
 *	Your ID can be whatever you wish, just send it in a GET
 *	request with the KEY and file name.
 *
 *	Ex. In this example you store the cache location in the DB
 *	as a regular option:
 *	
 *		case 'YOUR_PLUGIN_ID':
 *			$path = yourls_get_option('YOUR_CACHE_PATH');
 *			break;
 *
 *	Ex. In this example you just store the filepath here:
 *	
 * 		case 'YOUR_PLUGIN_ID':
 *			$path = '/path/to/your/files/');
 *			break;
 *
*/
// get plugin file cache location
switch ($id) {

	case 'snapshot':
		$path = yourls_get_option('snapshot_cache_path');
		if($path == null) $path = 'user/cache/preview';
		$path = YOURLS_ABSPATH . '/' . $path;
		break;
		
	case 'snapshot-alt':
		$path = 'user/plugins/snapshot/assets';
		$path = YOURLS_ABSPATH . '/' . $path;
		break;
		
	case 'iqrcodes':
		$path = yourls_get_option('iqrcodes_cache_loc');
		if($path == null) $path = 'user/cache/qr';
		$path = YOURLS_ABSPATH . '/' . $path;
		break;
		
	default:
		die('not a valid id');
}

// work with the file
$file = $path . '/' . $fn;						// include filename

if (is_file($file)) {							// and make sure the file exists at this location
	$type = pathinfo($fn, PATHINFO_EXTENSION);				// then get the file extention type
} else {
	die('file not found');							// or die
}

switch( $type ) {							// so that we can match header info
/*
 *
 * 	Filetype list: Either uncomment individual filetyes or
 *	entire sections in order to use them with this script.
 *
 *	New filetypes can be added by using the following format:
 *
 *		case "": 	$ctype=""; break;
 *
*/
/*	// Applications
	case "evy": 	$ctype="application/envoy"; 						break;
	case "fif": 	$ctype="application/fractals"; 						break;
	case "spl": 	$ctype="application/futuresplash"; 					break;
	case "hta": 	$ctype="application/hta"; 						break;
	case "acx": 	$ctype="application/internet-property-stream"; 				break;
	case "hqx": 	$ctype="application/mac-binhex40";	 				break;
	case "doc": 	$ctype="application/msword"; 						break;
	case "dot": 	$ctype="application/msword"; 						break;
	case "bin": 	$ctype="application/octet-stream";	 				break;
	case "class": 	$ctype="application/octet-stream";	 				break;
	case "dms": 	$ctype="application/octet-stream";	 				break;
	case "exe": 	$ctype="application/octet-stream";	 				break;
	case "iha": 	$ctype="application/octet-stream";	 				break;
	case "lzh": 	$ctype="application/octet-stream";	 				break;
	case "oda": 	$ctype="application/oda"; 						break;
	case "ogx": 	$ctype="application/ogg"; 						break;
	case "axs": 	$ctype="application/olescript"; 					break;
	case "pdf": 	$ctype="application/pdf"; 						break;
	case "prf": 	$ctype="application/pics-rules"; 					break;
	case "p10": 	$ctype="application/pkcs10"; 						break;
	case "crl": 	$ctype="application/pkix-crl"; 						break;
	case "ai": 	$ctype="application/postscript"; 					break;
	case "eps": 	$ctype="application/postscript"; 					break;
	case "ps": 	$ctype="application/postscript"; 					break;
	case "rtf": 	$ctype="application/rtf"; 						break;
	case "setpay": 	$ctype="application/set-payment-initiation"; 				break;
	case "setreg": 	$ctype="application/set-registration-initiation"; 			break;
	case "xla": 	$ctype="application/vnd.ms-excel"; 					break;
	case "xlc": 	$ctype="application/vnd.ms-excel"; 					break;
	case "xlm": 	$ctype="application/vnd.ms-excel"; 					break;
	case "xls": 	$ctype="application/vnd.ms-excel"; 					break;
	case "xlt": 	$ctype="application/vnd.ms-excel"; 					break;
	case "xlw": 	$ctype="application/vnd.ms-excel"; 					break;
	case "odc": 	$ctype="application/vnd.oasis.opendocument.chart"; 			break;
	case "otc": 	$ctype="application/vnd.oasis.opendocument.chart-template"; 		break;
	case "odb": 	$ctype="application/vnd.oasis.opendocument.database"; 			break;
	case "odf": 	$ctype="application/vnd.oasis.opendocument.formula"; 			break;
	case "odft": 	$ctype="application/vnd.oasis.opendocument.formula-template"; 		break;
	case "odg": 	$ctype="application/vnd.oasis.opendocument.graphics"; 			break;
	case "otg": 	$ctype="application/vnd.oasis.opendocument.graphics-template"; 		break;
	case "odi": 	$ctype="application/vnd.oasis.opendocument.image"; 			break;
	case "oti": 	$ctype="application/vnd.oasis.opendocument.image-template"; 		break;
	case "odp": 	$ctype="application/vnd.oasis.opendocument.presentation"; 		break;
	case "otp": 	$ctype="application/vnd.oasis.opendocument.presentation-template"; 	break;
	case "ods": 	$ctype="application/vnd.oasis.opendocument.spreadsheet"; 		break;
	case "ots": 	$ctype="application/vnd.oasis.opendocument.spreadsheet-template"; 	break;
	case "odt": 	$ctype="application/vnd.oasis.opendocument.text"; 			break;
	case "odm": 	$ctype="application/vnd.oasis.opendocument.text-master"; 		break;
	case "ott": 	$ctype="application/vnd.oasis.opendocument.text-template"; 		break;
	case "msg": 	$ctype="application/vnd.ms-outlook";					break;
	case "sst": 	$ctype="application/vnd.ms-pkicertstore"; 				break;
	case "cat": 	$ctype="application/vnd.ms-pkiseccat"; 					break;
	case "stl": 	$ctype="application/vnd.ms-pkistl"; 					break;
	case "pot": 	$ctype="application/vnd.ms-powerpoint"; 				break;
	case "pps": 	$ctype="application/vnd.ms-powerpoint"; 				break;
	case "ppt": 	$ctype="application/vnd.ms-powerpoint"; 				break;
	case "mpp": 	$ctype="application/vnd.ms-project"; 					break;
	case "wcm": 	$ctype="application/vnd.ms-works"; 					break;
	case "wdp": 	$ctype="application/vnd.ms-works"; 					break;
	case "wks": 	$ctype="application/vnd.ms-works"; 					break;
	case "wps": 	$ctype="application/vnd.ms-works"; 					break;
	case "sxc": 	$ctype="application/vnd.sun.xml.calc"; 					break;
	case "stc": 	$ctype="application/vnd.sun.xml.calc.template";				break;
	case "sxd": 	$ctype="application/vnd.sun.xml.draw"; 					break;
	case "std": 	$ctype="application/vnd.sun.xml.draw.template"; 			break;
	case "sxi": 	$ctype="application/vnd.sun.xml.impress"; 				break;
	case "sti": 	$ctype="application/vnd.sun.xml.impress.template"; 			break;
	case "sxm": 	$ctype="application/vnd.sun.xml.math"; 					break;
	case "sxw": 	$ctype="application/vnd.sun.xml.writer"; 				break;
	case "sxg": 	$ctype="application/vnd.sun.xml.writer.global"; 			break;
	case "stw": 	$ctype="application/vnd.sun.xml.writer.templat"; 			break;
	case "hlp": 	$ctype="application/winhlp"; 						break;
	case "bcpio": 	$ctype="application/x-bcpio"; 						break;
	case "cdf": 	$ctype="application/x-cdf"; 						break;
	case "z": 	$ctype="application/x-compress"; 					break;
	case "tgz": 	$ctype="application/x-compressed"; 					break;
	case "cpio": 	$ctype="application/x-cpio"; 						break;
	case "csh": 	$ctype="application/x-csh"; 						break;
	case "dcr": 	$ctype="application/x-director"; 					break;
	case "dir": 	$ctype="application/x-director"; 					break;
	case "dxr": 	$ctype="application/x-director"; 					break;
	case "dvi": 	$ctype="application/x-dvi"; 						break;
	case "gtar": 	$ctype="application/x-gtar"; 						break;
	case "gz": 	$ctype="application/x-gzip"; 						break;
	case "hdf": 	$ctype="application/x-hdf"; 						break;
	case "ins": 	$ctype="application/x-internet-signup"; 				break;
	case "isp": 	$ctype="application/x-internet-signup"; 				break;
	case "iii": 	$ctype="application/x-iphone"; 						break;
	case "js": 	$ctype="application/x-javascript"; 					break;
	case "latex": 	$ctype="application/x-latex";						break;
	case "mdb": 	$ctype="application/x-msaccess"; 					break;
	case "crd": 	$ctype="application/x-mscardfile"; 					break;
	case "clp": 	$ctype="application/x-msclip"; 						break;
	case "dll": 	$ctype="application/x-msdownload"; 					break;
	case "m13": 	$ctype="application/x-msmediaview"; 					break;
	case "m14": 	$ctype="application/x-msmediaview"; 					break;
	case "mvb": 	$ctype="application/x-msmediaview"; 					break;
	case "wmf": 	$ctype="application/x-msmetafile"; 					break;
	case "mny": 	$ctype="application/x-msmoney"; 					break;
	case "pub": 	$ctype="application/x-mspublisher"; 					break;
	case "scd": 	$ctype="application/x-msschedule"; 					break;
	case "trm": 	$ctype="application/x-msterminal"; 					break;
	case "wri": 	$ctype="application/x-mswrite"; 					break;
	case "cdf": 	$ctype="application/x-netcdf"; 						break;
	case "nc": 	$ctype="application/x-netcdf"; 						break;
	case "pma": 	$ctype="application/x-perfmon"; 					break;
	case "pmc": 	$ctype="application/x-perfmon"; 					break;
	case "pml": 	$ctype="application/x-perfmon"; 					break;
	case "pmr": 	$ctype="application/x-perfmon"; 					break;
	case "pmw": 	$ctype="application/x-perfmon"; 					break;
	case "p12": 	$ctype="application/x-pkcs12"; 						break;
	case "pfx": 	$ctype="application/x-pkcs12"; 						break;
	case "p7b": 	$ctype="application/x-pkcs7-certificates"; 				break;
	case "spc": 	$ctype="application/x-pkcs7-certificates"; 				break;
	case "p7r": 	$ctype="application/x-pkcs7-certreqresp";		 		break;
	case "p7c": 	$ctype="application/x-pkcs7-mime"; 					break;
	case "p7m": 	$ctype="application/x-pkcs7-mime"; 					break;
	case "p7s": 	$ctype="application/x-pkcs7-signature"; 				break;
	case "sh": 	$ctype="application/x-sh"; 						break;
	case "shar": 	$ctype="application/x-shar"; 						break;
	case "swf": 	$ctype="application/x-shockwave-flash"; 				break;
	case "sit": 	$ctype="application/x-stuffit"; 					break;
	case "sv4cpio": $ctype="application/x-sv4cpio"; 					break;
	case "sv4crc": 	$ctype="application/x-sv4crc"; 						break;
	case "tar": 	$ctype="application/x-tar"; 						break;
	case "tcl": 	$ctype="application/x-tcl"; 						break;
	case "tex": 	$ctype="application/x-tex"; 						break;
	case "texi": 	$ctype="application/x-texinfo"; 					break;
	case "texinfo": $ctype="application/x-texinfo"; 					break;
	case "roff": 	$ctype="application/x-troff"; 						break;
	case "ti": 	$ctype="application/x-troff"; 						break;
	case "tr": 	$ctype="application/x-troff"; 						break;
	case "man": 	$ctype="application/x-troff-man"; 					break;
	case "me": 	$ctype="application/x-troff-me"; 					break;
	case "ms": 	$ctype="application/x-troff-ms"; 					break;
	case "ustar": 	$ctype="application/x-ustar"; 						break;
	case "src": 	$ctype="application/x-wais-source"; 					break;
	case "cer": 	$ctype="application/x-x509-ca-cert"; 					break;
	case "crt": 	$ctype="application/x-x509-ca-cert"; 					break;
	case "der": 	$ctype="application/x-x509-ca-cert"; 					break;
	case "pko": 	$ctype="application/ynd.ms-pkipko"; 					break;
	case "zip": 	$ctype="application/zip"; 						break;
*//*	// Sound Files
	case "au": 	$ctype="audio/basic";	 						break;
	case "snd": 	$ctype="audio/basic"; 							break;
	case "mid": 	$ctype="audio/mid"; 							break;
	case "rmi": 	$ctype="audio/mid"; 							break;
	case "mp3": 	$ctype="audio/mpeg"; 							break;
	case "aga": 	$ctype="audio/ogg"; 							break;
	case "ogg": 	$ctype="audio/ogg"; 							break;
	case "aif": 	$ctype="audio/x-aiff"; 							break;
	case "aifc": 	$ctype="audio/x-aiff"; 							break;
	case "aiff": 	$ctype="audio/x-aiff"; 							break;
	case "m3u": 	$ctype="audio/x-mpegurl"; 						break;
	case "ra": 	$ctype="audio/x-pn-realaudio"; 						break;
	case "ram": 	$ctype="audio/x-pn-realaudio"; 						break;
	case "wav": 	$ctype="audio/x-wav"; 							break;
	case "weba": 	$ctype="audio/webm"; 							break;
*//*	// Image types
	case "bmp": 	$ctype="image/bmp"; 							break;
	case "cod": 	$ctype="image/cis-cod"; 						break;
	case "gif": 	$ctype="image/gif"; 							break;
	case "ief": 	$ctype="image/ief"; 							break;
	case "jpeg":	$ctype="image/jpeg"; 							break;
*/	case "jpg": 	$ctype="image/jpeg"; 							break;
/*	case "jpe": 	$ctype="image/jpeg"; 							break;
	case "jfif": 	$ctype="image/pipeg"; 							break;
*/	case "png": 	$ctype="image/png"; 							break;
	case "svg": 	$ctype="image/svg+xml"; 						break;
/*	case "tif": 	$ctype="image/tiff"; 							break;
	case "tiff": 	$ctype="image/tiff"; 							break;
	case "ras": 	$ctype="image/x-cmu-raster"; 						break;
	case "cmx": 	$ctype="image/x-cmx"; 							break;
	case "ico": 	$ctype="image/x-icon"; 							break;
	case "pnm": 	$ctype="image/x-portable-anymap"; 					break;
	case "pdb": 	$ctype="image/x-portable-bitmap"; 					break;
	case "pgm": 	$ctype="image/x-portable-graymap"; 					break;
	case "ppm": 	$ctype="image/x-portable-pixmap	"; 					break;
	case "rgb": 	$ctype="image/x-rgb"; 							break;
	case "xbm": 	$ctype="image/x-xbitmap"; 						break;
	case "xpm": 	$ctype="image/x-xpixmap"; 						break;
	case "xwd": 	$ctype="image/x-xwindowdump"; 						break;
*//*	// Mail Message Files
	case "mht": 	$ctype="message/rfc822"; 						break;
	case "mhtml": 	$ctype="message/rfc822"; 						break;
	case "nws": 	$ctype="message/rfc822"; 						break;
	// Text Files
*//*	case "css": 	$ctype="text/css"; 							break;
	case "323": 	$ctype="text/h323"; 							break;
	case "htm": 	$ctype="text/html"; 							break;
	case "html": 	$ctype="text/html"; 							break;
	case "stm": 	$ctype="text/html"; 							break;
	case "uls": 	$ctype="text/iuls"; 							break;
	case "bas": 	$ctype="text/plain"; 							break;
	case "c": 	$ctype="text/plain"; 							break;
	case "h": 	$ctype="text/plain"; 							break;
	case "txt": 	$ctype="text/plain"; 							break;
	case "rtx": 	$ctype="text/richtext"; 						break;
	case "sct": 	$ctype="text/scriptlet"; 						break;
	case "tsv": 	$ctype="text/tab-separated-values"; 					break;
	case "htt": 	$ctype="text/webviewhtml";						break;
	case "htc": 	$ctype="text/x-component";						break;
	case "etx": 	$ctype="text/x-setext";							break;
	case "vcf": 	$ctype="text/x-vcrd";							break;
*//*	// Video Files
	case "mp2": 	$ctype="video/mpeg"; 							break;
	case "mpa": 	$ctype="video/mpeg"; 							break;
	case "mpe": 	$ctype="video/mpeg"; 							break;
	case "mpeg": 	$ctype="video/mpeg"; 							break;
	case "mpg": 	$ctype="video/mpeg"; 							break;
	case "mpv2": 	$ctype="video/mpeg"; 							break;
	case "ogv": 	$ctype="video/ogg";							break;
	case "mov": 	$ctype="video/quicktime"; 						break;
	case "qt": 	$ctype="video/quicktime"; 						break;
	case "webm": 	$ctype="video/webm";							break;
	case "lsf": 	$ctype="video/x-la-asf"; 						break;
	case "lsx": 	$ctype="video/x-la-asf"; 						break;
	case "asf": 	$ctype="video/x-ms-asf"; 						break;
	case "asr": 	$ctype="video/x-ms-asf"; 						break;
	case "asx": 	$ctype="video/x-ms-asf"; 						break;
	case "avi": 	$ctype="video/x-msvideo"; 						break;
	case "movie": 	$ctype="video/x-sgi-movie"; 						break;
*//*	// Virtual World Files
	case "flr": 	$ctype="x-world/x-vrml"; 						break;
	case "vrml": 	$ctype="x-world/x-vrml"; 						break;
	case "wrl": 	$ctype="x-world/x-vrml"; 						break;
	case "wrz": 	$ctype="x-world/x-vrml"; 						break;
	case "xaf": 	$ctype="x-world/x-vrml"; 						break;
	case "xof": 	$ctype="x-world/x-vrml"; 						break;
*/	// The defualt case: nothing
	default: break;
}

if($ctype == null) die('file type not supported, please check your configuration');

header('Content-type: ' . $ctype);					// send the correct headers
header('Expires: 0');							// .
header('Content-Length: ' . filesize($file));				// .
readfile($file);							// with the file data
exit;
?>
