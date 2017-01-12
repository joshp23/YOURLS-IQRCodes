function iqrcodes(url, site) {
	if ( !( typeof url === 'undefined' ) || url ) {
		var qrcimg = url;
	} else {
		url = ( url == null ? $( '#copylink' ).val() : url );
		var key = time() + "iqrcodes";
		var fn = 'qrc_' + md5(url) + '.' + iqrcodes_imagetype;
		var qrcimg = '/srv/?id=iqrcodes&key=' + md5(key) + '&fn=' + fn;
	}

	var insertimg = "<div id='qrcode' class='share'><img id='myid' src='" + qrcimg + "' /></div>"
	
	if ( $( '#qrcode' ).length > 0 )
		$( '#qrcode' ).remove( );
	
		$( "#shareboxes" ).append( insertimg );        // Append new elements
		$( "div#qrcode img" ).css( "width", "100px" );
		$( "div#qrcode img" ).css( "height", "100px" );
}

function time() {
	var timeInSec = Math.floor(new Date().getTime() / 1000);
	var timestamp = Math.round(timeInSec / 60);
	return timestamp;
}

$(document).ready( function( ){
	// Share button behavior
	$( '.button_share' ).click( function( ){
		iqrcodes( );
	});
				
	// Tab behavior on stats page
	$('a[href=#stat_tab_share]').click( function( ){
		iqrcodes( );
	});
		  
	//iqrcodes();
});

