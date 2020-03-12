function iqrcodes(url, site) {
	if ( !( typeof url === 'undefined' ) || url ) {
		var qrcimg = url;
	}
	else {
		var shorturl = ( url == null ? $( '#copylink' ).val() : url );
		var base_url = window.location.origin;

		$.ajax({
			type: "POST",
			url: base_url + '/qrchk',
			data:{action:'qrchk', data: shorturl}
		});
	}

	var insertimg = "<div id='qrcode' class='share'><h3>QR Code</h3><img id='myid' src='" + shorturl + ".qr" + "' /></div>";
	if ( $( '#qrcode' ).length > 0 )
		$( '#qrcode' ).remove( );
		$( "#shareboxes" ).append( insertimg );        // Append new elements
		$( "div#qrcode img" ).css( "width", "100px" );
		$( "div#qrcode img" ).css( "height", "100px" );
}
$(document).ready( function( ){
	// Share button behavior
	$( '.button_share' ).click( function( ){
		iqrcodes( );
	});			
	// Tab behavior on stats page
	$('a[href="#stat_tab_share"]').click( function( ){
		iqrcodes( );
	});		  
	iqrcodes();
});

