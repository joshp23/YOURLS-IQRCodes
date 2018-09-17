# YOURLS-IQRCodes
YOURLS Integrated QRCodes plugin with exposed options and full integration

This is an updated fork of [Inline QRCode](http://techlister.com/plugins-2/qrcode-plugin-for-yourls/354/) which is more compact, configurable, and just as efficient with more features.

Updating to v2.0.0 + from the 1.x.x branch may cause some unexpected behavior. Deleting the cache and re-generating qr codes may be necessary. Note, attempting to install this before the required U-SRV will result in failure.

## Requires:
YOURLS 1.7.3 ready. Works with YOURLS 1.7.2
[U-SRV](https://github.com/joshp23/YOURLS-U-SRV) v2.0.0 +

## Features
### Old
* QRCodes are generated and cached for every new short url
* A new QRCode is generated when a short url is edited
* Cached QRCodes are deleted when its corresponding short url is deleted
* QRCodes are displayed within the sharebox whenever the sharebox is displayed
* QRCodes are generated for pre-existing shorturls when sharebox is displayed
* Codes are generated from a standalone php based QRCode library
  * No calls to google!

### New
* All options are available in the admin interface
  * Code size
  * Border width
  * ECC level
  * Image file type
  * Optional logo watermark (image preview, scale, location on QR Code)
  * Image cache location 
  * Auto-delete or preserve cache on plugin deactivation
* Scan the entire database at once and generate QR Codes for any short url that is found to be missing one
* Plenty of well documented, practical examples in the options page to help get started with integration
* Code links are served using U-SRV, a secure system allowing greater integration
* Updated and minimized md5.js
* Streamlined version of the QR Code generation library
* Almost 1/2 the size of its predecessor
  * This can halfed again by disabling and deleting the PHP QR Code cache, which was left in for enhanced performance. This setting can be found on lnie 100 of `assets/phpqrcode.php`

## Installation (Under Apache)
1. Download and install YOURLS and U-SRV. U-SRV will have created it's cache, within which will sit the IQRCodes's cache.
2. Download the [latest release](https://github.com/joshp23/YOURLS-IQRCodes/releases/latest) of this repo and extract the `iqrcodes` folder to `YOURLS/user/plugins/`
	- the following commands are run from `YOURLS` root folder. Eg, `/absolute/path/to/YOURLS`
3. Symlink or copy `qrchk.php` into the `pages` folder. Automation of this task is planned for a future release.
    - Symlink:  
	  `ln -s user/plugins/iqrcodes/assets/qrchk.php pages/qrchk.php`  
    - Copy:  
	  `cp user/plugins/iqrcodes/assets/qrchk.php pages/qrchk.php`
3. Set permissions and cache
    -  There needs to be two cache folders (relative to YOURLS root)
       -  `user/plugins/iqrcodes/cache`   
       is included with the plugin download
       -  `/path/to/U-SRV/cache/qr`   
       iqrcodes will attempt to create this
    - In case of failure just do somethign like the following (as root):
      -  `mkdir /PATH/TO/U-SRV/CACHE/qr`
      -  `chmod -R 777 /PATH/TO/U-SRV/CACHE`
      -  `chown -R www-data:www-data /PATH/TO/U-SRV/CACHE`
      -  `chown -R www-data:www-data /PATH/TO/YOURLS/user/plugins/iqrcodes`
4. Enable module, default config works fine, or visit IQRCodes page to fine tune.
5. Have fun!

### Hint:
Want to embed these QR codes into a worpress widget? Check out [this gist](https://gist.github.com/joshp23/3f990e6ec36e24ba53985968bbfa89f1)
### Note: 
If you are using YOURLS with Nginx and using [this](https://github.com/YOURLS/YOURLS/wiki/Nginx-configuration) directive, you may end up with [404's instead of images](https://github.com/joshp23/YOURLS-IQRCodes/issues/21#issuecomment-326797121). You may want to have a look at [this](https://github.com/YOURLS/YOURLS/issues/1715#issuecomment-326797015) comment and thread. 

If this becomes an issue, try changing
```
(try_files $uri $uri/ /yourls-loader.php;)
```
to
```
if (!-e $request_filename){ rewrite ^(.+)$ /yourls-loader.php?q=$1 last; }
```
## Credits
[Inline QRcode](http://techlister.com/plugins-2/qrcode-plugin-for-yourls/354/) by Savoul Pelister is the base of this fork

[PHP QR Code](http://phpqrcode.sourceforge.net/) by Dominik Dzienia (aka deltalab) generates the actual QR Codes

[JavaScript MD5](https://blueimp.github.io/JavaScript-MD5/) by Sebastian Tschan (aka BlueImp) hashes the filenames in js

===========================

    Copyright (C) 2016 - 2018 Josh Panter

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

