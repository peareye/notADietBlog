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
        $posts = $postMapper->getPosts(null, null, false);

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
     * Save Post
     */
    public function savePost($request, $response, $args)
    {
        // Get dependencies
        $toolbox = $this->container['toolbox'];
        $postMapper = $this->container['postMapper'];
        $sessionHandler = $this->container['sessionHandler'];
        $body = $request->getParsedBody();

        // Make blog post object
        $post = $postMapper->make();

        // Validate data (simple, add validation class later)
        if (empty($body['title']) || empty($body['url'])) {
            // Save to session data for redisplay
            $sessionHandler->setData(['postFormData' => $body]);
            return $response->withRedirect($router->pathFor('editPost'));
        }

        // If this is a previously published post, use that publish date as default
        $publishedDate = isset($body['published_date']) ? $body['published_date'] : '';
        if ($body['button'] === 'publish' && empty($publishedDate)) {
            // Then default to today
            $date = new \DateTime();
            $publishedDate = $date->format('Y-m-d');
        }

        // Assign data
        $post->id = $body['id'];
        $post->title = $body['title'];
        $post->url = $body['url']; // Should have been converted when title was edited in page
        $post->content = $body['content'];

        // Create post excerpt
        $post->content_excerpt = $toolbox->truncateHtmlText($post->content);

        // Only set the publish date if not empty
        if (!empty($publishedDate)) {
            $post->published_date = $publishedDate;
        }

        // Save
        $postMapper->save($post);

        // Display admin dashboard
        return $response->withRedirect($this->container->router->pathFor('adminDashboard'));
    }

    /**
     * Delete Blog Post
     */
    public function deletePost($request, $response, $args)
    {
        // Get dependencies
        $postMapper = $this->container['postMapper'];

        $post = $postMapper->make();
        $post->id = (int) $args['id'];

        $postMapper->delete($post);

        return $response->withRedirect($this->container->router->pathFor('adminDashboard'));
    }

    /**
     * Unpublish Blog Post
     */
    public function unpublishPost($request, $response, $args)
    {
        // Get dependencies
        $postMapper = $this->container['postMapper'];

        $post = $postMapper->make();
        $post->id = (int) $args['id'];
        $post->published_date = '';

        $postMapper->save($post);

        return $response->withRedirect($this->container->router->pathFor('adminDashboard'));
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
