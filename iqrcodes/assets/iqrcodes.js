function iqrcodes(url, site) {
	if ( !( typeof url === 'undefined' ) || url ) {
		var qrcimg = url;
	}
	else {
		var shorturl = ( url == null ? $( '#copylink' ).val() : url );
		var base_url = YOURLS_SITE;

		$.ajax({
			type: "POST",
			url: base_url + '/qrchk',
			data:{action:'qrchk', data: shorturl}
		});

		function getCookie(name) {
			var nameEQ = name + "=";
			var ca = document.cookie.split(';');
			for(var i=0;i < ca.length;i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(nameEQ) == 0)
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
				}
			return null;
		}
    		var timestamp = getCookie('usrv_iqrcodes');
		var key = md5(timestamp + 'iqrcodes');
		var fn = 'qrc_' + md5(shorturl) + '.' + iqrcodes_imagetype;
		var qrcimg = base_url + '/srv/?id=iqrcodes&key=' + key + '&fn=' + fn;
	}

	var insertimg = "<div id='qrcode' class='share'><h3>QR Code</h3><img id='myid' src='" + qrcimg + "' /></div>";
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

