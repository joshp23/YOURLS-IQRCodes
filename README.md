# YOURLS-IQRCodes
YOURLS Integrated QRCodes plugin with exposed options and full integration

This is an updated fork of [Inline QRCode](http://techlister.com/plugins-2/qrcode-plugin-for-yourls/354/) which is more compact, configurable, and just as efficient with more features.

## Features
### Old
* QRCodes are generated and cached for every new short url
* New QRCode is generated when the user edits the short url
* Cached QRCode is deleted when the user deletes an url
* Displays QRCode within the sharebox, whenever the sharebox is displayed
* ~~QRCodes generated at 165 x 165 pixels.~~ __updated__
* Codes are generated from standalone php QRCode library from Sourceforge.net
  * No calls to google!
* QRCodes can be generated for pre-existing shorturls by visiting stats page

### New
* All options are available in the admin interface, no file editing
* You can now generate codes of varying sizes, with varying degrees of ECC, and with varying border sizes
* Image storage location is configurable to allow easier qrcode exposure to other modules
* Auto-delete or preserve codes on plugin deactivation
* Code links are served using a new secure system, U-SRV, that allows greater integration
* Plenty of well documented, practical examples in the options page to help get started with integration
* Updated and minimized md5.js
* streamlined version of the QR Code generation library
* almost half the total size in bytes
  * This can be reduced in half yet again by disabling and deleting the PHP QR Code cache, which was left in for enhanced performance.This setting can be found on lnie 100 of `assets/phpqrcode.php`
  
## Installation
1. Download this repo and extract the `iqrcodes` folder to `YOURLS/user/plugins/`
2. Set permissions
3. Enable module, default config works fine, or visit IQRCodes page to fine tune.
4. Have fun!

## Credits
[Inline QRcode](http://techlister.com/plugins-2/qrcode-plugin-for-yourls/354/) by Savoul Pelister is the base of this fork

[PHP QR Code](http://phpqrcode.sourceforge.net/) by Dominik Dzienia (aka deltalab) generates the actual QR Codes

[JavaScript MD5](https://blueimp.github.io/JavaScript-MD5/) by Sebastian Tschan (aka BlueImp) hashes the filenames in js

#### DISCLAIMER:
* This plugin is offered "as is", and may or may not work for you. Give it a try, and have fun!

===========================

    Copyright (C) 2016 Josh Panter

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

