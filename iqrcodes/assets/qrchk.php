<?php
/*
	IQRCodes plugin for Yourls - URL Shortener ~ QRCode file check

	This file is called when iqrcodes.js is loaded in order to verify
	if a particular QRCode exists in the cache or not. If a short url 
	was added to the database before IQRCodes was installed then there
	will be no cached QR code, and it will need to be created.

	This function checks the file system and then calls to generate
	the QRCode if it is missing.

	Copy, or make a link to this file in the pages directory like so:

		YOURLS_DIR/pages/qrchk.php

	in order for this function to operate properly.
*/
// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

if( $_POST['action'] == 'qrchk' ) {

	$data = $_POST['data'];
	$shorturl = urldecode( $data );

	iqrcodes_mkdir( $opt[0] );

        $opt  = iqrcodes_get_opts();
	$base = YOURLS_SITE;
	$key = iqrcodes_key();
	
	$filename = '/qrc_' . md5($shorturl) . "." . $opt[5];
	$filepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $opt[0]. '/' . $filename;

	$imgname  = $base . '/srv/?id=iqrcodes&key=' . $key . '&fn=' . $filename;

	if ( !file_exists( $filepath ) && $shorturl == !null )
		QRcode::{$opt[5]}( $shorturl, $filepath, $opt[1], $opt[2], $opt[3] );
} else {
	echo <<<HTML
		<html>
			<head>
				<meta http-equiv="refresh" content="0;url=../">
			</head>
			<body>
				YOURLS has nothing for you to see here.
			</body>
		</html>
HTML;
}
?>
