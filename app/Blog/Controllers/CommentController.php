<?php
/**
 * Comment Controller
 */
namespace Blog\Controllers;

class CommentController extends BaseController
{
    /**
     * Show Comments
     */
    public function showAll($request, $response, $args)
    {
        // Get dependencies
        $commentMapper = $this->container['commentMapper'];

        // Get all comments, approved or not
        $comments = $commentMapper->getAllComments();

        return $this->container->view->render($response, '@admin/comments.html', ['comments' => $comments]);
    }

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

        // Set the response type and render thank you view
        $r = $response->withHeader('Content-Type', 'application/json');
        $source = $this->container->view->fetch('_thankYou.html');

        // Check honeypot for spammers
        if (!empty($request->getParsedBodyParam('altemail'))) {
            // Just return and say nothing
            return $r->write(json_encode(["status" => "1", "source" => "$source"]));
        }

        // Save comment
        $comment->reply_id = $request->getParsedBodyParam('reply_id');
        $comment->post_id = $request->getParsedBodyParam('post_id');
        $comment->name = $request->getParsedBodyParam('name');
        $comment->email = $request->getParsedBodyParam('email');
        $comment->comment = $request->getParsedBodyParam('comment');
        $commentMapper->save($comment);

        // Return
        return $r->write(json_encode(["status" => "1", "source" => "$source"]));
    }

    /**
     * Change Comment Status
     */
    public function changeCommentStatus($request, $response, $args)
    {
        // Get dependencies
        $commentMapper = $this->container['commentMapper'];
        $comment = $commentMapper->make();

        // Set value and save
        $comment->id = $args['commentId'];
        $comment->approved = $args['newStatus'];
        $commentMapper->save($comment);

        return $response->withRedirect($this->container->router->pathFor('comments'));
    }

    /**
     * Delete Comment
     */
    public function deleteComment($request, $response, $args)
    {
        // Get dependencies
        $commentMapper = $this->container['commentMapper'];
        $comment = $commentMapper->make();

        $comment->id = $args['commentId'];
        $commentMapper->delete($comment);

        return $response->withRedirect($this->container->router->pathFor('comments'));
    }
}
