# YOURLS-Integrated-QRCode
YOURLS QRCode plugin with exposed options and full integration

This is an updated fork of [Inline QRCode](http://techlister.com/plugins-2/qrcode-plugin-for-yourls/354/) which provides:

* an interface for newly exposed QR Code generation options 
* integration with other modules (Snapshot Preview)
* updated (and minimized) md5 js
* streamlined version of the QR Code generation library. 

This version is more compact, configurable, and just as efficient with more features.

## Features
### Old
* QRCode is generated and cached for every new short url
* New QRCode is generated when the user edits the short url
* Cached QRCode is deleted when the user deletes an url.
* Displays QRCode within the sharebox, whenever the sharebox is displayed.
* ~~QRCodes generated at 165 x 165 pixels.~~ __updated__
* Generated from standalone php QRCode library from Sourceforge.net.
* QRCodes can be generated for pre-existing shorturls by visiting stats page.

### New
* You can now generate codes of varying sizes, with varying degrees of EC, and with varying border sizes.
* Image storage location is configurable to allow easier qrcode exposure to other modules.
* All options are available in the admin interface, no file editing

## Installation
1. Download this repo and extract the `integrated-qrcode` folder to `YOURLS/user/plugins/`
2. Set up your image folder (suggest `YOURLS/cache/qr/` )
3. Set write permissions on that folder, and on `YOURLS/user/plugins/integrated-qrcode/cache/`
4. Enable module, default config is fine, or visit Integrated QR Code page to fine tune.

## Credits
[Inline QRcode](http://techlister.com/plugins-2/qrcode-plugin-for-yourls/354/) by Savoul Pelister is the base of this fork

[PHP QR Code](http://phpqrcode.sourceforge.net/) by Dominik Dzienia (aka deltalab) generates the actual QR Codes

[JavaScript MD5](https://blueimp.github.io/JavaScript-MD5/) by Sebastian Tschan (aka BlueImp) hashes the filenames in js
