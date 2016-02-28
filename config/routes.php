<?php
/**
 * Application Routes
 */

//
// Private routes
//
$app->group('/pippi', function () {

    // Main dashboard
    $this->get('/dashboard', function ($request, $response, $args) {
        (new Blog\Controllers\AdminController($this))->dashboard($request, $response, $args);
    })->setName('adminDashboard');

    // Add or edit post
    $this->get('/editpost[/{id}]', function ($request, $response, $args) {
        (new Blog\Controllers\AdminController($this))->editPost($request, $response, $args);
    })->setName('editPost');
});

//
// Public routes
//

// Search
$app->get('/search', function ($request, $response, $args) {
    (new Blog\Controllers\IndexController($this))->searchLocations($request, $response, $args);
})->setName('searchLocations');

// Home page (last route, the default)
$app->get('/', function ($request, $response, $args) {
    (new Blog\Controllers\IndexController($this))->home($request, $response, $args);
})->setName('home');
