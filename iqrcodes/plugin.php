<?php
/*
Plugin Name: IQRCodes
Plugin URI: https://github.com/joshp23/YOURLS-IQRCodes
Description: Integrated QR Codes
Version: 1.1.0
Author: Josh Panter
Author URI: https://unfettered.net
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// get qrcode library
require_once( dirname(__FILE__).'/assets/phpqrcode.php' );

// Add the admin page
yourls_add_action( 'plugins_loaded', 'iqrcodes_add_page' );
function iqrcodes_add_page() {
        yourls_register_plugin_page( 'iqrcodes', 'IQRCodes', 'iqrcodes_do_page' );
}
function iqrcodes_do_page() {
	// Check if a form was submitted
	iqrcodes_form_0();
	
	// Get the options and set defaults if needed
	$opt = iqrcodes_get_opts();

	// Make sure cache exists
	iqrcodes_mkdir( $opt[0] );
	
	// Create nonce
	$nonce = yourls_create_nonce( 'iqrcodes' );

	// some values necessary for display
	
	$D_chk = $P_chk = null;
	switch ($opt[4]) {
		case 'preserve': $P_chk = 'checked'; break;
		case 'delete':   $D_chk = 'checked'; break;
		default:  	 $P_chk = 'checked'; break;
	}
	
	$H_chk = $Q_chk = $M_chk = $L_chk = null;
	switch ($opt[1]) {
		case 'H': $H_chk = 'checked'; break;
		case 'Q': $Q_chk = 'checked'; break;
		case 'M': $M_chk = 'checked'; break;
		case 'L': $L_chk = 'checked'; break;
		default:  $H_chk = 'checked'; break;
	}
	
	$imgtypeSelected = array("svg" => "", "png" => "", "jpg" => "");
	switch ($opt[5]) {
		case 'svg':	$imgTypeSelected['svg'] = 'selected="selected"'; break;
		case 'jpg':	$imgTypeSelected['jpg'] = 'selected="selected"'; break;
		default:	$imgTypeSelected['png'] = 'selected="selected"'; $opt[5] = 'png'; break;
	}

	$logoPositionSelected = array("center" => "", "topleft" => "", "topright" => "");
	switch ($opt[7]) {
		case 'topleft':		$logoPositionSelected['topleft'] = 'selected="selected"'; break;
		case 'topright':	$logoPositionSelected['topright'] = 'selected="selected"'; break;
		default:		$logoPositionSelected['center'] = 'selected="selected"'; $opt[7] = 'center'; break;
	}

	$base = YOURLS_SITE;
	$key  = iqrcodes_key();
	$fn = 'qrc_' . md5($base . '/V') . "." . $opt[5];

	echo <<<HTML
		<div id="wrap">
			<div id="tabs">
				<div class="wrap_unfloat">
					<ul id="headers" class="toggle_display stat_tab">
						<li class="selected"><a href="#stat_tab_options"><h2>IQRCodes Config</h2></a></li>
						<li><a href="#stat_tab_examples"><h2>Display Examples</h2></a></li>
					</ul>
				</div>
				<div id="stat_tab_options" class="tab">
					<form method="post" enctype="multipart/form-data">
						<h3>Cache Options</h3>
						<h4>Image Cache Location</h4>
						<div style="padding-left: 10pt;">
							<p><input type="text" size=25 name="iqrcodes_cache_loc" value="$opt[0]" /></p>
							<p>Please set the cache location, do not include a preceeding or trailing slash.</p>
						</div>

						<h4>Cache Afterlife</h4>
						<div style="padding-left: 10pt;">
							<input type="hidden" name="iqrcodes_afterlife" value="preserve">
		  					<input type="radio" name="iqrcodes_afterlife" value="preserve" $P_chk> Preserve<br>
		  					<input type="radio" name="iqrcodes_afterlife" value="delete" $D_chk> Delete<br>
		  					<p>Decide what happens to the cache when the plugin is deactivated</p>
	  					</div>

						<hr/>

						<h3>QR Code Image Options</h3>
						<h4>Image Type</h4>
						<div style="padding-left: 10pt;">
							<select name="iqrcodes_imagetype" size="1">
								<option value="jpg" {$imgTypeSelected['jpg']}>JPG</option>
								<option value="svg" {$imgTypeSelected['svg']}>SVG</option>
								<option value="png" {$imgTypeSelected['png']}>PNG</option>
							</select>
						</div>

						<h4>Error Correction Level</h4>
						<div style="padding-left: 10pt;">
							<input type="hidden" name="iqrcodes_EC" value="H">
	   						<input type="radio" name="iqrcodes_EC" value="H" $H_chk> H: up to 30% damage<br>
	    						<input type="radio" name="iqrcodes_EC" value="Q" $Q_chk> Q: up to 25% damage<br>
	  						<input type="radio" name="iqrcodes_EC" value="M" $M_chk> M: up to 15% damage<br>
	  						<input type="radio" name="iqrcodes_EC" value="L" $L_chk> L: up to 07% damage<br>

	  						<p>How much damage can the codes take before they start losing data integrity?</p> 
	  						<p>Note: The more damage that they can take, the larger the file size.</p>
						</div>

						<h4>Image Size</h4>

						<div style="padding-left: 10pt;">
							<input type="hidden" name="iqrcodes_img_size" value="5">
		  					<input type="number" name="iqrcodes_img_size" min="1" max="10" value=$opt[2]><br>

							<p>Set the pixel size for qr code image here.</p>
							<p>Note:<p>

							<div style="padding-left: 10pt;">
								<p>A value of <code>5</code> will result in an image display of <code>165 x 165</code></p>
								<p>A value of <code>10</code> will result in an image display of <code>330 x 330</code></p>
							</div>
						</div>

						<h4>Image Silent Zone, aka Frame Size</h4>
						<div style="padding-left: 10pt;">
							<input type="hidden" name="iqrcodes_border_size" value="2">
	  						<input type="number" name="iqrcodes_border_size" min="2" max="10" value=$opt[3]>

							<p>Determine the size of the blank zone surrounding the codes.</p>
							<p>Default is 2, if you run into problems try increasing this number to at least 4. Otherwise, propbably leave this alone.</p>
						</div>
						<hr/>
						<h3>Logo</h3>
						<p>Set scaling to 0 to ignore/deactivate a previous loaded logo.</p>
						<h4>Upload</h4>
						<div style="padding-left: 10pt;">
							<input type="file" name="iqrcodes_logo_file" />
						</div>

						<h4>Scaling</h4>
						<div style="padding-left: 10pt;">
							<input type="text" name="iqrcodes_logo_scale" value="$opt[6]" />
						</div>

						<h4>Position</h4>
						<div style="padding-left: 10pt;">
							<select name="iqrcodes_logo_position" size="1">
								<option value="center" {$logoPositionSelected['center']}>Center</option>
								<option value="topleft" {$logoPositionSelected['topleft']}>Top left</option>
								<option value="topright" {$logoPositionSelected['topright']}>Top right</option>
							</select>
						</div>

						<input type="hidden" name="nonce" value="$nonce" />
						<p><input type="submit" value="Submit" /></p>
					</form>
				</div>

				<div id="stat_tab_examples" class="tab">
				
					<p>QR Codes will automatically appear in the share box and stats pages. You can expand on this and place the codes on your custom pages, or even call them remotely.</p>
				
					<h3>Code Publishing Via U-SRV link</h3>
				
					<p>You can use a simple GET request to retrieve a code from U-SRV at the following address:</p>
				
					<div style="padding-left: 10pt;">
						<p><code>$base/srv/</code></p>
					</div>
				
					<p>With the following parameters:</p>
				
					<ul>
						<li>id => 'iqrcodes'</li>
						<li>key => &#36;key</li>
						<li>fn => &#36;fn</li>
					</ul>
				
					<h4>id</h4>
					<div style="padding-left: 10pt;">
						<p>U-SRV uses this id to know where to look for the actual QRCode images. This should always be the same.</p>
					</div>
				
					<h4>key</h4>
					<div style="padding-left: 10pt;">
						<p>A key is valid for, at most, one minute, and is determined by hashing a unique timestamp concatenated with the ID. The following PHP produces a valid key:</p>
					
						<div style="padding-left: 10pt;">
<pre>
&#36;now &#61; round&#40;time&#40;&#41;/60&#41;&#59;
&#36;key &#61; md5&#40;&#36;now &#46; &#39;iqrcodes&#39;&#41;&#59;
</pre>
						</div>				
						<p>Which gives the following live value:</p> 
						<div style="padding-left: 10pt;">
							<code>$key</code>
						</div>
					</div>
				
					<h4>fn</h4>
					<div style="padding-left: 10pt;">
						<p>The file name, determined by hashing the short url and prepending that with 'qrc_', can be accomplished with the follwoing PHP:</p>
						<div style="padding-left: 10pt;">				
<pre>&#36;fn &#61; &#39;qrc_&#39; &#46; md5&#40;$base&#47;&#36;var&#41; &#46; &#39;&#46;png&#39;&#59;</pre>
						</div>
						<p>If &#36;var &#61; &#39;V&#39;, the filename is:
						<div style="padding-left: 10pt;">					
							<code>$fn</code>.</p>
						</div>
					</div>
				
					<br>

					<p>In the context of a PHP file, the following code will utilize the values from above and fetch a QR Code:</p>
				
					<div style="padding-left: 10pt;">
<pre>&#60;img src&#61;&#34;$base&#47;srv&#47;&#63;id&#61;iqrcodes&#38;key&#61;<strong>&#36;key</strong>&#38;fn&#61;<strong>&#36;fn</strong>&#34; &#47;&#62;</pre>
					</div>
				
					<hr>
				
					<h3>Local only Javascript/PHP</h3>
				
					<p>QR Codes can be called locally via IQRCodes's javascript functions. Try putting something like the following in your public index.php file. This would be at line 67 in the <code>sample-public-front-page.txt</code> file in the YOURLS root:</p>
				
					<div style="padding-left: 10pt;">									
<pre>&#60;&#63;php if &#40;isset&#40;&#36;return&#91;&#39;qrimage&#39;&#93;&#41;&#41; echo &#36;return&#91;&#39;qrimage&#39;&#93;&#59; &#63;&#62;</pre>
		        		</div>
		        			
		       			<p>Or try the following example:</p>
		       			
					<div style="padding-left: 10pt;">					           			
<pre>&#60;img src&#61;&#34;&#60;&#63;php if &#40;isset&#40;&#36;return&#91;&#39;qrimage&#39;&#93;&#41;&#41; echo &#36;return&#91;&#39;qrimage&#39;&#93;&#59; &#63;&#62;&#34; alt&#61;&#34;QRCode&#34;&#62;</pre>
					</div>
				</div>
			</div>
		</div>

HTML;

}

//insert the JS and CSS files to head.
yourls_add_action( 'html_head', 'iqrcodes_js' );
function iqrcodes_js() {
	$opt = iqrcodes_get_opts();
	echo "<script type=\"text/javascript\">var iqrcodes_imagetype=\"".$opt[5]."\";</script>" ;
	echo "<script src=\"". yourls_plugin_url( dirname( __FILE__ ) ). "/assets/md5.min.js\" type=\"text/javascript\"></script>" ;
	echo "<script src=\"". yourls_plugin_url( dirname( __FILE__ ) ). "/assets/iqrcodes.js\" type=\"text/javascript\"></script>" ;
	echo "<link rel=\"stylesheet\" href=\"". yourls_plugin_url( dirname( __FILE__ ) ) . "/assets/iqrcodes.css\" type=\"text/css\" />";
	echo '<link rel="stylesheet" href="/css/infos.css" type="text/css" media="screen" />';
	echo '<script src="/js/infos.js" type="text/javascript"></script>';
}

// form handler
function iqrcodes_form_0() {
	if( isset( $_POST['iqrcodes_cache_loc'] ) ) {
	
		yourls_verify_nonce( 'iqrcodes' );
		
		$pcloc = $_POST['iqrcodes_cache_loc'];
		$ocloc = yourls_get_option( 'iqrcodes_cache_loc' );
		
		if ($pcloc !== $ocloc ) {
			if ($ocloc == null ) {
				iqrcodes_mkdir( $pcloc );
				yourls_update_option( 'iqrcodes_cache_loc', $pcloc);
			} else {
			iqrcodes_mvdir( $ocloc , $pcloc );
			yourls_update_option( 'iqrcodes_cache_loc', $pcloc );
			}
		}
		
		if(isset($_POST['iqrcodes_EC'])) yourls_update_option( 'iqrcodes_EC', $_POST['iqrcodes_EC'] );
		if(isset($_POST['iqrcodes_img_size'])) yourls_update_option( 'iqrcodes_img_size', $_POST['iqrcodes_img_size'] );
		if(isset($_POST['iqrcodes_border_size'])) yourls_update_option( 'iqrcodes_border_size', $_POST['iqrcodes_border_size'] );
		if(isset($_POST['iqrcodes_afterlife'])) yourls_update_option( 'iqrcodes_afterlife', $_POST['iqrcodes_afterlife'] );
		if(isset($_POST['iqrcodes_imagetype'])) yourls_update_option( 'iqrcodes_imagetype', $_POST['iqrcodes_imagetype'] );
		if(isset($_POST['iqrcodes_logo_scale'])) yourls_update_option( 'iqrcodes_logo_scale', $_POST['iqrcodes_logo_scale'] );
		if(isset($_POST['iqrcodes_logo_position'])) yourls_update_option( 'iqrcodes_logo_position', $_POST['iqrcodes_logo_position'] );
		if(isset($_FILES['iqrcodes_logo_file'])&& in_array($_FILES['iqrcodes_logo_file']['type'], array("image/jpeg", "image/svg+xml", "image/png"))) {
			$path_parts = pathinfo($_FILES['iqrcodes_logo_file']['name']);
			move_uploaded_file($_FILES['iqrcodes_logo_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/user/plugins/iqrcodes/logo.".$path_parts['extension']);
		}
	}
}

// option handler
function iqrcodes_get_opts() {

	// Check DB
	$QRC_DIR 	= yourls_get_option('iqrcodes_cache_loc');
	$EC 		= yourls_get_option('iqrcodes_EC');
	$img_size 	= yourls_get_option('iqrcodes_img_size');
	$bdr_size 	= yourls_get_option('iqrcodes_border_size');
	$afterlife	= yourls_get_option('iqrcodes_afterlife');
	$imagetype	= yourls_get_option('iqrcodes_imagetype');
	$logo_scale	= yourls_get_option('iqrcodes_logo_scale');
	$logo_position	= yourls_get_option('iqrcodes_logo_position');
	
	// Set defaults
	if ($QRC_DIR 	== null) $QRC_DIR   = dirname(__FILE__).'/cache/qr';
	if ($EC 	== null) $EC_LVL    = 'H';
	if ($img_size 	== null) $img_size  = '5';			// 165 X 165 (10 = 330 X 330)
	if ($bdr_size 	== null) $bdr_size  = '2';
	if ($afterlife  == null) $afterlife = 'preserve';
	if ($imagetype	== null) $imagetype = 'png';
	if ($logo_scale	== null) $logo_scale = '0.25';
	if ($logo_position== null) $logo_position = 'center';
	
	// Return values
	return array(
		$QRC_DIR,						// opt[0]
		$EC,							// opt[1]
		$img_size,						// opt[2]
		$bdr_size,						// opt[3]
		$afterlife,						// opt[4]
		$imagetype,						// opt[5]
		$logo_scale,						// opt[6]
		$logo_position,						// opt[7]
	);
}

// Get key
function iqrcodes_key() {
	$now = round(time()/60);
	$key = md5($now . 'iqrcodes');
	return $key;
}

//Generate QR Code for shorturls generated before plugin installation.
yourls_add_filter( 'share_box_data', 'iqrcodes_sharebox' );
function iqrcodes_sharebox( $data ) {

	$shorturl = $data['shorturl'];
        $opt  = iqrcodes_get_opts();

	$base = YOURLS_SITE;
	$key = iqrcodes_key();
	
	iqrcodes_mkdir( $opt[0] );

	$filename = '/qrc_' . md5($shorturl) . "." . $opt[5];
 	$filepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $opt[0]. '/' . $filename;

	$imgname  = $base . '/srv/?id=iqrcodes&key=' . $key . '&fn=' . $filename;

	$data['qrcimg'] = $imgname;

	if ( !file_exists( $filepath ) )
		QRcode::$opt[5]( $shorturl, $filepath, $opt[1], $opt[2], $opt[3] );

	// required for direct call to yourls_add_new_link() which does not fire the javascript - lets do it manually
	$data['qrimage'] = "<script>iqrcodes('$imgname', '$base');</script>";
        return $data;
}

//Generate QRCode for new url added.
yourls_add_filter( 'add_new_link', 'iqrcodes_add_url' );
function iqrcodes_add_url( $data ) {
            
        $base = YOURLS_SITE;
        $key  = iqrcodes_key();
        $opt  = iqrcodes_get_opts();
        
	$shorturl = $data['shorturl'];
	
	iqrcodes_mkdir( $opt[0] );

	$filename = 'qrc_'. md5($shorturl) . "." . $opt[5];
	$filepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $opt[0]. '/' . $filename;
	
	$imgname  = $base . '/srv/?id=iqrcodes&key=' . $key . '&fn=' . $filename;
	
	$data['qrcimg'] = $imgname;
	
	QRcode::$opt[5]( $shorturl, $filepath, $opt[1], $opt[2], $opt[3] );
	
	$data['html'] .= "<script>iqrcodes( '$imgname' , '$base' );</script>";
	
	// required for direct call to yourls_add_new_link() which does not fire the javascript - lets do it manually
	$data['qrimage'] = "<script>iqrcodes( '$imgname' , '$base' );</script>";
						
	return $data;
}


//Generate new QRCode when the shorturl is edited.
yourls_add_filter ( 'pre_edit_link' , 'iqrcodes_edit_url' );
function iqrcodes_edit_url( $data ) {
		
	$oldkeyword = $data[1];
	$newkeyword = $data[2];
	
        $opt  = iqrcodes_get_opts();
	iqrcodes_mkdir( $opt[0] );
	
        $base = YOURLS_SITE;

	$oldfilepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $opt[0] . 'qrc_' . md5($base . '/' . $oldkeyword) . "." . $opt[5];
	
	if ( file_exists( $oldfilepath ))
		unlink( $oldfilepath );
	
	$newfilename = 'qrc_' . md5($base . '/' . $newkeyword) . "." . $opt[5];
	$newfilepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $opt[0] . $newfilename;
	
        $key  = iqrcodes_key();
	$imgname  = $base . '/srv/?id=iqrcodes&key=' . $key . '&fn=' . $newfilename;

	$data['qrcimg'] = $imgname;
	
	QRcode::$opt[5]( $base . '/' . $newkeyword, $newfilepath,  $opt[1], $opt[2], $opt[3] );
	
	return $data;
}

// Delete the QRCode when url is deleted.
yourls_add_action ( 'delete_link' , 'iqrcodes_delete_url' );
function iqrcodes_delete_url( $data ) {

	$keyword = $data[0];
        $opt  = iqrcodes_get_opts();
	
	$filename = 'qrc_' . md5(YOURLS_SITE . '/' . $keyword) . "." . $opt[5];
	$filepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $opt[0]. '/' . $filename;

	if ( file_exists( $filepath ))
		unlink( $filepath );		
		
	return $data;
}

// Craete cache on enable
yourls_add_action('activated_iqrcodes/plugin.php', 'iqrcodes_activate');
function iqrcodes_activate() {

	$opt = iqrcodes_get_opts();
	iqrcodes_mkdir( $opt[0] );
}

// purge cache on disable
yourls_add_action('deactivated_iqrcodes/plugin.php', 'iqrcodes_deactivate');
function iqrcodes_deactivate() {

	$opt = iqrcodes_get_opts();
	$dir = $_SERVER['DOCUMENT_ROOT'] . '/' . $opt[0] . '/';
	
	if($opt[4] == 'delete') {
		if (file_exists($dir)) {
			foreach (new DirectoryIterator($dir) as $fileInfo) {
				if ($fileInfo->isDot()) {
				continue;
			    	}
				unlink($fileInfo->getRealPath());
			}
		}
	}
}

// Make dir if null
function iqrcodes_mkdir( $new ) {	

	$new = $_SERVER['DOCUMENT_ROOT'] . '/' . $new . '/';
	if ( !file_exists( $new ) ) {
		mkdir( $new );
		chmod( $new, 0777 );
	}
	else
		return;
}

// Move directory if option is updated
function iqrcodes_mvdir( $old , $new ) {

	$old = $_SERVER['DOCUMENT_ROOT'] . '/' . $old . '/';
	$new = $_SERVER['DOCUMENT_ROOT'] . '/' . $new . '/';
	
	if ( !file_exists( $old ) || $old == null ) {
		snapshot_cache_mkdir( $new );
	} else { 
		if ( !file_exists( $new ) ) {
			rename( $old , $new );
			chmod( $new, 0777 );
		}
		else
			return;
	}
}

function checkSelected($var="foo", $item="foo") {
	if($var==$item) {
		echo " selected=\"selected\"";
	}
}
