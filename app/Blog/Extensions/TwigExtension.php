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
            new \Twig_SimpleFunction('getPostArchive', array($this, 'getPostArchiveNavigation')),
            new \Twig_SimpleFunction('theme', array($this, 'getThemeName')),
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
}
