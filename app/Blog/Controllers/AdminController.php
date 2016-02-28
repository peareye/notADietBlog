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
}
