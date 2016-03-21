<?php
/**
 * Default Configuration Settings
 *
 * Define all instance specific settings in config.local.php.
 */

/**
 * Production config controls debug and environment mode.
 */
$config['production'] = true;

/**
 * Default Domain
 * Note: Do not include a trailing slash
 */
$config['baseurl'] = '';

/**
 * Blog User
 *
 * Keep it simple for now, just one user is all we need
 */
$config['user']['user_id'] = 1;
$config['user']['email'] = '';

/**
 * Admin Segment
 */
$config['adminSegment'] = 'admin';

/**
 * Database Settings
 */
$config['database']['host'] = 'localhost';
$config['database']['dbname'] = '';
$config['database']['username'] = '';
$config['database']['password'] = '';

/**
 * Sessions
 */
$config['session']['cookieName'] = ''; // Name of the cookie
$config['session']['checkIpAddress'] = true;
$config['session']['checkUserAgent'] = true;
$config['session']['salt'] = ''; // Salt key to hash
$config['session']['secondsUntilExpiration'] = 7200; // 180 days (60*60*24*180)

/**
 * Email Connection
 */
$config['email']['host'] = '';
$config['email']['username'] = '';
$config['email']['password'] = '';

/**
 * File Uploads Config
 *
 * MimeType List => http://www.webmaster-toolkit.com/mime-types.shtml
 */
$config['image']['file.path'] = ROOT_DIR . 'web/files/originals/';
$config['image']['file.thumb.path'] = ROOT_DIR . 'web/files/thumbnails/';
$config['image']['file.uri'] = 'files/originals/';
$config['image']['file.thumb.uri'] = 'files/thumbnails/';
$config['image']['file.mimetypes'] = ['image/jpeg', 'image/pjpeg', 'image/png']; // Be sure to update /Thumbs.php with any new allowed extensions.
$config['image']['file.upload.max.size'] = '6M'; // Use "B", "K", M", or "G"

/**
 * Pagination Options
 */
$config['pagination']['rowsPerPage'] = 20;
$config['pagination']['numberOfLinks'] = 2;

/**
 * Social Authentication Options
 */
$config['auth.facebook']['app_id'] = '';
$config['auth.facebook']['app_secret'] = '';
$config['auth.facebook']['default_graph_version'] = 'v2.0';
$config['auth.google']['client_id'] = '';
$config['auth.google']['client_secret'] = '';
