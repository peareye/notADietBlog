<?php
/**
 * Custom Extensions for Twig
 */
namespace Blog\Extensions;

use Interop\Container\ContainerInterface;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var \Slim\Interfaces\RouterInterface
     */
    private $router;

    /**
     * @var string|\Slim\Http\Uri
     */
    private $uri;

    /**
     * @var Interop\Container\ContainerInterface
     */
    private $container;

    /**
     * Application Settings
     * @var array
     */
    private $settings = [];

    public function __construct(ContainerInterface $container)
    {
        $this->router = $container['router'];
        $this->uri = $container['request']->getUri();
        $this->container = $container;
        $this->settings = $container->get('settings');
    }

    // Identifer
    public function getName()
    {
        return 'blog';
    }

    /**
     * Register Global variables
     */
    public function getGlobals()
    {
        return [
            'setting' => $this->settings,
            'theme' => $this->getThemeName(),
        ];
    }

    /**
     * Register Custom Filters
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * Register Custom Functions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('pathFor', array($this, 'pathFor')),
            new \Twig_SimpleFunction('baseUrl', array($this, 'baseUrl')),
            new \Twig_SimpleFunction('basePath', array($this, 'getBasePath')),
            new \Twig_SimpleFunction('inUrl', array($this, 'isInUrl')),
            new \Twig_SimpleFunction('getPostArchive', array($this, 'getPostArchiveNavigation')),
            new \Twig_SimpleFunction('theme', array($this, 'getThemeName')),
            new \Twig_SimpleFunction('authenticated', array($this, 'authenticated')),
            new \Twig_SimpleFunction('imageSize', array($this, 'getImageSize')),
            new \Twig_SimpleFunction('postComments', array($this, 'getPostComments')),
            new \Twig_SimpleFunction('newCommentCount', array($this, 'getNewCommentCount')),
        ];
    }

    /**
     * Get Path for Named Route
     *
     * @param string $name Name of the route
     * @param array $data Associative array to assign to route segments
     * @param array $queryParams Query string parameters
     * @return string The desired route path without the domain, but does include the basePath
     */
    public function pathFor($name, $data = [], $queryParams = [])
    {
        return $this->router->pathFor($name, $data, $queryParams);
    }

    /**
     * Base URL
     *
     * Returns the base url including scheme, domain, port, and base path
     * @param none
     * @return string The base url
     */
    public function baseUrl()
    {
        if (is_string($this->uri)) {
            return $this->uri;
        }

        if (method_exists($this->uri, 'getBaseUrl')) {
            return $this->uri->getBaseUrl();
        }
    }

    /**
     * Base Path
     *
     * If the application is run from a directory below the project root
     * this will return the subdirectory path.
     * Use this instead of baseUrl to use relative URL's instead of absolute
     * @param void
     * @return string The base path segments
     */
    public function getBasePath()
    {
        if (method_exists($this->uri, 'getBasePath')) {
            return $this->uri->getBasePath();
        }
    }

    /**
     * In URL
     *
     * Checks if the supplied string is one of the URL segments
     * @param string $segment URL segment to find
     * @return boolean
     */
    public function isInUrl($segment = null)
    {
        // Verify we have a segment to find
        if ($segment === null) {
            return false;
        }

        // Clean segment of slashes
        $segment = trim($segment, '/');

        return in_array($segment, explode('/', $this->uri->getPath()));
    }

    /**
     * Get Post Archives
     */
    public function getPostArchiveNavigation()
    {
        // Get dependency and all posts
        $postMapper = $this->container->get('postMapper');
        $posts = $postMapper->getPosts();

        // Create array with posts nested by publish year and month
        $nav = [];
        foreach ($posts as $post) {
            $time = strtotime($post->published_date);
            $nav[date('Y', $time)][date('F', $time)][] = ['title' => $post->title, 'url' => $post->url];
        }

        return $nav;
    }

    /**
     * Get Theme Name
     *
     * @param void
     * @return string
     */
    public function getThemeName()
    {
        return ($this->settings['theme']) ?: 'default';
    }

    /**
     * Authenticated
     *
     * Is the user authenticated?
     * @return boolean
     */
    public function authenticated()
    {
        $security = $this->container->securityHandler;

        return $security->authenticated();
    }

    /**
     * Get Image Size
     *
     * @param string $path Path to image
     * @return array "width", "height", "aspect"
     */
    public function getImageSize($imagePath)
    {
        $filePath = $this->settings['file']['filePath'];
        $size = getimagesize($filePath . $imagePath);

        if (!$size || $size[1] === 0) {
            return null;
        }

        return ['width' => $size[0], 'height' => $size[1], 'aspect' => round($size[0] / $size[1], 4)];
    }

    /**
     * Get Post Comments
     *
     * Returns approved comments by post ID
     * @param int $postId
     * @return array
     */
    public function getPostComments($postId)
    {
        // Get dependencies and comments
        $commentMapper = $this->container->get('commentMapper');

        return $commentMapper->getPostComments($postId);
    }

    /**
     * Get New Comment Count
     *
     * @return int
     */
    public function getNewCommentCount()
    {
        $commentMapper = $this->container->get('commentMapper');

        return $commentMapper->getNewCommentCount();
    }
}
