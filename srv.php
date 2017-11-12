<?php
/*	
 *	U-SRV v 1.1.11
 *		
 *	This is a universal file server (for YOURLS)
 *	by Josh Panter <joshu at unfettered dot net>
 *
*/

// Verify that all parameters have been set

// get access key or die
if( isset($_GET['key'])) {
	$key = $_GET['key'];
} else {
	die('FAIL: missing passkey');
}
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

// Do security check

// create access lock
$now = round(time()/60);
$lock = md5($now . $id);
// set a cookie to help with javascript calls
$cname = "usrv_" . $id;
setcookie($cname,$now, 0, "/");
// check access key
if($lock !== $key) die('FAIL: bad access key');

/*
 *
 * 	ID 
 *
 *	Check sender and file store location
 *
 *	To add your plugin or script, add a new case below
 *	with an arbitrary ID and file store location. 
 *	Just send the same ID in the GET request.
 *
 *	Ex. In this example you store the cache location in the DB
 *	as a regular option:
 *	
 *		case 'ID_VALUE':
 *			$path = yourls_get_option('YOUR_CACHE_PATH');
 *			break;
 *
 *	Ex. In this example you just store the filepath here:
 *	
 * 		case 'ID_VALUE':
 *			$path = '/path/to/your/files/');
 *			break;
*/

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
$file = $path . '/' . $fn;

if (is_file($file)) {							// if the file exists at this location
	$type = pathinfo($fn, PATHINFO_EXTENSION);	// then get the file extention type
} else {
	die('file not found');						// or die
}
/*
 *  Mime Types
 *
 *  Header information must beset explicitly.
 *
 *	Add new file types by using the following format:
 *
 *		case "": 	$ctype=""; break;
 *
*/
switch( $type ) {
	case "jpg": $ctype="image/jpeg"; 	break;
	case "png": $ctype="image/png"; 	break;
	case "svg": $ctype="image/svg+xml"; break;
	// The defualt case: nothing
	default: break;
}

if($ctype == null) die('file type not supported, please check your configuration');

header('Content-type: ' . $ctype);					// send the correct headers
header('Expires: 0');								// .
header('Content-Length: ' . filesize($file));		// .
readfile($file);									// with the file data
exit;
?>
