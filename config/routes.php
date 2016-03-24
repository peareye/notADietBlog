<?php
/**
 * Application Routes
 */

//
// Private routes
//

$app->group("{$app->getContainer()->get('settings')['adminSegment']}", function () {

    // Validate unique post URL (Ajax request)
    $this->post('/api/validateurl', function ($request, $response, $args) {
        return (new Blog\Controllers\AdminController($this))->validateUniqueUrl($request, $response, $args);
    });

    // Main dashboard
    $this->get('/dashboard', function ($request, $response, $args) {
        return (new Blog\Controllers\AdminController($this))->dashboard($request, $response, $args);
    })->setName('adminDashboard');

    // Add or edit post
    $this->get('/editpost[/{id}]', function ($request, $response, $args) {
        return (new Blog\Controllers\AdminController($this))->editPost($request, $response, $args);
    })->setName('editPost');

    // Save post
    $this->post('/savepost', function ($request, $response, $args) {
        return (new Blog\Controllers\AdminController($this))->savePost($request, $response, $args);
    })->setName('savePost');

    // Delete post
    $this->get('/deletepost/{id}', function ($request, $response, $args) {
        return (new Blog\Controllers\AdminController($this))->deletePost($request, $response, $args);
    })->setName('deletePost');

    // Unpublish post
    $this->get('/unpublishpost/{id}', function ($request, $response, $args) {
        return (new Blog\Controllers\AdminController($this))->unpublishPost($request, $response, $args);
    })->setName('unpublishPost');

})->add(function ($request, $response, $next) {
    // Authentication
    $security = $this->get('securityHandler');

    if (!$security->authenticated()) {
        // Failed authentication, redirect away
        $response = $next($request, $response);
        return $response->withRedirect($this->router->pathFor('home'));
    }

    // Next call
    $response = $next($request, $response);

    return $response;
});

//
// Public routes
//

// Login - submit request for token
$app->get('/letmein', function ($request, $response, $args) {
    return (new Blog\Controllers\LoginController($this))->login($request, $response, $args);
})->setName('login');

// Send login token
$app->post('/sendlogintoken/', function ($request, $response, $args) {
    return (new Blog\Controllers\LoginController($this))->sendLoginToken($request, $response, $args);
})->setName('sendLoginToken');

// Accept login token and set session
$app->get('/logintoken/{token:[a-zA-Z0-9]{64}}', function ($request, $response, $args) {
    return (new Blog\Controllers\LoginController($this))->processLoginToken($request, $response, $args);
})->setName('processLoginToken');

// Sample HTML fragment for formatting hints
$app->get('/sample', function ($request, $response, $args) {
    return $this->view->render($response, 'sample.html');
});

// Submit contact message
$app->post('/sendmessage', function ($request, $response, $args) {
    return (new Blog\Controllers\IndexController($this))->submitMessage($request, $response, $args);
})->setName('contactSubmit');

// Contact thank you page
$app->get('/thank-you', function ($request, $response, $args) {
    return (new Blog\Controllers\IndexController($this))->contactThankYou($request, $response, $args);
})->setName('thankYou');

// Search
$app->get('/search', function ($request, $response, $args) {
    return (new Blog\Controllers\IndexController($this))->searchLocations($request, $response, $args);
})->setName('searchLocations');

// View post
$app->get('/post/{url}', function ($request, $response, $args) {
    return (new Blog\Controllers\IndexController($this))->viewPost($request, $response, $args);
})->setName('viewPost');

// Home page (last route, the default)
$app->get('/', function ($request, $response, $args) {
    return (new Blog\Controllers\IndexController($this))->home($request, $response, $args);
})->setName('home');
