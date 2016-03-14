<?php
/**
 * Not Found Handler
 *
 * Extends the Slim NotFound handler
 */
namespace Blog\Extensions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotFound extends \Slim\Handlers\NotFound
{
    /**
     * Twig View Handler
     */
    protected $view;

    /**
     * Monolog Logger
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param Slim\Views\Twig $view Slim Twig view handler
     */
    public function __construct(\Slim\Views\Twig $view, \Monolog\Logger $logger)
    {
        $this->view = $view;
        $this->logger = $logger;
    }

    /**
     * Invoke not found handler
     *
     * @param  ServerRequestInterface $request  The most recent Request object
     * @param  ResponseInterface      $response The most recent Response object
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        // Log request
        $path = $request->getUri()->getPath();
        $this->logger->info("Not Found (404): {$request->getMethod()} {$path}");

        // If request is for a file or image then just return and do no more
        if (preg_match('/^.*\.(jpg|jpeg|png|gif)$/i', $path)) {
            return;
        }

        // Return status 404 and template
        return parent::__invoke($request, $response);
    }

    /**
     * Return a response for text/html content not found
     *
     * @param  ServerRequestInterface $request  The most recent Request object
     * @param  ResponseInterface      $response The most recent Response object
     *
     * @return ResponseInterface
     */
    protected function renderHtmlNotFoundOutput(ServerRequestInterface $request, ResponseInterface $response)
    {
        // Render and return temmplate as string
        return $this->view->fetch('notFound.html');
    }
}
