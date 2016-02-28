<?php
/**
 * Default Configuration Settings
 *
 * Define all server specific settings in config.local.php.
 *
 * Do not commit config.local.php to version control!
 */

/**
 * Production config controls debug and environment mode.
 */
$config['production'] = true;

/**
 * Default Domain
 * Note: Do not include a trailing slash
 */
// $config['baseurl'] = '';

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
$config['email']['protocol'] = 'smtp';
$config['email']['smtp_host'] = 'localhost';
$config['email']['smtp_port'] = '';
$config['email']['smtp_user'] = '';
$config['email']['smtp_pass'] = '';
$config['email']['mailtype'] = 'html';

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
 * Routing Options
 */
// $config['routes.case_sensitive'] = false;

/**
 * Social Authentication Options
 */
$config['auth.facebook']['app_id'] = '';
$config['auth.facebook']['app_secret'] = '';
$config['auth.facebook']['default_graph_version'] = 'v2.0';
$config['auth.google']['client_id'] = '';
$config['auth.google']['client_secret'] = '';
