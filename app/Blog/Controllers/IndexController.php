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
        // Get dependencies
        $postMapper = $this->container->get('postMapper');
        $pagination = $this->container->get('pagination');

        // Get the page number and setup pagination
        $pageNumber = ($this->container->request->getParam('page')) ?: 1;
        $pagination->setPagePath($this->container->router->pathFor('home'));
        $pagination->setCurrentPageNumber($pageNumber);

        // Fetch posts with limit and offset
        $posts = $postMapper->getPosts($pagination->getRowsPerPage(), $pagination->getOffset());

        // Get total row count and add extension
        $pagination->setTotalRowsFound($postMapper->foundRows());
        $this->container->view->addExtension($pagination);

        // Render view
        $this->container->view->render($response, 'home.html', ['posts' => $posts]);
    }

    /**
     * View Post
     */
    public function viewPost($request, $response, $args)
    {
        $postMapper = $this->container['postMapper'];
        $post = $postMapper->getSinglePost($args['url']);

        // Was anything found?
        if (empty($post)) {
            return $this->notFound($request, $response);
        }

        // Make sure we have a template set
        $template = (!$post->template) ? 'post.html' : $post->template;

        $this->container->view->render($response, $template, ['post' => $post, 'metaDescription' => $post->meta_description]);
    }

    /**
     * Submit Contact Message
     */
    public function submitMessage($request, $response, $args)
    {
        // Have we caught a fly in our honey pot?
        if ($request->getParsedBodyParam('altemail') !== '') {
            // Redirect to thank you and stop there
            $log = $this->container->get('logger');
            $log->info('Contact honey pot caught a fly');
            return $response->withRedirect($this->container->router->pathFor('thankYou'));
        }

        // Get dependencies
        $params = $request->getParsedBody();
        $message = $this->container->get('mailMessage');
        $mailer = $this->container->get('sendMailMessage');
        $config = $this->container->get('settings');

        // Create message
        $message->setFrom("My Blog <{$config['email']['username']}>")
            ->addTo($config['user']['email'])
            ->setSubject('A message from your blog')
            ->setBody("Name: {$params['name']}\nEmail: {$params['email']}\nURL: {$params['url']}\n\n{$params['message']}");

        // Send
        $mailer->send($message);

        // Redirect to thank you
        return $response->withRedirect($this->container->router->pathFor('thankYou'));
    }

    /**
     * Contact Thank You
     */
    public function contactThankYou($request, $response, $args)
    {
        return $this->container->view->render($response, 'thankYou.html');
    }
}
