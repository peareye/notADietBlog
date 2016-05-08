<?php
/**
 * Application Routes
 */

//
// Private routes
//

$app->group("/{$app->getContainer()->get('settings')['route']['adminSegment']}", function () {

    // Validate unique post URL (Ajax request)
    $this->post('/validateurl', function ($request, $response, $args) {
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

    // Make sitemap
    $this->get('/updatesitmap', function ($request, $response, $args) {
        return (new Blog\Controllers\AdminController($this))->updateSitemap($request, $response, $args);
    })->setName('updateSitemap');

    // Upload file
    $this->post('/uploadfile', function ($request, $response, $args) {
        return (new Blog\Controllers\FileController($this))->uploadFile($request, $response, $args);
    })->setName('uploadFile');

    // Preview unpublished post
    $this->get('/previewpost/{url}', function ($request, $response, $args) {
        return (new Blog\Controllers\AdminController($this))->previewPost($request, $response, $args);
    })->setName('previewPost');

    // Load files into gallery (Ajax)
    $this->get('/loadfiles', function ($request, $response, $args) {
        return (new Blog\Controllers\FileController($this))->loadFiles($request, $response, $args);
    })->setName('loadImages');

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

// Logout
$app->get('/logout/', function ($request, $response, $args) {
    return (new Blog\Controllers\LoginController($this))->logout($request, $response, $args);
})->setName('logout');

// Sample HTML fragment for formatting hints
$app->get('/sample', function ($request, $response, $args) {
    return $this->view->render($response, 'sample.html');
});

// Submit comment
$app->post('/savecomment', function ($request, $response, $args) {
    return (new Blog\Controllers\CommentController($this))->save($request, $response, $args);
})->setName('commentSubmit');

// View post
$app->get('/post/{url}', function ($request, $response, $args) {
    return (new Blog\Controllers\IndexController($this))->viewPost($request, $response, $args);
})->setName('viewPost');

// Catch old WordPress routes and 301 redirect
$app->get('/{category}/{url}', function ($request, $response, $args) {
    // Get defined categories and try to match
    $wPCategories = $this->get('settings')['route']['wordPressCategories'];
    if (in_array($args['category'], $wPCategories)) {
        return $response->withRedirect($this->router->pathFor('viewPost', ['url' => $args['url']]), 301);
    }

    // Category not matched
    $notFound = $this->get('notFoundHandler');
    return $notFound($request, $response);
});

// Home page (last route, the default)
$app->get('/', function ($request, $response, $args) {
    return (new Blog\Controllers\IndexController($this))->home($request, $response, $args);
})->setName('home');
