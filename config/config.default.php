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
 * Theme Name
 *
 * This is the theme folder name
 */
$config['theme'] = 'default';

/**
 * Default Domain
 * Note: Do not include a trailing slash
 */
$config['baseUrl'] = '';

/**
 * Blog Settings
 *
 * Keep it simple for now, just one user is all we need
 */
$config['user']['user_id'] = 1;
$config['user']['email'] = '';
$config['user']['htmlTitle'] = '';
$config['user']['blogTitle'] = '';
$config['user']['blogSubTitle'] = '';
$config['user']['defaultMetaDescription'] = '';
$config['user']['sidebarAbout'] = '';
// $config['user']['sidebarRelatedLinks'][] = ['name' = 'Twitter', 'url' = 'https://twitter.com'];

/**
 * Admin Segment
 */
$config['adminSegment'] = '/admin';

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
 * Including trailing slash
 */
$config['file']['filePath'] = ROOT_DIR . 'web/media/';
$config['file']['fileThumbPath'] = ROOT_DIR . 'web/media/';
$config['file']['fileUri'] = 'media/';
$config['file']['fileThumbUri'] = 'media/';

/**
 * Pagination Options
 */
$config['pagination']['rowsPerPage'] = 10;
$config['pagination']['numberOfLinks'] = 2;
