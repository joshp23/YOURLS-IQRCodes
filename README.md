# YOURLS-IQRCodes
YOURLS Integrated QRCodes plugin with exposed options and full integration

This is an updated fork of [Inline QRCode](http://techlister.com/plugins-2/qrcode-plugin-for-yourls/354/) which is more compact, configurable, and just as efficient with more features.

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
1. Download this repo and extract the `iqrcodes` folder to `YOURLS/user/plugins/`
2. Symlink `assets/srv.php` to `YOURLS/pages/srv.php`
3. Symlink `assets/qrchk.php` to `YOURLS/pages/qrchk.php`
4. Set permissions and cache
    -  There needs to be two folders called cache (from YOURLS root)
       -  `user/plugins/iqrcodes/cache`   
       included with the plugin download
       -  `user/cache/qr`   
       must be created
    - You just can do something like this, and iqrcodes will try to create the second cache for you:
       -  `chown -R www-data:www-data /PATH/TO/YOURLS`   
    - Or just do the following from YOURLS root:
      -  `mkdir user/cache/qr`
      -  `chown -R www-data:www-data user/cache`
      -  `chown -R www-data:www-data user/plugins/iqrcodes`
      -  `chmod -R 777 user/cache`
5. Enable module, default config works fine, or visit IQRCodes page to fine tune.
6. Have fun!

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

    Copyright (C) 2016 - 2017 Josh Panter

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

