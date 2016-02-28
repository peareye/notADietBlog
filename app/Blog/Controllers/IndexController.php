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

        $this->container->view->render($response, 'home.html');
    }
}
