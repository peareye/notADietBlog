<?php
/**
 * Login Controller
 */
namespace Blog\Controllers;

class LoginController extends BaseController
{
    /**
     * Login
     *
     * Request login token
     */
    public function login($request, $response, $args)
    {
        $this->container->view->render($response, 'login.html');
    }

    /**
     * Send Login Token
     *
     * Sends login link to user email defined in config
     */
    public function sendLoginToken($request, $response, $args)
    {
        // Get dependencies
        $body = $request->getParsedBody();
        $message = $this->container->get('mailMessage');
        $mailer = $this->container->get('sendMailMessage');
        $config = $this->container->get('settings');
        $session = $this->container->get('sessionHandler');

        // Does the supplied email match the one in config?
        if ($config['user']['email'] !== strtolower(trim($body['email']))) {
            // It does not, log and silently redirect to home
            $this->container->logger->alert('Failed login attempt: ' . $body['email']);
            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        // Save token and create link with token a
        $token = hash('sha256', time() . $config['session']['salt']);
        $session->setData(['loginToken' => $token, 'loginTokenExpires' => time() + 120]);
        $link = $request->getUri()->getHost() . $this->container->router->pathFor('processLoginToken', ['token' => $token]);

        // Send message
        $message->setFrom("My Blog <{$config['email']['username']}>")
            ->addTo($config['user']['email'])
            ->setSubject('Blog Login')
            ->setBody("Click to login\n\n http://{$link}");

        try {
            $mailer->send($message);
        } catch (\Exception $e) {
            $this->container->logger->error('Email exception: ' . $e->getMessage());
        }

        // Direct to home page
        return $response->withRedirect($this->container->router->pathFor('home'));
    }

    /**
     * Process Login Token
     */
    public function processLoginToken($request, $response, $args)
    {
        // Get dependencies
        $session = $this->container->get('sessionHandler');
        $savedToken = $session->getData('loginToken');
        $tokenExpires = $session->getData('loginTokenExpires');

        // Check expires time on token & token match
        if ($args['token'] !== $savedToken && time() > $tokenExpires) {
            // No good, direct home
            $this->container->logger->info('Invalid login token, supplied: ' . $args['token'] . ' saved: ' . $savedToken . ' time: ' . time() . ' expires: ' . $tokenExpires);

            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        // Set session
        $security = $this->container->get('securityHandler');
        $security->startAuthenticatedSession();

        // Delete token
        $session->unsetData('loginToken');
        $session->unsetData('loginTokenExpires');

        // Go to admin dashboard
        return $response->withRedirect($this->container->router->pathFor('adminDashboard'));
    }

    /**
     * Logout
     *
     * Destoys session
     */
    public function logout($request, $response, $args)
    {
        $session = $this->container->get('sessionHandler');
        $session->destroy();

        return $response->withRedirect($this->container->router->pathFor('home'));
    }
}
