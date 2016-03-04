<?php
/**
 * Index Controller
 */
namespace Blog\Controllers;

class AdminController extends BaseController
{
    /**
     * Get Home Page
     */
    public function dashboard($request, $response, $args)
    {
        $postMapper = $this->container['postMapper'];
        $posts = $postMapper->getPosts();

        $this->container->view->render($response, 'admin/dashboard.html', ['posts' => $posts]);
    }

    /**
     * Add/Edit Post
     */
    public function editPost($request, $response, $args)
    {
        $postMapper = $this->container['postMapper'];
        $post = $postMapper->findById($args['id']);

        $this->container->view->render($response, 'admin/editPost.html', ['post' => $post]);
    }

    /**
     * Validate Unique URL
     */
    public function validateUniqueUrl($request, $response, $args)
    {
        // Get toolbox to clean URL
        $toolbox = $this->container['toolbox'];
        $postMapper = $this->container['postMapper'];
        $title = $request->getParsedBodyParam('title');

        // Set the response type
        $r = $response->withHeader('Content-Type', 'application/json');

        // Prep title string
        $url = $toolbox->cleanUrl($title);

        // Check table to see if this URL exists
        $urlIsUnique = $postMapper->postUrlIsUnique($url);

        if ($urlIsUnique) {
            $status = 'success';
        } else {
            $status = 'fail';
        }

        return $r->write(json_encode(["status" => "$status", "url" => $url]));
    }
}
