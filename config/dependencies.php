<?php
// DIC configuration

$container = $app->getContainer();

// Twig templates
$container['view'] = function ($c) {
    $view = new Slim\Views\Twig(ROOT_DIR . 'templates', [
        'cache' => ROOT_DIR . 'twigcache',
        'debug' => !$c->get('settings')['production'],
    ]);

    $view->addExtension(new Blog\Extensions\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));
    // $view->addExtension(new Blog\Extensions\PaginationExtension());

    if ($c->get('settings')['production'] === false) {
        $view->addExtension(new Twig_Extension_Debug());
    }

    return $view;
};

// Monolog logging
$container['logger'] = function ($c) {
    $logger = new Monolog\Logger('blog');
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler(ROOT_DIR . 'logs/' . date('Y-m-d') . '.log', Monolog\Logger::DEBUG));

    return $logger;
};

// Database connection
$container['database'] = function ($c) {
    $dbConfig = $c->get('settings')['database'];

    // Extra database options
    $dbConfig['options'][PDO::ATTR_PERSISTENT] = true;
    $dbConfig['options'][PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $dbConfig['options'][PDO::ATTR_EMULATE_PREPARES] = false;

    // Define connection string
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4";

    // Return connection
    return new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
};

// Custom error handling (overwrite Slim errorHandler to add logging)
$container['errorHandler'] = function ($c) {
    return new \Blog\Extensions\Error($c->get('settings')['displayErrorDetails'], $c['logger']);
};

// Sessions
$container['sessionHandler'] = function ($c) {
    return new WolfMoritz\Session\SessionHandler($c['database'], $c->get('settings')['session']);
};

// Load Toolbox
$container['toolbox'] = function ($c) {
    return new Blog\Library\Toolbox();
};

$container['postMapper'] = $container->factory(function ($c) {
    return new \Blog\Models\PostMapper($c['database'], $c['logger'], ['user_id' => 1]);
});

// Sitemap
// $app->sitemap = function () use ($app) {
//     return new Recipe\Library\SitemapHandler($app);
// };

// Image Uploader
// $app->ImageUploader = function () use ($app) {
//     return new Recipe\Library\ImageUploader($app->config('image'), $app->log);
// };

// Validation
// $app->Validation = function () {
//     return function ($data) {
//         return new Valitron\Validator($data);
//     };
// };

// Register 404 page
// $app->notFound(function () use ($app) {
//     // Log URL for not found request
//     $request = $app->request;
//     $serverVars = isset($_SERVER['HTTP_USER_AGENT']) ? ' [HTTP_USER_AGENT] ' . $_SERVER['HTTP_USER_AGENT'] : '';
//     $serverVars .= isset($_SERVER['REMOTE_ADDR']) ? ' [REMOTE_ADDR] ' . $_SERVER['REMOTE_ADDR'] : '';
//     $app->log->error('404 Not Found: ' . $request->getMethod() . ' ' . $request->getResourceUri() . $serverVars);

//     // If request is for a file image then just return
//     if (preg_match('/^.*\.(jpg|jpeg|png|gif)$/i', $request->getResourceUri())) {
//         return;
//     }

//     // Render 404 page
//     $app->twig->display('notFound.html');
// });
