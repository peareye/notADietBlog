<?php
/**
 * Image Upload Controller
 */
namespace Blog\Controllers;

class ImageController extends BaseController
{
    /**
     * Upload Image
     */
    public function uploadImage($request, $response, $args)
    {
        $image = $this->container->get('imageHandler');
        $path = $this->container->get('settings')['image']['file.path'];
        $newFileName =

        $newImage = $image->make($_FILES['new-image']['tmp_name']);
        $newImage->save(ROOT_DIR . 'web/files/originals/someimage.jpg');

        echo "DONE";
    }
}
