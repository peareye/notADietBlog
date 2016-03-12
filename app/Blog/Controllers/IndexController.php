<?php
/**
 * Index Controller
 */
namespace Blog\Controllers;

class IndexController extends BaseController
{
    /**
     * Get Home Page
     */
    public function home($request, $response, $args)
    {
        $postMapper = $this->container['postMapper'];
        $posts = $postMapper->getPosts();

        $this->container->view->render($response, 'home.html', ['posts' => $posts]);
    }

    /**
     * View Post
     */
    public function viewPost($request, $response, $args)
    {
        $postMapper = $this->container['postMapper'];
        $post = $postMapper->getSinglePost($args['url']);

        $this->container->view->render($response, 'post.html', ['post' => $post]);
    }
}
