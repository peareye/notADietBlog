<?php
/**
 * Comment Controller
 */
namespace Blog\Controllers;

class CommentController extends BaseController
{
    /**
     * Save Comment
     *
     * Using Ajax-y
     */
    public function save($request, $response, $args)
    {
        // Get dependencies
        $commentMapper = $this->container['commentMapper'];
        $comment = $commentMapper->make();

        // Check honeypot for spammers
        if (!empty($request->getParsedBodyParam('altemail'))) {
            // something...
        }

        // Valid email?

        // Save comment
        $comment->reply_id = $request->getParsedBodyParam('reply_id');
        $comment->post_id = $request->getParsedBodyParam('post_id');
        $comment->name = $request->getParsedBodyParam('name');
        $comment->email = $request->getParsedBodyParam('email');
        $comment->comment = $request->getParsedBodyParam('comment');
        $commentMapper->save($comment);

        // Set the response type and render thank you view
        $r = $response->withHeader('Content-Type', 'application/json');
        $source = $this->container->view->fetch('_thankYou.html');

        // Return
        return $r->write(json_encode(["status" => "1", "source" => "$source"]));
    }

    /**
     * Contact Thank You
     */
    public function contactThankYou($request, $response, $args)
    {
        return $this->container->view->render($response, 'thankYou.html');
    }
}
