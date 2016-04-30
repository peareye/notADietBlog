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

    // Relative URI to uploaded image
    protected $relativeFileUri = '';

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

        // Add the extension to the filename, and form new file URI
        $this->imageFileName .= ".{$ext}";
        $this->relativeFileUri .= $this->imageFileName;

        // Save to new directory
        $file->moveTo("{$this->uploadFilePath}/{$this->imageFileName}");

        // Unset this file
        unset($file);

        return true;
    }

    /**
     * Uploaded File URI
     *
     * @return string
     */
    public function getUploadedFileUri()
    {
        return $this->relativeFileUri;
    }

    /**
     * Create Directory Path
     *
     * Defines the custom directory path based on the record ID
     */
    protected function makeImagePath()
    {
        // Create image path, nesting folders by splitting the record ID
        $subFolder = substr($this->imageFileName, 0, 2);
        $this->relativeFileUri .= $subFolder . '/';
        $this->uploadFilePath = $this->uploadFilePathRoot . $subFolder;

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
