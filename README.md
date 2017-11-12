# U-SRV
Universal file server for YOURLS plugin development

Provides links to files while obfuscating filesystem paths, allowing easy, secure access to files between plugins.

## In detail:
Given the following parameters:

1. key => Access key
2. id  => plugin ID
3. fn  => filename

This script will:

1. Retrieve the file store location per plugin, can be from a database
2. Retrieve a particular file from the store (in or out of the server doc root)
3. Return a time limited link to that file

## Use (with YOURLS)
1. Copy or link `srv.php` to `/path/to/YOURLS/pages/`
2. Call U-SRV with a `GET` request from `https://sho.rt/srv/`

### Parameters
To be sent as a `GET` request. All parameters are required.
		- 	eg. `https://sho.rt/srv/?id=ID_VALUE&key=KEY_VALUE&fn=FILE_NAME_VALUE;

#### Access Key
The url's created with this script live for a maximum of 1 minute. This helps prevent unwanted hotlinking, etc.  
To set up your own access key just add something equivalent to the following to your plugin or script:

```
<?php
$now = date("YmdGi");
$id = 'My_Fancy_Plugin';
$key = md5($now . $id);
?>
```
U-SRV will also set a cookie for use with javascript, etc. as  
`$cname = "usrv_" . $id;`  
where `$cname` is the name of the cookie.

#### ID
Add a new case with an arbitrary ID and file store location to the ID section of `srv.php`. Send this same ID with the `GET` request.

* In this example the store location is retrieved from a database as a typical `YOURLS` option:
```
case 'ID_VALUE':
	$path = yourls_get_option('YOUR_CACHE_PATH');
	break;
```
* In this example the store filepath is set explicitly:
```
case 'ID_VALUE':
	$path = '/path/to/your/files/');
	break;
```

#### File info
The filename is set explicitly as a regular `GET` value.  
Mime types must be set expllicitly in the script in order to set header information correctly, and are restricted as an extra security measure. 
To add a new filetype, just add a new case to the Mime Types section of `srv.php`, check MIMETYPES.md for an exhaustive (?) list of examples.
* In order to allow the passing of a `tar.gz` file, add the following:
		- 'case "gz": $ctype="application/x-gzip"; break;`

### NOTES:
* U-SRV is pre-configured for the default filetypes used by the YOURLS [IQRCodes](https://github.com/joshp23/YOURLS-IQRCodes) and [Snapshot Visual Preview](https://github.com/joshp23/YOURLS-Snapshot) plugins. 
* As should be obviouse, this script can be easily modified for use outside of the YOURLS environment.

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

