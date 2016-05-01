<?php
/**
 * File Upload Controller
 */
namespace Blog\Controllers;

use \FilesystemIterator;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;

class FileController extends BaseController
{
    /**
     * Upload Image
     */
    public function uploadFile($request, $response, $args)
    {
        $image = $this->container->get('fileUploader');
        $status = $image->upload('new-image');
        $source = '';

        // Set the response type
        $r = $response->withHeader('Content-Type', 'application/json');

        // If successful, include thumbnail link and source
        if ($status) {
            $source = $this->container->view->fetch('@admin/_thumbWithLink.html', ['i' => $image->getUploadedFileUri()]);
        }

        return $r->write(json_encode(["status" => "$status", "source" => "$source"]));
    }

    /**
     * Load Images
     *
     * Sends over HTML structure of images to browse
     */
    public function loadFiles($request, $response, $args)
    {
        // Get images directory
        $imageDirectory = $this->container['settings']['file']['filePath'];

        // Traverse directory and get all objects to iterate over
        $paths = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $imageDirectory,
                FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS));

        // Loop over directory
        $images = [];
        foreach ($paths as $pathName => $fileInfo) {
            // Skip iteration if not a file, or if is a hidden system file
            if (!$fileInfo->isFile() || $fileInfo->getExtension() === 'DS_Store' || $fileInfo->getExtension() === 'gitkeep') {
                continue;
            }

            // Assign image paths
            $images[] = str_replace($imageDirectory, '', $fileInfo->getPathname());
        }

        return $this->container->view->render($response, '@admin/_imageModalGallery.html', ['images' => $images]);
    }
}
