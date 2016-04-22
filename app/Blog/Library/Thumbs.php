<?php
/**
 * Create Thumbnails
 *
 * *** This script is not part of the regular application flow. ***
 *
 * If a thumbnail is not found the web/.htacces passes the request to
 * this script which then makes the new thumbnail on the fly. The next time the same thumbnail is
 * requested, the web server can return the existing thumbnail.
 *
 * Thumbnail URI requests must be of this form:
 * /files/thumbs/fi/filename/WxH/filename.jpg
 */

// Set encoding
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Load the Composer Autoloader
require_once ROOT_DIR . 'vendor/autoload.php';

// Load default and local configuration settings
require ROOT_DIR . 'config/config.default.php';
require ROOT_DIR . 'config/config.local.php';

// Set error reporting level
if ($config['production'] === true) {
    // Production
    ini_set('display_errors', 'Off');
    error_reporting(0);
    $config['displayErrorDetails'] = false;
} else {
    // Development
    error_reporting(-1);
    $config['displayErrorDetails'] = true;
}

// Now create the application
$app = new Slim\App(['settings' => $config]);

require ROOT_DIR . 'config/dependencies.php';

// Thumbnail route, if not matched a 404 is returned
$thumbPath = $config['image']['fileThumbUri'];

$app->get("/$thumbPath{dims}/{im}/{imageName}", function ($request, $response, $args) {

    // Get paths
    $imageConfig = $this->get('settings')['image'];
    $originalImagePathRoot = $imageConfig['filePath'];
    $thumbImagePathRoot = $imageConfig['fileThumbPath'];

    // Does the original file exist?
    $originalImagePath = "$originalImagePathRoot{$args['im']}/{$args['imageName']}";

    if (!file_exists($originalImagePath)) {
        // Original file does not exist so stop and return 404
        return $response->withStatus(404);
    }

    // Parse the requested dimensions
    preg_match('/([0-9]*)x([0-9]*)/i', $args['dims'], $dimArray);
    $width = $dimArray[1];
    $height = $dimArray[2];

    // Create new directory for this size if it does not exist
    $thumbSizeDirectory = "$thumbImagePathRoot{$args['dims']}/{$args['im']}";

    // Make directories
    if (!file_exists($thumbSizeDirectory)) {
        if (!mkdir($thumbSizeDirectory, 0755, true)) {
            return $response->withStatus(520);
        }
    }

    // Add file name
    $thumbFilePath = $thumbSizeDirectory . '/' . $args['imageName'];

    // Create an Image Manipulator instance
    $manager = $this['imageManipulator'];
    $image = $manager->make($originalImagePath);

    // Check how to resize
    if (is_numeric($width) && is_numeric($height)) {
        // If both dimensions are set, crop and resise to requested aspect ratio but do not upsize
        $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        });
    } elseif (is_numeric($width) && empty($height)) {
        // If just a width is provided, widen proportionately
        $image->widen($width);
    } elseif (empty($width) && is_numeric($height)) {
        // If just a height is provided, heighten proportionately
        $image->heighten($height);
    }

    // Now save resized image to thumbnail path
    $image->save($thumbFilePath);

    // Send resized image stream to avoid 404 on first display
    $newThumbnail = $response->withStatus(200)->withHeader('Content-Type', null);
    return $newThumbnail->write($image->response('jpg'));
});

$app->run();
