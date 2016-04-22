<?php
/**
 * Image Uploader Class
 *
 */
namespace Blog\Library;

use \Exception;

class ImageUploader
{
    // Uploded files object
    protected $uploadedFiles;

    // Path to root of image uploads
    protected $uploadFilePathRoot;

    // Path to custom directory
    protected $uploadFilePath;

    // Filename to save
    public $imageFileName;

    /**
     * Constructor
     *
     * @param array $uploadedfiles Array of Slim\Http\UploadedFile objects
     * @param array $config Array of configuration items
     */
    public function __construct(array $uploadedFiles, array $config)
    {
        $this->uploadedFiles = $uploadedFiles;
        $this->uploadFilePathRoot = $config['filePath'];
    }

    /**
     * Create Directory Path
     *
     * Defines the custom directory path based on the record ID
     */
    protected function makeImagePath()
    {
        // Create image path, nesting folders by splitting the record ID
        $this->uploadFilePath = $this->uploadFilePathRoot . substr($this->imageFileName, 0, 2);

        // Create the path if the directory does not exist
        if (!is_dir($this->uploadFilePath)) {
            try {
                mkdir($this->uploadFilePath, 0775, true);
            } catch (Exception $e) {
                throw new Exception('Failed to create image directory path');
            }
        }

        return;
    }

    /**
     * Upload Images
     *
     * Upload specific image from $_FILES array
     * @param string, array key for image
     * @return boolean, true on success or false on failure
     */
    public function upload($imageKeyName)
    {
        if (empty($this->uploadedFiles[$imageKeyName])) {
            throw new Exception('Upload image key not found in uploadedFiles');
        }

        $file = $this->uploadedFiles[$imageKeyName];

        if ($file->getError() !== UPLOAD_ERR_OK) {
            return false;
        }

        // Get file name and extension
        $uploadFileName = $file->getClientFilename();
        $ext = strtolower(pathinfo($uploadFileName, PATHINFO_EXTENSION));

        // Generate new file name
        $this->newFilename($uploadFileName);

        // Attempt to create new directory based on filename
        $this->makeImagePath();

        // Save to new directory
        $file->moveTo("{$this->uploadFilePath}/{$this->imageFileName}.{$ext}");

        // Unset this file
        unset($file);

        return true;
    }

    /**
     * Make Filename
     *
     * Generates new filename
     * @param string $oldName Current filename
     * @return string
     */
    protected function newFilename($oldName)
    {
        $this->imageFileName = uniqid(mt_rand());
    }
}
